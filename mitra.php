<?php

declare(strict_types=1);

$pageTitle = 'Mitra Industri';

require_once __DIR__ . '/includes/public-header.php';

// Public directory: display every published partner. Never insert seed rows on page load.
$mitra = [];
$featuredMitra = [];
try {
    $columns = $pdo->query("SHOW COLUMNS FROM bkk_partners LIKE 'is_featured'")->fetchAll();
    $hasFeatured = !empty($columns);
    $select = $hasFeatured ? 'SELECT *' : 'SELECT *, 0 AS is_featured';
    $mitra = $pdo->query("{$select} FROM bkk_partners WHERE LOWER(TRIM(status)) = 'published' ORDER BY sort_order ASC, id ASC")->fetchAll();
    $featuredMitra = array_values(array_filter($mitra, static fn(array $partner): bool => (int)($partner['is_featured'] ?? 0) === 1));
} catch (Throwable $exception) {
    error_log('Mitra page query failed: ' . $exception->getMessage());
}
// Summary/collage records are showcase panels, not individual partner cards.
$isShowcasePartner = static function (array $partner): bool {
    $name = mb_strtolower(trim((string)($partner['name'] ?? '')), 'UTF-8');
    $category = mb_strtolower(trim((string)($partner['category'] ?? '')), 'UTF-8');

    // Only the two dedicated collage/summary records belong in the showcase.
    // Do not classify individual institutions such as "Mitra Universitas Bogor"
    // as a showcase just because their names contain "mitra" or "universitas".
    if (str_contains($category, 'rangkuman mitra')) {
        return true;
    }
    $isIndustrySummary = str_contains($name, 'mitra industri')
        && (str_contains($name, 'smk infokom') || str_contains($name, 'semua mitra') || str_contains($name, 'rangkuman'));
    $isEducationSummary = str_contains($name, 'mitra')
        && str_contains($name, 'universitas')
        && str_contains($name, 'pendidikan');

    return $isIndustrySummary || $isEducationSummary;
};
$showcaseMitra = array_values(array_filter($mitra, $isShowcasePartner));
$directoryMitra = array_values(array_filter($mitra, static fn(array $partner): bool => !$isShowcasePartner($partner)));
$featuredMitra = array_values(array_filter($directoryMitra, static fn(array $partner): bool => (int)($partner['is_featured'] ?? 0) === 1));
$featuredIds = array_fill_keys(array_map(static fn(array $partner): string => (string)$partner['id'], $featuredMitra), true);
$regularMitra = array_values(array_filter($directoryMitra, static fn(array $partner): bool => !isset($featuredIds[(string)$partner['id']])));
$websiteUrl = static function ($value): string {
    $value = trim((string)$value);
    if ($value === '') return '';
    if (!preg_match('~^https?://~i', $value)) $value = 'https://' . $value;
    return filter_var($value, FILTER_VALIDATE_URL) && in_array(strtolower((string)parse_url($value, PHP_URL_SCHEME)), ['http','https'], true) ? $value : '';
};
$renderPartnerCard = static function (array $partner, int $index, bool $featured = false) use ($websiteUrl): void {
    $name = trim((string)($partner['name'] ?? 'Mitra Industri')) ?: 'Mitra Industri';
    $category = trim((string)($partner['category'] ?? '')) ?: 'Mitra Industri';
    $logoPath = trim((string)($partner['logo'] ?? ''));
    $logo = $logoPath !== '' ? img_url($logoPath) : '';
    $website = $websiteUrl($partner['website'] ?? '');
    ?>
    <article class="mitra-card<?= $featured ? ' mitra-card-featured' : '' ?> reveal" data-category="<?= e($category) ?>" data-name="<?= e($name) ?>">
        <div class="mitra-card-corner" aria-hidden="true"></div>
        <div class="mitra-card-top"><span><?= e(str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT)) ?> / <?= $featured ? 'UNGGULAN' : 'MITRA' ?></span><i aria-hidden="true">↗</i></div>
        <?php if ($featured): ?><span class="mitra-featured-badge"><span>★</span> MITRA UNGGULAN</span><?php endif; ?>
        <div class="mitra-card-stage">
            <span class="mitra-card-watermark" aria-hidden="true"><?= e(str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
            <div class="mitra-logo-stage">
                <?php if ($logo !== ''): ?><img src="<?= e($logo) ?>" alt="Logo <?= e($name) ?>" loading="lazy" onerror="this.hidden=true;this.nextElementSibling.hidden=false"><?php endif; ?>
                <div class="mitra-logo-placeholder" <?= $logo !== '' ? 'hidden' : '' ?> aria-label="Logo belum tersedia"><span><?= e(mb_strtoupper(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8')) ?></span><small>LOGO MITRA</small></div>
            </div>
        </div>
        <div class="mitra-card-bottom">
            <div class="mitra-card-copy"><h3><?= e($name) ?></h3><p><?= e($category) ?></p></div>
            <?php if ($website !== ''): ?><a class="mitra-visit-btn" href="<?= e($website) ?>" target="_blank" rel="noopener noreferrer" aria-label="Kunjungi situs <?= e($name) ?>">KUNJUNGI SITUS <span aria-hidden="true">↗</span></a><?php else: ?><span class="mitra-no-website">SITUS BELUM TERSEDIA</span><?php endif; ?>
        </div>
        <div class="mitra-card-progress"><span></span></div>
    </article>
    <?php
};

vb_begin('mitra');
?>

<main class="mitra-page">
    <section class="mitra-hero">
        <div class="mitra-grid" aria-hidden="true"></div>
        <div class="mitra-hero-glow mitra-glow-blue" aria-hidden="true"></div>
        <div class="mitra-hero-glow mitra-glow-yellow" aria-hidden="true"></div>
        <div class="mitra-hero-word" aria-hidden="true">NETWORK</div>

        <div class="container mitra-hero-inner">
            <div class="mitra-hero-copy reveal">
                <p class="mitra-kicker"><span></span>01 / JEJARING INDUSTRI</p>
                <h1><?= e(cms_text('mitra', 'hero_title', 'MITRA INDUSTRI')) ?></h1>
                <p class="mitra-hero-text">
                    Membangun koneksi antara lingkungan pendidikan, dunia industri,
                    dan peluang karier untuk mendukung kesiapan siswa menghadapi dunia kerja.
                </p>
                <div class="mitra-hero-actions">
                    <a class="mitra-btn mitra-btn-primary" href="#partners">
                        <span>Kenali Mitra Kami</span><strong>↘</strong>
                    </a>
                    <span class="mitra-live"><i></i> JEJARING TERHUBUNG</span>
                </div>
            </div>

            <div class="mitra-network-visual reveal" aria-label="Diagram koneksi sekolah, BKK, industri dan karier">
                <div class="mitra-network-orbit orbit-one"></div>
                <div class="mitra-network-orbit orbit-two"></div>
                <div class="mitra-network-orbit orbit-three"></div>
                <div class="mitra-network-line line-school"></div>
                <div class="mitra-network-line line-career"></div>
                <div class="mitra-network-line line-industry"></div>
                <span class="mitra-network-dot dot-a"></span>
                <span class="mitra-network-dot dot-b"></span>
                <span class="mitra-network-dot dot-c"></span>

                <div class="mitra-node node-school">
                    <small>01</small><strong>SEKOLAH</strong>
                </div>
                <div class="mitra-node node-bkk">
                    <small>02</small><strong>BKK</strong>
                </div>
                <div class="mitra-node node-industry">
                    <small>03</small><strong>INDUSTRI</strong>
                </div>
                <div class="mitra-node node-career">
                    <small>04</small><strong>KARIER</strong>
                </div>
                <div class="mitra-network-core">
                    <span>INFOKOM</span>
                    <strong>CONNECT</strong>
                    <i></i>
                </div>
            </div>
        </div>

        <div class="mitra-hero-bottom container reveal">
            <span>SMK INFOKOM KOTA BOGOR</span>
            <span class="mitra-scroll-line"></span>
            <a href="#network">GULIR ↓</a>
        </div>
    </section>

    <section class="mitra-intro" id="network">
        <div class="container mitra-intro-grid">
            <div class="mitra-section-label reveal"><strong>02</strong><span>JEJARING KAMI</span></div>
            <div class="mitra-intro-copy reveal">
                <p class="mitra-kicker mitra-kicker-dark"><span></span>KOLABORASI</p>
                <h2><?= e(cms_text('mitra', 'section_1_title', 'MEMBANGUN HUBUNGAN DENGAN INDUSTRI.')) ?></h2>
                <p>Jejaring mitra mempertemukan pendidikan vokasi dengan dunia kerja dan membuka peluang kolaborasi yang relevan bagi peserta didik.</p>
                <div class="mitra-rule"><span></span></div>
            </div>
        </div>
    </section>

    <?php if ($showcaseMitra): ?>
    <section class="mitra-showcase-section" aria-labelledby="mitraShowcaseTitle">
        <div class="container">
            <div class="mitra-showcase-heading"><p class="mitra-kicker"><span></span>JEJARING UTAMA</p><h2 id="mitraShowcaseTitle">Kolaborasi <em>SMK Infokom.</em></h2><p>Ringkasan jejaring mitra sekolah dan mitra pendidikan ditampilkan terpisah dari direktori setiap perusahaan atau institusi.</p></div>
            <?php vb_dyn_open('mitra-showcase-cards', 'div', 'class="mitra-showcase-grid"'); ?>
                <?php foreach ($showcaseMitra as $index => $partner):
                    $showcaseName = trim((string)($partner['name'] ?? 'Jejaring Mitra')) ?: 'Jejaring Mitra';
                    $showcaseCategory = trim((string)($partner['category'] ?? ''));
                    $showcaseLogo = trim((string)($partner['logo'] ?? ''));
                    $isEducation = str_contains(mb_strtolower($showcaseName, 'UTF-8'), 'pendidikan') || str_contains(mb_strtolower($showcaseName, 'UTF-8'), 'universitas') || str_contains(mb_strtolower($showcaseCategory, 'UTF-8'), 'pendidikan');
                ?>
                    <article class="mitra-showcase-card reveal">
                        <div class="mitra-showcase-index">0<?= $index + 1 ?> / <?= $isEducation ? 'PENDIDIKAN' : 'JEJARING INFOKOM' ?></div>
                        <div class="mitra-showcase-image">
                            <?php if ($showcaseLogo !== ''): ?><img src="<?= e(img_url($showcaseLogo)) ?>" alt="<?= e($showcaseName) ?>" loading="lazy"><?php else: ?><div class="mitra-showcase-placeholder"><?= e($showcaseName) ?></div><?php endif; ?>
                        </div>
                        <div class="mitra-showcase-caption"><h3><?= e($showcaseName) ?></h3><?php if ($showcaseCategory !== ''): ?><p><?= e($showcaseCategory) ?></p><?php endif; ?></div>
                    </article>
                <?php endforeach; ?>
            <?php vb_dyn_close(); ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if ($directoryMitra): ?>
    <section class="mitra-ticker-section" aria-label="Logo mitra individual aktif">
        <div class="container mitra-ticker-heading"><span class="mitra-ticker-dot"></span><p>JEJARING MITRA SMK INFOKOM</p><span><?= count($directoryMitra) ?> MITRA AKTIF</span></div>
        <div class="mitra-ticker" tabindex="0" aria-label="Running text logo mitra; dapat dijeda dengan fokus atau hover">
            <?php vb_dyn_open('mitra-ticker-items', 'div', 'class="mitra-ticker-track"'); ?>
                <?php for ($loop = 0; $loop < 2; $loop++): foreach ($directoryMitra as $partner): ?>
                    <div class="mitra-ticker-item">
                        <?php $tickerLogo = trim((string)($partner['logo'] ?? '')); ?>
                        <?php if ($tickerLogo !== ''): ?><img src="<?= e(img_url($tickerLogo)) ?>" alt="" loading="lazy" onerror="this.hidden=true"><?php else: ?><span class="mitra-ticker-fallback"><?= e(mb_strtoupper(mb_substr((string)$partner['name'], 0, 1, 'UTF-8'), 'UTF-8')) ?></span><?php endif; ?>
                        <span><?= e($partner['name']) ?></span>
                    </div>
                <?php endforeach; endfor; ?>
            <?php vb_dyn_close(); ?>
        </div>
    </section>
    <?php endif; ?>

    <section class="mitra-partners" id="partners">
        <div class="mitra-bg-number" aria-hidden="true">03</div>
        <div class="container">
            <div class="mitra-heading reveal">
                <div><p class="mitra-kicker mitra-kicker-dark"><span></span>03 / DIREKTORI MITRA</p><h2>MITRA<br><em>KAMI.</em></h2></div>
                <p class="mitra-heading-note">Jelajahi jejaring mitra SMK INFOKOM. Gunakan pencarian atau filter kategori untuk menemukan mitra yang kamu cari.</p>
            </div>

            <?php if ($featuredMitra): ?>
            <section class="mitra-featured-section" aria-labelledby="mitraFeaturedTitle">
                <div class="mitra-subheading"><div><p class="mitra-kicker mitra-kicker-dark"><span></span>PILIHAN KAMI</p><h3 id="mitraFeaturedTitle">Mitra <em>Unggulan.</em></h3></div><p>Seluruh mitra yang ditandai unggulan dan berstatus Published ditampilkan di sini.</p></div>
                <?php vb_dyn_open('mitra-featured-cards', 'div', 'class="mitra-card-grid mitra-featured-grid"'); ?>
                    <?php foreach ($featuredMitra as $index => $partner): $renderPartnerCard($partner, $index, true); endforeach; ?>
                <?php vb_dyn_close(); ?>
            </section>
            <?php endif; ?>

            <div class="mitra-directory-tools" role="search" aria-label="Cari dan filter mitra">
                <label class="mitra-search" for="mitraSearch">
                    <span class="mitra-tool-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.2 4.2"/></svg></span>
                    <span class="mitra-tool-field"><span class="mitra-tool-caption">PENCARIAN MITRA</span><input type="search" id="mitraSearch" placeholder="Cari nama mitra..." autocomplete="off"></span>
                    <span class="mitra-search-hint" aria-hidden="true">⌕</span>
                </label>
                <label class="mitra-filter-label" for="mitraCategory">
                    <span class="mitra-tool-icon mitra-filter-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><path d="M4 5h16l-6.2 7.1v5.2l-3.6 1.8v-7L4 5Z"/></svg></span>
                    <span class="mitra-tool-field"><span class="mitra-tool-caption">FILTER KATEGORI</span><select id="mitraCategory"><option value="">Semua kategori</option><?php $categories = array_values(array_unique(array_filter(array_map(static fn($item) => trim((string)($item['category'] ?? '')), $directoryMitra)))); sort($categories, SORT_NATURAL | SORT_FLAG_CASE); foreach ($categories as $category): ?><option value="<?= e(mb_strtolower($category, 'UTF-8')) ?>"><?= e($category) ?></option><?php endforeach; ?></select></span>
                    <svg class="mitra-select-chevron" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </label>
                <div class="mitra-result-count" aria-live="polite"><span class="mitra-count-icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none"><circle cx="9" cy="8" r="3.2"/><path d="M2.8 19v-1.5a5.8 5.8 0 0 1 11.6 0V19M16 5.3a3.2 3.2 0 0 1 0 6.2M17.2 14a4.8 4.8 0 0 1 4 4.7V19"/></svg></span><span><strong id="mitraResultCount"><?= count($regularMitra) ?></strong><small>mitra ditampilkan</small></span></div>
            </div>

            <?php vb_dyn_open('mitra-directory-cards', 'div', 'class="mitra-card-grid" id="mitraDirectory"'); ?>
                <?php foreach ($regularMitra as $index => $partner): $renderPartnerCard($partner, $index); endforeach; ?>
            <?php vb_dyn_close(); ?>
            <p class="mitra-empty-state" id="mitraEmptyState" hidden>Belum ada mitra yang sesuai dengan pencarian ini.</p>
            <?php if (!$directoryMitra): ?><div class="mitra-empty-state">Belum ada mitra individual berstatus Published.</div><?php endif; ?>
        </div>
    </section>

    <section class="mitra-connected">
        <div class="mitra-connected-grid" aria-hidden="true"></div>
        <div class="container">
            <div class="mitra-connected-head reveal">
                <p class="mitra-kicker"><span></span>04 / JEJARING INDUSTRI</p>
                <h2>DARI SEKOLAH<br><em>MENUJU INDUSTRI.</em></h2>
            </div>

            <div class="mitra-flow reveal">
                <div class="mitra-flow-line"></div>
                <div class="mitra-flow-item"><span>01</span><strong>SEKOLAH</strong><small>Learn</small></div>
                <div class="mitra-flow-item active"><span>02</span><strong>BKK</strong><small>Connect</small></div>
                <div class="mitra-flow-item"><span>03</span><strong>INDUSTRI</strong><small>Collaborate</small></div>
                <div class="mitra-flow-item"><span>04</span><strong>KARIER</strong><small>Grow</small></div>
            </div>

            <div class="mitra-connected-tags reveal">
                <span>PENDIDIKAN</span><span>KOLABORASI</span><span>PELUANG</span><span>KARIER</span>
            </div>
        </div>
    </section>

    <section class="mitra-cta">
        <div class="mitra-cta-word" aria-hidden="true">CONNECT</div>
        <div class="container mitra-cta-inner reveal">
            <p class="mitra-kicker"><span></span>05 / KOLABORASI</p>
            <h2>TERBUKA UNTUK<br><em>KOLABORASI INDUSTRI.</em></h2>
            <p>Membangun hubungan yang relevan antara pendidikan vokasi dan dunia industri.</p>
            <a class="mitra-btn mitra-btn-primary" href="<?= e(url('kontak.php')) ?>">
                <span>Hubungi Kami</span><strong>↗</strong>
            </a>
        </div>
    </section>
</main>

<script>
(() => {
    const search = document.getElementById('mitraSearch');
    const category = document.getElementById('mitraCategory');
    const directory = document.getElementById('mitraDirectory');
    const count = document.getElementById('mitraResultCount');
    const empty = document.getElementById('mitraEmptyState');
    if (!directory) return;
    const cards = Array.from(directory.querySelectorAll('.mitra-card'));
    const normalize = (value) => (value || '').toLocaleLowerCase('id-ID').normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    const filterCards = () => {
        const term = normalize(search?.value.trim());
        const selected = normalize(category?.value).trim();
        let visible = 0;
        cards.forEach((card) => {
            const matchesName = normalize(card.dataset.name).includes(term);
            const matchesCategory = !selected || normalize(card.dataset.category).trim() === selected;
            const show = matchesName && matchesCategory;
            card.hidden = !show;
            if (show) visible++;
        });
        if (count) count.textContent = visible;
        if (empty) empty.hidden = visible !== 0;
    };
    search?.addEventListener('input', filterCards);
    category?.addEventListener('change', filterCards);
    const revealItems = document.querySelectorAll('.mitra-page .reveal');
    if ('IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        const observer = new IntersectionObserver((entries) => entries.forEach((entry) => {
            if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); }
        }), { threshold: 0.12 });
        revealItems.forEach((item) => observer.observe(item));
    } else revealItems.forEach((item) => item.classList.add('is-visible'));
})();
</script>

<?php vb_end(); ?>
<?php require __DIR__ . '/includes/public-footer.php'; ?>
