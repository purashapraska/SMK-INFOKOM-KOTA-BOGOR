<?php

declare(strict_types=1);

require_once __DIR__ . '/config/app.php';

if (is_logged_in()) {
    redirect('admin/dashboard.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verify_csrf($_POST['_csrf'] ?? null)) {
        $error = 'Sesi formulir tidak valid.';
    } else {
        $email = trim(
            (string) ($_POST['email'] ?? '')
        );

        $password = (string) (
            $_POST['password'] ?? ''
        );

        // Installation without any account: create the default administrator
        // so the panel can never be locked out (login: lihat README).
        try {
            if ((int) $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn() === 0) {
                $pdo->prepare(
                    'INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)'
                )->execute([
                    'Administrator',
                    'admin@infokom.sch.id',
                    password_hash('Admin123!', PASSWORD_DEFAULT),
                    'ADMIN',
                ]);
            }
        } catch (Throwable) {
            // Continue with normal login if the table cannot be written.
        }

        $statement = $pdo->prepare(
            'SELECT * FROM users '
            . 'WHERE LOWER(email) = LOWER(?) '
            . 'LIMIT 1'
        );

        $statement->execute([$email]);
        $user = $statement->fetch();

        if (
            $user
            && password_verify(
                $password,
                (string) $user['password']
            )
        ) {
            if (password_needs_rehash((string) $user['password'], PASSWORD_DEFAULT)) {
                try {
                    $pdo->prepare('UPDATE users SET password = ? WHERE id = ?')
                        ->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
                } catch (Throwable) {
                }
            }

            login_user($user);

            log_activity(
                'login',
                'Login berhasil'
            );

            redirect('admin/dashboard.php');
        }

        $error = 'Email atau password salah.';
    }
}

$background = setting(
    'login_image',
    'assets/images/placeholder.svg'
);

$logo = setting(
    'logo_path',
    'assets/images/logo-infokom.svg'
);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <link rel="icon" type="image/png" href="<?= e(img_url(setting('site_favicon', setting('logo_path', 'assets/images/logo-infokom.svg')))) ?>">

    <title>
        Login Admin &mdash;
        <?= e(setting('school_name', APP_NAME)) ?>
    </title>

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&amp;family=Manrope:wght@600;700;800&amp;display=swap"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="<?= e(url('assets/css/style.css')) ?>"
    >
</head>
<body>

<div class="page-mask" aria-hidden="true">
    <span class="page-mask-label">SMK INFOKOM BOGOR</span>
</div>

<div class="login-page">

    <div class="login-shell">

        <div
            class="login-visual"
            style="background-image: linear-gradient(90deg, rgba(7, 28, 48, .86), rgba(15, 76, 129, .34)), url('<?= e(url($background)) ?>');
                   background-position: center;
                   background-size: cover;"
        >
            <div class="login-identity">

                <img
                    class="login-logo"
                    src="<?= e(url($logo)) ?>"
                    alt="Logo"
                >

                <p class="eyebrow">
                    ADMINISTRATOR
                </p>

                <h1>
                    Kelola seluruh pengalaman digital sekolah.
                </h1>

            </div>
        </div>

        <div class="login-panel">

            <div class="login-form-wrap">

                <a
                    class="brand"
                    href="<?= e(url('')) ?>"
                >
                    <span class="brand-logo-wrap">
                        <span class="brand-logo-glow"></span>

                        <img
                            src="<?= e(url($logo)) ?>"
                            alt="Logo"
                        >
                    </span>

                    <span class="brand-copy">
                        <strong>SMK INFOKOM</strong>
                        <small>
                            <?= e(mb_strtoupper(setting('school_city', 'BOGOR'))) ?>
                        </small>
                    </span>
                </a>

                <p class="eyebrow">
                    CONTENT MANAGEMENT
                </p>

                <h2>
                    Masuk
                </h2>

                <p>
                    Gunakan akun administrator untuk mengelola website.
                </p>

                <?php if ($error): ?>
                    <div class="notice error">
                        <?= e($error) ?>
                    </div>
                <?php endif; ?>

                <form
                    class="login-form"
                    method="post"
                >
                    <input
                        type="hidden"
                        name="_csrf"
                        value="<?= e(csrf_token()) ?>"
                    >

                    <label>
                        Email

                        <input
                            class="form-control"
                            name="email"
                            type="email"
                            autocomplete="username"
                            required
                        >
                    </label>

                    <label>
                        Password

                        <input
                            class="form-control"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                        >
                    </label>

                    <button
                        class="btn btn-primary"
                        type="submit"
                    >
                        Masuk ke Dashboard
                        <span>→</span>
                    </button>
                </form>

            </div>

        </div>

    </div>

</div>

<script>
    window.addEventListener('pageshow', () => {
        document.body.classList.add('page-ready');
    });
</script>

</body>
</html>
