<?php

declare(strict_types=1);

require_once __DIR__ . '/config/app.php';

$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
);

if (!$id) {
    http_response_code(404);
    exit('Dokumen tidak ditemukan.');
}

$statement = $pdo->prepare(
    'SELECT title, file_path FROM ppdb_documents WHERE id = ? LIMIT 1'
);

$statement->execute([$id]);
$document = $statement->fetch();

if (!$document || empty($document['file_path'])) {
    http_response_code(404);
    exit('Dokumen tidak ditemukan.');
}

$relativePath = ltrim(
    str_replace('\\', '/', (string) $document['file_path']),
    '/'
);

if (
    str_contains($relativePath, '..')
    || !str_starts_with($relativePath, 'uploads/ppdb/')
) {
    http_response_code(403);
    exit('Akses dokumen ditolak.');
}

$filePath = __DIR__ . '/' . $relativePath;

if (
    !is_file($filePath)
    || !is_readable($filePath)
) {
    http_response_code(404);
    exit('File PDF belum tersedia.');
}

$downloadName = preg_replace(
    '/[^a-zA-Z0-9_-]+/',
    '-',
    pathinfo((string) $document['title'], PATHINFO_FILENAME)
);

$downloadName = trim((string) $downloadName, '-');

if ($downloadName === '') {
    $downloadName = 'dokumen-ppdb';
}

$downloadName .= '.pdf';

header('Content-Type: application/pdf');
header('Content-Length: ' . (string) filesize($filePath));
header(
    'Content-Disposition: attachment; filename="'
    . $downloadName
    . '"'
);
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store, max-age=0');

readfile($filePath);
exit;
