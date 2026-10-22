<?php

declare(strict_types=1);

$pageTitle = 'Praktik Kerja Lapangan';

require_once __DIR__ . '/includes/public-header.php';

// Keep existing installations compatible when the PKL website link field is newly added.
if (table_exists('internships')) {
    try {
        $internshipColumns = $pdo->query("SHOW COLUMNS FROM internships")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('website', $internshipColumns, true)) {
            $pdo->exec("ALTER TABLE internships ADD COLUMN website VARCHAR(500) NULL AFTER image");
        }
    } catch (Throwable) {
        // The page can still render from existing fields if migration is unavailable.
    }
}

$rows = $pdo->query(
    "SELECT id, title, type, image, website, description FROM internships ORDER BY id DESC"
)->fetchAll();

$phases = [
    [
        '01',
        'PEMBEKALAN',
        'Siswa mempersiapkan kompetensi, sikap kerja, dan pemahaman sebelum memasuki lingkungan industri.'
    ],
    [
        '02',
        'PENEMPATAN',
        'Penempatan siswa disesuaikan dengan program keahlian dan kebutuhan tempat pelaksanaan PKL.'
    ],
    [
        '03',
        'PELAKSANAAN',
        'Siswa menerapkan kompetensi sekolah melalui pengalaman kerja secara langsung.'
    ],
    [
        '04',
        'MONITORING',
        'Perkembangan siswa dipantau melalui hubungan antara siswa, pembimbing sekolah, dan pembimbing industri.'
    ],
    [
        '05',
        'EVALUASI',
        'Pengalaman dan hasil pelaksanaan PKL menjadi bagian dari proses evaluasi dan pengembangan kompetensi.'
    ],
];

$goals = [
    ['01', 'MENGEMBANGKAN KOMPETENSI', 'Menerapkan kompetensi keahlian melalui pengalaman kerja yang nyata.'],
    ['02', 'MEMAHAMI DUNIA KERJA', 'Mengenal budaya, ritme, tanggung jawab, dan lingkungan kerja profesional.'],
    ['03', 'MEMBENTUK KARAKTER', 'Membangun disiplin, komunikasi, tanggung jawab, dan etika profesional.'],
    ['04', 'MENINGKATKAN KESIAPAN KERJA', 'Menghubungkan pembelajaran di sekolah dengan kebutuhan dunia industri.'],
];


vb_begin('pkl');
?>

<main class="pkl-page">

    <section class="pkl-hero" id="pkl-top">
        <div class="pkl-grid-bg" aria-hidden="true"></div>
        <div class="pkl-orbit pkl-orbit-one" aria-hidden="true"></div>
        <div class="pkl-orbit pkl-orbit-two" aria-hidden="true"></div>
        <div class="pkl-cross pkl-cross-a" aria-hidden="true"></div>
        <div class="pkl-cross pkl-cross-b" aria-hidden="true"></div>

        <div class="container pkl-hero-inner">
            <div class="pkl-hero-copy reveal">
                <div class="pkl-kicker">
                    <span class="pkl-kicker-dot"></span>
                    <span>01 / PRAKTIK KERJA LAPANGAN</span>
                </div>

                <h1><?= e(cms_text('pkl', 'hero_title', 'FROM SCHOOL TO INDUSTRY.')) ?></h1>

                <p class="pkl-hero-lead">
                    <?= e(cms_text('pkl', 'hero_description', 'Praktik Kerja Lapangan untuk menghubungkan pembelajaran di sekolah dengan pengalaman nyata di dunia kerja.')) ?>
                </p>

                <div class="pkl-hero-actions">
                    <a class="btn btn-primary pkl-btn" href="#pkl-journey">
                        Jelajahi PKL <span>↗</span>
                    </a>
                    <a class="pkl-text-link" href="#pkl-placement">
                        Lihat Penempatan <span>↓</span>
                    </a>
                </div>
            </div>

            <div class="pkl-hero-visual reveal" aria-label="Alur Praktik Kerja Lapangan">
                <div class="pkl-visual-label pkl-label-one">SCHOOL</div>
                <div class="pkl-visual-label pkl-label-two">INDUSTRY</div>
                <div class="pkl-visual-label pkl-label-three">EXPERIENCE</div>

                <div class="pkl-journey-core">
                    <div class="pkl-core-ring pkl-core-ring-one"></div>
                    <div class="pkl-core-ring pkl-core-ring-two"></div>
                    <div class="pkl-core-center">
                        <span>PKL</span>
                        <small>FIELD EXPERIENCE</small>
                    </div>

                    <div class="pkl-node pkl-node-one"><b>01</b><span>SCHOOL</span></div>
                    <div class="pkl-node pkl-node-two"><b>02</b><span>PREPARE</span></div>
                    <div class="pkl-node pkl-node-three"><b>03</b><span>WORK</span></div>
                    <div class="pkl-node pkl-node-four"><b>04</b><span>GROW</span></div>

                    <div class="pkl-path pkl-path-one"></div>
                    <div class="pkl-path pkl-path-two"></div>
                    <div class="pkl-path pkl-path-three"></div>
                    <div class="pkl-path-dot"></div>
                </div>

                <div class="pkl-hero-index">PKL / 2026</div>
            </div>
        </div>

        <div class="pkl-scroll-cue" aria-hidden="true">
            <span>SCROLL TO EXPLORE</span>
            <i></i>
        </div>
    </section>

    <section class="pkl-about section" id="pkl-about">
        <div class="container pkl-split">
            <div class="pkl-section-heading reveal">
                <p class="pkl-section-number">02 / ABOUT PKL</p>
                <h2>Belajar di sekolah.<br><em>Berpraktik di industri.</em></h2>
            </div>

            <div class="pkl-about-content reveal">
                <p class="pkl-large-copy">
                    PKL menjadi ruang bagi siswa untuk membawa kompetensi yang dipelajari
                    di sekolah ke dalam situasi kerja yang nyata.
                </p>
                <div class="pkl-about-rule"><span></span></div>
                <p>
                    Melalui pengalaman langsung, siswa dapat mengenal ritme kerja,
                    tanggung jawab, komunikasi, dan budaya profesional secara lebih dekat.
                </p>
            </div>
        </div>

        <div class="pkl-formula-wrap" aria-hidden="true">
            <div class="pkl-formula-item">LEARN</div>
            <div class="pkl-formula-plus">+</div>
            <div class="pkl-formula-item">PRACTICE</div>
            <div class="pkl-formula-plus">+</div>
            <div class="pkl-formula-item">EXPERIENCE</div>
            <div class="pkl-formula-equals">=</div>
            <div class="pkl-formula-result">COMPETENCE</div>
        </div>
    </section>

    <section class="pkl-goals section" id="pkl-goals">
        <div class="container">
            <div class="pkl-section-intro reveal">
                <p class="pkl-section-number">03 / OBJECTIVES</p>
                <h2>Pengalaman kerja<br><span>yang membentuk.</span></h2>
            </div>

            <div class="pkl-goal-list">
                <?php foreach ($goals as $goal): ?>
                    <article class="pkl-goal reveal">
                        <div class="pkl-goal-number"><?= e($goal[0]) ?></div>
                        <div class="pkl-goal-copy">
                            <h3><?= e($goal[1]) ?></h3>
                            <p><?= e($goal[2]) ?></p>
                        </div>
                        <span class="pkl-goal-arrow">↗</span>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="pkl-journey section" id="pkl-journey">
        <div class="pkl-journey-bg-word" aria-hidden="true">JOURNEY</div>
        <div class="container">
            <div class="pkl-section-intro pkl-dark-intro reveal">
                <p class="pkl-section-number">04 / PKL JOURNEY</p>
                <h2>Perjalanan dari<br><span>persiapan sampai evaluasi.</span></h2>
            </div>

            <div class="pkl-timeline" data-pkl-timeline>
                <div class="pkl-timeline-track">
                    <span class="pkl-timeline-progress"></span>
                </div>

                <?php foreach ($phases as $phase): ?>
                    <article class="pkl-phase reveal" data-phase>
                        <div class="pkl-phase-dot"><span><?= e($phase[0]) ?></span></div>
                        <div class="pkl-phase-card">
                            <p><?= e($phase[0]) ?> / PKL</p>
                            <h3><?= e($phase[1]) ?></h3>
                            <span class="pkl-phase-line"></span>
                            <div class="pkl-phase-description"><?= e($phase[2]) ?></div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="pkl-placement section" id="pkl-placement">
        <div class="container">
            <div class="pkl-placement-head reveal">
                <div>
                    <p class="pkl-section-number">05 / STUDENT PLACEMENT</p>
                    <h2>Penempatan<br><span>PKL.</span></h2>
                </div>
                <div class="pkl-placement-note">
                    <span class="pkl-live-dot"></span>
                    <p>Data penempatan dapat dikelola melalui dashboard admin.</p>
                </div>
            </div>

            <div class="pkl-filter reveal" role="tablist" aria-label="Filter program PKL">
                <button type="button" class="is-active" data-pkl-filter="all">SEMUA</button>
                <button type="button" data-pkl-filter="rpl">RPL</button>
                <button type="button" data-pkl-filter="tjkt">TJKT</button>
                <button type="button" data-pkl-filter="dkv">DKV</button>
                <button type="button" data-pkl-filter="pspt">PSPT</button>
            </div>

            <?php vb_dyn_open('placements', 'div', 'class="pkl-placement-grid" data-pkl-placement-grid'); ?>
                <?php foreach ($rows as $index => $row): ?>
                    <?php
                    $searchText = strtolower(
                        ($row['title'] ?? '') . ' ' .
                        ($row['type'] ?? '') . ' ' .
                        ($row['description'] ?? '')
                    );
                    ?>
                    <article
                        class="pkl-placement-card reveal"
                        data-pkl-item
                        data-pkl-category="<?= e($searchText) ?>"
                    >
                        <div class="pkl-placement-top">
                            <span><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                            <i>PKL PROGRAM</i>
                        </div>
                        <div class="pkl-company-visual">
                            <?php if (!empty($row['image'])): ?>
                                <img src="<?= e(img_url($row['image'])) ?>" alt="Logo <?= e($row['title']) ?>" loading="lazy">
                            <?php else: ?>
                                <div class="pkl-company-placeholder" aria-hidden="true">
                                    <?= e(mb_strtoupper(mb_substr($row['title'] ?: 'PKL', 0, 2))) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <p class="pkl-placement-type"><?= e($row['type'] ?: 'PROGRAM PKL') ?></p>
                        <h3><?= e($row['title']) ?></h3>
                        <p class="pkl-placement-description">
                            <?= e($row['description'] ?: 'Informasi pelaksanaan Praktik Kerja Lapangan.') ?>
                        </p>
                        <?php if (!empty($row['website'])): ?>
                            <div class="pkl-placement-footer">
                                <a href="<?= e($row['website']) ?>" target="_blank" rel="noopener noreferrer">
                                    <span>KUNJUNGI SITUS</span>
                                    <b>↗</b>
                                </a>
                            </div>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>

                <?php if (!$rows): ?>
                    <div class="pkl-empty reveal">
                        <span>PKL / 01</span>
                        <h3>Program PKL belum tersedia.</h3>
                        <p>Tambahkan program melalui dashboard admin untuk menampilkannya di halaman ini.</p>
                    </div>
                <?php endif; ?>
            <?php vb_dyn_close(); ?>
        </div>
    </section>

    <section class="pkl-connection section" id="pkl-connection">
        <div class="container">
            <div class="pkl-connection-shell reveal">
                <div class="pkl-connection-copy">
                    <p class="pkl-section-number">06 / INDUSTRY CONNECTION</p>
                    <h2>School.<br>Student.<br><span>Industry.</span></h2>
                    <p>
                        PKL mempertemukan kompetensi sekolah dengan pengalaman kerja melalui
                        proses yang terarah dan terhubung.
                    </p>
                </div>

                <div class="pkl-connection-map" aria-label="Alur koneksi PKL: School, Student, Industry">
                    <div class="pkl-connection-rail" aria-hidden="true">
                        <span class="pkl-rail-segment segment-one"></span>
                        <span class="pkl-rail-segment segment-two"></span>
                        <span class="pkl-rail-dot"></span>
                    </div>

                    <div class="pkl-connect-node node-school">
                        <b>01</b>
                        <strong>SCHOOL</strong>
                        <span>COMPETENCY</span>
                    </div>
                    <div class="pkl-connect-node node-student">
                        <b>02</b>
                        <strong>STUDENT</strong>
                        <span>EXPERIENCE</span>
                    </div>
                    <div class="pkl-connect-node node-industry">
                        <b>03</b>
                        <strong>INDUSTRY</strong>
                        <span>WORKPLACE</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pkl-monitoring section">
        <div class="container">
            <div class="pkl-monitoring-layout">
                <div class="pkl-section-heading reveal">
                    <p class="pkl-section-number">07 / MONITORING</p>
                    <h2>Terhubung.<br><em>Terpantau.</em><br>Terarah.</h2>
                </div>

                <div class="pkl-monitoring-stack reveal">
                    <div class="pkl-monitor-card"><span>01</span><strong>PEMBIMBING SEKOLAH</strong><i>↗</i></div>
                    <div class="pkl-monitor-connector"></div>
                    <div class="pkl-monitor-card is-main"><span>02</span><strong>SISWA</strong><i>↗</i></div>
                    <div class="pkl-monitor-connector"></div>
                    <div class="pkl-monitor-card"><span>03</span><strong>PEMBIMBING INDUSTRI</strong><i>↗</i></div>
                </div>
            </div>
        </div>
    </section>

    <section class="pkl-faq section" id="pkl-faq">
        <div class="container pkl-faq-layout">
            <div class="pkl-section-intro reveal">
                <p class="pkl-section-number">08 / FAQ</p>
                <h2>Pertanyaan<br><span>seputar PKL.</span></h2>
            </div>

            <div class="pkl-accordion reveal">
                <details open>
                    <summary><span>01</span> Apa itu Praktik Kerja Lapangan?</summary>
                    <div class="pkl-answer">PKL merupakan pengalaman belajar di lingkungan kerja yang membantu siswa menerapkan kompetensi dan mengenal budaya profesional.</div>
                </details>
                <details>
                    <summary><span>02</span> Bagaimana proses penempatan PKL?</summary>
                    <div class="pkl-answer">Penempatan mengikuti proses dan kebijakan sekolah serta mempertimbangkan program keahlian dan kebutuhan tempat pelaksanaan.</div>
                </details>
                <details>
                    <summary><span>03</span> Apakah siswa mendapatkan monitoring?</summary>
                    <div class="pkl-answer">Monitoring menjadi bagian dari proses PKL melalui keterhubungan siswa, pembimbing sekolah, dan pembimbing industri.</div>
                </details>
                <details>
                    <summary><span>04</span> Bagaimana hasil PKL dievaluasi?</summary>
                    <div class="pkl-answer">Evaluasi dilakukan sebagai bagian dari peninjauan pengalaman dan perkembangan kompetensi siswa selama pelaksanaan PKL.</div>
                </details>
            </div>
        </div>
    </section>

    <section class="pkl-cta">
        <div class="pkl-cta-grid" aria-hidden="true"></div>
        <div class="pkl-cta-shape" aria-hidden="true"></div>
        <div class="container pkl-cta-inner reveal">
            <p class="pkl-section-number">09 / YOUR EXPERIENCE</p>
            <h2>SIAP MENGENAL<br><span>DUNIA KERJA?</span></h2>
            <p>Bangun pengalaman, kembangkan kompetensi, dan bersiap menghadapi dunia industri.</p>
            <a class="btn btn-primary pkl-btn" href="#pkl-journey">Lihat Alur PKL <span>↗</span></a>
        </div>
    </section>

</main>

<?php vb_end(); ?>
<?php require __DIR__ . '/includes/public-footer.php'; ?>
