<?php

declare(strict_types=1);

$pageTitle = 'Magang Jepang';

require_once __DIR__ . '/includes/public-header.php';

$pdo->exec(
    "CREATE TABLE IF NOT EXISTS magang_instagram (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(220) NOT NULL,
        instagram_url VARCHAR(500) NOT NULL,
        description TEXT NULL,
        status ENUM('published','draft') DEFAULT 'published',
        sort_order INT NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
);

$posts = $pdo->query(
    "SELECT * FROM magang_instagram
     WHERE status = 'published'
     ORDER BY sort_order ASC, id DESC"
)->fetchAll();


vb_begin('magang');
?>

<section class="magang-japan-hero">
    <div class="magang-grid" aria-hidden="true"></div>
    <div class="magang-orbit magang-orbit-one" aria-hidden="true"></div>
    <div class="magang-orbit magang-orbit-two" aria-hidden="true"></div>
    <div class="magang-japan-sun" aria-hidden="true"></div>
    <div class="magang-hero-word" aria-hidden="true">JAPAN</div>

    <div class="container magang-hero-inner">
        <div class="magang-hero-copy reveal">
            <p class="magang-kicker"><span>01</span> / INTERNATIONAL INTERNSHIP</p>
            <h1><?= e(cms_text('magang', 'hero_title', 'MAGANG KE JEPANG')) ?></h1>
            <p class="magang-hero-text">
                Membangun pengalaman, kedisiplinan, dan wawasan kerja melalui pengalaman internasional di Jepang.
            </p>
            <div class="magang-hero-actions">
                <a class="btn btn-yellow" href="#japan-experience">Lihat Pengalaman <span>↘</span></a>
                <a class="magang-text-link" href="#japan-journey">Lihat Journey <span>↗</span></a>
            </div>
        </div>

        <div class="magang-journey-visual reveal" aria-label="Japan journey">
            <div class="magang-map-orbit">
                <span class="magang-map-label label-id">INDONESIA</span>
                <span class="magang-map-label label-prep">PREPARATION</span>
                <span class="magang-map-label label-jp">JAPAN</span>
                <span class="magang-map-label label-career">EXPERIENCE</span>
                <span class="magang-route magang-route-a"></span>
                <span class="magang-route magang-route-b"></span>
                <span class="magang-route-dot"></span>
                <span class="magang-route-dot second"></span>
                <div class="magang-japan-mark">日</div>
            </div>
        </div>
    </div>

    <div class="magang-scroll-cue" aria-hidden="true">
        <span>SCROLL TO EXPLORE</span><i></i>
    </div>
</section>

<section class="section magang-intro-section">
    <div class="container magang-intro-grid">
        <div class="reveal">
            <p class="magang-section-kicker">02 / JAPAN INTERNSHIP</p>
            <h2><?= e(cms_text('magang', 'section_1_title', 'Belajar di kelas. Berkembang di dunia nyata.')) ?></h2>
        </div>
        <div class="magang-intro-copy reveal">
            <p>
                Program Magang Jepang menjadi ruang untuk mengenal budaya kerja, membangun kemandirian,
                dan mendapatkan pengalaman profesional dalam lingkungan internasional.
            </p>
            <div class="magang-mini-line"><span></span><b>INTERNATIONAL EXPERIENCE</b></div>
        </div>
    </div>
</section>

<section class="magang-benefit-section" id="japan-journey">
    <div class="container">
        <div class="magang-section-heading reveal">
            <div>
                <p class="magang-section-kicker">03 / WHY JAPAN</p>
                <h2>Lebih dari sekadar<br><strong>pengalaman kerja.</strong></h2>
            </div>
            <span class="magang-heading-number">JPN / 03</span>
        </div>

        <div class="magang-benefit-grid">
            <?php
            $benefits = [
                ['01', 'WORK CULTURE', 'Mengenal disiplin, ketelitian, dan budaya profesional Jepang.'],
                ['02', 'LANGUAGE', 'Mengembangkan kemampuan komunikasi dan penggunaan bahasa Jepang.'],
                ['03', 'REAL EXPERIENCE', 'Belajar melalui pengalaman langsung dalam lingkungan kerja.'],
                ['04', 'GLOBAL EXPOSURE', 'Membuka wawasan terhadap lingkungan dan cara kerja internasional.'],
            ];
            ?>
            <?php foreach ($benefits as $index => $benefit): ?>
                <article class="magang-benefit-card magang-accent-<?= $index % 2 === 0 ? 'yellow' : 'blue' ?> reveal">
                    <span class="benefit-number"><?= e($benefit[0]) ?></span>
                    <span class="benefit-symbol" aria-hidden="true">↗</span>
                    <div>
                        <p><?= e($benefit[1]) ?></p>
                        <h3><?= e($benefit[2]) ?></h3>
                    </div>
                    <span class="benefit-line"></span>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="magang-process-section">
    <div class="container">
        <div class="magang-section-heading light reveal">
            <div>
                <p class="magang-section-kicker">04 / PREPARATION</p>
                <h2>Menuju Jepang,<br><strong>selangkah demi selangkah.</strong></h2>
            </div>
        </div>

        <div class="magang-process-line" aria-hidden="true"><span></span></div>
        <div class="magang-process-grid">
            <?php
            $steps = [
                ['01', 'PERSIAPAN', 'Membangun kesiapan awal program.'],
                ['02', 'BAHASA JEPANG', 'Mempersiapkan komunikasi dan pemahaman bahasa.'],
                ['03', 'PEMBEKALAN', 'Mengenal budaya dan lingkungan kerja.'],
                ['04', 'SELEKSI', 'Mengikuti proses seleksi program.'],
                ['05', 'KEBERANGKATAN', 'Mempersiapkan perjalanan menuju Jepang.'],
                ['06', 'MAGANG DI JEPANG', 'Mendapatkan pengalaman langsung di lingkungan kerja.'],
            ];
            ?>
            <?php foreach ($steps as $i => $step): ?>
                <article class="magang-step reveal">
                    <span><?= e($step[0]) ?></span>
                    <h3><?= e($step[1]) ?></h3>
                    <p><?= e($step[2]) ?></p>
                    <i></i>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="magang-instagram-section" id="japan-experience">
    <div class="magang-instagram-word" aria-hidden="true">JAPAN</div>
    <div class="container">
        <div class="magang-instagram-heading reveal">
            <div>
                <p class="magang-section-kicker">05 / JAPAN EXPERIENCE</p>
                <h2>Cerita langsung<br><strong>dari Instagram.</strong></h2>
                <p>
                    Dokumentasi perjalanan Magang Jepang ditampilkan langsung dari Instagram sekolah.
                </p>
            </div>
            <div class="magang-live-badge"><span></span> LIVE FROM INSTAGRAM</div>
        </div>

<?php vb_dyn_open('posts', 'div'); ?>
        <?php if ($posts): ?>
            <div class="magang-instagram-grid">
                <?php foreach ($posts as $index => $post): ?>
                    <?php $embed = instagram_embed_url((string) $post['instagram_url']); ?>
                    <?php if ($embed === '') continue; ?>
                    <article class="magang-instagram-card magang-card-<?= ($index % 4) + 1 ?> reveal">
                        <div class="magang-card-topline">
                            <span><?= sprintf('%02d', $index + 1) ?> / JAPAN</span>
                            <span>INSTAGRAM</span>
                        </div>
                        <div class="magang-card-corner corner-tl"></div>
                        <div class="magang-card-corner corner-br"></div>
                        <div class="magang-ig-frame">
                            <blockquote
                                class="instagram-media"
                                data-instgrm-permalink="<?= e($embed) ?>"
                                data-instgrm-version="14"
                                style="background:#FFF; border:0; margin:0 auto; max-width:540px; min-width:280px; width:100%;"
                            ></blockquote>
                        </div>
                        <div class="magang-card-bottomline">
                            <div>
                                <strong><?= e($post['title']) ?></strong>
                                <?php if (!empty($post['description'])): ?>
                                    <span><?= e($post['description']) ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="<?= e($embed) ?>" target="_blank" rel="noopener noreferrer" aria-label="Lihat di Instagram">↗</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="magang-instagram-empty reveal">
                <span>IG / 00</span>
                <h3>Dokumentasi Magang Jepang akan tampil di sini.</h3>
                <p>Tambahkan URL Instagram melalui Admin → Magang Jepang / Instagram.</p>
            </div>
        <?php endif; ?>
<?php vb_dyn_close(); ?>
    </div>
</section>

<section class="magang-cta-section">
    <div class="magang-cta-grid" aria-hidden="true"></div>
    <div class="container magang-cta-inner reveal">
        <p class="magang-section-kicker">06 / READY FOR JAPAN?</p>
        <h2>Siapkan langkahmu<br><strong>menuju pengalaman internasional.</strong></h2>
        <a class="btn btn-yellow" href="<?= e(url('kontak.php')) ?>">Hubungi Kami <span>↗</span></a>
    </div>
</section>

<script async src="https://www.instagram.com/embed.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const refreshInstagram = () => {
        if (window.instgrm && window.instgrm.Embeds) {
            window.instgrm.Embeds.process();
        }
    };
    refreshInstagram();
    window.addEventListener('load', refreshInstagram);
});
</script>

<style>
/* Magang Jepang — dedicated visual system */
.magang-japan-hero{position:relative;min-height:820px;overflow:hidden;background:#07111f;color:#f8fafc;display:flex;align-items:center;border-bottom:1px solid rgba(96,165,250,.18)}
.magang-grid,.magang-cta-grid{position:absolute;inset:0;background-image:linear-gradient(rgba(96,165,250,.075) 1px,transparent 1px),linear-gradient(90deg,rgba(96,165,250,.075) 1px,transparent 1px);background-size:64px 64px;mask-image:linear-gradient(to bottom,transparent,black 20%,black 80%,transparent);animation:magangGrid 18s linear infinite}
.magang-hero-inner{position:relative;z-index:2;display:grid;grid-template-columns:1.05fr .95fr;gap:40px;align-items:center;padding:130px 0 100px}
.magang-kicker,.magang-section-kicker{font:700 11px/1.4 'DM Sans',sans-serif;letter-spacing:.2em;text-transform:uppercase;color:#60a5fa;margin:0 0 22px}.magang-kicker span{color:#facc15}.magang-hero-copy h1{font:800 clamp(58px,8vw,116px)/.82 Manrope,sans-serif;letter-spacing:-.065em;margin:0}.magang-hero-copy h1 em{font-style:normal;color:#facc15;-webkit-text-stroke:1px rgba(250,204,21,.35)}.magang-hero-text{max-width:570px;color:rgba(248,250,252,.68);font-size:17px;line-height:1.8;margin:34px 0}.magang-hero-actions{display:flex;align-items:center;gap:28px}.magang-text-link{color:#f8fafc;text-decoration:none;font-weight:700}.magang-text-link span{color:#facc15;transition:transform .3s}.magang-text-link:hover span{display:inline-block;transform:translate(4px,-4px)}
.magang-hero-word{position:absolute;right:-4vw;top:9%;font:800 25vw/.8 Manrope,sans-serif;letter-spacing:-.09em;color:rgba(37,99,235,.055);pointer-events:none;user-select:none}.magang-japan-sun{position:absolute;width:330px;height:330px;border:1px solid rgba(239,68,68,.32);border-radius:50%;right:16%;top:12%;box-shadow:0 0 90px rgba(239,68,68,.08);animation:magangPulse 5s ease-in-out infinite}.magang-japan-sun:after{content:"";position:absolute;inset:24px;border:1px dashed rgba(250,204,21,.2);border-radius:50%;animation:magangSpin 24s linear infinite}.magang-orbit{position:absolute;border:1px solid rgba(96,165,250,.12);border-radius:50%;pointer-events:none}.magang-orbit-one{width:620px;height:620px;right:0;top:4%;animation:magangSpin 30s linear infinite}.magang-orbit-two{width:820px;height:820px;right:-90px;top:-10%;border-color:rgba(250,204,21,.08);animation:magangSpinReverse 42s linear infinite}
.magang-journey-visual{min-height:520px;display:grid;place-items:center}.magang-map-orbit{width:min(480px,80vw);aspect-ratio:1;position:relative;border:1px solid rgba(96,165,250,.2);border-radius:50%;box-shadow:inset 0 0 80px rgba(37,99,235,.07),0 0 80px rgba(37,99,235,.08);animation:magangFloat 6s ease-in-out infinite}.magang-map-orbit:before,.magang-map-orbit:after{content:"";position:absolute;border-radius:50%;inset:10%;border:1px dashed rgba(248,250,252,.13);animation:magangSpin 20s linear infinite}.magang-map-orbit:after{inset:27%;border-style:solid;border-color:rgba(250,204,21,.16);animation:magangSpinReverse 12s linear infinite}.magang-japan-mark{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:118px;height:118px;border-radius:50%;display:grid;place-items:center;background:#ef4444;color:#fff;font:800 50px/1 Manrope;box-shadow:0 0 0 12px rgba(239,68,68,.07),0 0 70px rgba(239,68,68,.22);animation:magangMarkPulse 3.2s ease-in-out infinite}.magang-map-label{position:absolute;font:800 10px/1 'DM Sans',sans-serif;letter-spacing:.16em;color:rgba(248,250,252,.62);padding:9px 11px;border:1px solid rgba(96,165,250,.18);background:rgba(7,17,31,.7);backdrop-filter:blur(8px)}.label-id{left:2%;top:39%}.label-prep{left:30%;top:5%;color:#facc15}.label-jp{right:5%;top:38%;color:#fff}.label-career{right:24%;bottom:10%;color:#60a5fa}.magang-route{position:absolute;height:1px;background:linear-gradient(90deg,transparent,#facc15,transparent);transform-origin:left center}.magang-route-a{width:58%;left:13%;top:47%;transform:rotate(-22deg);animation:magangRoute 3.2s linear infinite}.magang-route-b{width:48%;right:7%;top:57%;transform:rotate(39deg);background:linear-gradient(90deg,transparent,#60a5fa,transparent);animation:magangRoute 4.1s linear infinite reverse}.magang-route-dot{position:absolute;width:8px;height:8px;border-radius:50%;background:#facc15;left:37%;top:34%;box-shadow:0 0 20px #facc15;animation:magangDot 3.2s linear infinite}.magang-route-dot.second{background:#60a5fa;box-shadow:0 0 20px #60a5fa;left:67%;top:58%;animation-duration:4.1s;animation-direction:reverse}.magang-scroll-cue{position:absolute;z-index:3;bottom:28px;left:50%;transform:translateX(-50%);display:flex;align-items:center;gap:13px;color:rgba(248,250,252,.45);font-size:9px;letter-spacing:.2em}.magang-scroll-cue i{display:block;width:42px;height:1px;background:#facc15;animation:magangCue 1.8s ease-in-out infinite}
.magang-intro-section{background:#f8fafc}.magang-intro-grid{display:grid;grid-template-columns:1fr 1fr;gap:100px;align-items:end}.magang-intro-section h2,.magang-section-heading h2{font:700 clamp(38px,5vw,70px)/.95 Manrope,sans-serif;letter-spacing:-.055em;color:#07111f;margin:0}.magang-intro-section h2 strong,.magang-section-heading h2 strong{color:#2563eb}.magang-intro-copy p{font-size:18px;line-height:1.8;color:#475569;max-width:570px}.magang-mini-line{display:flex;align-items:center;gap:15px;margin-top:35px;font:800 9px/1 'DM Sans';letter-spacing:.16em;color:#07111f}.magang-mini-line span{width:70px;height:3px;background:#facc15}
.magang-benefit-section{padding:120px 0;background:#fff}.magang-section-heading{display:flex;justify-content:space-between;align-items:end;gap:30px;margin-bottom:65px}.magang-heading-number{font:800 10px/1 'DM Sans';letter-spacing:.18em;color:#94a3b8}.magang-benefit-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}.magang-benefit-card{position:relative;min-height:350px;padding:28px 26px;display:flex;flex-direction:column;justify-content:space-between;background:#07111f;color:#f8fafc;overflow:hidden;transition:transform .5s cubic-bezier(.2,.8,.2,1),box-shadow .5s,border-color .5s;border:1px solid rgba(96,165,250,.12)}.magang-benefit-card:before{content:"";position:absolute;width:180px;height:180px;border-radius:50%;right:-90px;top:-90px;background:rgba(37,99,235,.13);transition:transform .7s}.magang-benefit-card:after{content:"";position:absolute;left:-30%;top:0;width:30%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.08),transparent);transform:skewX(-18deg);transition:transform .9s}.magang-benefit-card:hover{transform:translateY(-12px);box-shadow:0 30px 70px rgba(7,17,31,.2)}.magang-benefit-card:hover:before{transform:scale(2.2)}.magang-benefit-card:hover:after{transform:translateX(430%)}.magang-accent-yellow{border-top:3px solid #facc15}.magang-accent-blue{border-top:3px solid #2563eb}.benefit-number{font:800 12px/1 'DM Sans';letter-spacing:.16em;color:#facc15}.magang-accent-blue .benefit-number{color:#60a5fa}.benefit-symbol{align-self:flex-end;font-size:26px;color:#fff;transition:transform .4s;color:#facc15}.magang-accent-blue .benefit-symbol{color:#60a5fa}.magang-benefit-card:hover .benefit-symbol{transform:translate(6px,-6px) rotate(8deg)}.magang-benefit-card p{font:800 10px/1.3 'DM Sans';letter-spacing:.15em;color:rgba(248,250,252,.48);margin:0 0 10px}.magang-benefit-card h3{font:700 23px/1.25 Manrope;margin:0;max-width:250px}.benefit-line{width:35px;height:3px;background:#facc15;transition:width .5s}.magang-accent-blue .benefit-line{background:#2563eb}.magang-benefit-card:hover .benefit-line{width:100%}
.magang-process-section{position:relative;padding:130px 0;background:#07111f;color:#f8fafc;overflow:hidden}.magang-process-section:before{content:"PREPARE";position:absolute;right:-5vw;top:12%;font:800 20vw/.8 Manrope;color:rgba(96,165,250,.035);letter-spacing:-.09em}.magang-section-heading.light h2{color:#f8fafc}.magang-process-line{height:2px;background:rgba(96,165,250,.16);position:relative;margin:60px 0 0}.magang-process-line span{display:block;width:35%;height:100%;background:linear-gradient(90deg,#facc15,#2563eb);animation:magangProgress 5s ease-in-out infinite}.magang-process-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:20px;margin-top:0}.magang-step{position:relative;padding-top:30px;min-height:220px;border-left:1px solid rgba(96,165,250,.15);padding-left:18px}.magang-step:before{content:"";position:absolute;width:10px;height:10px;border-radius:50%;background:#07111f;border:2px solid #facc15;top:-5px;left:-6px;transition:transform .4s,box-shadow .4s}.magang-step:hover:before{transform:scale(1.5);box-shadow:0 0 0 8px rgba(250,204,21,.08),0 0 25px rgba(250,204,21,.4)}.magang-step>span{font:800 11px/1 'DM Sans';color:#facc15;letter-spacing:.14em}.magang-step h3{font:800 15px/1.2 Manrope;margin:25px 0 10px}.magang-step p{font-size:13px;line-height:1.6;color:rgba(248,250,252,.52);margin:0}.magang-step i{display:block;width:25px;height:2px;background:#2563eb;margin-top:25px;transition:width .4s}.magang-step:hover i{width:70px}
.magang-instagram-section{position:relative;padding:130px 0;background:#f8fafc;overflow:hidden}.magang-instagram-word{position:absolute;left:-2vw;top:5%;font:800 22vw/.8 Manrope;letter-spacing:-.09em;color:rgba(37,99,235,.035);pointer-events:none}.magang-instagram-heading{display:flex;justify-content:space-between;align-items:end;gap:40px;margin-bottom:60px;position:relative;z-index:1}.magang-instagram-heading h2{font:700 clamp(40px,5vw,74px)/.9 Manrope;letter-spacing:-.055em;color:#07111f;margin:0}.magang-instagram-heading h2 strong{color:#2563eb}.magang-instagram-heading>div:first-child>p:last-child{max-width:540px;color:#64748b;line-height:1.7;margin:25px 0 0}.magang-live-badge{display:flex;align-items:center;gap:10px;font:800 9px/1 'DM Sans';letter-spacing:.16em;color:#07111f;padding:12px 15px;border:1px solid rgba(7,17,31,.12);white-space:nowrap}.magang-live-badge span{width:7px;height:7px;border-radius:50%;background:#ef4444;box-shadow:0 0 0 rgba(239,68,68,.5);animation:magangLive 1.8s infinite}.magang-instagram-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:28px;align-items:start;position:relative;z-index:1}.magang-instagram-card{position:relative;background:#fff;border:1px solid rgba(7,17,31,.09);padding:14px;box-shadow:0 18px 50px rgba(7,17,31,.07);overflow:hidden;transition:transform .55s cubic-bezier(.2,.8,.2,1),box-shadow .55s,border-color .4s}.magang-instagram-card:nth-child(2){margin-top:80px}.magang-instagram-card:nth-child(5){margin-top:80px}.magang-instagram-card:hover{transform:translateY(-14px) rotate(-.3deg);box-shadow:0 35px 85px rgba(7,17,31,.15);border-color:rgba(37,99,235,.35)}.magang-card-topline{display:flex;justify-content:space-between;padding:6px 4px 14px;font:800 8px/1 'DM Sans';letter-spacing:.16em;color:#64748b}.magang-card-topline span:first-child{color:#2563eb}.magang-card-corner{position:absolute;width:20px;height:20px;z-index:3;pointer-events:none;transition:width .45s,height .45s}.corner-tl{left:0;top:0;border-left:3px solid #facc15;border-top:3px solid #facc15}.corner-br{right:0;bottom:0;border-right:3px solid #2563eb;border-bottom:3px solid #2563eb}.magang-instagram-card:hover .magang-card-corner{width:38px;height:38px}.magang-ig-frame{position:relative;background:#f1f5f9;overflow:hidden;min-height:500px}.magang-ig-frame:before{content:"";position:absolute;inset:0;background:linear-gradient(120deg,transparent 30%,rgba(255,255,255,.55) 48%,transparent 65%);transform:translateX(-120%);z-index:2;pointer-events:none}.magang-instagram-card:hover .magang-ig-frame:before{animation:magangSweep 1.1s ease}.magang-card-bottomline{display:flex;justify-content:space-between;gap:20px;padding:17px 5px 4px;border-top:1px solid rgba(7,17,31,.08);margin-top:12px}.magang-card-bottomline strong{display:block;font:800 14px/1.35 Manrope;color:#07111f}.magang-card-bottomline span{display:block;color:#64748b;font-size:11px;line-height:1.5;margin-top:5px}.magang-card-bottomline a{flex:0 0 auto;width:38px;height:38px;border:1px solid rgba(37,99,235,.18);display:grid;place-items:center;color:#2563eb;text-decoration:none;transition:.35s}.magang-card-bottomline a:hover{background:#2563eb;color:#fff;transform:translate(3px,-3px)}.magang-instagram-empty{position:relative;border:1px dashed rgba(37,99,235,.25);padding:70px;text-align:center;background:#fff}.magang-instagram-empty span{font:800 10px/1 'DM Sans';letter-spacing:.18em;color:#facc15}.magang-instagram-empty h3{font:800 30px/1.1 Manrope;color:#07111f}.magang-instagram-empty p{color:#64748b}
.magang-cta-section{position:relative;overflow:hidden;background:#07111f;color:#f8fafc;padding:120px 0}.magang-cta-inner{position:relative;z-index:2}.magang-cta-inner h2{font:700 clamp(42px,6vw,86px)/.9 Manrope;letter-spacing:-.06em;margin:0 0 38px}.magang-cta-inner h2 strong{color:#facc15}.magang-cta-section:after{content:"日";position:absolute;right:8%;top:50%;transform:translateY(-50%);font:800 330px/1 Manrope;color:rgba(239,68,68,.08);animation:magangFloat 6s ease-in-out infinite}
@keyframes magangGrid{to{background-position:64px 64px}}@keyframes magangSpin{to{transform:rotate(360deg)}}@keyframes magangSpinReverse{to{transform:rotate(-360deg)}}@keyframes magangFloat{50%{transform:translateY(-12px)}}@keyframes magangPulse{50%{transform:scale(1.04);opacity:.7}}@keyframes magangMarkPulse{50%{box-shadow:0 0 0 24px rgba(239,68,68,.04),0 0 90px rgba(239,68,68,.3)}}@keyframes magangRoute{0%{opacity:.1;transform:scaleX(.5) rotate(-22deg)}50%{opacity:1;transform:scaleX(1) rotate(-22deg)}100%{opacity:.1;transform:scaleX(.5) rotate(-22deg)}}@keyframes magangDot{0%{transform:translate(0,0);opacity:0}20%{opacity:1}50%{transform:translate(120px,45px)}80%{opacity:1}100%{transform:translate(210px,90px);opacity:0}}@keyframes magangCue{50%{width:70px}}@keyframes magangProgress{0%,100%{width:20%}50%{width:80%}}@keyframes magangLive{70%{box-shadow:0 0 0 8px rgba(239,68,68,0)}}@keyframes magangSweep{to{transform:translateX(160%)}}@media(max-width:1100px){.magang-benefit-grid{grid-template-columns:repeat(2,1fr)}.magang-process-grid{grid-template-columns:repeat(3,1fr)}.magang-instagram-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.magang-instagram-card:nth-child(2),.magang-instagram-card:nth-child(5){margin-top:50px}}@media(max-width:820px){.magang-japan-hero{min-height:auto}.magang-hero-inner{grid-template-columns:1fr;padding:110px 0 90px}.magang-journey-visual{min-height:420px}.magang-intro-grid{grid-template-columns:1fr;gap:30px}.magang-instagram-heading{display:block}.magang-live-badge{display:inline-flex;margin-top:25px}.magang-process-grid{grid-template-columns:repeat(2,1fr)}}@media(max-width:620px){.magang-hero-copy h1{font-size:58px}.magang-hero-actions{flex-direction:column;align-items:flex-start}.magang-map-orbit{width:320px}.magang-japan-sun{width:220px;height:220px;right:-30px;top:25%}.magang-benefit-grid,.magang-process-grid,.magang-instagram-grid{grid-template-columns:1fr}.magang-instagram-card:nth-child(2),.magang-instagram-card:nth-child(5){margin-top:0}.magang-section-heading{display:block}.magang-heading-number{display:block;margin-top:20px}.magang-card-bottomline{padding-bottom:6px}.magang-cta-section:after{font-size:220px;right:-30px}}
@media(prefers-reduced-motion:reduce){.magang-grid,.magang-orbit,.magang-japan-sun,.magang-map-orbit,.magang-map-orbit:before,.magang-map-orbit:after,.magang-japan-mark,.magang-route,.magang-route-dot,.magang-scroll-cue i,.magang-process-line span,.magang-live-badge span,.magang-cta-section:after{animation:none!important}.magang-instagram-card,.magang-benefit-card{transition:none!important}}
</style>

<?php vb_end(); ?>
<?php require __DIR__ . '/includes/public-footer.php'; ?>
