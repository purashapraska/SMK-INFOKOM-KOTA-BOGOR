<?php

declare(strict_types=1);

$pageTitle = 'Guru & Staff';
require_once __DIR__ . '/includes/public-header.php';

// Visual Builder integration: keep the original PHP page as the fallback.
vb_begin('guru');

require_once __DIR__ . '/config/teacher_seed.php';

/**
 * Guru page reads from the same teachers table used by Admin.
 * The seed is handled once by config/database.php for fresh/empty installs.
 */
$people = [];

try {
    $statement = $pdo->query(
        'SELECT id, name, image, position, subject, education, bio ' .
        'FROM teachers ORDER BY id ASC'
    );

    $teacherRows = $statement->fetchAll();

    foreach ($teacherRows as $row) {
        $position = trim((string) ($row['position'] ?? 'Guru'));
        $positionLower = mb_strtolower($position);

        if (
            str_contains($positionLower, 'kepala sekolah')
            && !str_contains($positionLower, 'wakil')
        ) {
            $group = 'leadership';
        } elseif (
            str_contains($positionLower, 'wakil kepala')
        ) {
            $group = 'leadership';
        } elseif (
            str_contains($positionLower, 'tata usaha')
            || str_contains($positionLower, 'bendahara')
            || str_contains($positionLower, 'perpustakaan')
            || str_contains($positionLower, 'staf')
            || str_contains($positionLower, 'staff')
            || str_contains($positionLower, 'administrasi')
        ) {
            $group = 'staff';
        } else {
            $group = 'teacher';
        }

        $image = trim((string) ($row['image'] ?? ''));
        $fallback = 'assets/images/placeholder.svg';

        if ($image !== '') {
            $fallback = $image;
        }

        $people[] = [
            'id' => (int) ($row['id'] ?? 0),
            'name' => trim((string) ($row['name'] ?? '')),
            'position' => $position,
            'field' => trim((string) ($row['subject'] ?? '')) ?: 'Pendidik',
            'group' => $group,
            'image' => $image,
            'fallback_image' => img_url($fallback),
            'subject' => trim((string) ($row['subject'] ?? '')),
            'education' => trim((string) ($row['education'] ?? '')),
            'bio' => trim((string) ($row['bio'] ?? '')),
        ];
    }
} catch (Throwable) {
    $people = [];
}

$people = array_values(
    array_filter(
        $people,
        static fn (array $person): bool => $person['name'] !== ''
    )
);

$leaders = array_values(array_filter(
    $people,
    static fn (array $person): bool => $person['group'] === 'leadership'
));

$teachers = array_values(array_filter(
    $people,
    static fn (array $person): bool => $person['group'] === 'teacher'
));

$staff = array_values(array_filter(
    $people,
    static fn (array $person): bool => $person['group'] === 'staff'
));

$heroPerson = null;
foreach ($people as $person) {
    $positionText = mb_strtolower((string) ($person['position'] ?? ''));
    $nameText = mb_strtolower((string) ($person['name'] ?? ''));
    if (str_contains($positionText, 'kepala sekolah') && !str_contains($positionText, 'wakil')) {
        $heroPerson = $person;
        break;
    }
}
$heroPerson ??= $people[0] ?? [
    'id' => 0,
    'name' => 'Tim SMK INFOKOM Kota Bogor',
    'position' => 'Pendidik & Tenaga Kependidikan',
    'field' => 'Pendidik',
    'group' => 'teacher',
    'image' => '',
    'fallback_image' => url('assets/images/placeholder.svg'),
    'subject' => 'Pendidik',
    'education' => 'Belum tersedia',
    'bio' => 'Data tenaga kependidikan belum tersedia di database.',
];
?>

<section class="guru-hero guru-editorial-hero" data-principal-name="<?= e(mb_strtolower($heroPerson['name'])) ?>">
    <div class="guru-hero-grid" aria-hidden="true"></div>
    <div class="container guru-hero-inner">
        <div class="guru-hero-copy reveal is-visible">
            <span class="guru-kicker"><i></i> Kepemimpinan SMK Infokom</span>
            <h1><?= e(cms_text('guru', 'hero_title', 'Mengenal Sosok di Balik Pendidikan.')) ?></h1>
            <p>
                <?= e(cms_text('guru', 'hero_description', 'Kepemimpinan, dedikasi, dan semangat membentuk generasi masa depan. Kenali para pendidik dan tenaga kependidikan yang mendampingi perjalanan belajar di SMK INFOKOM Kota Bogor.')) ?>
            </p>
            <div class="guru-hero-actions">
                <a class="btn btn-primary" href="#jajaran-pimpinan">Kenali Tim Sekolah <span>↓</span></a>
                <span class="guru-scroll-note">Pendidik · Penggerak · Pembimbing</span>
            </div>
        </div>
        <div class="guru-hero-visual reveal is-visible">
            <div class="guru-orbit guru-orbit-one" aria-hidden="true"></div>
            <div class="guru-orbit guru-orbit-two" aria-hidden="true"></div>
            <div class="guru-principal-frame">
                <span class="guru-principal-frame-accent" aria-hidden="true"></span>
                <div class="guru-hero-photo">
                    <img src="<?= e($heroPerson['fallback_image']) ?>" alt="<?= e($heroPerson['name']) ?>" fetchpriority="high">
                    <div class="guru-hero-caption">
                        <span>Kepala Sekolah</span>
                        <strong><?= e($heroPerson['name']) ?></strong>
                    </div>
                </div>
                <span class="guru-principal-seal" aria-hidden="true">SMK<br>INFOKOM</span>
            </div>
        </div>
    </div>
</section>

<section class="section guru-people-directory" id="jajaran-pimpinan">
    <div class="container">
        <div class="guru-directory-intro reveal">
            <span class="eyebrow">Tenaga Pendidik & Kependidikan</span>
            <h2>Orang-orang di balik<br><em>setiap proses belajar.</em></h2>
            <p>Jelajahi jajaran pimpinan, guru, dan staf sekolah. Gunakan pencarian untuk menemukan personel berdasarkan nama atau jabatan.</p>
        </div>

        <div class="guru-toolbar reveal">
            <label class="guru-search">
                <span aria-hidden="true">⌕</span>
                <input id="guruSearch" type="search" placeholder="Cari nama atau jabatan..." autocomplete="off" aria-label="Cari nama atau jabatan personel">
            </label>
            <span class="guru-search-hint">Pencarian langsung</span>
        </div>
        <div class="guru-empty" id="guruEmpty" hidden>Tidak ada personel yang cocok dengan pencarian.</div>

        <section class="guru-person-group" aria-labelledby="guruLeadershipTitle">
            <div class="guru-group-heading reveal">
                <span class="guru-group-index">01</span>
                <div><span class="eyebrow">Jajaran Pimpinan</span><h2 id="guruLeadershipTitle">Mengarah bersama.</h2></div>
                <p>Tim pimpinan yang mendukung arah dan pengelolaan sekolah.</p>
            </div>
            <div class="guru-leader-grid guru-group-grid">
                <?php $leaderIndex = 0; ?>
                <?php foreach ($leaders as $person): ?>
                    <?php if ((int) $person['id'] === (int) ($heroPerson['id'] ?? -1)) continue; ?>
                    <?php $leaderIndex++; ?>
                    <article class="guru-leader-card guru-person-card reveal" data-group="leadership" data-name="<?= e(mb_strtolower($person['name'] . ' ' . $person['position'])) ?>" data-person='<?= e(json_encode($person, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT)) ?>'>
                        <div class="guru-card-photo">
                            <img src="<?= e($person['fallback_image']) ?>" alt="<?= e($person['name']) ?>" loading="lazy">
                            <span class="guru-card-number"><?= str_pad((string) $leaderIndex, 2, '0', STR_PAD_LEFT) ?></span>
                            <button class="guru-view" type="button">Lihat Profil <span>↗</span></button>
                        </div>
                        <div class="guru-card-body"><span><?= e($person['field']) ?></span><h3><?= e($person['name']) ?></h3><p><?= e($person['position']) ?></p></div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="guru-person-group" aria-labelledby="guruTeachersTitle">
            <div class="guru-group-heading reveal">
                <span class="guru-group-index">02</span>
                <div><span class="eyebrow">Tenaga Pendidik</span><h2 id="guruTeachersTitle">Para guru.</h2></div>
                <p>Para pendidik yang mendampingi proses belajar dan pengembangan kompetensi siswa.</p>
            </div>
            <div class="guru-grid guru-group-grid">
                <?php $teacherIndex = 0; ?>
                <?php foreach ($teachers as $person): ?>
                    <?php $teacherIndex++; ?>
                    <article class="guru-card guru-person-card reveal" data-group="teacher" data-name="<?= e(mb_strtolower($person['name'] . ' ' . $person['position'] . ' ' . $person['subject'])) ?>" data-person='<?= e(json_encode($person, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT)) ?>'>
                        <div class="guru-card-photo"><img src="<?= e($person['fallback_image']) ?>" alt="<?= e($person['name']) ?>" loading="lazy"><div class="guru-photo-shade"></div><span class="guru-card-number"><?= str_pad((string) $teacherIndex, 2, '0', STR_PAD_LEFT) ?></span><button class="guru-view" type="button">Lihat Profil <span>↗</span></button></div>
                        <div class="guru-card-body"><span><?= e($person['subject'] ?: $person['field']) ?></span><h3><?= e($person['name']) ?></h3><p><?= e($person['position']) ?></p></div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="guru-person-group" aria-labelledby="guruStaffTitle">
            <div class="guru-group-heading reveal">
                <span class="guru-group-index">03</span>
                <div><span class="eyebrow">Tenaga Kependidikan</span><h2 id="guruStaffTitle">Staf & tenaga pendukung.</h2></div>
                <p>Personel pendukung yang membantu operasional dan layanan sekolah.</p>
            </div>
            <div class="guru-grid guru-group-grid">
                <?php $staffIndex = 0; ?>
                <?php foreach ($staff as $person): ?>
                    <?php $staffIndex++; ?>
                    <article class="guru-card guru-person-card reveal" data-group="staff" data-name="<?= e(mb_strtolower($person['name'] . ' ' . $person['position'] . ' ' . $person['subject'])) ?>" data-person='<?= e(json_encode($person, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT)) ?>'>
                        <div class="guru-card-photo"><img src="<?= e($person['fallback_image']) ?>" alt="<?= e($person['name']) ?>" loading="lazy"><div class="guru-photo-shade"></div><span class="guru-card-number"><?= str_pad((string) $staffIndex, 2, '0', STR_PAD_LEFT) ?></span><button class="guru-view" type="button">Lihat Profil <span>↗</span></button></div>
                        <div class="guru-card-body"><span><?= e($person['subject'] ?: $person['field']) ?></span><h3><?= e($person['name']) ?></h3><p><?= e($person['position']) ?></p></div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</section>

<section class="section guru-closing">
    <div class="container guru-closing-inner reveal">
        <span class="eyebrow">Bersama</span>
        <h2>Setiap nama punya cerita.<br><em>Setiap pendidik meninggalkan jejak.</em></h2>
        <a class="btn btn-primary" href="<?= e(url('kontak.php')) ?>">Hubungi Sekolah <span>↗</span></a>
    </div>
</section>

<div class="guru-modal" id="guruModal" aria-hidden="true">
    <div class="guru-modal-backdrop" data-close-guru></div>
    <div class="guru-modal-panel" role="dialog" aria-modal="true" aria-labelledby="guruModalName" tabindex="-1">
        <button class="guru-modal-close" type="button" aria-label="Tutup" data-close-guru>×</button>
        <div class="guru-modal-photo"><img id="guruModalPhoto" src="" alt=""></div>
        <div class="guru-modal-content">
            <span id="guruModalField" class="eyebrow"></span><h2 id="guruModalName"></h2>
            <p id="guruModalPosition" class="guru-modal-position"></p><div class="guru-modal-rule"></div>
            <div class="guru-modal-details"><div><span>Pendidikan</span><strong id="guruModalEducation">Belum tersedia</strong></div><div><span>Bidang / Mata Pelajaran</span><strong id="guruModalFieldDetail">Belum tersedia</strong></div></div>
            <p id="guruModalDescription" class="guru-modal-description"></p>
        </div>
    </div>
</div>

<?php vb_end(); ?>

<?php require_once __DIR__ . '/includes/public-footer.php'; ?>
