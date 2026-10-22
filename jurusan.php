<?php
    $pageTitle = 'Jurusan';
    require_once __DIR__ . '/includes/public-header.php';

// Visual Builder integration: directory and each major detail get independent revisions.
$vbJurusanId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$vbJurusanPageKey = 'jurusan';
if ($vbJurusanId > 0) {
    try {
        $vbMajorStmt = $pdo->prepare('SELECT name FROM majors WHERE id = ? LIMIT 1');
        $vbMajorStmt->execute([$vbJurusanId]);
        $vbMajorName = mb_strtolower((string) ($vbMajorStmt->fetchColumn() ?: ''));
        $vbMajorSlug = str_contains($vbMajorName, 'perangkat lunak') ? 'rpl' : (str_contains($vbMajorName, 'komputer') || str_contains($vbMajorName, 'jaringan') ? 'tkj' : (str_contains($vbMajorName, 'desain') || str_contains($vbMajorName, 'komunikasi visual') ? 'dkv' : (str_contains($vbMajorName, 'siaran') || str_contains($vbMajorName, 'televisi') ? 'pspt' : '')));
        if ($vbMajorSlug !== '') { $vbJurusanPageKey = 'jurusan-' . $vbMajorSlug; }
    } catch (Throwable $e) { /* Original page remains available if major lookup fails. */ }
}
vb_begin($vbJurusanPageKey);
    $id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
    ) ?: 0;
    $statement = $pdo->query(
    'SELECT * FROM majors ORDER BY id ASC'
    );
    $majors = $statement->fetchAll();
    $selectedMajor = null;
    if ($id > 0) {
    foreach ($majors as $major) {
    if ((int) $major['id'] === $id) {
    $selectedMajor = $major;
    break;
    }
    }
    }
?>
<?php
$majorVisuals = [
    'rpl' => [
        'label' => 'REKAYASA PERANGKAT LUNAK',
        'short' => 'RPL',
        'color' => '#D92D3F',
        'soft' => '#FFF0F2',
        'image' => 'assets/images/jurusan/rpl.png',
        'focus' => ['Pengembangan aplikasi', 'Website', 'Solusi perangkat lunak'],
        'number' => '01',
    ],
    'tkj' => [
        'label' => 'TEKNIK KOMPUTER DAN JARINGAN',
        'short' => 'TKJ',
        'color' => '#7C3AED',
        'soft' => '#F4EEFF',
        'image' => 'assets/images/jurusan/tkj.png',
        'focus' => ['Jaringan komputer', 'Sistem dan perangkat', 'Infrastruktur teknologi'],
        'number' => '02',
    ],
    'dkv' => [
        'label' => 'DESAIN KOMUNIKASI VISUAL',
        'short' => 'DKV',
        'color' => '#F28C28',
        'soft' => '#FFF4E8',
        'image' => 'assets/images/jurusan/dkv.png',
        'focus' => ['Komunikasi visual', 'Desain grafis', 'Branding & karya digital'],
        'number' => '03',
    ],
    'pspt' => [
        'label' => 'PRODUKSI DAN SIARAN PROGRAM TELEVISI',
        'short' => 'PSPT',
        'color' => '#169C63',
        'soft' => '#EAF9F1',
        'image' => 'assets/images/jurusan/pspt.png',
        'focus' => ['Produksi video', 'Penyiaran & tata kamera', 'Audio & editing'],
        'number' => '04',
    ],
];

function major_slug_from_name(string $name): string
{
    $name = mb_strtolower($name);
    return match (true) {
        str_contains($name, 'perangkat lunak') => 'rpl',
        str_contains($name, 'komputer') || str_contains($name, 'jaringan') => 'tkj',
        str_contains($name, 'desain') || str_contains($name, 'komunikasi visual') => 'dkv',
        str_contains($name, 'siaran') || str_contains($name, 'televisi') => 'pspt',
        default => 'rpl',
    };
}

function major_view(array $major, array $majorVisuals): array
{
    $slug = major_slug_from_name((string) ($major['name'] ?? ''));
    $visual = $majorVisuals[$slug];
    $image = !empty($major['image']) ? img_url($major['image']) : url($visual['image']);
    $major['slug'] = $slug;
    $major['visual'] = $visual;
    $major['image_url'] = $image;
    return $major;
}

$majorItems = array_map(
    static fn (array $major): array => major_view($major, $majorVisuals),
    $majors
);

$selectedView = $selectedMajor
    ? major_view($selectedMajor, $majorVisuals)
    : null;
?>

<?php if ($selectedView): ?>
    <?php $visual = $selectedView['visual']; ?>
    <main
        class="major-detail-page"
        style="--major-color: <?= e($visual['color']) ?>; --major-soft: <?= e($visual['soft']) ?>;"
        data-major="<?= e($selectedView['slug']) ?>"
    >
        <section class="major-detail-hero">
            <div class="major-detail-orb major-detail-orb-one"></div>
            <div class="major-detail-orb major-detail-orb-two"></div>
            <div class="container major-detail-hero-grid">
                <div class="major-detail-copy reveal">
                    <a class="major-detail-back" href="<?= e(url('jurusan.php')) ?>">
                        <span>←</span> Semua Jurusan
                    </a>
                    <div class="major-kicker">
                        <span style="background: <?= e($visual['color']) ?>"></span>
                        PROGRAM KEAHLIAN <?= e($visual['number']) ?>
                    </div>
                    <h1><?= e($selectedView['name']) ?></h1>
                    <p class="major-detail-lead">
                        <?= nl2br(e($selectedView['description'] ?? '')) ?>
                    </p>
                    <div class="major-detail-actions">
                        <a class="button major-color-button" href="<?= e(url('ppdb.php')) ?>">
                            Mulai Perjalanan <span>↗</span>
                        </a>
                        <a class="major-text-button" href="#kompetensi">
                            Lihat kompetensi <span>↓</span>
                        </a>
                    </div>
                </div>

                <div class="major-logo-stage reveal" style="--delay: 120ms">
                    <div class="major-stage-grid"></div>
                    <div class="major-stage-ring"></div>
                    <span class="major-stage-number"><?= e($visual['number']) ?></span>
                    <div class="major-logo-glow"></div>
                    <img
                        src="<?= e($selectedView['image_url']) ?>"
                        alt="Logo <?= e($selectedView['name']) ?>"
                        class="major-logo-image"
                    >
                    <span class="major-stage-label"><?= e($visual['short']) ?></span>
                </div>
            </div>
        </section>

        <section class="major-detail-intro section">
            <div class="container major-intro-grid">
                <div class="major-intro-heading reveal">
                    <p class="eyebrow">TENTANG PROGRAM</p>
                    <h2><?= e(cms_text('jurusan', 'section_1_title', 'Kenali bidang yang ingin kamu tekuni.')) ?></h2>
                </div>
                <div class="major-intro-text reveal" style="--delay: 100ms">
                    <p>
                        Halaman ini merangkum informasi utama program keahlian berdasarkan deskripsi yang tersedia pada data website sekolah.
                    </p>
                    <div class="major-info-line">
                        <span style="background: <?= e($visual['color']) ?>"></span>
                        <strong><?= e($visual['label']) ?></strong>
                    </div>
                </div>
            </div>
        </section>

        <section class="major-focus-section section" id="kompetensi">
            <div class="container">
                <div class="major-section-head reveal">
                    <div>
                        <p class="eyebrow">FOKUS PEMBELAJARAN</p>
                        <h2>Bidang utama yang menjadi <em>identitas jurusan.</em></h2>
                    </div>
                    <span class="major-section-index" style="color: <?= e($visual['color']) ?>">/ <?= e($visual['short']) ?></span>
                </div>

                <div class="major-focus-grid">
                    <?php foreach ($visual['focus'] as $index => $focus): ?>
                        <article class="major-focus-card reveal" style="--delay: <?= (int) ($index * 90) ?>ms">
                            <span class="major-focus-number" style="color: <?= e($visual['color']) ?>">0<?= $index + 1 ?></span>
                            <span class="major-focus-dot" style="background: <?= e($visual['color']) ?>"></span>
                            <h3><?= e($focus) ?></h3>
                            <p>
                                Bagian dari ruang lingkup utama <?= e($visual['short']) ?> yang ditampilkan berdasarkan deskripsi program keahlian.
                            </p>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="major-visual-section">
            <div class="container">
                <div class="major-visual-panel reveal">
                    <div class="major-visual-copy">
                        <p class="eyebrow">IDENTITAS PROGRAM</p>
                        <h2><?= e($visual['short']) ?><br><em><?= e($selectedView['name']) ?></em></h2>
                        <p>
                            Gunakan informasi pada bagian ini sebagai pengantar sebelum mengenal lebih jauh bidang yang tersedia di program keahlian.
                        </p>
                    </div>
                    <div class="major-visual-logo-wrap">
                        <div class="major-visual-logo-backdrop"></div>
                        <img src="<?= e($selectedView['image_url']) ?>" alt="<?= e($selectedView['name']) ?>" loading="lazy">
                    </div>
                </div>
            </div>
        </section>

        <section class="major-next-section section">
            <div class="container">
                <div class="major-section-head reveal">
                    <div>
                        <p class="eyebrow">JELAJAHI LAINNYA</p>
                        <h2>Temukan program <em>lainnya.</em></h2>
                    </div>
                    <a class="profile-outline-link major-outline-link" href="<?= e(url('jurusan.php')) ?>">
                        Semua Jurusan <span>↗</span>
                    </a>
                </div>

                <div class="major-next-grid">
                    <?php foreach ($majorItems as $index => $major): ?>
                        <?php if ((int) $major['id'] === (int) $selectedView['id']) continue; ?>
                        <a
                            class="major-next-card reveal"
                            href="<?= e(url('jurusan.php?id=' . (int) $major['id'])) ?>"
                            style="--card-color: <?= e($major['visual']['color']) ?>; --card-soft: <?= e($major['visual']['soft']) ?>; --delay: <?= (int) ($index * 80) ?>ms"
                        >
                            <div class="major-next-logo">
                                <img src="<?= e($major['image_url']) ?>" alt="<?= e($major['name']) ?>" loading="lazy">
                            </div>
                            <div>
                                <span><?= e($major['visual']['short']) ?></span>
                                <h3><?= e($major['name']) ?></h3>
                            </div>
                            <b>↗</b>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="major-detail-cta">
            <div class="container">
                <div class="major-cta-inner reveal">
                    <div>
                        <p class="eyebrow">SMK INFOKOM KOTA BOGOR</p>
                        <h2>Sudah menemukan bidang<br><em>yang ingin kamu tekuni?</em></h2>
                    </div>
                    <a class="button major-color-button" href="<?= e(url('ppdb.php')) ?>">
                        Lihat Informasi PPDB <span>↗</span>
                    </a>
                </div>
            </div>
        </section>
    </main>
<?php else: ?>
    <main class="major-index-page">
        <section class="major-index-hero">
            <div class="major-index-noise"></div>
            <div class="container major-index-hero-grid">
                <div class="reveal">
                    <p class="eyebrow">PROGRAM KEAHLIAN</p>
                    <h1><?= e(cms_text('jurusan', 'hero_title', 'Temukan bidang yang ingin kamu kuasai.')) ?></h1>
                    <p class="major-index-lead">
                        Kenali empat program keahlian SMK INFOKOM Kota Bogor dan pilih bidang yang paling sesuai dengan minatmu.
                    </p>
                </div>
                <div class="major-index-side reveal" style="--delay: 140ms">
                    <span>04</span>
                    <p>PROGRAM<br>KEAHLIAN</p>
                </div>
            </div>
        </section>

        <section class="major-directory section">
            <div class="container">
                <div class="major-directory-head reveal">
                    <div>
                        <p class="eyebrow">EXPLORE</p>
                        <h2>Empat jalur, <em>satu langkah ke depan.</em></h2>
                    </div>
                    <p>Pilih kartu untuk melihat detail program keahlian, fokus bidang, dan identitas visualnya.</p>
                </div>

                <div class="major-directory-grid">
                    <?php foreach ($majorItems as $index => $major): ?>
                        <?php $visual = $major['visual']; ?>
                        <a
                            class="major-directory-card reveal"
                            href="<?= e(url('jurusan.php?id=' . (int) $major['id'])) ?>"
                            style="--card-color: <?= e($visual['color']) ?>; --card-soft: <?= e($visual['soft']) ?>; --delay: <?= (int) ($index * 100) ?>ms"
                        >
                            <div class="major-card-topline">
                                <span>0<?= $index + 1 ?></span>
                                <i><?= e($visual['short']) ?></i>
                            </div>
                            <div class="major-directory-logo">
                                <div class="major-card-orbit"></div>
                                <img src="<?= e($major['image_url']) ?>" alt="Logo <?= e($major['name']) ?>" loading="lazy">
                            </div>
                            <div class="major-directory-body">
                                <p class="major-card-label"><?= e($visual['label']) ?></p>
                                <h3><?= e($major['name']) ?></h3>
                                <p><?= e($major['description'] ?? '') ?></p>
                                <span class="major-card-link">Eksplorasi jurusan <b>↗</b></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="major-index-cta">
            <div class="container">
                <div class="major-index-cta-inner reveal">
                    <div>
                        <p class="eyebrow">YOUR NEXT STEP</p>
                        <h2>Kenali bidangmu.<br><em>Bangun masa depanmu.</em></h2>
                    </div>
                    <a class="button" href="<?= e(url('ppdb.php')) ?>">Informasi PPDB <span>↗</span></a>
                </div>
            </div>
        </section>
    </main>
<?php endif; ?>

<?php vb_end(); ?>

<?php require __DIR__ . '/includes/public-footer.php'; ?>
