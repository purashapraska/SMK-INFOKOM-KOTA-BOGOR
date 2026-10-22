USE school_db;

-- =========================================================
-- DEMO / CONTENT PACK — SMK INFOKOM KOTA BOGOR
-- =========================================================

INSERT INTO majors (name, image, description) VALUES
('Rekayasa Perangkat Lunak', 'assets/images/placeholder.svg', 'Pembelajaran pemrograman, pengembangan website, aplikasi, basis data, dan rekayasa perangkat lunak.'),
('Teknik Komputer dan Jaringan', 'assets/images/placeholder.svg', 'Mempelajari jaringan komputer, perangkat keras, server, keamanan jaringan, dan administrasi sistem.'),
('Multimedia', 'assets/images/placeholder.svg', 'Mengembangkan kompetensi desain grafis, fotografi, video, animasi, dan produksi konten digital.'),
('Produksi dan Siaran Program Televisi', 'assets/images/placeholder.svg', 'Mempelajari produksi program, penyiaran, tata kamera, audio, editing, dan komunikasi visual.'),
('Desain Komunikasi Visual', 'assets/images/placeholder.svg', 'Mengembangkan kemampuan komunikasi visual, branding, ilustrasi, layout, dan desain digital.');

INSERT INTO teachers (name, image, position, subject, education, bio) VALUES
('Budi Santoso, S.Kom.', 'assets/images/placeholder.svg', 'Guru Produktif', 'Pemrograman Web', 'S1', 'Mendampingi siswa dalam pengembangan aplikasi web dan praktik pemrograman.'),
('Dewi Lestari, S.Kom.', 'assets/images/placeholder.svg', 'Guru Produktif', 'Basis Data', 'S1', 'Mengajarkan konsep basis data, perancangan skema, dan pengolahan data.'),
('Rizky Maulana, S.Kom.', 'assets/images/placeholder.svg', 'Guru Produktif', 'Jaringan Komputer', 'S1', 'Membimbing praktik jaringan komputer, administrasi sistem, dan troubleshooting.'),
('Siti Nurhayati, S.Pd.', 'assets/images/placeholder.svg', 'Guru Normatif/Adaptif', 'Bahasa Indonesia', 'S1', 'Mendorong kemampuan literasi, komunikasi, dan presentasi siswa.'),
('Andri Firmansyah, S.Kom.', 'assets/images/placeholder.svg', 'Guru Produktif', 'Multimedia', 'S1', 'Mendampingi produksi visual, video, desain, dan konten digital siswa.'),
('Nina Kartika, S.Pd.', 'assets/images/placeholder.svg', 'Guru Normatif/Adaptif', 'Bahasa Inggris', 'S1', 'Mengembangkan kemampuan komunikasi Bahasa Inggris untuk kebutuhan akademik dan industri.');

INSERT INTO students (name, nisn, class_name) VALUES
('Andi Pratama', '0012345678', 'XII RPL 1'),
('Raka Ramadhan', '0012345679', 'XI TKJ 1'),
('Nabila Putri', '0012345680', 'XII DKV 1'),
('Fajar Hidayat', '0012345681', 'XI RPL 2'),
('Alya Safitri', '0012345682', 'X MPLB 1'),
('Dimas Akbar', '0012345683', 'XII TKJ 2');

INSERT INTO news (title, image, category, content) VALUES
('SMK Infokom Kota Bogor Mendorong Siswa Siap Menghadapi Era Digital', 'assets/images/placeholder.svg', 'Sekolah', 'Kegiatan pembelajaran dan pengembangan kompetensi teknologi terus diarahkan agar siswa memiliki keterampilan yang relevan dengan kebutuhan dunia industri.'),
('Kegiatan Praktik dan Pembelajaran Berbasis Teknologi', 'assets/images/placeholder.svg', 'Kegiatan Sekolah', 'Siswa mengikuti kegiatan praktik di lingkungan sekolah dengan mengutamakan pengalaman langsung, kolaborasi, dan pemecahan masalah.'),
('Informasi Penerimaan Peserta Didik Baru Tahun 2026', 'assets/images/placeholder.svg', 'PPDB', 'Informasi pendaftaran peserta didik baru SMK Infokom Kota Bogor tahun 2026 tersedia melalui layanan PPDB sekolah.'),
('Pengembangan Kompetensi Siswa melalui Kegiatan Industri', 'assets/images/placeholder.svg', 'Industri', 'Program PKL dan magang menjadi bagian dari pengalaman belajar untuk mengenalkan siswa pada budaya kerja dan kebutuhan industri.');

INSERT INTO facilities (name, image, description) VALUES
('Laboratorium Komputer', 'assets/images/placeholder.svg', 'Laboratorium untuk praktik pemrograman, basis data, jaringan, dan pengembangan teknologi.'),
('Laboratorium Jaringan', 'assets/images/placeholder.svg', 'Ruang praktik untuk simulasi jaringan komputer, server, dan administrasi sistem.'),
('Studio Multimedia', 'assets/images/placeholder.svg', 'Ruang untuk produksi foto, video, audio, desain, dan konten digital.'),
('Ruang Kelas', 'assets/images/placeholder.svg', 'Ruang pembelajaran yang mendukung kegiatan belajar teori dan praktik.'),
('Perpustakaan', 'assets/images/placeholder.svg', 'Sumber belajar untuk menunjang literasi dan kegiatan akademik siswa.'),
('Area Kegiatan Siswa', 'assets/images/placeholder.svg', 'Ruang untuk kegiatan organisasi, ekstrakurikuler, dan pengembangan karakter.');

INSERT INTO achievements (title, image, year, major, level, rank_label, description) VALUES
('Mobile Legends Student Championship', NULL, 2026, 'RPL', 'Nasional', 'Juara 1', 'DEMO: contoh pencapaian esports pelajar tingkat nasional.'),
('Lomba Kompetensi Siswa — Web Technologies', NULL, 2026, 'RPL', 'Nasional', 'Juara 2', 'DEMO: contoh pencapaian pengembangan web pada kompetisi tingkat nasional.'),
('Network Infrastructure Competition', NULL, 2026, 'TKJ', 'Provinsi', 'Juara 1', 'DEMO: contoh kompetisi perancangan dan konfigurasi infrastruktur jaringan.'),
('Digital Poster Championship', NULL, 2026, 'DKV', 'Nasional', 'Juara 1', 'DEMO: contoh pencapaian desain poster digital tingkat nasional.'),
('Short Movie Production Competition', NULL, 2025, 'PSPT', 'Provinsi', 'Juara 2', 'DEMO: contoh kompetisi produksi film pendek pelajar.'),
('UI/UX Design Challenge', NULL, 2025, 'RPL', 'Nasional', 'Juara 1', 'DEMO: contoh kompetisi desain antarmuka dan pengalaman pengguna.'),
('Cyber Security Competition', NULL, 2025, 'RPL', 'Nasional', 'Juara 3', 'DEMO: contoh kompetisi keamanan siber pelajar tingkat nasional.'),
('Video Creative Festival', NULL, 2025, 'PSPT', 'Kota', 'Juara 1', 'DEMO: contoh kompetisi produksi video kreatif tingkat kota.'),
('Network Design Competition', NULL, 2024, 'TKJ', 'Provinsi', 'Juara 2', 'DEMO: contoh kompetisi desain jaringan tingkat provinsi.'),
('Digital Illustration Competition', NULL, 2024, 'DKV', 'Nasional', 'Juara 1', 'DEMO: contoh kompetisi ilustrasi digital tingkat nasional.');


INSERT INTO extracurriculars (name, image, description) VALUES
('Futsal', 'assets/images/placeholder.svg', 'Kegiatan olahraga dan pembinaan kerja sama tim siswa.'),
('Pramuka', 'assets/images/placeholder.svg', 'Pembinaan karakter, disiplin, kepemimpinan, dan kemandirian.'),
('Paskibra', 'assets/images/placeholder.svg', 'Pelatihan kedisiplinan, baris-berbaris, dan kepemimpinan.'),
('Desain & Multimedia', 'assets/images/placeholder.svg', 'Pengembangan kemampuan desain, fotografi, video, dan konten digital.'),
('Programming Club', 'assets/images/placeholder.svg', 'Ruang belajar untuk eksplorasi pemrograman dan proyek teknologi.'),
('English Club', 'assets/images/placeholder.svg', 'Latihan komunikasi bahasa Inggris untuk kebutuhan akademik dan dunia kerja.');

INSERT INTO internships (title, type, image, description) VALUES
('Praktik Kerja Lapangan Industri', 'PKL', 'assets/images/placeholder.svg', 'Program pengalaman kerja siswa di mitra industri untuk mengenal budaya kerja dan menerapkan kompetensi kejuruan.'),
('Program Magang Teknologi', 'Magang', 'assets/images/placeholder.svg', 'Pengalaman praktik di lingkungan kerja yang relevan dengan bidang teknologi dan kompetensi siswa.');

INSERT INTO bkk_partners (name, category, logo, description, website, status, sort_order) VALUES
('Mitra Industri SMK INFOKOM', 'Mitra Industri', 'assets/images/bkk/partners/mitra-industri-brosur.png', 'Logo mitra industri yang ditampilkan pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 1),
('Mitra Universitas & Pendidikan', 'Mitra Pendidikan', 'assets/images/bkk/partners/mitra-universitas-brosur.png', 'Logo mitra universitas dan pendidikan yang ditampilkan pada brosur resmi SPMB SMK INFOKOM Kota Bogor.', '', 'published', 2);

INSERT INTO jobs (company, position, location, description, deadline, apply_url, status) VALUES
('PT Contoh Digital', 'Junior Web Developer', 'Bogor', 'Peluang kerja untuk lulusan bidang pengembangan website.', '2026-12-31', 'https://example.com', 'published'),
('Mitra Kreatif Bogor', 'Junior Graphic Designer', 'Bogor', 'Posisi untuk kandidat dengan kemampuan desain grafis dan konten digital.', '2026-11-30', 'https://example.com', 'published');

-- Media dapat diubah melalui Admin.
INSERT INTO youtube_videos (title, youtube_url, description, status, sort_order) VALUES
('KABAR SEKOLAH (SMK INFOKOM BOGOR) #1', 'https://www.youtube.com/watch?v=rB6AtNeqM98', 'Dokumentasi karya siswa SMK Infokom, termasuk kegiatan MPLS dan ekstrakurikuler.', 'published', 1);
