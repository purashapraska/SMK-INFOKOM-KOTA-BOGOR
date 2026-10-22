<?php

declare(strict_types=1);
require_once __DIR__ . '/../config/app.php';
require_login();
$user = current_user();
if (($user['role'] ?? '') !== 'ADMIN') {
    http_response_code(403);
    exit('Akses Visual Builder hanya tersedia untuk ADMIN.');
}
$title = 'Visual Website Builder';
$csrf = csrf_token();
$pages = [
    'home' => 'Beranda', 'profil' => 'Profil Sekolah', 'jurusan' => 'Semua Jurusan',
    'jurusan-rpl' => 'Jurusan — RPL', 'jurusan-tkj' => 'Jurusan — TKJ',
    'jurusan-dkv' => 'Jurusan — DKV', 'jurusan-pspt' => 'Jurusan — PSPT',
    'guru' => 'Guru & Tenaga Kependidikan', 'berita' => 'Informasi / Berita',
    'prestasi' => 'Prestasi', 'fasilitas' => 'Fasilitas', 'galeri' => 'Galeri',
    'kontak' => 'Kontak', 'ppdb' => 'PPDB', 'bkk' => 'BKK / Karier',
    'kunjungan-industri' => 'Kunjungan Industri',
    'pkl' => 'PKL', 'magang' => 'Magang Jepang', 'ekstrakurikuler' => 'Ekstrakurikuler',
    'mitra' => 'Mitra Industri',
];
$publishable = vb_publishable_pages();
$publicUrls = [];
foreach ($publishable as $key => $file) {
    $publicUrls[$key] = url($file);
}
// Detail jurusan use independent Visual Builder keys, while retaining the existing PHP page.
try {
    $majorRows = $pdo->query('SELECT id, name FROM majors ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
    foreach ($majorRows as $majorRow) {
        $name = mb_strtolower((string) ($majorRow['name'] ?? ''));
        $slug = str_contains($name, 'perangkat lunak') ? 'rpl' : (str_contains($name, 'komputer') || str_contains($name, 'jaringan') ? 'tkj' : (str_contains($name, 'desain') || str_contains($name, 'komunikasi visual') ? 'dkv' : (str_contains($name, 'siaran') || str_contains($name, 'televisi') ? 'pspt' : '')));
        if ($slug !== '') { $publicUrls['jurusan-' . $slug] = url($file = 'jurusan.php?id=' . (int) $majorRow['id']); }
    }
} catch (Throwable $e) { /* Keep the builder usable if the majors table is temporarily unavailable. */ }
require __DIR__ . '/_header.php';
?>
<link rel="stylesheet" href="https://unpkg.com/grapesjs@0.22.13/dist/css/grapes.min.css">
<style>
.vb-shell{margin:-8px;min-height:calc(100vh - 150px);background:#0b1220;color:#e5edf9;border-radius:16px;overflow:hidden;font-family:Inter,system-ui,sans-serif}.vb-top{display:flex;align-items:center;gap:12px;padding:14px 18px;background:#111c30;border-bottom:1px solid #24334b;flex-wrap:wrap}.vb-brand{font-weight:800;letter-spacing:.02em}.vb-top select,.vb-top button{background:#17243a;border:1px solid #33445e;color:#edf4ff;border-radius:9px;padding:9px 12px}.vb-top button{cursor:pointer}.vb-top button.primary{background:#2563eb;border-color:#2563eb}.vb-top button:disabled{opacity:.45;cursor:not-allowed}.vb-status{font-size:12px;color:#a8bbd8;margin-left:auto}.vb-notice{padding:10px 18px;background:#14233a;color:#bfd4f5;font-size:13px;border-bottom:1px solid #24334b}.vb-layout{display:grid;grid-template-columns:minmax(0,1fr) 270px;min-height:650px}.vb-editor{min-width:0}.vb-side{border-left:1px solid #24334b;background:#111c30;padding:16px;overflow:auto;max-height:650px}.vb-top button.publish{background:#16a34a;border-color:#16a34a;font-weight:700}.vb-top button.ghost{background:transparent}.vb-live{font-size:12px;padding:5px 10px;border-radius:999px;background:#1d2d46;color:#bcd0ee}.vb-live.on{background:#14532d;color:#bbf7d0}#vb-blocks .gjs-block{width:46%;min-height:64px;margin:1.5%}.vb-history-item .row{display:flex;gap:6px;margin-top:6px;flex-wrap:wrap}.vb-history-item button{background:#17243a;border:1px solid #33445e;color:#edf4ff;border-radius:7px;padding:4px 8px;font-size:11px;cursor:pointer}.vb-badge{display:inline-block;padding:1px 7px;border-radius:999px;font-size:10px;font-weight:800;margin-left:6px;background:#334155;color:#e2e8f0}.vb-badge.live{background:#16a34a;color:#fff}.vb-side h3{font-size:13px;text-transform:uppercase;letter-spacing:.08em;color:#9fb3d1;margin:6px 0 12px}.vb-history{font-size:12px;color:#c5d3e8;display:grid;gap:8px}.vb-history-item{border:1px solid #2c3e59;border-radius:8px;padding:8px}.vb-warning{font-size:12px;line-height:1.55;color:#9fb3d1;margin-top:18px}.gjs-one-bg{background-color:#101b2e!important}.gjs-two-color{color:#dce7f8!important}.gjs-three-bg{background-color:#1d2d46!important}.gjs-four-color,.gjs-four-color-h:hover{color:#60a5fa!important}.gjs-pn-panel{background:#111c30!important}.gjs-block{border-color:#344863!important;background:#17243a!important;color:#dce7f8!important}.gjs-block:hover{border-color:#60a5fa!important}.gjs-cv-canvas{background:#d9e2ef!important}.gjs-sm-sector-title{background:#1b2a41!important;color:#e6efff!important}.gjs-field input,.gjs-field select,.gjs-field textarea{color:#edf4ff!important;background:#17243a!important}.gjs-mdl-dialog{color:#182334}@media(max-width:900px){.vb-layout{grid-template-columns:1fr}.vb-side{border-left:0;border-top:1px solid #24334b}.vb-status{width:100%;margin-left:0}}
</style>
<div class="vb-shell">
  <div class="vb-top">
    <span class="vb-brand">INFOKOM / VISUAL BUILDER</span>
    <label for="vb-page" class="sr-only">Pilih halaman</label>
    <select id="vb-page" aria-label="Pilih halaman">
      <?php foreach ($pages as $key => $label): ?>
        <option value="<?= e($key) ?>" <?= isset($publishable[$key]) ? '' : 'disabled' ?>><?= e($label) ?><?= isset($publishable[$key]) ? '' : ' (segera)' ?></option>
      <?php endforeach; ?>
    </select>
    <button type="button" id="vb-desktop">Desktop</button>
    <button type="button" id="vb-tablet">Tablet</button>
    <button type="button" id="vb-mobile">Mobile</button>
    <button type="button" id="vb-reset" class="ghost" title="Buang perubahan di canvas dan muat ulang dari website asli">Muat ulang dari website</button>
    <button type="button" id="vb-save" class="primary">Simpan Draft</button>
    <button type="button" id="vb-preview" class="ghost" title="Buka draft tersimpan di tab baru">Pratinjau</button>
    <button type="button" id="vb-publish" class="publish">Publikasikan</button>
    <button type="button" id="vb-unpublish" class="ghost" title="Website publik kembali memakai desain asli">Kembalikan ke desain asli</button>
    <span id="vb-live" class="vb-live">Memeriksa…</span>
    <span id="vb-status" class="vb-status" role="status">Memuat editor…</span>
  </div>
  <div class="vb-notice">Canvas memuat tampilan asli website. Area bergaris biru berlabel <b>BLOK OTOMATIS</b> isinya tetap dari database (jurusan, video, Instagram, galeri, lokasi, angka, teks berjalan): posisi, urutan, dan latar bagian itu bisa diatur, tetapi isinya tidak diedit di sini. Bagian lain bebas diedit. Klik <b>Publikasikan</b> untuk menayangkan ke pengunjung; <b>Kembalikan ke desain asli</b> membatalkan kapan saja.</div>
  <div class="vb-layout">
    <div id="gjs" class="vb-editor"></div>
    <aside class="vb-side">
      <h3>Tambah Komponen</h3>
      <div id="vb-blocks"></div>
      <h3 style="margin-top:18px">Atribut (link, alt gambar)</h3>
      <div id="vb-trait-manager"></div>
      <h3 style="margin-top:18px">Properti & Layers</h3>
      <div id="vb-style-manager"></div>
      <div id="vb-layer-manager"></div>
      <h3 style="margin-top:22px">Riwayat</h3>
      <div id="vb-history" class="vb-history">Memuat riwayat…</div>
      <p class="vb-warning">Ganti gambar: klik dua kali gambar, lalu unggah atau pilih. Ganti link/tombol: pilih elemen, lalu isi <i>Href</i> di panel Atribut. Gambar latar bagian (misal hero): pilih bagiannya, buka <i>Warna &amp; Latar</i>, atur <i>Background</i>.</p>
    </aside>
  </div>
</div>
<script src="https://unpkg.com/grapesjs@0.22.13/dist/grapes.min.js"></script>
<script>
(() => {
  const csrf = <?= json_encode($csrf) ?>;
  const api = <?= json_encode(url('admin/visual-builder-api.php')) ?>;
  const publicUrls = <?= json_encode($publicUrls) ?>;
  const pageSelect = document.getElementById('vb-page');
  const statusEl = document.getElementById('vb-status');
  const liveEl = document.getElementById('vb-live');
  const historyEl = document.getElementById('vb-history');
  const buttons = {
    save: document.getElementById('vb-save'), publish: document.getElementById('vb-publish'),
    unpublish: document.getElementById('vb-unpublish'), preview: document.getElementById('vb-preview'),
    reset: document.getElementById('vb-reset'),
  };
  let loaded = false;
  let siteHead = null;

  const CANVAS_CSS = `
    [class*="reveal"],.profile-timeline-item,.contact-map-panel-premium,.info-video-card,.facility-showcase-card,.facility-card-view,.guru-view,.instagram-home-card,.ig-gallery-hero-copy .eyebrow,.ig-gallery-hero-copy h1,.ig-gallery-hero-copy>p,.ig-gallery-hero-link{opacity:1!important;transform:none!important;transition:none!important;animation:none!important}
    [data-vb-dynamic]{position:relative;outline:2px dashed rgba(37,99,235,.7);outline-offset:-2px}
    [data-vb-dynamic]::before{content:"BLOK OTOMATIS · " attr(data-vb-dynamic);position:absolute;top:0;left:0;z-index:60;padding:5px 9px;border-radius:0 0 8px 0;background:#2563eb;color:#fff;font:700 11px/1 system-ui,sans-serif;letter-spacing:.04em;pointer-events:none}
  `;

  async function handleUpload(event) {
    const files = event.dataTransfer ? event.dataTransfer.files : event.target.files;
    for (const file of Array.from(files || [])) {
      setStatus('Mengunggah gambar…');
      const form = new FormData();
      form.append('file', file);
      try {
        const response = await fetch(`${api}?action=upload&page=${encodeURIComponent(pageSelect.value)}`, {
          method: 'POST', credentials: 'same-origin', headers: { 'Accept': 'application/json', 'X-CSRF-Token': csrf }, body: form,
        });
        const data = await response.json();
        if (!response.ok || !data.ok) throw new Error(data.message || 'Upload gagal.');
        (editor.Assets || editor.AssetManager).add({ type: 'image', src: data.src });
        setStatus('Gambar diunggah. Klik gambarnya di daftar untuk memakainya.');
      } catch (error) { setStatus(error.message, true); }
    }
  }

  const editor = grapesjs.init({
    container: '#gjs', height: '650px', fromElement: false, storageManager: false, protectedCss: '',
    selectorManager: { componentFirst: true },
    panels: { defaults: [ { id: 'panel-top', el: '.vb-top', buttons: [] } ] },
    blockManager: { appendTo: '#vb-blocks', blocks: [] },
    traitManager: { appendTo: '#vb-trait-manager' },
    styleManager: { appendTo: '#vb-style-manager', sectors: [
      { name: 'Ukuran & Layout', open: true, buildProps: ['display','width','min-height','margin','padding','gap','flex-direction','justify-content','align-items'] },
      { name: 'Tipografi', open: false, buildProps: ['font-family','font-size','font-weight','color','line-height','letter-spacing','text-align','text-decoration'] },
      { name: 'Warna & Latar', open: false, buildProps: ['background-color','background','border','border-radius','box-shadow'] },
    ]},
    layerManager: { appendTo: '#vb-layer-manager' },
    deviceManager: { devices: [
      { name: 'Desktop', width: '' }, { name: 'Tablet', width: '768px', widthMedia: '992px' },
      { name: 'Mobile', width: '375px', widthMedia: '480px' },
    ]},
    canvas: { scripts: [], styles: [] },
    assetManager: { assets: [], upload: `${api}?action=upload`, uploadFile: handleUpload, autoAdd: false },
    plugins: [],
  });

  // Blok yang isinya dari database: wrapper bisa dipindah/diatur, isi di dalamnya terkunci.
  function lockChildren(component) {
    component.components().forEach((child) => {
      child.set({ selectable: false, hoverable: false, editable: false, draggable: false, droppable: false,
        removable: false, copyable: false, layerable: false, highlightable: false });
      lockChildren(child);
    });
  }
  editor.DomComponents.addType('vb-dynamic', {
    isComponent: (el) => (el.nodeType === 1 && el.hasAttribute('data-vb-dynamic') ? { type: 'vb-dynamic' } : undefined),
    model: {
      defaults: { name: 'Blok otomatis', droppable: false, copyable: false },
      init() { lockChildren(this); },
    },
  });

  const blocks = [
    ['vb-section','Section','<section style="padding:64px 28px;background:#f8fafc;color:#0f172a"><div style="max-width:1100px;margin:0 auto"><h2>Judul section</h2><p>Tambahkan deskripsi section di sini.</p></div></section>'],
    ['vb-heading','Judul','<h2 style="font-size:36px;font-weight:800;color:#0f172a;margin:0 0 16px">Judul baru</h2>'],
    ['vb-text','Paragraf','<p style="font-size:16px;line-height:1.8;color:#475569">Tulis deskripsi atau informasi di sini.</p>'],
    ['vb-button','Tombol','<a href="#" style="display:inline-block;padding:12px 20px;border-radius:8px;background:#1d4ed8;color:#fff;text-decoration:none;font-weight:700">Pelajari lebih lanjut</a>'],
    ['vb-card','Kartu','<article style="padding:24px;border-radius:16px;background:#fff;color:#0f172a;box-shadow:0 10px 30px rgba(15,23,42,.08)"><h3 style="font-size:22px;font-weight:800;margin:0 0 10px">Judul kartu</h3><p style="line-height:1.7;color:#475569">Deskripsi singkat untuk kartu ini.</p></article>'],
    ['vb-image','Gambar','<figure style="margin:0"><img src="https://placehold.co/1200x600/e2e8f0/334155?text=Ganti+gambar" alt="Deskripsi gambar" style="width:100%;height:auto;display:block;border-radius:12px"><figcaption style="font-size:13px;color:#64748b;margin-top:8px">Keterangan gambar</figcaption></figure>'],
    ['vb-hero','Hero','<section style="padding:80px 28px;background:#0b1f42;color:#fff"><div style="max-width:1100px;margin:0 auto"><p style="text-transform:uppercase;letter-spacing:.15em;color:#facc15;font-weight:800">SMK INFOKOM KOTA BOGOR</p><h1 style="font-size:clamp(36px,6vw,68px);line-height:1.05;font-weight:800;margin:14px 0">Tulis judul utama di sini</h1><p style="font-size:18px;line-height:1.8;max-width:680px;color:#dbeafe">Tambahkan penjelasan singkat tentang sekolah.</p><a href="#" style="display:inline-block;margin-top:20px;padding:13px 22px;background:#facc15;color:#0b1f42;border-radius:8px;text-decoration:none;font-weight:800">Kenali Sekolah</a></div></section>'],
  ];
  blocks.forEach(([id,label,content]) => editor.BlockManager.add(id,{label,category:'Komponen',content,select:true,activate:true}));

  function setStatus(message, bad = false) { statusEl.textContent = message; statusEl.style.color = bad ? '#fca5a5' : '#a8bbd8'; }
  function setBusy(busy) { Object.values(buttons).forEach((b) => { b.disabled = busy; }); }

  async function request(action, page, payload, extra = {}) {
    const opts = { credentials: 'same-origin', headers: { 'Accept': 'application/json' } };
    if (payload) { opts.method = 'POST'; opts.headers['Content-Type'] = 'application/json'; opts.headers['X-CSRF-Token'] = csrf; opts.body = JSON.stringify(payload); }
    const query = new URLSearchParams({ action, page, ...extra });
    const response = await fetch(`${api}?${query.toString()}`, opts);
    let data;
    try { data = await response.json(); } catch (e) { throw new Error('Respons server tidak valid (HTTP ' + response.status + ').'); }
    if (!response.ok || !data.ok) throw new Error(data.message || 'Permintaan gagal.');
    return data;
  }

  // ---- Ambil tampilan asli halaman publik (isi + CSS situs) ----
  async function fetchSnapshot(pageKey) {
    const base = publicUrls[pageKey];
    if (!base) throw new Error('Halaman ini belum bisa dimuat dari website.');
    const sep = base.includes('?') ? '&' : '?';
    const response = await fetch(`${base}${sep}vb_snapshot=1&_=${Date.now()}`, { credentials: 'same-origin', headers: { 'Accept': 'text/html' } });
    if (!response.ok) throw new Error('Tidak dapat membaca halaman asli (HTTP ' + response.status + ').');
    const text = await response.text();
    const START = '<!--VB-CONTENT-START-->', END = '<!--VB-CONTENT-END-->';
    const a = text.indexOf(START), b = text.indexOf(END);
    if (a < 0 || b < 0 || b < a) throw new Error('Halaman asli belum memiliki penanda Visual Builder. Pastikan index.php dan includes/visual-builder.php sudah diperbarui, dan Anda login sebagai ADMIN.');
    const doc = new DOMParser().parseFromString(text, 'text/html');
    // CSS situs: <link>/<style> dari head, plus <style> inline halaman (dipisah dari isi saat snapshot).
    const styles = Array.from(doc.querySelectorAll('link[rel="stylesheet"], style'));
    return { content: text.slice(a + START.length, b), styles, baseUrl: response.url };
  }

  function applySiteHead() {
    if (!siteHead) return;
    const doc = editor.Canvas.getDocument();
    if (!doc || !doc.head) return;
    doc.querySelectorAll('[data-vb-site]').forEach((n) => n.remove());
    const baseTag = doc.createElement('base');
    baseTag.setAttribute('href', siteHead.baseUrl); baseTag.setAttribute('data-vb-site', '1');
    doc.head.prepend(baseTag);
    siteHead.styles.forEach((node) => {
      const clone = doc.importNode(node, true);
      clone.setAttribute('data-vb-site', '1');
      doc.head.appendChild(clone);
    });
    const extra = doc.createElement('style');
    extra.setAttribute('data-vb-site', '1'); extra.textContent = CANVAS_CSS;
    doc.head.appendChild(extra);
  }
  editor.on('canvas:frame:load', applySiteHead);

  // Segarkan isi blok otomatis dengan data terbaru dari website.
  function refreshDynamic(content) {
    const fresh = {};
    const doc = new DOMParser().parseFromString('<body>' + content + '</body>', 'text/html');
    doc.querySelectorAll('[data-vb-dynamic]').forEach((el) => { fresh[el.getAttribute('data-vb-dynamic')] = el.innerHTML; });
    editor.getWrapper().find('[data-vb-dynamic]').forEach((component) => {
      const key = component.getAttributes()['data-vb-dynamic'];
      if (Object.prototype.hasOwnProperty.call(fresh, key)) {
        component.components(fresh[key]);
        lockChildren(component);
      }
    });
  }

  function buildOutput() {
    const doc = new DOMParser().parseFromString(editor.getHtml(), 'text/html');
    doc.querySelectorAll('[data-vb-dynamic]').forEach((el) => el.replaceChildren());
    doc.querySelectorAll('script').forEach((el) => el.remove());
    return { html: doc.body.innerHTML.trim(), css: editor.getCss({ avoidProtected: true }) };
  }

  function showFromSite(snap) {
    siteHead = snap;
    editor.Css.clear();
    editor.setComponents(snap.content);
    applySiteHead();
    editor.UndoManager.clear();
  }
  function showRevision(revision, snap) {
    siteHead = snap;
    editor.loadProjectData(JSON.parse(revision.project_data));
    refreshDynamic(snap.content);
    applySiteHead();
    editor.UndoManager.clear();
  }

  const STATUS_LABEL = { draft: 'Draft', published: 'TAYANG', archived: 'Arsip' };
  function renderLive(live) {
    if (live) { liveEl.textContent = `Tayang: revisi ${live.revision_no}`; liveEl.classList.add('on'); }
    else { liveEl.textContent = 'Belum dipublikasikan (website memakai desain asli)'; liveEl.classList.remove('on'); }
    buttons.unpublish.disabled = !live;
  }
  function renderHistory(items) {
    historyEl.replaceChildren();
    if (!items || !items.length) { historyEl.textContent = 'Belum ada revisi tersimpan.'; return; }
    items.forEach((item) => {
      const row = document.createElement('div'); row.className = 'vb-history-item';
      const title = document.createElement('strong'); title.textContent = `Revisi ${item.revision_no}`;
      const badge = document.createElement('span'); badge.className = 'vb-badge' + (item.status === 'published' ? ' live' : ''); badge.textContent = STATUS_LABEL[item.status] || item.status;
      title.append(badge);
      const time = document.createElement('div'); time.textContent = item.created_at;
      const actions = document.createElement('div'); actions.className = 'row';
      const load = document.createElement('button'); load.type = 'button'; load.textContent = 'Muat';
      load.addEventListener('click', () => loadRevision(item.id));
      actions.append(load);
      if (item.status !== 'published') {
        const live = document.createElement('button'); live.type = 'button'; live.textContent = 'Tayangkan versi ini';
        live.addEventListener('click', () => publishRevision(item.id, item.revision_no));
        actions.append(live);
      }
      row.append(title, time, actions); historyEl.append(row);
    });
  }
  async function refreshMeta() {
    const data = await request('load', pageSelect.value);
    renderLive(data.live); renderHistory(data.history);
  }

  async function loadPage() {
    loaded = false; setBusy(true); setStatus('Memuat tampilan website…');
    try {
      const key = pageSelect.value;
      const data = await request('load', key);
      const snap = await fetchSnapshot(key);
      if (data.revision && data.revision.project_data) {
        showRevision(data.revision, snap);
        setStatus(`Revisi ${data.revision.revision_no} dimuat (blok otomatis disegarkan dari website)`);
      } else {
        showFromSite(snap);
        setStatus('Tampilan asli website dimuat. Edit, lalu Simpan Draft atau Publikasikan.');
      }
      renderLive(data.live); renderHistory(data.history); loaded = true;
    } catch (error) { setStatus(error.message, true); historyEl.textContent = 'Tidak dapat memuat.'; }
    finally { setBusy(false); if (!loaded) buttons.unpublish.disabled = true; }
  }

  async function loadRevision(id) {
    if (!window.confirm('Memuat revisi ini akan mengganti isi canvas. Perubahan yang belum disimpan akan hilang. Lanjutkan?')) return;
    setBusy(true); setStatus('Memuat revisi…');
    try {
      const key = pageSelect.value;
      const data = await request('load', key, null, { revision: id });
      const snap = await fetchSnapshot(key);
      if (!data.revision) throw new Error('Revisi tidak ditemukan.');
      showRevision(data.revision, snap);
      setStatus(`Revisi ${data.revision.revision_no} dimuat. Belum tayang sampai Anda menekan Publikasikan.`);
    } catch (error) { setStatus(error.message, true); }
    finally { setBusy(false); }
  }

  async function saveDraft() {
    const out = buildOutput();
    return request('save', pageSelect.value, { project_data: editor.getProjectData(), html: out.html, css: out.css });
  }

  async function publishRevision(id, no) {
    if (!window.confirm(`Tayangkan revisi ${no} ke website publik sekarang?`)) return;
    setBusy(true); setStatus('Mempublikasikan…');
    try {
      const result = await request('publish', pageSelect.value, { revision_id: id });
      setStatus(result.message); await refreshMeta();
    } catch (error) { setStatus(error.message, true); }
    finally { setBusy(false); }
  }

  buttons.save.addEventListener('click', async () => {
    if (!loaded) return;
    setBusy(true); setStatus('Menyimpan draft…');
    try { const data = await saveDraft(); setStatus(`Draft revisi ${data.revision_no} tersimpan (belum tayang)`); await refreshMeta(); }
    catch (error) { setStatus(error.message, true); }
    finally { setBusy(false); }
  });

  buttons.publish.addEventListener('click', async () => {
    if (!loaded) return;
    if (!window.confirm('Simpan dan tayangkan desain ini ke website publik sekarang? Pengunjung akan langsung melihat perubahannya. Anda bisa membatalkannya kapan saja.')) return;
    setBusy(true); setStatus('Menyimpan lalu mempublikasikan…');
    try {
      const saved = await saveDraft();
      const result = await request('publish', pageSelect.value, { revision_id: saved.revision_id });
      setStatus(result.message); await refreshMeta();
    } catch (error) { setStatus(error.message, true); }
    finally { setBusy(false); }
  });

  buttons.unpublish.addEventListener('click', async () => {
    if (!window.confirm('Kembalikan website publik ke desain asli? Hasil edit tetap tersimpan sebagai riwayat.')) return;
    setBusy(true);
    try { const result = await request('unpublish', pageSelect.value, {}); setStatus(result.message); await refreshMeta(); }
    catch (error) { setStatus(error.message, true); }
    finally { setBusy(false); }
  });

  buttons.preview.addEventListener('click', () => {
    const base = publicUrls[pageSelect.value];
    if (base) window.open(`${base}${base.includes('?') ? '&' : '?'}vb_preview=draft`, '_blank', 'noopener');
  });

  buttons.reset.addEventListener('click', async () => {
    if (!window.confirm('Buang isi canvas saat ini dan muat ulang tampilan asli dari website?')) return;
    setBusy(true); setStatus('Memuat tampilan asli…');
    try { showFromSite(await fetchSnapshot(pageSelect.value)); setStatus('Tampilan asli dimuat ulang.'); }
    catch (error) { setStatus(error.message, true); }
    finally { setBusy(false); }
  });

  pageSelect.addEventListener('change', loadPage);
  document.getElementById('vb-desktop').addEventListener('click', () => editor.setDevice('Desktop'));
  document.getElementById('vb-tablet').addEventListener('click', () => editor.setDevice('Tablet'));
  document.getElementById('vb-mobile').addEventListener('click', () => editor.setDevice('Mobile'));

  if (typeof editor.onReady === 'function') editor.onReady(loadPage); else editor.on('load', loadPage);
})();
</script>
<?php require __DIR__ . '/_footer.php'; ?>
