<?php

declare(strict_types=1);

$pageTitle = 'Profil SMK INFOKOM';

require_once __DIR__ . '/includes/public-header.php';

$schoolName = setting('school_name', 'SMK INFOKOM KOTA BOGOR');
$schoolDescription = setting(
    'school_description',
    'SMK INFOKOM Kota Bogor merupakan satuan pendidikan kejuruan yang berfokus pada pengembangan kompetensi, karakter, kreativitas, dan kesiapan peserta didik menghadapi perkembangan teknologi dan dunia industri.'
);
$vision = setting(
    'vision',
    'Terwujudnya SMK Infokom Bogor sebagai SMK yang peduli dan berbudaya lingkungan hidup dengan tamatan yang kompeten dan mampu bersaing di tingkat Nasional, maupun Internasional.'
);
$mission = setting(
    'mission',
    '1. Membangun mental dan sikap peserta didik yang beriman dan bertaqwa kepada Tuhan Yang Maha Esa.\n2. Mengembangkan kurikulum dan menyelenggarakan pembelajaran berstandar Nasional.\n3. Melalui kemitraan, keterbukaan dan pelayanan prima, meningkatkan kualitas dan kuantitas tamatan yang siap kerja, menyiapkan wirausahawan yang handal dan mampu bersaing, serta menyiapkan tenaga terampil yang memiliki etos kerja dan kepribadian.'
);
$headmasterName = setting('welcome_name', 'Kepala Sekolah');
$headmasterPosition = setting('welcome_position', 'Kepala Sekolah');
$headmasterText = setting('welcome_text', 'Selamat datang di SMK INFOKOM Kota Bogor. Mari tumbuh bersama melalui pembelajaran, karya, pengalaman, dan semangat untuk terus berkembang.');
$heroImage = setting('hero_image', '') ?: setting('welcome_image', '');
$heroImageUrl = $heroImage !== '' ? img_url($heroImage) : img_url(null);
$headmasterImage = setting('welcome_image', '');

$majors = [];
$facilities = [];

try {
    $majors = $pdo->query(
        'SELECT id, name, image, description FROM majors ORDER BY id ASC LIMIT 8'
    )->fetchAll();
} catch (Throwable) {
    $majors = [];
}

try {
    $facilities = $pdo->query(
        'SELECT id, name, image, description FROM facilities ORDER BY id DESC LIMIT 6'
    )->fetchAll();
} catch (Throwable) {
    $facilities = [];
}

$identity = [
    ['label' => 'Nama', 'value' => $schoolName],
    ['label' => 'NPSN', 'value' => '20238531'],
    ['label' => 'Status', 'value' => 'SMK Swasta'],
    ['label' => 'Naungan', 'value' => 'Yayasan Telekomunikasi Nasional'],
    ['label' => 'Alamat', 'value' => setting('school_address', 'Jl. Letjen Ibrahim Adjie No. 178, Sindangbarang, Bogor Barat, Kota Bogor, Jawa Barat')],
    ['label' => 'Telepon', 'value' => setting('school_phone', '0251-8348108')],
    ['label' => 'Email', 'value' => setting('school_email', 'smk.infokom@gmail.com')],
];

$timeline = [
    ['year' => '2005', 'title' => 'SMK INFOKOM berdiri', 'text' => 'SMK Informatika dan Telekomunikasi (INFOKOM) Kota Bogor didirikan pada 10 Mei 2005 dan memperoleh izin operasional pada 18 Juli 2005.'],
    ['year' => '2010', 'title' => 'Yayasan Telekomunikasi Nasional', 'text' => 'Yayasan Telekomunikasi Nasional didirikan berdasarkan Akte Pendirian Nomor 137 pada 10 Juni 2010 dan menaungi SMK INFOKOM.'],
    ['year' => 'Sekarang', 'title' => 'Terus bertumbuh', 'text' => 'SMK INFOKOM terus mengembangkan pendidikan vokasi, kompetensi keahlian, karya siswa, dan pengalaman belajar yang relevan dengan teknologi serta komunikasi.'],
];

$values = [
    ['number' => '01', 'title' => 'Kompetensi', 'text' => 'Belajar dengan fokus pada keterampilan yang dapat diterapkan dalam dunia kerja dan industri.'],
    ['number' => '02', 'title' => 'Kreativitas', 'text' => 'Mendorong siswa menghasilkan karya, ide, dan solusi melalui teknologi dan komunikasi.'],
    ['number' => '03', 'title' => 'Karakter', 'text' => 'Membangun pribadi yang disiplin, bertanggung jawab, dan siap berkolaborasi.'],
    ['number' => '04', 'title' => 'Masa Depan', 'text' => 'Menyiapkan generasi muda agar adaptif terhadap perubahan teknologi dan kebutuhan industri.'],
];


vb_begin('profil');
?>

<section class="profile-hero" id="profileTop">
    <div class="profile-hero-media" data-profile-parallax>
        <img
            src="<?= e($heroImageUrl) ?>"
            alt="Foto SMK INFOKOM Kota Bogor"
        >
    </div>

    <div class="profile-hero-overlay"></div>
    <div class="profile-hero-grid"></div>

    <div class="container profile-hero-inner">
        <div class="profile-hero-copy reveal is-visible">
            <span class="profile-kicker">
                <i></i>
                PROFIL SEKOLAH
            </span>

            <p class="profile-breadcrumb">
                Beranda <span>/</span> Profil
            </p>

            <h1>
                <?= e(cms_text('profil', 'hero_title', 'Mengenal lebih dekat SMK INFOKOM.')) ?>
            </h1>

            <p class="profile-hero-description">
                <?= e(cms_text('profil', 'hero_description', $schoolDescription)) ?>
            </p>

            <div class="profile-hero-actions">
                <a class="button button-primary" href="#tentang">
                    Jelajahi Profil
                    <span>↓</span>
                </a>
                <a class="profile-text-link" href="<?= e(url('jurusan.php')) ?>">
                    Lihat Program Keahlian <span>↗</span>
                </a>
            </div>
        </div>

        <div class="profile-hero-side reveal is-visible">
            <div class="profile-hero-side-line"></div>
            <span>SMK INFOKOM</span>
            <strong>KOTA BOGOR</strong>
        </div>
    </div>

    <div class="profile-scroll-cue">
        <span>SCROLL TO EXPLORE</span>
        <i></i>
    </div>

    <div class="profile-hero-index">02 <span>/</span> 08</div>
</section>

<section class="profile-intro profile-section" id="tentang">
    <div class="container profile-intro-grid">
        <div class="profile-section-label reveal">
            <span>01</span>
            <p>TENTANG KAMI</p>
        </div>

        <div class="profile-intro-copy reveal">
            <p class="eyebrow">THE INFOKOM STORY</p>
            <h2>
                Bukan hanya tempat belajar.<br>
                <em>Ini tempat untuk bertumbuh.</em>
            </h2>
            <p class="lead">
                <?= e($schoolDescription) ?>
            </p>
            <p>
                Lingkungan pendidikan vokasi menjadi ruang bagi siswa untuk belajar melalui teori,
                praktik, karya, kegiatan sekolah, dan pengalaman yang mendekatkan mereka dengan
                kebutuhan dunia kerja.
            </p>
        </div>

        <div class="profile-intro-stat reveal">
            <span>INFOKOM</span>
            <strong>TECH &amp;<br>CREATIVE</strong>
            <small>Kompetensi · Karakter · Karya</small>
        </div>
    </div>
</section>

<section class="profile-story profile-section">
    <div class="container profile-story-grid">
        <div class="profile-story-visual reveal">
            <div class="profile-story-card">
                <span class="profile-story-number">A</span>
                <span class="profile-story-caption">SMK INFOKOM KOTA BOGOR</span>
                <div class="profile-story-orbit"></div>
            </div>
        </div>

        <div class="profile-story-copy reveal">
            <p class="eyebrow">PERJALANAN</p>
            <h2><?= e(cms_text('profil', 'section_1_title', 'Setiap fase membawa sekolah ini melangkah lebih jauh.')) ?></h2>
            <p>
                Perjalanan SMK INFOKOM tercatat dalam data pendidikan pemerintah dan terus berlanjut
                melalui pengembangan kompetensi, kegiatan, karya siswa, dan pembelajaran vokasi.
            </p>

            <div class="profile-timeline">
                <?php foreach ($timeline as $index => $item): ?>
                    <article class="profile-timeline-item reveal" style="--delay: <?= (int) ($index * 90) ?>ms">
                        <div class="profile-timeline-dot"></div>
                        <div>
                            <span><?= e($item['year']) ?></span>
                            <h3><?= e($item['title']) ?></h3>
                            <p><?= e($item['text']) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="profile-vision profile-section">
    <div class="profile-vision-noise"></div>
    <div class="container">
        <div class="profile-section-label profile-section-label-light reveal">
            <span>02</span>
            <p>ARAH SEKOLAH</p>
        </div>

        <div class="profile-vision-heading reveal">
            <p class="eyebrow">VISI</p>
            <h2><?= nl2br(e($vision)) ?></h2>
        </div>

        <div class="profile-mission reveal">
            <div>
                <p class="eyebrow">MISI</p>
                <h3>Bagaimana kami bergerak menuju visi tersebut.</h3>
            </div>
            <div class="profile-mission-text">
                <?= nl2br(e($mission)) ?>
            </div>
        </div>

        <div class="profile-objectives reveal">
            <div class="profile-objectives-heading">
                <p class="eyebrow">TUJUAN</p>
                <h3>Arah yang ingin diwujudkan melalui pendidikan.</h3>
            </div>
            <div class="profile-objectives-list">
                <?php
                $objectives = [
                    'Menyiapkan lulusan yang bertaqwa dan kompeten untuk memasuki lapangan kerja.',
                    'Membentuk lulusan yang peduli lingkungan, produktif, adaptif, dan kreatif.',
                    'Membekali siswa agar mampu berkarir, berkompetisi, dan bersikap profesional.',
                    'Menyiapkan tenaga kerja tingkat menengah untuk kebutuhan dunia kerja berskala global.',
                    'Mendorong siswa mampu bekerja mandiri dan mengisi kebutuhan DU/DI sesuai kompetensi.',
                    'Mengembangkan sikap profesional dan kompetensi pada bidang keahlian.',
                    'Membekali ilmu pengetahuan dan teknologi untuk pengembangan diri serta pendidikan lanjut.'
                ];
                ?>
                <?php foreach ($objectives as $index => $objective): ?>
                    <div class="profile-objective">
                        <span><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                        <p><?= e($objective) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="profile-headmaster profile-section">
    <div class="container profile-headmaster-grid">
        <div class="profile-headmaster-copy reveal">
            <p class="eyebrow">SAMBUTAN KEPALA SEKOLAH</p>
            <h2>Belajar hari ini.<br><em>Mempersiapkan esok.</em></h2>
            <blockquote>
                “<?= e($headmasterText) ?>”
            </blockquote>
            <div class="profile-signature">
                <strong><?= e($headmasterName) ?></strong>
                <span><?= e($headmasterPosition) ?></span>
            </div>
        </div>

        <div class="profile-headmaster-photo reveal">
            <div class="profile-photo-frame">
                <span class="profile-photo-corner corner-a"></span>
                <span class="profile-photo-corner corner-b"></span>
                <?php if ($headmasterImage !== ''): ?>
                    <img src="<?= e(img_url($headmasterImage)) ?>" alt="<?= e($headmasterName) ?>">
                <?php else: ?>
                    <img src="<?= e($heroImageUrl) ?>" alt="SMK INFOKOM Kota Bogor">
                <?php endif; ?>
            </div>
            <span class="profile-photo-label">LEADERSHIP · INFOKOM</span>
        </div>
    </div>
</section>

<section class="profile-values profile-section">
    <div class="container">
        <div class="profile-section-heading reveal">
            <div>
                <p class="eyebrow">NILAI YANG DIBANGUN</p>
                <h2>Hal-hal yang ingin<br><em>kami tumbuhkan.</em></h2>
            </div>
            <p>
                Pendidikan vokasi bukan hanya soal kemampuan teknis. Ada sikap, kreativitas,
                kolaborasi, dan kesiapan untuk terus belajar.
            </p>
        </div>

        <div class="profile-values-grid">
            <?php foreach ($values as $value): ?>
                <article class="profile-value-card reveal">
                    <span><?= e($value['number']) ?></span>
                    <div class="profile-value-icon">+</div>
                    <h3><?= e($value['title']) ?></h3>
                    <p><?= e($value['text']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="profile-identity profile-section">
    <div class="container">
        <div class="profile-section-label reveal">
            <span>03</span>
            <p>IDENTITAS SEKOLAH</p>
        </div>

        <div class="profile-identity-heading reveal">
            <p class="eyebrow">SCHOOL IDENTITY</p>
            <h2>Informasi penting<br>tentang sekolah.</h2>
        </div>

        <div class="profile-identity-grid">
            <?php foreach ($identity as $index => $item): ?>
                <div class="profile-identity-item reveal" style="--delay: <?= (int) ($index * 50) ?>ms">
                    <span><?= e($item['label']) ?></span>
                    <strong><?= e($item['value']) ?></strong>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if ($majors): ?>
<section class="profile-majors profile-section">
    <div class="container">
        <div class="profile-section-heading reveal">
            <div>
                <p class="eyebrow">PROGRAM KEAHLIAN</p>
                <h2>Temukan bidang<br><em>yang ingin kamu tekuni.</em></h2>
            </div>
            <a class="profile-outline-link" href="<?= e(url('jurusan.php')) ?>">
                Semua Jurusan <span>↗</span>
            </a>
        </div>

        <?php vb_dyn_open('majors', 'div', 'class="profile-major-grid"'); ?>
            <?php foreach ($majors as $index => $major): ?>
                <a
                    class="profile-major-card reveal"
                    href="<?= e(url('jurusan.php?id=' . (int) $major['id'])) ?>"
                    style="--delay: <?= (int) ($index * 70) ?>ms"
                >
                    <div class="profile-major-image">
                        <img src="<?= e(img_url($major['image'])) ?>" alt="<?= e($major['name']) ?>" loading="lazy">
                        <span><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                    </div>
                    <div class="profile-major-body">
                        <h3><?= e($major['name']) ?></h3>
                        <p><?= e(mb_substr((string) ($major['description'] ?? ''), 0, 105)) ?></p>
                        <b>Eksplorasi <span>↗</span></b>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php vb_dyn_close(); ?>
    </div>
</section>
<?php endif; ?>

<?php if ($facilities): ?>
<section class="profile-facilities profile-section">
    <div class="container">
        <div class="profile-section-heading reveal">
            <div>
                <p class="eyebrow">FASILITAS</p>
                <h2>Ruang untuk belajar,<br><em>berpraktik, dan berkarya.</em></h2>
            </div>
            <a class="profile-outline-link" href="<?= e(url('fasilitas.php')) ?>">
                Lihat Fasilitas <span>↗</span>
            </a>
        </div>

        <?php vb_dyn_open('facilities', 'div', 'class="profile-facility-strip"'); ?>
            <?php foreach ($facilities as $index => $facility): ?>
                <a class="profile-facility-card reveal" href="<?= e(url('fasilitas.php')) ?>" style="--delay: <?= (int) ($index * 60) ?>ms">
                    <img src="<?= e(img_url($facility['image'])) ?>" alt="<?= e($facility['name']) ?>" loading="lazy">
                    <div>
                        <span><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                        <h3><?= e($facility['name']) ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php vb_dyn_close(); ?>
    </div>
</section>
<?php endif; ?>

<section class="profile-cta profile-section">
    <div class="profile-cta-shape shape-one"></div>
    <div class="profile-cta-shape shape-two"></div>
    <div class="container profile-cta-inner reveal">
        <p class="eyebrow">NEXT CHAPTER</p>
        <h2>Siap menjadi bagian dari<br><em>generasi teknologi berikutnya?</em></h2>
        <p>Kenali program keahlian, lihat kegiatan sekolah, dan temukan langkahmu bersama SMK INFOKOM Kota Bogor.</p>
        <div class="profile-cta-actions">
            <a class="button button-primary profile-journey-button" href="<?= e(url('ppdb.php')) ?>">Mulai Perjalanan <span>↗</span></a>
            <a class="profile-cta-link" href="<?= e(url('kontak.php')) ?>">Hubungi Sekolah <span>→</span></a>
        </div>
    </div>
</section>

<?php vb_end(); ?>
<?php require __DIR__ . '/includes/public-footer.php'; ?>
