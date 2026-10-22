# SMK INFOKOM KOTA BOGOR — Native PHP CMS

Website profil sekolah dengan CMS Admin, MySQL/MariaDB, PDO, upload media, PPDB internal, BKK, PKL, jurusan interaktif, dan animasi frontend.

## Stack

- PHP 8.2+
- MySQL / MariaDB
- PDO
- HTML5
- CSS3
- JavaScript vanilla
- Laragon / Apache

## Instalasi Lokal

1. Extract project ke:

```text
C:\laragon\www\SMK-INFOKOM-KOTA-BOGOR
```

2. Jalankan Apache dan MySQL dari Laragon.

3. Buka:

```text
http://localhost/SMK-INFOKOM-KOTA-BOGOR/setup.php
```

4. Klik `Buat Database & Tabel`.

5. Buka website:

```text
http://localhost/SMK-INFOKOM-KOTA-BOGOR/
```

6. Login Admin:

```text
http://localhost/SMK-INFOKOM-KOTA-BOGOR/login.php
```

Akun awal:

```text
Email    : admin@infokom.sch.id
Password : Admin123!
```

Setelah instalasi selesai, hapus `setup.php` dari server lokal yang akan dipublikasikan.

## Fitur Frontend

- Hero Home dengan background yang dapat diganti Admin.
- Navbar floating dan active indicator bergerak.
- Mobile navigation.
- Dark/light mode.
- Scroll progress.
- Scroll reveal.
- Counter animation.
- Page transition.
- Hover microinteraction.
- Jurusan interactive detail dengan background blur.
- Guru, fasilitas, berita, prestasi, galeri, ekstrakurikuler, PKL, BKK.
- PPDB internal.
- Floating WhatsApp.
- Social media links.
- Responsive desktop/tablet/mobile.
- Reduced-motion support.

## Fitur Admin

- Dashboard statistik.
- CRUD Jurusan.
- CRUD Guru.
- CRUD Siswa.
- CRUD Berita.
- CRUD Fasilitas.
- CRUD Prestasi.
- CRUD Galeri.
- CRUD Ekstrakurikuler.
- CRUD PKL & Magang.
- CRUD BKK / Lowongan.
- CRUD Dokumen PPDB.
- Verifikasi Pendaftar PPDB.
- Pesan masuk.
- Pengaturan identitas sekolah.
- Upload logo.
- Upload background Home/Hero.
- Upload background Login.
- Upload foto sambutan.
- Pengaturan sosial media.
- Pengaturan warna utama dan aksen.
- Activity log.
- CSRF protection.
- Password hashing.
- PDO prepared statements.
- Upload validation.
- Upload gambar otomatis dikompres dan di-resize maksimal 1600×1600 px.
- Gambar JPG/PNG/WebP disimpan sebagai WebP jika GD mendukung WebP, sehingga ukuran file lebih kecil.
- Batas upload gambar dari website: 10 MB sebelum proses kompresi.

## PPDB Internal

Tombol `Daftar Sekarang` pada halaman PPDB menuju:

```text
ppdb-daftar.php
```

Data disimpan ke tabel:

```text
ppdb_registrations
```

Status awal adalah `pending` dan dapat diubah Admin menjadi `verified` atau `rejected`.

## Struktur Utama

```text
SMK-INFOKOM-KOTA-BOGOR/
├── admin/
├── assets/
│   ├── css/
│   ├── images/
│   └── js/
├── config/
├── database/
├── includes/
├── uploads/
├── index.php
├── profil.php
├── jurusan.php
├── guru.php
├── fasilitas.php
├── berita.php
├── prestasi.php
├── ekstrakurikuler.php
├── pkl.php
├── bkk.php
├── ppdb.php
├── ppdb-daftar.php
├── kontak.php
├── login.php
└── setup.php
```

## Contoh Data

Lihat `CONTOH-ISI.md` untuk contoh satu pengisian setiap modul Admin. Jika ingin memasukkan contoh data ke database, jalankan `database/demo_content.sql` setelah setup. Data tersebut adalah data demo dan dapat dihapus dari Admin.

## Upload Gambar Otomatis

Semua upload gambar dari Admin diproses oleh website. Gambar akan di-resize maksimal **1600×1600 px** dan dikompres dengan kualitas sekitar **82%**. Jika PHP/GD mendukung WebP, hasil akhirnya disimpan sebagai `.webp` agar lebih ringan.

Jika gambar yang dipilih lebih besar dari batas PHP sebelum script berjalan, PHP dapat menolak upload terlebih dahulu. Pada Laragon, pastikan `php.ini` memiliki pengaturan yang cukup, misalnya:

```ini
upload_max_filesize = 10M
post_max_size = 16M
```

Setelah mengubah `php.ini`, restart Apache/Laragon. Pastikan extension GD aktif:

```ini
extension=gd
```

Website akan menampilkan pesan yang jelas jika GD belum aktif atau batas upload PHP terlampaui.

## Update — Mitra Industri

Halaman **Mitra Industri** sekarang berdiri sendiri di `mitra.php` dan sudah ditambahkan sebagai menu langsung di navbar.

- `bkk.php` tetap fokus pada BKK dan lowongan kerja; section Mitra tidak lagi ditampilkan di halaman BKK.
- `mitra.php` berisi halaman Industry Network terpisah dengan hero network, partner showcase, animasi, flow School → BKK → Industry → Career, CTA, dan side drawer detail partner.
- Enam logo partner menggunakan file logo individual yang diberikan: Bank Mandiri, Bank BJB, Bank Syariah Indonesia (BSI), Cakrawala Digital, IPB Press, dan MSV Studio.
- Data partner tetap menggunakan tabel `bkk_partners` agar dapat dikelola melalui admin yang sudah ada.
