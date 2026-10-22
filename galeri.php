<?php

declare(strict_types=1);

$pageTitle = 'Galeri Instagram';
require __DIR__ . '/includes/public-header.php';

// Visual Builder integration: keep the original PHP page as the fallback.
vb_begin('galeri');

if (!table_exists('gallery')) {
    $pdo->exec("CREATE TABLE gallery (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(180) NOT NULL,
        instagram_url VARCHAR(500) NOT NULL,
        description TEXT NULL,
        status ENUM('published','draft') DEFAULT 'published',
        sort_order INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

try {
    $columns = $pdo->query('SHOW COLUMNS FROM gallery')->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('instagram_url', $columns, true)) {
        $pdo->exec("ALTER TABLE gallery ADD COLUMN instagram_url VARCHAR(500) NULL AFTER title");
    }
    if (!in_array('description', $columns, true)) {
        $pdo->exec("ALTER TABLE gallery ADD COLUMN description TEXT NULL AFTER instagram_url");
    }
    if (!in_array('status', $columns, true)) {
        $pdo->exec("ALTER TABLE gallery ADD COLUMN status ENUM('published','draft') DEFAULT 'published' AFTER description");
    }
    if (!in_array('sort_order', $columns, true)) {
        $pdo->exec("ALTER TABLE gallery ADD COLUMN sort_order INT NOT NULL DEFAULT 0 AFTER status");
    }
} catch (Throwable) {
    // The public page remains available even if an old installation needs migration.
}

$rows = $pdo->query(
    "SELECT * FROM gallery
     WHERE (status = 'published' OR status IS NULL)
       AND instagram_url IS NOT NULL
       AND instagram_url <> ''
     ORDER BY sort_order ASC, id DESC"
)->fetchAll();

$instagramUrl = setting('instagram_url', '');
$galleryHeroLogo = setting('gallery_hero_logo', '');
?>

<main class="instagram-gallery-page">
    <section class="ig-gallery-hero">
        <div class="ig-gallery-grid"></div>
        <div class="ig-gallery-orbit ig-orbit-a"></div>
        <div class="ig-gallery-orbit ig-orbit-b"></div>
        <div class="ig-gallery-orbit ig-orbit-c"></div>
        <span class="ig-gallery-hero-number">IG</span>
        <span class="ig-gallery-code ig-code-one">VISUAL JOURNAL</span>
        <span class="ig-gallery-code ig-code-two">SCHOOL / LIFE</span>
        <span class="ig-gallery-code ig-code-three">@INFOKOM</span>

        <div class="container ig-gallery-hero-inner reveal">
            <div class="ig-gallery-hero-copy">
                <p class="eyebrow">01 / INSTAGRAM GALLERY</p>
                <h1><?= e(cms_text('galeri', 'hero_title', 'FOLLOW OUR JOURNEY.')) ?></h1>
                <p>
                    Kegiatan, karya, dan momen terbaru SMK Infokom Kota Bogor
                    dikurasi langsung dari Instagram sekolah.
                </p>
                <?php if ($instagramUrl): ?>
                    <a class="ig-gallery-hero-link" href="<?= e($instagramUrl) ?>" target="_blank" rel="noopener noreferrer">
                        <span>OPEN INSTAGRAM</span>
                        <b>↗</b>
                    </a>
                <?php endif; ?>
            </div>

            <div class="ig-gallery-hero-stage" aria-hidden="true">
                <div class="ig-stage-card ig-stage-card-a"><span>01</span><b>POST</b></div>
                <div class="ig-stage-card ig-stage-card-b"><span>02</span><b>REEL</b></div>
                <div class="ig-stage-card ig-stage-card-c"><span>03</span><b>STORY</b></div>
                <div class="ig-stage-core">
                    <?php if ($galleryHeroLogo !== ''): ?>
                        <img class="ig-gallery-hero-logo" src="<?= e(img_url($galleryHeroLogo)) ?>" alt="Logo hero Galeri Instagram">
                    <?php else: ?>
                        <span class="ig-gallery-hero-fallback" aria-hidden="true">◎</span>
                    <?php endif; ?>
                    <small>INSTAGRAM</small>
                </div>
            </div>
        </div>
    </section>

    <section class="ig-gallery-feed section" id="instagram-feed">
        <div class="container">
            <div class="ig-gallery-section-head reveal">
                <div>
                    <p class="eyebrow">02 / VISUAL ARCHIVE</p>
                    <h2>MOMENTS<br><span>IN FRAME.</span></h2>
                </div>
                <p>
                    Semua frame diambil dari postingan Instagram yang dikelola melalui
                    Admin. Tidak ada upload foto ulang ke server galeri.
                </p>
            </div>

            <?php if ($rows): ?>
                <div class="ig-gallery-grid-list">
                    <?php foreach ($rows as $index => $row): ?>
                        <?php
                        $number = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
                        $permalink = instagram_embed_url((string) ($row['instagram_url'] ?? ''));
                        if ($permalink === '') {
                            continue;
                        }
                        $caption = trim((string) ($row['description'] ?? ''));
                        ?>
                        <article class="ig-gallery-card reveal ig-card-offset-<?= e((string) ($index % 3)) ?>">
                            <div class="ig-gallery-card-frame">
                                <div class="ig-gallery-card-topline">
                                    <span><?= e($number) ?> / INSTAGRAM</span>
                                    <span class="ig-gallery-status"><i></i> LIVE SOURCE</span>
                                </div>

                                <div class="ig-post-stage">
                                    <blockquote
                                        class="instagram-media"
                                        data-instgrm-permalink="<?= e($permalink) ?>"
                                        data-instgrm-version="14"
                                    >
                                        <a href="<?= e($permalink) ?>" target="_blank" rel="noopener noreferrer">View this post on Instagram</a>
                                    </blockquote>
                                </div>

                                <div class="ig-gallery-card-meta">
                                    <div>
                                        <small><?= e($number) ?> / VISUAL MOMENT</small>
                                        <h3><?= e((string) ($row['title'] ?? 'Instagram Post')) ?></h3>
                                        <?php if ($caption !== ''): ?>
                                            <p><?= e(mb_strimwidth($caption, 0, 130, '…')) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?= e($permalink) ?>" target="_blank" rel="noopener noreferrer" aria-label="Buka posting Instagram">
                                        ↗
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="ig-gallery-empty reveal">
                    <span>◎</span>
                    <small>NO POSTS YET</small>
                    <h3>Instagram gallery siap diisi.</h3>
                    <p>Tambahkan URL posting Instagram melalui Admin → Galeri.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="ig-profile-strip section">
        <div class="container">
            <div class="ig-profile-card reveal">
                <div class="ig-profile-mark">◎</div>
                <div class="ig-profile-copy">
                    <small>03 / SCHOOL INSTAGRAM</small>
                    <strong><?= e($instagramUrl !== '' ? $instagramUrl : '@SMKINFOKOMKOTABOGOR') ?></strong>
                    <span>See more of our everyday moments.</span>
                </div>
                <?php if ($instagramUrl): ?>
                    <a href="<?= e($instagramUrl) ?>" target="_blank" rel="noopener noreferrer">FOLLOW ↗</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="ig-marquee-section">
        <div class="ig-marquee" aria-hidden="true">
            <span>VISUAL MOMENTS</span><i>✦</i><span>SCHOOL LIFE</span><i>✦</i><span>SMK INFOKOM</span><i>✦</i><span>VISUAL MOMENTS</span><i>✦</i>
        </div>
    </section>

    <section class="ig-gallery-cta">
        <div class="ig-cta-lines"></div>
        <div class="container ig-gallery-cta-inner reveal">
            <p class="eyebrow">04 / STAY CONNECTED</p>
            <h2>SEE MORE OF OUR<br><span>EVERYDAY MOMENTS.</span></h2>
            <?php if ($instagramUrl): ?>
                <a class="btn btn-yellow" href="<?= e($instagramUrl) ?>" target="_blank" rel="noopener noreferrer">Follow Instagram <span>↗</span></a>
            <?php endif; ?>
        </div>
        <div class="ig-cta-word">INSTAGRAM</div>
    </section>
</main>

<?php vb_end(); ?>

<?php require __DIR__ . '/includes/public-footer.php'; ?>
