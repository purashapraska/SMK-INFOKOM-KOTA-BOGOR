<?php

declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/**
 * Detect the URL prefix of the project automatically, so login redirects work
 * whatever the folder name is, and whether the site runs in a sub-folder
 * (localhost/nama-folder) or as the document root (virtual host / hosting).
 * Can be forced with the INFOKOM_BASE_URL environment variable.
 */
function detect_base_url(): string
{
    $forced = getenv('INFOKOM_BASE_URL');

    if ($forced !== false) {
        return rtrim($forced, '/');
    }

    $root = realpath(dirname(__DIR__));
    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath((string) $_SERVER['DOCUMENT_ROOT']) : false;

    if ($root !== false && $docRoot !== false && $docRoot !== '') {
        $root = str_replace('\\', '/', $root);
        $docRoot = rtrim(str_replace('\\', '/', $docRoot), '/');

        if ($root === $docRoot) {
            return '';
        }

        if (str_starts_with($root, $docRoot . '/')) {
            return substr($root, strlen($docRoot));
        }
    }

    return '/' . basename(dirname(__DIR__));
}

define('BASE_URL', detect_base_url());
define('APP_NAME', 'SMK Infokom Kota Bogor');

date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/database.php';

// BKK partners schema is kept compatible with older installations.
try {
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS bkk_partners (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(220) NOT NULL,
            category VARCHAR(100) DEFAULT 'Mitra Industri',
            logo VARCHAR(255),
            description TEXT,
            website VARCHAR(500),
            status ENUM('published','draft') DEFAULT 'published',
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB"
    );

    $featuredColumn = $pdo->query("SHOW COLUMNS FROM bkk_partners LIKE 'is_featured'")->fetch();
    if (!$featuredColumn) {
        $pdo->exec('ALTER TABLE bkk_partners ADD COLUMN is_featured TINYINT(1) NOT NULL DEFAULT 0 AFTER website');
    }

    $partnerCount = (int) $pdo->query('SELECT COUNT(*) FROM bkk_partners')->fetchColumn();

    if ($partnerCount === 0) {
        $seed = $pdo->prepare(
            "INSERT INTO bkk_partners (name, category, logo, description, website, status, sort_order) VALUES (?, ?, ?, ?, ?, 'published', ?)"
        );

        $seed->execute([
            'Mitra Industri SMK INFOKOM',
            'Mitra Industri',
            'assets/images/bkk/partners/mitra-industri-brosur.png',
            'Logo mitra industri yang ditampilkan pada brosur resmi SPMB SMK INFOKOM Kota Bogor.',
            '',
            1,
        ]);

        $seed->execute([
            'Mitra Universitas & Pendidikan',
            'Mitra Pendidikan',
            'assets/images/bkk/partners/mitra-universitas-brosur.png',
            'Logo mitra universitas dan pendidikan yang ditampilkan pada brosur resmi SPMB SMK INFOKOM Kota Bogor.',
            '',
            2,
        ]);
    }
} catch (Throwable) {
    // Keep the website usable when the database user cannot alter the schema.
}

// Default extracurricular content: name + logo + description only.
try {
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS extracurriculars (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(180) NOT NULL,
            image VARCHAR(255),
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB"
    );

    $extracurricularCount = (int) $pdo->query('SELECT COUNT(*) FROM extracurriculars')->fetchColumn();
    if ($extracurricularCount === 0) {
        $extracurricularSeed = $pdo->prepare(
            'INSERT INTO extracurriculars (name, image, description) VALUES (?, NULL, ?)'
        );
        $extracurriculars = [
            ['Paskibra', 'Kegiatan untuk membangun kedisiplinan, kepemimpinan, tanggung jawab, dan kekompakan siswa.'],
            ['Pramuka', 'Wadah untuk mengembangkan kemandirian, kerja sama, kedisiplinan, dan jiwa kepemimpinan.'],
            ['PMR', 'Kegiatan yang mengembangkan kepedulian sosial serta pengetahuan dan keterampilan dasar kesehatan.'],
            ['Japan Indonesia Community (JIC)', 'Komunitas untuk mengenal bahasa dan budaya Jepang melalui kegiatan belajar dan praktik bersama.'],
            ['Futsal', 'Kegiatan olahraga yang mengembangkan kemampuan bermain, kerja sama tim, sportivitas, dan kebugaran.'],
            ['Hadroh', 'Kegiatan seni musik religi untuk mengembangkan kreativitas, kekompakan, dan kecintaan terhadap seni keagamaan.'],
        ];
        foreach ($extracurriculars as [$name, $description]) {
            $extracurricularSeed->execute([$name, $description]);
        }
    }
} catch (Throwable) {
    // Keep the website usable when the database user cannot alter the schema.
}
// Visual Builder tables are created automatically (safe: CREATE TABLE IF NOT EXISTS,
// never drops or alters existing data), so the manual SQL migration is no longer required.
try {
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS visual_builder_pages (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            page_key VARCHAR(80) NOT NULL UNIQUE,
            page_label VARCHAR(160) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS visual_builder_revisions (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            page_id BIGINT UNSIGNED NOT NULL,
            revision_no INT UNSIGNED NOT NULL,
            status ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
            project_data LONGTEXT NOT NULL,
            html_content LONGTEXT NOT NULL,
            css_content LONGTEXT NOT NULL,
            created_by BIGINT UNSIGNED NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            published_at TIMESTAMP NULL DEFAULT NULL,
            UNIQUE KEY uq_visual_builder_page_revision (page_id, revision_no),
            KEY idx_visual_builder_page_status (page_id, status, created_at),
            CONSTRAINT fk_visual_builder_revision_page FOREIGN KEY (page_id)
                REFERENCES visual_builder_pages(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );

    $vbSeed = $pdo->prepare(
        'INSERT INTO visual_builder_pages (page_key, page_label) VALUES (?, ?) ON DUPLICATE KEY UPDATE page_label = VALUES(page_label)'
    );
    foreach ([
        'home' => 'Beranda', 'profil' => 'Profil Sekolah', 'jurusan' => 'Jurusan',
        'guru' => 'Guru & Tenaga Kependidikan', 'berita' => 'Informasi / Berita',
        'prestasi' => 'Prestasi', 'fasilitas' => 'Fasilitas', 'galeri' => 'Galeri',
        'kontak' => 'Kontak', 'ppdb' => 'PPDB', 'bkk' => 'BKK / Karier',
        'pkl' => 'PKL', 'magang' => 'Magang Jepang',
        'ekstrakurikuler' => 'Ekstrakurikuler', 'mitra' => 'Mitra Industri',
    ] as $vbKey => $vbLabel) {
        $vbSeed->execute([$vbKey, $vbLabel]);
    }
} catch (Throwable $exception) {
    error_log('[Visual Builder migration] ' . $exception->getMessage());
}

require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/activity.php';
require_once __DIR__ . '/../includes/visual-builder.php';
