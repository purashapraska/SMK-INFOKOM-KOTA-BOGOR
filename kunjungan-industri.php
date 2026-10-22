<?php
declare(strict_types=1);
$pageTitle = 'Kunjungan Industri';
require_once __DIR__ . '/includes/public-header.php';

// Visual Builder integration: keep the original PHP page as the fallback.
vb_begin('kunjungan-industri');
require_once __DIR__ . '/includes/industrial-visits-data.php';
ensure_industrial_visit_schema();
$stmt = $pdo->query("SELECT * FROM industrial_visits WHERE id=1 AND status='published' LIMIT 1");
$visit = $stmt->fetch(PDO::FETCH_ASSOC);
/**
 * Resolve a stored Kunjungan Industri image to a real public file.
 * Older rows may accidentally contain an admin-page URL instead of a filename.
 * In that case, use a real image from uploads/industrial-visits rather than
 * rendering the admin PHP page as an <img> source.
 */
function visit_img(?string $path, string $fallback = '', int $slot = 0, bool $recover = true): string {
    $projectRoot = __DIR__;
    $uploadDir = $projectRoot . '/uploads/industrial-visits';
    $baseUrl = rtrim((string) BASE_URL, '/');
    $value = trim((string) ($path ?: $fallback));
    $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif', 'svg'];

    $isImageFile = static function (string $file) use ($extensions): bool {
        return is_file($file) && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), $extensions, true);
    };

    if ($value !== '') {
        $value = str_replace('\\', '/', $value);
        // A URL can be kept only if it points to an image, not an admin PHP page.
        if (preg_match('~^https?://~i', $value)) {
            $urlPath = (string) parse_url($value, PHP_URL_PATH);
            $extension = strtolower(pathinfo($urlPath, PATHINFO_EXTENSION));
            if (in_array($extension, $extensions, true)) {
                // For this project's own localhost URLs, verify the local file exists.
                $host = strtolower((string) parse_url($value, PHP_URL_HOST));
                if (!in_array($host, ['localhost', '127.0.0.1'], true)) return $value;
                $relative = ltrim($urlPath, '/');
                $prefix = ltrim($baseUrl, '/') . '/';
                if (str_starts_with($relative, $prefix)) $relative = substr($relative, strlen($prefix));
                $local = $projectRoot . '/' . $relative;
                if ($isImageFile($local)) return $baseUrl . '/' . ltrim($relative, '/');
            }
        } else {
            $base = rtrim($baseUrl, '/');
            if ($base !== '' && str_starts_with($value, $base . '/')) $value = substr($value, strlen($base) + 1);
            $value = preg_replace('~^(?:\./|(?:\.\./)+)+~', '', $value) ?? $value;
            $value = ltrim($value, '/');
            // Treat bare filenames as belonging to the industrial-visits upload directory.
            $candidates = [$projectRoot . '/' . $value];
            if (!str_contains($value, '/')) $candidates[] = $uploadDir . '/' . $value;
            foreach ($candidates as $candidate) {
                if ($isImageFile($candidate)) {
                    $relative = str_replace('\\', '/', substr($candidate, strlen($projectRoot) + 1));
                    return $baseUrl . '/' . ltrim($relative, '/');
                }
            }
        }
    }

    // Recover gracefully when the DB contains an admin URL or stale path.
    // (Not used for the logo: a missing logo must never be replaced by a random photo.)
    if (!$recover) return '';
    $files = [];
    if (is_dir($uploadDir)) {
        foreach (glob($uploadDir . '/*') ?: [] as $file) if ($isImageFile($file)) $files[] = $file;
    }
    usort($files, static fn(string $a, string $b): int => (filemtime($b) <=> filemtime($a)) ?: strcmp($b, $a));
    if ($files) {
        $chosen = $files[min(max($slot, 0), count($files) - 1)];
        $relative = str_replace('\\', '/', substr($chosen, strlen($projectRoot) + 1));
        return $baseUrl . '/' . ltrim($relative, '/');
    }
    return '';
}
?>
<style>
.ki-page{--ki-navy:#071426;--ki-blue:#142b49;--ki-yellow:#facc15;background:#071426;color:#f8fafc;overflow:hidden}
.ki-page *{box-sizing:border-box}.ki-container{width:min(1160px,calc(100% - 40px));margin-inline:auto}
.ki-hero{min-height:clamp(760px,100svh,980px);position:relative;display:flex;align-items:center;background:radial-gradient(ellipse at 75% 20%,#23466a 0,transparent 45%),linear-gradient(135deg,#132b48,#071426 72%);isolation:isolate}
.ki-hero-photo{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:center 42%;z-index:0;animation:ki-photo-in 1.4s cubic-bezier(.2,.7,.2,1) both;filter:saturate(1.08) contrast(1.03)}
.ki-hero:after{content:"";position:absolute;inset:0;z-index:1;background:linear-gradient(90deg,rgba(4,14,28,.88) 0%,rgba(4,14,28,.60) 42%,rgba(4,14,28,.08) 100%),linear-gradient(0deg,rgba(7,20,38,.72) 0%,transparent 42%)}
.ki-hero-grid{position:absolute;inset:0;z-index:2;opacity:.14;background-image:linear-gradient(rgba(255,255,255,.12) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.12) 1px,transparent 1px);background-size:64px 64px;mask-image:linear-gradient(90deg,#000,transparent)}
.ki-hero-content{animation:ki-content-in .85s .12s both;padding-top:clamp(170px,18vh,220px);padding-bottom:clamp(76px,9vh,112px);position:relative;z-index:3;max-width:850px}
.ki-eyebrow{display:inline-flex;align-items:center;gap:10px;border:1px solid rgba(250,204,21,.4);background:rgba(250,204,21,.08);color:#fde68a;border-radius:999px;padding:9px 14px;font-size:11px;letter-spacing:.16em;font-weight:800;text-transform:uppercase}
.ki-eyebrow:before{content:"";width:7px;height:7px;border-radius:50%;background:#facc15;box-shadow:0 0 14px #facc15}
.ki-hero h1{font-size:clamp(42px,7vw,84px);line-height:.99;letter-spacing:-.055em;margin:24px 0;color:#fff;max-width:850px;font-weight:850}
.ki-hero h1 span{color:#facc15}.ki-lead{font-size:clamp(16px,1.8vw,20px);line-height:1.8;color:#dbe6f3;max-width:680px;margin:0}
.ki-meta{display:flex;flex-wrap:wrap;gap:12px;margin-top:28px}.ki-meta span{display:inline-flex;align-items:center;gap:9px;padding:11px 14px;border-radius:12px;background:rgba(13,30,51,.7);border:1px solid rgba(255,255,255,.14);color:#f8fafc;font-size:13px;backdrop-filter:blur(12px)}
.ki-logo{display:inline-flex;align-items:center;gap:10px;margin-top:25px;color:#fff;font-weight:700}.ki-logo img{width:48px;height:48px;object-fit:contain;background:#fff;border-radius:12px;padding:5px;box-shadow:0 8px 22px rgba(0,0,0,.18)}
.ki-logo-missing{display:inline-flex;width:48px;height:48px;align-items:center;justify-content:center;background:rgba(255,255,255,.09);border:1px dashed rgba(255,255,255,.38);border-radius:12px;color:#facc15}.ki-scroll{display:inline-flex;align-items:center;gap:10px;color:#cbd5e1;text-transform:uppercase;letter-spacing:.14em;font-size:10px;margin-top:34px}.ki-scroll:before{content:"";width:34px;height:1px;background:#facc15}
.ki-section{padding:clamp(64px,8vw,110px) 0;position:relative}.ki-section-head{max-width:720px;margin-bottom:36px}.ki-kicker{font-size:11px;letter-spacing:.18em;color:#facc15;font-weight:800;text-transform:uppercase}.ki-section h2{font-size:clamp(30px,4vw,48px);letter-spacing:-.04em;line-height:1.08;margin:13px 0 16px;color:#fff}.ki-section p{color:#c4d0df;line-height:1.85}
.ki-story-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:clamp(28px,5vw,70px);align-items:center}
.ki-photo-frame{position:relative;border-radius:24px;overflow:hidden;min-height:320px;background:linear-gradient(135deg,#173454,#0b1728);border:1px solid rgba(255,255,255,.12);box-shadow:0 25px 70px rgba(0,0,0,.24)}
.ki-photo-frame img{display:block;width:100%;height:100%;min-height:320px;position:absolute;inset:0;object-fit:cover;transition:transform .8s cubic-bezier(.2,.7,.2,1)}
.ki-photo-frame img,.ki-bottom-frame img,.ki-hero-photo{image-rendering:auto}
.ki-hero-photo{display:block!important}
.ki-image-caption{position:absolute;left:14px;bottom:14px;z-index:4;padding:8px 12px;border-radius:999px;background:rgba(3,12,24,.62);border:1px solid rgba(255,255,255,.16);color:#fff;font-size:11px;backdrop-filter:blur(10px)}
.ki-photo-frame:hover img{transform:scale(1.035)}
.ki-placeholder{display:flex;min-height:320px;align-items:center;justify-content:center;flex-direction:column;gap:12px;color:#b6c7dc;text-align:center;padding:24px;background:radial-gradient(ellipse at center,rgba(96,165,250,.16),transparent 65%)}
.ki-placeholder svg{width:40px;height:40px;color:#facc15}
.ki-story-copy{padding-block:12px}.ki-story-copy p{font-size:16px}.ki-purpose{margin-top:26px;padding:20px 22px;border-left:3px solid #facc15;background:linear-gradient(90deg,rgba(250,204,21,.09),rgba(250,204,21,0));border-radius:0 14px 14px 0}
.ki-bottom{padding-top:15px}.ki-bottom-frame{height:clamp(280px,45vw,520px);border-radius:26px;overflow:hidden;position:relative;background:linear-gradient(135deg,#1b395a,#0b1728);border:1px solid rgba(255,255,255,.12)}
.ki-bottom-frame img{width:100%;height:100%;object-fit:cover;display:block;transition:transform 1s}.ki-bottom-frame:hover img{transform:scale(1.025)}
.ki-bottom-overlay{position:absolute;inset:0;background:linear-gradient(90deg,rgba(4,14,28,.88),rgba(4,14,28,.12)),linear-gradient(0deg,rgba(4,14,28,.55),transparent 55%);display:flex;align-items:flex-end;padding:clamp(24px,5vw,56px)}
.ki-bottom-overlay h2{max-width:650px;margin:0;color:#fff;font-size:clamp(28px,4.4vw,52px);line-height:1.08}
.ki-bottom-overlay p{max-width:600px;margin:15px 0 0;color:#e2e8f0}
.ki-detail-button{margin-top:24px;border:0;border-radius:999px;background:#facc15;color:#111827;padding:13px 19px;font-weight:800;cursor:pointer;transition:transform .25s,box-shadow .25s}.ki-detail-button:hover{transform:translateY(-2px);box-shadow:0 10px 28px rgba(250,204,21,.2)}
.ki-modal{position:fixed;inset:0;z-index:9999;background:rgba(2,8,18,.78);backdrop-filter:blur(12px);display:none;align-items:center;justify-content:center;padding:20px}.ki-modal.is-open{display:flex;animation:ki-fade .25s ease both}.ki-modal-dialog{width:min(760px,100%);max-height:min(86vh,900px);overflow:auto;background:#0c1b2e;border:1px solid rgba(255,255,255,.15);border-radius:22px;box-shadow:0 30px 100px rgba(0,0,0,.5);padding:clamp(22px,4vw,38px);animation:ki-modal-in .35s cubic-bezier(.2,.8,.2,1) both}.ki-modal-top{display:flex;justify-content:space-between;gap:20px;align-items:flex-start}.ki-modal h2{margin:12px 0;color:#fff;font-size:clamp(26px,4vw,38px)}.ki-modal p{color:#cbd5e1;line-height:1.8}.ki-modal-close{flex:0 0 auto;border:1px solid #34465d;background:#132943;color:#fff;border-radius:50%;width:42px;height:42px;font-size:23px;cursor:pointer}.ki-modal-meta{display:flex;flex-wrap:wrap;gap:10px;margin:18px 0}.ki-modal-meta span{padding:8px 11px;background:#142943;border-radius:10px;color:#e2e8f0;font-size:12px}
.ki-reveal{opacity:0;transform:translateY(22px);transition:opacity .75s ease,transform .75s cubic-bezier(.2,.7,.2,1)}.ki-reveal.is-visible{opacity:1;transform:translateY(0)}.ki-delay-1{transition-delay:.12s}.ki-delay-2{transition-delay:.22s}
@keyframes ki-photo-in{from{transform:scale(1.08);opacity:.5}to{transform:scale(1);opacity:1}}@keyframes ki-content-in{from{opacity:0;transform:translateY(18px)}to{opacity:1;transform:translateY(0)}}@keyframes ki-fade{from{opacity:0}to{opacity:1}}@keyframes ki-modal-in{from{opacity:0;transform:translateY(14px) scale(.97)}to{opacity:1;transform:translateY(0) scale(1)}}
@media(max-width:760px){.ki-hero{min-height:max(760px,100svh);align-items:center}.ki-hero-content{animation:ki-content-in .85s .12s both;padding-top:150px;padding-bottom:72px}.ki-hero:after{background:linear-gradient(0deg,#071426 0%,rgba(4,14,28,.78) 55%,rgba(4,14,28,.35) 100%)}.ki-story-grid{grid-template-columns:1fr}.ki-photo-frame,.ki-photo-frame img,.ki-placeholder{min-height:260px}.ki-container{width:min(100% - 30px,1160px)}.ki-meta span{font-size:12px}.ki-modal{padding:10px}.ki-modal-dialog{border-radius:18px}}
@media(prefers-reduced-motion:reduce){.ki-page *, .ki-page *:before,.ki-page *:after{animation:none!important;transition:none!important;scroll-behavior:auto!important}.ki-reveal{opacity:1;transform:none}}

/* Kunjungan Industri: paksa teks halaman ini terang, tanpa mengubah halaman lain. */
.ki-page,
.ki-page :is(p,span,h1,h2,h3,h4,h5,h6,a,strong,em,small,label,li,dt,dd,figcaption,blockquote){
  color:#f8fafc !important;
}
/* Pertahankan aksen kuning dan teks tombol yang memang sengaja gelap. */
.ki-page .ki-eyebrow,
.ki-page .ki-kicker,
.ki-page .ki-logo-missing,
.ki-page .ki-placeholder svg,
.ki-page .ki-hero h1 span{
  color:#facc15 !important;
}
.ki-page .ki-detail-button{
  color:#111827 !important;
}
</style>
<main class="ki-page">
<?php if ($visit): ?>
<section class="ki-hero" aria-labelledby="ki-title">
  <?php vb_dyn_open('ki-hero-photo', 'div', 'style="display:contents"'); $heroPhoto = visit_img($visit['hero_image'] ?? null, (string)($visit['middle_image'] ?? ($visit['bottom_image'] ?? '')), 0); if ($heroPhoto !== ''): ?><img class="ki-hero-photo" src="<?=e($heroPhoto)?>" alt="Dokumentasi hero kunjungan industri" fetchpriority="high"><?php endif; vb_dyn_close(); ?>
  <div class="ki-hero-grid" aria-hidden="true"></div>
  <div class="ki-container ki-hero-content">
    <div class="ki-eyebrow">Industrial Visit · 2025</div>
    <h1 id="ki-title"><?=e($visit['title'])?></h1>
    <p class="ki-lead">Pengalaman belajar di luar kelas untuk memperluas wawasan dan mengenal lingkungan pendidikan tinggi bersama <?=e($visit['institution'])?>.</p>
    <div class="ki-meta"><span>◷ <?=e($visit['visit_date'] ? date('d F Y', strtotime($visit['visit_date'])) : 'Tanggal belum ditentukan')?></span><span>⌁ <?=e($visit['duration_label'])?></span></div>
    <div class="ki-logo"><?php vb_dyn_open('ki-logo-image', 'span', 'style="display:contents"'); $logoUrl = visit_img($visit['institution_logo'] ?? null, '', 3, false); if ($logoUrl !== ''): ?><img src="<?=e($logoUrl)?>" alt="Logo <?=e($visit['institution'])?>"><?php else: ?><span class="ki-logo-missing" role="img" aria-label="Logo belum diunggah"><svg viewBox="0 0 24 24" width="25" height="25" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9" r="1.5"/><path d="m21 15-5-5L5 20"/></svg></span><?php endif; vb_dyn_close(); ?><span><?=e($visit['institution'])?></span></div>
    <div><a class="ki-scroll" href="#ki-story">Discover the journey</a></div>
  </div>
</section>
<section class="ki-section" id="ki-story"><div class="ki-container ki-story-grid">
  <?php vb_dyn_open('ki-story-photo', 'div', 'class="ki-photo-frame ki-reveal"'); $midPhoto = visit_img($visit['middle_image'] ?? null, '', 1); if ($midPhoto !== ''): ?><img src="<?=e($midPhoto)?>" alt="Dokumentasi kegiatan kunjungan industri" loading="lazy"><?php else: ?><div class="ki-placeholder"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9" r="1.5"/><path d="m21 15-5-5L5 20"/></svg><strong>Dokumentasi Kunjungan Industri</strong><span>Foto dapat ditambahkan melalui panel admin.</span></div><?php endif; vb_dyn_close(); ?>
  <div class="ki-story-copy ki-reveal ki-delay-1"><div class="ki-kicker">Belajar di luar kelas</div><h2>Melihat lebih dekat dunia teknologi dan pendidikan tinggi.</h2><p><?=nl2br(e($visit['story'] ?: 'Kisah kegiatan akan diperbarui berdasarkan dokumentasi resmi sekolah.'))?></p><div class="ki-purpose"><div class="ki-kicker">Tujuan Kunjungan</div><p><?=nl2br(e($visit['purpose'] ?: 'Memperluas wawasan siswa melalui pengalaman belajar langsung.'))?></p></div><button class="ki-detail-button" type="button" data-ki-open>Jelajahi detail kegiatan <span aria-hidden="true">↗</span></button></div>
</div></section>
<section class="ki-section ki-bottom"><div class="ki-container">
  <?php vb_dyn_open('ki-bottom-photo', 'div', 'class="ki-bottom-frame ki-reveal"'); $bottomPhoto = visit_img($visit['bottom_image'] ?? null, '', 2); if ($bottomPhoto !== ''): ?><img src="<?=e($bottomPhoto)?>" alt="Foto penutup kunjungan industri" loading="lazy"><div class="ki-bottom-overlay"><div><div class="ki-kicker">A journey to remember</div><h2>Pengalaman hari ini, bekal untuk masa depan.</h2><p>Setiap kunjungan membuka kesempatan untuk belajar, bertanya, dan melihat kemungkinan baru.</p><button class="ki-detail-button" type="button" data-ki-open>Lihat detail kunjungan</button></div></div><?php else: ?><div class="ki-placeholder" style="height:100%"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9" r="1.5"/><path d="m21 15-5-5L5 20"/></svg><strong>Pengalaman hari ini, bekal untuk masa depan.</strong><span>Foto penutup dapat diganti melalui panel admin.</span><button class="ki-detail-button" type="button" data-ki-open>Detail kunjungan</button></div><?php endif; vb_dyn_close(); ?>
</div></section>
<div class="ki-modal" id="ki-detail-modal" role="dialog" aria-modal="true" aria-labelledby="ki-modal-title" aria-hidden="true"><div class="ki-modal-dialog"><div class="ki-modal-top"><div><div class="ki-kicker">Industrial Visit / 2025</div><h2 id="ki-modal-title"><?=e($visit['title'])?></h2></div><button type="button" class="ki-modal-close" data-ki-close aria-label="Tutup detail">×</button></div><div class="ki-modal-meta"><span><?=e($visit['institution'])?></span><span><?=e($visit['visit_date'] ? date('d F Y', strtotime($visit['visit_date'])) : 'Tanggal belum ditentukan')?></span><span><?=e($visit['duration_label'])?></span></div><h3 style="color:#fff">Tujuan Kunjungan</h3><p><?=nl2br(e($visit['purpose'] ?: '-'))?></p><h3 style="color:#fff">Cerita Kegiatan</h3><p><?=nl2br(e($visit['story'] ?: '-'))?></p></div></div>
<?php else: ?><section class="ki-section"><div class="ki-container"><h1>Kunjungan Industri</h1><p>Informasi kegiatan belum tersedia.</p></div></section><?php endif; ?>
</main>
<script>
(()=>{const modal=document.getElementById('ki-detail-modal');if(!modal)return;let previousFocus=null;const open=()=>{previousFocus=document.activeElement;modal.classList.add('is-open');modal.setAttribute('aria-hidden','false');document.body.style.overflow='hidden';modal.querySelector('[data-ki-close]').focus()};const close=()=>{modal.classList.remove('is-open');modal.setAttribute('aria-hidden','true');document.body.style.overflow='';if(previousFocus)previousFocus.focus()};document.querySelectorAll('[data-ki-open]').forEach(b=>b.addEventListener('click',open));modal.querySelectorAll('[data-ki-close]').forEach(b=>b.addEventListener('click',close));modal.addEventListener('click',e=>{if(e.target===modal)close()});document.addEventListener('keydown',e=>{if(e.key==='Escape'&&modal.classList.contains('is-open'))close()});const items=document.querySelectorAll('.ki-reveal');if('IntersectionObserver'in window){const observer=new IntersectionObserver(entries=>entries.forEach(entry=>{if(entry.isIntersecting){entry.target.classList.add('is-visible');observer.unobserve(entry.target)}}),{threshold:.12});items.forEach(item=>observer.observe(item))}else items.forEach(item=>item.classList.add('is-visible'))})();
</script>
<?php vb_end(); ?>

<?php require_once __DIR__ . '/includes/public-footer.php'; ?>
