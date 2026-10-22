CREATE DATABASE IF NOT EXISTS school_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE school_db;

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS activity_logs,contact_messages,ppdb_documents,magang_instagram,bkk_partners,jobs,internships,extracurriculars,achievements,gallery,facility_images,facilities,students,teachers,majors,news,settings,users;

SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('ADMIN','OPERATOR') NOT NULL DEFAULT 'OPERATOR',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB;

CREATE TABLE settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)
ENGINE=InnoDB;

CREATE TABLE majors (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(180) NOT NULL,
    image VARCHAR(255),
    website VARCHAR(500),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB;

CREATE TABLE teachers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    image VARCHAR(255),
    position VARCHAR(120),
    subject VARCHAR(120),
    education VARCHAR(30) NOT NULL DEFAULT 'S1',
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB;

CREATE TABLE students (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    nisn VARCHAR(30),
    class_name VARCHAR(80),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB;

CREATE TABLE news (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(220) NOT NULL,
    image VARCHAR(255),
    category VARCHAR(100),
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB;

CREATE TABLE facilities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(180) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    category VARCHAR(80) NOT NULL DEFAULT 'Fasilitas Umum',
    location VARCHAR(180),
    capacity VARCHAR(80),
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB;

CREATE TABLE facility_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    facility_id INT UNSIGNED NOT NULL,
    image VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_facility_images_facility (facility_id),
    CONSTRAINT fk_facility_images_facility FOREIGN KEY (facility_id) REFERENCES facilities(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE achievements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(220) NOT NULL,
    image VARCHAR(255),
    year YEAR,
    major VARCHAR(20),
    level VARCHAR(30),
    rank_label VARCHAR(80),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB;

CREATE TABLE gallery (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    instagram_url VARCHAR(500) NOT NULL,
    description TEXT,
    status ENUM('published','draft') DEFAULT 'published',
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB;

CREATE TABLE extracurriculars (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(180) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB;

CREATE TABLE internships (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(220) NOT NULL,
    type VARCHAR(60),
    image VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB;

CREATE TABLE magang_instagram (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(220) NOT NULL,
    instagram_url VARCHAR(500) NOT NULL,
    description TEXT,
    status ENUM('published','draft') DEFAULT 'published',
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB;

CREATE TABLE jobs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company VARCHAR(180) NOT NULL,
    position VARCHAR(180) NOT NULL,
    location VARCHAR(150),
    description TEXT,
    deadline DATE,
    apply_url VARCHAR(500),
    status ENUM('published','draft') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS bkk_partners (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(220) NOT NULL,
    category VARCHAR(100) DEFAULT 'Mitra Industri',
    logo VARCHAR(255),
    description TEXT,
    website VARCHAR(500),
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    status ENUM('published','draft') DEFAULT 'published',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO bkk_partners (name, category, logo, description, website, status, sort_order) VALUES
('Bank Syariah Indonesia (BSI)', 'Mitra Industri', 'assets/images/bkk/partners/individual2/bsi.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 1),
('BONET IT Solution Partner', 'Mitra Industri', 'assets/images/bkk/partners/individual2/bonet.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 2),
('MSV Studio', 'Mitra Industri', 'assets/images/bkk/partners/individual2/msv-studio.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 3),
('Cakrawala Digital Indonesia', 'Mitra Industri', 'assets/images/bkk/partners/individual2/cakrawala-digital.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 4),
('Citraweb Solusi Teknologi', 'Mitra Industri', 'assets/images/bkk/partners/individual2/citraweb.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 5),
('Bank Mandiri', 'Mitra Industri', 'assets/images/bkk/partners/individual2/bank-mandiri.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 6),
('Bank BJB Syariah', 'Mitra Industri', 'assets/images/bkk/partners/individual2/bank-bjb-syariah.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 7),
('Laku', 'Mitra Industri', 'assets/images/bkk/partners/individual2/laku.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 8),
('IPB Press', 'Mitra Industri', 'assets/images/bkk/partners/individual2/ipb-press.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 9),
('Bharata Tour Organizer', 'Mitra Industri', 'assets/images/bkk/partners/individual2/bharata-tour-organizer.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 10),
('Jawa Pos', 'Mitra Industri', 'assets/images/bkk/partners/individual2/jawa-pos.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 11),
('Mega Motor', 'Mitra Industri', 'assets/images/bkk/partners/individual2/mega-motor.png', 'Mitra industri yang tercantum pada brosur resmi SPMK INFOKOM Kota Bogor.', '', 'published', 12),
('Almadita Energy Solusi', 'Mitra Industri', 'assets/images/bkk/partners/individual2/almadita.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 13),
('by.U', 'Mitra Industri', 'assets/images/bkk/partners/individual2/byu.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 14),
('Anteraja', 'Mitra Industri', 'assets/images/bkk/partners/individual2/anteraja.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 15),
('Taman Safari Bogor', 'Mitra Industri', 'assets/images/bkk/partners/individual2/taman-safari-bogor.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 16),
('MCM', 'Mitra Industri', 'assets/images/bkk/partners/individual2/mcm.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 17),
('MUM', 'Mitra Industri', 'assets/images/bkk/partners/individual2/mum.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 18),
('Tribunnews', 'Mitra Industri', 'assets/images/bkk/partners/individual2/tribunnews.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 19),
('PMI', 'Mitra Industri', 'assets/images/bkk/partners/individual2/pmi.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 20),
('Sekisho Group', 'Mitra Industri', 'assets/images/bkk/partners/individual2/sekisho-group.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 21),
('Sakura Edukasi', 'Mitra Industri', 'assets/images/bkk/partners/individual2/sakura-edukasi.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 22),
('Azalee Group', 'Mitra Industri', 'assets/images/bkk/partners/individual2/azalee-group.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 23),
('Artificial Intelligence Center Indonesia (AICI)', 'Mitra Industri', 'assets/images/bkk/partners/individual2/aici.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 24),
('Tenma Hospital Group', 'Mitra Industri', 'assets/images/bkk/partners/individual2/tenma-hospital-group.png', 'Mitra industri yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 25),
('Institut Pertanian Bogor (IPB)', 'Mitra Pendidikan', 'assets/images/bkk/partners/individual2/ipb.png', 'Mitra universitas dan pendidikan yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 101),
('Telkom University', 'Mitra Pendidikan', 'assets/images/bkk/partners/individual2/telkom-university.png', 'Mitra universitas dan pendidikan yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 102),
('Universitas Pakuan', 'Mitra Pendidikan', 'assets/images/bkk/partners/individual2/universitas-pakuan.png', 'Mitra universitas dan pendidikan yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 103),
('Universitas Gunadarma', 'Mitra Pendidikan', 'assets/images/bkk/partners/individual2/universitas-gunadarma.png', 'Mitra universitas dan pendidikan yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 104),
('Mitra Universitas Bogor', 'Mitra Pendidikan', 'assets/images/bkk/partners/individual2/universitas-bogor.png', 'Logo mitra pendidikan yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 105),
('Universitas Bunda Mulia', 'Mitra Pendidikan', 'assets/images/bkk/partners/individual2/universitas-bunda-mulia.png', 'Mitra universitas dan pendidikan yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 106),
('Japan Foundation / Japan-related Partner', 'Mitra Pendidikan', 'assets/images/bkk/partners/individual2/japan-foundation.png', 'Logo mitra pendidikan yang tercantum pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 107);

CREATE TABLE IF NOT EXISTS youtube_videos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(220) NOT NULL,
    youtube_url VARCHAR(500) NOT NULL,
    category VARCHAR(100),
    description TEXT,
    status ENUM('published','draft') DEFAULT 'published',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS instagram_posts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(220) NOT NULL,
    instagram_url VARCHAR(500) NOT NULL,
    description TEXT,
    status ENUM('published','draft') DEFAULT 'published',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE ppdb_documents (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(220) NOT NULL,
    category VARCHAR(100),
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB;

CREATE TABLE ppdb_registrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    origin_school VARCHAR(180) NOT NULL,
    phone VARCHAR(30),
    address TEXT,
    status ENUM('pending','verified','rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB;

CREATE TABLE contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(30),
    subject VARCHAR(200),
    message TEXT NOT NULL,
    status ENUM('unread','read') DEFAULT 'unread',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
ENGINE=InnoDB;

CREATE TABLE activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX(user_id),
    CONSTRAINT fk_log_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL
)
ENGINE=InnoDB;

INSERT INTO users(name,email,password,role) VALUES('Administrator','admin@infokom.sch.id','$2y$12$PpExDAq7yoH3J.10wV1eIeOie7X7SSsqG0KbQxDQMUy4nriAU8lNG','ADMIN');

INSERT INTO settings(setting_key,setting_value) VALUES
('school_name','SMK Infokom Kota Bogor'),('school_city','Bogor'),('school_address','Jl. Letjen Ibrahim Adjie No.178, RT.03/RW.08, Sindangbarang, Kecamatan Bogor Barat, Kota Bogor, Jawa Barat 16117.'),('school_phone','0251-8348108'),('school_email','smk.infokom@gmail.com'),('school_description','SMK Infokom Kota Bogor berfokus pada pengembangan kompetensi teknologi, karakter, dan kesiapan menghadapi dunia industri.'),('running_text','Penerimaan Peserta Didik Baru 2026 • SMK Infokom Kota Bogor • RPL • TKJ • DKV • PSPT • Kegiatan siswa • Prestasi • PKL & Magang • BKK'),('vision','Menjadi sekolah kejuruan yang unggul, berkarakter, dan adaptif terhadap perkembangan teknologi.'),('mission','Menyelenggarakan pembelajaran berbasis kompetensi, memperkuat karakter, dan membangun kemitraan dengan dunia industri.'),('primary_color','#0F4C81'),('accent_color','#F4C542'),('logo_path','assets/images/logo-infokom.svg'),('hero_image','assets/images/placeholder.svg'),('login_image','assets/images/placeholder.svg'),('welcome_image','assets/images/placeholder.svg'),('hero_title','Membentuk Generasi Digital yang Kompeten.'),('hero_text','Ruang belajar untuk membangun kompetensi teknologi, karakter, dan kesiapan menghadapi dunia industri.'),('welcome_title','Selamat datang di SMK Infokom Kota Bogor.'),('welcome_text','SMK Infokom Kota Bogor hadir untuk membantu peserta didik mengembangkan keterampilan yang relevan, disiplin belajar, serta pengalaman yang mendekatkan mereka dengan kebutuhan dunia kerja.'),('welcome_name','Ir. Hj. Liliek Rahmaningsih, M.MPd'),('welcome_position','Kepala Sekolah'),('instagram_url',''),('facebook_url',''),('youtube_url','https://www.youtube.com/watch?v=rB6AtNeqM98'),('tiktok_url',''),('whatsapp_number',''),('whatsapp_message','Halo, saya ingin mendapatkan informasi tentang SMK Infokom Kota Bogor.'),('bkk_logo',''),('location_address','Jl. Letjen Ibrahim Adjie No.178, RT.03/RW.08, Sindangbarang, Kecamatan Bogor Barat, Kota Bogor, Jawa Barat 16117.'),('location_latitude','-6.597300000000'),('location_longitude','106.779300000000'),('ppdb_url',''),('ppdb_intro','Temukan jadwal, persyaratan, panduan, dan dokumen pendaftaran peserta didik baru.'),('pkl_intro','Program PKL dan magang membantu siswa mengenal budaya kerja dan menerapkan kompetensi.'),('bkk_intro','BKK menyediakan informasi lowongan, layanan karier, dan hubungan dengan mitra industri.');

INSERT INTO majors(name,image,description) VALUES
('Rekayasa Perangkat Lunak','assets/images/jurusan/rpl.png','Pengembangan aplikasi, website, dan solusi perangkat lunak.'),
('Teknik Komputer dan Jaringan','assets/images/jurusan/tkj.png','Jaringan komputer, sistem, perangkat, dan infrastruktur teknologi.'),
('Desain Komunikasi Visual','assets/images/jurusan/dkv.png','Komunikasi visual, desain grafis, branding, dan produksi karya digital.'),
('Produksi dan Siaran Program Televisi','assets/images/jurusan/pspt.png','Produksi video, penyiaran, tata kamera, audio, editing, dan pengelolaan program media.');

INSERT INTO settings (setting_key, setting_value) VALUES
('school_hours_weekday','Senin – Jumat · 07.00 – 17.00'),
('school_hours_saturday','Sabtu · 07.00 – 12.30'),
('school_hours_sunday','Minggu · Tutup')
ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value);
