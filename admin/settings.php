<?php

declare(strict_types=1);

$title = 'Pengaturan Website';

require_once __DIR__ . '/../config/app.php';

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verify_csrf($_POST['_csrf'] ?? null)) {
        flash('error', 'CSRF tidak valid.');
        redirect('admin/settings.php');
    }

    try {
        // Keep one canonical address so Home and Contact never drift apart.
        if (array_key_exists('school_address', $_POST)) {
            $_POST['location_address'] = post_string('school_address', 20000);
        }

        $keys = [
            'school_name',
            'school_city',
            'school_address',
            'school_phone',
            'school_email',
            'school_description',
            'vision',
            'mission',
            'hero_title',
            'hero_text',
            'welcome_title',
            'welcome_text',
            'welcome_name',
            'welcome_position',
            'instagram_url',
            'facebook_url',
            'youtube_url',
            'tiktok_url',
            'whatsapp_number',
            'whatsapp_message',
            'location_address',
            'location_latitude',
            'location_longitude',
            'school_hours_weekday',
            'school_hours_saturday',
            'school_hours_sunday',
            'ppdb_url',
            'ppdb_intro',
            'pkl_intro',
            'bkk_intro',
            'running_text',
            'information_hero_title',
            'information_hero_subtitle',
        ];

        foreach ($keys as $key) {
            save_setting(
                $key,
                post_string($key, 20000)
            );
        }

        save_setting(
            'primary_color',
            safe_color(
                post_string('primary_color'),
                '#0F4C81'
            )
        );

        save_setting(
            'accent_color',
            safe_color(
                post_string('accent_color'),
                '#F4C542'
            )
        );

        $uploads = [
            [
                'field' => 'logo_file',
                'key' => 'logo_path',
                'directory' => 'uploads/settings',
            ],
            [
                'field' => 'hero_file',
                'key' => 'hero_image',
                'directory' => 'uploads/settings',
            ],
            [
                'field' => 'login_file',
                'key' => 'login_image',
                'directory' => 'uploads/settings',
            ],
            [
                'field' => 'welcome_file',
                'key' => 'welcome_image',
                'directory' => 'uploads/settings',
            ],
            [
                'field' => 'favicon_file',
                'key' => 'site_favicon',
                'directory' => 'uploads/settings',
            ],
            [
                'field' => 'information_hero_logo_file',
                'key' => 'information_hero_logo',
                'directory' => 'uploads/settings',
            ],
            [
                'field' => 'facility_hero_logo_file',
                'key' => 'facility_hero_logo',
                'directory' => 'uploads/settings',
            ],
            [
                'field' => 'gallery_hero_logo_file',
                'key' => 'gallery_hero_logo',
                'directory' => 'uploads/settings',
            ],
            [
                'field' => 'bkk_logo_file',
                'key' => 'bkk_logo',
                'directory' => 'uploads/settings',
            ],
        ];

        foreach ($uploads as $upload) {
            $path = upload_image(
                $upload['field'],
                $upload['directory']
            );

            if ($path !== null) {
                save_setting(
                    $upload['key'],
                    $path
                );
            }
        }

        log_activity(
            'settings',
            'Pengaturan website diperbarui'
        );

        flash(
            'success',
            'Pengaturan berhasil disimpan.'
        );
    } catch (Throwable $exception) {
        flash(
            'error',
            $exception->getMessage()
        );
    }

    redirect('admin/settings.php');
}

require __DIR__ . '/_header.php';

$success = flash('success');
$error = flash('error');

?>
<section class="panel">

    <div class="panel-heading">
        <div>
            <p class="eyebrow">WEBSITE CONTROL</p>

            <h2>
                Pengaturan website
            </h2>

            <p class="panel-subtitle">
                Semua identitas, gambar utama, kontak, dan konten pengantar
                dapat diubah tanpa menyentuh kode frontend.
            </p>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="notice success">
            <?= e($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="notice error">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <form
        class="admin-form"
        method="post"
        enctype="multipart/form-data"
    >
        <input
            type="hidden"
            name="_csrf"
            value="<?= e(csrf_token()) ?>"
        >

        <div class="form-grid-admin">

            <label>
                Nama sekolah
                <input
                    name="school_name"
                    value="<?= e(setting('school_name', APP_NAME)) ?>"
                >
            </label>

            <label>
                Kota
                <input
                    name="school_city"
                    value="<?= e(setting('school_city', 'Bogor')) ?>"
                >
            </label>

            <label>
                Alamat
                <input
                    name="school_address"
                    value="<?= e(setting('school_address', 'Jl. Letjen Ibrahim Adjie No.178, RT.03/RW.08, Sindangbarang, Kecamatan Bogor Barat, Kota Bogor, Jawa Barat 16117.')) ?>"
                >
            </label>

            <label>
                Telepon
                <input
                    name="school_phone"
                    value="<?= e(setting('school_phone', '')) ?>"
                >
            </label>

            <label>
                Email
                <input
                    name="school_email"
                    type="email"
                    value="<?= e(setting('school_email', '')) ?>"
                >
            </label>

            <label>
                WhatsApp
                <input
                    name="whatsapp_number"
                    placeholder="62812xxxx"
                    value="<?= e(setting('whatsapp_number', '')) ?>"
                >
            </label>

            <label>
                Warna utama
                <input
                    name="primary_color"
                    value="<?= e(setting('primary_color', '#0F4C81')) ?>"
                >
            </label>

            <label>
                Warna aksen
                <input
                    name="accent_color"
                    value="<?= e(setting('accent_color', '#F4C542')) ?>"
                >
            </label>

            <label>
                Pesan WhatsApp Otomatis
                <textarea name="whatsapp_message" rows="4"><?= e(setting('whatsapp_message', 'Halo, saya ingin mendapatkan informasi tentang SMK Infokom Kota Bogor.')) ?></textarea>
            </label>

            <label>
                Instagram
                <input
                    name="instagram_url"
                    value="<?= e(setting('instagram_url', '')) ?>"
                >
            </label>

            <label>
                Facebook
                <input
                    name="facebook_url"
                    value="<?= e(setting('facebook_url', '')) ?>"
                >
            </label>

            <label>
                YouTube
                <input
                    name="youtube_url"
                    value="<?= e(setting('youtube_url', '')) ?>"
                >
            </label>

            <label>
                TikTok
                <input
                    name="tiktok_url"
                    value="<?= e(setting('tiktok_url', '')) ?>"
                >
            </label>

            <label class="full">
                Running Text / Informasi Berjalan
                <input
                    name="running_text"
                    value="<?= e(setting('running_text', 'Penerimaan Peserta Didik Baru SMK Infokom Kota Bogor • Informasi jurusan • Kegiatan sekolah • Prestasi siswa • PKL & Magang • BKK.')) ?>"
                >
                <small>
                    Teks ini tampil sebagai running text di bawah navbar.
                </small>
            </label>

            <label class="full">
                Deskripsi sekolah
                <textarea name="school_description" rows="4"><?= e(setting('school_description', '')) ?></textarea>
            </label>

            <label>
                Visi
                <textarea name="vision" rows="4"><?= e(setting('vision', '')) ?></textarea>
            </label>

            <label>
                Misi
                <textarea name="mission" rows="4"><?= e(setting('mission', '')) ?></textarea>
            </label>

            <label>
                Judul Hero
                <input
                    name="hero_title"
                    value="<?= e(setting('hero_title', 'Membentuk Generasi Digital yang Kompeten.')) ?>"
                >
            </label>

            <label>
                Teks Hero
                <textarea name="hero_text" rows="4"><?= e(setting('hero_text', '')) ?></textarea>
            </label>

            <label>
                Judul Sambutan
                <input
                    name="welcome_title"
                    value="<?= e(setting('welcome_title', 'Selamat datang di SMK Infokom Kota Bogor.')) ?>"
                >
            </label>

            <label>
                Nama Sambutan
                <input
                    name="welcome_name"
                    value="<?= e(setting('welcome_name', 'Kepala Sekolah')) ?>"
                >
            </label>

            <label>
                Jabatan Sambutan
                <input
                    name="welcome_position"
                    value="<?= e(setting('welcome_position', 'Kepala Sekolah')) ?>"
                >
            </label>

            <label>
                Isi Sambutan
                <textarea name="welcome_text" rows="6"><?= e(setting('welcome_text', '')) ?></textarea>
            </label>

            <label>
                Link PPDB Eksternal (Opsional)
                <input
                    name="ppdb_url"
                    value="<?= e(setting('ppdb_url', '')) ?>"
                >
                <small>
                    Opsional. Tombol Daftar Sekarang utama menggunakan PPDB internal.
                </small>
            </label>

            <label>
                Intro PPDB
                <textarea name="ppdb_intro" rows="4"><?= e(setting('ppdb_intro', '')) ?></textarea>
            </label>

            <label>
                Intro PKL &amp; Magang
                <textarea name="pkl_intro" rows="4"><?= e(setting('pkl_intro', '')) ?></textarea>
            </label>

            <label>
                Intro BKK
                <textarea name="bkk_intro" rows="4"><?= e(setting('bkk_intro', '')) ?></textarea>
            </label>

            <label class="settings-location-address">
                Alamat Lokasi GPS <small>(otomatis mengikuti Alamat Sekolah)</small>
                <textarea name="location_address" rows="3" readonly><?= e(setting('school_address', setting('location_address', 'Jl. Letjen Ibrahim Adjie No.178, RT.03/RW.08, Sindangbarang, Kecamatan Bogor Barat, Kota Bogor, Jawa Barat 16117.'))) ?></textarea>
                <small class="field-help">Ubah alamat utama pada kolom Alamat di atas. Alamat Beranda, Kontak, dan pencarian Google Maps akan mengikuti nilai tersebut setelah disimpan.</small>
            </label>

            <label>
                Latitude
                <input
                    name="location_latitude"
                    inputmode="decimal"
                    placeholder="-6.xxxxx"
                    value="<?= e(setting('location_latitude', '')) ?>"
                >
            </label>

            <label>
                Longitude
                <input
                    name="location_longitude"
                    inputmode="decimal"
                    placeholder="106.xxxxx"
                    value="<?= e(setting('location_longitude', '')) ?>"
                >
            </label>

            <label>
                Jam operasional Senin–Jumat
                <input
                    name="school_hours_weekday"
                    value="<?= e(setting('school_hours_weekday', 'Senin – Jumat · 07.00 – 17.00')) ?>"
                >
            </label>

            <label>
                Jam operasional Sabtu
                <input
                    name="school_hours_saturday"
                    value="<?= e(setting('school_hours_saturday', 'Sabtu · 07.00 – 12.30')) ?>"
                >
            </label>

            <label>
                Jam operasional Minggu
                <input
                    name="school_hours_sunday"
                    value="<?= e(setting('school_hours_sunday', 'Minggu · Tutup')) ?>"
                >
            </label>

            <div class="admin-setting-section full">
                <div>
                    <p class="eyebrow">BRAND IDENTITY</p>
                    <h3>Logo Website dan Logo Hero Halaman</h3>
                    <p class="panel-subtitle">Kelola logo website serta logo khusus pada hero halaman Informasi, Fasilitas, Galeri Instagram, dan BKK. Setiap pengaturan logo berdiri sendiri.</p>
                </div>
            </div>

            <label>
                Logo Website
                <input
                    accept="image/jpeg,image/png,image/webp"
                    name="logo_file"
                    type="file"
                >
                <small class="form-hint">Maks. 10 MB • otomatis dikompres ke WebP</small>

                <?php if (setting('logo_path', '')): ?>
                    <img class="preview" src="<?= e(img_url(setting('logo_path'))) ?>" alt="Preview logo website">
                <?php endif; ?>
            </label>

            <label>
                Favicon / Icon Browser
                <input
                    accept="image/jpeg,image/png,image/webp"
                    name="favicon_file"
                    type="file"
                >
                <small class="form-hint">PNG disarankan • otomatis dikompres</small>

                <?php if (setting('site_favicon', '')): ?>
                    <img class="preview" src="<?= e(img_url(setting('site_favicon'))) ?>" alt="Preview favicon">
                <?php endif; ?>
            </label>

            <label>
                Logo Hero Informasi
                <input
                    accept="image/jpeg,image/png,image/webp"
                    name="information_hero_logo_file"
                    type="file"
                >
                <small class="form-hint">Khusus hero halaman Informasi • otomatis dikompres</small>

                <?php if (setting('information_hero_logo', '')): ?>
                    <img class="preview" src="<?= e(img_url(setting('information_hero_logo'))) ?>" alt="Preview logo hero informasi">
                <?php endif; ?>
            </label>

            <label>
                Logo Hero Fasilitas
                <input
                    accept="image/jpeg,image/png,image/webp"
                    name="facility_hero_logo_file"
                    type="file"
                >
                <small class="form-hint">Logo yang tampil di tengah desain hero Fasilitas • PNG transparan disarankan • otomatis dikompres</small>

                <?php if (setting('facility_hero_logo', '')): ?>
                    <img class="preview" src="<?= e(img_url(setting('facility_hero_logo'))) ?>" alt="Preview logo hero fasilitas">
                <?php endif; ?>
            </label>

            <label>
                Logo Hero Galeri Instagram
                <input
                    accept="image/jpeg,image/png,image/webp"
                    name="gallery_hero_logo_file"
                    type="file"
                >
                <small class="form-hint">Logo dekoratif pada panel kaca hero Galeri Instagram • PNG transparan disarankan • maks. 10 MB, otomatis dikompres</small>

                <?php if (setting('gallery_hero_logo', '')): ?>
                    <img class="preview" src="<?= e(img_url(setting('gallery_hero_logo'))) ?>" alt="Preview logo hero galeri Instagram">
                <?php else: ?>
                    <small class="form-hint">Belum ada logo khusus. Ikon Instagram bawaan akan digunakan.</small>
                <?php endif; ?>
            </label>

            <label>
                Judul Hero Informasi
                <input name="information_hero_title" value="<?= e(setting('information_hero_title', 'Semua cerita sekolah, dalam satu layar.')) ?>">
            </label>

            <label>
                Subjudul Hero Informasi
                <textarea name="information_hero_subtitle" rows="4"><?= e(setting('information_hero_subtitle', 'Temukan dokumentasi, kegiatan, prestasi, dan cerita SMK INFOKOM Kota Bogor melalui video YouTube resmi sekolah.')) ?></textarea>
            </label>

            <label>
                Logo BKK
                <input
                    accept="image/jpeg,image/png,image/webp"
                    name="bkk_logo_file"
                    type="file"
                >
                <small class="form-hint">Maks. 10 MB • otomatis dikompres ke WebP</small>

                <?php if (setting('bkk_logo', '')): ?>
                    <img
                        class="preview"
                        src="<?= e(img_url(setting('bkk_logo'))) ?>"
                        alt="Preview logo BKK"
                    >
                <?php endif; ?>
            </label>

            <label>
                Background Home / Hero
                <input
                    accept="image/jpeg,image/png,image/webp"
                    name="hero_file"
                    type="file"
                >
                <small class="form-hint">Maks. 10 MB • otomatis dikompres ke WebP</small>

                <?php if (setting('hero_image', '')): ?>
                    <img
                        class="preview"
                        src="<?= e(img_url(setting('hero_image'))) ?>"
                        alt="Preview hero"
                    >
                <?php endif; ?>
            </label>

            <label>
                Background Login
                <input
                    accept="image/jpeg,image/png,image/webp"
                    name="login_file"
                    type="file"
                >
                <small class="form-hint">Maks. 10 MB • otomatis dikompres ke WebP</small>
            </label>

            <label>
                Foto Sambutan
                <input
                    accept="image/jpeg,image/png,image/webp"
                    name="welcome_file"
                    type="file"
                >
                <small class="form-hint">Maks. 10 MB • otomatis dikompres ke WebP</small>

                <?php if (setting('welcome_image', '')): ?>
                    <img
                        class="preview"
                        src="<?= e(img_url(setting('welcome_image'))) ?>"
                        alt="Preview sambutan"
                    >
                <?php endif; ?>
            </label>

        </div>

        <button
            class="button button-primary"
            type="submit"
        >
            Simpan Semua Pengaturan
        </button>
    </form>
</section>

<?php

require __DIR__ . '/_footer.php';

?>
