# Panduan Mengubah Tampilan Website SMK INFOKOM

Panduan ini dibuat agar perubahan warna, font, ukuran tulisan, dan background lebih mudah dicari tanpa mengubah struktur project atau database.

## Cara termudah: Dashboard Admin

Buka **Admin > Tampilan / Appearance** (`admin/appearance.php`).

- **Default Global** mengatur warna dan font untuk seluruh halaman yang memakai sistem tema.
- **Per Halaman** mengatur halaman tertentu saja; pilih "Ikuti global" agar halaman mengikuti pengaturan global.
- Warna yang tersedia: judul, teks/paragraf, background halaman, tombol, dan teks tombol.
- Font yang tersedia: font judul dan font teks.

## Pusat variabel CSS

File: `assets/css/style.css`, paling atas pada blok `:root` berkomentar **PUSAT PENGATURAN TEMA WEBSITE**.

| Tujuan | Variabel |
|---|---|
| Warna utama/brand | `--primary` |
| Warna biru gelap | `--primary-dark` |
| Warna aksen kuning | `--accent` |
| Teks utama | `--ink` |
| Teks sekunder | `--muted` |
| Warna judul default | `--heading-color-default` |
| Warna paragraf default | `--text-color-default` |
| Background dasar | `--page-background` |
| Background kartu/panel | `--surface` |
| Background alternatif | `--soft` |
| Border/garis | `--line` |
| Ukuran font dasar | `--font-size-base` |
| Ukuran teks kecil | `--font-size-small` |
| Ukuran paragraf umum | `--font-size-body` |
| Ukuran judul utama | `--font-size-h1` |
| Ukuran judul bagian | `--font-size-h2` |

Contoh: `--accent: #f4c542;` berarti warna kuning. Gunakan format HEX `#RRGGBB`.

**Penting:** variabel ukuran font sudah disediakan sebagai pusat konfigurasi, tetapi tidak semua selector lama menggunakan variabel ukuran tersebut. Beberapa komponen dan halaman memiliki ukuran khusus untuk menjaga desain responsif. Periksa selector terkait sebelum mengubah ukuran secara menyeluruh.

## Jika perubahan CSS tidak terlihat

1. Periksa **Admin > Tampilan > Default Global**.
2. Periksa apakah halaman memakai pengaturan **Per Halaman**.
3. Periksa `includes/public-header.php`, yang menerapkan variabel tema dari database.
4. Cari selector halaman terkait di `assets/css/style.css` dan file PHP halaman itu. Ada halaman dengan warna khusus seperti hero, Mitra, Ekstrakurikuler, PPDB, dan Kunjungan Industri.
5. Muat ulang dengan `Ctrl + F5` untuk membersihkan cache browser.

## Peta file penting

- `assets/css/style.css`: CSS utama untuk halaman publik.
- `assets/css/admin.css`: CSS dashboard admin.
- `assets/js/app.js`: interaksi publik, navbar, dropdown, scroll, dan komponen interaktif.
- `includes/public-header.php`: header publik dan penerapan tema dari database.
- `includes/public-footer.php`: footer publik dan penutup halaman.
- `admin/appearance.php`: pengaturan warna dan font global/per halaman.
- `admin/visual-builder.php` dan `includes/visual-builder.php`: Visual Builder.
- `admin/industrial-visits.php` dan `kunjungan-industri.php`: pengelolaan dan halaman Kunjungan Industri.

## Perlindungan data

Jangan impor ulang `database/database.sql` ke database yang sudah berisi data tanpa backup dan pemeriksaan terlebih dahulu. Panduan ini tidak memerlukan migrasi database.
