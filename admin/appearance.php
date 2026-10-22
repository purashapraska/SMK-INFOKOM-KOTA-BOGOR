<?php

declare(strict_types=1);

$title = 'Tampilan Website';
require_once __DIR__ . '/../config/app.php';
require_login();

$pages = [
    'home' => 'Home',
    'profil' => 'Profil',
    'jurusan' => 'Jurusan',
    'guru' => 'Guru',
    'informasi' => 'Informasi / Berita',
    'prestasi' => 'Prestasi',
    'kesiswaan' => 'Kesiswaan / Ekstrakurikuler',
    'fasilitas' => 'Fasilitas',
    'program-karier' => 'Program & Karier',
    'mitra' => 'Mitra',
    'galeri' => 'Galeri',
    'ppdb' => 'PPDB',
    'kontak' => 'Kontak',
];
$fonts = ['DM Sans', 'Inter', 'Poppins', 'Roboto', 'Nunito Sans', 'Lato', 'Source Sans 3', 'Manrope'];
$fields = [
    'heading_color' => ['label' => 'Warna judul', 'type' => 'color', 'default' => '#142230'],
    'text_color' => ['label' => 'Warna teks dan paragraf', 'type' => 'color', 'default' => '#475569'],
    'background_color' => ['label' => 'Warna background halaman', 'type' => 'color', 'default' => '#FBFCFD'],
    'button_color' => ['label' => 'Warna tombol', 'type' => 'color', 'default' => '#0F4C81'],
    'button_text_color' => ['label' => 'Warna teks tombol', 'type' => 'color', 'default' => '#FFFFFF'],
    'heading_font' => ['label' => 'Font judul', 'type' => 'font', 'default' => 'Manrope'],
    'body_font' => ['label' => 'Font teks', 'type' => 'font', 'default' => 'DM Sans'],
];
$scope = ($_GET['scope'] ?? 'global') === 'page' ? 'page' : 'global';
$pageKey = (string) ($_GET['page'] ?? 'home');
if (!isset($pages[$pageKey])) $pageKey = 'home';
$prefix = $scope === 'global' ? 'visual_global_' : 'visual_page_' . $pageKey . '_';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['_csrf'] ?? null)) {
        flash('error', 'Token keamanan tidak valid. Silakan coba lagi.');
        redirect('admin/appearance.php?scope=' . $scope . ($scope === 'page' ? '&page=' . rawurlencode($pageKey) : ''));
    }
    try {
        foreach ($fields as $key => $field) {
            $value = trim((string) ($_POST[$key] ?? ''));
            if ($scope === 'page' && (isset($_POST['inherit_' . $key]) || $value === '')) {
                save_setting($prefix . $key, '');
                continue;
            }
            if ($field['type'] === 'color') {
                $value = safe_color($value, $field['default']);
            } elseif ($field['type'] === 'font' && !in_array($value, $fonts, true)) {
                $value = $field['default'];
            }
            save_setting($prefix . $key, $value);
        }
        log_activity('appearance', 'Pengaturan tampilan ' . ($scope === 'global' ? 'global' : 'halaman ' . $pages[$pageKey]) . ' diperbarui');
        flash('success', 'Pengaturan tampilan berhasil disimpan.');
    } catch (Throwable $exception) {
        flash('error', 'Pengaturan gagal disimpan. Periksa koneksi database.');
    }
    redirect('admin/appearance.php?scope=' . $scope . ($scope === 'page' ? '&page=' . rawurlencode($pageKey) : ''));
}

$success = flash('success');
$error = flash('error');
require __DIR__ . '/_header.php';
?>
<section class="panel">
    <div class="panel-heading"><div><p class="eyebrow">VISUAL CMS</p><h2>Pengaturan tampilan website</h2><p class="panel-subtitle">Atur warna dan font dari admin. Pengaturan per halaman dapat mengikuti nilai global atau memakai nilai khusus.</p></div></div>
    <?php if ($success): ?><div class="notice success"><?= e($success) ?></div><?php endif; ?>
    <?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
    <div class="appearance-tabs">
        <a class="button <?= $scope === 'global' ? 'button-primary' : 'button-muted' ?>" href="<?= e(url('admin/appearance.php?scope=global')) ?>">Default Global</a>
        <a class="button <?= $scope === 'page' ? 'button-primary' : 'button-muted' ?>" href="<?= e(url('admin/appearance.php?scope=page&page=' . rawurlencode($pageKey))) ?>">Per Halaman</a>
    </div>
    <?php if ($scope === 'page'): ?>
    <form method="get" class="appearance-page-picker">
        <input type="hidden" name="scope" value="page">
        <label>Pilih halaman<select name="page" onchange="this.form.submit()"><?php foreach ($pages as $key => $label): ?><option value="<?= e($key) ?>" <?= $pageKey === $key ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?></select></label>
        <p class="form-hint">Kosongkan nilai untuk menggunakan pengaturan global. Pengaturan ini tidak memengaruhi halaman lain.</p>
    </form>
    <?php endif; ?>
    <form method="post" class="admin-form appearance-form">
        <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
        <div class="form-grid-admin">
        <?php foreach ($fields as $key => $field):
            $stored = setting($prefix . $key, $scope === 'global' ? $field['default'] : '');
            $display = $stored !== '' ? $stored : ($scope === 'page' ? '' : $field['default']);
            $effective = $stored !== '' ? $stored : setting('visual_global_' . $key, $field['default']);
        ?>
            <label>
                <?= e($field['label']) ?>
                <?php if ($field['type'] === 'color'): ?>
                    <span class="appearance-color-field"><input type="color" name="<?= e($key) ?>" value="<?= e($display !== '' ? safe_color($display, $field['default']) : safe_color($effective, $field['default'])) ?>"><input type="text" value="<?= e($display) ?>" placeholder="<?= e($scope === 'page' ? 'Ikuti global: ' . $effective : $field['default']) ?>" data-color-text="<?= e($key) ?>" maxlength="7" pattern="#[0-9a-fA-F]{6}" <?= $scope === 'global' ? 'required' : '' ?>></span>
                    <?php if ($scope === 'page'): ?><span class="appearance-inherit"><input type="checkbox" name="inherit_<?= e($key) ?>" value="1" <?= $display === '' ? 'checked' : '' ?>> Ikuti warna global</span><?php endif; ?>
                <?php else: ?>
                    <select name="<?= e($key) ?>" <?= $scope === 'global' ? 'required' : '' ?>>
                        <?php if ($scope === 'page'): ?><option value="">Ikuti global (<?= e($effective) ?>)</option><?php endif; ?>
                        <?php foreach ($fonts as $font): ?><option value="<?= e($font) ?>" <?= $display === $font ? 'selected' : '' ?>><?= e($font) ?></option><?php endforeach; ?>
                    </select>
                <?php endif; ?>
                <?php if ($scope === 'page'): ?><small class="form-hint">Nilai aktif saat ini: <?= e($effective) ?></small><?php endif; ?>
            </label>
        <?php endforeach; ?>
        </div>
        <div class="appearance-preview" id="appearancePreview">
            <p class="eyebrow">PRATINJAU</p><h3>SMK INFOKOM Kota Bogor</h3><p>Contoh teks paragraf untuk melihat kontras dan kombinasi font.</p><button type="button">Contoh tombol</button>
        </div>
        <div class="actions-row appearance-actions"><button class="button button-primary" type="submit">Simpan Pengaturan</button><a class="button button-muted" href="<?= e(url('admin/appearance.php?scope=' . $scope . ($scope === 'page' ? '&page=' . rawurlencode($pageKey) : ''))) ?>">Batalkan</a></div>
    </form>
</section>
<script>
(function(){
 const form=document.querySelector('.appearance-form'); if(!form)return;
 const preview=document.getElementById('appearancePreview');
 const colorInputs=[...form.querySelectorAll('input[type="color"]')];
 colorInputs.forEach(input=>{
   const text=form.querySelector('[data-color-text="'+input.name+'"]);
   const inherit=form.querySelector('[name="inherit_'+input.name+'"]');
   if(text){ text.value=text.value||input.value; input.addEventListener('input',()=>{text.value=input.value; update();}); text.addEventListener('input',()=>{if(/^#[0-9a-fA-F]{6}$/.test(text.value))input.value=text.value;update();}); }
   if(inherit){ const sync=()=>{input.disabled=inherit.checked;text.disabled=inherit.checked;};inherit.addEventListener('change',sync);sync(); }
 });
 form.querySelectorAll('select').forEach(el=>el.addEventListener('change',update));
 function update(){
   const get=(name,fallback)=>{const el=form.elements[name];return el?(el.value||fallback):fallback;};
   const heading=get('heading_color','#142230'), text=get('text_color','#475569'), bg=get('background_color','#FBFCFD'), button=get('button_color','#0F4C81'), buttonText=get('button_text_color','#FFFFFF');
   preview.style.background=bg; preview.style.color=text; preview.querySelector('h3').style.color=heading; preview.querySelector('h3').style.fontFamily='"'+get('heading_font','Manrope')+'",sans-serif'; preview.querySelector('p:not(.eyebrow)').style.color=text; preview.querySelector('p:not(.eyebrow)').style.fontFamily='"'+get('body_font','DM Sans')+'",sans-serif'; const btn=preview.querySelector('button');btn.style.background=button;btn.style.color=buttonText;
 }
 update();
 // Keep paired text and color controls in sync before submit.
 form.addEventListener('submit',()=>{colorInputs.forEach(input=>{const text=form.querySelector('[data-color-text="'+input.name+'"]');const inherit=form.querySelector('[name="inherit_'+input.name+'"]');if(inherit&&inherit.checked){input.disabled=false;input.value='';}else if(text&&/^#[0-9a-fA-F]{6}$/.test(text.value))input.value=text.value;});});
})();
</script>
<?php require __DIR__ . '/_footer.php'; ?>
