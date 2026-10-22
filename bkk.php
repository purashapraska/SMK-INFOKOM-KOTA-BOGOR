<?php

declare(strict_types=1);

$pageTitle = 'BKK';

require_once __DIR__ . '/includes/public-header.php';

$jobs = $pdo->query(
    'SELECT * FROM jobs '
    . 'WHERE status = "published" '
    . 'AND (deadline IS NULL OR deadline >= CURDATE()) '
    . 'ORDER BY created_at DESC'
)->fetchAll();

function bkk_category(string $position): string
{
    $value = strtolower($position);

    if (preg_match('/developer|programmer|software|network|it|system|data|cyber|web|engineer/', $value)) {
        return 'Teknologi';
    }

    if (preg_match('/design|designer|creative|content|multimedia|graphic/', $value)) {
        return 'Kreatif';
    }

    if (preg_match('/admin|administrasi|finance|account|hr|human resource/', $value)) {
        return 'Administrasi';
    }

    if (preg_match('/marketing|sales|customer|business/', $value)) {
        return 'Marketing';
    }

    return 'Lainnya';
}

function bkk_initials(string $company): string
{
    $words = preg_split('/\s+/', trim($company)) ?: [];
    $initials = '';

    foreach ($words as $word) {
        if ($word === '') {
            continue;
        }

        $initials .= strtoupper(substr($word, 0, 1));

        if (strlen($initials) >= 2) {
            break;
        }
    }

    return $initials !== '' ? $initials : 'BK';
}

$bkkLogo = setting('bkk_logo', '');

vb_begin('bkk');
?>

<main class="bkk-page">
    <section class="bkk-hero">
        <div class="bkk-grid-bg" aria-hidden="true"></div>
        <div class="bkk-hero-glow bkk-hero-glow-one" aria-hidden="true"></div>
        <div class="bkk-hero-glow bkk-hero-glow-two" aria-hidden="true"></div>

        <div class="container bkk-hero-inner">
            <div class="bkk-hero-copy reveal">
                <p class="bkk-kicker"><span></span>01 / BKK</p>
                <h1><?= e(cms_text('bkk', 'hero_title', 'BURSA KERJA KHUSUS')) ?></h1>
                <p class="bkk-hero-text">
                    <?= e(cms_text('bkk', 'hero_description', setting('bkk_intro', 'Menghubungkan kompetensi siswa dengan peluang dan kebutuhan dunia kerja.'))) ?>
                </p>

                <div class="bkk-hero-actions">
                    <a class="bkk-btn bkk-btn-primary" href="#lowongan">
                        <span>Lihat Lowongan</span>
                        <strong>↗</strong>
                    </a>
                    <a class="bkk-btn bkk-btn-outline" href="#tentang-bkk">
                        Tentang BKK
                    </a>
                </div>
            </div>

            <div class="bkk-hero-visual reveal" aria-label="BKK INFOKOM">
                <div class="bkk-orbit bkk-orbit-outer" aria-hidden="true">
                    <span class="bkk-orbit-dot bkk-dot-one"></span>
                    <span class="bkk-orbit-dot bkk-dot-two"></span>
                    <span class="bkk-orbit-dot bkk-dot-three"></span>
                </div>
                <div class="bkk-orbit bkk-orbit-middle" aria-hidden="true"></div>
                <div class="bkk-orbit bkk-orbit-inner" aria-hidden="true"></div>

                <div class="bkk-visual-line bkk-line-one" aria-hidden="true"></div>
                <div class="bkk-visual-line bkk-line-two" aria-hidden="true"></div>

                <div class="bkk-logo-core">
                    <?php if ($bkkLogo !== ''): ?>
                        <img
                            src="<?= e(img_url($bkkLogo)) ?>"
                            alt="Logo BKK INFOKOM"
                        >
                    <?php else: ?>
                        <span class="bkk-logo-fallback">BKK<small>INFOKOM</small></span>
                    <?php endif; ?>
                </div>

                <div class="bkk-float-label bkk-label-career">
                    <span>CAREER</span>
                    <i>01</i>
                </div>
                <div class="bkk-float-label bkk-label-industry">
                    <span>INDUSTRY</span>
                    <i>02</i>
                </div>
                <div class="bkk-float-label bkk-label-opportunity">
                    <span>OPPORTUNITY</span>
                    <i>03</i>
                </div>

                <div class="bkk-hero-caption">
                    <span>FROM SCHOOL</span>
                    <b>TO CAREER</b>
                </div>
            </div>
        </div>

        <div class="bkk-hero-bottom container">
            <span>CAREER</span>
            <span>INDUSTRY</span>
            <span>SKILLS</span>
            <span>OPPORTUNITY</span>
            <span>FUTURE</span>
            <a href="#tentang-bkk" aria-label="Scroll ke konten BKK">↓</a>
        </div>
    </section>

    <section class="section bkk-about" id="tentang-bkk">
        <div class="container bkk-about-grid">
            <div class="bkk-section-label reveal">
                <span>02</span>
                <p>ABOUT BKK</p>
            </div>
            <div class="bkk-about-copy reveal">
                <h2><?= e(cms_text('bkk', 'section_2_title', 'Menghubungkan sekolah dengan dunia industri.')) ?></h2>
                <p>
                    BKK SMK INFOKOM Kota Bogor menjadi penghubung antara kompetensi lulusan
                    dengan kebutuhan dunia kerja melalui informasi lowongan, persiapan karier,
                    hubungan industri, dan proses penyaluran kerja.
                </p>
                <div class="bkk-about-line"><span></span></div>
            </div>
        </div>
    </section>

    <section class="section bkk-services">
        <div class="container">
            <div class="bkk-section-heading reveal">
                <div>
                    <p class="bkk-kicker"><span></span>03 / SERVICES</p>
                    <h2>Layanan BKK.</h2>
                </div>
                <p>Support yang membantu siswa dan lulusan melangkah lebih siap menuju dunia kerja.</p>
            </div>

            <div class="bkk-service-list">
                <article class="bkk-service reveal">
                    <span>01</span>
                    <div><h3>Informasi Lowongan</h3><p>Temukan peluang kerja yang relevan dengan kompetensi dan minat.</p></div>
                    <b>↗</b>
                </article>
                <article class="bkk-service reveal">
                    <span>02</span>
                    <div><h3>Persiapan Karier</h3><p>Bekal untuk menghadapi proses seleksi dan budaya kerja profesional.</p></div>
                    <b>↗</b>
                </article>
                <article class="bkk-service reveal">
                    <span>03</span>
                    <div><h3>Penyaluran Kerja</h3><p>Menghubungkan lulusan dengan kesempatan kerja dari mitra BKK.</p></div>
                    <b>↗</b>
                </article>
                <article class="bkk-service reveal">
                    <span>04</span>
                    <div><h3>Kerja Sama Industri</h3><p>Membangun relasi dengan dunia industri untuk membuka peluang baru.</p></div>
                    <b>↗</b>
                </article>
            </div>
        </div>
    </section>

    <section class="section bkk-jobs" id="lowongan">
        <div class="container">
            <div class="bkk-jobs-heading reveal">
                <div>
                    <p class="bkk-kicker"><span></span>04 / JOB VACANCIES</p>
                    <h2>LOWONGAN<br><em>TERBARU</em></h2>
                    <p>Temukan peluang karier dari perusahaan mitra BKK SMK INFOKOM Kota Bogor.</p>
                </div>
                <div class="bkk-job-word" aria-hidden="true">JOB</div>
            </div>

            <div class="bkk-job-tools reveal">
                <label class="bkk-search">
                    <span>⌕</span>
                    <input id="bkkJobSearch" type="search" placeholder="Cari posisi, perusahaan, atau lokasi..." autocomplete="off">
                    <button type="button" aria-label="Cari">→</button>
                </label>
                <div class="bkk-filters" role="tablist" aria-label="Filter kategori lowongan">
                    <button class="is-active" type="button" data-job-filter="all">Semua</button>
                    <button type="button" data-job-filter="Teknologi">Teknologi</button>
                    <button type="button" data-job-filter="Administrasi">Administrasi</button>
                    <button type="button" data-job-filter="Kreatif">Kreatif</button>
                    <button type="button" data-job-filter="Marketing">Marketing</button>
                    <button type="button" data-job-filter="Lainnya">Lainnya</button>
                </div>
            </div>

<?php vb_dyn_open('jobs', 'div'); ?>
            <?php if ($jobs): ?>
                <div class="bkk-job-grid" id="bkkJobGrid">
                    <?php foreach ($jobs as $index => $job): ?>
                        <?php
                        $category = bkk_category((string) $job['position']);
                        $jobPayload = [
                            'company' => (string) $job['company'],
                            'position' => (string) $job['position'],
                            'location' => (string) ($job['location'] ?? 'Lokasi belum ditentukan'),
                            'description' => (string) ($job['description'] ?? ''),
                            'deadline' => $job['deadline'] ? date('d M Y', strtotime($job['deadline'])) : 'Terbuka',
                            'apply_url' => (string) ($job['apply_url'] ?? ''),
                            'category' => $category,
                        ];
                        ?>
                        <article
                            class="bkk-job-card reveal"
                            data-job-card
                            data-category="<?= e($category) ?>"
                            data-search="<?= e(strtolower($job['company'] . ' ' . $job['position'] . ' ' . ($job['location'] ?? '') . ' ' . $category)) ?>"
                            data-job="<?= e(json_encode($jobPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?>"
                        >
                            <div class="bkk-job-card-top">
                                <span class="bkk-job-number">0<?= $index + 1 ?></span>
                                <span class="bkk-job-status">OPEN</span>
                            </div>
                            <div class="bkk-company-mark"><?= e(bkk_initials((string) $job['company'])) ?></div>
                            <p class="bkk-job-company"><?= e($job['company']) ?></p>
                            <h3><?= e($job['position']) ?></h3>
                            <div class="bkk-job-meta">
                                <span>⌖ <?= e($job['location'] ?: 'Lokasi belum ditentukan') ?></span>
                                <span>◷ Full Time</span>
                            </div>
                            <p class="bkk-job-description">
                                <?= e(mb_strimwidth((string) ($job['description'] ?? ''), 0, 150, '…', 'UTF-8')) ?>
                            </p>
                            <div class="bkk-job-footer">
                                <div><small>DEADLINE</small><strong><?= e($job['deadline'] ? date('d M Y', strtotime($job['deadline'])) : 'Terbuka') ?></strong></div>
                                <button type="button" class="bkk-detail-btn" data-job-open>Lihat Detail <span>→</span></button>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div class="bkk-job-empty" id="bkkJobEmpty" hidden>
                    <strong>Lowongan tidak ditemukan.</strong>
                    <span>Coba kata kunci atau kategori yang berbeda.</span>
                </div>
            <?php else: ?>
                <div class="bkk-job-empty bkk-job-empty-visible">
                    <strong>Belum ada lowongan aktif.</strong>
                    <span>Lowongan yang dipublikasikan melalui Admin BKK akan tampil di sini.</span>
                </div>
            <?php endif; ?>
<?php vb_dyn_close(); ?>
        </div>
    </section>

    <section class="section bkk-process">
        <div class="container">
            <div class="bkk-section-heading reveal">
                <div>
                    <p class="bkk-kicker"><span></span>05 / HOW IT WORKS</p>
                    <h2>Dari sekolah menuju <em>dunia kerja.</em></h2>
                </div>
            </div>

            <div class="bkk-process-track">
                <span class="bkk-process-line" aria-hidden="true"></span>
                <article class="bkk-process-step reveal"><span>01</span><h3>Daftar</h3><p>Temukan lowongan yang sesuai.</p></article>
                <article class="bkk-process-step reveal"><span>02</span><h3>Verifikasi</h3><p>Siapkan data dan dokumen.</p></article>
                <article class="bkk-process-step reveal"><span>03</span><h3>Seleksi</h3><p>Ikuti tahapan dari perusahaan.</p></article>
                <article class="bkk-process-step reveal"><span>04</span><h3>Interview</h3><p>Tunjukkan kompetensi terbaikmu.</p></article>
                <article class="bkk-process-step reveal"><span>05</span><h3>Penempatan</h3><p>Mulai perjalanan karier.</p></article>
            </div>
        </div>
    </section>

    <section class="bkk-cta">
        <div class="bkk-cta-word" aria-hidden="true">CAREER</div>
        <div class="container bkk-cta-inner reveal">
            <p class="bkk-kicker"><span></span>06 / START YOUR CAREER</p>
            <h2>SIAP MEMULAI<br><em>LANGKAH KARIERMU?</em></h2>
            <p>Temukan peluang dan mulai perjalanan menuju dunia kerja bersama BKK.</p>
            <a class="bkk-btn bkk-btn-primary" href="#lowongan"><span>Lihat Lowongan</span><strong>↗</strong></a>
        </div>
    </section>
</main>

<div class="bkk-job-modal" id="bkkJobModal" aria-hidden="true">
    <div class="bkk-modal-backdrop" data-job-close></div>
    <div class="bkk-modal-panel" role="dialog" aria-modal="true" aria-labelledby="bkkModalTitle">
        <button class="bkk-modal-close" type="button" data-job-close aria-label="Tutup">×</button>
        <p class="bkk-kicker"><span></span>JOB DETAIL</p>
        <span class="bkk-modal-category" id="bkkModalCategory"></span>
        <h2 id="bkkModalTitle">—</h2>
        <p class="bkk-modal-company" id="bkkModalCompany">—</p>
        <div class="bkk-modal-meta">
            <span id="bkkModalLocation">—</span>
            <span id="bkkModalDeadline">—</span>
        </div>
        <div class="bkk-modal-description" id="bkkModalDescription">—</div>
        <a class="bkk-btn bkk-btn-primary" id="bkkModalApply" href="#" target="_blank" rel="noopener noreferrer">
            <span>Lamar Sekarang</span><strong>↗</strong>
        </a>
    </div>
</div>

<?php vb_end(); ?>
<?php require __DIR__ . '/includes/public-footer.php'; ?>
