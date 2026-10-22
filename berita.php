<?php

declare(strict_types=1);

$pageTitle = 'Informasi';
require_once __DIR__ . '/includes/public-header.php';

// Visual Builder integration: keep the original PHP page as the fallback.
vb_begin('berita');

$videos = [];
try {
    $videos = $pdo->query(
        "SELECT * FROM youtube_videos
         WHERE status = 'published'
         ORDER BY sort_order ASC, created_at DESC, id DESC"
    )->fetchAll();
} catch (Throwable $exception) {
    $videos = [];
}

$categories = [];
foreach ($videos as $video) {
    $category = trim((string) ($video['category'] ?? ''));
    if ($category !== '' && !in_array($category, $categories, true)) {
        $categories[] = $category;
    }
}
?>

<section class="info-hero">
    <div class="info-hero-grid"></div>
    <div class="info-hero-orbit info-hero-orbit-one"></div>
    <div class="info-hero-orbit info-hero-orbit-two"></div>
    <div class="container info-hero-inner">
        <div class="info-hero-copy reveal">
            <div class="info-kicker"><span>05</span><i></i><b>INFORMASI</b></div>
            <h1><?= nl2br(e(cms_text('informasi', 'hero_title', setting('information_hero_title', 'Semua cerita sekolah, dalam satu layar.')))) ?></h1>
            <p><?= nl2br(e(cms_text('informasi', 'hero_description', setting('information_hero_subtitle', 'Temukan dokumentasi, kegiatan, prestasi, dan cerita SMK INFOKOM Kota Bogor melalui video YouTube resmi sekolah.')))) ?></p>
            <div class="info-hero-actions">
                <a class="btn btn-primary" href="#video-list">Jelajahi Video <span>↓</span></a>
                <?php $schoolYoutube = trim((string) setting('youtube_url', '')); ?>
                <?php if ($schoolYoutube !== ''): ?>
                    <a class="btn btn-outline" href="<?= e($schoolYoutube) ?>" target="_blank" rel="noopener noreferrer">Channel YouTube <span>↗</span></a>
                <?php endif; ?>
            </div>
        </div>
        <div class="info-hero-visual reveal">
            <div class="info-hero-frame info-hero-logo-frame">
                <div class="info-hero-logo-glow"></div>
                <img
                    class="info-hero-logo"
                    src="<?= e(img_url(setting('information_hero_logo', setting('logo_path', 'assets/images/logo-infokom.svg')))) ?>"
                    alt="Logo SMK INFOKOM Kota Bogor"
                >
                <div class="info-hero-lines"></div>
                <div class="info-hero-label">OFFICIAL<br><strong>SMK INFOKOM</strong></div>
            </div>
            <span class="info-floating-note">WATCH<br>DISCOVER<br>REMEMBER</span>
        </div>
    </div>
    <div class="info-scroll-note"><span></span> SCROLL TO EXPLORE</div>
</section>

<section class="info-video-section" id="video-list">
    <div class="container">
        <div class="info-section-head reveal">
            <div>
                <div class="info-section-number">01 / VIDEO LIBRARY</div>
                <p class="eyebrow">CERITA SMK INFOKOM</p>
                <h2><?= e(cms_text('informasi', 'section_1_title', 'Putar. Lihat. Rasakan cerita sekolah.')) ?></h2>
            </div>
            <p>Video diambil langsung dari YouTube. Tidak perlu upload foto atau thumbnail — gambar video otomatis mengikuti thumbnail YouTube.</p>
        </div>

        <div class="info-toolbar reveal">
            <div class="info-search-wrap">
                <span aria-hidden="true">⌕</span>
                <input id="infoVideoSearch" type="search" placeholder="Cari video..." autocomplete="off">
            </div>
            <div class="info-filters" role="tablist" aria-label="Filter video">
                <button type="button" class="info-filter active" data-info-filter="all">Semua</button>
                <?php foreach ($categories as $category): ?>
                    <button type="button" class="info-filter" data-info-filter="<?= e($category) ?>"><?= e($category) ?></button>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($videos): ?>
            <div class="info-video-grid" id="infoVideoGrid">
                <?php foreach ($videos as $index => $video): ?>
                    <?php $videoId = youtube_video_id((string) ($video['youtube_url'] ?? '')); ?>
                    <?php if ($videoId === '') continue; ?>
                    <?php $category = trim((string) ($video['category'] ?? '')) ?: 'Cerita Sekolah'; ?>
                    <article
                        class="info-video-card reveal"
                        data-info-card
                        data-category="<?= e($category) ?>"
                        data-title="<?= e(strtolower((string) $video['title'])) ?>"
                        style="--info-delay: <?= (int) (($index % 6) * 80) ?>ms"
                    >
                        <button
                            type="button"
                            class="info-video-trigger"
                            data-video-id="<?= e($videoId) ?>"
                            data-video-title="<?= e((string) $video['title']) ?>"
                            data-video-description="<?= e((string) ($video['description'] ?? '')) ?>"
                            aria-label="Putar <?= e((string) $video['title']) ?>"
                        >
                            <span class="info-video-thumb">
                                <img
                                    src="https://img.youtube.com/vi/<?= e($videoId) ?>/hqdefault.jpg"
                                    alt="Thumbnail <?= e((string) $video['title']) ?>"
                                    loading="lazy"
                                >
                                <span class="info-video-overlay"></span>
                                <span class="info-video-corner"><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                                <span class="info-play-ring"><span>▶</span></span>
                                <span class="info-video-accent"></span>
                            </span>
                            <span class="info-video-body">
                                <span class="info-video-meta">
                                    <b><?= e($category) ?></b>
                                    <time><?= e(date('d M Y', strtotime((string) $video['created_at']))) ?></time>
                                </span>
                                <strong><?= e((string) $video['title']) ?></strong>
                                <?php if (!empty($video['description'])): ?>
                                    <em><?= e(mb_substr((string) $video['description'], 0, 120)) ?><?= mb_strlen((string) $video['description']) > 120 ? '…' : '' ?></em>
                                <?php endif; ?>
                                <span class="info-watch">Tonton video <span>↗</span></span>
                            </span>
                        </button>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="info-empty" id="infoVideoEmpty" hidden>
                <span>NO RESULT</span>
                <strong>Video yang kamu cari belum ditemukan.</strong>
                <button type="button" id="infoResetSearch">Reset pencarian</button>
            </div>
        <?php else: ?>
            <div class="info-no-video reveal">
                <span class="info-no-video-icon">▶</span>
                <span class="eyebrow">YOUTUBE LIBRARY</span>
                <h3>Belum ada video yang dipublikasikan.</h3>
                <p>Tambahkan video YouTube melalui Admin → YouTube. Thumbnail akan muncul otomatis tanpa upload foto.</p>
                <?php if (is_logged_in()): ?>
                    <a class="btn btn-primary" href="<?= e(url('admin/manage.php?entity=youtube_videos')) ?>">Kelola Video <span>↗</span></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="info-bottom-section">
    <div class="info-bottom-grid"></div>
    <div class="container info-bottom-inner reveal">
        <div>
            <div class="info-section-number">02 / OFFICIAL CHANNEL</div>
            <p class="eyebrow">CERITA YANG TERUS BERJALAN</p>
            <h2>Satu sekolah.<br>Banyak cerita.</h2>
        </div>
        <div>
            <p>Ikuti dokumentasi kegiatan dan perkembangan SMK INFOKOM Kota Bogor melalui kanal YouTube resmi sekolah.</p>
            <?php if ($schoolYoutube !== ''): ?>
                <a class="info-channel-link" href="<?= e($schoolYoutube) ?>" target="_blank" rel="noopener noreferrer">Buka Channel YouTube <span>↗</span></a>
            <?php endif; ?>
        </div>
    </div>
</section>

<div class="video-modal info-video-modal" id="homeVideoModal" aria-hidden="true">
    <div class="video-modal-backdrop" data-video-close></div>
    <div class="video-modal-panel" role="dialog" aria-modal="true" aria-labelledby="homeVideoTitle">
        <button type="button" class="video-modal-close" data-video-close aria-label="Tutup video">×</button>
        <div class="video-modal-frame" id="homeVideoFrame"></div>
        <div class="video-modal-caption">
            <span>YOUTUBE · SMK INFOKOM</span>
            <h3 id="homeVideoTitle">Video sekolah</h3>
            <p id="homeVideoDescription"></p>
        </div>
    </div>
</div>

<?php vb_end(); ?>

<?php require __DIR__ . '/includes/public-footer.php'; ?>
