<?php
declare(strict_types=1);
$title = 'Konten Halaman';
require_once __DIR__ . '/../config/app.php';
require_login();
$pages = [
    'home' => 'Beranda',
    'profil' => 'Profil',
    'jurusan' => 'Jurusan',
    'guru' => 'Guru & Staff',
    'informasi' => 'Informasi',
    'prestasi' => 'Prestasi',
    'kesiswaan' => 'Kesiswaan / Ekstrakurikuler',
    'fasilitas' => 'Fasilitas',
    'program-karier' => 'Program & Karier',
    'pkl' => 'PKL',
    'magang' => 'Magang Jepang',
    'bkk' => 'BKK',
    'mitra' => 'Mitra',
    'galeri' => 'Galeri',
    'ppdb' => 'PPDB',
    'kontak' => 'Kontak',
];
$fieldDefs = [
 'hero_title' => ['label'=>'Judul utama / hero','type'=>'textarea'],
 'hero_description' => ['label'=>'Deskripsi utama / hero','type'=>'textarea'],
 'section_1_title' => ['label'=>'Judul bagian 1','type'=>'text'],
 'section_2_title' => ['label'=>'Judul bagian 2','type'=>'text'],
 'button_label' => ['label'=>'Label tombol utama','type'=>'text'],
 'button_url' => ['label'=>'Tujuan tombol utama (URL atau #bagian)','type'=>'text'],
];
$page = (string)($_GET['page'] ?? 'home');
if (!isset($pages[$page])) $page='home';
$mode = (string)($_POST['action'] ?? 'draft');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 if (!verify_csrf($_POST['_csrf'] ?? null)) { flash('error','Token keamanan tidak valid.'); redirect('admin/content.php?page='.$page); }
 try {
  foreach ($fieldDefs as $key=>$def) {
   $value = trim((string)($_POST[$key] ?? ''));
   if (strlen($value) > 20000) throw new RuntimeException('Teks terlalu panjang (maksimal 20.000 byte per kolom).');
   save_setting('cms_draft_'.$page.'_'.$key, $value);
   if ($mode === 'publish') save_setting('cms_page_'.$page.'_'.$key, $value);
  }
  if ($mode !== 'publish') { $_SESSION['cms_preview_key'] = bin2hex(random_bytes(16)); }
  if ($mode === 'publish') { unset($_SESSION['cms_preview_key']); save_setting('cms_page_'.$page.'_published_at', date('c')); log_activity('content','Konten halaman '.$pages[$page].' dipublikasikan'); flash('success','Konten berhasil dipublikasikan.'); }
  else { log_activity('content','Draft konten halaman '.$pages[$page].' disimpan'); flash('success','Draft tersimpan. Tampilan publik belum berubah.'); }
 } catch (Throwable $e) { flash('error', 'Gagal menyimpan konten: '.$e->getMessage()); }
 redirect('admin/content.php?page='.$page);
}
$draft=[]; $published=[];
foreach ($fieldDefs as $key=>$def) { $draft[$key]=setting('cms_draft_'.$page.'_'.$key, setting('cms_page_'.$page.'_'.$key, '')); $published[$key]=setting('cms_page_'.$page.'_'.$key, ''); }
$success=flash('success'); $error=flash('error');
require __DIR__.'/_header.php';
?>
<section class="panel">
 <div class="panel-heading"><div><p class="eyebrow">CONTENT MANAGEMENT</p><h2>Editor Konten Halaman</h2><p class="panel-subtitle">Kelola judul utama dan beberapa teks utama tanpa mengedit file PHP. Draft tidak tampil ke pengunjung sampai dipublikasikan.</p></div></div>
 <?php if($success): ?><div class="notice success"><?=e($success)?></div><?php endif; ?><?php if($error): ?><div class="notice error"><?=e($error)?></div><?php endif; ?>
 <form method="get" class="appearance-page-picker"><label>Pilih halaman<select name="page" onchange="this.form.submit()"><?php foreach($pages as $key=>$label): ?><option value="<?=e($key)?>" <?=$page===$key?'selected':''?>><?=e($label)?></option><?php endforeach; ?></select></label></form>
 <div class="notice">Nilai kosong akan menggunakan teks bawaan halaman. Pengaturan ini menggunakan tabel <code>settings</code> yang sudah ada, jadi tidak memerlukan perubahan struktur database.</div>
 <form method="post" class="admin-form"><input type="hidden" name="_csrf" value="<?=e(csrf_token())?>">
 <div class="form-grid-admin">
 <?php foreach($fieldDefs as $key=>$def): ?><label class="<?= $def['type']==='textarea'?'form-field-wide':'' ?>"><?=e($def['label'])?>
 <?php if($def['type']==='textarea'): ?><textarea name="<?=e($key)?>" rows="3" maxlength="5000"><?=e($draft[$key])?></textarea><?php else: ?><input type="text" name="<?=e($key)?>" maxlength="5000" value="<?=e($draft[$key])?>"><?php endif; ?>
 <small class="form-hint">Teks yang tampil saat ini: <?=e($published[$key] !== '' ? substr($published[$key],0,100) : 'Menggunakan teks bawaan halaman')?></small></label><?php endforeach; ?>
 </div><div class="actions-row"><button class="button button-muted" name="action" value="draft" type="submit">Simpan Draft</button><button class="button button-primary" name="action" value="publish" type="submit" onclick="return confirm('Publikasikan perubahan ke website?')">Publikasikan</button></div></form>
 <?php if(isset($_SESSION['cms_preview_key'])): ?><p><a class="button button-muted" target="_blank" rel="noopener" href="<?=e(url(['home'=>'index.php','profil'=>'profil.php','jurusan'=>'jurusan.php','guru'=>'guru.php','informasi'=>'berita.php','prestasi'=>'prestasi.php','kesiswaan'=>'ekstrakurikuler.php','fasilitas'=>'fasilitas.php','program-karier'=>'pkl.php','pkl'=>'pkl.php','magang'=>'magang.php','bkk'=>'bkk.php','mitra'=>'mitra.php','galeri'=>'galeri.php','ppdb'=>'ppdb.php','kontak'=>'kontak.php'][$page] . '?cms_preview=' . $_SESSION['cms_preview_key'])) ?>">Pratinjau Draft</a></p><?php endif; ?>
 <p class="form-hint">Catatan: field akan mengubah bagian yang sudah dihubungkan ke CMS. Bagian konten terstruktur seperti Guru, Jurusan, Berita, Fasilitas, Galeri, dan Lowongan tetap dikelola melalui menu CRUD masing-masing.</p>
</section>
<?php require __DIR__.'/_footer.php'; ?>
