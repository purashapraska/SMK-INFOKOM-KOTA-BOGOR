<?php

declare(strict_types=1);

$pageTitle = 'Ekstrakurikuler';
require __DIR__ . '/includes/public-header.php';

$rows = $pdo->query('SELECT * FROM extracurriculars ORDER BY id ASC')->fetchAll();

vb_begin('ekstrakurikuler');
?>

<main class="ekskul-page">
    <section class="ekskul-hero">
        <div class="ekskul-hero-grid"></div>
        <div class="ekskul-hero-orbit ekskul-orbit-one"></div>
        <div class="ekskul-hero-orbit ekskul-orbit-two"></div>
        <span class="ekskul-hero-number">06</span>
        <span class="ekskul-hero-code ekskul-code-one">DISCIPLINE</span>
        <span class="ekskul-hero-code ekskul-code-two">TEAMWORK</span>
        <span class="ekskul-hero-code ekskul-code-three">CULTURE</span>
        <span class="ekskul-hero-code ekskul-code-four">CREATIVITY</span>

        <div class="container ekskul-hero-inner reveal">
            <div class="ekskul-hero-copy">
                <p class="eyebrow">01 / EXTRACURRICULAR</p>
                <h1><?= e(cms_text('kesiswaan', 'hero_title', 'FIND YOUR PLACE TO GROW.')) ?></h1>
                <p class="ekskul-hero-description">
                    <?= e(cms_text('kesiswaan', 'hero_description', 'Ruang untuk mengembangkan minat, bakat, karakter, dan pengalaman bersama komunitas sekolah.')) ?>
                </p>
                <a class="ekskul-hero-link" href="#activities">
                    <span>EXPLORE ACTIVITIES</span>
                    <b>↘</b>
                </a>
            </div>

            <div class="ekskul-hero-stage" aria-hidden="true">
                <div class="ekskul-stage-ring ring-a"></div>
                <div class="ekskul-stage-ring ring-b"></div>
                <div class="ekskul-stage-core">
                    <span>06</span>
                    <small>ACTIVITIES</small>
                </div>
                <span class="ekskul-stage-node node-1">01</span>
                <span class="ekskul-stage-node node-2">02</span>
                <span class="ekskul-stage-node node-3">03</span>
                <span class="ekskul-stage-node node-4">04</span>
                <span class="ekskul-stage-node node-5">05</span>
                <span class="ekskul-stage-node node-6">06</span>
            </div>
        </div>
    </section>

    <section class="ekskul-intro section">
        <div class="container ekskul-section-head reveal">
            <div>
                <p class="eyebrow">02 / EXPLORE ACTIVITIES</p>
                <h2>ENAM RUANG.<br><span>SATU PERJALANAN.</span></h2>
            </div>
            <p>
                Setiap kegiatan menjadi ruang untuk mencoba, berlatih, berkolaborasi,
                dan tumbuh bersama.
            </p>
        </div>

        <?php vb_dyn_open('activities', 'div', 'class="container ekskul-list" id="activities"'); ?>
            <?php foreach ($rows as $index => $row): ?>
                <?php
                    $number = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
                    $name = (string) ($row['name'] ?? 'Ekstrakurikuler');
                    $description = (string) ($row['description'] ?? '');
                    $image = trim((string) ($row['image'] ?? ''));
                ?>
                <article class="ekskul-item reveal" data-index="<?= e($index + 1) ?>">
                    <div class="ekskul-item-number"><?= e($number) ?></div>
                    <div class="ekskul-item-logo-wrap">
                        <?php if ($image !== ''): ?>
                            <img class="ekskul-item-logo" src="<?= e(img_url($image)) ?>" alt="Logo <?= e($name) ?>" loading="lazy">
                        <?php else: ?>
                            <div class="ekskul-logo-placeholder" aria-hidden="true">
                                <span><?= e(mb_strtoupper(mb_substr($name, 0, 1))) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="ekskul-item-content">
                        <p class="ekskul-item-kicker">ACTIVITY / <?= e($number) ?></p>
                        <h3><?= e($name) ?></h3>
                        <p><?= e($description) ?></p>
                    </div>
                    <div class="ekskul-item-arrow" aria-hidden="true">↗</div>
                    <div class="ekskul-item-line"></div>
                </article>
            <?php endforeach; ?>
        <?php vb_dyn_close(); ?>
    </section>

    <section class="ekskul-growth section">
        <div class="ekskul-growth-bg">GROW</div>
        <div class="container">
            <div class="ekskul-section-head ekskul-growth-head reveal">
                <div>
                    <p class="eyebrow">03 / SIX WAYS TO GROW</p>
                    <h2>BUILD SKILLS<br><span>BEYOND THE CLASSROOM.</span></h2>
                </div>
            </div>

            <div class="ekskul-growth-grid">
                <?php
                $growth = [
                    ['DISCIPLINE', 'PASKIBRA', '01'],
                    ['INDEPENDENCE', 'PRAMUKA', '02'],
                    ['CARE', 'PMR', '03'],
                    ['CULTURE', 'JAPAN INDONESIA COMMUNITY', '04'],
                    ['TEAMWORK', 'FUTSAL', '05'],
                    ['CREATIVITY', 'HADROH', '06'],
                ];
                foreach ($growth as [$quality, $activity, $number]):
                ?>
                    <div class="ekskul-growth-card reveal">
                        <span><?= e($number) ?></span>
                        <div>
                            <small><?= e($quality) ?></small>
                            <strong><?= e($activity) ?></strong>
                        </div>
                        <i></i>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="ekskul-journey section">
        <div class="container">
            <div class="ekskul-section-head reveal">
                <div>
                    <p class="eyebrow">04 / ACTIVITY JOURNEY</p>
                    <h2>DISCOVER.<br>JOIN. <span>GROW.</span></h2>
                </div>
                <p>
                    Mulai dari rasa ingin tahu, temukan komunitas yang cocok, lalu berkembang
                    melalui pengalaman bersama.
                </p>
            </div>

            <div class="ekskul-journey-track">
                <div class="ekskul-journey-progress"></div>
                <?php
                $steps = [
                    ['01', 'DISCOVER', 'Temukan kegiatan yang paling dekat dengan minatmu.'],
                    ['02', 'JOIN', 'Bergabung dan kenali lingkungan komunitasnya.'],
                    ['03', 'PRACTICE', 'Latih kemampuan melalui kegiatan bersama.'],
                    ['04', 'GROW', 'Bangun pengalaman, karakter, dan kepercayaan diri.'],
                ];
                foreach ($steps as [$number, $title, $text]):
                ?>
                    <div class="ekskul-journey-step reveal">
                        <span class="ekskul-journey-node"><?= e($number) ?></span>
                        <div>
                            <small>STEP <?= e($number) ?></small>
                            <h3><?= e($title) ?></h3>
                            <p><?= e($text) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="ekskul-cta" aria-labelledby="ekskul-cta-title">
        <div class="ekskul-cta-lines" aria-hidden="true"></div>
        <div class="ekskul-cta-orbit ekskul-cta-orbit-one" aria-hidden="true"></div>
        <div class="ekskul-cta-orbit ekskul-cta-orbit-two" aria-hidden="true"></div>
        <div class="container ekskul-cta-inner reveal">
            <p class="eyebrow ekskul-cta-eyebrow">05 / YOUR SPACE</p>
            <h2 id="ekskul-cta-title"><span class="ekskul-cta-line">YOUR INTEREST</span><span class="ekskul-cta-line">CAN BECOME</span><span class="ekskul-cta-line ekskul-cta-highlight">YOUR STRENGTH.</span></h2>
            <p class="ekskul-cta-description">Explore your interests. Build your potential.</p>
            <a class="ekskul-cta-button" href="#activities">
                <span>Explore Activities</span>
                <span class="ekskul-cta-button-icon" aria-hidden="true">↗</span>
            </a>
        </div>
        <div class="ekskul-cta-word" aria-hidden="true">EXPLORE</div>
    </section>
</main>

<script>
(() => {
    const hero = document.querySelector('.ekskul-page .ekskul-hero');
    if (!hero || window.matchMedia('(prefers-reduced-motion: reduce)').matches || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
    let frame = 0;
    hero.addEventListener('pointermove', (event) => {
        if (frame) cancelAnimationFrame(frame);
        frame = requestAnimationFrame(() => {
            const bounds = hero.getBoundingClientRect();
            const x = (event.clientX - bounds.left) / bounds.width - 0.5;
            const y = (event.clientY - bounds.top) / bounds.height - 0.5;
            hero.style.setProperty('--ek-pointer-x', `${x * 10}px`);
            hero.style.setProperty('--ek-pointer-y', `${y * 8}px`);
        });
    }, { passive: true });
    hero.addEventListener('pointerleave', () => {
        hero.style.setProperty('--ek-pointer-x', '0px');
        hero.style.setProperty('--ek-pointer-y', '0px');
    }, { passive: true });
})();
</script>

<?php vb_end(); ?>
<?php require __DIR__ . '/includes/public-footer.php'; ?>
