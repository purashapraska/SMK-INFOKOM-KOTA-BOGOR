<?php

declare(strict_types=1);

function url(string $path = ''): string
{
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function e(mixed $value): string
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

function redirect(string $path): never
{
    header(
        'Location: ' .
        (str_starts_with($path, '/') ? $path : url($path))
    );

    exit;
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;

        return null;
    }

    $value = $_SESSION['_flash'][$key] ?? null;

    unset($_SESSION['_flash'][$key]);

    return $value;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf'];
}

function verify_csrf(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['_csrf'])
        && hash_equals($_SESSION['_csrf'], $token);
}

function setting(string $key, string $default = ''): string
{
    global $pdo;

    static $cache = [];

    if (array_key_exists($key, $cache)) {
        return (string) $cache[$key];
    }

    try {
        $statement = $pdo->prepare(
            'SELECT setting_value FROM settings ' .
            'WHERE setting_key = ? LIMIT 1'
        );

        $statement->execute([$key]);
        $value = $statement->fetchColumn();

        $cache[$key] = ($value === false || $value === null)
            ? $default
            : (string) $value;
    } catch (Throwable) {
        $cache[$key] = $default;
    }

    return (string) $cache[$key];
}

function save_setting(string $key, string $value): void
{
    global $pdo;

    $statement = $pdo->prepare(
        'INSERT INTO settings (setting_key, setting_value) ' .
        'VALUES (?, ?) ' .
        'ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)'
    );

    $statement->execute([$key, $value]);
}

function safe_color(string $value, string $fallback): string
{
    return preg_match('/^#[0-9a-fA-F]{6}$/', $value)
        ? strtoupper($value)
        : $fallback;
}

function upload_file(
    string $field,
    string $directory,
    array $allowed,
    int $max = 5 * 1024 * 1024
): ?string {
    if (
        empty($_FILES[$field]) ||
        $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE
    ) {
        return null;
    }

    $file = $_FILES[$field];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload gagal.');
    }

    if ($file['size'] > $max) {
        throw new RuntimeException('Ukuran file terlalu besar.');
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);

    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Format file tidak didukung.');
    }

    $name = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];
    $target = __DIR__ . '/../' . trim($directory, '/');

    if (!is_dir($target)) {
        mkdir($target, 0755, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $target . '/' . $name)) {
        throw new RuntimeException('File gagal disimpan.');
    }

    return trim($directory, '/') . '/' . $name;
}

function upload_image(
    string $field,
    string $directory = 'uploads/general',
    int $max = 10 * 1024 * 1024,
    int $maxWidth = 1600,
    int $maxHeight = 1600,
    int $quality = 82
): ?string {
    if (
        empty($_FILES[$field]) ||
        $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE
    ) {
        return null;
    }

    $file = $_FILES[$field];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $messages = [
            UPLOAD_ERR_INI_SIZE => 'Ukuran gambar melewati batas upload PHP. Silakan naikkan upload_max_filesize di php.ini.',
            UPLOAD_ERR_FORM_SIZE => 'Ukuran gambar melewati batas form.',
            UPLOAD_ERR_PARTIAL => 'Upload gambar tidak selesai.',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary PHP tidak tersedia.',
            UPLOAD_ERR_CANT_WRITE => 'Server gagal menulis file gambar.',
            UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP.',
        ];

        throw new RuntimeException(
            $messages[$file['error']] ?? 'Upload gambar gagal.'
        );
    }

    if ($file['size'] > $max) {
        throw new RuntimeException(
            'Ukuran gambar terlalu besar. Maksimal ' .
            number_format($max / 1024 / 1024, 0) .
            ' MB sebelum diproses.'
        );
    }

    if (!extension_loaded('gd')) {
        throw new RuntimeException(
            'Ekstensi GD PHP belum aktif. Aktifkan extension=gd di php.ini.'
        );
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = (string) $finfo->file($file['tmp_name']);
    $allowed = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    if (!in_array($mime, $allowed, true)) {
        throw new RuntimeException(
            'Format gambar tidak didukung. Gunakan JPG, PNG, atau WebP.'
        );
    }

    $source = match ($mime) {
        'image/jpeg' => @imagecreatefromjpeg($file['tmp_name']),
        'image/png' => @imagecreatefrompng($file['tmp_name']),
        'image/webp' => function_exists('imagecreatefromwebp')
            ? @imagecreatefromwebp($file['tmp_name'])
            : false,
        default => false,
    };

    if (!$source) {
        throw new RuntimeException('Gambar tidak dapat diproses. Pastikan file valid.');
    }

    $width = imagesx($source);
    $height = imagesy($source);

    $scale = min(
        1,
        $maxWidth / max(1, $width),
        $maxHeight / max(1, $height)
    );

    $newWidth = max(1, (int) round($width * $scale));
    $newHeight = max(1, (int) round($height * $scale));

    $canvas = imagecreatetruecolor($newWidth, $newHeight);

    if ($mime === 'image/png' || $mime === 'image/webp') {
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefilledrectangle($canvas, 0, 0, $newWidth, $newHeight, $transparent);
    }

    imagecopyresampled(
        $canvas,
        $source,
        0,
        0,
        0,
        0,
        $newWidth,
        $newHeight,
        $width,
        $height
    );

    $targetDir = __DIR__ . '/../' . trim($directory, '/');

    if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
        imagedestroy($canvas);
        imagedestroy($source);
        throw new RuntimeException('Folder upload tidak dapat dibuat.');
    }

    // WebP is preferred for much smaller web images while keeping transparency.
    $extension = function_exists('imagewebp') ? 'webp' : match ($mime) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        default => 'webp',
    };

    $name = bin2hex(random_bytes(16)) . '.' . $extension;
    $target = $targetDir . '/' . $name;

    $saved = match ($extension) {
        'webp' => imagewebp($canvas, $target, $quality),
        'jpg' => imagejpeg($canvas, $target, $quality),
        'png' => imagepng($canvas, $target, 6),
        default => false,
    };

    imagedestroy($canvas);
    imagedestroy($source);

    if (!$saved || !is_file($target)) {
        throw new RuntimeException('Gambar gagal disimpan setelah dikompres.');
    }

    return trim($directory, '/') . '/' . $name;
}

function upload_pdf(
    string $field,
    string $directory = 'uploads/documents'
): ?string {
    return upload_file(
        $field,
        $directory,
        ['application/pdf' => 'pdf'],
        10 * 1024 * 1024
    );
}

function img_url(
    ?string $path,
    string $fallback = 'assets/images/placeholder.svg'
): string {
    return url($path ?: $fallback);
}

function post_string(string $key, int $max = 10000): string
{
    return mb_substr(
        trim((string) ($_POST[$key] ?? '')),
        0,
        $max
    );
}

function safe_slug(string $value): string
{
    $value = mb_strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/u', '-', $value) ?? '';

    return trim($value, '-');
}

function table_exists(string $table): bool
{
    global $pdo;

    try {
        $statement = $pdo->prepare(
            'SELECT 1 FROM information_schema.tables ' .
            'WHERE table_schema = DATABASE() AND table_name = ?'
        );

        $statement->execute([$table]);

        return (bool) $statement->fetchColumn();
    } catch (Throwable) {
        return false;
    }
}


function ensure_facility_schema(): void
{
    global $pdo;

    try {
        if (!table_exists('facilities')) {
            $pdo->exec("CREATE TABLE facilities (\n                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n                name VARCHAR(180) NOT NULL,\n                image VARCHAR(255),\n                description TEXT,\n                category VARCHAR(80) NOT NULL DEFAULT 'Fasilitas Umum',\n                location VARCHAR(180),\n                capacity VARCHAR(80),\n                is_featured TINYINT(1) NOT NULL DEFAULT 0,\n                status ENUM('published','draft') NOT NULL DEFAULT 'published',\n                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n            ) ENGINE=InnoDB");
        } else {
            $columns = $pdo->query("SHOW COLUMNS FROM facilities")->fetchAll(PDO::FETCH_COLUMN);
            $alter = [];
            if (!in_array('category', $columns, true)) $alter[] = "ADD COLUMN category VARCHAR(80) NOT NULL DEFAULT 'Fasilitas Umum' AFTER description";
            if (!in_array('location', $columns, true)) $alter[] = "ADD COLUMN location VARCHAR(180) NULL AFTER category";
            if (!in_array('capacity', $columns, true)) $alter[] = "ADD COLUMN capacity VARCHAR(80) NULL AFTER location";
            if (!in_array('is_featured', $columns, true)) $alter[] = "ADD COLUMN is_featured TINYINT(1) NOT NULL DEFAULT 0 AFTER capacity";
            if (!in_array('status', $columns, true)) $alter[] = "ADD COLUMN status ENUM('published','draft') NOT NULL DEFAULT 'published' AFTER is_featured";
            if ($alter) $pdo->exec("ALTER TABLE facilities " . implode(', ', $alter));
        }

        if (!table_exists('facility_images')) {
            $pdo->exec("CREATE TABLE facility_images (\n                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n                facility_id INT UNSIGNED NOT NULL,\n                image VARCHAR(255) NOT NULL,\n                sort_order INT NOT NULL DEFAULT 0,\n                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n                INDEX idx_facility_images_facility (facility_id),\n                CONSTRAINT fk_facility_images_facility FOREIGN KEY (facility_id) REFERENCES facilities(id) ON DELETE CASCADE\n            ) ENGINE=InnoDB");
        }
    } catch (Throwable) {
        // Public/admin pages remain usable if an older DB user lacks ALTER privileges.
    }
}

function count_rows(string $table): int
{
    if (
        !preg_match('/^[a-z_]+$/', $table) ||
        !table_exists($table)
    ) {
        return 0;
    }

    global $pdo;

    return (int) $pdo
        ->query("SELECT COUNT(*) FROM `{$table}`")
        ->fetchColumn();
}

function active_page(string $file): string
{
    return basename($_SERVER['PHP_SELF'] ?? '') === $file
        ? 'active'
        : '';
}


function youtube_video_id(string $url): string
{
    $url = trim($url);

    if ($url === '') {
        return '';
    }

    $parts = parse_url($url);
    $host = strtolower((string) ($parts['host'] ?? ''));
    $host = preg_replace('/^www\./', '', $host) ?? '';

    if ($host === 'youtu.be') {
        $id = trim((string) ($parts['path'] ?? ''), '/');
    } elseif ($host === 'youtube.com' || $host === 'm.youtube.com') {
        parse_str((string) ($parts['query'] ?? ''), $query);
        $id = (string) ($query['v'] ?? '');

        if ($id === '') {
            $path = trim((string) ($parts['path'] ?? ''), '/');
            $segments = $path === '' ? [] : explode('/', $path);
            $id = in_array($segments[0] ?? '', ['embed', 'shorts', 'live'], true)
                ? (string) ($segments[1] ?? '')
                : '';
        }
    } else {
        return '';
    }

    return preg_match('/^[A-Za-z0-9_-]{11}$/', $id)
        ? $id
        : '';
}

function instagram_embed_url(string $url): string
{
    $url = trim($url);

    if ($url === '') {
        return '';
    }

    $parts = parse_url($url);
    $host = strtolower((string) ($parts['host'] ?? ''));
    $host = preg_replace('/^www\./', '', $host) ?? '';

    if ($host !== 'instagram.com') {
        return '';
    }

    $path = trim((string) ($parts['path'] ?? ''), '/');

    if (!preg_match('/^(p|reel|tv)\/[A-Za-z0-9_-]+$/', $path)) {
        return '';
    }

    return 'https://www.instagram.com/' . $path . '/';
}

function google_maps_embed_url(
    string $latitude,
    string $longitude,
    string $address = ''
): string {
    if (
        is_numeric($latitude)
        && is_numeric($longitude)
    ) {
        $lat = (float) $latitude;
        $lng = (float) $longitude;

        if (
            $lat >= -90 && $lat <= 90
            && $lng >= -180 && $lng <= 180
        ) {
            return 'https://www.google.com/maps?q='
                . rawurlencode($lat . ',' . $lng)
                . '&output=embed';
        }
    }

    $address = trim($address);

    if ($address === '') {
        return '';
    }

    return 'https://www.google.com/maps?q='
        . rawurlencode($address)
        . '&output=embed';
}


/** Published page-copy override stored in the existing settings table. */
function cms_text(string $page, string $field, string $default = ''): string
{
    $page = preg_replace('/[^a-z0-9_-]/', '', strtolower($page)) ?? '';
    $field = preg_replace('/[^a-z0-9_-]/', '', strtolower($field)) ?? '';
    if ($page === '' || $field === '') return $default;
    $value = setting('cms_page_' . $page . '_' . $field, '');
    if ($value === '' && is_logged_in() && isset($_GET['cms_preview']) && (string)($_SESSION['cms_preview_key'] ?? '') !== '' && hash_equals((string)$_SESSION['cms_preview_key'], (string)$_GET['cms_preview'])) {
        $value = setting('cms_draft_' . $page . '_' . $field, '');
    }
    return $value !== '' ? $value : $default;
}
