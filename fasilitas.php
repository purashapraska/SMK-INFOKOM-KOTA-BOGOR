<?php

declare(strict_types=1);

$pageTitle = 'Fasilitas';
require_once __DIR__ . '/includes/public-header.php';

// Visual Builder integration: keep the original PHP page as the fallback.
vb_begin('fasilitas');
ensure_facility_schema();

$featured = $pdo->query("SELECT * FROM facilities WHERE status='published' ORDER BY is_featured DESC, id DESC LIMIT 1")->fetch() ?: null;
$rows = $pdo->query("SELECT * FROM facilities WHERE status='published' ORDER BY is_featured DESC, id DESC")->fetchAll();
$galleryByFacility = [];
if (table_exists('facility_images')) {
    $images = $pdo->query('SELECT * FROM facility_images ORDER BY facility_id, sort_order, id')->fetchAll();
    foreach ($images as $image) $galleryByFacility[(int)$image['facility_id']][] = $image['image'];
}
$categories = [];
foreach ($rows as $r) { $categories[$r['category'] ?: 'Fasilitas Umum'] = true; }
?>
<section class="facility-hero">
    <div class="facility-hero-grid"></div>
    <div class="container facility-hero-inner">
        <div class="facility-hero-copy reveal">
            <p class="eyebrow facility-eyebrow"><span>01</span> / FACILITIES</p>
            <h1><?= e(cms_text('fasilitas', 'hero_title', 'Fasilitas untuk tumbuh, belajar & berkarya.')) ?></h1>
            <p class="facility-hero-lead">Ruang belajar, praktik, olahraga, dan fasilitas pendukung yang dirancang untuk menemani perjalanan siswa SMK INFOKOM Kota Bogor.</p>
            <div class="facility-hero-actions">
                <a class="facility-hero-button" href="#facility-explore"><span>Jelajahi Fasilitas</span><b>↓</b></a>
                <span class="facility-hero-note">RUANG • TEKNOLOGI • PENGALAMAN</span>
            </div>
        </div>
        <div class="facility-hero-visual reveal" style="--facility-delay:120ms">
            <div class="facility-hero-design" aria-hidden="true">
                <div class="facility-design-number">01</div>
                <div class="facility-design-circle facility-design-circle-a"></div>
                <div class="facility-design-circle facility-design-circle-b"></div>
                <div class="facility-design-cross facility-cross-a"></div>
                <div class="facility-design-cross facility-cross-b"></div>
                <div class="facility-design-line facility-line-a"></div>
                <div class="facility-design-line facility-line-b"></div>
                <div class="facility-design-label facility-label-a"><span>SPACE</span><b>01</b></div>
                <div class="facility-design-label facility-label-b"><span>LEARN</span><b>02</b></div>
                <div class="facility-design-label facility-label-c"><span>CREATE</span><b>03</b></div>
                <div class="facility-design-center">
                    <?php if (setting('facility_hero_logo', '')): ?>
                        <div class="facility-hero-logo-wrap">
                            <span class="facility-hero-logo-ring"></span>
                            <img class="facility-hero-logo" src="<?= e(img_url(setting('facility_hero_logo'))) ?>" alt="Logo SMK INFOKOM Kota Bogor">
                        </div>
                    <?php else: ?>
                        <div class="facility-design-fallback-logo"><span>SMK</span><strong>INFOKOM</strong><small>KOTA BOGOR</small></div>
                    <?php endif; ?>
                </div>
                <div class="facility-design-accent"></div>
            </div>
        </div>
    </div>
    <div class="facility-scroll-note"><span></span> SCROLL TO EXPLORE</div>
</section>

<section class="facility-intro section" id="facility-explore">
    <div class="container">
        <div class="facility-section-head reveal">
            <div><p class="eyebrow"><span>02</span> / EXPLORE</p><h2>Setiap ruang punya<br><em>ceritanya sendiri.</em></h2></div>
            <p>Temukan fasilitas berdasarkan kategori. Klik kartu untuk melihat detail dan galeri foto.</p>
        </div>
        <div class="facility-toolbar reveal">
            <div class="facility-filters"><button class="facility-filter active" data-facility-filter="all">Semua</button><?php foreach(array_keys($categories) as $cat): ?><button class="facility-filter" data-facility-filter="<?=e(safe_slug($cat))?>"><?=e($cat)?></button><?php endforeach; ?></div>
        </div>
        <div class="facility-masonry" id="facilityGrid">
            <?php foreach($rows as $i=>$r): $fid=(int)$r['id']; $cat=$r['category'] ?: 'Fasilitas Umum'; $gallery=$galleryByFacility[$fid]??[]; $payload=['id'=>$fid,'name'=>$r['name'],'category'=>$cat,'description'=>$r['description']??'','location'=>$r['location']??'','capacity'=>$r['capacity']??'','image'=>img_url($r['image']),'gallery'=>array_map(fn($p)=>img_url($p), $gallery)]; ?>
            <article class="facility-showcase-card reveal" data-facility-card data-category="<?=e(safe_slug($cat))?>" style="--facility-delay:<?=($i%6)*80?>ms">
                <button type="button" class="facility-card-trigger" data-facility-open='<?=e(json_encode($payload, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE))?>'>
                    <span class="facility-card-image"><img loading="lazy" src="<?=e(img_url($r['image']))?>" alt="<?=e($r['name'])?>"><span class="facility-card-shade"></span><span class="facility-card-number"><?=str_pad((string)($i+1),2,'0',STR_PAD_LEFT)?></span><span class="facility-card-view">VIEW <b>↗</b></span></span>
                    <span class="facility-card-copy"><small><?=e($cat)?></small><strong><?=e($r['name'])?></strong><em><?=e(mb_substr((string)($r['description']??''),0,120))?></em><span class="facility-card-link">Lihat detail <b>→</b></span></span>
                </button>
            </article>
            <?php endforeach; ?>
        </div>
        <?php if(!$rows): ?><div class="facility-no-data reveal"><span>FACILITIES / 00</span><h3>Belum ada fasilitas yang dipublikasikan.</h3><p>Tambahkan fasilitas dari dashboard admin untuk menampilkannya di halaman ini.</p></div><?php endif; ?>
    </div>
</section>

<section class="facility-closing">
    <div class="container facility-closing-inner reveal"><p class="eyebrow"><span>03</span> / SCHOOL EXPERIENCE</p><h2>Ruang yang baik<br>membuka <em>lebih banyak kemungkinan.</em></h2><p>Gunakan fasilitas sekolah sebagai ruang untuk belajar, mencoba, berkolaborasi, dan menghasilkan karya.</p><a class="facility-hero-button facility-hero-button-light" href="#facility-explore"><span><?= e(cms_text('fasilitas', 'button_label', 'Lihat Semua Fasilitas')) ?></span><b>↗</b></a></div>
</section>

<div class="facility-modal" id="facilityModal" aria-hidden="true">
    <div class="facility-modal-backdrop" data-facility-close></div>
    <div class="facility-modal-panel" role="dialog" aria-modal="true" aria-labelledby="facilityModalTitle">
        <button class="facility-modal-close" type="button" data-facility-close aria-label="Tutup">×</button>
        <div class="facility-modal-media"><img id="facilityModalImage" src="" alt=""><div class="facility-modal-count" id="facilityModalCount">01 / 01</div><button class="facility-gallery-nav prev" id="facilityPrev" type="button">←</button><button class="facility-gallery-nav next" id="facilityNext" type="button">→</button></div>
        <div class="facility-modal-content"><p class="eyebrow" id="facilityModalCategory"></p><h2 id="facilityModalTitle"></h2><p id="facilityModalDescription"></p><div class="facility-modal-meta"><div><span>LOKASI</span><strong id="facilityModalLocation">—</strong></div><div><span>KAPASITAS</span><strong id="facilityModalCapacity">—</strong></div></div></div>
    </div>
</div>
<?php vb_end(); ?>

<?php require __DIR__ . '/includes/public-footer.php'; ?>
