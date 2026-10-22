<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';

require_login();

$user = current_user();
$title = $title ?? 'Dashboard';

if (!is_array($user)) {
    $user = [];
}

$userName = (string) ($user['name'] ?? 'Administrator');

$currentScript = basename($_SERVER['PHP_SELF'] ?? '');
$currentEntity = (string) ($_GET['entity'] ?? '');

$menus = [
    [
        'href' => 'dashboard.php',
        'label' => 'Dashboard',
        'key' => 'dashboard',
    ],
    [
        'href' => 'manage.php?entity=majors',
        'label' => 'Jurusan',
        'key' => 'majors',
    ],
    [
        'href' => 'manage.php?entity=teachers',
        'label' => 'Guru',
        'key' => 'teachers',
    ],
    [
        'href' => 'manage.php?entity=students',
        'label' => 'Siswa',
        'key' => 'students',
    ],
    [
        'href' => 'facilities.php',
        'label' => 'Fasilitas',
        'key' => 'facilities',
    ],
    [
        'href' => 'manage.php?entity=achievements',
        'label' => 'Prestasi',
        'key' => 'achievements',
    ],
    [
        'href' => 'manage.php?entity=gallery',
        'label' => 'Galeri',
        'key' => 'gallery',
    ],
    [
        'href' => 'manage.php?entity=extracurriculars',
        'label' => 'Ekstrakurikuler',
        'key' => 'extracurriculars',
    ],
    [
        'href' => 'manage.php?entity=internships',
        'label' => 'PKL',
        'key' => 'internships',
    ],
    [
        'href' => 'industrial-visits.php',
        'label' => 'Kunjungan Industri',
        'key' => 'industrial-visits',
    ],
    [
        'href' => 'manage.php?entity=magang_instagram',
        'label' => 'Magang Jepang / Instagram',
        'key' => 'magang_instagram',
    ],
    [
        'href' => 'manage.php?entity=jobs',
        'label' => 'BKK / Lowongan',
        'key' => 'jobs',
    ],
    [
        'href' => 'manage.php?entity=bkk_partners',
        'label' => 'BKK / Mitra',
        'key' => 'bkk_partners',
    ],
    [
        'href' => 'manage.php?entity=youtube_videos',
        'label' => 'YouTube',
        'key' => 'youtube_videos',
    ],
    [
        'href' => 'manage.php?entity=instagram_posts',
        'label' => 'Instagram',
        'key' => 'instagram_posts',
    ],
    [
        'href' => 'manage.php?entity=ppdb_documents',
        'label' => 'Dokumen PPDB',
        'key' => 'ppdb_documents',
    ],
    [
        'href' => 'manage.php?entity=ppdb_registrations',
        'label' => 'Pendaftar PPDB',
        'key' => 'ppdb_registrations',
    ],
    [
        'href' => 'manage.php?entity=contact_messages',
        'label' => 'Pesan Masuk',
        'key' => 'contact_messages',
    ],
    [
        'href' => 'content.php',
        'label' => 'Konten Halaman',
        'key' => 'content',
    ],
    [
        'href' => 'appearance.php',
        'label' => 'Tampilan Website',
        'key' => 'appearance',
    ],
    [
        'href' => 'settings.php',
        'label' => 'Pengaturan Website',
        'key' => 'settings',
    ],
];

// Visual Builder hanya untuk ADMIN. Disisipkan tepat setelah "Tampilan Website".
if (($user['role'] ?? '') === 'ADMIN') {
    $builderMenu = [
        'href' => 'visual-builder.php',
        'label' => 'Visual Builder',
        'key' => 'visual-builder',
    ];
    $appearanceAt = array_search('appearance', array_column($menus, 'key'), true);
    array_splice($menus, $appearanceAt === false ? count($menus) : $appearanceAt + 1, 0, [$builderMenu]);
}

function admin_menu_active(string $key, string $currentScript, string $currentEntity): bool
{
    if ($key === 'dashboard') {
        return $currentScript === 'dashboard.php';
    }

    if ($key === 'settings') {
        return $currentScript === 'settings.php';
    }

    if ($key === 'appearance') {
        return $currentScript === 'appearance.php';
    }

    if ($key === 'content') {
        return $currentScript === 'content.php';
    }

    if ($key === 'visual-builder') {
        return $currentScript === 'visual-builder.php';
    }

    if ($key === 'industrial-visits') {
        return $currentScript === 'industrial-visits.php';
    }

    return ($key === 'facilities' && $currentScript === 'facilities.php')
        || ($currentScript === 'manage.php' && $currentEntity === $key);
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <link rel="icon" type="image/png" href="<?= e(img_url(setting('site_favicon', setting('logo_path', 'assets/images/logo-infokom.svg')))) ?>">

    <title>
        <?= e($title) ?>
        &mdash;
        Admin SMK Infokom
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
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@600;700;800&display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="<?= e(url('assets/css/admin.css?v=20260930-pkl-1')) ?>"
    >
</head>
<body class="<?= $currentEntity === 'internships' ? 'admin-pkl-page' : '' ?>">

<div class="admin-page-mask" aria-hidden="true">
    <span>SMK INFOKOM</span>
</div>

<div class="admin-shell">

    <aside class="sidebar">

        <a
            class="admin-brand"
            href="<?= e(url('admin/dashboard.php')) ?>"
        >
            <img
                src="<?= e(img_url(setting('logo_path'))) ?>"
                alt="Logo sekolah"
            >

            <span>
                <strong>
                    SMK INFOKOM
                </strong>

                <small>
                    CONTENT MANAGEMENT
                </small>
            </span>
        </a>

        <nav aria-label="Menu admin">

            <?php foreach ($menus as $menu): ?>

                <a
                    href="<?= e(url('admin/' . $menu['href'])) ?>"
                    class="<?= admin_menu_active(
                        $menu['key'],
                        $currentScript,
                        $currentEntity
                    ) ? 'active' : '' ?>"
                >
                    <?= e($menu['label']) ?>
                </a>

            <?php endforeach; ?>

        </nav>

        <a
            class="logout"
            href="<?= e(url('logout.php')) ?>"
        >
            Keluar dari Admin
        </a>

    </aside>

    <div class="admin-main">

        <header class="admin-top">

            <button
                class="admin-menu"
                type="button"
                aria-label="Buka menu admin"
                onclick="document.querySelector('.sidebar').classList.toggle('open')"
            >
                ☰
            </button>

            <div>
                <strong>
                    <?= e($title) ?>
                </strong>

                <span>
                    / <?= e($userName) ?>
                </span>
            </div>

            <a
                href="<?= e(url('')) ?>"
                target="_blank"
                rel="noopener"
            >
                Lihat Website ↗
            </a>

        </header>

        <main class="admin-content">
