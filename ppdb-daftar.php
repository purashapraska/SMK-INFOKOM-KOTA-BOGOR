<?php

declare(strict_types=1);

require_once __DIR__ . '/config/app.php';

$pageTitle = 'Pendaftaran PPDB';

$errors = [];

$old = [
    'name' => '',
    'origin_school' => '',
    'phone' => '',
    'address' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verify_csrf($_POST['_csrf'] ?? null)) {
        $errors[] = 'Sesi formulir sudah kedaluwarsa. Silakan coba lagi.';
    }

    foreach ($old as $key => $value) {
        $old[$key] = post_string($key, 5000);
    }

    if ($old['name'] === '') {
        $errors[] = 'Nama lengkap wajib diisi.';
    }

    if ($old['origin_school'] === '') {
        $errors[] = 'Asal sekolah wajib diisi.';
    }

    if ($old['phone'] === '') {
        $errors[] = 'Nomor WhatsApp wajib diisi.';
    }

    if ($old['address'] === '') {
        $errors[] = 'Alamat wajib diisi.';
    }

    if (!$errors) {

        try {
            $statement = $pdo->prepare(
                'INSERT INTO ppdb_registrations '
                . '(name, origin_school, phone, address, status) '
                . 'VALUES (?, ?, ?, ?, ?)'
            );

            $statement->execute([
                $old['name'],
                $old['origin_school'],
                $old['phone'],
                $old['address'],
                'pending',
            ]);

            $registrationId = (int) $pdo->lastInsertId();

            log_activity(
                'ppdb_registration',
                'Pendaftaran PPDB baru #' . $registrationId
            );

            redirect(
                'ppdb-daftar.php?success='
                . $registrationId
            );
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            $errors[] = 'Pendaftaran gagal disimpan. Silakan coba lagi.';
        }
    }
}

$successId = filter_input(
    INPUT_GET,
    'success',
    FILTER_VALIDATE_INT
) ?: 0;

require_once __DIR__ . '/includes/public-header.php';

?>

<section class="page-hero">
    <div class="container reveal">

        <p class="eyebrow">
            PPDB ONLINE
        </p>

        <h1>
            Mulai langkahmu di Infokom.
        </h1>

        <p>
            Isi data pendaftaran dengan benar.
            Data akan masuk ke dashboard admin untuk
            proses verifikasi.
        </p>

    </div>
</section>

<section class="section">
    <div class="container">

        <?php if ($successId > 0): ?>

            <div class="ppdb-success-card reveal is-visible">

                <div class="success-icon">
                    ✓
                </div>

                <p class="eyebrow">
                    PENDAFTARAN BERHASIL
                </p>

                <h2>
                    Data kamu sudah diterima.
                </h2>

                <p>
                    Simpan nomor pendaftaran berikut
                    untuk referensi.
                </p>

                <div class="registration-number">
                    <span>
                        Nomor Pendaftaran
                    </span>

                    <strong>
                        PPDB-<?= e(
                            str_pad(
                                (string) $successId,
                                5,
                                '0',
                                STR_PAD_LEFT
                            )
                        ) ?>
                    </strong>
                </div>

                <a
                    class="btn btn-primary"
                    href="<?= e(url('ppdb.php')) ?>"
                >
                    Kembali ke PPDB
                </a>

            </div>

        <?php else: ?>

            <?php if ($errors): ?>

                <div class="form-alert">
                    <strong>
                        Periksa data berikut:
                    </strong>

                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li>
                                <?= e($error) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            <?php endif; ?>

            <div class="ppdb-form-layout">

                <div class="ppdb-form-intro reveal">

                    <p class="eyebrow">
                        PROSES PENDAFTARAN
                    </p>

                    <h2>
                        Sederhana,
                        jelas, dan terarah.
                    </h2>

                    <p>
                        Setelah dikirim, data akan masuk ke
                        sistem admin sekolah dengan status
                        Pending.
                    </p>

                    <div class="form-step">
                        <span>01</span>
                        <div>
                            <strong>Isi Data</strong>
                            <small>
                                Lengkapi informasi calon peserta didik.
                            </small>
                        </div>
                    </div>

                    <div class="form-step">
                        <span>02</span>
                        <div>
                            <strong>Verifikasi</strong>
                            <small>
                                Admin memeriksa data pendaftaran.
                            </small>
                        </div>
                    </div>

                    <div class="form-step">
                        <span>03</span>
                        <div>
                            <strong>Selesai</strong>
                            <small>
                                Status pendaftaran dapat diperbarui admin.
                            </small>
                        </div>
                    </div>

                </div>

                <div class="ppdb-form-card reveal">

                    <form
                        method="post"
                        action=""
                        novalidate
                    >

                        <input
                            type="hidden"
                            name="_csrf"
                            value="<?= e(csrf_token()) ?>"
                        >

                        <div class="form-group">
                            <label for="name">
                                Nama Lengkap
                            </label>

                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="<?= e($old['name']) ?>"
                                autocomplete="name"
                                placeholder="Nama lengkap calon peserta didik"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="origin_school">
                                Asal Sekolah
                            </label>

                            <input
                                id="origin_school"
                                name="origin_school"
                                type="text"
                                value="<?= e($old['origin_school']) ?>"
                                placeholder="Contoh: SMP Negeri 1 Bogor"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="phone">
                                Nomor WhatsApp
                            </label>

                            <input
                                id="phone"
                                name="phone"
                                type="tel"
                                value="<?= e($old['phone']) ?>"
                                autocomplete="tel"
                                placeholder="08xxxxxxxxxx"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="address">
                                Alamat
                            </label>

                            <textarea
                                id="address"
                                name="address"
                                rows="5"
                                placeholder="Alamat lengkap"
                                required
                            ><?= e($old['address']) ?></textarea>
                        </div>

                        <button
                            class="btn btn-primary ppdb-submit-button"
                            type="submit"
                        >
                            <span>
                                Kirim Pendaftaran
                            </span>

                            <span>
                                →
                            </span>
                        </button>

                    </form>

                </div>

            </div>

        <?php endif; ?>

    </div>
</section>

<?php

require_once __DIR__ . '/includes/public-footer.php';

?>
