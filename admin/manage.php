<?php

declare(strict_types=1);

$title = 'Kelola Data';

require_once __DIR__ . '/../config/app.php';

require_login();

if (table_exists('internships')) {
    try {
        $internshipColumns = $pdo->query("SHOW COLUMNS FROM internships")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('website', $internshipColumns, true)) {
            $pdo->exec("ALTER TABLE internships ADD COLUMN website VARCHAR(500) NULL AFTER image");
        }
    } catch (Throwable) {
        // Keep the admin usable on older installations.
    }
}

if (table_exists('achievements')) {
    try {
        $achievementColumns = $pdo->query("SHOW COLUMNS FROM achievements")->fetchAll(PDO::FETCH_COLUMN);
        $achievementMigrations = [
            'major' => "ALTER TABLE achievements ADD COLUMN major VARCHAR(20) NULL AFTER year",
            'level' => "ALTER TABLE achievements ADD COLUMN level VARCHAR(30) NULL AFTER major",
            'rank_label' => "ALTER TABLE achievements ADD COLUMN rank_label VARCHAR(80) NULL AFTER level",
        ];
        foreach ($achievementMigrations as $column => $alter) {
            if (!in_array($column, $achievementColumns, true)) {
                $pdo->exec($alter);
            }
        }
    } catch (Throwable) {
        // Keep the admin usable on older installations.
    }
}

if (table_exists('teachers')) {
    try {
        $teacherColumns = $pdo->query("SHOW COLUMNS FROM teachers")->fetchAll(PDO::FETCH_COLUMN);
        if (!in_array('education', $teacherColumns, true)) {
            $pdo->exec("ALTER TABLE teachers ADD COLUMN education VARCHAR(30) NOT NULL DEFAULT 'S1' AFTER subject");
        }
        if (!in_array('bio', $teacherColumns, true)) {
            $pdo->exec("ALTER TABLE teachers ADD COLUMN bio TEXT NULL AFTER education");
        }
    } catch (Throwable) {
        // Keep the admin usable on older installations.
    }
}

$configs = [
    'majors' => [
        'label' => 'Jurusan',
        'table' => 'majors',
        'fields' => [
            'name' => [
                'Nama Jurusan',
                'text',
                true,
            ],
            'description' => [
                'Deskripsi',
                'textarea',
                false,
            ],
            'image' => [
                'Foto Jurusan',
                'image',
                false,
            ],
        ],
    ],
    'teachers' => [
        'label' => 'Guru',
        'table' => 'teachers',
        'fields' => [
            'name' => [
                'Nama Guru',
                'text',
                true,
            ],
            'position' => [
                'Jabatan',
                'teacher_position',
                false,
            ],
            'subject' => [
                'Mata Pelajaran',
                'text',
                false,
            ],
            'education' => [
                'Pendidikan Terakhir',
                'education',
                true,
            ],
            'bio' => [
                'Profil / Deskripsi Guru',
                'textarea',
                false,
            ],
            'image' => [
                'Foto Guru',
                'image',
                false,
            ],
        ],
    ],
    'students' => [
        'label' => 'Siswa',
        'table' => 'students',
        'fields' => [
            'name' => [
                'Nama Siswa',
                'text',
                true,
            ],
            'nisn' => [
                'NISN',
                'text',
                false,
            ],
            'class_name' => [
                'Kelas',
                'text',
                false,
            ],
        ],
    ],
    'facilities' => [
        'label' => 'Fasilitas',
        'table' => 'facilities',
        'fields' => [
            'name' => [
                'Nama Fasilitas',
                'text',
                true,
            ],
            'description' => [
                'Deskripsi',
                'textarea',
                false,
            ],
            'image' => [
                'Foto',
                'image',
                false,
            ],
        ],
    ],
    'achievements' => [
        'label' => 'Prestasi',
        'table' => 'achievements',
        'fields' => [
            'title' => [
                'Nama Prestasi',
                'text',
                true,
            ],
            'year' => [
                'Tahun',
                'number',
                false,
            ],
            'description' => [
                'Deskripsi',
                'textarea',
                false,
            ],
            'major' => [
                'Jurusan',
                'achievement_major',
                true,
            ],
            'level' => [
                'Tingkat',
                'achievement_level',
                true,
            ],
            'rank_label' => [
                'Peringkat',
                'achievement_rank',
                true,
            ],
        ],
    ],
    'gallery' => [
        'label' => 'Galeri Instagram',
        'table' => 'gallery',
        'fields' => [
            'title' => [
                'Judul Postingan',
                'text',
                true,
            ],
            'instagram_url' => [
                'URL Postingan Instagram',
                'url',
                true,
            ],
            'description' => [
                'Deskripsi / Caption',
                'textarea',
                false,
            ],
            'status' => [
                'Status',
                'media_status',
                true,
            ],
            'sort_order' => [
                'Urutan',
                'number',
                false,
            ],
        ],
    ],
    'extracurriculars' => [
        'label' => 'Ekstrakurikuler',
        'table' => 'extracurriculars',
        'fields' => [
            'name' => [
                'Nama Ekskul',
                'text',
                true,
            ],
            'image' => [
                'Logo Ekskul',
                'image',
                false,
            ],
            'description' => [
                'Deskripsi',
                'textarea',
                false,
            ],
        ],
    ],
    'internships' => [
        'label' => 'PKL',
        'table' => 'internships',
        'fields' => [
            'title' => [
                'Nama / Program PKL',
                'text',
                true,
            ],
            'type' => [
                'Jurusan / Kategori',
                'pkl_category',
                true,
            ],
            'description' => [
                'Deskripsi Program',
                'textarea',
                false,
            ],
            'image' => [
                'Foto / Logo Perusahaan',
                'image',
                false,
            ],
            'website' => [
                'Link Situs Perusahaan',
                'url',
                false,
            ],
        ],
    ],
    'jobs' => [
        'label' => 'BKK / Lowongan',
        'table' => 'jobs',
        'fields' => [
            'company' => [
                'Perusahaan',
                'text',
                true,
            ],
            'position' => [
                'Posisi',
                'text',
                true,
            ],
            'location' => [
                'Lokasi',
                'text',
                false,
            ],
            'description' => [
                'Deskripsi',
                'textarea',
                false,
            ],
            'deadline' => [
                'Deadline',
                'date',
                false,
            ],
            'apply_url' => [
                'Link Lamar',
                'url',
                false,
            ],
            'status' => [
                'Status',
                'select',
                false,
            ],
        ],
    ],
    'bkk_partners' => [
        'label' => 'BKK / Mitra',
        'table' => 'bkk_partners',
        'fields' => [
            'name' => [
                'Nama Mitra / Grup Logo',
                'text',
                true,
            ],
            'category' => [
                'Kategori',
                'text',
                false,
            ],
            'description' => [
                'Deskripsi',
                'textarea',
                false,
            ],
            'logo' => [
                'Logo Mitra',
                'image',
                false,
            ],
            'website' => [
                'Link Situs Mitra (URL)',
                'url',
                false,
            ],
            'is_featured' => [
                'Mitra Unggulan',
                'featured_status',
                false,
            ],
            'status' => [
                'Status',
                'media_status',
                true,
            ],
            'sort_order' => [
                'Urutan',
                'number',
                false,
            ],
        ],
    ],
    'youtube_videos' => [
        'label' => 'YouTube',
        'table' => 'youtube_videos',
        'fields' => [
            'title' => [
                'Judul Video',
                'text',
                true,
            ],
            'youtube_url' => [
                'URL YouTube',
                'url',
                true,
            ],
            'category' => [
                'Kategori',
                'text',
                false,
            ],
            'description' => [
                'Deskripsi',
                'textarea',
                false,
            ],
            'status' => [
                'Status',
                'media_status',
                true,
            ],
            'sort_order' => [
                'Urutan',
                'number',
                false,
            ],
        ],
    ],
    'instagram_posts' => [
        'label' => 'Instagram',
        'table' => 'instagram_posts',
        'fields' => [
            'title' => [
                'Judul Postingan',
                'text',
                true,
            ],
            'instagram_url' => [
                'URL Postingan Instagram',
                'url',
                true,
            ],
            'description' => [
                'Deskripsi',
                'textarea',
                false,
            ],
            'status' => [
                'Status',
                'media_status',
                true,
            ],
            'sort_order' => [
                'Urutan',
                'number',
                false,
            ],
        ],
    ],
    'magang_instagram' => [
        'label' => 'Magang Jepang / Instagram',
        'table' => 'magang_instagram',
        'fields' => [
            'title' => [
                'Judul Pengalaman',
                'text',
                true,
            ],
            'instagram_url' => [
                'URL Postingan Instagram',
                'url',
                true,
            ],
            'description' => [
                'Caption / Deskripsi',
                'textarea',
                false,
            ],
            'status' => [
                'Status',
                'media_status',
                true,
            ],
            'sort_order' => [
                'Urutan',
                'number',
                false,
            ],
        ],
    ],
    'ppdb_documents' => [
        'label' => 'Dokumen PPDB',
        'table' => 'ppdb_documents',
        'fields' => [
            'title' => [
                'Judul Dokumen',
                'text',
                true,
            ],
            'category' => [
                'Kategori',
                'text',
                false,
            ],
            'description' => [
                'Deskripsi',
                'textarea',
                false,
            ],
            'file_path' => [
                'File PDF',
                'pdf',
                true,
            ],
        ],
    ],
    'ppdb_registrations' => [
        'label' => 'Pendaftar PPDB',
        'table' => 'ppdb_registrations',
        'fields' => [
            'name' => [
                'Nama Pendaftar',
                'text',
                true,
            ],
            'origin_school' => [
                'Asal Sekolah',
                'text',
                true,
            ],
            'phone' => [
                'WhatsApp',
                'text',
                false,
            ],
            'address' => [
                'Alamat',
                'textarea',
                false,
            ],
            'status' => [
                'Status',
                'ppdb_status',
                true,
            ],
        ],
    ],
    'contact_messages' => [
        'label' => 'Pesan Masuk',
        'table' => 'contact_messages',
        'fields' => [
            'name' => [
                'Nama',
                'text',
                true,
            ],
            'email' => [
                'Email',
                'email',
                true,
            ],
            'phone' => [
                'WhatsApp',
                'text',
                false,
            ],
            'subject' => [
                'Subjek',
                'text',
                false,
            ],
            'message' => [
                'Pesan',
                'textarea',
                true,
            ],
            'status' => [
                'Status',
                'message_status',
                true,
            ],
        ],
    ],
];

$entity = (string) (
    $_GET['entity'] ?? 'majors'
);

if (!isset($configs[$entity])) {
    echo '<div class="notice error">Modul tidak ditemukan.</div>';
    require __DIR__ . '/_footer.php';
    exit;
}

$config = $configs[$entity];
$table = $config['table'];

if ($entity === 'gallery') {
    try {
        if (!table_exists('gallery')) {
            $pdo->exec("CREATE TABLE gallery (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(180) NOT NULL,
                instagram_url VARCHAR(500) NOT NULL,
                description TEXT NULL,
                status ENUM('published','draft') DEFAULT 'published',
                sort_order INT NOT NULL DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } else {
            $columns = $pdo->query("SHOW COLUMNS FROM gallery")->fetchAll(PDO::FETCH_COLUMN);
            if (!in_array('instagram_url', $columns, true)) {
                $pdo->exec("ALTER TABLE gallery ADD COLUMN instagram_url VARCHAR(500) NULL AFTER title");
            }
            if (!in_array('description', $columns, true)) {
                $pdo->exec("ALTER TABLE gallery ADD COLUMN description TEXT NULL AFTER instagram_url");
            }
            if (!in_array('status', $columns, true)) {
                $pdo->exec("ALTER TABLE gallery ADD COLUMN status ENUM('published','draft') DEFAULT 'published' AFTER description");
            }
            if (!in_array('sort_order', $columns, true)) {
                $pdo->exec("ALTER TABLE gallery ADD COLUMN sort_order INT NOT NULL DEFAULT 0 AFTER status");
            }
        }
    } catch (Throwable) {
        // Keep the admin usable on older installations.
    }
}

if ($entity === 'magang_instagram') {
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
}

$action = (string) ($_GET['action'] ?? 'list');
$id = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT
) ?: 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!verify_csrf($_POST['_csrf'] ?? null)) {
        flash(
            'error',
            'Sesi formulir tidak valid.'
        );

        redirect(
            'admin/manage.php?entity=' . $entity
        );
    }

    try {
        $data = [];

        foreach ($config['fields'] as $field => $meta) {
            if (in_array($meta[1], ['image', 'pdf'], true)) {
                continue;
            }

            $data[$field] = post_string(
                $field,
                20000
            );
        }

        if ($entity === 'youtube_videos') {
            if (youtube_video_id($data['youtube_url'] ?? '') === '') {
                throw new RuntimeException(
                    'URL YouTube tidak valid. Gunakan URL video YouTube yang benar.'
                );
            }
        }

        if (in_array($entity, ['gallery', 'instagram_posts', 'magang_instagram'], true) && isset($data['instagram_url'])) {
            if (instagram_embed_url($data['instagram_url'] ?? '') === '') {
                throw new RuntimeException(
                    'URL Instagram tidak valid. Gunakan URL posting, reel, atau video Instagram.'
                );
            }
        }

        if (
            $entity === 'jobs'
            && !in_array(
                $data['status'] ?? '',
                ['published', 'draft'],
                true
            )
        ) {
            $data['status'] = 'draft';
        }

        if (
            $entity === 'contact_messages'
            && !in_array(
                $data['status'] ?? '',
                ['unread', 'read'],
                true
            )
        ) {
            $data['status'] = 'unread';
        }

        if (
            $entity === 'ppdb_registrations'
            && !in_array(
                $data['status'] ?? '',
                ['pending', 'verified', 'rejected'],
                true
            )
        ) {
            $data['status'] = 'pending';
        }

        foreach ($config['fields'] as $field => $meta) {
            $type = $meta[1];

            if ($type === 'image') {
                $upload = upload_image(
                    $field,
                    'uploads/' . $entity
                );

                if ($upload !== null) {
                    $data[$field] = $upload;
                } elseif ($id === 0) {
                    $data[$field] = null;
                }
            }

            if ($type === 'pdf') {
                $upload = upload_pdf(
                    $field,
                    'uploads/ppdb'
                );

                if ($upload !== null) {
                    $data[$field] = $upload;
                } elseif ($id === 0) {
                    throw new RuntimeException(
                        'File PDF wajib diunggah.'
                    );
                }
            }
        }

        if ($id > 0) {
            $sets = [];
            $values = [];

            foreach ($data as $key => $value) {
                $sets[] = "`{$key}` = ?";
                $values[] = $value;
            }

            if ($sets) {
                $values[] = $id;

                $statement = $pdo->prepare(
                    "UPDATE `{$table}` "
                    . 'SET '
                    . implode(', ', $sets)
                    . ' WHERE id = ?'
                );

                $statement->execute($values);
            }

            log_activity(
                'update',
                $entity . ' #' . $id
            );

            flash(
                'success',
                'Data berhasil diperbarui.'
            );
        } else {
            $columns = array_keys($data);
            $placeholders = implode(
                ',',
                array_fill(
                    0,
                    count($columns),
                    '?'
                )
            );

            $statement = $pdo->prepare(
                "INSERT INTO `{$table}` "
                . '(`'
                . implode('`,`', $columns)
                . '`) VALUES ('
                . $placeholders
                . ')'
            );

            $statement->execute(
                array_values($data)
            );

            log_activity(
                'create',
                $entity . ' #' . $pdo->lastInsertId()
            );

            flash(
                'success',
                'Data berhasil ditambahkan.'
            );
        }

        redirect(
            'admin/manage.php?entity=' . $entity
        );
    } catch (Throwable $exception) {
        flash(
            'error',
            $exception->getMessage()
        );

        $suffix = $id > 0
            ? '&action=edit&id=' . $id
            : '&action=create';

        redirect(
            'admin/manage.php?entity='
            . $entity
            . $suffix
        );
    }
}

if (
    $action === 'delete'
    && $id > 0
    && verify_csrf($_GET['_csrf'] ?? null)
) {
    $statement = $pdo->prepare(
        "DELETE FROM `{$table}` WHERE id = ?"
    );

    $statement->execute([$id]);

    log_activity(
        'delete',
        $entity . ' #' . $id
    );

    flash(
        'success',
        'Data berhasil dihapus.'
    );

    redirect(
        'admin/manage.php?entity=' . $entity
    );
}

require __DIR__ . '/_header.php';

$success = flash('success');
$error = flash('error');

if ($action === 'create' || $action === 'edit') {
    $row = [];

    if ($id > 0) {
        $statement = $pdo->prepare(
            "SELECT * FROM `{$table}` "
            . 'WHERE id = ? LIMIT 1'
        );

        $statement->execute([$id]);
        $row = $statement->fetch() ?: [];
    }

    ?>
    <section class="panel">
        <div class="panel-heading">
            <div>
                <p class="eyebrow">ADMIN</p>

                <h2>
                    <?= e($id ? 'Edit ' : 'Tambah ') ?>
                    <?= e($config['label']) ?>
                </h2>
            </div>

            <a
                class="button button-muted"
                href="<?= e(url('admin/manage.php?entity=' . $entity)) ?>"
            >
                Kembali
            </a>
        </div>

        <form
            class="admin-form admin-pkl-form"
            method="post"
            enctype="multipart/form-data"
        >
            <?php if ($entity === 'internships'): ?>
                <div class="admin-pkl-brief">
                    <div class="admin-pkl-brief-mark">PKL</div>
                    <div>
                        <strong>Konten PKL berbasis visual</strong>
                        <p>Kelola nama perusahaan/program, kategori jurusan (RPL, TKJ, PSPT, DKV), foto/logo perusahaan, deskripsi, dan link situs. Data langsung tampil pada kartu penempatan PKL.</p>
                    </div>
                </div>
            <?php endif; ?>
            <input
                type="hidden"
                name="_csrf"
                value="<?= e(csrf_token()) ?>"
            >

            <div class="form-grid-admin">

                <?php foreach ($config['fields'] as $field => $meta): ?>

                    <label>
                        <?= e($meta[0]) ?>

                        <?php if ($meta[1] === 'textarea'): ?>

                            <textarea
                                name="<?= e($field) ?>"
                                rows="6"
                                <?= !empty($meta[2]) ? 'required' : '' ?>
                            ><?= e($row[$field] ?? '') ?></textarea>

                        <?php elseif (
                            $meta[1] === 'select'
                            || $meta[1] === 'ppdb_status'
                            || $meta[1] === 'message_status'
                            || $meta[1] === 'media_status'
                            || $meta[1] === 'teacher_position'
                            || $meta[1] === 'education'
                            || $meta[1] === 'featured_status'
                        ): ?>

                            <select name="<?= e($field) ?>">

                                <?php if ($meta[1] === 'ppdb_status'): ?>

                                    <?php
                                    $statusOptions = [
                                        'pending' => 'Pending',
                                        'verified' => 'Verified',
                                        'rejected' => 'Rejected',
                                    ];
                                    ?>

                                    <?php foreach ($statusOptions as $value => $label): ?>
                                        <option
                                            value="<?= e($value) ?>"
                                            <?= ($row[$field] ?? 'pending') === $value
                                                ? 'selected'
                                                : '' ?>
                                        >
                                            <?= e($label) ?>
                                        </option>
                                    <?php endforeach; ?>

                                <?php elseif ($meta[1] === 'featured_status'): ?>

                                    <?php foreach (['0' => 'Mitra Biasa', '1' => 'Mitra Unggulan'] as $value => $label): ?>
                                        <option value="<?= e($value) ?>" <?= (string)($row[$field] ?? '0') === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                                    <?php endforeach; ?>

                                <?php elseif ($meta[1] === 'media_status'): ?>

                                    <?php foreach (['published' => 'Tampil', 'draft' => 'Draft'] as $value => $label): ?>
                                        <option
                                            value="<?= e($value) ?>"
                                            <?= ($row[$field] ?? 'published') === $value
                                                ? 'selected'
                                                : '' ?>
                                        >
                                            <?= e($label) ?>
                                        </option>
                                    <?php endforeach; ?>

                                <?php elseif ($meta[1] === 'message_status'): ?>

                                    <?php foreach (['unread' => 'Unread', 'read' => 'Read'] as $value => $label): ?>
                                        <option
                                            value="<?= e($value) ?>"
                                            <?= ($row[$field] ?? 'unread') === $value
                                                ? 'selected'
                                                : '' ?>
                                        >
                                            <?= e($label) ?>
                                        </option>
                                    <?php endforeach; ?>

                                <?php elseif ($meta[1] === 'achievement_major'): ?>

                                    <?php foreach ([
                                        'RPL' => 'RPL',
                                        'TKJ' => 'TKJ',
                                        'PSPT' => 'PSPT',
                                        'DKV' => 'DKV',
                                    ] as $value => $label): ?>
                                        <option
                                            value="<?= e($value) ?>"
                                            <?= ($row[$field] ?? '') === $value ? 'selected' : '' ?>
                                        >
                                            <?= e($label) ?>
                                        </option>
                                    <?php endforeach; ?>

                                <?php elseif ($meta[1] === 'achievement_level'): ?>

                                    <?php foreach ([
                                        'Kota' => 'Kota',
                                        'Provinsi' => 'Provinsi',
                                        'Nasional' => 'Nasional',
                                    ] as $value => $label): ?>
                                        <option
                                            value="<?= e($value) ?>"
                                            <?= ($row[$field] ?? '') === $value ? 'selected' : '' ?>
                                        >
                                            <?= e($label) ?>
                                        </option>
                                    <?php endforeach; ?>

                                <?php elseif ($meta[1] === 'achievement_rank'): ?>

                                    <?php foreach ([
                                        'Juara 1' => 'Juara 1',
                                        'Juara 2' => 'Juara 2',
                                        'Juara 3' => 'Juara 3',
                                        'Finalis' => 'Finalis',
                                        'Penghargaan' => 'Penghargaan',
                                    ] as $value => $label): ?>
                                        <option
                                            value="<?= e($value) ?>"
                                            <?= ($row[$field] ?? '') === $value ? 'selected' : '' ?>
                                        >
                                            <?= e($label) ?>
                                        </option>
                                    <?php endforeach; ?>

                                <?php elseif ($meta[1] === 'pkl_category'): ?>

                                    <?php foreach ([
                                        'RPL' => 'RPL',
                                        'TKJ' => 'TKJ',
                                        'PSPT' => 'PSPT',
                                        'DKV' => 'DKV',
                                    ] as $value => $label): ?>
                                        <option
                                            value="<?= e($value) ?>"
                                            <?= ($row[$field] ?? '') === $value ? 'selected' : '' ?>
                                        >
                                            <?= e($label) ?>
                                        </option>
                                    <?php endforeach; ?>

                                <?php elseif ($meta[1] === 'teacher_position'): ?>

                                    <input
                                        type="text"
                                        name="<?= e($field) ?>"
                                        value="<?= e($row[$field] ?? '') ?>"
                                        list="teacherPositions"
                                        placeholder="Contoh: Guru Produktif RPL"
                                    >

                                <?php elseif ($meta[1] === 'education'): ?>

                                    <?php foreach (['D3', 'S1', 'S2', 'S3'] as $value): ?>
                                        <option value="<?= e($value) ?>" <?= ($row[$field] ?? 'S1') === $value ? 'selected' : '' ?>><?= e($value) ?></option>
                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <option
                                        value="published"
                                        <?= ($row[$field] ?? '') === 'published'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        Published
                                    </option>

                                    <option
                                        value="draft"
                                        <?= ($row[$field] ?? '') === 'draft'
                                            ? 'selected'
                                            : '' ?>
                                    >
                                        Draft
                                    </option>

                                <?php endif; ?>

                            </select>

                        <?php elseif ($meta[1] === 'image'): ?>

                            <input
                                type="file"
                                name="<?= e($field) ?>"
                                accept="image/jpeg,image/png,image/webp"
                            >
                            <small class="form-hint">Maks. 10 MB • otomatis dikompres ke WebP</small>

                            <?php if (!empty($row[$field])): ?>
                                <img
                                    class="preview"
                                    src="<?= e(img_url($row[$field])) ?>"
                                    alt="Preview"
                                >
                            <?php endif; ?>

                        <?php elseif ($meta[1] === 'pdf'): ?>

                            <input
                                type="file"
                                name="<?= e($field) ?>"
                                accept="application/pdf"
                                <?= $id ? '' : 'required' ?>
                            >
                            <small class="form-hint">Maks. 10 MB • otomatis dikompres ke WebP</small>

                            <?php if (!empty($row[$field])): ?>
                                <a
                                    href="<?= e(url('download-ppdb.php?id=' . (int) $id)) ?>"
                                >
                                    Lihat PDF saat ini
                                </a>
                            <?php endif; ?>

                        <?php else: ?>

                            <input
                                type="<?= e($meta[1]) ?>"
                                name="<?= e($field) ?>"
                                value="<?= e($row[$field] ?? '') ?>"
                                <?= !empty($meta[2]) ? 'required' : '' ?>
                            >

                        <?php endif; ?>
                    </label>

                <?php endforeach; ?>

            </div>

            <?php if ($entity === 'teachers'): ?>
                <datalist id="teacherPositions">
                    <option value="Kepala Sekolah">
                    <option value="Wakil Kepala Sekolah">
                    <option value="Guru">
                    <option value="Guru Produktif RPL">
                    <option value="Guru Produktif TKJ">
                    <option value="Guru Produktif DKV">
                    <option value="Guru Produktif PSPT">
                    <option value="Guru Normatif/Adaptif">
                    <option value="Staf Kurikulum">
                    <option value="Tata Usaha">
                    <option value="Perpustakaan">
                    <option value="Bendahara">
                </datalist>
            <?php endif; ?>

            <button
                class="button button-primary"
                type="submit"
            >
                <?= e($id ? 'Simpan Perubahan' : 'Simpan Data') ?>
            </button>
        </form>
    </section>
    <?php
} else {
    ?>
    <section class="panel">

        <div class="panel-heading">
            <div>
                <p class="eyebrow">CONTENT MODULE</p>

                <h2>
                    <?= e($config['label']) ?>
                </h2>

                <p class="panel-subtitle">
                    Tambah, edit, dan hapus konten dari database.
                </p>
            </div>

            <?php if ($config['fields']): ?>
                <a
                    class="button button-primary"
                    href="<?= e(
                        url(
                            'admin/manage.php?entity='
                            . $entity
                            . '&action=create'
                        )
                    ) ?>"
                >
                    + Tambah Data
                </a>
            <?php endif; ?>
        </div>

        <?php if ($success): ?>
            <div class="notice success">
                <?= e($success) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="notice error">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!$config['fields']): ?>
            <div class="admin-empty">
                Modul ini menggunakan halaman khusus untuk membaca data.
            </div>
        <?php else: ?>

            <?php if ($entity === 'internships'): ?>
                <?php
                $pklCount = (int) $pdo->query('SELECT COUNT(*) FROM internships')->fetchColumn();
                ?>
                <div class="admin-pkl-overview">
                    <div class="admin-pkl-overview-copy">
                        <span class="admin-pkl-kicker">PKL / CONTENT CONTROL</span>
                        <h3>Kelola program PKL sekolah.</h3>
                        <p>Data di sini langsung digunakan pada bagian <strong>Student Placement</strong> di website. Foto/logo dan link situs perusahaan dapat dikelola dari form ini.</p>
                    </div>
                    <div class="admin-pkl-count">
                        <small>PROGRAM AKTIF</small>
                        <strong><?= e((string) $pklCount) ?></strong>
                    </div>
                </div>
            <?php endif; ?>

            <div class="table-wrap<?= $entity === 'internships' ? ' admin-pkl-table-wrap' : '' ?>">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>

                            <?php foreach ($config['fields'] as $field => $meta): ?>
                                <th>
                                    <?= e($meta[0]) ?>
                                </th>
                            <?php endforeach; ?>

                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php
                        $rows = $pdo
                            ->query(
                                "SELECT * FROM `{$table}` "
                                . 'ORDER BY id DESC'
                            )
                            ->fetchAll();
                        ?>

                        <?php foreach ($rows as $item): ?>

                            <tr>
                                <td>
                                    <?= e($item['id']) ?>
                                </td>

                                <?php foreach ($config['fields'] as $field => $meta): ?>
                                    <td>

                                        <?php if (
                                            $meta[1] === 'image'
                                            && !empty($item[$field])
                                        ): ?>

                                            <img
                                                class="thumb"
                                                src="<?= e(img_url($item[$field])) ?>"
                                                alt=""
                                            >

                                        <?php elseif (
                                            $meta[1] === 'pdf'
                                            && !empty($item[$field])
                                        ): ?>

                                            <a
                                                href="<?= e(url('download-ppdb.php?id=' . (int) $item['id'])) ?>"
                                            >
                                                PDF
                                            </a>

                                        <?php elseif ($field === 'status'): ?>

                                            <span
                                                class="status-pill <?= e((string) $item[$field]) ?>"
                                            >
                                                <?= e((string) $item[$field]) ?>
                                            </span>

                                        <?php else: ?>

                                            <?= e(
                                                mb_substr(
                                                    (string) ($item[$field] ?? ''),
                                                    0,
                                                    90
                                                )
                                            ) ?>

                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>

                                <td>
                                    <a
                                        class="button button-muted"
                                        href="<?= e(
                                            url(
                                                'admin/manage.php?entity='
                                                . $entity
                                                . '&action=edit&id='
                                                . (int) $item['id']
                                            )
                                        ) ?>"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        class="button button-danger"
                                        href="<?= e(
                                            url(
                                                'admin/manage.php?entity='
                                                . $entity
                                                . '&action=delete&id='
                                                . (int) $item['id']
                                                . '&_csrf='
                                                . csrf_token()
                                            )
                                        ) ?>"
                                        onclick="return confirm('Hapus data ini?')"
                                    >
                                        Hapus
                                    </a>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

        <?php endif; ?>

    </section>
    <?php
}

require __DIR__ . '/_footer.php';

?>
