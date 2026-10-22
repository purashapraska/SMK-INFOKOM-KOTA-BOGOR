<?php
    $pageTitle = 'Prestasi';
    require_once __DIR__ . '/includes/public-header.php';

// Visual Builder integration: keep the original PHP page as the fallback.
vb_begin('prestasi');

    // Keep the page compatible with existing installations while adding richer achievement metadata.
    try {
        $columns = $pdo->query("SHOW COLUMNS FROM achievements")->fetchAll(PDO::FETCH_COLUMN);
        foreach ([
            'major' => "ALTER TABLE achievements ADD COLUMN major VARCHAR(20) NULL AFTER year",
            'level' => "ALTER TABLE achievements ADD COLUMN level VARCHAR(30) NULL AFTER major",
            'rank_label' => "ALTER TABLE achievements ADD COLUMN rank_label VARCHAR(80) NULL AFTER level",
        ] as $column => $alter) {
            if (!in_array($column, $columns, true)) {
                $pdo->exec($alter);
            }
        }
    } catch (Throwable) {
        // Existing data remains usable even if schema migration is unavailable.
    }

    // Seed 10 demo achievements automatically when the table is empty.
    // If the previous package's 10 known demo records are still present, replace
    // only that demo set so the new sample content appears without touching
    // unrelated/real achievement records.
    $existingAchievementCount = (int) $pdo->query('SELECT COUNT(*) FROM achievements')->fetchColumn();

    if ($existingAchievementCount > 0) {
        $oldDemoTitles = [
            'LKS Web Technologies',
            'Kompetisi Desain Grafis Pelajar',
            'Network Competition SMK',
            'Produksi Video Kreatif Pelajar',
            'Hackathon Pelajar Jawa Barat',
            'Poster Digital Pelajar',
            'IT Network Design Competition',
            'Aplikasi Inovatif Pelajar',
            'Podcast Pendidikan Pelajar',
            'Kompetisi Animasi 2D Pelajar',
        ];

        $placeholders = implode(',', array_fill(0, count($oldDemoTitles), '?'));
        $demoCheck = $pdo->prepare("SELECT COUNT(*) FROM achievements WHERE title IN ($placeholders)");
        $demoCheck->execute($oldDemoTitles);
        $oldDemoCount = (int) $demoCheck->fetchColumn();

        if ($oldDemoCount === $existingAchievementCount && $existingAchievementCount <= count($oldDemoTitles)) {
            $deleteDemo = $pdo->prepare("DELETE FROM achievements WHERE title IN ($placeholders)");
            $deleteDemo->execute($oldDemoTitles);
            $existingAchievementCount = 0;
        }
    }
    if ($existingAchievementCount === 0) {
        $demoAchievements = [
            ['Mobile Legends Student Championship', 2026, 'RPL', 'Nasional', 'Juara 1', 'DEMO: contoh pencapaian esports pelajar tingkat nasional.'],
            ['Lomba Kompetensi Siswa — Web Technologies', 2026, 'RPL', 'Nasional', 'Juara 2', 'DEMO: contoh pencapaian pengembangan web pada kompetisi tingkat nasional.'],
            ['Network Infrastructure Competition', 2026, 'TKJ', 'Provinsi', 'Juara 1', 'DEMO: contoh kompetisi perancangan dan konfigurasi infrastruktur jaringan.'],
            ['Digital Poster Championship', 2026, 'DKV', 'Nasional', 'Juara 1', 'DEMO: contoh pencapaian desain poster digital tingkat nasional.'],
            ['Short Movie Production Competition', 2025, 'PSPT', 'Provinsi', 'Juara 2', 'DEMO: contoh kompetisi produksi film pendek pelajar.'],
            ['UI/UX Design Challenge', 2025, 'RPL', 'Nasional', 'Juara 1', 'DEMO: contoh kompetisi desain antarmuka dan pengalaman pengguna.'],
            ['Cyber Security Competition', 2025, 'RPL', 'Nasional', 'Juara 3', 'DEMO: contoh kompetisi keamanan siber pelajar tingkat nasional.'],
            ['Video Creative Festival', 2025, 'PSPT', 'Kota', 'Juara 1', 'DEMO: contoh kompetisi produksi video kreatif tingkat kota.'],
            ['Network Design Competition', 2024, 'TKJ', 'Provinsi', 'Juara 2', 'DEMO: contoh kompetisi desain jaringan tingkat provinsi.'],
            ['Digital Illustration Competition', 2024, 'DKV', 'Nasional', 'Juara 1', 'DEMO: contoh kompetisi ilustrasi digital tingkat nasional.'],
        ];

        $seedStatement = $pdo->prepare(
            'INSERT INTO achievements (title, image, year, major, level, rank_label, description) VALUES (?, NULL, ?, ?, ?, ?, ?)'
        );

        foreach ($demoAchievements as $demo) {
            $seedStatement->execute($demo);
        }
    }

    $rows = $pdo->query(
        'SELECT * FROM achievements ORDER BY year DESC, id DESC'
    )->fetchAll();

    $total = count($rows);
    $levels = [
        'Kota' => 0,
        'Provinsi' => 0,
        'Nasional' => 0,
    ];
    $majors = [
        'RPL' => 0,
        'TKJ' => 0,
        'PSPT' => 0,
        'DKV' => 0,
    ];

    foreach ($rows as $row) {
        $level = trim((string) ($row['level'] ?? ''));
        $major = strtoupper(trim((string) ($row['major'] ?? '')));

        if (isset($levels[$level])) {
            $levels[$level]++;
        }

        if (isset($majors[$major])) {
            $majors[$major]++;
        }
    }

    $levelMax = max(1, ...array_values($levels));
    $yearGroups = [];

    foreach ($rows as $row) {
        $year = (string) ($row['year'] ?? '');
        if ($year === '') {
            $year = 'LAINNYA';
        }
        $yearGroups[$year][] = $row;
    }
?>

<main class="achievement-page">
    <section class="achievement-hero">
        <div class="achievement-grid"></div>
        <div class="achievement-orbit achievement-orbit-one"></div>
        <div class="achievement-orbit achievement-orbit-two"></div>
        <div class="achievement-circuit achievement-circuit-one"></div>
        <div class="achievement-circuit achievement-circuit-two"></div>

        <div class="container achievement-hero-inner">
            <div class="achievement-hero-copy reveal">
                <p class="achievement-kicker">01 / ACHIEVEMENTS <span class="achievement-demo-badge">SAMPLE CONTENT</span></p>
                <h1><?= e(cms_text('prestasi', 'hero_title', 'PROVING WHAT WE CAN DO.')) ?></h1>
                <p class="achievement-hero-description">
                    <?= e(cms_text('prestasi', 'hero_description', 'Prestasi siswa dan sekolah sebagai rekam jejak kompetensi, kreativitas, dan pengalaman berkompetisi.')) ?>
                </p>

                <div class="achievement-hero-stats">
                    <div class="achievement-total">
                        <strong data-counter="<?= e((string) $total) ?>" data-suffix="+">0</strong>
                        <span>TOTAL ACHIEVEMENTS</span>
                    </div>
                    <?php foreach ($majors as $major => $count): ?>
                        <div class="achievement-mini-stat">
                            <b><?= e((string) $count) ?></b>
                            <span><?= e($major) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="achievement-trophy-stage reveal" aria-hidden="true">
                <div class="trophy-halo"></div>
                <div class="trophy-ring trophy-ring-a"></div>
                <div class="trophy-ring trophy-ring-b"></div>
                <div class="trophy-cup">
                    <span class="trophy-star">★</span>
                    <span class="trophy-handle trophy-handle-left"></span>
                    <span class="trophy-handle trophy-handle-right"></span>
                    <span class="trophy-bowl"></span>
                    <span class="trophy-stem"></span>
                    <span class="trophy-base"></span>
                </div>
                <div class="trophy-label trophy-label-top">ACHIEVEMENT</div>
                <div class="trophy-label trophy-label-side">SMK<br>INFOKOM</div>
                <div class="trophy-year">// 2026</div>
            </div>
        </div>
    </section>

    <section class="achievement-section achievement-overview">
        <div class="container">
            <div class="achievement-section-heading reveal">
                <div>
                    <p class="achievement-kicker">02 / ACHIEVEMENT OVERVIEW</p>
                    <h2>Pencapaian kami<br> dalam angka.</h2>
                </div>
                <p>
                    Setiap prestasi adalah hasil dari kerja keras, kolaborasi,
                    disiplin, dan semangat untuk terus berkembang.
                </p>
            </div>

            <div class="achievement-level-grid">
                <?php foreach ($levels as $level => $count): ?>
                    <?php $percentage = $total > 0 ? (int) round(($count / $total) * 100) : 0; ?>
                    <article class="achievement-level-card reveal">
                        <div class="achievement-level-top">
                            <strong><?= e((string) $count) ?></strong>
                            <span><?= e(strtoupper($level)) ?></span>
                        </div>
                        <p>
                            Prestasi tingkat <?= e($level === 'Kota' ? 'Kota Bogor dan sekitarnya' : ($level === 'Provinsi' ? 'Jawa Barat' : 'Nasional')) ?>.
                        </p>
                        <div class="achievement-progress">
                            <span style="--achievement-progress: <?= e((string) $percentage) ?>%"></span>
                        </div>
                        <small><?= e((string) $percentage) ?>%</small>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="achievement-section achievement-list-section" id="prestasi-list">
        <div class="container">
            <div class="achievement-list-heading reveal">
                <div>
                    <p class="achievement-kicker">03 / PRESTASI TERBARU</p>
                    <h2>Prestasi terbaru.</h2>
                    <p>10 data contoh prestasi untuk preview halaman sekolah.</p>
                </div>

                <div class="achievement-filters" role="tablist" aria-label="Filter prestasi berdasarkan jurusan">
                    <button class="achievement-filter is-active" type="button" data-achievement-filter="all">Semua</button>
                    <?php foreach (array_keys($majors) as $major): ?>
                        <button class="achievement-filter" type="button" data-achievement-filter="<?= e($major) ?>"><?= e($major) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="achievement-card-grid">
                <?php foreach ($rows as $index => $row): ?>
                    <?php
                        $major = strtoupper(trim((string) ($row['major'] ?? '')));
                        $level = trim((string) ($row['level'] ?? ''));
                        $rank = trim((string) ($row['rank_label'] ?? ''));
                    ?>
                    <article
                        class="achievement-card reveal"
                        data-achievement-item
                        data-achievement-major="<?= e($major) ?>"
                        style="--achievement-delay: <?= e((string) (($index % 5) * 70)) ?>ms"
                    >
                        <div class="achievement-card-top">
                            <span class="achievement-number"><?= e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                            <span class="achievement-major"><?= e($major !== '' ? $major : 'SMK') ?></span>
                        </div>
                        <div class="achievement-card-body">
                            <span class="achievement-rank"><?= e($rank !== '' ? $rank : 'PRESTASI') ?></span>
                            <h3><?= e($row['title']) ?></h3>
                            <p><?= e($row['description']) ?></p>
                        </div>
                        <div class="achievement-card-meta">
                            <span>⌖ <?= e($level !== '' ? $level : 'Sekolah') ?></span>
                            <span>▣ <?= e((string) ($row['year'] ?? '')) ?></span>
                            <span class="achievement-arrow">↗</span>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="achievement-section achievement-champions-section">
        <div class="container">
            <div class="achievement-section-heading reveal">
                <div>
                    <p class="achievement-kicker">04 / HALL OF CHAMPIONS</p>
                    <h2>Three moments.<br>One mindset.</h2>
                </div>
                <p>
                    Tiga pencapaian unggulan sebagai visual penghormatan untuk semangat
                    kompetisi, disiplin, dan keberanian untuk tampil.
                </p>
            </div>

            <div class="achievement-podium">
                <article class="champion-podium podium-second reveal">
                    <div class="podium-medal"><span>02</span></div>
                    <p class="podium-rank">RUNNER UP</p>
                    <h3>Web Technologies</h3>
                    <span>RPL · NASIONAL · 2026</span>
                    <div class="podium-block"><b>02</b></div>
                </article>
                <article class="champion-podium podium-first reveal">
                    <div class="podium-medal podium-medal-gold"><span>01</span></div>
                    <p class="podium-rank">CHAMPION</p>
                    <h3>Mobile Legends</h3>
                    <span>RPL · NASIONAL · 2026</span>
                    <div class="podium-block"><b>01</b></div>
                </article>
                <article class="champion-podium podium-third reveal">
                    <div class="podium-medal"><span>03</span></div>
                    <p class="podium-rank">THIRD PLACE</p>
                    <h3>Cyber Security</h3>
                    <span>RPL · NASIONAL · 2025</span>
                    <div class="podium-block"><b>03</b></div>
                </article>
            </div>
        </div>
    </section>

    <section class="achievement-section achievement-timeline-section">
        <div class="container">
            <div class="achievement-section-heading reveal">
                <div>
                    <p class="achievement-kicker">05 / TIMELINE PRESTASI</p>
                    <h2>Jejak prestasi<br>dari masa ke masa.</h2>
                </div>
                <p>
                    Perjalanan panjang dengan dedikasi, kompetisi, dan semangat
                    untuk terus menciptakan pencapaian baru.
                </p>
            </div>

            <div class="achievement-timeline">
                <?php foreach ($yearGroups as $year => $items): ?>
                    <div class="achievement-timeline-year reveal">
                        <div class="achievement-year-label"><?= e($year) ?></div>
                        <div class="achievement-year-items">
                            <?php foreach ($items as $item): ?>
                                <div class="achievement-timeline-item">
                                    <span class="achievement-timeline-dot"></span>
                                    <div>
                                        <b><?= e($item['rank_label'] ?? 'Prestasi') ?></b>
                                        <strong><?= e($item['title']) ?></strong>
                                        <small><?= e($item['major'] ?? 'SMK') ?> · <?= e($item['level'] ?? 'Sekolah') ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="achievement-section achievement-major-section">
        <div class="container">
            <div class="achievement-section-heading reveal">
                <div>
                    <p class="achievement-kicker">06 / BY MAJOR</p>
                    <h2>Prestasi<br>berdasarkan jurusan.</h2>
                </div>
                <p>Lihat sebaran pencapaian dari setiap kompetensi keahlian.</p>
            </div>

            <div class="achievement-major-grid">
                <?php foreach ($majors as $major => $count): ?>
                    <?php $majorPercentage = $total > 0 ? (int) round(($count / $total) * 100) : 0; ?>
                    <article class="achievement-major-card reveal">
                        <span class="achievement-major-icon">
                            <?= $major === 'RPL' ? '</>' : ($major === 'TKJ' ? '⌁' : ($major === 'PSPT' ? '▶' : '✦')) ?>
                        </span>
                        <div>
                            <b><?= e($major) ?></b>
                            <strong><?= e((string) $count) ?></strong>
                            <small>PRESTASI</small>
                        </div>
                        <div class="achievement-mini-bars" aria-hidden="true">
                            <?php for ($bar = 1; $bar <= 5; $bar++): ?>
                                <i style="--bar-height: <?= e((string) max(25, min(100, $majorPercentage + (($bar - 3) * 10)))) ?>%"></i>
                            <?php endfor; ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="achievement-section achievement-level-section">
        <div class="container">
            <div class="achievement-level-layout reveal">
                <div>
                    <p class="achievement-kicker">07 / LEVEL OF ACHIEVEMENT</p>
                    <h2>Tingkat<br>pencapaian.</h2>
                    <p>
                        Sebaran prestasi berdasarkan level kompetisi yang diikuti siswa.
                    </p>
                </div>
                <div class="achievement-level-bars">
                    <?php foreach ($levels as $level => $count): ?>
                        <?php $width = (int) round(($count / $levelMax) * 100); ?>
                        <div class="achievement-level-row">
                            <div class="achievement-level-label">
                                <b><?= e(strtoupper($level)) ?></b>
                                <span><?= e((string) $count) ?> Prestasi</span>
                            </div>
                            <div class="achievement-level-track">
                                <span style="--level-width: <?= e((string) $width) ?>%"></span>
                            </div>
                            <strong><?= e((string) ($total > 0 ? round(($count / $total) * 100) : 0)) ?>%</strong>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="achievement-level-quote">
                    <span>★</span>
                    <p>Dari kota hingga nasional, setiap pencapaian menjadi langkah menuju masa depan yang lebih tinggi.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="achievement-cta-section">
        <div class="achievement-cta-grid"></div>
        <div class="container achievement-cta-inner reveal">
            <div>
                <p class="achievement-kicker">08 / KEEP GOING</p>
                <h2>Setiap prestasi<br><span>dimulai dari satu langkah.</span></h2>
            </div>
            <div class="achievement-cta-copy">
                <p>Terus berprestasi. Terus berkompetisi. Terus berkarya.</p>
                <a class="btn btn-yellow" href="<?= e(url('jurusan.php')) ?>">Lihat Program Sekolah <span>→</span></a>
            </div>
        </div>
    </section>
</main>

<?php vb_end(); ?>

<?php require __DIR__ . '/includes/public-footer.php'; ?>
