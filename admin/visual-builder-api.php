<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, private');

function vb_json(int $status, array $payload): never
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

if (!is_logged_in()) {
    vb_json(401, ['ok' => false, 'message' => 'Silakan login kembali.']);
}

$user = current_user();
if (($user['role'] ?? '') !== 'ADMIN') {
    vb_json(403, ['ok' => false, 'message' => 'Hanya ADMIN yang dapat mengakses Visual Builder pada tahap ini.']);
}

$allowedPages = [
    'home' => 'Beranda', 'profil' => 'Profil Sekolah', 'jurusan' => 'Semua Jurusan',
    'jurusan-rpl' => 'Jurusan — RPL', 'jurusan-tkj' => 'Jurusan — TKJ',
    'jurusan-dkv' => 'Jurusan — DKV', 'jurusan-pspt' => 'Jurusan — PSPT',
    'guru' => 'Guru & Tenaga Kependidikan', 'berita' => 'Informasi / Berita',
    'prestasi' => 'Prestasi', 'fasilitas' => 'Fasilitas', 'galeri' => 'Galeri',
    'kontak' => 'Kontak', 'ppdb' => 'PPDB', 'bkk' => 'BKK / Karier',
    'pkl' => 'PKL', 'magang' => 'Magang Jepang',
    'ekstrakurikuler' => 'Ekstrakurikuler', 'mitra' => 'Mitra Industri',
    'kunjungan-industri' => 'Kunjungan Industri',
];

// Pages whose public file already calls vb_begin()/vb_end(). Only these can be cloned and published.
$publishablePages = vb_publishable_pages();

try {
    $action = (string) ($_GET['action'] ?? 'load');
    $pageKey = (string) ($_GET['page'] ?? 'home');
    if (!isset($allowedPages[$pageKey])) {
        vb_json(400, ['ok' => false, 'message' => 'Halaman tidak dikenal.']);
    }

    $pageStmt = $pdo->prepare('SELECT id, page_label FROM visual_builder_pages WHERE page_key = ? LIMIT 1');
    $pageStmt->execute([$pageKey]);
    $page = $pageStmt->fetch();
    if (!$page) {
        // Add only the missing page registry row; never reset or overwrite existing revisions.
        $register = $pdo->prepare('INSERT INTO visual_builder_pages (page_key, page_label) VALUES (?, ?) ON DUPLICATE KEY UPDATE page_label = VALUES(page_label)');
        $register->execute([$pageKey, $allowedPages[$pageKey]]);
        $pageStmt->execute([$pageKey]);
        $page = $pageStmt->fetch();
    }
    if (!$page) {
        vb_json(503, ['ok' => false, 'message' => 'Data halaman Visual Builder belum tersedia.']);
    }
    $pageId = (int) $page['id'];
    $isPublishable = isset($publishablePages[$pageKey]);

    // ---------- LOAD (read only) ----------
    if ($action === 'load') {
        $revisionId = (int) ($_GET['revision'] ?? 0);
        if ($revisionId > 0) {
            $stmt = $pdo->prepare('SELECT id, revision_no, status, project_data, created_at FROM visual_builder_revisions WHERE id = ? AND page_id = ? LIMIT 1');
            $stmt->execute([$revisionId, $pageId]);
        } else {
            $stmt = $pdo->prepare('SELECT id, revision_no, status, project_data, created_at FROM visual_builder_revisions WHERE page_id = ? ORDER BY revision_no DESC LIMIT 1');
            $stmt->execute([$pageId]);
        }
        $revision = $stmt->fetch();

        $liveStmt = $pdo->prepare("SELECT id, revision_no, published_at FROM visual_builder_revisions WHERE page_id = ? AND status = 'published' ORDER BY revision_no DESC LIMIT 1");
        $liveStmt->execute([$pageId]);

        $historyStmt = $pdo->prepare('SELECT id, revision_no, status, created_at FROM visual_builder_revisions WHERE page_id = ? ORDER BY revision_no DESC LIMIT 15');
        $historyStmt->execute([$pageId]);

        vb_json(200, [
            'ok' => true,
            'page' => $page,
            'publishable' => $isPublishable,
            'revision' => $revision ?: null,
            'live' => $liveStmt->fetch() ?: null,
            'history' => $historyStmt->fetchAll(),
        ]);
    }

    // ---------- Everything below changes data: POST + CSRF ----------
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !in_array($action, ['save', 'publish', 'unpublish', 'upload'], true)) {
        vb_json(405, ['ok' => false, 'message' => 'Aksi tidak diizinkan.']);
    }
    if (!verify_csrf($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null)) {
        vb_json(403, ['ok' => false, 'message' => 'Token keamanan tidak valid. Muat ulang halaman lalu coba lagi.']);
    }

    // ---------- UPLOAD IMAGE ----------
    if ($action === 'upload') {
        try {
            $path = upload_image('file', 'uploads/builder', 8 * 1024 * 1024, 2200, 2200, 82);
        } catch (RuntimeException $exception) {
            vb_json(422, ['ok' => false, 'message' => $exception->getMessage()]);
        }
        if ($path === null) {
            vb_json(422, ['ok' => false, 'message' => 'Tidak ada gambar yang diunggah.']);
        }
        vb_json(200, ['ok' => true, 'src' => url($path)]);
    }

    $raw = file_get_contents('php://input');
    if ($raw === false || strlen($raw) > 1_500_000) {
        vb_json(413, ['ok' => false, 'message' => 'Data editor terlalu besar (maksimal 1,5 MB).']);
    }
    $body = json_decode($raw, true);
    if (!is_array($body)) {
        vb_json(400, ['ok' => false, 'message' => 'Payload JSON tidak valid.']);
    }

    // ---------- SAVE DRAFT ----------
    if ($action === 'save') {
        $projectData = $body['project_data'] ?? null;
        $html = (string) ($body['html'] ?? '');
        $css = (string) ($body['css'] ?? '');
        if (!is_array($projectData) || strlen($html) > 500_000 || strlen($css) > 300_000) {
            vb_json(422, ['ok' => false, 'message' => 'Data proyek tidak valid atau melebihi batas ukuran.']);
        }
        if (vb_unsafe_markup($html, $css)) {
            vb_json(422, ['ok' => false, 'message' => 'Konten berisi script atau atribut berbahaya. Hapus elemen tersebut sebelum menyimpan.']);
        }

        $projectJson = json_encode($projectData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($projectJson === false || strlen($projectJson) > 1_000_000) {
            vb_json(422, ['ok' => false, 'message' => 'Data proyek terlalu besar atau tidak dapat diproses.']);
        }

        $pdo->beginTransaction();
        $countStmt = $pdo->prepare('SELECT COALESCE(MAX(revision_no), 0) + 1 FROM visual_builder_revisions WHERE page_id = ? FOR UPDATE');
        $countStmt->execute([$pageId]);
        $revisionNo = (int) $countStmt->fetchColumn();
        $insert = $pdo->prepare("INSERT INTO visual_builder_revisions (page_id, revision_no, status, project_data, html_content, css_content, created_by) VALUES (?, ?, 'draft', ?, ?, ?, ?)");
        $insert->execute([$pageId, $revisionNo, $projectJson, $html, $css, (int) $user['id']]);
        $newId = (int) $pdo->lastInsertId();
        $pdo->commit();

        vb_json(200, [
            'ok' => true,
            'message' => 'Draft tersimpan. Website publik belum diubah.',
            'revision_id' => $newId,
            'revision_no' => $revisionNo,
        ]);
    }

    // ---------- PUBLISH ----------
    if ($action === 'publish') {
        if (!$isPublishable) {
            vb_json(422, ['ok' => false, 'message' => 'Halaman ini belum mendukung publikasi.']);
        }

        $revisionId = (int) ($body['revision_id'] ?? 0);
        $stmt = $pdo->prepare('SELECT id, revision_no, status, project_data, html_content, css_content FROM visual_builder_revisions WHERE id = ? AND page_id = ? LIMIT 1');
        $stmt->execute([$revisionId, $pageId]);
        $source = $stmt->fetch();
        if (!$source) {
            vb_json(404, ['ok' => false, 'message' => 'Revisi tidak ditemukan.']);
        }
        if ($source['status'] === 'published') {
            vb_json(200, ['ok' => true, 'message' => 'Versi ini sudah tayang.', 'revision_no' => (int) $source['revision_no']]);
        }
        $sourceHtml = (string) $source['html_content'];
        if (trim($sourceHtml) === '' || vb_unsafe_markup($sourceHtml, (string) $source['css_content'])) {
            vb_json(422, ['ok' => false, 'message' => 'Revisi ini kosong atau berisi markup yang tidak aman, tidak dapat dipublikasikan.']);
        }
        if ($pageKey === 'mitra' && (
            !str_contains($sourceHtml, 'mitra-page')
            || !preg_match('/data-vb-dynamic=["\']mitra-directory-cards["\']/', $sourceHtml)
        )) {
            vb_json(422, [
                'ok' => false,
                'message' => 'Desain Mitra belum memuat struktur halaman dan blok direktori mitra otomatis. Muat ulang tampilan asli Mitra di Visual Builder, lalu simpan kembali sebelum publikasi.'
            ]);
        }

        $pdo->beginTransaction();
        $pdo->prepare("UPDATE visual_builder_revisions SET status = 'archived' WHERE page_id = ? AND status = 'published'")->execute([$pageId]);
        $countStmt = $pdo->prepare('SELECT COALESCE(MAX(revision_no), 0) + 1 FROM visual_builder_revisions WHERE page_id = ? FOR UPDATE');
        $countStmt->execute([$pageId]);
        $revisionNo = (int) $countStmt->fetchColumn();
        $insert = $pdo->prepare("INSERT INTO visual_builder_revisions (page_id, revision_no, status, project_data, html_content, css_content, created_by, published_at) VALUES (?, ?, 'published', ?, ?, ?, ?, NOW())");
        $insert->execute([$pageId, $revisionNo, $source['project_data'], $source['html_content'], $source['css_content'], (int) $user['id']]);
        $pdo->commit();

        vb_json(200, ['ok' => true, 'message' => 'Dipublikasikan. Website publik sudah memakai desain ini.', 'revision_no' => $revisionNo]);
    }

    // ---------- UNPUBLISH (back to the original page) ----------
    if ($action === 'unpublish') {
        $pdo->prepare("UPDATE visual_builder_revisions SET status = 'archived' WHERE page_id = ? AND status = 'published'")->execute([$pageId]);
        vb_json(200, ['ok' => true, 'message' => 'Website publik kembali memakai desain asli.']);
    }
} catch (Throwable $exception) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('[Visual Builder] ' . $exception->getMessage());
    vb_json(500, ['ok' => false, 'message' => 'Terjadi kesalahan saat memproses Visual Builder. Detail: ' . $exception->getMessage()]);
}
