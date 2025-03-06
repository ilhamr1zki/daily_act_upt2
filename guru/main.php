<?php 
  
  date_default_timezone_set('Asia/Jakarta');
  $date=date('Y-m-d'); 
  
  require '../php/config.php'; 
  require '../php/function.php'; 
  
  // Cek status login user jika tidak ada session
  if (!$user->isLoggedInGuru()) { 

    header("location:../"); //Redirect ke halaman login  

  }

  $key_room  = "";
  
  $nama_role = $_SESSION['key_guru_paud'];

  $thisPage =  (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  $endUrl   = substr($thisPage, strpos($thisPage, "/lookactivity") + 13);
  // echo $endUrl;exit;
  // echo $thisPage;exit;

  $is_SD      = "/SD/i";
  $is_PAUD    = "/PAUD/i";
  $is_All     = "/Kepala_Unit/i";

  $foundDataSD    = preg_match($is_SD, $_SESSION['c_guru_paud']);
  $foundDataPAUD  = preg_match($is_PAUD, $_SESSION['c_guru_paud']);
  $foundDataAll   = preg_match($is_All, $_SESSION['c_guru_paud']);
  $isGuru         = "";
  $isWalas        = 0;

  $getNipWalas = mysqli_num_rows(mysqli_query($con, "SELECT nip FROM group_kelas WHERE nip = '$_SESSION[nip_guru_paud]' "));

  if ($getNipWalas == 1) {

    $getNameGroupClass = mysqli_fetch_array(mysqli_query($con, "SELECT nama_group_kelas FROM group_kelas WHERE nip = '$_SESSION[nip_guru_paud]' "));

  }

  // echo $getNipWalas;exit;

  if ($foundDataSD == 1) {

    $isGuru = "GURU SD";

  } else if ($foundDataPAUD == 1) {

    $isGuru = "GURU TK/PAUD";

  } else if ($foundDataAll == 1) {

    $isGuru = "KEPALA UNIT POROS MASJID";

  }

  $currTahun    = "";
  $currSemester = "";

  $createDaily  = 0; 

  $check = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tahun_ajaran WHERE status = 'aktif' AND c_role = '$_SESSION[key_guru_paud]' "));

  if ($check != 0) {

    $currTahun = mysqli_query($con, "SELECT tahun FROM tahun_ajaran WHERE status = 'aktif' AND c_role = '$_SESSION[key_guru_paud]' ");
    $currTahun = mysqli_fetch_assoc($currTahun)['tahun'];
    // echo $currTahun;exit;
    $currSemester = mysqli_query($con, "SELECT semester FROM tahun_ajaran WHERE status = 'aktif' AND c_role = '$_SESSION[key_guru_paud]' ");
    $currSemester = mysqli_fetch_assoc($currSemester)['semester'];

  } else {

    $currTahun    = "";
    $currSemester = "";

  }

  $na = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM guru where c_guru = '$_SESSION[key_guru_paud]' ")); 
  //$setting=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM setting limit 1 "));*/ 
  if (isset($_GET['nextPage'])) {
    echo $_GET['nextPage'];exit;
  }

  if (isset($_POST['sg_out'])) {
    session_destroy();
    session_unset($_SESSION['key_guru_paud']);
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
  <!-- Ionicons -->
  <!-- <link rel="stylesheet" href="<?= $base; ?>theme/bower_components/Ionicons/css/ionicons.min.css"> -->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= $base; ?>theme/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?= $base; ?>theme/dist/css/skins/_all-skins.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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

  <!-- <link rel="stylesheet" href="<?php echo $base; ?>theme/dist/css/samples.css"> -->
  
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

    body {
      font-family:arial;
    }

    #inpage_tanggal_upload_notappr {
      width: 213px;
    }

    #swal2-title {
      font-size: 17px;
    }

    hr.new1 {
      border-top: 1px solid black;
    }

    #tr_dashboard {
      cursor: pointer;
    }

    #view_all:hover {
      background-color: #ddd;
    }

    #tr_dashboard, 
    #tr_modalsiswa {
      cursor: pointer;
    }

    /*#date_approved {
      width: 212px;
    }*/

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
      font-size: 12px !important;
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

    #tanggal_upload {
      width: 213px;
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

    #kepsekchat,
    #namaguruchat {
      font-size: 12px;
    }

    #cdastd {
      font-size: 17px;
      margin-left: -4px;
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

      #cdastd {
        font-size: 12px;
        margin-left: -4px;
      }

      #cdagrp {
        font-size: 12px;
      }

      #boxTitle {
        font-size: 12px;
      }

      #addIcon {
        top: 2px;
      }

      .center h4 {
        font-size: 12px;
      }

      #kepsekchat,
      #namaguruchat,
      #tglsendguru {
        font-size: 10px;
      }

      #namaguru {
        font-size: 12px;
        margin-top: 4px;
      }

      #tglpublish {
        font-size: 9px;
      }

      #tdkadakomen {
        font-size: 15px;
      }

      #inpage_date_approved,
      #inpage_date_not_approved,
      #inpage_tanggal_upload_notappr {
        width: 100%;
      }

      .swal2-show {
        width: 70% !important;
        font-size: 12px !important;
        font-weight: bold;
      }

      #tanggal_upload,
      #date_not_approved,
      #hg_date_appr,
      #hg_date_not_approved {
        width: 100%;
      }

      #dotTo {
        margin-left: 23px;
      }

      #date_approved {
        width: 100%;
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
            <h4 class="modal-title"> <strong> DAILY ACTIVITY </strong> </h4>
          </center>
      </div>
      <div class="modal-body" style="margin-bottom: 10px;">

          <div class="box-body" style="padding-left: 60px; padding-right: 60px;">

            <form role="form" id="forms">

              <div class="row">

                <div class="col-sm-6">
                  <div class="form-group">
                    <label style="font-size: 13px;"> STATUS : <strong style="color: yellowgreen;"> Waiting Approval </strong> <i class="fa fa-fw fa-hourglass-half" style="color: yellowgreen;"></i> </label>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label> DATE POSTED </label>
                    <input type="text" readonly id="tanggal_upload" name="tanggal_upload" class="form-control">
                  </div>
                </div>

              </div>

              <div class="form-group">
                <label for="siswa_daily" id="lbl_std_or_gp">STUDENT</label>
                <input type="text" id="siswa_daily" name="siswa_daily" readonly class="form-control">
              </div>

              <div class="form-group gambar_banner">
                <label for="banner"> UPLOADED PHOTO </label>
                <!-- <input type="text" id="banner" name=""> -->
                <img class="img-responsive pad" id="foto_upload" alt="Photo">
              </div>

              <div class="form-group">
                <label for="title_daily">TITLE DAILY ACTIVITY</label>
                <input type="text" id="title_daily" name="title_daily" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="main_daily">DESCRIPTION DAILY ACTIVITY</label>
                <div class="isiDaily" id="main_daily" style="height: 150px;border: 1px solid #eee;padding: 10px; background-color: #eee;" class="form-control">
                  
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

<!-- Modal Approved -->
<div class="modal fade" id="modal-default-appr">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom-color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <center>
            <h4 class="modal-title"> <strong> DAILY ACTIVITY </strong> </h4>
          </center>
      </div>
      <div class="modal-body" style="margin-bottom: 10px;">

          <div class="box-body" style="padding-left: 60px; padding-right: 60px;">

            <form role="form" id="forms">

              <div class="row">
                
                <div class="col-sm-6">
                  <div class="form-group">
                    <label> STATUS : <strong style="color: green;"> Approved </strong> <i class="glyphicon glyphicon-ok" style="color: green;"></i> </label>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label> DATE POSTED </label>
                    <input type="text" readonly id="tanggal_upload_appr" name="tanggal_upload_appr" class="form-control">
                  </div>
                </div>

              </div>

              <div class="form-group">
                <label for="date_approved"> DATE APPROVE </label>
                <input type="text" id="date_approved" name="date_approved" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="siswa_daily_appr" id="lbl_std_or_gp_df_appr">STUDENT</label>
                <input type="text" id="siswa_daily_appr" name="siswa_daily_appr" readonly class="form-control">
              </div>

              <div class="form-group gambar_banner_appr">
                <label for="banner"> UPLOADED PHOTO </label>
                <!-- <input type="text" id="banner" name=""> -->
                <img class="img-responsive pad" id="foto_upload_appr" alt="Photo">
              </div>

              <div class="form-group">
                <label for="title_daily_appr">TITLE DAILY ACTIVITY</label>
                <input type="text" id="title_daily_appr" name="title_daily_appr" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="main_daily_appr">DESCRIPTION DAILY ACTIVITY</label>
                <div class="isiDailyAppr" id="main_daily_appr" style="height: 150px;border: 1px solid #eee;padding: 10px; background-color: #eee;" class="form-control">
                  
                </div>
                <!-- <textarea style="height: 150px;" class="form-control" id="main_daily" rows="3" placeholder="Announcement ..."></textarea> -->
              </div>

            </form>

          </div>

      </div>
      <div class="modal-footer">
        
        <button type="button" id="close_approve" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <form id="formDfAppr" method="POST">
          <input type="hidden" id="df_frompage_lookdaily" name="frompage_lookdaily" value="homepage">
          <input type="hidden" id="df_roomkey_lookdaily" name="roomkey_lookdaily">
          <input type="hidden" id="df_nis_lookdaily" name="nis_or_idgroup_lookdaily"> 
          <input type="hidden" id="df_siswa_lookdaily" name="nama_siswa_or_groupkelas_lookdaily"> 
          <input type="hidden" id="df_guru_lookdaily" name="guru_lookdaily">
          <input type="hidden" id="df_nipguru_lookdaily" name="nipguru_lookdaily">
          <input type="hidden" id="df_img_lookdaily" name="foto_upload_lookdaily">
          <input type="hidden" id="df_tglappr_lookdaily" name="tgl_posting_lookdaily">
          <input type="hidden" id="df_tglori_posting_lookdaily" name="tglori_posting_lookdaily">
          <input type="hidden" id="df_jdl_lookdaily" name="jdl_posting_lookdaily">
          <input type="hidden" id="df_isi_lookdaily" name="isi_posting_lookdaily">
          <button type="submit" name="redirectLookDaily" id="lookdaily_appr" class="btn btn-primary"> <i class="glyphicon glyphicon-eye-open"></i> Lookdaily </button>
        
        </form>
          
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-default-notappr">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom-color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <center>
            <h4 class="modal-title"> <strong> DAILY ACTIVITY </strong> </h4>
          </center>
      </div>
      <div class="modal-body" style="margin-bottom: 10px;">

          <div class="box-body" style="padding-left: 60px; padding-right: 60px;">

            <form role="form" id="forms">

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label> STATUS : <strong style="color: red;"> Not Approved </strong> <i class="glyphicon glyphicon-remove" style="color: red;"></i> </label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label> DATE POSTED </label>
                    <input type="text" readonly id="tanggal_upload_notappr" name="tanggal_upload_notappr" class="form-control">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="date_not_approved"> DATE NOT APPROVE </label>
                <input type="text" id="date_not_approved" name="date_not_approved" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="siswa_daily_notappr" id="lbl_std_or_gp_df_noappr">STUDENT</label>
                <input type="text" id="siswa_daily_notappr" name="siswa_daily_notappr" readonly class="form-control">
              </div>

              <div class="form-group gambar_banner_appr">
                <label for="banner"> UPLOADED PHOTO </label>
                <!-- <input type="text" id="banner" name=""> -->
                <img class="img-responsive pad" id="foto_upload_notappr" alt="Photo">
              </div>

              <div class="form-group">
                <label for="title_daily_notappr">TITLE DAILY ACTIVITY</label>
                <input type="text" id="title_daily_notappr" name="title_daily_notappr" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="main_daily_notappr">DESCRIPTION DAILY ACTIVITY</label>
                <div class="isiDailyAppr" id="main_daily_notappr" style="height: 150px;border: 1px solid #eee;padding: 10px; background-color: #eee;" class="form-control">
                  
                </div>
                <!-- <textarea style="height: 150px;" class="form-control" id="main_daily" rows="3" placeholder="Announcement ..."></textarea> -->
              </div>

              <div class="form-group" id="div_default_reason">
                <label for="reason_notappr">REASON NOT APPROVED</label>
                <div id="reason_notappr" style="height: 150px;border: 1px solid #eee;padding: 10px; background-color: #eee;" class="form-control">
                  
                </div>
                <!-- <textarea style="height: 150px;" class="form-control" id="main_daily" rows="3" placeholder="Announcement ..."></textarea> -->
              </div>

            </form>

          </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="close_notapprove" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-hightlight-appr">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom-color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <center>
            <h4 class="modal-title"> <strong> DAILY ACTIVITY </strong> </h4>
          </center>
      </div>
      <div class="modal-body" style="margin-bottom: 10px;">

          <div class="box-body" style="padding-left: 60px; padding-right: 60px;">

            <form role="form" id="forms">

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label> STATUS : <strong style="color: green;"> Approved </strong> <i class="glyphicon glyphicon-ok" style="color: green;"></i> </label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label> DATE POSTED </label>
                    <input type="text" readonly id="hg_tanggal_upload_appr" name="hg_tanggal_upload_appr" class="form-control">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="date_approved"> DATE APPROVE </label>
                <input type="text" id="hg_date_appr" name="date_approved" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="hg_siswa_daily_appr" id="lbl_stdorgp_hg_appr">STUDENT</label>
                <input type="text" id="hg_siswa_daily_appr" name="hg_siswa_daily_appr" readonly class="form-control">
              </div>

              <div class="form-group hg_gambar_banner_appr">
                <label for="banner"> UPLOADED PHOTO </label>
                <!-- <input type="text" id="banner" name=""> -->
                <img class="img-responsive pad" id="hg_foto_upload_appr" alt="Photo">
              </div>

              <div class="form-group">
                <label for="hg_title_daily_appr">TITLE DAILY ACTIVITY</label>
                <input type="text" id="hg_title_daily_appr" name="hg_title_daily_appr" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="hg_main_daily_appr">DESCRIPTION DAILY ACTIVITY</label>
                <div class="isiDailyAppr" id="hg_main_daily_appr" style="height: 150px;border: 1px solid #eee;padding: 10px; background-color: #eee;" class="form-control">
                  
                </div>
                <!-- <textarea style="height: 150px;" class="form-control" id="main_daily" rows="3" placeholder="Announcement ..."></textarea> -->
              </div>

            </form>

          </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="hg_close_approve" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <form id="formHgAppr" method="post">
          <input type="hidden" id="hg_frompage_lookdaily" name="frompage_lookdaily" value="homepage">
          <input type="hidden" id="hg_roomkey_lookdaily" name="roomkey_lookdaily">
          <input type="hidden" id="hg_nama_guru_lookdaily" name="guru_lookdaily">
          <input type="hidden" id="hg_nipguru_lookdaily" name="nipguru_lookdaily" value="<?= $_SESSION['nip_guru_paud']; ?>">
          <input type="hidden" id="hg_nama_siswa_lookdaily" name="nama_siswa_or_groupkelas_lookdaily">
          <input type="hidden" id="hg_nis_siswa_lookdaily" name="nis_or_idgroup_lookdaily">
          <input type="hidden" id="hg_foto_upload_lookdaily" name="foto_upload_lookdaily">
          <input type="hidden" id="hg_tgl_posting_lookdaily" name="tgl_posting_lookdaily">
          <input type="hidden" id="hg_tglori_posting_lookdaily" name="tglori_posting_lookdaily">
          <input type="hidden" id="hg_jdl_posting_lookdaily" name="jdl_posting_lookdaily">
          <input type="hidden" id="hg_isi_posting_lookdaily" name="isi_posting_lookdaily">
          <button type="submit" name="redirectLookDaily" id="hg_lookdaily_appr" class="btn btn-primary"> <i class="glyphicon glyphicon-eye-open"></i> Lookdaily </button>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-hightlight-noappr">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom-color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <center>
            <h4 class="modal-title"> <strong> DAILY ACTIVITY </strong> </h4>
          </center>
      </div>
      <div class="modal-body" style="margin-bottom: 10px;">

          <div class="box-body" style="padding-left: 60px; padding-right: 60px;">

            <form role="form" id="forms">

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label> STATUS : <strong style="color: red;"> Not Approved </strong> <i class="glyphicon glyphicon-remove" style="color: red;"></i> </label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label> DATE POSTED </label>
                    <input type="text" readonly id="hg_tanggal_upload_notappr" name="hg_tanggal_upload_notappr" class="form-control">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="hg_date_not_approved"> DATE NOT APPROVE </label>
                <input type="text" id="hg_date_not_approved" name="hg_date_not_approved" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="hg_siswa_daily_notappr" id="lbl_stdorgp_hg_noappr">STUDENT</label>
                <input type="text" id="hg_siswa_daily_notappr" name="hg_siswa_daily_notappr" readonly class="form-control">
              </div>

              <div class="form-group gambar_banner_appr">
                <label for="banner"> UPLOADED PHOTO </label>
                <!-- <input type="text" id="banner" name=""> -->
                <img class="img-responsive pad" id="hg_foto_upload_notappr" alt="Photo">
              </div>

              <div class="form-group">
                <label for="hg_title_daily_notappr">TITLE DAILY ACTIVITY</label>
                <input type="text" id="hg_title_daily_notappr" name="hg_title_daily_notappr" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="hg_main_daily_notappr">DESCRIPTION DAILY ACTIVITY</label>
                <div class="isiDailyAppr" id="hg_main_daily_notappr" style="height: 150px;border: 1px solid #eee;padding: 10px; background-color: #eee;" class="form-control">
                  
                </div>
                <!-- <textarea style="height: 150px;" class="form-control" id="main_daily" rows="3" placeholder="Announcement ..."></textarea> -->
              </div>

              <div class="form-group" id="hg_div_default_reason">
                <label for="hg_reason_notappr">REASON</label>
                <div id="hg_reason_notappr" style="height: 150px;border: 1px solid #eee;padding: 10px; background-color: #eee;" class="form-control">
                  
                </div>
              </div>

            </form>

          </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="hg_close_notapprove" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>

<div class="modal fade" id="modal-hightlight-wt-appr">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom-color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <center>
            <h4 class="modal-title"> <strong> DAILY ACTIVITY </strong> </h4>
          </center>
      </div>
      <div class="modal-body" style="margin-bottom: 10px;">

          <div class="box-body" style="padding-left: 60px; padding-right: 60px;">

            <form role="form" id="forms">
              <input type="hidden" id="highlight_id_daily_waiiting">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label id="label_status_wt"> STATUS : <strong style="color: yellowgreen;" id="hightlight_status_wait_appr"> Waiting Approval <i id="icon_change" class="fa fa-fw fa-hourglass-half" style="color: yellowgreen;"></i> </strong> </label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label> DATE POSTED </label>
                    <input type="text" readonly id="hightlight_tanggal_upload" name="hightlight_tanggal_upload" class="form-control">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="hightlight_siswa_daily" id="lbl_stdorgp_hg_wt_appr">STUDENT</label>
                <input type="text" id="hightlight_siswa_daily" name="hightlight_siswa_daily" readonly class="form-control">
              </div>

              <div class="form-group gambar_banner">
                <label for="banner"> UPLOADED PHOTO </label>
                <!-- <input type="text" id="banner" name=""> -->
                <img class="img-responsive pad" id="hightlight_foto_upload_wt_appr" alt="Photo">
              </div>

              <div class="form-group">
                <label for="hightlight_title_daily">TITLE DAILY ACTIVITY</label>
                <input type="text" id="hightlight_title_daily" name="hightlight_title_daily" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="hightlight_main_daily">DESCRIPTION DAILY ACTIVITY</label>
                <div class="isiDaily" id="hightlight_main_daily" style="height: 150px;border: 1px solid #eee;padding: 10px; background-color: #eee;" class="form-control">
                  
                </div>
                <!-- <textarea style="height: 150px;" class="form-control" id="main_daily" rows="3" placeholder="Announcement ..."></textarea> -->
              </div>

              <div class="form-group hightlight_reason">
                <label for="hightlight_reason">REASON (Optional)</label>
                <input type="text" id="hightlight_reason" class="form-control" placeholder="Reason ... (Optional)">
              </div>

            </form>

          </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="hightlight_close_approve_wait" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="inpage-wt-appr">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom-color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <center>
            <h4 class="modal-title"> <strong> DAILY ACTIVITY </strong> </h4>
          </center>
      </div>
      <div class="modal-body" style="margin-bottom: 10px;">

          <div class="box-body" style="padding-left: 60px; padding-right: 60px;">

            <form role="form" id="forms">
              <input type="hidden" id="inpage_id_daily_waiiting_wt_appr">
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label id="label_status_wt"> STATUS : <strong style="color: yellowgreen;" id="hightlight_status_wait_appr"> Waiting Approval <i id="icon_change" class="fa fa-fw fa-hourglass-half" style="color: yellowgreen;"></i> </strong> </label>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label> DATE POSTED </label>
                    <input type="text" readonly id="inpage_tanggal_upload_wt_appr" name="inpage_tanggal_upload_wt_appr" class="form-control">
                  </div>
                </div>

              </div>

              <div class="form-group">
                <label for="inpage_siswa_daily_wt_appr" id="lbl_inpage_wtappr_std_or_gp">STUDENT</label>
                <input type="text" id="inpage_siswa_daily_wt_appr" name="inpage_siswa_daily_wt_appr" readonly class="form-control">
              </div>

              <div class="form-group gambar_banner">
                <label for="banner"> UPLOADED PHOTO </label>
                <!-- <input type="text" id="banner" name=""> -->
                <img class="img-responsive pad" id="inpage_foto_upload_wt_appr" alt="Photo">
              </div>

              <div class="form-group">
                <label for="inpage_title_daily_wt_appr">TITLE DAILY ACTIVITY</label>
                <input type="text" id="inpage_title_daily_wt_appr" name="inpage_title_daily_wt_appr" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="inpage_main_daily_wt_appr">DESCRIPTION DAILY ACTIVITY</label>
                <div class="isiDaily" id="inpage_main_daily_wt_appr" style="height: 150px;border: 1px solid #eee;padding: 10px; background-color: #eee;" class="form-control">
                  
                </div>
                <!-- <textarea style="height: 150px;" class="form-control" id="main_daily" rows="3" placeholder="Announcement ..."></textarea> -->
              </div>

            </form>

          </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="inpage_close_wt_appr" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog --> 
</div>

<div class="modal fade" id="inpage-approved">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom-color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <center>
            <h4 class="modal-title"> <strong> DAILY ACTIVITY </strong> </h4>
          </center>
      </div>
      <div class="modal-body" style="margin-bottom: 10px;">

          <div class="box-body" style="padding-left: 60px; padding-right: 60px;">

            <form role="form" id="forms">

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label> STATUS : <strong style="color: green;"> Approved </strong> <i class="glyphicon glyphicon-ok" style="color: green;"></i> </label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label> DATE POSTED </label>
                    <input type="text" readonly id="inpage_tanggal_upload_appr" name="inpage_tanggal_upload_appr" class="form-control">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="inpage_date_approved"> DATE APPROVE </label>
                <input type="text" id="inpage_date_approved" name="inpage_date_approved" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="inpage_siswa_daily_appr" id="lbl_std_or_gp_inpage_appr">STUDENT</label>
                <input type="text" id="inpage_siswa_daily_appr" name="inpage_siswa_daily_appr" readonly class="form-control">
              </div>

              <div class="form-group gambar_banner_appr">
                <label for="banner"> UPLOADED PHOTO </label>
                <!-- <input type="text" id="banner" name=""> -->
                <img class="img-responsive pad" id="inpage_foto_upload_appr" alt="Photo">
              </div>

              <div class="form-group">
                <label for="inpage_title_daily_appr">TITLE DAILY ACTIVITY</label>
                <input type="text" id="inpage_title_daily_appr" name="inpage_title_daily_appr" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="inpage_main_daily_appr">DESCRIPTION DAILY ACTIVITY</label>
                <div class="isiDailyAppr" id="inpage_main_daily_appr" style="height: 150px;border: 1px solid #eee;padding: 10px; background-color: #eee;" class="form-control">
                  
                </div>
                <!-- <textarea style="height: 150px;" class="form-control" id="main_daily" rows="3" placeholder="Announcement ..."></textarea> -->
              </div>

            </form>

          </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="inpage_close_approve" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <form id="inpg_appr" method="post">
          <input type="hidden" id="inpage_frompage_lookdaily" name="frompage_lookdaily" value="status_approved">
          <input type="hidden" id="inpage_roomkey_lookdaily" name="roomkey_lookdaily">
          <input type="hidden" id="inpage_nama_guru_lookdaily" name="guru_lookdaily">
          <input type="hidden" id="inpage_nipguru_lookdaily" name="nipguru_lookdaily" value="<?= $_SESSION['nip_guru_paud']; ?>">
          <input type="hidden" id="inpage_nis_siswa_lookdaily" name="nis_or_idgroup_lookdaily">
          <input type="hidden" id="inpage_nama_siswa_lookdaily" name="nama_siswa_or_groupkelas_lookdaily">
          <input type="hidden" id="inpage_foto_upload_lookdaily" name="foto_upload_lookdaily">
          <input type="hidden" id="inpage_tgl_posting_lookdaily" name="tgl_posting_lookdaily">
          <input type="hidden" id="inpage_tglori_posting_lookdaily" name="tglori_posting_lookdaily">
          <input type="hidden" id="inpage_jdl_posting_lookdaily" name="jdl_posting_lookdaily">
          <input type="hidden" id="inpage_isi_posting_lookdaily" name="isi_posting_lookdaily">
          <button type="submit" name="redirectLookDaily" id="inpage_lookdaily_appr" class="btn btn-primary"> <i class="glyphicon glyphicon-eye-open"></i> Lookdaily </button>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
</div>

<div class="modal fade" id="inpage-not-approved">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom-color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <center>
            <h4 class="modal-title"> <strong> DAILY ACTIVITY </strong> </h4>
          </center>
      </div>
      <div class="modal-body" style="margin-bottom: 10px;">

          <div class="box-body" style="padding-left: 60px; padding-right: 60px;">

            <form role="form" id="forms">

              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group">
                    <label> STATUS : <strong style="color: red;"> Not Approved </strong> <i class="glyphicon glyphicon-remove" style="color: red;"></i> </label>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label> DATE POSTED </label>
                    <input type="text" readonly id="inpage_tanggal_upload_notappr" name="inpage_tanggal_upload_notappr" class="form-control">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="inpage_date_not_approved"> DATE NOT APPROVE </label>
                <input type="text" id="inpage_date_not_approved" name="inpage_date_not_approved" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="inpage_siswa_daily_notappr" id="lbl_std_or_gp_inpage_noappr">STUDENT</label>
                <input type="text" id="inpage_siswa_daily_notappr" name="inpage_siswa_daily_notappr" readonly class="form-control">
              </div>

              <div class="form-group gambar_banner_appr">
                <label for="banner"> UPLOADED PHOTO </label>
                <!-- <input type="text" id="banner" name=""> -->
                <img class="img-responsive pad" id="inpage_foto_upload_notappr" alt="Photo">
              </div>

              <div class="form-group">
                <label for="inpage_title_daily_notappr">TITLE DAILY ACTIVITY</label>
                <input type="text" id="inpage_title_daily_notappr" name="inpage_title_daily_notappr" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="inpage_main_daily_notappr">DESCRIPTION DAILY ACTIVITY</label>
                <div class="isiDailyAppr" id="inpage_main_daily_notappr" style="height: 150px;border: 1px solid #eee;padding: 10px; background-color: #eee;" class="form-control">
                  
                </div>
                <!-- <textarea style="height: 150px;" class="form-control" id="main_daily" rows="3" placeholder="Announcement ..."></textarea> -->
              </div>

              <div class="form-group" id="inpage_div_notappr_reason">
                <label for="inpage_reason_notappr">REASON NOT APPROVED</label>
                <div id="inpage_reason_notappr" style="height: 150px;border: 1px solid #eee;padding: 10px; background-color: #eee;" class="form-control">
                  
                </div>
                <!-- <textarea style="height: 150px;" class="form-control" id="main_daily" rows="3" placeholder="Announcement ..."></textarea> -->
              </div>

            </form>

          </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="inpage_close_notapprove" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
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

          <!-- <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" id="" data-toggle="dropdown">
              <i class="glyphicon glyphicon-thumbs-up"></i>
              <span class="label label-warning" id="">  </span>
            </a>
            <ul class="dropdown-menu">
              <li class="header msgAppr"> <center id="">  </center> </li>
              <li> -->
                <!-- inner menu: contains the actual data -->
                <!-- <ul class="menu" id="">
                  
                </ul>
              </li>
              <li class="footer"><a href="status_approved" id=""> View all (<strong id=""></strong>) Status Approved </a> </li>
            </ul>
          </li> -->

          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" id="untukNotifAppr" data-toggle="dropdown">
              <i class="glyphicon glyphicon-thumbs-up"></i>
              <span class="label label-warning" id="notifApproveList">  </span>
            </a>
            <ul class="dropdown-menu">
              <li class="header msgAppr"> <center id="pesanSdhApprove">  </center> </li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" id="isi_pesan_sudah_approve">
                  
                </ul>
              </li>
              <li class="footer"><a href="<?= $basegu; ?>status_approved" id="view_all"> View all (<strong id="viewAllStatAppr"></strong>) Status Approved </a> </li>
            </ul>
          </li>

          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" id="untukNotifNoApprKepsek" data-toggle="dropdown">
              <i class="glyphicon glyphicon-thumbs-down"></i>
              <span class="label label-warning" id="notifNotApproveList"> </span>
            </a>
            <ul class="dropdown-menu">
              <li class="header" id="pesanTdkDiApprove" style="text-align: center;"> </li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" id="isi_pesan_tdk_di_approve">
                  
                </ul>
              </li>
              <li class="footer"><a href="status_not_approved" id="v_all"> View all (<strong id="viewAllStatNotAppr"></strong>) Status Not Approved </a></li>
            </ul>
          </li>

          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" id="untukNotifWait" data-toggle="dropdown">
              <i class="fa fa-hourglass-2"></i>
              <span class="label label-warning" id="notifWaitApproveList">  </span>
            </a>
            <ul class="dropdown-menu">
              <li class="header msgWaitAppr"> <center id="waitingList">  </center> </li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" id="isi_pesan_belum_approve">
                  
                </ul>
              </li>
              <li class="footer"><a href="status_waiting_approval" id="view_all">View all (<strong id="viewAllStatWait"></strong>) Status Waiting </a></li>
            </ul>
          </li>

          <!-- <li class="active"> -->
            <!-- <a><i class="glyphicon glyphicon-stats"></i> <?php echo $ata['tahun']; ?> Semester <?php echo $ata['semester']; ?></a> -->
            <!-- <a><i class="glyphicon glyphicon-stats"></i> <?= $currTahun; ?> Semester <?= $currSemester; ?></a> -->
          <!-- </li> -->

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
          

          <li class="dropdown user user-menu">

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo $base; ?>imgstatis/logo2.png" class="user-image" alt="User Image">
                <span class="hidden-xs"> <?= strtoupper($_SESSION['username_guru_paud'])?> </span>
              </a>

            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header" style="height: 185px;">
                  <img src="<?php echo $base; ?>imgstatis/logo2.png" class="img-circle" alt="User Image">
                <p>
                  <?= strtoupper($_SESSION['nama_guru_paud']); ?>
                  <small>(<?= strtoupper($isGuru); ?>)</small>
                </p>
                <p style="font-size: 11px;"><?php echo $aplikasi['namasek']; ?></p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                    <button type="submit" name="change_pass" id="change_pass" class="btn btn-default btn-flat"> <i class="glyphicon glyphicon-pencil"></i> Ganti Password </button>
                </div>
                <div class="pull-right">
                  <button type="submit" name="sg_out" id="sg_out" class="btn btn-default btn-flat"> Sign Out </button>
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
          <p> <?= strtoupper($_SESSION['username_guru_paud']); ?> (<?= strtoupper($_SESSION['jabatan_paud']); ?>) </p>
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
          <a href="<?= $basegu; ?>" id="dashboard"><i style="color: white;" class="glyphicon glyphicon-th-large text-primary"></i> <span id=""> DASHBOARD </span> </a>
        </li>

        <!-- DAILY -->
        <li>
          <a href="#" id="aList1">
            <i class="glyphicon glyphicon-list-alt"></i> <span id="titleList1"> DAILY </span>
          </a>
          <ul class="treeview-menu">

            <li>
              <a href="#" id="subcreate">
                <i class="glyphicon glyphicon-plus text-primary"></i> <span id="isiList2"> Create </span>
              </a>
              <ul class="treeview-menu">
                <li> 
                  <a href="<?= $basegu; ?>createdailystudent" id="create_data"><i class="fa-solid fa-user text-primary"></i> <span style="margin-left: 9px;"> Student </span> </a>
                </li>
                <?php if ($getNipWalas == 1): ?>
                  <li> 
                    <a href="<?= $basegu; ?>createdailygroup" id="createdailygroup"><i class="fa-solid fa-users text-primary" style="margin-left: -3px;"></i> <span style="margin-left: 6px;"> Group Class </span> </a>
                  </li>
                <?php endif ?>
              </ul>
            </li>

            <li>
              <a href="#">
                <i class="glyphicon glyphicon-zoom-in text-primary"></i> <span id="isiListQuery"> Query </span>
              </a>
              <ul class="treeview-menu">
                <li> 
                  <a href="<?= $basegu; ?>querydailystudent" id="query_data_siswa"><i class="fa fa-user text-primary"></i> <span style="margin-left: 1px;" id="sub_isiList1"> Student </span> </a>
                </li>
                <?php if ($getNipWalas == 1): ?>
                  <li> 
                    <a href="<?= $basegu; ?>querydailygroup" id="query_data_group"><i class="fa fa-users text-primary"></i> <span style="margin-left: 1px;" id="sub_isiList2group"> Group Class </span> </a>
                  </li>
                <?php endif ?>
              </ul>
            </li>

            <li>
              <a href="#">
                <i class="glyphicon glyphicon-check text-primary"></i> <span id="isiList3"> Status </span>
              </a>
              <ul class="treeview-menu">
                <li> 
                  <a href="<?= $basegu; ?>status_waiting_approval" id="stat_wait"><i class="glyphicon glyphicon-hourglass text-primary"></i> <span style="margin-left: 7px;" id="sub_isiList2"> </span> Waiting Approval </a>
                </li>
                <li> 
                  <a href="<?= $basegu; ?>status_approved" id="stat_appr"><i class="glyphicon glyphicon-thumbs-up text-primary"></i> <span style="margin-left: 7px;" id="sub_isiList3"> </span> Approved </a>
                </li>
                <li> 
                  <a href="<?= $basegu; ?>status_not_approved" id="stat_not_appr"><i class="glyphicon glyphicon-thumbs-down text-primary"></i> <span style="margin-left: 7px;" id="sub_isiList4"> </span> Not Approved </a>
                </li>
              </ul>
            </li>

          </ul>
        </li>

        <?php if ($getNipWalas == 1): ?>

          <!-- GROUP CLASS -->
          <li>
            <a href="#" id="isgroupclass">
              <i class="fa-solid fa-users"></i> <span style="margin-left: 2px;"> GROUP CLASS </span>
            </a>
            <ul class="treeview-menu">
              
              <li>
                <a href="<?= $basegu; ?>groupclass/<?= $getNameGroupClass['nama_group_kelas']; ?>" id="is_groupclass"><i class="glyphicon glyphicon-list-alt text-primary"></i> <span id="g_kls"> GROUP CLASS <?= $getNameGroupClass['nama_group_kelas']; ?> </span> </a>
              </li>

            </ul>
          </li>
          
        <?php endif ?>

        <!-- MAINTENANCE -->
        <li>
          <a href="#" id="list_maintenance">
            <i class="glyphicon glyphicon-cog"></i> <span id="titleList2"> MAINTENANCE </span>
          </a>
          <ul class="treeview-menu">
            
            <li>
              <a href="<?= $basegu; ?>changepassword" id="changepassword"><i class="glyphicon glyphicon-wrench text-primary" id="create"></i> <span id="isiList4"> Change Password </span> </a>
            </li>

            <li>
              <a href="<?= $basegu; ?>changenumberphone" id="changenumberphone"><i class="glyphicon glyphicon-phone text-primary" id="create"></i> <span id="isiList4"> Change Number Phone </span> </a>
            </li>

          </ul>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
   <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <?php if (!empty($_GET['on'])): ?>

      <?php if ($_GET['on'] != 'createdailystudent' && $_GET['on'] != 'creategroupdaily'): ?>
        
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
    <section class="content">

<?php
  if(empty($_GET['on'])){require 'view/dashboard/index.php';}
  else{
    $act=($_GET['on']);
    if($act=='kelas'){
      require 'view/a-kelas.php';
    }

    #region checkpembayaraninputdata
    else if ($act == 'checkpembayarandaninputdata') {
      require 'view/spp/check_pembayaran/check_pembayaran_dan_inputdata.php';
    }

    #region edit data
    elseif ($act == 'editdata') {
      require 'view/spp/edit_data/editdata.php';
    }

    #region import data
    elseif($act == 'upload') {
      require 'view/spp/upload/uploadfile.php';
    }

    #region dashboard
    // elseif($act == 'dashboard') {
    //   $createDaily = 0;
    //   require 'view/dashboard/index.php';
    // } 

    #region daily
    elseif($act == 'createdailystudent') {
      require 'view/daily/create/index.php';
    } elseif($act == 'querydailystudent') {
      require 'view/daily/query/querydailysiswa.php';
    } elseif($act == 'querydailygroup') {
      require 'view/daily/query/querydailygroup.php';
    } elseif($act == 'listdaily') {
      require 'view/daily/query/listdaily.php';
    } elseif($act == 'teachercreatedaily') {
      require 'view/daily/query/teachercreatedaily.php';
    } elseif($act == 'lookactivity') {
      require 'view/daily/query/lookactivity.php';
    } elseif($act == 'createdailygroup') {
      require 'view/daily/create/group/index.php';
    } elseif($act == 'teachercreategroupdaily') {
      require 'view/daily/query/teachercreategroupdaily.php';
    }

    #region group class if teacher is walas
    elseif($act == 'groupclass') {
      require 'view/groupclass/index.php';
    } 

    elseif($act == 'addtogroupclass') {
      require 'view/groupclass/addtogroup.php';
    }

    #region form maintenance
    elseif ($act == 'tahunajaran') {
      require 'view/maintenance/tahun_ajaran/index.php';
    } 

    #region status_daily
    elseif($act == 'status_waiting_approval') {
      require 'view/daily/status/waiting/index.php';
    }

    elseif ($act == 'status_approved') {
      require 'view/daily/status/approved/index.php';
    }

    elseif ($act == 'status_not_approved') {
      require 'view/daily/status/not_approved/index.php';
    }

    #region change password
    else if ($act == 'changepassword') {
      require 'view/maintenance/change_password/index.php';
    }

    else if ($act == 'changenumberphone') {
      require 'view/maintenance/change_numberphone/index.php';
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
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo $aplikasi['namasek']; ?></a></strong> by ATH & IRP
 
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

  $(document).ready(function(){

    let timeIsOut = `<?= $timeIsOut; ?>`

    if (timeIsOut == 1) {

      const myTimeout = setTimeout(showPopUp, 1000);

    }

    $("#change_pass").click(function(){
      location.href = `<?= $basegu; ?>changepassword`
    })

    function showPopUp() {
      $.ajax({
        url   : `<?= $basegu; ?>logout`,
        type  : 'POST',
        data  : {
          is_out : true,
        },
        success:function(data){
          
          let callBack = JSON.parse(data).is_val
          if (callBack == true) {
            Swal.fire({
              title: 'TIME IS OUT',
              icon: "warning"
            });

            setTimeout(isLogOut, 1500);
            
          } else if(callBack == false) {
            Swal.fire({
              title: 'LOG OUT',
              icon: "warning"
            });

          }

        }
      })

      setTimeout(clearSession, 1200);
      
    }

    function clearSession() {
      localStorage.removeItem("showpopup");
      document.location.href = `<?= $base; ?>`
    }

    let role              = `<?= $nama_role; ?>`
    // alert(role)
    let showNotif         = document.getElementById('notifMasuk')

    $("#sg_out").click(function(){
      $.ajax({
        url : `<?= $basegu; ?>a-control/<?= md5('logout_act1_guru_paud'); ?>/access`,
        type : 'GET',
        success:function(data) {
          let response_data = JSON.parse(data)[0];
          if (response_data == "is_logout") {
            Swal.fire({
              title: 'LOG OUT',
              icon: "success"
            });

            showPopUpLogOut();
          }
        }
      });
    })

    function showPopUpLogOut() {
      Swal.fire({
        title: 'LOG OUT',
        icon: "warning"
      });

      setTimeout(redirectPage, 1200);
      
    }

    function redirectPage() {
      document.location.href = `<?= $base; ?>`
    }

    const loadData = () => {

      setInterval(function(){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {

            let jumlahAppr        = JSON.parse(this.responseText).notif_appr;
            let jumlahWaitAppr    = JSON.parse(this.responseText).waiting_list;
            let jumlahNtAppr      = JSON.parse(this.responseText).notif_not_appr;

            $("#viewAllStatAppr").text(JSON.parse(this.responseText).notif_appr_all);
            $("#viewAllStatWait").text(JSON.parse(this.responseText).wt_list_all);
            $("#viewAllStatNotAppr").text(JSON.parse(this.responseText).notif_not_appr_all);
            // let jumlahWtAppr  = JSON.parse(this.responseText).notif_not_yet_appr;

            if (jumlahAppr == 0) {

              $("#notifApproveList").hide();
              $("#pesanSdhApprove").html(`Belum ada daily yang di Approved Hari Ini`);
              $(".msgAppr").css("background-color", "whitesmoke");
              $("#isi_pesan_sudah_approve").hide();

            } else if (jumlahAppr != 0) {

              $("#notifApproveList").show();
              $("#isi_pesan_sudah_approve").show();
              $("#notifApproveList").text(JSON.parse(this.responseText).notif_appr);
              $("#pesanSdhApprove").html(`<span style="font-weight: bold;"> ${JSON.parse(this.responseText).notif_appr} </span> Daily Sudah di Approve Hari Ini`);
              $(".msgAppr").css("background-color", "lightyellow");
              $("#isi_pesan_sudah_approve").html(JSON.parse(this.responseText).isi_notif_approved);

            }

            if (jumlahNtAppr == 0) {

              $("#notifNotApproveList").hide();
              $("#pesanTdkDiApprove").html(`Belum ada daily yang Tidak Disetujui hari ini`);
              $("#pesanTdkDiApprove").css({
                "background-color" : "whitesmoke",
                "color"            : "black"
              });
              $(".notapprlist").hide();

            } else if (jumlahNtAppr != 0) {

              $("#notifNotApproveList").show();
              $("#notifNotApproveList").text(JSON.parse(this.responseText).notif_not_appr);
              // $("#untukNotifApprKepsek").append(JSON.parse(this.responseText).elementNotifAppr);
              $("#pesanTdkDiApprove").html(`<span style="font-weight: bold;"> ${JSON.parse(this.responseText).notif_not_appr} </span> Daily Tidak di Approve`);
              $("#pesanTdkDiApprove").css({
                "background-color": "red",
                "color" : "yellow"
              });
              $("#isi_pesan_tdk_di_approve").html(JSON.parse(this.responseText).isi_notif_not_approved);

            }

            if(jumlahWaitAppr == 0 ) {

              $("#waitingList").html(`Tidak ada daily yang di buat`);
              $("#notifWaitApproveList").hide();
              $(".msgWaitAppr").css("background-color", "whitesmoke");
              $("#isi_pesan_belum_approve").hide();

            } else if (jumlahWaitAppr != 0 ) {

              $("#notifWaitApproveList").show();
              $("#isi_pesan_belum_approve").show();
              $("#notifWaitApproveList").text(JSON.parse(this.responseText).waiting_list);
              $("#waitingList").html(`Ada <span style="font-weight: bold;"> ${JSON.parse(this.responseText).waiting_list} </span> Daily Menunggu Persetujuan Untuk Di Posting Hari Ini`)
              $(".msgWaitAppr").css("background-color", "lightyellow");
              $("#isi_pesan_belum_approve").html(JSON.parse(this.responseText).isi_waiting_list);

            }

            $(".wtlist").click(function(e){
              e.preventDefault();

              let dataTglUpload = $(this).data('tgl_upload');
              let dataImage     = $(this).data('img');
              let dataSiswa     = $(this).data('siswa_blmappr');
              let dataJudul     = $(this).data('judul');
              let dataIsi       = $(this).data('isian');
              let grouporstd    = $(this).data('group_or_std');

              let image         = document.querySelector("img[id='foto_upload']");
              let labelstdgroup = $("#lbl_std_or_gp").html();

              $("#modal-default").modal('show');

              if (grouporstd == 'group') {
                $("#lbl_std_or_gp").text('GROUP');
              } else {
                $("#lbl_std_or_gp").text('STUDENT');
              }

              $("#siswa_daily").val(dataSiswa);
              $("#tanggal_upload").val(dataTglUpload);
              image.setAttribute("src", `../image_uploads/${dataImage}`);
              $("#title_daily").val(dataJudul);
              $("#main_daily").html(dataIsi);

            });

            $(".apprlist").click(function(e) {

              e.preventDefault();
              $("#modal-default-appr").modal('show');

              let dataDailyId       = $(this).data('daily_id');
              let dataSender        = $(this).data('pengirim');
              let dataGuru          = $(this).data('nama_guru_lengkap');
              let dataNisSiswa      = $(this).data('nis_siswa_was_appr');
              let dataSiswaOrGroup  = $(this).data('siswaorgroup_was_appr');
              let dataSiswa         = $(this).data('siswa_was_appr');
              let dataTglAppr       = $(this).data('tgl_approved');
              let dataTglUploadAppr = $(this).data('tgl_upload');
              let dataImageAprr     = $(this).data('img');
              let dataTitleAppr     = $(this).data('judul');
              let dataIsiAppr       = $(this).data('isian');
              let dataRoomKey       = $(this).data('room_key');
              let dataDfTglOri      = $(this).data('tgl_ori');
              let grouporstd        = $(this).data('group_or_std');
              let dataNip           = $(this).data('nipguru');
              let imageAppr         = document.querySelector("img[id='foto_upload_appr']");

              // if (`<?= $thisPage; ?>` == `<?= $basegu; ?>lookactivity/${dataRoomKey}`) {

              //   $('#formDfAppr').attr('action', `${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` == `<?= $basegu; ?>createdailystudent`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` == `<?= $basegu; ?>createdailygroup`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` == `<?= $basegu; ?>status_waiting_approval`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` == `<?= $basegu; ?>status_approved`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` == `<?= $basegu; ?>status_not_approved`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` == `<?= $basegu; ?>changepassword`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` == `<?= $basegu; ?>changenumberphone`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` != `<?= $basegu; ?>`) {

              //   $('#formDfAppr').attr('action', `${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` == `<?= $basegu; ?>groupclass/${dataSiswaOrGroup}`) {

              //   $('#formDfAppr').attr('action', `<?= $basegu; ?>lookactivity/${dataRoomKey}`);

              // } else {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // }

              if (`<?= $thisPage; ?>` == `<?= $basegu; ?>lookactivity/${dataRoomKey}`) {

                $('#formDfAppr').attr('action', `${dataRoomKey}`);

              } else {

                $('#formDfAppr').attr('action', `<?= $basegu; ?>lookactivity/${dataRoomKey}`);

              }

              if (grouporstd == 'group') {
                $("#lbl_std_or_gp_df_appr").text('GROUP');
                $("#lookdaily_appr").attr('name', 'daily_group');
                $("#df_roomkey_lookdaily").attr('name', 'roomkey_group_lookdaily');
              } else {
                $("#lbl_std_or_gp_df_appr").text('STUDENT');
                $("#lookdaily_appr").attr('name', 'redirectLookDaily');
                $("#df_roomkey_lookdaily").attr('name', 'roomkey_lookdaily');
              }

              $("#date_approved").val(dataTglAppr);
              $("#pengirim_appr").val(dataSender);
              $("#siswa_daily_appr").val(dataSiswa);
              $("#tanggal_upload_appr").val(dataTglUploadAppr);

              imageAppr.setAttribute("src", `../image_uploads/${dataImageAprr}`);

              $("#title_daily_appr").val(dataTitleAppr);
              $("#main_daily_appr").html(dataIsiAppr);
              $("#df_nis_lookdaily").val(dataNisSiswa);
              $("#df_siswa_lookdaily").val(dataSiswa);
              $("#df_guru_lookdaily").val(dataGuru);
              $("#df_nipguru_lookdaily").val(dataNip);
              $("#df_img_lookdaily").val(dataImageAprr);
              $("#df_tglappr_lookdaily").val(dataTglAppr);
              $("#df_jdl_lookdaily").val(dataTitleAppr);
              $("#df_isi_lookdaily").val(dataIsiAppr);
              $("#df_roomkey_lookdaily").val(dataRoomKey);
              $("#df_tglori_posting_lookdaily").val(dataDfTglOri);

            });

            $(".notapprlist").click(function(e) {

              e.preventDefault();
              $("#modal-default-notappr").modal('show');

              let dataDailyId           = $(this).data('daily_id');
              let dataSender            = $(this).data('pengirim');
              let dataSiswa             = $(this).data('siswa_not_appr');
              let dataTglNotAppr        = $(this).data('tgl_noapproved');
              let dataTglUploadNotAppr  = $(this).data('tgl_upload');
              let dataImageNotAprr      = $(this).data('img');
              let dataTitleAppr         = $(this).data('judul');
              let dataIsiAppr           = $(this).data('isian');
              let dataReason            = $(this).data('reason_daily');
              let grouporstd            = $(this).data('group_or_std');

              let imageNotAppr          = document.querySelector("img[id='foto_upload_notappr']"); 

              if (grouporstd == 'group') {
                $("#lbl_std_or_gp_df_noappr").text('GROUP');
              } else {
                $("#lbl_std_or_gp_df_noappr").text('STUDENT');
              }

              $("#date_not_approved").val(dataTglNotAppr);
              $("#pengirim_notappr").val(dataSender);
              $("#tanggal_upload_notappr").val(dataTglUploadNotAppr);
              $("#siswa_daily_notappr").val(dataSiswa);

              imageNotAppr.setAttribute("src", `../image_uploads/${dataImageNotAprr}`);

              $("#title_daily_notappr").val(dataTitleAppr);
              $("#main_daily_notappr").html(dataIsiAppr);

              if(dataReason == 'no_comment') {
                $("#div_default_reason").hide();
              } else if (dataReason != 'no_comment') {
                $("#div_default_reason").show();
                $("#reason_notappr").text(dataReason); 
              }

            });

          }
        };

        xhttp.open("GET", `<?= $basegu; ?>data`, true);
        xhttp.send();
      }, 500)

    }

    loadData();

  })

  function isLogOut() {
    document.location.href = `<?= $base; ?>`
  }

  function aFList1() {
    let getTitleList1 = document.getElementById('isiList1').innerHTML;
    $("#isiMenu").css({
      "margin-left" : "5px"
    });
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

    $('#list_group').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
      "autoWidth": false
    });

    $("#list_siswa1").DataTable();

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

    $('#tabelCariSiswaCheckPembayarans').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": false,
      "autoWidth": false
    });

    $("#hightlight_list_siswa").DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "info": true,
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
