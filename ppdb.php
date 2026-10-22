<?php
    $pageTitle='PPDB';
    require_once __DIR__.'/includes/public-header.php';
    $docs=$pdo->query('SELECT * FROM ppdb_documents ORDER BY id DESC')->fetchAll();
    $year=(int)date('Y');
    $ppdbStatus=setting('ppdb_status','OPEN');
    $ppdbIntro=setting('ppdb_intro','Temukan jadwal, persyaratan, panduan, dan dokumen pendaftaran peserta didik baru.');

vb_begin('ppdb');
?>

<main class="ppdb-page">
    <section class="ppdb-hero ppdb-reveal">
        <div class="ppdb-grid"></div>
        <div class="ppdb-hero-orbit ppdb-orbit-a"></div>
        <div class="ppdb-hero-orbit ppdb-orbit-b"></div>
        <span class="ppdb-orbit-dot ppdb-dot-a"></span>
        <span class="ppdb-orbit-dot ppdb-dot-b"></span>
        <div class="container ppdb-hero-inner">
            <div class="ppdb-hero-copy">
                <p class="ppdb-kicker">01 / ADMISSION</p>
                <div class="ppdb-wordmark">PPDB</div>
                <h1><?= e(cms_text('ppdb', 'hero_title', 'SMK INFOKOM KOTA BOGOR')) ?></h1>
                <p class="ppdb-display">START YOUR<br><strong>NEXT CHAPTER.</strong></p>
                <p class="ppdb-intro"><?=e($ppdbIntro)?></p>
                <div class="ppdb-actions">
                    <a class="ppdb-btn ppdb-btn-primary" href="<?=e(url('ppdb-daftar.php'))?>"><span>Daftar Sekarang</span><i>↗</i></a>
                    <a class="ppdb-btn ppdb-btn-outline" href="#ppdb-documents"><span>Download Brosur PDF</span><i>↓</i></a>
                </div>
            </div>
            <div class="ppdb-hero-system" aria-label="Status pendaftaran">
                <div class="ppdb-system-top"><span>PPDB / <?=$year?></span><span class="ppdb-live"><b></b> <?=e(strtoupper($ppdbStatus))?></span></div>
                <div class="ppdb-system-core"><span class="ppdb-system-label">REGISTRATION</span><strong><?=$year?></strong><em>SMK INFOKOM</em></div>
                <div class="ppdb-system-line"><span></span></div>
                <div class="ppdb-system-steps"><span>01 DATA</span><span>02 PILIHAN</span><span>03 DOKUMEN</span></div>
                <div class="ppdb-system-coord">06.59° S&nbsp;&nbsp;106.78° E <b>•</b> BOGOR</div>
            </div>
        </div>
        <div class="ppdb-scroll-note"><span></span> SCROLL TO EXPLORE</div>
    </section>

    <section class="ppdb-section ppdb-why">
        <div class="container">
            <div class="ppdb-section-head ppdb-reveal">
                <p class="ppdb-kicker">02 / WHY INFOKOM</p>
                <h2><?= e(cms_text('ppdb', 'section_1_title', 'YOUR NEXT CHAPTER STARTS HERE.')) ?></h2>
            </div>
            <div class="ppdb-editorial-list">
                <article class="ppdb-editorial-item ppdb-reveal"><span class="ppdb-num">01</span><div><h3>KOMPETENSI DIGITAL</h3><p>Belajar teknologi dan keterampilan yang relevan dengan perkembangan dunia kerja.</p></div><i>↗</i></article>
                <article class="ppdb-editorial-item ppdb-reveal"><span class="ppdb-num">02</span><div><h3>DUNIA INDUSTRI</h3><p>Pengalaman belajar diarahkan untuk mendekatkan siswa dengan kebutuhan industri.</p></div><i>↗</i></article>
                <article class="ppdb-editorial-item ppdb-reveal"><span class="ppdb-num">03</span><div><h3>KREATIVITAS</h3><p>Ruang untuk berkarya, mencoba ide baru, dan mengembangkan potensi setiap siswa.</p></div><i>↗</i></article>
                <article class="ppdb-editorial-item ppdb-reveal"><span class="ppdb-num">04</span><div><h3>KARAKTER</h3><p>Membangun disiplin, tanggung jawab, kolaborasi, dan kesiapan menghadapi masa depan.</p></div><i>↗</i></article>
            </div>
        </div>
    </section>

    <section class="ppdb-section ppdb-majors">
        <div class="container">
            <div class="ppdb-section-head ppdb-reveal"><p class="ppdb-kicker">03 / PROGRAM KEAHLIAN</p><h2>CHOOSE YOUR<br><span>DIRECTION.</span></h2></div>
            <div class="ppdb-major-list">
                <a href="<?=e(url('jurusan.php'))?>" class="ppdb-major ppdb-reveal"><span>01 / RPL</span><strong>REKAYASA PERANGKAT LUNAK</strong><i>↗</i></a>
                <a href="<?=e(url('jurusan.php'))?>" class="ppdb-major ppdb-reveal"><span>02 / TKJ</span><strong>TEKNIK KOMPUTER &amp; JARINGAN</strong><i>↗</i></a>
                <a href="<?=e(url('jurusan.php'))?>" class="ppdb-major ppdb-reveal"><span>03 / DKV</span><strong>DESAIN KOMUNIKASI VISUAL</strong><i>↗</i></a>
                <a href="<?=e(url('jurusan.php'))?>" class="ppdb-major ppdb-reveal"><span>04 / PSPT</span><strong>PRODUKSI SIARAN DAN PERFILMAN</strong><i>↗</i></a>
            </div>
        </div>
    </section>

    <section class="ppdb-section ppdb-journey">
        <div class="container">
            <div class="ppdb-section-head ppdb-reveal"><p class="ppdb-kicker">04 / REGISTRATION FLOW</p><h2>ONE PROCESS.<br><span>FIVE STEPS.</span></h2></div>
            <div class="ppdb-timeline">
                <div class="ppdb-progress"><span></span></div>
                <div class="ppdb-step ppdb-reveal"><b>01</b><div><strong>DATA DIRI</strong><p>Lengkapi identitas calon peserta didik.</p></div></div>
                <div class="ppdb-step ppdb-reveal"><b>02</b><div><strong>ASAL SEKOLAH</strong><p>Masukkan informasi sekolah asal.</p></div></div>
                <div class="ppdb-step ppdb-reveal"><b>03</b><div><strong>PILIH JURUSAN</strong><p>Tentukan program keahlian yang diminati.</p></div></div>
                <div class="ppdb-step ppdb-reveal"><b>04</b><div><strong>DOKUMEN</strong><p>Siapkan dokumen yang diperlukan.</p></div></div>
                <div class="ppdb-step ppdb-reveal"><b>05</b><div><strong>KONFIRMASI</strong><p>Periksa data lalu kirim pendaftaran.</p></div></div>
            </div>
        </div>
    </section>

    <section class="ppdb-section ppdb-docs" id="ppdb-documents">
        <div class="container">
            <div class="ppdb-section-head ppdb-reveal"><p class="ppdb-kicker">05 / DOCUMENTS</p><h2>READY YOUR<br><span>DOCUMENTS.</span></h2></div>
            <div class="ppdb-doc-layout">
                <div class="ppdb-checklist ppdb-reveal">
                    <?php foreach(['Kartu Keluarga','Akta Kelahiran','Ijazah / SKL','Pas Foto'] as $i=>$item): ?>
                    <div class="ppdb-check"><span>✓</span><strong><?=e($item)?></strong><small>REQUIRED</small></div>
                    <?php endforeach; ?>
                </div>
                <?php vb_dyn_open('docs', 'div', 'class="ppdb-pdf-stack"'); ?>
                    <?php if($docs): foreach($docs as $i=>$d): ?>
                    <article class="ppdb-pdf-card ppdb-reveal">
                        <div class="ppdb-pdf-paper"><span>PPDB</span><strong>PDF</strong><small><?=e($d['category'] ?: 'INFORMATION')?></small></div>
                        <div class="ppdb-pdf-copy"><p><?=e($d['category'] ?: 'DOCUMENT')?></p><h3><?=e($d['title'])?></h3><span><?=e($d['description'])?></span><a href="<?=e(url('download-ppdb.php?id='.(int)$d['id']))?>" download>DOWNLOAD PDF <b>↗</b></a></div>
                    </article>
                    <?php endforeach; else: ?>
                    <div class="ppdb-empty ppdb-reveal">Dokumen PPDB belum tersedia. Admin dapat menambahkan dokumen dari panel pengelolaan.</div>
                    <?php endif; ?>
                <?php vb_dyn_close(); ?>
            </div>
        </div>
    </section>

    <section class="ppdb-section ppdb-schedule">
        <div class="container">
            <div class="ppdb-section-head ppdb-reveal"><p class="ppdb-kicker">06 / SCHEDULE</p><h2>THE ROAD<br><span>AHEAD.</span></h2></div>
            <div class="ppdb-schedule-list">
                <div class="ppdb-schedule-row ppdb-reveal"><span>01</span><time>01</time><div><strong>PENDAFTARAN</strong><p>Pendaftaran peserta didik baru dibuka.</p></div></div>
                <div class="ppdb-schedule-row ppdb-reveal"><span>02</span><time>02</time><div><strong>VERIFIKASI</strong><p>Data dan dokumen pendaftaran diperiksa.</p></div></div>
                <div class="ppdb-schedule-row ppdb-reveal"><span>03</span><time>03</time><div><strong>SELEKSI</strong><p>Proses seleksi mengikuti ketentuan PPDB sekolah.</p></div></div>
                <div class="ppdb-schedule-row ppdb-reveal"><span>04</span><time>04</time><div><strong>PENGUMUMAN</strong><p>Informasi hasil pendaftaran diumumkan melalui kanal resmi.</p></div></div>
            </div>
        </div>
    </section>

    <section class="ppdb-section ppdb-faq">
        <div class="container">
            <div class="ppdb-section-head ppdb-reveal"><p class="ppdb-kicker">07 / FAQ</p><h2>QUESTIONS<br><span>ANSWERED.</span></h2></div>
            <div class="ppdb-faq-list">
                <details class="ppdb-reveal"><summary>Bagaimana cara mendaftar? <b>+</b></summary><p>Gunakan tombol Daftar Sekarang untuk masuk ke halaman pendaftaran dan lengkapi data yang diminta.</p></details>
                <details class="ppdb-reveal"><summary>Dokumen apa yang perlu disiapkan? <b>+</b></summary><p>Siapkan dokumen sesuai persyaratan PPDB yang tercantum pada dokumen resmi sekolah.</p></details>
                <details class="ppdb-reveal"><summary>Apakah bisa memilih lebih dari satu jurusan? <b>+</b></summary><p>Informasi pilihan jurusan mengikuti ketentuan PPDB yang ditetapkan sekolah pada periode pendaftaran.</p></details>
                <details class="ppdb-reveal"><summary>Bagaimana proses verifikasi? <b>+</b></summary><p>Data dan dokumen yang dikirim akan diperiksa oleh pihak sekolah sesuai proses PPDB.</p></details>
            </div>
        </div>
    </section>

    <section class="ppdb-cta ppdb-reveal">
        <div class="ppdb-cta-watermark">PPDB</div>
        <div class="container ppdb-cta-inner">
            <p class="ppdb-kicker">08 / START YOUR JOURNEY</p>
            <h2>READY TO JOIN<br><span>INFOKOM?</span></h2>
            <p>Your next chapter starts here.</p>
            <a class="ppdb-btn ppdb-btn-primary" href="<?=e(url('ppdb-daftar.php'))?>"><span>Daftar Sekarang</span><i>↗</i></a>
        </div>
    </section>
</main>

<style>
.ppdb-page{--pnavy:#07111f;--pblue:#2563eb;--pyellow:#facc15;--paper:#f8fafc;--ink:#0b1220;background:#f8fafc;color:var(--ink);overflow:hidden}
.ppdb-hero{min-height:760px;background:var(--pnavy);color:#fff;position:relative;display:flex;align-items:center;isolation:isolate}
.ppdb-grid{position:absolute;inset:0;opacity:.16;background-image:linear-gradient(rgba(255,255,255,.12) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.12) 1px,transparent 1px);background-size:72px 72px;animation:ppdbGrid 18s linear infinite}
.ppdb-hero:after{content:"";position:absolute;inset:auto 0 0;height:180px;background:linear-gradient(transparent,var(--pnavy));z-index:-1}
.ppdb-hero-inner{position:relative;z-index:2;display:grid;grid-template-columns:minmax(0,1.1fr) minmax(360px,.9fr);gap:80px;align-items:center;padding-top:80px}
.ppdb-kicker{font-size:11px;letter-spacing:.2em;font-weight:800;color:var(--pyellow);margin:0 0 22px}.ppdb-hero-copy{position:relative}.ppdb-wordmark{font:800 clamp(78px,12vw,170px)/.78 Manrope,sans-serif;letter-spacing:-.08em;color:transparent;-webkit-text-stroke:1px rgba(255,255,255,.2);position:absolute;left:-12px;top:18px;z-index:-1}.ppdb-hero h1{font:800 clamp(38px,5.6vw,78px)/.9 Manrope,sans-serif;letter-spacing:-.055em;margin:70px 0 34px}.ppdb-hero h1 span{color:#8fb5ff}.ppdb-display{font:700 clamp(28px,3.2vw,48px)/.98 Manrope,sans-serif;letter-spacing:-.04em;margin:0 0 25px}.ppdb-display strong{color:var(--pyellow)}.ppdb-intro{max-width:580px;color:rgba(255,255,255,.66);font-size:16px;line-height:1.8}.ppdb-actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:32px}.ppdb-btn{display:inline-flex;align-items:center;justify-content:space-between;gap:32px;min-height:52px;padding:0 20px;text-decoration:none;font-weight:800;font-size:11px;letter-spacing:.06em;text-transform:uppercase;border:1px solid transparent;transition:.35s ease}.ppdb-btn i{font-style:normal;font-size:18px}.ppdb-btn-primary{background:var(--pyellow);color:#111827}.ppdb-btn-primary:hover{transform:translateY(-4px);box-shadow:0 14px 35px rgba(250,204,21,.2)}.ppdb-btn-outline{border-color:rgba(255,255,255,.25);color:#fff}.ppdb-btn-outline:hover{border-color:var(--pyellow);color:var(--pyellow);transform:translateY(-4px)}
.ppdb-hero-system{min-height:430px;border:1px solid rgba(255,255,255,.2);background:rgba(255,255,255,.035);position:relative;padding:24px;backdrop-filter:blur(8px);box-shadow:0 30px 100px rgba(0,0,0,.2);transform:rotate(2deg);transition:transform .6s ease}.ppdb-hero-system:hover{transform:rotate(0) translateY(-8px)}.ppdb-system-top,.ppdb-system-steps{display:flex;justify-content:space-between;font-size:9px;letter-spacing:.14em;font-weight:800;color:rgba(255,255,255,.55)}.ppdb-live{color:#fff}.ppdb-live b{display:inline-block;width:7px;height:7px;border-radius:50%;background:var(--pyellow);animation:ppdbPulse 1.5s infinite}.ppdb-system-core{height:285px;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;border-left:1px solid rgba(255,255,255,.1);border-right:1px solid rgba(255,255,255,.1);margin:22px 0;position:relative}.ppdb-system-core:before,.ppdb-system-core:after{content:"";position:absolute;width:130px;height:130px;border:1px solid rgba(37,99,235,.55);border-radius:50%;animation:ppdbSpin 14s linear infinite}.ppdb-system-core:after{width:190px;height:190px;border-color:rgba(250,204,21,.25);animation-direction:reverse}.ppdb-system-label{font-size:9px;letter-spacing:.3em;color:#8fb5ff;z-index:1}.ppdb-system-core strong{font:800 96px/.9 Manrope,sans-serif;letter-spacing:-.08em;z-index:1}.ppdb-system-core em{font-style:normal;font-size:10px;letter-spacing:.3em;color:rgba(255,255,255,.5);z-index:1}.ppdb-system-line{height:1px;background:rgba(255,255,255,.14);margin-bottom:20px}.ppdb-system-line span{display:block;width:42%;height:2px;background:var(--pyellow);animation:ppdbLine 3s ease-in-out infinite}.ppdb-system-coord{font-size:8px;letter-spacing:.15em;color:rgba(255,255,255,.3);margin-top:24px}.ppdb-system-coord b{color:var(--pyellow)}.ppdb-hero-orbit{position:absolute;border:1px solid rgba(37,99,235,.35);border-radius:50%;pointer-events:none}.ppdb-orbit-a{width:520px;height:520px;right:-180px;top:80px;animation:ppdbSpin 24s linear infinite}.ppdb-orbit-b{width:730px;height:730px;right:-280px;top:-20px;border-color:rgba(250,204,21,.12);animation:ppdbSpin 34s linear infinite reverse}.ppdb-orbit-dot{position:absolute;width:9px;height:9px;background:var(--pyellow);border-radius:50%;right:230px;top:180px;box-shadow:0 0 0 8px rgba(250,204,21,.08);animation:ppdbFloat 3s ease-in-out infinite}.ppdb-dot-b{right:90px;top:610px;width:6px;height:6px;background:#60a5fa;animation-delay:1s}.ppdb-scroll-note{position:absolute;bottom:30px;left:calc(50% - 70px);font-size:8px;letter-spacing:.2em;color:rgba(255,255,255,.35);display:flex;gap:10px;align-items:center}.ppdb-scroll-note span{width:34px;height:1px;background:var(--pyellow)}
.ppdb-section{padding:130px 0}.ppdb-section-head{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:60px}.ppdb-section-head h2{font:800 clamp(42px,6vw,82px)/.88 Manrope,sans-serif;letter-spacing:-.06em;margin:0;text-align:right}.ppdb-section-head h2 span{color:var(--pblue)}.ppdb-editorial-list{border-top:1px solid #dbe2ea}.ppdb-editorial-item{display:grid;grid-template-columns:90px 1fr 50px;gap:30px;align-items:center;padding:34px 0;border-bottom:1px solid #dbe2ea;transition:.35s ease}.ppdb-editorial-item:hover{padding-left:18px;background:linear-gradient(90deg,rgba(37,99,235,.06),transparent)}.ppdb-editorial-item .ppdb-num{font:800 12px Manrope;color:#9aa7b7}.ppdb-editorial-item h3{font:800 24px Manrope;margin:0 0 8px}.ppdb-editorial-item p{margin:0;color:#697586;max-width:650px;line-height:1.7}.ppdb-editorial-item>i{font-style:normal;font-size:25px;text-align:right;transition:.3s}.ppdb-editorial-item:hover>i{color:var(--pblue);transform:translate(5px,-5px)}
.ppdb-majors{background:#eef4fb}.ppdb-major-list{border-top:1px solid #cfd9e5}.ppdb-major{display:grid;grid-template-columns:130px 1fr 60px;align-items:center;gap:20px;padding:34px 10px;border-bottom:1px solid #cfd9e5;text-decoration:none;color:var(--ink);transition:.4s ease}.ppdb-major span{font-size:10px;font-weight:800;letter-spacing:.12em;color:#64748b}.ppdb-major strong{font:800 clamp(22px,3vw,42px)/1 Manrope;letter-spacing:-.04em}.ppdb-major i{font-style:normal;text-align:right;font-size:25px;transition:.3s}.ppdb-major:hover{background:#fff;padding-left:25px;padding-right:25px;box-shadow:0 15px 40px rgba(15,76,129,.08)}.ppdb-major:hover strong{color:var(--pblue)}.ppdb-major:hover i{color:var(--pyellow);transform:translate(5px,-5px)}
.ppdb-journey{background:var(--pnavy);color:#fff}.ppdb-journey .ppdb-section-head h2{color:#fff}.ppdb-journey .ppdb-section-head h2 span{color:var(--pyellow)}.ppdb-timeline{position:relative;padding:25px 0}.ppdb-progress{position:absolute;left:32px;top:42px;bottom:42px;width:2px;background:rgba(255,255,255,.13)}.ppdb-progress span{display:block;width:100%;height:0;background:var(--pyellow);transition:height .2s linear}.ppdb-step{display:grid;grid-template-columns:65px 1fr;gap:30px;align-items:start;min-height:120px;position:relative}.ppdb-step>b{width:65px;height:65px;border:1px solid rgba(255,255,255,.2);border-radius:50%;display:grid;place-items:center;font:800 13px Manrope;background:var(--pnavy);z-index:1;transition:.3s}.ppdb-step:hover>b{background:var(--pyellow);color:#111827;border-color:var(--pyellow);transform:scale(1.08)}.ppdb-step strong{display:block;font:800 24px Manrope;margin:4px 0 8px}.ppdb-step p{margin:0;color:rgba(255,255,255,.5);line-height:1.7}
.ppdb-doc-layout{display:grid;grid-template-columns:.8fr 1.2fr;gap:70px;align-items:start}.ppdb-checklist{border-top:1px solid #dbe2ea}.ppdb-check{display:grid;grid-template-columns:36px 1fr auto;gap:16px;align-items:center;padding:22px 0;border-bottom:1px solid #dbe2ea}.ppdb-check span{width:28px;height:28px;border:1px solid var(--pblue);color:var(--pblue);display:grid;place-items:center;font-weight:900}.ppdb-check small{font-size:8px;letter-spacing:.16em;color:#94a3b8}.ppdb-pdf-stack{display:grid;gap:18px}.ppdb-pdf-card{display:grid;grid-template-columns:160px 1fr;gap:25px;padding:20px;border:1px solid #dbe2ea;background:#fff;transition:.4s;box-shadow:0 10px 35px rgba(15,23,42,.04)}.ppdb-pdf-card:hover{transform:translateY(-6px);border-color:#9ab6e8;box-shadow:0 20px 50px rgba(37,99,235,.1)}.ppdb-pdf-paper{min-height:180px;background:var(--pnavy);color:#fff;padding:18px;display:flex;flex-direction:column;justify-content:space-between;position:relative;overflow:hidden}.ppdb-pdf-paper:after{content:"";position:absolute;width:100px;height:100px;border:1px solid rgba(250,204,21,.35);border-radius:50%;right:-35px;bottom:-35px}.ppdb-pdf-paper span{font-size:10px;letter-spacing:.18em;color:var(--pyellow);font-weight:800}.ppdb-pdf-paper strong{font:800 46px Manrope;letter-spacing:-.06em}.ppdb-pdf-paper small{font-size:8px;letter-spacing:.14em;color:#93a4ba}.ppdb-pdf-copy p{font-size:9px;letter-spacing:.16em;color:var(--pblue);font-weight:800}.ppdb-pdf-copy h3{font:800 24px Manrope;margin:10px 0}.ppdb-pdf-copy>span{display:block;color:#64748b;line-height:1.7;font-size:14px}.ppdb-pdf-copy a{display:inline-flex;margin-top:25px;color:var(--ink);font-size:10px;font-weight:900;letter-spacing:.1em;text-decoration:none;border-bottom:2px solid var(--pyellow);padding-bottom:6px}.ppdb-pdf-copy a b{margin-left:18px;color:var(--pblue)}.ppdb-empty{border:1px dashed #cbd5e1;padding:40px;color:#64748b}
.ppdb-schedule{background:#fff}.ppdb-schedule-list{border-top:1px solid #dbe2ea}.ppdb-schedule-row{display:grid;grid-template-columns:70px 110px 1fr;gap:20px;align-items:center;padding:28px 0;border-bottom:1px solid #dbe2ea}.ppdb-schedule-row>span{font-size:9px;letter-spacing:.15em;color:#94a3b8}.ppdb-schedule-row time{font:800 48px Manrope;color:var(--pblue);letter-spacing:-.06em}.ppdb-schedule-row strong{font:800 18px Manrope}.ppdb-schedule-row p{margin:6px 0 0;color:#64748b}
.ppdb-faq{background:#eef4fb}.ppdb-faq-list{border-top:1px solid #cfd9e5}.ppdb-faq details{border-bottom:1px solid #cfd9e5}.ppdb-faq summary{list-style:none;cursor:pointer;padding:27px 0;font:800 18px Manrope;display:flex;justify-content:space-between}.ppdb-faq summary::-webkit-details-marker{display:none}.ppdb-faq summary b{font-size:22px;font-weight:400;transition:.3s}.ppdb-faq details[open] summary b{transform:rotate(45deg);color:var(--pblue)}.ppdb-faq details p{max-width:760px;color:#64748b;line-height:1.8;padding:0 0 28px;margin:0}
.ppdb-cta{min-height:650px;background:var(--pnavy);color:#fff;display:flex;align-items:center;position:relative;overflow:hidden}.ppdb-cta:before{content:"";position:absolute;inset:0;background:linear-gradient(90deg,transparent 49.9%,rgba(255,255,255,.07) 50%,transparent 50.1%);background-size:100px 100%;opacity:.3}.ppdb-cta-inner{position:relative;z-index:1}.ppdb-cta h2{font:800 clamp(60px,10vw,145px)/.82 Manrope;letter-spacing:-.08em;margin:0}.ppdb-cta h2 span{color:var(--pyellow)}.ppdb-cta p:not(.ppdb-kicker){color:rgba(255,255,255,.5);margin:25px 0 30px}.ppdb-cta-watermark{position:absolute;right:-60px;bottom:-100px;font:800 35vw/.7 Manrope;color:transparent;-webkit-text-stroke:1px rgba(255,255,255,.06);letter-spacing:-.1em}
.ppdb-reveal{opacity:0;transform:translateY(30px);transition:opacity .8s ease,transform .8s ease}.ppdb-reveal.ppdb-visible{opacity:1;transform:none}
@keyframes ppdbGrid{to{background-position:72px 72px}}@keyframes ppdbSpin{to{transform:rotate(360deg)}}@keyframes ppdbPulse{50%{box-shadow:0 0 0 8px rgba(250,204,21,.02)}}@keyframes ppdbFloat{50%{transform:translateY(-12px)}}@keyframes ppdbLine{0%,100%{width:18%}50%{width:72%}}
@media(max-width:900px){.ppdb-hero{min-height:auto;padding:120px 0 100px}.ppdb-hero-inner{grid-template-columns:1fr;gap:55px}.ppdb-hero-system{min-height:360px;max-width:520px;width:100%;margin:auto}.ppdb-system-core{height:220px}.ppdb-section{padding:90px 0}.ppdb-section-head{display:block}.ppdb-section-head h2{text-align:left;margin-top:25px}.ppdb-doc-layout{grid-template-columns:1fr}.ppdb-orbit-a{right:-300px}.ppdb-orbit-b{right:-420px}.ppdb-cta{min-height:520px}}
@media(max-width:600px){.ppdb-hero-inner{padding-top:25px}.ppdb-hero h1{font-size:42px}.ppdb-display{font-size:30px}.ppdb-actions{display:grid}.ppdb-btn{width:100%}.ppdb-editorial-item{grid-template-columns:45px 1fr 25px;gap:15px;padding:26px 0}.ppdb-editorial-item h3{font-size:18px}.ppdb-major{grid-template-columns:80px 1fr 25px;gap:10px}.ppdb-major strong{font-size:20px}.ppdb-pdf-card{grid-template-columns:1fr}.ppdb-pdf-paper{min-height:150px}.ppdb-schedule-row{grid-template-columns:40px 75px 1fr;gap:12px}.ppdb-schedule-row time{font-size:34px}.ppdb-schedule-row strong{font-size:15px}.ppdb-cta h2{font-size:62px}.ppdb-scroll-note{display:none}}
@media(prefers-reduced-motion:reduce){.ppdb-grid,.ppdb-hero-orbit,.ppdb-system-core:before,.ppdb-system-core:after,.ppdb-live b,.ppdb-system-line span{animation:none!important}.ppdb-reveal{opacity:1;transform:none;transition:none}}
</style>

<script>
(() => {
    const items = document.querySelectorAll('.ppdb-reveal');
    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('ppdb-visible');
            obs.unobserve(entry.target);
        });
    }, {threshold:.12});
    items.forEach((el, i) => { el.style.transitionDelay = `${Math.min(i % 5, 4) * 70}ms`; observer.observe(el); });

    const progress = document.querySelector('.ppdb-progress span');
    const timeline = document.querySelector('.ppdb-timeline');
    if (progress && timeline) {
        const update = () => {
            const rect = timeline.getBoundingClientRect();
            const viewport = window.innerHeight;
            const passed = Math.min(rect.height, Math.max(0, viewport * .62 - rect.top));
            progress.style.height = `${(passed / rect.height) * 100}%`;
        };
        window.addEventListener('scroll', update, {passive:true});
        window.addEventListener('resize', update);
        update();
    }
})();
</script>

<?php vb_end(); ?>
<?php require __DIR__.'/includes/public-footer.php';?>
