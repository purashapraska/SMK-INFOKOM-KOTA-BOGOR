<?php
$pageTitle = 'Beranda';
require_once __DIR__ . '/includes/public-header.php';

$hero = setting('hero_image', 'assets/images/placeholder.svg');
$welcome = setting('welcome_image', '');
$majors = $pdo->query('SELECT * FROM majors ORDER BY id LIMIT 4')->fetchAll();
$news = $pdo->query('SELECT * FROM news ORDER BY created_at DESC LIMIT 3')->fetchAll();
$gallery = $pdo->query('SELECT * FROM gallery ORDER BY id DESC LIMIT 6')->fetchAll();
$youtubeVideos = $pdo->query("SELECT * FROM youtube_videos WHERE status = 'published' ORDER BY sort_order ASC, id DESC LIMIT 3")->fetchAll();
$instagramPosts = $pdo->query("SELECT * FROM instagram_posts WHERE status = 'published' ORDER BY sort_order ASC, id DESC LIMIT 3")->fetchAll();
$instagramSettingUrl = trim((string) setting('instagram_url', ''));

// URL Instagram dari Pengaturan Website juga ditampilkan sebagai post tambahan.
// Jika URL tersebut sudah ada di data CMS, jangan diduplikasi.
if ($instagramSettingUrl !== '') {
    $normalizedSetting = rtrim($instagramSettingUrl, '/') . '/';
    $alreadyExists = false;

    foreach ($instagramPosts as $post) {
        if (rtrim((string) ($post['instagram_url'] ?? ''), '/') . '/' === $normalizedSetting) {
            $alreadyExists = true;
            break;
        }
    }

    if (!$alreadyExists) {
        array_unshift($instagramPosts, [
            'instagram_url' => $instagramSettingUrl,
            'title' => 'Kegiatan Sekolah',
            'description' => 'Dokumentasi kegiatan terbaru SMK Infokom Kota Bogor.',
        ]);
    }

    $instagramPosts = array_slice($instagramPosts, 0, 3);
}

$locationAddress = setting(
    'school_address',
    setting('location_address', 'Jl. Letjen Ibrahim Adjie No.178, RT.03/RW.08, Sindangbarang, Kecamatan Bogor Barat, Kota Bogor, Jawa Barat 16117.')
);
$locationLatitude = setting('location_latitude', '-6.597300000000');
$locationLongitude = setting('location_longitude', '106.779300000000');
$mapsEmbed = google_maps_embed_url('', '', $locationAddress);
$mapsQuery = $locationAddress;

$schoolHoursWeekday = setting('school_hours_weekday', 'Senin – Jumat · 07.00 – 17.00');
$schoolHoursSaturday = setting('school_hours_saturday', 'Sabtu · 07.00 – 12.30');
$schoolHoursSunday = setting('school_hours_sunday', 'Minggu · Tutup');

vb_begin('home');
?>

<section class="home-hero hero">
    <div class="hero-media" style="--hero-url:url('<?= e(url($hero)) ?>')"></div>
    <div class="hero-overlay"></div>

    <div class="container hero-content reveal">
        <p class="eyebrow hero-eyebrow">SMK INFOKOM KOTA BOGOR</p>
        <h1><?= e(cms_text('home', 'hero_title', setting('hero_title', 'Membentuk Generasi Digital yang Kompeten.'))) ?></h1>
        <p class="hero-copy">
            <?= e(cms_text('home', 'hero_description', setting('hero_text', 'Ruang belajar untuk membangun kompetensi teknologi, karakter, dan kesiapan menghadapi dunia industri.'))) ?>
        </p>
        <div class="actions">
            <a class="btn btn-primary" href="<?= e(cms_text('home', 'button_url', url('profil.php'))) ?>"><?= e(cms_text('home', 'button_label', 'Jelajahi Sekolah')) ?> <span>→</span></a>
            <a class="btn btn-outline" href="<?= e(url('ppdb.php')) ?>">Informasi PPDB <span>↗</span></a>
        </div>
    </div>

    <div class="hero-meta">
        <span>01</span>
        <span>SCROLL UNTUK MENJELAJAHI</span>
    </div>
</section>

<section class="running-block home-block home-block-yellow">
    <div class="running-label">INFORMASI TERKINI</div>
    <?php vb_dyn_open('running', 'div', 'class="running-track" aria-label="Informasi terkini"'); ?>
        <div class="running-content">
            <span><?= e(setting('running_text', 'Penerimaan Peserta Didik Baru • Kegiatan Sekolah • Prestasi Siswa • PKL & Magang • BKK • Informasi terbaru SMK Infokom Kota Bogor')) ?></span>
            <span aria-hidden="true">✦</span>
            <span><?= e(setting('running_text', 'Penerimaan Peserta Didik Baru • Kegiatan Sekolah • Prestasi Siswa • PKL & Magang • BKK • Informasi terbaru SMK Infokom Kota Bogor')) ?></span>
            <span aria-hidden="true">✦</span>
        </div>
    <?php vb_dyn_close(); ?>
</section>

<section class="home-block home-block-white welcome-section">
    <div class="container">
        <div class="home-section-label reveal"><span>01</span><b>SAMBUTAN</b></div>
        <div class="welcome home-welcome">
            <div class="welcome-visual reveal">
                <div class="welcome-image-frame">
                    <div class="welcome-image-backdrop" style="--image:url('<?= e(url($welcome ?: 'assets/images/placeholder.svg')) ?>')"></div>
                    <img src="<?= e(url($welcome ?: 'assets/images/placeholder.svg')) ?>" alt="<?= e(setting('welcome_name', 'Kepala Sekolah')) ?>" loading="lazy">
                </div>
                <div class="welcome-stamp">KEPALA<br>SEKOLAH</div>
            </div>
            <div class="welcome-copy reveal">
                <p class="eyebrow">SAMBUTAN KEPALA SEKOLAH</p>
                <h2><?= e(cms_text('home', 'section_1_title', setting('welcome_title', 'Selamat datang di SMK Infokom Kota Bogor.'))) ?></h2>
                <p><?= nl2br(e(setting('welcome_text', 'SMK Infokom Kota Bogor hadir untuk membantu peserta didik mengembangkan keterampilan yang relevan, disiplin belajar, serta pengalaman yang mendekatkan mereka dengan kebutuhan dunia kerja.'))) ?></p>
                <div class="signature">
                    <strong><?= e(setting('welcome_name', 'Kepala Sekolah')) ?></strong>
                    <span><?= e(setting('welcome_position', 'Kepala Sekolah')) ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="home-block home-block-blue strengths-section">
    <div class="container">
        <div class="home-section-label reveal light"><span>02</span><b>KENAPA INFOKOM?</b></div>
        <div class="section-head reveal home-head light-head">
            <div>
                <p class="eyebrow">KEUNGGULAN SEKOLAH</p>
                <h2><?= e(cms_text('home', 'section_2_title', 'Belajar teknologi. Tumbuh sebagai pribadi.')) ?></h2>
            </div>
            <p>Lingkungan belajar yang menggabungkan kompetensi, karakter, pengalaman praktik, dan kesiapan menghadapi dunia industri.</p>
        </div>

        <div class="strength-grid">
            <?php
            $strengths = [
                ['01', 'Pembelajaran Berbasis Teknologi', 'Pembelajaran mengikuti perkembangan teknologi dan kebutuhan industri.', '↗'],
                ['02', 'Guru Profesional', 'Didukung tenaga pendidik yang kompeten dan berpengalaman.', '↗'],
                ['03', 'Kesiapan Dunia Kerja', 'Membekali siswa dengan keterampilan yang relevan dengan dunia industri.', '↗'],
                ['04', 'Pengembangan Karakter', 'Membentuk siswa yang disiplin, kreatif, bertanggung jawab, dan berkarakter.', '↗'],
            ];
            foreach ($strengths as $strength):
            ?>
                <article class="strength-card reveal">
                    <span class="card-index"><?= e($strength[0]) ?></span>
                    <div class="strength-icon"><?= e($strength[3]) ?></div>
                    <h3><?= e($strength[1]) ?></h3>
                    <p><?= e($strength[2]) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="home-block home-block-white majors-section">
    <div class="container">
        <div class="home-section-label reveal"><span>03</span><b>PROGRAM KEAHLIAN</b></div>
        <div class="section-head reveal home-head">
            <div>
                <p class="eyebrow">PROGRAM KEAHLIAN</p>
                <h2>Temukan bidang yang ingin kamu kuasai.</h2>
            </div>
            <a class="text-link" href="<?= e(url('jurusan.php')) ?>">Lihat semua jurusan <span>→</span></a>
        </div>

        <?php vb_dyn_open('majors', 'div', 'class="major-portrait-grid"'); ?>
            <?php foreach ($majors as $i => $major): ?>
                <a class="major-portrait reveal" href="<?= e(url('jurusan.php?id=' . $major['id'])) ?>">
                    <div class="major-portrait-media">
                        <img src="<?= e(img_url($major['image'])) ?>" alt="<?= e($major['name']) ?>" loading="lazy">
                        <div class="major-portrait-overlay"></div>
                        <span class="major-number">0<?= $i + 1 ?></span>
                        <span class="major-arrow">↗</span>
                    </div>
                    <div class="major-portrait-body">
                        <h3><?= e($major['name']) ?></h3>
                        <p><?= e(mb_substr((string) ($major['description'] ?? ''), 0, 105)) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php vb_dyn_close(); ?>
    </div>
</section>

<section class="home-block home-block-navy stats-section">
    <div class="container">
        <div class="home-section-label reveal light"><span>04</span><b>DALAM ANGKA</b></div>
        <?php vb_dyn_open('stats', 'div', 'class="stats-home-grid"'); ?>
            <div class="stat-home reveal"><strong data-counter="<?= e(setting('school_stat_students', '300')) ?>" data-suffix="+">0</strong><span>Siswa Aktif</span></div>
            <div class="stat-home reveal"><strong data-counter="<?= e(setting('school_stat_teachers', (string) count_rows('teachers'))) ?>" data-suffix="+">0</strong><span>Guru & Tenaga Pendidik</span></div>
            <div class="stat-home reveal"><strong data-counter="<?= e(setting('school_stat_alumni', '0')) ?>" data-suffix="+">0</strong><span>Alumni</span></div>
            <div class="stat-home reveal"><strong data-counter="<?= e(setting('school_stat_partners', (string) count_rows('bkk_partners'))) ?>" data-suffix="+">0</strong><span>Mitra Industri</span></div>
        <?php vb_dyn_close(); ?>
    </div>
</section>

<section class="home-block home-block-soft media-story-section">
    <div class="container">
        <div class="home-section-label reveal"><span>05</span><b>CERITA SEKOLAH</b></div>
        <div class="section-head reveal home-head">
            <div>
                <p class="eyebrow">CERITA SMK INFOKOM</p>
                <h2>Kegiatan dan cerita sekolah dalam video.</h2>
            </div>
            <a class="text-link" href="<?= e(url('berita.php')) ?>">Lihat Cerita & Berita <span>→</span></a>
        </div>

        <?php vb_dyn_open('videos', 'div'); ?>
        <?php if ($youtubeVideos): ?>
            <div class="home-video-grid">
                <?php foreach ($youtubeVideos as $i => $video): ?>
                    <?php $videoId = youtube_video_id((string) $video['youtube_url']); ?>
                    <?php if ($videoId): ?>
                        <button
                            type="button"
                            class="home-video-card reveal"
                            data-video-id="<?= e($videoId) ?>"
                            data-video-title="<?= e($video['title']) ?>"
                            aria-label="Putar <?= e($video['title']) ?>"
                        >
                            <span class="home-video-thumb">
                                <img src="https://img.youtube.com/vi/<?= e($videoId) ?>/hqdefault.jpg" alt="<?= e($video['title']) ?>" loading="lazy">
                                <span class="home-video-shade"></span>
                                <span class="home-play">▶</span>
                                <span class="home-video-index">0<?= $i + 1 ?></span>
                            </span>
                            <span class="home-video-body">
                                <small>YouTube · Cerita Sekolah</small>
                                <strong><?= e($video['title']) ?></strong>
                                <?php if (!empty($video['description'])): ?><em><?= e(mb_substr($video['description'], 0, 90)) ?></em><?php endif; ?>
                            </span>
                        </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="media-empty reveal">Video cerita sekolah akan tampil setelah ditambahkan melalui Admin.</div>
        <?php endif; ?>
        <?php vb_dyn_close(); ?>
    </div>
</section>



<section class="home-block home-block-blue instagram-home-section">
    <div class="container">
        <div class="home-section-label reveal light"><span>06</span><b>KEGIATAN SEKOLAH</b></div>
        <div class="section-head reveal home-head light-head instagram-head">
            <div>
                <p class="eyebrow">INSTAGRAM</p>
                <h2>Momen kegiatan sekolah kami.</h2>
                <p>Lihat dokumentasi kegiatan terbaru melalui Instagram resmi sekolah.</p>
            </div>
            <?php if (setting('instagram_url', '')): ?>
                <a class="btn btn-yellow" href="<?= e(setting('instagram_url')) ?>" target="_blank" rel="noopener noreferrer">Lihat Instagram ↗</a>
            <?php endif; ?>
        </div>

        <?php vb_dyn_open('instagram', 'div', 'class="instagram-home-wrap reveal"'); ?>
            <?php if ($instagramPosts): ?>
                <div class="instagram-home-grid">
                    <?php foreach ($instagramPosts as $i => $instagramPost): ?>
                        <?php $instagramUrl = instagram_embed_url((string) ($instagramPost['instagram_url'] ?? '')); ?>
                        <?php if ($instagramUrl): ?>
                            <article class="instagram-home-card" style="--ig-delay: <?= (int) $i * 100 ?>ms">
                                <div class="instagram-card-label">
                                    <span>0<?= (int) $i + 1 ?></span>
                                    <b>INSTAGRAM</b>
                                </div>
                                <blockquote class="instagram-media" data-instgrm-permalink="<?= e($instagramUrl) ?>" data-instgrm-version="14"></blockquote>
                                <?php if (!empty($instagramPost['title']) || !empty($instagramPost['description'])): ?>
                                    <div class="instagram-home-caption">
                                        <?php if (!empty($instagramPost['title'])): ?><strong><?= e($instagramPost['title']) ?></strong><?php endif; ?>
                                        <?php if (!empty($instagramPost['description'])): ?><span><?= e($instagramPost['description']) ?></span><?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </article>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="instagram-placeholder">
                    <span>Instagram</span>
                    <strong>Tambahkan posting Instagram melalui Admin.</strong>
                </div>
            <?php endif; ?>
        <?php vb_dyn_close(); ?>
    </div>
</section>

<section class="home-block home-block-soft gallery-home-section">
    <div class="container">
        <div class="home-section-label reveal"><span>07</span><b>GALERI KEGIATAN</b></div>
        <div class="section-head reveal home-head">
            <div>
                <p class="eyebrow">DOKUMENTASI</p>
                <h2>Momen yang tersimpan menjadi cerita.</h2>
            </div>
            <a class="text-link" href="<?= e(url('galeri.php')) ?>">Lihat Galeri <span>→</span></a>
        </div>
        <?php vb_dyn_open('gallery', 'div', 'class="home-instagram-strip"'); ?>
            <?php foreach (array_slice($gallery, 0, 3) as $i => $post): ?>
                <?php $postUrl = instagram_embed_url((string)($post['instagram_url'] ?? '')); ?>
                <?php if ($postUrl !== ''): ?>
                    <a class="home-instagram-card reveal home-instagram-card-<?= e((string)($i % 3)) ?>" href="<?= e($postUrl) ?>" target="_blank" rel="noopener noreferrer">
                        <span class="home-instagram-card-top"><b><?= e(str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT)) ?></b><i>◎</i></span>
                        <span class="home-instagram-card-icon">◎</span>
                        <strong><?= e($post['title']) ?></strong>
                        <small>VIEW ON INSTAGRAM ↗</small>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php vb_dyn_close(); ?>
    </div>
</section>

<section class="home-block home-block-white location-home-section">
    <div class="container">
        <div class="home-section-label reveal"><span>08</span><b>LOKASI & INFORMASI</b></div>
        <div class="section-head reveal home-head">
            <div>
                <p class="eyebrow">TEMUKAN KAMI</p>
                <h2>Datang dan kenali SMK Infokom Kota Bogor.</h2>
            </div>
            <a class="btn btn-primary" href="https://www.google.com/maps/search/?api=1&amp;query=<?= e(rawurlencode($mapsQuery)) ?>" target="_blank" rel="noopener noreferrer">Buka Google Maps ↗</a>
        </div>

        <?php vb_dyn_open('location', 'div', 'class="location-home-grid"'); ?>
            <div class="location-info-card reveal">
                <div class="location-info-title"><span class="location-pin">⌖</span><div><small>SMK INFOKOM KOTA BOGOR</small><strong>Informasi Sekolah</strong></div></div>
                <div class="location-fact"><span>Alamat</span><strong><?= nl2br(e($locationAddress)) ?></strong></div>
                <div class="location-fact"><span>Telepon</span><strong><?= e(setting('school_phone', '0251-8348108')) ?></strong></div>
                <div class="location-fact"><span>Email</span><strong><?= e(setting('school_email', 'smk.infokom@gmail.com')) ?></strong></div>
                <div class="location-hours">
                    <div class="hours-status"><span class="status-dot"></span><b>Jam Operasional</b></div>
                    <p><?= e($schoolHoursWeekday) ?></p>
                    <p><?= e($schoolHoursSaturday) ?></p>
                    <p><?= e($schoolHoursSunday) ?></p>
                </div>
            </div>
            <div class="location-map location-home-map reveal">
                <?php if ($mapsEmbed): ?>
                    <iframe src="<?= e($mapsEmbed) ?>" title="Lokasi SMK Infokom Kota Bogor" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php endif; ?>
                <div class="map-caption"><span>●</span> Sindangbarang · Bogor Barat</div>
            </div>
        <?php vb_dyn_close(); ?>
    </div>
</section>

<section class="home-block home-cta-block">
    <div class="home-cta-bg" style="--cta-url:url('<?= e(url($hero)) ?>')"></div>
    <div class="home-cta-overlay"></div>
    <div class="container home-cta-inner reveal">
        <p class="eyebrow">PPDB SMK INFOKOM KOTA BOGOR</p>
        <h2>Siap menjadi bagian dari generasi teknologi berikutnya?</h2>
        <p>Temukan program keahlian, kegiatan, dan informasi pendaftaran untuk memulai perjalananmu bersama SMK Infokom.</p>
        <a class="btn btn-yellow" href="<?= e(url('ppdb.php')) ?>">Lihat Informasi PPDB <span>→</span></a>
    </div>
</section>

<?php vb_dyn_open('video-modal', 'div', 'class="video-modal" id="homeVideoModal" aria-hidden="true"'); ?>
    <div class="video-modal-backdrop" data-video-close></div>
    <div class="video-modal-panel" role="dialog" aria-modal="true" aria-labelledby="homeVideoTitle">
        <button class="video-modal-close" type="button" aria-label="Tutup video" data-video-close>×</button>
        <div class="video-modal-frame" id="homeVideoFrame"></div>
        <div class="video-modal-caption">
            <span>YouTube · Cerita SMK Infokom</span>
            <h3 id="homeVideoTitle">Cerita Sekolah</h3>
        </div>
    </div>
<?php vb_dyn_close(); ?>

<?php vb_end(); ?>
<?php require_once __DIR__ . '/includes/public-footer.php'; ?>
