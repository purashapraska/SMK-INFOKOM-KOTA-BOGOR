# CMS Konten Halaman dan Font

## Konten Halaman
- Masuk ke panel admin dan pilih **Konten Halaman**.
- Pilih halaman, ubah field yang telah dihubungkan, lalu pilih **Simpan Draft** untuk menyimpan tanpa mengubah tampilan publik atau **Publikasikan** untuk menerapkan perubahan.
- Setelah menyimpan draft, tombol **Pratinjau Draft** akan membuka halaman dengan draft di sesi admin tersebut.
- Konten terstruktur seperti Guru, Jurusan, Berita, Fasilitas, Galeri, dan Lowongan tetap dikelola dari menu CRUD masing-masing.
- Nilai disimpan dalam tabel `settings` yang sudah ada; tidak ada migration SQL baru.

## Font
- Pengaturan Font Judul dan Font Teks berada di **Tampilan Website**.
- Variabel font global/per halaman diterapkan lewat CSS dengan prioritas agar aturan font lama pada komponen tidak mengalahkan pengaturan CMS.
- Cache stylesheet publik diberi versi baru agar browser mengambil CSS yang telah diperbarui.

## Batas implementasi
Editor konten menyediakan field judul hero, deskripsi hero, dua judul bagian, label tombol, dan URL tombol. Field hanya memengaruhi komponen halaman yang telah dihubungkan ke `cms_text()`. Fitur ini menjadi fondasi editor konten bertahap, bukan pengganti semua field CRUD atau media library yang telah ada.
