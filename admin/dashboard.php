<?php

declare(strict_types=1);

$title = 'Dashboard';

require __DIR__ . '/_header.php';

$statisticsKeys = [
    'students' => 'Jumlah siswa',
    'teachers' => 'Jumlah guru',
    'alumni' => 'Jumlah alumni',
    'partners' => 'Jumlah mitra industri',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save_school_statistics') {
    if (!verify_csrf($_POST['_csrf'] ?? null)) {
        flash('error', 'Sesi formulir tidak valid. Silakan muat ulang halaman lalu coba lagi.');
    } else {
        try {
            foreach ($statisticsKeys as $key => $label) {
                $rawValue = $_POST['stats'][$key] ?? null;
                if (!is_scalar($rawValue) || !preg_match('/^\d{1,9}$/', (string) $rawValue)) {
                    throw new InvalidArgumentException('Nilai ' . $label . ' harus berupa bilangan bulat nonnegatif.');
                }
                save_setting('school_stat_' . $key, (string) (int) $rawValue);
            }
            flash('success', 'Statistik sekolah berhasil disimpan dan akan ditampilkan di website pengunjung.');
        } catch (Throwable $exception) {
            flash('error', $exception instanceof InvalidArgumentException ? $exception->getMessage() : 'Statistik gagal disimpan. Periksa koneksi database.');
        }
    }
}

$schoolStatistics = [
    'students' => setting('school_stat_students', '300'),
    'teachers' => setting('school_stat_teachers', (string) count_rows('teachers')),
    'alumni' => setting('school_stat_alumni', '0'),
    'partners' => setting('school_stat_partners', (string) count_rows('bkk_partners')),
];

$cards = [
    [
        'label' => 'Siswa',
        'table' => 'students',
    ],
    [
        'label' => 'Guru',
        'table' => 'teachers',
    ],
    [
        'label' => 'Jurusan',
        'table' => 'majors',
    ],
    [
        'label' => 'YouTube',
        'table' => 'youtube_videos',
    ],
    [
        'label' => 'Fasilitas',
        'table' => 'facilities',
    ],
    [
        'label' => 'Prestasi',
        'table' => 'achievements',
    ],
    [
        'label' => 'PKL & Magang',
        'table' => 'internships',
    ],
    [
        'label' => 'Lowongan BKK',
        'table' => 'jobs',
    ],
];

$recentVideos = [];

if (table_exists('youtube_videos')) {
    $recentVideos = $pdo
        ->query(
            'SELECT id, title, category, created_at '
            . 'FROM youtube_videos '
            . 'ORDER BY id DESC '
            . 'LIMIT 5'
        )
        ->fetchAll();
}

$recentRegistrations = [];

if (table_exists('ppdb_registrations')) {
    $recentRegistrations = $pdo
        ->query(
            'SELECT id, name, origin_school, status, created_at '
            . 'FROM ppdb_registrations '
            . 'ORDER BY id DESC '
            . 'LIMIT 5'
        )
        ->fetchAll();
}

$activityLogs = [];

if (table_exists('activity_logs')) {
    $activityLogs = $pdo
        ->query(
            'SELECT action, description, created_at '
            . 'FROM activity_logs '
            . 'ORDER BY id DESC '
            . 'LIMIT 8'
        )
        ->fetchAll();
}

?>

<section class="panel">

    <div class="panel-heading">

        <div>
            <p class="eyebrow">
                CONTROL CENTER
            </p>

            <h2>
                Selamat datang, <?= e($user['name']) ?>.
            </h2>

            <p class="panel-subtitle">
                Kelola seluruh isi website sekolah dari satu tempat.
            </p>
        </div>

        <a
            class="button button-primary"
            href="<?= e(url('admin/settings.php')) ?>"
        >
            Atur Website
        </a>

    </div>

</section>

<section class="panel school-statistics-panel">
    <div class="panel-heading">
        <div>
            <p class="eyebrow">PUBLIC WEBSITE</p>
            <h2>Statistik Sekolah</h2>
            <p class="panel-subtitle">Atur angka yang tampil di statistik halaman depan. Angka ini diisi manual dan tidak dihitung otomatis dari data tabel.</p>
        </div>
    </div>
    <?php if ($message = flash('success')): ?>
        <div class="alert alert-success"><?= e($message) ?></div>
    <?php endif; ?>
    <?php if ($message = flash('error')): ?>
        <div class="alert alert-error"><?= e($message) ?></div>
    <?php endif; ?>
    <form method="post" class="form-grid">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="action" value="save_school_statistics">
        <?php foreach ($statisticsKeys as $key => $label): ?>
            <div class="form-group">
                <label for="stat-<?= e($key) ?>"><?= e($label) ?></label>
                <input id="stat-<?= e($key) ?>" name="stats[<?= e($key) ?>]" type="number" min="0" max="999999999" step="1" required value="<?= e($schoolStatistics[$key]) ?>">
            </div>
        <?php endforeach; ?>
        <div class="form-actions">
            <button class="button button-primary" type="submit">Simpan Statistik</button>
        </div>
    </form>
</section>

<section class="dash-grid">

    <?php foreach ($cards as $card): ?>

        <a
            class="dash-card"
            href="<?= e(
                url(
                    'admin/manage.php?entity='
                    . $card['table']
                )
            ) ?>"
        >
            <span>
                <?= e($card['label']) ?>
            </span>

            <strong>
                <?= e($card['table'] === 'students' ? setting('school_stat_students', '300') : count_rows($card['table'])) ?>
            </strong>
        </a>

    <?php endforeach; ?>

</section>

<section class="dashboard-layout">

    <div>

        <section class="panel">

            <div class="panel-heading">

                <div>
                    <p class="eyebrow">
                        QUICK ACTIONS
                    </p>

                    <h2>
                        Tambahkan konten
                    </h2>
                </div>

            </div>

            <div class="actions-row">

                <a
                    class="button button-primary"
                    href="<?= e(url('admin/manage.php?entity=teachers&action=create')) ?>"
                >
                    + Guru
                </a>

                <a
                    class="button button-primary"
                    href="<?= e(url('admin/manage.php?entity=majors&action=create')) ?>"
                >
                    + Jurusan
                </a>

                <a
                    class="button button-primary"
                    href="<?= e(url('admin/manage.php?entity=youtube_videos&action=create')) ?>"
                >
                    + YouTube
                </a>

                <a
                    class="button button-muted"
                    href="<?= e(url('admin/manage.php?entity=gallery&action=create')) ?>"
                >
                    + Galeri
                </a>

            </div>

        </section>

        <section class="panel">

            <div class="panel-heading">

                <div>
                    <p class="eyebrow">
                        CONTENT FLOW
                    </p>

                    <h2>
                        Video YouTube terbaru
                    </h2>
                </div>

                <a
                    class="button button-muted"
                    href="<?= e(url('admin/manage.php?entity=youtube_videos')) ?>"
                >
                    Kelola Semua
                </a>

            </div>

            <?php if ($recentVideos): ?>

                <div class="admin-list">

                    <?php foreach ($recentVideos as $item): ?>

                        <a
                            class="admin-list-item"
                            href="<?= e(
                                url(
                                    'admin/manage.php?entity=youtube_videos&action=edit&id='
                                    . (int) $item['id']
                                )
                            ) ?>"
                        >
                            <span>
                                <strong>
                                    <?= e($item['title']) ?>
                                </strong>

                                <span>
                                    <?= e($item['category'] ?: 'YouTube') ?>
                                    ·
                                    <?= e($item['created_at']) ?>
                                </span>
                            </span>

                            <b>
                                →
                            </b>
                        </a>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="admin-empty">
                    Belum ada video YouTube. Tambahkan video melalui menu YouTube.
                </div>

            <?php endif; ?>

        </section>

        <section class="panel">

            <div class="panel-heading">

                <div>
                    <p class="eyebrow">
                        ACTIVITY
                    </p>

                    <h2>
                        Aktivitas terbaru
                    </h2>
                </div>

            </div>

            <div class="table-wrap">

                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>
                                Waktu
                            </th>
                            <th>
                                Aksi
                            </th>
                            <th>
                                Deskripsi
                            </th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($activityLogs as $log): ?>

                            <tr>
                                <td>
                                    <?= e($log['created_at']) ?>
                                </td>

                                <td>
                                    <?= e($log['action']) ?>
                                </td>

                                <td>
                                    <?= e($log['description']) ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>

            </div>

        </section>

    </div>

    <div>

        <section class="panel">

            <div class="panel-heading">

                <div>
                    <p class="eyebrow">
                        PPDB
                    </p>

                    <h2>
                        Pendaftar terbaru
                    </h2>
                </div>

                <a
                    class="button button-muted"
                    href="<?= e(url('admin/manage.php?entity=ppdb_registrations')) ?>"
                >
                    Buka
                </a>

            </div>

            <?php if ($recentRegistrations): ?>

                <div class="admin-list">

                    <?php foreach ($recentRegistrations as $item): ?>

                        <a
                            class="admin-list-item"
                            href="<?= e(
                                url(
                                    'admin/manage.php?entity=ppdb_registrations&action=edit&id='
                                    . (int) $item['id']
                                )
                            ) ?>"
                        >
                            <span>
                                <strong>
                                    <?= e($item['name']) ?>
                                </strong>

                                <span>
                                    <?= e($item['origin_school']) ?>
                                </span>
                            </span>

                            <span
                                class="status-pill <?= e($item['status']) ?>"
                            >
                                <?= e($item['status']) ?>
                            </span>
                        </a>

                    <?php endforeach; ?>

                </div>

            <?php else: ?>

                <div class="admin-empty">
                    Belum ada pendaftar PPDB.
                </div>

            <?php endif; ?>

        </section>

        <section class="panel">

            <div class="panel-heading">

                <div>
                    <p class="eyebrow">
                        WEBSITE CONTROL
                    </p>

                    <h2>
                        Contoh fitur
                    </h2>
                </div>

            </div>

            <div class="admin-list">

                <div class="admin-list-item">
                    <span>
                        <strong>
                            Hero Home
                        </strong>
                        <span>
                            Foto, judul, dan teks dapat diganti dari Pengaturan.
                        </span>
                    </span>
                </div>

                <div class="admin-list-item">
                    <span>
                        <strong>
                            Jurusan
                        </strong>
                        <span>
                            Nama, foto, dan deskripsi tampil otomatis di website.
                        </span>
                    </span>
                </div>

                <div class="admin-list-item">
                    <span>
                        <strong>
                            Guru
                        </strong>
                        <span>
                            Tambahkan guru, jabatan, mata pelajaran, dan foto.
                        </span>
                    </span>
                </div>

                <div class="admin-list-item">
                    <span>
                        <strong>
                            PPDB
                        </strong>
                        <span>
                            Pendaftaran internal tersimpan dan dapat diverifikasi admin.
                        </span>
                    </span>
                </div>

            </div>

        </section>

    </div>

</section>

<?php

require __DIR__ . '/_footer.php';

?>
