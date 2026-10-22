<?php

declare(strict_types=1);

// Reset / buat ulang akun admin. Jalankan dari terminal (Laragon > Terminal):
//   php database/seed_admin.php
//   php database/seed_admin.php email@sekolah.id PasswordBaru123
// Tidak bisa dijalankan lewat browser demi keamanan.
if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Jalankan lewat terminal: php database/seed_admin.php');
}

require_once __DIR__ . '/../config/app.php';

$email = $argv[1] ?? 'admin@infokom.sch.id';
$password = $argv[2] ?? 'Admin123!';
$hash = password_hash($password, PASSWORD_DEFAULT);

$statement = $pdo->prepare(
    'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?) '
    . 'ON DUPLICATE KEY UPDATE password = VALUES(password), role = VALUES(role)'
);

$statement->execute(['Administrator', $email, $hash, 'ADMIN']);

echo "Admin siap: {$email} / {$password}" . PHP_EOL;
