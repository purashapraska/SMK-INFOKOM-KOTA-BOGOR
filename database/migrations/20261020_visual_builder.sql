-- Visual Website Builder foundation for SMK INFOKOM.
-- Run manually after backing up the database. This migration does not drop or alter existing tables.
CREATE TABLE IF NOT EXISTS visual_builder_pages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_key VARCHAR(80) NOT NULL UNIQUE,
    page_label VARCHAR(160) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS visual_builder_revisions (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO visual_builder_pages (page_key, page_label) VALUES
('home', 'Beranda'),
('profil', 'Profil Sekolah'),
('jurusan', 'Jurusan'),
('guru', 'Guru & Tenaga Kependidikan'),
('berita', 'Informasi / Berita'),
('prestasi', 'Prestasi'),
('fasilitas', 'Fasilitas'),
('galeri', 'Galeri'),
('kontak', 'Kontak'),
('ppdb', 'PPDB'),
('bkk', 'BKK / Karier'),
('pkl', 'PKL'),
('magang', 'Magang Jepang'),
('ekstrakurikuler', 'Ekstrakurikuler'),
('mitra', 'Mitra Industri')
ON DUPLICATE KEY UPDATE page_label = VALUES(page_label);
