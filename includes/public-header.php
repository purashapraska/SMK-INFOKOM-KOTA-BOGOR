<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';

$pageTitle = $pageTitle ?? 'Beranda';

$isHome = basename($_SERVER['PHP_SELF'] ?? '') === 'index.php';

$schoolName = setting(
    'school_name',
    APP_NAME
);

$city = setting(
    'school_city',
    'Kota Bogor'
);

$logo = setting(
    'logo_path',
    'assets/images/logo-infokom.svg'
);

$favicon = setting(
    'site_favicon',
    $logo
);


$primary = safe_color(
    setting('primary_color', '#0F4C81'),
    '#0F4C81'
);

$accent = safe_color(
    setting('accent_color', '#F4C542'),
    '#F4C542'
);

// CMS visual settings: global defaults with optional per-page overrides.
$visualPages = [
    'index.php' => 'home',
    'profil.php' => 'profil',
    'jurusan.php' => 'jurusan',
    'guru.php' => 'guru',
    'berita.php' => 'informasi',
    'prestasi.php' => 'prestasi',
    'ekstrakurikuler.php' => 'kesiswaan',
    'fasilitas.php' => 'fasilitas',
    'pkl.php' => 'program-karier',
    'magang.php' => 'program-karier',
    'bkk.php' => 'program-karier',
    'mitra.php' => 'mitra',
    'galeri.php' => 'galeri',
    'ppdb.php' => 'ppdb',
    'kontak.php' => 'kontak',
];
$currentPageKey = $visualPages[basename($_SERVER['PHP_SELF'] ?? '')] ?? 'home';
$visualFonts = [
    'DM Sans' => 'DM Sans',
    'Inter' => 'Inter',
    'Poppins' => 'Poppins',
    'Roboto' => 'Roboto',
    'Nunito Sans' => 'Nunito Sans',
    'Lato' => 'Lato',
    'Source Sans 3' => 'Source Sans 3',
    'Manrope' => 'Manrope',
];
$visualKeys = [
    'heading_color' => ['#142230', '#142230'],
    'text_color' => ['#475569', '#475569'],
    'background_color' => ['#FBFCFD', '#FBFCFD'],
    'button_color' => [$primary, $primary],
    'button_text_color' => ['#FFFFFF', '#FFFFFF'],
    'heading_font' => ['Manrope', 'Manrope'],
    'body_font' => ['DM Sans', 'DM Sans'],
];
$visual = [];
foreach ($visualKeys as $key => [$fallback, $unused]) {
    $globalValue = setting('visual_global_' . $key, $fallback);
    $pageValue = setting('visual_page_' . $currentPageKey . '_' . $key, '');
    $visual[$key] = $pageValue !== '' ? $pageValue : $globalValue;
}
foreach (['heading_color', 'text_color', 'background_color', 'button_color', 'button_text_color'] as $colorKey) {
    $visual[$colorKey] = safe_color($visual[$colorKey], $visualKeys[$colorKey][0]);
}
if (!isset($visualFonts[$visual['heading_font']])) {
    $visual['heading_font'] = 'Manrope';
}
if (!isset($visualFonts[$visual['body_font']])) {
    $visual['body_font'] = 'DM Sans';
}
$googleFonts = array_unique([$visual['heading_font'], $visual['body_font'], 'DM Sans', 'Manrope']);
$fontFamilies = [];
foreach ($googleFonts as $fontFamily) {
    $fontFamilies[] = str_replace(' ', '+', $fontFamily) . ':wght@400;500;600;700;800';
}
$googleFontsUrl = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', $fontFamilies) . '&display=swap';

$wa = preg_replace(
    '/\D+/',
    '',
    setting('whatsapp_number', '')
);

$ig = setting(
    'instagram_url',
    ''
);

?>
<!DOCTYPE html>
<html
    lang="id"
>
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="description"
        content="Website resmi SMK Infokom Kota Bogor"
    >

    <meta
        name="theme-color"
        content="<?= e($primary) ?>"
    >

    <link
        rel="icon"
        type="image/png"
        href="<?= e(img_url($favicon)) ?>"
    >

    <title>
        <?= e($pageTitle) ?>
        &mdash;
        <?= e($schoolName) ?>
    </title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="<?= e($googleFontsUrl) ?>"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="<?= e(url('assets/css/style.css?v=' . ($pageTitle === 'Ekstrakurikuler' ? '20261019-cms-font-content-1' : ($pageTitle === 'Galeri Instagram' ? '20261019-cms-font-content-1' : ($pageTitle === 'Kontak' ? '20261019-cms-font-content-1' : (in_array($pageTitle, ['Mitra', 'Mitra Industri'], true) ? '20261021-mitra-showcase-white-2' : '20261019-cms-font-content-1')))))) ?>"
    >

    <!-- THEME GLOBAL: nilai ini berasal dari Admin > Tampilan.
         Jika warna font/background tidak berubah setelah mengedit CSS, periksa nilai ini. -->
    <style>
        :root {
            --primary: <?= e($primary) ?>;
            --accent: <?= e($accent) ?>;
            --site-heading-color: <?= e($visual['heading_color']) ?>;
            --site-text-color: <?= e($visual['text_color']) ?>;
            --site-background: <?= e($visual['background_color']) ?>;
            --site-button-color: <?= e($visual['button_color']) ?>;
            --site-button-text-color: <?= e($visual['button_text_color']) ?>;
            --site-heading-font: "<?= e($visual['heading_font']) ?>", sans-serif;
            --site-body-font: "<?= e($visual['body_font']) ?>", sans-serif;
        }
        body { background-color: var(--site-background); color: var(--site-text-color); font-family: var(--site-body-font); }
        section:not([class*="hero"]) h1, section:not([class*="hero"]) h2, section:not([class*="hero"]) h3, section:not([class*="hero"]) h4, section:not([class*="hero"]) h5, section:not([class*="hero"]) h6 { font-family: var(--site-heading-font); color: var(--site-heading-color); }
        .button-primary { background-color: var(--site-button-color); color: var(--site-button-text-color); }
    </style>
</head>
<body>

<div
    class="scroll-progress"
    aria-hidden="true"
>
    <span id="scrollProgress"></span>
</div>

<header
    class="site-header<?= $isHome ? ' home-header' : '' ?>"
    id="siteHeader"
>
    <div class="container nav-shell">

        <a
            class="brand"
            href="<?= e(url('')) ?>"
            aria-label="<?= e($schoolName) ?>"
        >
            <span class="brand-logo-wrap">
                <span class="brand-logo-glow"></span>

                <img
                    src="<?= e(url($logo)) ?>"
                    alt="Logo <?= e($schoolName) ?>"
                >
            </span>

            <span class="brand-copy">
                <strong>
                    SMK INFOKOM
                </strong>

                <small>
                    <?= e(mb_strtoupper($city)) ?>
                </small>
            </span>
        </a>

        <button
            class="nav-toggle"
            type="button"
            aria-label="Buka menu"
            aria-expanded="false"
        >
            <i></i>
            <i></i>
            <i></i>
        </button>

        <nav
            class="site-nav"
            id="siteNavigation"
            aria-label="Navigasi utama"
        >
            <span class="nav-indicator" aria-hidden="true"></span>

            <a class="<?= e(active_page('index.php')) ?>" href="<?= e(url('')) ?>">
                <span>Beranda</span>
            </a>

            <a class="<?= e(active_page('profil.php')) ?>" href="<?= e(url('profil.php')) ?>">
                <span>Profil</span>
            </a>

            <?php
                $academicPages = ['jurusan.php', 'guru.php'];
                $isAcademicPage = in_array(basename($_SERVER['PHP_SELF'] ?? ''), $academicPages, true);
            ?>
            <div class="nav-dropdown<?= $isAcademicPage ? ' active' : '' ?>">
                <button
                    class="nav-dropdown-trigger<?= $isAcademicPage ? ' active' : '' ?>"
                    type="button"
                    aria-expanded="false"
                    aria-controls="academicMenu"
                >
                    <span>Akademik</span>
                    <svg class="dropdown-arrow" viewBox="0 0 16 16" width="13" height="13" aria-hidden="true" focusable="false">
                        <path d="m4 6 4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <div class="dropdown-menu" id="academicMenu">
                    <div class="dropdown-heading">
                        <span>AKADEMIK</span>
                        <small>Program belajar &amp; tenaga pendidik</small>
                    </div>
                    <a href="<?= e(url('jurusan.php')) ?>" class="<?= e(active_page('jurusan.php')) ?>">
                        <span class="dropdown-icon" aria-hidden="true">01</span>
                        <span>
                            <strong>Jurusan</strong>
                            <small>Kompetensi keahlian</small>
                        </span>
                        <b aria-hidden="true">↗</b>
                    </a>
                    <a href="<?= e(url('guru.php')) ?>" class="<?= e(active_page('guru.php')) ?>">
                        <span class="dropdown-icon" aria-hidden="true">02</span>
                        <span>
                            <strong>Guru</strong>
                            <small>Guru, pimpinan &amp; staf sekolah</small>
                        </span>
                        <b aria-hidden="true">↗</b>
                    </a>
                </div>
            </div>

            <a class="<?= e(active_page('fasilitas.php')) ?>" href="<?= e(url('fasilitas.php')) ?>">
                <span>Fasilitas</span>
            </a>

            <?php
                $programPages = ['pkl.php', 'magang.php', 'bkk.php', 'kunjungan-industri.php'];
                $isProgramPage = in_array(basename($_SERVER['PHP_SELF'] ?? ''), $programPages, true);
            ?>
            <div class="nav-dropdown<?= $isProgramPage ? ' active' : '' ?>">
                <button
                    class="nav-dropdown-trigger<?= $isProgramPage ? ' active' : '' ?>"
                    type="button"
                    aria-expanded="false"
                    aria-controls="programCareerMenu"
                >
                    <span>Program &amp; Karier</span>
                    <svg class="dropdown-arrow" viewBox="0 0 16 16" width="13" height="13" aria-hidden="true" focusable="false">
                        <path d="m4 6 4 4 4-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
                <div class="dropdown-menu" id="programCareerMenu">
                    <div class="dropdown-heading">
                        <span>PROGRAM SEKOLAH</span>
                        <small>Belajar &amp; berkarier</small>
                    </div>
                    <a href="<?= e(url('pkl.php')) ?>" class="<?= e(active_page('pkl.php')) ?>">
                        <span class="dropdown-icon" aria-hidden="true">01</span>
                        <span>
                            <strong>PKL</strong>
                            <small>Praktik Kerja Lapangan</small>
                        </span>
                        <b aria-hidden="true">↗</b>
                    </a>
                    <a href="<?= e(url('magang.php')) ?>" class="<?= e(active_page('magang.php')) ?>">
                        <span class="dropdown-icon" aria-hidden="true">02</span>
                        <span>
                            <strong>Magang Jepang</strong>
                            <small>Pengalaman kerja internasional</small>
                        </span>
                        <b aria-hidden="true">↗</b>
                    </a>
                    <a href="<?= e(url('bkk.php')) ?>" class="<?= e(active_page('bkk.php')) ?>">
                        <span class="dropdown-icon" aria-hidden="true">03</span>
                        <span>
                            <strong>BKK</strong>
                            <small>Bursa Kerja Khusus</small>
                        </span>
                        <b aria-hidden="true">↗</b>
                    </a>
                    <a href="<?= e(url('kunjungan-industri.php')) ?>" class="<?= e(active_page('kunjungan-industri.php')) ?>">
                        <span class="dropdown-icon" aria-hidden="true">04</span>
                        <span>
                            <strong>Kunjungan Industri</strong>
                            <small>Dokumentasi kegiatan industri</small>
                        </span>
                        <b aria-hidden="true">↗</b>
                    </a>
                </div>
            </div>

            <a class="<?= e(active_page('mitra.php')) ?>" href="<?= e(url('mitra.php')) ?>">
                <span>Mitra</span>
            </a>

            <a class="<?= e(active_page('prestasi.php')) ?>" href="<?= e(url('prestasi.php')) ?>">
                <span>Prestasi</span>
            </a>

            <a class="<?= e(active_page('ekstrakurikuler.php')) ?>" href="<?= e(url('ekstrakurikuler.php')) ?>">
                <span>Ekstrakurikuler</span>
            </a>

            <a class="<?= e(active_page('galeri.php')) ?>" href="<?= e(url('galeri.php')) ?>">
                <span>Galeri</span>
            </a>

            <a class="<?= e(active_page('kontak.php')) ?>" href="<?= e(url('kontak.php')) ?>">
                <span>Kontak</span>
            </a>

            <a class="nav-ppdb <?= e(active_page('ppdb.php')) ?>" href="<?= e(url('ppdb.php')) ?>">
                <span>PPDB</span>
                <i aria-hidden="true">↗</i>
            </a>
        </nav>

    </div>
</header>

<button class="nav-backdrop" type="button" aria-label="Tutup menu navigasi" tabindex="-1"></button>

<div class="page-mask public-page-mask" aria-hidden="true">
    <img
        class="page-transition-logo"
        src="<?= e(url($logo)) ?>"
        alt=""
        width="112"
        height="112"
        decoding="async"
    >
</div>

<div class="news-ticker" aria-label="Informasi berjalan">
    <div class="news-ticker-label">
        <span class="ticker-dot"></span>
        INFORMASI
    </div>
    <div class="news-ticker-track">
        <div class="news-ticker-content">
            <span><?= e(setting('running_text', 'Penerimaan Peserta Didik Baru SMK Infokom Kota Bogor • Informasi jurusan • Kegiatan sekolah • Prestasi siswa • PKL & Magang • BKK dan informasi terbaru sekolah.')) ?></span>
            <i>✦</i>
            <span><?= e(setting('running_text', 'Penerimaan Peserta Didik Baru SMK Infokom Kota Bogor • Informasi jurusan • Kegiatan sekolah • Prestasi siswa • PKL & Magang • BKK dan informasi terbaru sekolah.')) ?></span>
            <i>✦</i>
        </div>
    </div>
</div>

