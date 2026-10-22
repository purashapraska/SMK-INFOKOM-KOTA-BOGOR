<?php
declare(strict_types=1);

function ensure_industrial_visit_schema(): void
{
    global $pdo;
    $pdo->exec("CREATE TABLE IF NOT EXISTS industrial_visits (
        id INT UNSIGNED NOT NULL PRIMARY KEY,
        title VARCHAR(220) NOT NULL,
        institution VARCHAR(220) NOT NULL,
        visit_date DATE NULL,
        duration_label VARCHAR(100) NOT NULL DEFAULT 'Sekitar satu minggu',
        purpose TEXT NULL,
        story TEXT NULL,
        hero_image VARCHAR(255) NULL,
        middle_image VARCHAR(255) NULL,
        bottom_image VARCHAR(255) NULL,
        institution_logo VARCHAR(255) NULL,
        status ENUM('published','draft') NOT NULL DEFAULT 'published',
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB");
    $stmt = $pdo->prepare("SELECT id FROM industrial_visits WHERE id = 1");
    $stmt->execute();
    if (!$stmt->fetchColumn()) {
        $insert = $pdo->prepare("INSERT INTO industrial_visits
            (id,title,institution,visit_date,duration_label,purpose,story,status)
            VALUES (1,?,?,?,?,?,?, 'published')");
        $insert->execute([
            'Menjelajahi Dunia Teknologi Bersama AMIKOM',
            'Universitas AMIKOM Yogyakarta',
            '2025-02-20',
            'Sekitar satu minggu',
            'Mengenal lingkungan pendidikan tinggi dan memperluas wawasan siswa mengenai dunia teknologi.',
            'Kunjungan Industri menjadi kesempatan bagi siswa SMK INFOKOM Kota Bogor untuk mendapatkan pengalaman belajar di luar lingkungan sekolah. Cerita kegiatan dapat dilengkapi melalui panel admin berdasarkan dokumentasi dan pengalaman sebenarnya.'
        ]);
    }
}
