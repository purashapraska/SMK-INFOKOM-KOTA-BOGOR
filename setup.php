<?php

declare(strict_types=1);

require_once __DIR__ . '/config/database.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $sqlPath = __DIR__ . '/database/database.sql';

        if (!is_file($sqlPath)) {
            throw new RuntimeException(
                'File database.sql tidak ditemukan.'
            );
        }

        $sql = file_get_contents($sqlPath);

        if ($sql === false) {
            throw new RuntimeException(
                'Database SQL tidak dapat dibaca.'
            );
        }

        $statements = [];
        $buffer = '';
        $inString = false;
        $stringCharacter = '';
        $length = strlen($sql);

        for ($index = 0; $index < $length; $index++) {
            $character = $sql[$index];

            if ($inString) {
                $buffer .= $character;

                if (
                    $character === $stringCharacter
                    && ($index === 0 || $sql[$index - 1] !== '\\')
                ) {
                    $inString = false;
                }

                continue;
            }

            if ($character === "'" || $character === '"') {
                $inString = true;
                $stringCharacter = $character;
                $buffer .= $character;
                continue;
            }

            if ($character === ';') {
                if (trim($buffer) !== '') {
                    $statements[] = trim($buffer);
                }

                $buffer = '';
                continue;
            }

            $buffer .= $character;
        }

        if (trim($buffer) !== '') {
            $statements[] = trim($buffer);
        }

        foreach ($statements as $statement) {
            $pdo->exec($statement);
        }

        $message = 'Database dan seluruh tabel berhasil disiapkan.';
    } catch (Throwable $exception) {
        $error = $exception->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>
        Setup &mdash; SMK Infokom Kota Bogor
    </title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >
</head>
<body>

<main class="page-hero">
    <div class="container">

        <p class="eyebrow">
            INSTALLATION
        </p>

        <h1>
            Setup SMK Infokom Kota Bogor
        </h1>

        <p>
            Gunakan halaman ini satu kali untuk membuat database
            dan tabel awal project.
        </p>

        <?php if ($message): ?>

            <div class="notice success">
                <?= e($message) ?>
            </div>

            <p>
                <a
                    class="btn btn-primary"
                    href="index.php"
                >
                    Buka Website
                </a>
            </p>

        <?php endif; ?>

        <?php if ($error): ?>

            <div class="notice error">
                <?= e($error) ?>
            </div>

        <?php endif; ?>

        <?php if (!$message): ?>

            <form method="post">
                <button
                    class="btn btn-primary"
                    type="submit"
                >
                    Buat Database &amp; Tabel
                </button>
            </form>

        <?php endif; ?>

    </div>
</main>

</body>
</html>
