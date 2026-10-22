<?php
declare(strict_types=1);
$title = 'Kunjungan Industri';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../includes/industrial-visits-data.php';
require_login();
ensure_industrial_visit_schema();

$stmt=$pdo->query('SELECT * FROM industrial_visits WHERE id=1 LIMIT 1');
$row=$stmt->fetch(PDO::FETCH_ASSOC) ?: [];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!verify_csrf($_POST['_csrf'] ?? null)) {
        flash('error','Sesi formulir tidak valid. Silakan coba lagi.');
        redirect('admin/industrial-visits.php');
    }
    try {
        $titleText=post_string('title',220);
        $institution=post_string('institution',220) ?: 'Universitas AMIKOM Yogyakarta';
        $date=post_string('visit_date',20);
        $duration=post_string('duration_label',100) ?: 'Sekitar satu minggu';
        $purpose=post_string('purpose',10000);
        $story=post_string('story',20000);
        $status=($_POST['status'] ?? 'published')==='draft'?'draft':'published';
        if ($titleText==='') throw new RuntimeException('Judul kegiatan wajib diisi.');
        if ($date!=='' && !preg_match('/^\d{4}-\d{2}-\d{2}$/',$date)) throw new RuntimeException('Format tanggal tidak valid.');
        $uploads=[];
        foreach (['hero_image'=>'uploads/industrial-visits','middle_image'=>'uploads/industrial-visits','bottom_image'=>'uploads/industrial-visits','institution_logo'=>'uploads/industrial-visits'] as $field=>$dir) {
            $uploads[$field]=upload_image($field,$dir,12*1024*1024,2200,1800,84);
        }
        $sql="UPDATE industrial_visits SET title=?,institution=?,visit_date=?,duration_label=?,purpose=?,story=?,status=?";
        $params=[$titleText,$institution,$date!==''?$date:null,$duration,$purpose,$story,$status];
        foreach ($uploads as $field=>$path) { if ($path!==null) { $sql.=", {$field}=?"; $params[]=$path; } }
        $sql.=" WHERE id=1";
        $pdo->prepare($sql)->execute($params);
        if (function_exists('log_activity')) log_activity('update','industrial_visits #1');
        flash('success','Konten Kunjungan Industri berhasil disimpan.');
        redirect('admin/industrial-visits.php');
    } catch (Throwable $e) {
        flash('error',$e->getMessage());
        redirect('admin/industrial-visits.php');
    }
}
$stmt=$pdo->query('SELECT * FROM industrial_visits WHERE id=1 LIMIT 1');
$row=$stmt->fetch(PDO::FETCH_ASSOC) ?: [];
require __DIR__.'/_header.php';
$success=flash('success');$error=flash('error');
?>
<style>
.ki-admin-wrap{max-width:1040px;margin:0 auto}.ki-admin-intro{padding:24px;border-radius:18px;background:linear-gradient(135deg,#0d2340,#142e4d);color:#fff;margin-bottom:22px;border:1px solid rgba(255,255,255,.12)}.ki-admin-intro h2{margin:0 0 8px;color:#fff}.ki-admin-intro p{margin:0;color:#d6e3f2;line-height:1.7}.ki-admin-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}.ki-admin-field{display:flex;flex-direction:column;gap:8px;margin-bottom:17px}.ki-admin-field label{font-weight:700}.ki-admin-field input,.ki-admin-field textarea,.ki-admin-field select{width:100%;max-width:100%}.ki-admin-field textarea{min-height:130px}.ki-admin-photo{padding:15px;border:1px solid var(--border-color,#dbe2ea);border-radius:14px;background:var(--card-bg,#fff);min-width:0}.ki-admin-photo img{display:block;width:100%;height:150px;object-fit:contain;background:#f1f5f9;border-radius:10px;margin-bottom:12px}.ki-admin-photo small{display:block;opacity:.75;line-height:1.5;margin-top:6px}.ki-admin-actions{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:22px}@media(max-width:720px){.ki-admin-grid{grid-template-columns:1fr}}
</style>
<div class="ki-admin-wrap">
  <div class="ki-admin-intro"><h2>Kunjungan Industri</h2><p>Kelola konten halaman, tanggal, cerita kegiatan, logo Universitas AMIKOM Yogyakarta, dan tepat tiga foto: hero, tengah, serta bawah. Foto yang tidak diganti akan tetap menggunakan file sebelumnya.</p></div>
  <?php if($success): ?><div class="alert alert-success"><?=e($success)?></div><?php endif; ?>
  <?php if($error): ?><div class="alert alert-danger"><?=e($error)?></div><?php endif; ?>
  <form method="post" enctype="multipart/form-data" class="card" style="padding:24px">
    <input type="hidden" name="_csrf" value="<?=e(csrf_token())?>">
    <div class="ki-admin-grid">
      <div class="ki-admin-field"><label for="title">Judul kegiatan</label><input id="title" name="title" type="text" maxlength="220" required value="<?=e($row['title']??'')?>"></div>
      <div class="ki-admin-field"><label for="institution">Nama institusi</label><input id="institution" name="institution" type="text" maxlength="220" required value="<?=e($row['institution']??'Universitas AMIKOM Yogyakarta')?>"></div>
      <div class="ki-admin-field"><label for="visit_date">Tanggal mulai</label><input id="visit_date" name="visit_date" type="date" value="<?=e($row['visit_date']??'')?>"></div>
      <div class="ki-admin-field"><label for="duration_label">Durasi</label><input id="duration_label" name="duration_label" type="text" maxlength="100" value="<?=e($row['duration_label']??'Sekitar satu minggu')?>"></div>
    </div>
    <div class="ki-admin-field"><label for="purpose">Tujuan kunjungan</label><textarea id="purpose" name="purpose" rows="4"><?=e($row['purpose']??'')?></textarea></div>
    <div class="ki-admin-field"><label for="story">Cerita kegiatan</label><textarea id="story" name="story" rows="7"><?=e($row['story']??'')?></textarea></div>
    <h3>Foto dan logo</h3>
    <div class="ki-admin-grid">
      <?php foreach (['hero_image'=>'Foto 1 — Hero','middle_image'=>'Foto 2 — Bagian tengah','bottom_image'=>'Foto 3 — Bagian bawah','institution_logo'=>'Logo Universitas AMIKOM'] as $field=>$label): ?>
      <div class="ki-admin-photo">
        <strong><?=e($label)?></strong>
        <?php if(!empty($row[$field])): ?><img src="<?=e(url($row[$field]))?>" alt="<?=e($label)?> saat ini"><?php else: ?><div style="height:150px;display:grid;place-items:center;background:#f1f5f9;color:#64748b;border-radius:10px;margin:12px 0">Belum ada file</div><?php endif; ?>
        <div class="ki-admin-field" style="margin:12px 0 0"><label for="<?=e($field)?>">Unggah / ganti</label><input id="<?=e($field)?>" type="file" name="<?=e($field)?>" accept="image/jpeg,image/png,image/webp"></div>
        <small>Format JPG, PNG, atau WebP; maksimal 12 MB. Kosongkan jika tidak ingin mengganti.</small>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="ki-admin-field" style="margin-top:18px"><label for="status">Status halaman</label><select id="status" name="status"><option value="published" <?=($row['status']??'published')==='published'?'selected':''?>>Published — tampil di website</option><option value="draft" <?=($row['status']??'')==='draft'?'selected':''?>>Draft — disembunyikan</option></select></div>
    <div class="ki-admin-actions"><span style="color:var(--muted,#64748b);font-size:13px">Tanggal awal tersimpan: 20 Februari 2025. Durasi masih perkiraan.</span><button type="submit" class="btn btn-primary">Simpan perubahan</button></div>
  </form>
</div>
<?php require __DIR__.'/_footer.php'; ?>
