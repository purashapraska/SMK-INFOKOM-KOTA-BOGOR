<?php

declare(strict_types=1);

$pageTitle = 'Kontak';

require_once __DIR__ . '/includes/public-header.php';

$sent = false;
$error = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validToken = verify_csrf($_POST['_csrf'] ?? null);
    $name = post_string('name');
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $phone = post_string('phone');
    $subject = post_string('subject');
    $message = post_string('message');

    if ($validToken && $name && $email && $message) {
        $statement = $pdo->prepare(
            'INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)'
        );
        $statement->execute([$name, $email, $phone, $subject, $message]);
        $sent = true;
    } else {
        $error = true;
    }
}

$address = setting(
    'school_address',
    setting('location_address', 'Jl. Letjen Ibrahim Adjie No.178, RT.03/RW.08, Sindangbarang, Kecamatan Bogor Barat, Kota Bogor, Jawa Barat 16117.')
);
$phone = setting('school_phone', '-');
$email = setting('school_email', '-');
$waRaw = setting('whatsapp_number', '');
$wa = preg_replace('/\D+/', '', $waRaw) ?? '';
$waMessage = setting('whatsapp_message', 'Halo, saya ingin mendapatkan informasi tentang SMK Infokom Kota Bogor.');
$waLink = $wa !== ''
    ? 'https://wa.me/' . $wa . '?text=' . rawurlencode($waMessage)
    : url('kontak.php');
$ig = setting('instagram_url', '');
$youtube = setting('youtube_url', '');
$lat = setting('location_latitude', '-6.597300000000');
$lng = setting('location_longitude', '106.779300000000');
$mapsEmbed = google_maps_embed_url('', '', $address);
$mapsQuery = $address;
$mapsLink = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($mapsQuery);
$directionsLink = 'https://www.google.com/maps/dir/?api=1&destination=' . rawurlencode($address);
$hoursWeekday = setting('school_hours_weekday', 'Senin – Jumat · 07.00 – 17.00');
$hoursSaturday = setting('school_hours_saturday', 'Sabtu · 07.00 – 12.30');
$hoursSunday = setting('school_hours_sunday', 'Minggu / Hari Libur Nasional · Tutup');

vb_begin('kontak');
?>

<section class="contact-hero">
    <div class="contact-grid-bg" aria-hidden="true"></div>
    <div class="contact-hero-orbit orbit-a" aria-hidden="true"></div>
    <div class="contact-hero-orbit orbit-b" aria-hidden="true"></div>
    <div class="contact-hero-line line-a" aria-hidden="true"></div>
    <div class="contact-hero-line line-b" aria-hidden="true"></div>

    <div class="container contact-hero-inner">
        <div class="contact-hero-copy reveal">
            <p class="contact-kicker"><span>01</span> / CONTACT</p>
            <h1><?= e(cms_text('kontak', 'hero_title', "LET'S CONNECT.")) ?></h1>
            <p class="contact-hero-text">
                <?= e(cms_text('kontak', 'hero_description', 'Temukan lokasi, hubungi kami, dan dapatkan informasi yang kamu butuhkan tentang SMK Infokom Kota Bogor.')) ?>
            </p>
            <?php vb_dyn_open('contact-actions-hero', 'div', 'class="contact-actions"'); ?>
                <a class="btn btn-primary" href="<?= e($mapsLink) ?>" target="_blank" rel="noopener noreferrer">Open Google Maps <span>↗</span></a>
                <a class="btn btn-outline contact-outline-light" href="<?= e($waLink) ?>" target="_blank" rel="noopener noreferrer">WhatsApp <span>↗</span></a>
            <?php vb_dyn_close(); ?>
        </div>

        <div class="contact-location-visual reveal" aria-label="Visual lokasi SMK Infokom Kota Bogor">
            <div class="location-coordinates"><?= e($lat) ?> / <?= e($lng) ?></div>
            <div class="location-status"><span></span> LOCATION ACTIVE</div>
            <div class="location-orbit location-orbit-one"></div>
            <div class="location-orbit location-orbit-two"></div>
            <div class="location-cross cross-one"></div>
            <div class="location-cross cross-two"></div>
            <div class="location-pin-wrap">
                <div class="location-ripple"></div>
                <div class="location-pin"><span></span></div>
            </div>
            <div class="location-label">
                <small>SMK INFOKOM</small>
                <strong>KOTA BOGOR</strong>
                <span>WEST JAVA · INDONESIA</span>
            </div>
            <div class="location-tag tag-one">SCHOOL</div>
            <div class="location-tag tag-two">BOGOR</div>
            <div class="location-tag tag-three">MAP / 01</div>
        </div>
    </div>
</section>

<section class="contact-section contact-find">
    <div class="container">
        <div class="contact-section-head reveal">
            <p class="contact-kicker dark"><span>02</span> / FIND US</p>
            <h2>Find the place.<br><strong>Start the conversation.</strong></h2>
        </div>

        <?php vb_dyn_open('contact-find', 'div', 'class="contact-find-grid contact-find-grid-premium"'); ?>
            <div class="contact-info-panel contact-info-panel-premium reveal">
                <div class="contact-info-title"><span class="contact-location-index">06 / LOCATION</span><span class="contact-location-accent" aria-hidden="true"></span>SMK INFOKOM<br><strong>KOTA BOGOR</strong></div>
                <div class="contact-info-list">
                    <div class="contact-info-row">
                        <span class="contact-info-number">01</span>
                        <div><small>ADDRESS</small><p class="contact-copy-address-text"><?= nl2br(e($address)) ?></p></div>
                    </div>
                    <div class="contact-info-row">
                        <span class="contact-info-number">02</span>
                        <div><small>PHONE</small><p><?= e($phone) ?></p></div>
                    </div>
                    <div class="contact-info-row">
                        <span class="contact-info-number">03</span>
                        <div><small>WHATSAPP</small><p><?= e($waRaw !== '' ? $waRaw : '-') ?></p></div>
                    </div>
                    <div class="contact-info-row">
                        <span class="contact-info-number">04</span>
                        <div><small>EMAIL</small><p><?= e($email) ?></p></div>
                    </div>
                </div>
                <div class="contact-location-actions">
                    <button class="contact-copy-address" type="button" data-copy-address="<?= e($address) ?>"><span class="copy-icon" aria-hidden="true">▣</span><span data-copy-label>Salin Alamat</span></button>
                    <a class="contact-directions-link" href="<?= e($directionsLink) ?>" target="_blank" rel="noopener noreferrer">Petunjuk Arah <span>↗</span></a>
                    <a class="contact-map-link" href="<?= e($mapsLink) ?>" target="_blank" rel="noopener noreferrer">Buka Google Maps <span>↗</span></a>
                </div>
            </div>

            <div class="contact-map-panel contact-map-panel-premium reveal">
                <?php if ($mapsEmbed): ?>
                    <iframe src="<?= e($mapsEmbed) ?>" title="Lokasi SMK Infokom Kota Bogor di Google Maps" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php else: ?>
                    <div class="contact-map-fallback"><span>LOCATION</span><strong>Google Maps belum tersedia.</strong></div>
                <?php endif; ?>
                <div class="map-corner map-corner-tl"><span class="map-live-dot"></span> LIVE MAP / BOGOR</div>
                <a class="map-corner map-corner-br" href="<?= e($mapsLink) ?>" target="_blank" rel="noopener noreferrer">OPEN IN GOOGLE MAPS ↗</a>
            </div>
        <?php vb_dyn_close(); ?>
    </div>
</section>

<section class="contact-section contact-channels contact-channels-redesign">
    <div class="container">
        <div class="contact-section-head reveal">
            <p class="contact-kicker dark"><span>03</span> / CONTACT CHANNELS</p>
            <h2>Choose your<br><strong>way to connect.</strong></h2>
            <p class="contact-channels-intro">Pilih cara yang paling nyaman untuk menghubungi tim SMK Infokom Kota Bogor.</p>
        </div>
        <div class="contact-connect-layout">
            <?php vb_dyn_open('contact-channels', 'div', 'class="contact-connect-cards"'); ?>
                <article class="contact-connect-card contact-connect-whatsapp reveal">
                    <div class="contact-connect-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20.5 11.8a8.5 8.5 0 0 1-12.6 7.4L3 20.5l1.4-4.7a8.5 8.5 0 1 1 16.1-4Z"/><path d="M8.6 8.1c.2-.5.5-.5.8-.5h.4c.2 0 .4.1.5.4l.8 1.8c.1.2.1.4 0 .6l-.6.7c-.2.2-.2.4 0 .6.5.9 1.3 1.6 2.2 2.1.2.1.4.1.6-.1l.8-.9c.2-.2.4-.2.6-.1l1.7.8c.3.1.4.3.4.5 0 .4-.2 1.2-.7 1.6-.5.5-1.2.7-2 .5-1-.2-2.3-.8-3.7-2.1-1.2-1.1-2-2.5-2.2-3.5-.2-.9.1-1.6.4-2Z"/></svg></div>
                    <p class="contact-connect-label">WHATSAPP HUMAS</p>
                    <h3><?= e($waRaw !== '' ? $waRaw : 'Hubungi Humas') ?></h3>
                    <p class="contact-connect-description">Tanyakan informasi sekolah secara langsung melalui WhatsApp.</p>
                    <a class="contact-connect-button" href="<?= e($waLink) ?>" target="_blank" rel="noopener noreferrer">Chat WhatsApp <span>↗</span></a>
                </article>
                <article class="contact-connect-card contact-connect-email reveal">
                    <div class="contact-connect-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg></div>
                    <p class="contact-connect-label">EMAIL SEKOLAH</p>
                    <h3><?= e($email !== '-' && $email !== '' ? $email : 'Email belum tersedia') ?></h3>
                    <p class="contact-connect-description">Kirim pertanyaan atau pesan resmi kepada pihak sekolah.</p>
                    <?php if (filter_var($email, FILTER_VALIDATE_EMAIL)): ?><a class="contact-connect-button" href="mailto:<?= e($email) ?>">Kirim Email <span>↗</span></a><?php else: ?><span class="contact-connect-button is-disabled" aria-disabled="true">Email belum tersedia</span><?php endif; ?>
                </article>
            <?php vb_dyn_close(); ?>
            <div class="contact-connect-visual reveal" aria-label="Ilustrasi tiga dimensi smartphone dan gelembung pesan">
                <div class="connect-visual-glow"></div>
                <div class="connect-orbit connect-orbit-one"></div><div class="connect-orbit connect-orbit-two"></div>
                <div class="connect-floating-bubble bubble-message"><span></span><span></span><span></span></div>
                <div class="connect-floating-bubble bubble-mail"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/></svg></div>
                <div class="connect-phone-shadow"></div>
                <div class="connect-phone"><div class="connect-phone-side"></div><div class="connect-phone-screen"><div class="connect-phone-notch"></div><div class="connect-screen-top"><span>09:41</span><span>● ● ▰</span></div><div class="connect-screen-avatar">I</div><strong>Infokom Humas</strong><small>online</small><div class="connect-chat chat-in">Halo! Ada yang bisa kami bantu?</div><div class="connect-chat chat-out">Saya ingin bertanya.</div><div class="connect-chat chat-in short"></div><div class="connect-screen-input"><span>Message...</span><b>➤</b></div></div></div>
                <div class="connect-floating-bubble bubble-check">✓</div>
                <span class="connect-visual-caption">LET'S TALK <i></i> WE'RE HERE TO HELP</span>
            </div>
        </div>
    </div>
</section>

<section class="contact-section contact-hours contact-hours-premium" aria-labelledby="contact-hours-title">
    <div class="contact-hours-glow contact-hours-glow-one" aria-hidden="true"></div>
    <div class="contact-hours-glow contact-hours-glow-two" aria-hidden="true"></div>
    <div class="container contact-hours-container">
        <div class="contact-hours-editorial reveal">
            <p class="contact-kicker dark"><span>05</span> / OFFICE HOURS</p>
            <h2 id="contact-hours-title">Know when<br><strong>we're here.</strong></h2>
            <p class="contact-hours-intro">Plan your visit around our school hours. We look forward to welcoming you.</p>
            <div class="contact-hours-note"><span class="contact-hours-note-dot" aria-hidden="true"></span><span>All times are shown in Western Indonesia Time.</span></div>
        </div>
        <div class="contact-hours-dashboard reveal">
            <div class="contact-clock-card">
                <div class="contact-clock-topline"><span><i aria-hidden="true"></i> LOCAL TIME</span><span>WIB · UTC+7</span></div>
                <time class="contact-live-clock" id="contactLiveClock" datetime="">--:--:--</time>
                <div class="contact-clock-bottom"><span id="contactLiveDay">Memuat hari…</span><span id="contactLiveDate">Memuat tanggal…</span></div>
                <div class="contact-clock-orbit" aria-hidden="true"><span></span></div>
            </div>
            <div class="contact-hours-schedule">
                <div class="contact-hours-schedule-head"><div><span class="contact-schedule-index">SCHEDULE / 01</span><h3>Operating schedule</h3></div><span class="contact-schedule-badge">WEEKLY</span></div>
                <?php vb_dyn_open('contact-hours', 'div', 'class="contact-hours-timeline"'); ?>
                    <div class="contact-hours-row"><span class="contact-hours-marker" aria-hidden="true"></span><div class="contact-hours-day"><span>MON — FRI</span><small>Weekdays</small></div><strong><?= e($hoursWeekday) ?></strong></div>
                    <div class="contact-hours-row"><span class="contact-hours-marker" aria-hidden="true"></span><div class="contact-hours-day"><span>SATURDAY</span><small>Weekend service</small></div><strong><?= e($hoursSaturday) ?></strong></div>
                    <div class="contact-hours-row is-closed"><span class="contact-hours-marker" aria-hidden="true"></span><div class="contact-hours-day"><span>SUNDAY / HOLIDAYS</span><small>School is closed</small></div><strong><?= e($hoursSunday) ?></strong></div>
                <?php vb_dyn_close(); ?>
                <p class="contact-hours-footnote"><span aria-hidden="true">✳</span> National holidays follow the official holiday calendar.</p>
            </div>
        </div>
    </div>
</section>
<script>
(() => {
    const clock = document.getElementById('contactLiveClock');
    const day = document.getElementById('contactLiveDay');
    const date = document.getElementById('contactLiveDate');
    if (!clock || !day || !date) return;
    const timeFormatter = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
    const dayFormatter = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', weekday: 'long' });
    const dateFormatter = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', day: 'numeric', month: 'long', year: 'numeric' });
    const updateClock = () => {
        const now = new Date();
        clock.textContent = timeFormatter.format(now).replace(/\./g, ':');
        clock.dateTime = now.toISOString();
        day.textContent = dayFormatter.format(now);
        date.textContent = dateFormatter.format(now);
    };
    updateClock();
    window.setInterval(updateClock, 1000);
})();
</script>

<section class="contact-section contact-faq">
    <div class="container">
        <div class="contact-section-head reveal">
            <p class="contact-kicker dark"><span>06</span> / FAQ</p>
            <h2>Questions,<br><strong>answered.</strong></h2>
        </div>
        <div class="contact-faq-list">
            <details class="contact-faq-item reveal"><summary><span>01</span> Bagaimana cara menuju SMK Infokom?</summary><p>Gunakan tombol Google Maps pada halaman ini untuk membuka lokasi sekolah langsung di Google Maps.</p></details>
            <details class="contact-faq-item reveal"><summary><span>02</span> Bagaimana cara menghubungi sekolah?</summary><p>Kamu dapat menghubungi sekolah melalui telepon, WhatsApp, email, atau formulir kontak yang tersedia.</p></details>
            <details class="contact-faq-item reveal"><summary><span>03</span> Apakah sekolah dapat dihubungi melalui WhatsApp?</summary><p>Ya. Tombol WhatsApp akan membuka percakapan langsung dengan nomor WhatsApp yang dikelola dari pengaturan website.</p></details>
            <details class="contact-faq-item reveal"><summary><span>04</span> Di mana lokasi sekolah?</summary><p>Lokasi sekolah ditampilkan pada peta Google Maps di section Find Us dan mengikuti data lokasi dari pengaturan website.</p></details>
        </div>
    </div>
</section>

<section class="contact-final-cta">
    <div class="contact-cta-word" aria-hidden="true">CONTACT</div>
    <div class="container contact-cta-inner reveal">
        <p class="contact-kicker"><span>07</span> / CONNECT WITH US</p>
        <h2>NEED MORE<br><strong>INFORMATION?</strong></h2>
        <p>We're here to help.</p>
        <?php vb_dyn_open('contact-actions', 'div', 'class="contact-actions"'); ?>
            <a class="btn btn-primary" href="<?= e($waLink) ?>" target="_blank" rel="noopener noreferrer">WhatsApp <span>↗</span></a>
            <a class="btn btn-outline contact-outline-light" href="<?= e($mapsLink) ?>" target="_blank" rel="noopener noreferrer">Google Maps <span>↗</span></a>
        <?php vb_dyn_close(); ?>
    </div>
</section>

<?php vb_end(); ?>
<?php require __DIR__ . '/includes/public-footer.php'; ?>
