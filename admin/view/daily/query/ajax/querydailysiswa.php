<?php  

  require '../../../../../php/config.php';
  require '../../../../../php/function.php';

  $timeOut        = $_SESSION['expire'];
    
  $timeRunningOut = time() + 5;

  $timeIsOut = 0;

  // echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

  if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

    $_SESSION['form_success'] = "session_time_out";

    $timeIsOut = 1;
    error_reporting(1);
      // exit;

  } else {

    $dataSiswa = mysqli_query($con, "
      SELECT * FROM data_murid_sd
      UNION 
      SELECT * FROM data_murid_tk
    ");

  }

?>

<link rel="icon" href="favicon.ico">
  
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?php echo $base; ?>imgstatis/favicon.jpg">

  <!-- Jquery -->
  <script type="text/javascript" src="<?php echo $base; ?>jquery.js"></script> 

  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <!-- Theme -->
  <link href="<?php echo $base; ?>theme/js/summernote.min.css" rel="stylesheet"/>
 
  <!-- Bootstrap 3.3.6 -->
  
  <!-- Font Awesome -->
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo $base; ?>theme/plugins/datatables/dataTables.bootstrap.css">
  
  <!-- jvectormap -->

  <!-- Theme style -->

  <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $base; ?>theme/plugins/iCheck/square/blue.css">

  <link rel="stylesheet" href="<?= $base; ?>theme/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= $base; ?>theme/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <!-- <link rel="stylesheet" href="<?= $base; ?>theme/bower_components/Ionicons/css/ionicons.min.css"> -->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= $base; ?>theme/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?= $base; ?>theme/dist/css/skins/_all-skins.min.css">

  <!-- Pace style -->
  <link rel="stylesheet" href="<?php echo $base; ?>theme/plugins/pace/pace.min.css">

  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  
  <!-- datetime -->
  <link href="<?php echo $base; ?>theme/datetime/css/bootstrap-datetimepicker.min.css" rel="stylesheet">

  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo $base; ?>theme/plugins/select2/select2.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $base; ?>theme/dist/css/AdminLTE.min.css">

  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $base; ?>theme/dist/css/skins/_all-skins.min.css">

  <link rel="stylesheet" href="<?php echo $base; ?>theme/dist/css/overridecss.css">
  
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style type="text/css">
    
    body {
      font-family:arial;
    }

    hr.new1 {
      border-top: 1px solid black;
    }

    .dt-input {
      margin-right: 10px !important;
    }

    #filterDate {
      display: flex;
      gap: 15px;
    }

    #tableFilter {
      margin-bottom: 20px;
    }

    #dotFrom {
      margin-left: 5px;
    }

    #cancel {
      transform: scaleX(-1);
    }

    #dotTo {
      margin-left: 5px;
    }

    #min {
      margin-left: 10px;
    }

    #max {
      margin-left: 10px;
    }

    #addIcon {
      top: 4px;
    }

    .tombol {
      display: flex;
      gap: 10px;
    }

    .form-isi {
      display: flex;
      flex-direction: column;
    }

    .swal2-show {
      width: 25% !important;
      font-size: 15px !important;
      font-weight: bold;
    }

    #spp1 {
      width: 20%;
      margin-right: 10px;
      text-align: end;
    }

    #tombol-cetak {
      display: flex; 
      gap: 5px; 
    }

    #tombol-cetak-pangkal {
      display: flex; 
      gap: 12px; 
      justify-content: center;
      margin-right: -4px;
    }

    #example1 {
      width: 167% !important;
      max-width: 200% !important;
    }

    #example_semua {
      width: 230% !important;
      max-width: 250% !important;
    }

    #example1_info {
      display: none;
    }

    .treeview-menu li a:hover {
      background-color: #ccc;
      color: black !important;
    }

    #example1 th {
      background-color: lightblue;
      border: 2px solid black;
    }

    #example1 tbody tr:hover {
      background-color: aqua;
    }

    #example_semua th {
      background-color: lightblue;
      border: 2px solid black;
    }

    #example_semua tbody tr:hover {
      background-color: aqua;
    }

    #isi_tahun_ajaran {
      margin-left: -80px;
      top: -5px;
      z-index: 1;
    }

    #pilih_tahun_ajaran {
      width: 70%;
    }

    #isi_tahun_ajaran_2 {
      margin-left: -367px;
      top: -5px;
    }

    #div_slash {
      margin-left: -38px;
      top: -5px;
    }

    .uang_spp, 
    .uang_pangkal, 
    .uang_regis,
    .uang_seragam,
    .uang_buku,
    .uang_kegiatan,
    .lain2 {
      width: 20%;
      margin-right: 10px; 
      text-align: end;
    }

    .ket_uang_spp, 
    .ket_uang_pangkal, 
    .ket_uang_regis,
    .ket_uang_seragam,
    .ket_uang_buku,
    .ket_uang_kegiatan,
    .ket_lain2 {
      width: 25%;
    }

    #spp2 {
      width: 25%;
    }

    .judul {
      width: 100%; 
      background-color: #FFF; 
      padding: 10px;
      margin-bottom: 10px; 
    }

    .select2-container {
      width: 110% !important;
    }

    .keterangan_juz {
      width: 200%;
    }

    #seqketsurahedit {
      width: 60%;
    }

    .flex-container {
      display: flex;
      justify-content: center;
    }

    .flex-container > div {
      border: 1px solid black;
      margin: 10px;
      width: 50%;
      padding: 20px;
    }

    .flex-containers {
      display: flex;
      justify-content: center;
    }

    .flex-containers > div {
      border: 1px solid black;
      margin: 10px;
      width: 100%;
      padding: 20px;
    }

    #total {
      float: right;
    }

    #forLabel {
      margin-top: 2px;
    }
    
    #jun22 {
      margin-left: 180px;
    }

    #tombol {
      width: 30%;
      margin-left: -30px;
    }

    #tahun_ajaran {
      width: 70px;
      margin-left: 10px;
      text-align: center;
      padding: 1px;
    }

    #slashx {
      width: 10px;
      font-size: 17px;
    }

    #tahun_ajaran_2 {
      width: 70px;
      text-align: center;
      padding: 1px;
    }

    #semesterx {
      margin-left: 35px;
      width: 71px;
      height: 25px;
    }

    @media only screen and (max-width: 600px) {

      .cobasidebar {
        margin-top: 17%;
      }

      #cobacontent {
        min-height: 250px !important;
        margin-right: auto !important;
        margin-left: auto !important;
        padding-left: 15px !important;
        padding-right: 15px !important;
      }

    }

    @media only screen and (max-width: 768px) {

      .uang_spp, 
      .uang_pangkal, 
      .uang_regis,
      .uang_seragam,
      .uang_buku,
      .uang_kegiatan,
      .lain2 {
        width: 40%;
        margin-right: 10px; 
        text-align: end;
      }

      #dotTo {
        margin-left: 23px;
      }

      #filterDate {
        display: flex;
        flex-direction: column;
      }

      #tahun_ajaran {
        width: 50px;
        margin-left: 9px;
        text-align: center;
        padding: 1px;
      }

      #slashx {
        width: 20px;
        font-size: 17px;
      }

      #tahun_ajaran_2 {
        width: 50px;
        margin-left: -8px;
        text-align: center;
        padding: 1px;
      }

      #semesterx {
        margin-left: 30px;
        width: 41px;
      }

      #tombol-cetak {
        display: flex; 
        gap: 5px; 
        flex-direction: column;
      }

      #tombol-cetak-pangkal {
        display: flex; 
        gap: 5px; 
        justify-content: center;
        flex-direction: column;
      }

      #example1 {
        width: 167% !important;
        max-width: 200% !important;
      }

      .tombol {
        display: flex;
        flex-direction: column;
        margin-right: 50px;
      }

      .ket_uang_spp, 
      .ket_uang_pangkal, 
      .ket_uang_regis,
      .ket_uang_seragam,
      .ket_uang_buku,
      .ket_uang_kegiatan,
      .ket_lain2 {
        width: 53%;
      }
      
      #tombol {
        width: 100%;
      }

      #save_record {
        width: 100%;
        margin-left: 29px;
      }

      #cek_pembayaran {
        width: 100%;
        margin-left: 29px;
      }

      #cetak_kuitansi_web {
        width: 100%;
        margin-left: 29px;
      }

      #cetak_slip_kuitansi {
        width: 100%;
        margin-left: 29px;
      }

      #jun22 {
        margin-left: 180px;
      }      

      #spp1 {
        width: 30%;
        margin-right: 10px;
        text-align: end;
      }

      #spp2 {
        width: 63%;
      }

      .flex-container {
        display: flex;
        justify-content: center;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
      }

      .flex-container > div {
        background-color: #f1f1f1;
        border: 1px solid black;
        margin: 10px;
        width: 100%;
        padding: 20px;
      }

      .flex-containers {
        display: flex;
        justify-content: center;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;
      }

      .flex-containers > div {
        background-color: white;
        border: 1px solid black;
        margin: 10px;
        width: 100%;
        padding: 20px;
      }

      .select2-container {
        width: 100% !important;
      }

      .keteranganAyat {
        width: 100%
      }

      #seqketsurahedit {
        width: 16%;
      }

      #example1 th {
        background-color: lightblue;
        border: 2px solid black;
        width: 127%;
      }

    }

    /* The Modal (background) */
    .modal-error {
      display: none; /* Hidden by default */
      position: fixed; /* Stay in place */
      z-index: 9999; /* Sit on top */
      padding-top: 100px; /* Location of the box */
      left: 0;
      top: 0;
      width: 100%; /* Full width */
      height: 100%; /* Full height */
      overflow: auto; /* Enable scroll if needed */
      background-color: rgb(0,0,0); /* Fallback color */
      background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content-error {
      background-color: #fefefe;
      margin: auto;
      padding: 20px;
      border: 1px solid #888;
      width: 30%;
    }

    /* The Close Button */
    .close-popup-err {
      color: #aaaaaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close-popup-err:hover,
    .close-popup-err:focus {
      color: #000;
      text-decoration: none;
      cursor: pointer;
    }

  </style>

<div class="box box-info">
  <div class="box-body table-responsive">

    <table id="list_siswa" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th style="text-align: center;" width="5%">NO</th>
          <th style="text-align: center;">NIS</th>
          <th style="text-align: center;">NAMA</th>
          <th style="text-align: center;">KELAS</th>
          <th style="text-align: center;">GENDER</th>
          <th style="text-align: center;"> DAILY </th>
        </tr>
      </thead>

      <tbody>
        <?php $no = 1; ?>
        <?php foreach($dataSiswa as $siswa) : ?>
          <tr>
            <td style="text-align: center;"> <?= $no++; ?> </td>
            <td style="text-align: center;"> <?= $siswa['nis']; ?> </td>
            <td style="text-align: center;"> <?= $siswa['nama']; ?> </td>
            <td style="text-align: center;"> <?= $siswa['kelas']; ?> </td>
            <?php if ($siswa['jk'] == 'L'): ?>
              <td style="text-align: center;"> LAKI - LAKI </td>
            <?php elseif($siswa['jk'] == 'P'): ?>
              <td style="text-align: center;"> PEREMPUAN </td>
            <?php endif ?>

            <td style="text-align: center;"> 
                <input type="hidden" name="nis" value="<?= $siswa['nis']; ?>">
                <input type="hidden" name="nama" value="<?= $siswa['nama']; ?>" id="nama_siswa">
                <button class="btn btn-sm btn-primary" onclick="lihatDaily(`<?= $siswa['nis']; ?>`, `<?= $siswa['nama']; ?>`)"> <i class="glyphicon glyphicon-eye-open"></i> DAILY </button> 
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>

    </table>
  </div>
</div>

<!-- Bootstrap 3.3.6 -->
<script src="<?php echo $base; ?>theme/bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="<?php echo $base; ?>theme/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $base; ?>theme/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo $base; ?>theme/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo $base; ?>theme/plugins/fastclick/fastclick.js"></script>
<!-- Sparkline -->
<script src="<?php echo $base; ?>theme/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<!-- SlimScroll 1.3.0 -->
<script src="<?php echo $base; ?>theme/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS 1.0.1 -->
<!-- AdminLTE App -->
<script src="<?php echo $base; ?>theme/dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $base; ?>theme/dist/js/demo.js"></script>
<script type="text/javascript">

  $('#list_siswa').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": true,
    "info": false,
    "autoWidth": false
  });

  $('#bgnNama').click(function(e){
    e.preventDefault()
    var spans = document.getElementsByTagName("li");
    for (var i = 0; i < spans.length; i++) {
        if (spans[i].className == 'dropdown user user-menu') {
            spans[i].classList.add("open")
        } else if (spans[i].className == 'dropdown user user-menu open') {
            spans[i].classList.remove("open")
        }
    }
  })

  document.getElementById('isiMenu').innerHTML = `<span style="font-weight: bold;"> QUERY - </span>` + `<span style="font-weight: bold;"> SISWA </span>`

  function lihatDaily(nis, nama) {
    // alert(nis)
    $.ajax({
      type : "POST",
      url  : "view/daily/query/ajax/teachercreatedaily.php",
      data : {
        nis : nis,
        nama : nama,
      },
      success : function(data) {
        $("#isi_konten").html(data);
      }
    })

  }

</script>