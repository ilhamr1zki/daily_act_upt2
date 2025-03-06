<?php 
  
  date_default_timezone_set('Asia/Jakarta');
  $date=date('Y-m-d'); 
  
  require '../php/config.php'; 
  require '../php/function.php'; 
  // require('spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
  // require('spreadsheet-reader-master/SpreadsheetReader.php');
  
  // Cek status login user jika tidak ada session
  if (!$user->isLoggedInAdmin()) { 

    header("location:../"); //Redirect ke halaman login  
  }

  $nama_role = $_SESSION['key_admin_paud'];

  // echo $_SESSION['key_admin_paud'];exit;

  $currTahun    = "";
  $currSemester = "";

  $createDaily  = 0; 

  $check = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tahun_ajaran WHERE status = 'aktif' AND c_role = '$_SESSION[key_admin_paud]' "));

  if ($check != 0) {

    $currTahun = mysqli_query($con, "SELECT tahun FROM tahun_ajaran WHERE status = 'aktif' AND c_role = '$_SESSION[key_admin_paud]' ");
    $currTahun = mysqli_fetch_assoc($currTahun)['tahun'];
    // echo $currTahun;exit;
    $currSemester = mysqli_query($con, "SELECT semester FROM tahun_ajaran WHERE status = 'aktif' AND c_role = '$_SESSION[key_admin_paud]' ");
    $currSemester = mysqli_fetch_assoc($currSemester)['semester'];

  } else {

    $currTahun    = "";
    $currSemester = "";

  }

  $na = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM admin where c_admin = '$_SESSION[key_admin_paud]' ")); 

  //$setting=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM setting limit 1 "));*/ 
  if (isset($_GET['nextPage'])) {
    echo $_GET['nextPage'];exit;
  }

  if (isset($_POST['sg_out'])) {
    echo "Out";exit;
    session_destroy();
    session_unset($_SESSION['key_admin_paud']);
    header("location:../login");
  }

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> AIIS - ACTIVITY DAILY </title>
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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Ionicons -->
  <!-- <link rel="stylesheet" href="<?= $base; ?>theme/bower_components/Ionicons/css/ionicons.min.css"> -->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= $base; ?>theme/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?= $base; ?>theme/dist/css/skins/_all-skins.min.css">

  <script>
  function pageValidation()
  {
      var valid=true;
      var pageNo = $('#page-no').val();
      var totalPage = $('#total-page').val();
      if(pageNo == ""|| pageNo < 1 || !pageNo.match(/\d+/) || pageNo > parseInt(totalPage)){
          $("#page-no").css("border-color","#ee0000").show();
          valid=false;
      }
      return valid;
  }
  </script>

  <script type="text/javascript">
      $(document).ready(function() {
        $("#cari").keyup(function(){
        $("#fbody").find("tr").hide();
        var data = this.value.split("");
        var jo = $("#fbody").find("tr");
        $.each(data, function(i, v)
        {
              jo = jo.filter("*:contains('"+v+"')");
        });
          jo.fadeIn();

        })
  });

  </script>

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

    span #cke_25, 
    span #cke_11,
    span #cke_19,
    span #cke_21,
    span #cke_30,
    span #cke_32,
    span #cke_46 {
      display: none;
    }

    .select2-selection {
      height: 315px;
    }

    #list_dashboard {
      cursor: pointer;
    }

    #tr_joingroup {
      cursor: pointer;
    }

    li #v_all:hover {
      background-color: #ddd;
    }

    body {
      font-family:arial;
    }

    hr.new1 {
      border-top: 1px solid black;
    }

    #jenis_pembayaran {
      width: 215px;
    }

    #swal2-title {
      font-size: 17px;
    }

    #gambar {
      height: 100%;
      width: 100%;
      margin-top: 10px;
      object-fit: cover;
      background: #dfdfdf;
      margin-bottom: 10px;
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
        margin-top: 3%;
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

      #btn_sv_group {
        width: 100%;
      }

      #jenis_pembayaran {
        width: 100%;
      }

      .swal2-show {
        width: 50% !important;
        font-size: 15px !important;
        font-weight: bold;
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
</head>

<body class="skin-blue hold-transition fixed" 
oncontextmenu="return false">

<div class="modal fade" id="modal-default">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom-color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <center>
            <h4 class="modal-title"> <strong> INFO PEMBAYARAN SISWA </strong> </h4>
          </center>
      </div>
      <div class="modal-body" style="margin-bottom: 10px;">

          <div class="box-body" style="padding-left: 60px; padding-right: 60px;">

            <form role="form" id="forms">

              <div class="form-group">
                <label style="font-size: 13px;"> STATUS PEMBAYARAN : <strong style="color: yellowgreen;"> BELUM DIBAYAR </strong> <i class="fa fa-fw fa-hourglass-half" style="color: yellowgreen;"></i> </label>
              </div>

              <div class="form-group">
                <label> SISWA </label>
                <input type="text" readonly id="nm_siswa" name="nm_siswa" class="form-control">
              </div>

              <div class="form-group">
                <label> TANGGAL DIBUAT </label>
                <input type="text" readonly id="tanggal_dibuat" name="tanggal_dibuat" class="form-control">
              </div>

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="jenis_pembayaran">JENIS PEMBAYARAN</label>
                    <input type="text" id="jenis_pembayaran" style="font-size: 13px;" value="REGISTRASI/DAFTAR ULANG" name="jenis_pembayaran" readonly class="form-control">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="nominal_byr">NOMINAL</label>
                    <input type="text" id="nominal_byr" name="nominal_byr" readonly class="form-control">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="keterangan">KETERANGAN</label>
                <div class="isiKeterangan" id="keterangan" style="height: 150px;border: 1px solid #eee;padding: 10px; background-color: #eee;" class="form-control">
                  
                </div>
                <!-- <textarea style="height: 150px;" class="form-control" id="main_daily" rows="3" placeholder="Announcement ..."></textarea> -->
              </div>

            </form>

          </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="close_approve" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>AKH</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"> <b style="font-size: 17px;"> AIIS - DAILY ACTIVITY </b> </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
     <a href="#" class="glyphicon glyphicon-th" data-toggle="offcanvas" role="button" style="color: #fff;margin-top: 15px;margin-left: 15px;font-size: 15px;"> Menu
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" id="untukNotifNoApprKepsek" data-toggle="dropdown">
              <i class="glyphicon glyphicon-envelope"></i>
              <span class="label label-warning" id="notifInfoList">  </span>
            </a>
            <ul class="dropdown-menu">
              <li class="header" id="pesanInfo" style="text-align: center;"> </li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" id="isi_pesan_info">
                  
                </ul>
              </li>
              <!-- <li class="footer"><a href="listinfo" id="v_all"> Lihat Semua (<strong id="viewAllInfo"></strong>) Pemberitahuan </a></li> -->
            </ul>
          </li>

          <li class="active">
            <!-- <a><i class="glyphicon glyphicon-stats"></i> <?php echo $ata['tahun']; ?> Semester <?php echo $ata['semester']; ?></a> -->
            <a><i class="glyphicon glyphicon-stats"></i> <?= $currTahun; ?> Semester <?= $currSemester; ?></a>
          </li>

        <?php /*if(empty($_GET['thisaction']) or $_GET['thisaction']!='grafik'){ ?>
          <li>
            <a href="<?php echo $basead; ?>a-control/<?php echo md5('testtoken').'/'.$t1=md5(date('H:i:s')); ?>"><i class="glyphicon glyphicon-stats"></i> Test Token</a>
          </li>
          <li>
            <a href="<?php echo $basecon; ?>grafik"><i class="glyphicon glyphicon-stats"></i> Grafik</a>
          </li>
        <?php } else{?>
          <li class="active">
            <a href="<?php echo $basecon; ?>grafik"><i class="glyphicon glyphicon-stats"></i> Grafik</a>
          </li>
        <?php }*/ ?>
          

          <li class="dropdown user user-menu" id="bgnNama">

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo $base; ?>imgstatis/logo2.png" class="user-image" alt="User Image">
                <span class="hidden-xs"><?php echo ucfirst($_SESSION['name_user_paud']) ?></span>
              </a>

            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header" style="height: 185px;">
                  <img src="<?php echo $base; ?>imgstatis/logo2.png" class="img-circle" alt="User Image">
                <p>
                  <?php echo ucfirst($na['username']); ?>
                  <small>(<?= ucfirst($na['nama']); ?>)</small>
                </p>
                <p style="font-size: 11px;"><?php echo $aplikasi['namasek']; ?></p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <form action="" method="post">
                    <button type="submit" name="change_pass" class="btn btn-default btn-flat"> <i class="glyphicon glyphicon-pencil"></i> Ganti Password </button>
                  </form>
                </div>
                <div class="pull-right">
                  <button onclick="logout()" class="btn btn-default btn-flat"> Sign Out </button>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar cobasidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
            <img src="<?php echo $base; ?>imgstatis/logo2.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?= ucfirst($na['username']); ?> (<?= $na['nama']; ?>) </p>
          <i class="glyphicon glyphicon-time"></i> <?php echo tgl(date('d-m-Y')); ?>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">

      </form>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">MAIN NAVIGATION</li>

        <!-- DASHBOARD -->
        <li>
          <a href="<?= $basead; ?>" id="dashboard"><i style="color: white;" class="glyphicon glyphicon-th-large text-primary"></i> <span id=""> DASHBOARD </span> </a>
        </li>

        <li>
          <a href="<?= $basead; ?>teacher" id="addguru"><i style="color: white;" class="glyphicon glyphicon-user"></i> <span id="addtc"> TEACHER </span> </a>
        </li>

        <li>
          <a href="<?= $basead; ?>students" id="students"><i style="color: white;" class="fa-classic fa-solid fa-user-graduate fa-fw"></i> <span id="addst" style="margin-left: 2px;"> STUDENTS </span> </a>
        </li>

        <li>
          <a href="#" id="list_student">
            <i class="fa-solid fa-users"></i> <span style="margin-left: 2px;"> GROUP CLASS </span>
          </a>
          <ul class="treeview-menu">
            
            <li>
              <a href="<?= $basead; ?>listgroupclass" id="listgroupclass"><i class="glyphicon glyphicon-list-alt text-primary"></i> <span id="g_kls"> List Group Class </span> </a>
            </li>

            <li>
              <a href="<?= $basead; ?>creategroupstudents" id="creategroupstudents"><i class="glyphicon glyphicon-plus text-primary"></i> <span id="b_gk"> Buat Group Kelas </span> </a>
            </li>

          </ul>
        </li>

        <!-- Users -->
        <li>
          <a href="#" id="list_announcement">
            <i class="glyphicon glyphicon-envelope"></i> <span> ANNOUNCEMENT </span>
          </a>
          <ul class="treeview-menu">
            
            <li>
              <a href="<?= $basead; ?>createinfo" id="createinfo"><i class="glyphicon glyphicon-plus text-primary"></i> <span id="isiList4"> Buat Info </span> </a>
            </li>

            <!-- <li>
              <a href="<?= $basead; ?>createinfo" id="listinfo"><i class="glyphicon glyphicon-list-alt text-primary"></i> <span id="isiList4"> Info Yang Dibuat </span> </a>
            </li> -->

          </ul>
        </li>

        <!-- MAINTENANCE -->
        <li>
          <a href="#" id="list_maintenance">
            <i class="glyphicon glyphicon-cog"></i> <span id="titleList2"> MAINTENANCE </span>
          </a>
          <ul class="treeview-menu">

            <li>
              <a href="<?= $basead; ?>changepassword" id="changepassword"><i class="glyphicon glyphicon-wrench text-primary" id="create"></i> <span id="isiList4"> Change Password </span> </a>
            </li>
            
            <li>
              <a href="<?= $basead; ?>tahunajaran" id="tahunajaran"><i class="glyphicon glyphicon-text-width text-primary" id="create"></i> <span id="isiList3"> Tahun Ajaran </span> </a>
            </li>

            <li>
              <a href="<?= $basead; ?>resetdata" id="resetdata"><i class="glyphicon glyphicon-retweet text-primary" id="create"></i> <span id="isiList3"> Reset </span> </a>
            </li>

          </ul>
        </li>

        <!-- Users -->
        <!-- <li>
          <a href="#" id="list_users">
            <i class="glyphicon glyphicon-user"></i> <span> USERS </span>
          </a>
          <ul class="treeview-menu">
            
            <li>
              <a href="<?= $basead; ?>tambahuser" id="tambahuser"><i class="glyphicon glyphicon-plus text-primary"></i> <span id="isiList4"> Tambah User </span> </a>
            </li>

            <li>
              <a href="<?= $basead; ?>edituser" id="edituser"><i class="glyphicon glyphicon-pencil text-primary"></i> <span id="isiList5"> Edit User </span> </a>
            </li>

          </ul>
        </li> -->

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
   <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <?php if (!empty($_GET['on'])): ?>

      <?php if ($_GET['on'] != 'createdaily'): ?>
        
        <!-- Content Header (Page header) -->
        <section class="content-header" id="content_head">
          <div class="box box-info" style="margin-bottom: -5px;">
            <div class="box-header with-border">
              <h3 class="box-title" id="boxTitle"> <i class="fa" id="addIcon"></i> <span id="isiMenu"> </span> </h3>
            </div>
          </div>
        </section>

      <?php endif ?>

    <?php endif ?>

    <!-- Main content -->
    <section class="content" id="isi_konten">

<?php
  if(empty($_GET['on'])){require 'view/dashboard/index.php';}
  else{
    $act=($_GET['on']);
    if($act=='kelas'){
      require 'view/a-kelas.php';
    }

    #region eksport data wali murid
    else if ($act == 'ekspordatawalimurid') {
      require 'view/dashboard/ekspordatawalimurid.php';
    }

    #region dashboard
    // elseif($act == 'dashboard') {
    //   $createDaily = 0;
    //   require 'view/dashboard/index.php';
    // }

    elseif($act == 'addteacher') {
      require 'view/a-guru.php';
    }

    elseif($act == 'teacher') {
      require 'view/list_teacher.php';
    }

    #region STUDENTS

    elseif($act == 'students') {
      require 'view/students/all_student.php';
    }

    elseif($act == 'importsiswa') {
      require 'view/students/importsiswa.php';
    }

    elseif($act == 'addstudent') {
      require 'view/students/a-student.php';
    }

    elseif($act == 'listgroupclass') {
      require 'view/students/listgroupclass.php';
    }

    elseif($act == 'groupclass') {
      require 'view/students/anggota.php';
    }

    elseif($act == 'creategroupstudents') {
      require 'view/students/index.php';
    }

    elseif($act == 'addtogroupclass') {
      require 'view/students/addtogroup.php';
    }

    #region announcement
    elseif($act == 'createinfo') {
      require 'view/announcement/index.php';
    }

    #region form maintenance
    elseif($act == 'changepassword') {
      require 'view/maintenance/password/changepassword.php';
    }

    elseif ($act == 'tahunajaran') {
      require 'view/maintenance/tahun_ajaran/index.php';
    }

    elseif ($act == 'resetdata') {
      require 'view/maintenance/reset/index.php';
    } 

    else{
      require 'view/404.php';
    }
  }
?>
    </section>
    <!-- /.content -->
  </div>
</div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo $aplikasi['namasek']; ?></a></strong> by IRP
 
</div>
<!-- ./wrapper -->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery 2.2.3 -->
<script src="<?php echo $base; ?>theme/plugins/jQuery/jquery-2.2.3.min.js"></script>

<script src="<?php echo $base; ?>theme/js/summernote.min.js" crossorigin="anonymous"></script>

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
<!-- <script src="<?php echo $base; ?>theme/plugins/select2/select2.full.min.js"></script> -->
<script>

  let createDailys  = `<?= $createDaily; ?>`
  let basead        = `<?= $basead; ?>logout.php`

  const loadData = () => {

    setTimeout(function(){
      let xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          
          let jumlahInfo     = JSON.parse(this.responseText).notif_info;

          if (jumlahInfo == 0) {

            $("#notifInfoList").hide();
            $("#pesanInfo").show();
            $("#pesanInfo").html(`Tidak Ada Info Pemberitahuan !`);
            $("#pesanInfo").css("background-color", "whitesmoke");
            // $("#viewAllInfo").text(JSON.parse(this.responseText).notif_info);
            $("#isi_pesan_sudah_approve").hide();

          } else if(jumlahInfo > 0) {

            $("#notifInfoList").show();
            $("#pesanInfo").show();
            $("#pesanInfo").html(`Ada <span style="font-weight: bold;"> ${JSON.parse(this.responseText).notif_info} </span> Pemberitahuan Yang Di buat Hari Ini`);
            $("#pesanInfo").css("background-color", "lightyellow");
            $("#notifInfoList").text(JSON.parse(this.responseText).notif_info);
            $("#isi_pesan_info").html(JSON.parse(this.responseText).isi_notif_info);
            // $("#viewAllInfo").text(JSON.parse(this.responseText).notif_info);

          }

          $(".infolist").click(function(){
            
            $("#modal-default").modal('show');

            let dataNamaSiswa  = $(this).data('nama_siswa');
            let dataTglUpload  = $(this).data('tgl_upload');
            let dataJnsByr     = $(this).data('jenis_byr');
            let dataKetByr     = $(this).data('ket_byr');
            let dataNominal    = $(this).data('nominal_format');

            $("#nm_siswa").val(dataNamaSiswa);
            $("#tanggal_dibuat").val(dataTglUpload);
            if(dataJnsByr == 'REGISTRASI') {
              $("#jenis_pembayaran").val('REGISTRASI / DAFTAR ULANG');
            } else {
              $("#jenis_pembayaran").val(dataJnsByr);
            }
            $("#nominal_byr").val(dataNominal);
            $("#keterangan").html(dataKetByr);
          });

        }
      };

      xhttp.open("GET", `<?= $basead; ?>data`, true);
      xhttp.send();
    }, 1000)

  }

  $(document).ready(function(){

    loadData()

    let timeIsOut = `<?= $timeIsOut; ?>`

    if (timeIsOut == 1) {

      const myTimeout = setTimeout(showPopUp, 1000);

    }

    function clickSubMenu() {
      $("#isiList2").click();
      $("#query_data_siswa").css({
          "background-color" : "#ccc",
          "color" : "black"
      });
    }

    function showPopUp() {
      Swal.fire({
        title: 'TIME IS OUT',
        icon: "warning"
      });

      setTimeout(clearSession, 1200);
      
    }

    function clearSession() {
      $.ajax({
        url : `<?= $basead; ?>admin.php`,
        type : 'POST',
        data : {
          clearSess : "out"
        },
        success:function(data) {
          let checkDataOut = JSON.parse(data).clear
          if(checkDataOut == true) {
            document.location.href = `<?= $base; ?>login`
          }
        }

      })
    }

    let role              = `<?= $nama_role; ?>`
    // alert(role)
    let showNotif         = document.getElementById('notifMasuk')

  })

  function logout() {

    $.ajax({
      url : `<?= $basead; ?>a-control/<?= md5('logout_act_paud'); ?>/access`,
      type : 'GET',
      success:function(data) {
        let response_data = JSON.parse(data)[0];
        if (response_data == "is_logout") {
          showPopUpLogOut();
        }
      }
    });

  }

  function showPopUpLogOut() {
    Swal.fire({
      title: 'LOG OUT',
      icon: "warning"
    });

    setTimeout(redirectPage, 1200);
    
  }

  function redirectPage() {
    document.location.href = `<?= $basead; ?>`
  }

  $(function () {

    $("#example1x").DataTable({
      "paging": true
    });

    $('#example2').DataTable({
      "paging": false,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": false,
      "autoWidth": false
    });

    $('#example3').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": false,
      "autoWidth": false
    });

    $('#list_siswa').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false
    });

    $('#list_guru').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": false,
      "autoWidth": false
    });

    $("#hightlight_list_siswa").DataTable();

    $('#tabelCariSiswaCheckPembayarans').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": false,
      "autoWidth": false
    });

  });

</script>
<script src="<?php echo $base; ?>theme/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
<script src="<?php echo $base; ?>theme/plugins/pace/pace.min.js"></script>
<script type="text/javascript">
  // To make Pace works on Ajax calls
  $(document).ajaxStart(function() { Pace.restart(); });
    $('.ajax').click(function(){
        $.ajax({url: '#', success: function(result){
            $('.ajax-content').html('<hr>Ajax Request Completed !');
        }});
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Select2 -->
<script src="<?php echo $base; ?>theme/plugins/select2/select2.full.min.js"></script>
<script>
  $(function () {
    //Initialize Select2 Elements
    $("#select2").select2();
  });
  $(function () {
    //Initialize Select2 Elements
    $("#select3").select2();
  });
  $(function () {
    //Initialize Select2 Elements
    $("#select4").select2();
  });
</script>
<script>
//angka 500 dibawah ini artinya pesan akan muncul dalam 0,5 detik setelah document ready
$(document).ready(function(){setTimeout(function(){$(".alert").fadeIn('fast');}, 100);});
//angka 3000 dibawah ini artinya pesan akan hilang dalam 3 detik setelah muncul
setTimeout(function(){$(".alert").fadeOut('fast');}, 3000);
</script>

</body>
</html>
