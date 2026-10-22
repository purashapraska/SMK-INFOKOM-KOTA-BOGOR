<?php

declare(strict_types=1);

$host = getenv('INFOKOM_DB_HOST') ?: '127.0.0.1';
$database = getenv('INFOKOM_DB_NAME') ?: 'school_db';
$username = getenv('INFOKOM_DB_USER') ?: 'root';
$password = getenv('INFOKOM_DB_PASSWORD') !== false
    ? (string) getenv('INFOKOM_DB_PASSWORD')
    : '';
$port = getenv('INFOKOM_DB_PORT') ?: '3306';
$charset = 'utf8mb4';

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_TIMEOUT => 5,
];

try {
    $serverDsn = "mysql:host={$host};port={$port};charset={$charset}";
    $serverOptions = $options;
    $serverOptions[PDO::MYSQL_ATTR_MULTI_STATEMENTS] = true;

    $serverPdo = new PDO($serverDsn, $username, $password, $serverOptions);

    $safeDatabase = str_replace('`', '``', $database);
    $serverPdo->exec(
        "CREATE DATABASE IF NOT EXISTS `{$safeDatabase}` " .
        "CHARACTER SET {$charset} COLLATE utf8mb4_unicode_ci"
    );

    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset={$charset}";
    $pdo = new PDO($dsn, $username, $password, $options);

    $hasUsersTable = (bool) $pdo->query("SHOW TABLES LIKE 'users'")->fetchColumn();

    if (!$hasUsersTable) {
        $schemaPath = __DIR__ . '/../database/database.sql';

        if (!is_file($schemaPath)) {
            throw new RuntimeException('File database/database.sql tidak ditemukan.');
        }

        $schema = file_get_contents($schemaPath);

        if ($schema === false || trim($schema) === '') {
            throw new RuntimeException('File database/database.sql kosong atau tidak dapat dibaca.');
        }

        $serverPdo->exec($schema);
        $pdo = new PDO($dsn, $username, $password, $options);
    }

    // Ensure the Guru table exists on older installations as well.
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS teachers (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(150) NOT NULL,
            image VARCHAR(255),
            position VARCHAR(180),
            subject VARCHAR(120),
            education VARCHAR(30) NOT NULL DEFAULT 'S1',
            bio TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB"
    );

    $teacherColumns = $pdo->query("SHOW COLUMNS FROM teachers")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('education', $teacherColumns, true)) {
        $pdo->exec("ALTER TABLE teachers ADD COLUMN education VARCHAR(30) NOT NULL DEFAULT 'S1' AFTER subject");
    }

    if (!in_array('bio', $teacherColumns, true)) {
        $pdo->exec("ALTER TABLE teachers ADD COLUMN bio TEXT NULL AFTER education");
    }

    // Seed the initial Guru & Staff dataset only when the table is empty.
    // After this first sync, all edits are owned by the database/admin panel.
    $teacherCount = (int) $pdo->query("SELECT COUNT(*) FROM teachers")->fetchColumn();

    if ($teacherCount === 0) {
        $seedPath = __DIR__ . '/teacher_seed.php';

        if (is_file($seedPath)) {
            require_once $seedPath;
            $seedStatement = $pdo->prepare(
                'INSERT INTO teachers (name, image, position, subject, education, bio) VALUES (?, ?, ?, ?, ?, ?)'
            );

            foreach (default_teacher_seed_data() as $teacher) {
                $seedStatement->execute($teacher);
            }
        }
    }

    // Tables added by later feature revisions. These statements are safe
    // because they never remove existing application data.
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS youtube_videos (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(220) NOT NULL,
            youtube_url VARCHAR(500) NOT NULL,
            description TEXT,
            status ENUM('published','draft') DEFAULT 'published',
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB"
    );

    $youtubeColumns = $pdo->query("SHOW COLUMNS FROM youtube_videos")->fetchAll(PDO::FETCH_COLUMN);

    if (!in_array('category', $youtubeColumns, true)) {
        $pdo->exec("ALTER TABLE youtube_videos ADD COLUMN category VARCHAR(100) NULL AFTER youtube_url");
    }

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS instagram_posts (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(220) NOT NULL,
            instagram_url VARCHAR(500) NOT NULL,
            description TEXT,
            status ENUM('published','draft') DEFAULT 'published',
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB"
    );
} catch (Throwable $exception) {
    error_log('[SMK INFOKOM DB] ' . $exception->getMessage());

    http_response_code(500);

    exit(
        'Koneksi database gagal. Pastikan MySQL Laragon aktif, port 3306 benar, ' .
        'dan username/password MySQL sesuai. Jika ini instalasi baru, pastikan ' .
        'file database/database.sql masih ada.'
    );
}


// Ensure new branding settings exist for existing installations.
try {
    $brandingDefaults = [
        'site_favicon' => '',
        'information_hero_logo' => '',
        'information_hero_title' => 'Semua cerita sekolah, dalam satu layar.',
        'information_hero_subtitle' => 'Temukan dokumentasi, kegiatan, prestasi, dan cerita SMK INFOKOM Kota Bogor melalui video YouTube resmi sekolah.',
    ];
    $brandingStatement = $pdo->prepare(
        'INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_key = setting_key'
    );
    foreach ($brandingDefaults as $brandingKey => $brandingValue) {
        $brandingStatement->execute([$brandingKey, $brandingValue]);
    }
} catch (Throwable) {
    // Existing settings are still usable if this optional migration cannot run.
}
