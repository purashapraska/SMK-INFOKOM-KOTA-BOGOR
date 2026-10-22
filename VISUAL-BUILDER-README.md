# Visual Website Builder — Fondasi Tahap 1

Fondasi awal Visual Website Builder memakai GrapesJS, PHP Native, dan MySQL.

## Isi tahap ini
- Menu admin **Visual Website Builder**.
- Editor GrapesJS dengan katalog komponen terkontrol (hero, section, heading, teks, tombol, kartu, gambar).
- Pemilih halaman yang sudah ada dan preview viewport desktop/tablet/mobile.
- Penyimpanan draft dan riwayat revisi ke tabel terpisah.
- Endpoint dibatasi untuk sesi login dengan role `ADMIN`, memakai CSRF token dan prepared statements.
- Draft disimpan terpisah dan **belum memengaruhi website publik**.

## Prasyarat
- PHP 8.4+ dengan PDO MySQL.
- MySQL 8+.
- Browser yang dapat mengakses CDN GrapesJS (unpkg) atau GrapesJS di-host secara lokal.

## Instalasi lokal
1. Backup database dan folder proyek.
2. Salin proyek ke folder uji, jangan menimpa arsip asli.
3. Pilih database pengujian, bukan database produksi.
4. Jalankan secara manual `database/migrations/20261020_visual_builder.sql` pada database yang dipilih. Migrasi ini hanya membuat tabel baru dan mengisi daftar halaman dengan `INSERT ... ON DUPLICATE KEY UPDATE`; tidak menjalankan `DROP TABLE`.
5. Login dengan akun role `ADMIN`.
6. Buka Admin → Visual Website Builder.

## Batasan tahap 1
- Editor dimulai dengan canvas draft, bukan otomatis mengimpor HTML halaman publik yang sudah ada.
- Tombol publikasi belum disediakan.
- Draft belum terhubung ke renderer website publik.
- Gambar pada komponen masih memakai placeholder; integrasi Media Library dan upload aman dikerjakan pada tahap berikutnya.
- Integrasi penuh setiap halaman, pemetaan data dinamis, sanitasi HTML/CSS yang lengkap, publish/rollback, dan pengujian runtime dengan MySQL masih belum selesai.

Jangan menganggap draft sebagai perubahan live. Publikasi baru boleh ditambahkan setelah renderer komponen dan sanitasi final dirancang serta diuji.
