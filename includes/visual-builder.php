<?php

declare(strict_types=1);

/**
 * Visual Builder - renderer helpers for the public website.
 *
 * How it works:
 *  - A public page calls vb_begin('home') before its content and vb_end() after it.
 *  - Parts of the page that must keep coming from the database are wrapped with
 *    vb_dyn_open() / vb_dyn_close(). Their rendered HTML is captured every request.
 *  - If an admin has PUBLISHED a design for that page, vb_end() prints the published
 *    HTML instead of the original markup and re-inserts the live dynamic parts.
 *  - On ANY problem (missing table, unsafe markup, exception) the original page is printed,
 *    so the public website can never be broken by the builder.
 */

/** Pages whose public file calls vb_begin()/vb_end(): page_key => public file. */
function vb_publishable_pages(): array
{
    return [
        'home' => 'index.php',
        'profil' => 'profil.php',
        'ppdb' => 'ppdb.php',
        'kontak' => 'kontak.php',
        'ekstrakurikuler' => 'ekstrakurikuler.php',
        'mitra' => 'mitra.php',
        'magang' => 'magang.php',
        'pkl' => 'pkl.php',
        'bkk' => 'bkk.php',
        'jurusan' => 'jurusan.php',
        'jurusan-rpl' => 'jurusan.php',
        'jurusan-tkj' => 'jurusan.php',
        'jurusan-dkv' => 'jurusan.php',
        'jurusan-pspt' => 'jurusan.php',
        'guru' => 'guru.php',
        'berita' => 'berita.php',
        'prestasi' => 'prestasi.php',
        'fasilitas' => 'fasilitas.php',
        'galeri' => 'galeri.php',
        'kunjungan-industri' => 'kunjungan-industri.php',
    ];
}

/** Dynamic blocks that must always exist on the page (re-added if the editor removed them). */
const VB_REQUIRED_DYNAMIC = ['video-modal'];

function vb_is_admin(): bool
{
    try {
        if (!function_exists('is_logged_in') || !is_logged_in()) {
            return false;
        }

        $user = current_user();

        return ($user['role'] ?? '') === 'ADMIN';
    } catch (Throwable) {
        return false;
    }
}

/** True when the markup contains something we refuse to store or publish. */
function vb_unsafe_markup(string $html, string $css = ''): bool
{
    if (preg_match('/<\s*script\b|<\s*style\b|\bon[a-z]+\s*=|javascript\s*:/i', $html)) {
        return true;
    }

    return (bool) preg_match('/<|@import|expression\s*\(|javascript\s*:/i', $css);
}

function vb_begin(string $pageKey): void
{
    $GLOBALS['vb_state'] = [
        'page' => $pageKey,
        'dynamic' => [],
        'wrappers' => [],
        'stack' => [],
    ];

    ob_start();
}

function vb_dyn_open(string $key, string $tag = 'div', string $attrs = ''): void
{
    echo '<' . $tag . ($attrs !== '' ? ' ' . $attrs : '') . ' data-vb-dynamic="' . $key . '">';

    if (!isset($GLOBALS['vb_state'])) {
        return;
    }

    ob_start();
    $GLOBALS['vb_state']['stack'][] = [$key, $tag, $attrs];
}

function vb_dyn_close(): void
{
    if (!isset($GLOBALS['vb_state']) || empty($GLOBALS['vb_state']['stack'])) {
        return;
    }

    [$key, $tag, $attrs] = array_pop($GLOBALS['vb_state']['stack']);
    $inner = (string) ob_get_clean();

    $GLOBALS['vb_state']['dynamic'][$key] = $inner;
    $GLOBALS['vb_state']['wrappers'][$key] = [$tag, $attrs];

    echo $inner . '</' . $tag . '>';
}

/** Latest revision of a page. $publishedOnly=true -> the live one; false -> newest of any status. */
function vb_find_revision(string $pageKey, bool $publishedOnly): ?array
{
    $pdo = $GLOBALS['pdo'] ?? null;

    if (!$pdo instanceof PDO) {
        return null;
    }

    $sql = 'SELECT r.html_content, r.css_content
            FROM visual_builder_revisions r
            INNER JOIN visual_builder_pages p ON p.id = r.page_id
            WHERE p.page_key = ? '
        . ($publishedOnly ? "AND r.status = 'published' " : '')
        . 'ORDER BY r.revision_no DESC LIMIT 1';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pageKey]);
    $row = $stmt->fetch();

    return $row ?: null;
}

/** Replace the complete contents of dynamic regions, including non-empty saved snapshots. */
function vb_replace_dynamic_regions(string $html, array $dynamic, array &$used): string
{
    foreach ($dynamic as $key => $content) {
        $key = (string) $key;
        if (!preg_match('/^[a-z0-9-]+$/i', $key)) {
            continue;
        }

        $pattern = '/<([a-z][a-z0-9]*)\b(?=[^>]*\bdata-vb-dynamic\s*=\s*(["\'])' . preg_quote($key, '/') . '\2)[^>]*>/i';
        if (!preg_match($pattern, $html, $opening, PREG_OFFSET_CAPTURE)) {
            continue;
        }

        $openTag = $opening[0][0];
        $openStart = $opening[0][1];
        $tagName = strtolower($opening[1][0]);
        $innerStart = $openStart + strlen($openTag);

        // Tokenize tags and comments so nested elements of the same type are balanced correctly.
        if (!preg_match_all('/<!--.*?-->|<\/?[a-z][^>]*>/is', $html, $tokens, PREG_OFFSET_CAPTURE, $innerStart)) {
            continue;
        }

        $depth = 1;
        $innerEnd = null;
        foreach ($tokens[0] as $token) {
            $tag = $token[0];
            $offset = $token[1];
            if (str_starts_with($tag, '<!--')) {
                continue;
            }
            if (!preg_match('/^<\/?([a-z][a-z0-9]*)\b/i', $tag, $parts) || strtolower($parts[1]) !== $tagName) {
                continue;
            }
            if (preg_match('/^<\//', $tag)) {
                $depth--;
                if ($depth === 0) {
                    $innerEnd = $offset;
                    break;
                }
            } elseif (!preg_match('/\/\s*>$/', $tag)) {
                $depth++;
            }
        }

        if ($innerEnd === null) {
            continue;
        }

        $html = substr($html, 0, $innerStart) . (string) $content . substr($html, $innerEnd);
        $used[$key] = true;
    }

    return $html;
}

function vb_compose(array $revision, array $dynamic, array $wrappers, string $assets = ''): string
{
    $html = (string) $revision['html_content'];
    $used = [];
    $html = vb_replace_dynamic_regions($html, $dynamic, $used);

    foreach (VB_REQUIRED_DYNAMIC as $key) {
        if (isset($used[$key]) || !isset($dynamic[$key], $wrappers[$key])) {
            continue;
        }

        [$tag, $attrs] = $wrappers[$key];
        $html .= '<' . $tag . ' ' . $attrs . ' data-vb-dynamic="' . $key . '">'
            . $dynamic[$key] . '</' . $tag . '>';
    }

    $css = (string) preg_replace('/<\/?style/i', '', (string) $revision['css_content']);

    return '<style id="vb-page-css">' . $css . '</style>' . "\n" . $html . "\n" . $assets;
}

/**
 * Split page markup into [markup without inline script/style, the removed script/style blocks].
 * Blocks that live INSIDE a dynamic region are left alone (they come back with that region).
 */
function vb_split_assets(string $html, array $dynamic): array
{
    $tokens = [];
    $masked = $html;
    $i = 0;

    foreach ($dynamic as $inner) {
        $inner = (string) $inner;
        $i++;

        if ($inner === '') {
            continue;
        }

        $pos = strpos($masked, $inner);

        if ($pos === false) {
            continue;
        }

        $token = "\x01VB" . $i . "\x01";
        $masked = substr_replace($masked, $token, $pos, strlen($inner));
        $tokens[$token] = $inner;
    }

    $pattern = '/<(script|style)\b[^>]*>.*?<\/\1\s*>/is';
    $assets = '';

    if (preg_match_all($pattern, $masked, $found)) {
        $assets = implode("\n", $found[0]);
    }

    $clean = (string) preg_replace($pattern, '', $masked);

    return [strtr($clean, $tokens), strtr($assets, $tokens)];
}

function vb_end(): void
{
    $state = $GLOBALS['vb_state'] ?? null;

    if (!is_array($state)) {
        return;
    }

    // Close any buffer left open by mistake, then take the original page content.
    while (!empty($GLOBALS['vb_state']['stack'])) {
        vb_dyn_close();
    }

    $state = $GLOBALS['vb_state'];
    $original = (string) ob_get_clean();
    unset($GLOBALS['vb_state']);

    // Inline <script>/<style> of the page (filters, accordions, animations) are not editable design:
    // they are kept out of the editor and re-attached after the published HTML.
    [$editable, $assets] = vb_split_assets($original, $state['dynamic']);

    // Used by the editor to clone the real page into its canvas (admins only).
    if (($_GET['vb_snapshot'] ?? '') === '1' && vb_is_admin()) {
        echo '<!--VB-CONTENT-START-->' . $editable . '<!--VB-CONTENT-END-->';
        echo $assets;

        return;
    }

    // Mitra now uses explicit dynamic regions for database-driven partner cards.
    // Its published layout can therefore be applied without freezing live partner data.

    // Jurusan detail URLs (?id=...) are data-driven variants of the directory page.
    // Keep their original PHP output so a directory snapshot cannot replace every detail view.
    if ((string) ($state['page'] ?? '') === 'jurusan' && (int) ($_GET['id'] ?? 0) > 0) {
        echo $original;
        return;
    }

    try {
        $previewDraft = ($_GET['vb_preview'] ?? '') === 'draft' && vb_is_admin();
        $revision = vb_find_revision((string) $state['page'], !$previewDraft);

        $revisionHtml = (string) ($revision['html_content'] ?? '');
        // Accept either quote style and whitespace around the dynamic attribute.
        // A strict double-quote substring check can incorrectly reject a valid saved
        // revision, causing the public page to silently fall back to the native template
        // while the Visual Builder continues showing the saved design.
        $hasMitraDirectoryRegion = (bool) preg_match(
            '/\bdata-vb-dynamic\s*=\s*(["\'])mitra-directory-cards\1/i',
            $revisionHtml
        );
        $invalidMitraLayout = (string) ($state['page'] ?? '') === 'mitra'
            && (
                !str_contains($revisionHtml, 'mitra-page')
                || !$hasMitraDirectoryRegion
            );

        // Kunjungan Industri: photos come from the database. A snapshot saved before these
        // live regions existed has the (possibly empty) photo markup frozen into it, so the
        // photos would never show. Use the original PHP page until a layout that contains
        // all live photo regions is published.
        $invalidKiLayout = false;
        if ((string) ($state['page'] ?? '') === 'kunjungan-industri') {
            foreach (['ki-hero-photo', 'ki-logo-image', 'ki-story-photo', 'ki-bottom-photo'] as $kiRegion) {
                if (!preg_match('/\bdata-vb-dynamic\s*=\s*(["\'])' . $kiRegion . '\1/i', $revisionHtml)) {
                    $invalidKiLayout = true;
                    break;
                }
            }
        }

        if (
            $revision === null
            || trim($revisionHtml) === ''
            || vb_unsafe_markup($revisionHtml, (string) ($revision['css_content'] ?? ''))
            || $invalidMitraLayout
            || $invalidKiLayout
        ) {
            // A broken/incomplete Mitra revision must never blank the public directory.
            // Keep the original PHP page (with live database content) until a valid layout is published.
            echo $original;

            return;
        }

        echo vb_compose($revision, $state['dynamic'], $state['wrappers'], $assets);
    } catch (Throwable $exception) {
        error_log('[Visual Builder render] ' . $exception->getMessage());
        echo $original;
    }
}
