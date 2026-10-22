<?php
    $wa=preg_replace('/\D+/','',setting('whatsapp_number',''));
    $ig=setting('instagram_url','');
$waMessage=setting('whatsapp_message','Halo, saya ingin mendapatkan informasi tentang SMK Infokom Kota Bogor.');
$waLink=$wa ? 'https://wa.me/' . $wa . '?text=' . rawurlencode($waMessage) : '';
?>
<?php
$waHref = $wa
    ? $waLink
    : url('kontak.php');
?>
<a
 aria-label="Hubungi WhatsApp"
 class="wa-float"
 href="<?=e($waHref)?>"
 rel="noopener"
 target="_blank"
>
 <span>
  WA
 </span>
 <b>
  Hubungi Kami
 </b>
</a>
<footer class="site-footer">
 <div class="container footer-grid">
  <div>
   <a class="brand footer-brand" href="<?=e(url(''))?>">
    <img alt="Logo" src="<?=e(url(setting('logo_path','assets/images/logo-infokom.svg')))?>"/>
    <span>
     <strong>
      SMK INFOKOM
     </strong>
     <small>
      <?=e(mb_strtoupper(setting('school_city','BOGOR')))?>
     </small>
    </span>
   </a>
   <p>
    <?=e(setting('school_address','Kota Bogor, Jawa Barat'))?>
   </p>
  </div>
  <div>
   <h3>
    Navigasi
   </h3>
   <a href="<?=e(url('profil.php'))?>">
    Profil
   </a>
   <a href="<?=e(url('jurusan.php'))?>">
    Jurusan
   </a>
   <a href="<?=e(url('guru.php'))?>">
    Guru
   </a>
   <a href="<?=e(url('ppdb.php'))?>">
    PPDB
   </a>
  </div>
  <div>
   <h3>
    Program
   </h3>
   <a href="<?=e(url('pkl.php'))?>">
    PKL
   </a>
   <a href="<?=e(url('magang.php'))?>">
    Magang Jepang
   </a>
   <a href="<?=e(url('bkk.php'))?>">
    BKK
   </a>
   <a href="<?=e(url('kunjungan-industri.php'))?>">
    Kunjungan Industri
   </a>
   <a href="<?=e(url('prestasi.php'))?>">
    Prestasi
   </a>
   <a href="<?=e(url('ekstrakurikuler.php'))?>">
    Ekstrakurikuler
   </a>
   <a href="<?=e(url('berita.php'))?>">
    Berita
   </a>
  </div>
  <div>
   <h3>
    Kontak
   </h3>
   <p>
    <?=e(setting('school_phone','-'))?>
   </p>
   <p>
    <?=e(setting('school_email','-'))?>
   </p>
   <?php if($ig):?>
   <a href="<?=e($ig)?>" rel="noopener" target="_blank">
    Instagram Sekolah &nearrow;
   </a>
   <?php endif;?>
  </div>
 </div>
 <div class="footer-bottom">
  <div class="container">
   <span>
    &copy; <?=date('Y')?> <?=e(setting('school_name',APP_NAME))?>
   </span>
   <span>
    Website Profil Sekolah
   </span>
  </div>
 </div>
</footer>
<button aria-label="Kembali ke atas" class="back-top" id="backTop">
 &uarr;
</button>
<script src="https://www.instagram.com/embed.js" async>
</script>
<script src="<?=e(url('assets/js/app.js?v=20261018-location-hybrid-1'))?>">
</script>
