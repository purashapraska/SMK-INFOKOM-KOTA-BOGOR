<?php

declare(strict_types=1);

$title = 'Kelola Fasilitas';
require_once __DIR__ . '/../config/app.php';
require_login();
ensure_facility_schema();

function facility_delete_files(int $facilityId): void
{
    global $pdo;
    $paths = [];
    $stmt = $pdo->prepare('SELECT image FROM facility_images WHERE facility_id = ?');
    $stmt->execute([$facilityId]);
    $paths = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $stmt = $pdo->prepare('SELECT image FROM facilities WHERE id = ?');
    $stmt->execute([$facilityId]);
    $main = $stmt->fetchColumn();
    if ($main) $paths[] = $main;
    foreach ($paths as $path) {
        if ($path && str_starts_with((string)$path, 'uploads/facilities/')) {
            $full = __DIR__ . '/../' . ltrim((string)$path, '/');
            if (is_file($full)) @unlink($full);
        }
    }
}

$action = (string)($_GET['action'] ?? 'list');
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['_csrf'] ?? null)) {
        flash('error', 'Sesi formulir tidak valid.');
        redirect('admin/facilities.php');
    }

    try {
        $name = post_string('name', 180);
        if ($name === '') throw new RuntimeException('Nama fasilitas wajib diisi.');
        $category = post_string('category', 80) ?: 'Fasilitas Umum';
        $description = post_string('description', 20000);
        $location = post_string('location', 180);
        $capacity = post_string('capacity', 80);
        $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
        $status = ($_POST['status'] ?? 'published') === 'draft' ? 'draft' : 'published';

        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE facilities SET name=?, category=?, description=?, location=?, capacity=?, is_featured=?, status=? WHERE id=?');
            $stmt->execute([$name, $category, $description, $location, $capacity, $isFeatured, $status, $id]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO facilities (name, category, description, location, capacity, is_featured, status) VALUES (?,?,?,?,?,?,?)');
            $stmt->execute([$name, $category, $description, $location, $capacity, $isFeatured, $status]);
            $id = (int)$pdo->lastInsertId();
        }

        $main = upload_image('image', 'uploads/facilities');
        if ($main !== null) {
            $old = $pdo->prepare('SELECT image FROM facilities WHERE id=?');
            $old->execute([$id]);
            $oldPath = $old->fetchColumn();
            $pdo->prepare('UPDATE facilities SET image=? WHERE id=?')->execute([$main, $id]);
            if ($oldPath && str_starts_with((string)$oldPath, 'uploads/facilities/')) {
                $oldFile = __DIR__ . '/../' . $oldPath;
                if (is_file($oldFile)) @unlink($oldFile);
            }
        }

        if (!empty($_POST['replace_gallery']) && $id > 0) {
            $stmt = $pdo->prepare('SELECT image FROM facility_images WHERE facility_id=?');
            $stmt->execute([$id]);
            foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $path) {
                $file = __DIR__ . '/../' . $path;
                if (is_file($file)) @unlink($file);
            }
            $pdo->prepare('DELETE FROM facility_images WHERE facility_id=?')->execute([$id]);
        }

        $uploads = $_FILES['gallery'] ?? null;
        if (is_array($uploads) && isset($uploads['name']) && is_array($uploads['name'])) {
            $order = 0;
            foreach ($uploads['name'] as $i => $unused) {
                if (($uploads['error'][$i] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) continue;
                $single = [
                    'name' => $uploads['name'][$i],
                    'type' => $uploads['type'][$i],
                    'tmp_name' => $uploads['tmp_name'][$i],
                    'error' => $uploads['error'][$i],
                    'size' => $uploads['size'][$i],
                ];
                $_FILES['_facility_gallery_single'] = $single;
                $path = upload_image('_facility_gallery_single', 'uploads/facilities');
                unset($_FILES['_facility_gallery_single']);
                if ($path !== null) {
                    $pdo->prepare('INSERT INTO facility_images (facility_id,image,sort_order) VALUES (?,?,?)')->execute([$id, $path, $order++]);
                }
            }
        }

        log_activity($action === 'edit' ? 'update' : 'create', 'facilities #' . $id);
        flash('success', $action === 'edit' ? 'Fasilitas berhasil diperbarui.' : 'Fasilitas berhasil ditambahkan.');
        redirect('admin/facilities.php');
    } catch (Throwable $e) {
        flash('error', $e->getMessage());
        redirect('admin/facilities.php' . ($id ? '?action=edit&id=' . $id : '?action=create'));
    }
}

if ($action === 'delete' && $id > 0 && verify_csrf($_GET['_csrf'] ?? null)) {
    facility_delete_files($id);
    $pdo->prepare('DELETE FROM facilities WHERE id=?')->execute([$id]);
    log_activity('delete', 'facilities #' . $id);
    flash('success', 'Fasilitas berhasil dihapus.');
    redirect('admin/facilities.php');
}

require __DIR__ . '/_header.php';
$success = flash('success');
$error = flash('error');

if ($action === 'create' || $action === 'edit') {
    $row = ['category'=>'Fasilitas Umum','status'=>'published','is_featured'=>0];
    if ($id > 0) {
        $stmt = $pdo->prepare('SELECT * FROM facilities WHERE id=? LIMIT 1');
        $stmt->execute([$id]);
        $row = array_merge($row, $stmt->fetch() ?: []);
    }
    $gallery = [];
    if ($id > 0) {
        $stmt = $pdo->prepare('SELECT * FROM facility_images WHERE facility_id=? ORDER BY sort_order,id');
        $stmt->execute([$id]);
        $gallery = $stmt->fetchAll();
    }
    ?>
    <section class="panel facility-admin-panel">
        <div class="panel-heading">
            <div><p class="eyebrow">FACILITY STUDIO</p><h2><?= e($id ? 'Edit Fasilitas' : 'Tambah Fasilitas') ?></h2><p class="panel-subtitle">Kelola visual fasilitas, detail, dan galeri dalam satu tempat.</p></div>
            <a class="button button-muted" href="<?= e(url('admin/facilities.php')) ?>">Kembali</a>
        </div>
        <?php if ($success): ?><div class="notice success"><?= e($success) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
        <form class="admin-form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
            <div class="facility-admin-grid">
                <div>
                    <label>Nama Fasilitas<input type="text" name="name" required value="<?= e($row['name'] ?? '') ?>" placeholder="Contoh: Laboratorium Komputer"></label>
                    <div class="form-grid-admin">
                        <label>Kategori<select name="category"><?php foreach(['Laboratorium','Ruang Belajar','Olahraga','Perpustakaan','Fasilitas Umum'] as $v): ?><option value="<?=e($v)?>" <?=($row['category']??'')===$v?'selected':''?>><?=e($v)?></option><?php endforeach; ?></select></label>
                        <label>Status<select name="status"><option value="published" <?=($row['status']??'')==='published'?'selected':''?>>Tampil</option><option value="draft" <?=($row['status']??'')==='draft'?'selected':''?>>Draft</option></select></label>
                    </div>
                    <div class="form-grid-admin"><label>Lokasi<input type="text" name="location" value="<?=e($row['location']??'')?>" placeholder="Gedung A / Lantai 2"></label><label>Kapasitas<input type="text" name="capacity" value="<?=e($row['capacity']??'')?>" placeholder="36 siswa"></label></div>
                    <label>Deskripsi<textarea name="description" rows="7" placeholder="Jelaskan fungsi dan keunggulan fasilitas..."><?=e($row['description']??'')?></textarea></label>
                    <label class="facility-check"><input type="checkbox" name="is_featured" value="1" <?=!empty($row['is_featured'])?'checked':''?>> Jadikan fasilitas unggulan di halaman publik</label>
                </div>
                <div class="facility-upload-stack">
                    <div class="facility-upload-card"><span>FOTO UTAMA</span><?php if (!empty($row['image'])): ?><img src="<?=e(img_url($row['image']))?>" alt="Preview foto utama"><?php else: ?><div class="facility-upload-placeholder">Upload foto utama</div><?php endif; ?><input type="file" name="image" accept="image/jpeg,image/png,image/webp"><small>Maks. 10 MB • otomatis dikompres</small></div>
                    <div class="facility-upload-card"><span>GALERI FASILITAS</span><p>Tambahkan sampai 6 foto tambahan.</p><input type="file" name="gallery[]" multiple accept="image/jpeg,image/png,image/webp"><small>Foto lama dapat diganti dengan mencentang opsi di bawah.</small><?php if ($gallery): ?><label class="facility-check"><input type="checkbox" name="replace_gallery" value="1"> Hapus galeri lama saat menyimpan</label><div class="facility-admin-gallery"><?php foreach($gallery as $g): ?><img src="<?=e(img_url($g['image']))?>" alt=""> <?php endforeach; ?></div><?php endif; ?></div>
                </div>
            </div>
            <button class="button button-primary facility-save-button" type="submit"><span><?= e($id ? 'Simpan Perubahan' : 'Publikasikan Fasilitas') ?></span><b>↗</b></button>
        </form>
    </section>
    <?php
} else {
    $q = trim((string)($_GET['q'] ?? ''));
    $category = trim((string)($_GET['category'] ?? ''));
    $sql = 'SELECT * FROM facilities WHERE 1=1'; $params=[];
    if ($q !== '') { $sql .= ' AND (name LIKE ? OR description LIKE ? OR location LIKE ?)'; $like='%'.$q.'%'; $params=[$like,$like,$like]; }
    if ($category !== '') { $sql .= ' AND category=?'; $params[]=$category; }
    $sql .= ' ORDER BY is_featured DESC, id DESC';
    $stmt=$pdo->prepare($sql); $stmt->execute($params); $rows=$stmt->fetchAll();
    ?>
    <section class="panel facility-admin-panel">
        <div class="panel-heading"><div><p class="eyebrow">FACILITY STUDIO</p><h2>Fasilitas</h2><p class="panel-subtitle">Kelola showcase fasilitas sekolah dari database.</p></div><a class="button button-primary" href="<?=e(url('admin/facilities.php?action=create'))?>">+ Tambah Fasilitas</a></div>
        <?php if ($success): ?><div class="notice success"><?=e($success)?></div><?php endif; ?>
        <?php if ($error): ?><div class="notice error"><?=e($error)?></div><?php endif; ?>
        <form class="facility-admin-toolbar" method="get"><input type="text" name="q" value="<?=e($q)?>" placeholder="Cari fasilitas..."><select name="category"><option value="">Semua kategori</option><?php foreach(['Laboratorium','Ruang Belajar','Olahraga','Perpustakaan','Fasilitas Umum'] as $v): ?><option value="<?=e($v)?>" <?= $category===$v?'selected':''?>><?=e($v)?></option><?php endforeach;?></select><button class="button button-muted" type="submit">Cari</button><?php if($q!==''||$category!==''): ?><a class="button button-muted" href="<?=e(url('admin/facilities.php'))?>">Reset</a><?php endif;?></form>
        <div class="facility-admin-list">
        <?php foreach($rows as $item): ?>
            <article class="facility-admin-row">
                <div class="facility-admin-row-image"><img src="<?=e(img_url($item['image']))?>" alt="<?=e($item['name'])?>"></div>
                <div class="facility-admin-row-copy"><div class="facility-admin-row-top"><span><?=e($item['category'])?></span><?php if(!empty($item['is_featured'])):?><b>FEATURED</b><?php endif;?></div><h3><?=e($item['name'])?></h3><p><?=e(mb_substr((string)$item['description'],0,150))?></p><small><?=e($item['location'] ?: 'Lokasi belum diisi')?><?= $item['capacity'] ? ' · '.e($item['capacity']) : '' ?></small></div>
                <div class="facility-admin-row-actions"><a class="button button-muted" href="<?=e(url('admin/facilities.php?action=edit&id='.(int)$item['id']))?>">Edit</a><a class="button button-danger" href="<?=e(url('admin/facilities.php?action=delete&id='.(int)$item['id'].'&_csrf='.csrf_token()))?>" onclick="return confirm('Hapus fasilitas beserta galerinya?')">Hapus</a></div>
            </article>
        <?php endforeach; ?>
        <?php if(!$rows): ?><div class="admin-empty">Belum ada fasilitas yang sesuai pencarian.</div><?php endif; ?>
        </div>
    </section>
    <?php
}
require __DIR__ . '/_footer.php';
