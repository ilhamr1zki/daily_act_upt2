<?php  

  $kepsekSD      = "/SD/i";
  $kepsekPAUD    = "/PAUD/i";

  $foundDataSD    = preg_match($kepsekSD, $_SESSION['c_kepsek']);
  $foundDataPAUD  = preg_match($kepsekPAUD, $_SESSION['c_kepsek']);

?>

<div class="error-page">
  <h3 class="headline text-yellow"> 404</h3>
    <div class="error-content">
      <h3><i class="glyphicon glyphicon-ban-circle text-yellow"></i> Akses Ditolak</h3>
        <p>Maaf Halaman Tidak Bisa Ditampilkan, Terjadi Kesalahan</p>
        <a href="<?php echo $basekepsek; ?>" class="btn btn-flat btn-danger btn-block">Halaman Utama</a>
    </div>
        <!-- /.error-content -->
</div>
      <!-- /.error-page -->

      <script type="text/javascript">
        $("#content_head").hide()
      </script>