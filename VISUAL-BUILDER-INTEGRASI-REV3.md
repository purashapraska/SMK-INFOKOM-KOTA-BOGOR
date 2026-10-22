# Visual Builder — Integrasi halaman REV3

## Halaman yang diintegrasikan

Visual Builder sekarang mengenali halaman berikut untuk proses load, draft, snapshot, dan publikasi: Jurusan, Guru & Tenaga Kependidikan, Informasi/Berita, Prestasi, Fasilitas, Galeri, dan Kunjungan Industri. Halaman yang sebelumnya didukung tetap dipertahankan.

## File yang berubah

- `includes/visual-builder.php`: daftar halaman publishable dan perlindungan route detail Jurusan (`jurusan.php?id=...`).
- `admin/visual-builder.php`: Kunjungan Industri ditambahkan ke daftar halaman editor.
- `admin/visual-builder-api.php`: Kunjungan Industri diizinkan API; registry page yang belum ada didaftarkan tanpa menghapus revision lama.
- Tujuh file halaman publik: menambahkan `vb_begin()` / `vb_end()` dengan fallback ke PHP asli.
- `database/migrations/20261021_visual_builder_kunjungan_industri.sql`: registrasi halaman Kunjungan Industri secara idempotent.

## Penting sebelum publikasi

- Simpan draft dan periksa preview sebelum menekan Publikasikan.
- Jurusan detail `?id=...` tetap menggunakan PHP asli supaya snapshot daftar jurusan tidak menggantikan halaman detail.
- Konten yang berasal dari database pada halaman baru harus diuji setelah publikasi; area dinamis yang belum diberi `vb_dyn_open()` / `vb_dyn_close()` berpotensi tersimpan sebagai snapshot. Untuk data yang harus selalu live, tambahkan dynamic wrapper khusus sebelum mengandalkan publikasi sebagai layout produksi.
- Pengujian yang dilakukan pada paket ini mencakup lint sintaks PHP. Pengujian runtime browser, session, API, dan database perlu dilakukan di Laragon karena bergantung pada instalasi lokal.
