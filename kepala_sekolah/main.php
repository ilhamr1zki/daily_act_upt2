<?php 
  
  date_default_timezone_set('Asia/Jakarta');
  $date=date('Y-m-d'); 
  
  require '../php/config.php'; 
  require '../php/function.php'; 
  // require('spreadsheet-reader-master/php-excel-reader/excel_reader2.php');
  // require('spreadsheet-reader-master/SpreadsheetReader.php');

  // error_reporting(1);exit;

  // Cek status login user jika tidak ada session
  if (!$user->isLoggedInHeadMaster()) { 

    header("location:../"); //Redirect ke halaman login  
  }

  $nama_role  = $_SESSION['id_kepsek'];
  $thisPage   = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
  // echo $thisPage;exit;

  // echo $_SESSION['id_kepsek'];exit;

  $currTahun    = "";
  $currSemester = "";

  $createDaily  = 0; 

  $check = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tahun_ajaran WHERE status = 'aktif' AND c_role = '$_SESSION[id_kepsek]' "));

  if ($check != 0) {

    $currTahun = mysqli_query($con, "SELECT tahun FROM tahun_ajaran WHERE status = 'aktif' AND c_role = '$_SESSION[id_kepsek]' ");
    $currTahun = mysqli_fetch_assoc($currTahun)['tahun'];
    // echo $currTahun;exit;
    $currSemester = mysqli_query($con, "SELECT semester FROM tahun_ajaran WHERE status = 'aktif' AND c_role = '$_SESSION[id_kepsek]' ");
    $currSemester = mysqli_fetch_assoc($currSemester)['semester'];

  } else {

    $currTahun    = "";
    $currSemester = "";

  }

  $na = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM kepala_sekolah where id = '$_SESSION[id_kepsek]' ")); 
  //$setting=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM setting limit 1 "));*/ 
  if (isset($_GET['nextPage'])) {
    echo $_GET['nextPage'];exit;
  }

  $kepsekSD      = "/SD/i";
  $kepsekPAUD    = "/PAUD/i";

  $foundDataSD    = preg_match($kepsekSD, $_SESSION['c_kepsek']);
  $foundDataPAUD  = preg_match($kepsekPAUD, $_SESSION['c_kepsek']);

  $isKepalaSekolah = "";

  if ($foundDataSD == 1) {
    $isKepalaSekolah = "KEPALA SEKOLAH SD";
  } elseif ($foundDataPAUD == 1) {
    $isKepalaSekolah = "KEPALA SEKOLAH TK/PAUD";
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
    
    body {
      font-family:arial;
    }

    hr.new1 {
      border-top: 1px solid black;
    }

    #v_all:hover {
      background-color: #ddd;
    }

    #swal2-title {
      font-size: 17px;
    }

    #tr_dashboard {
      background-color: lightgreen;
    }

    #tr_dashboard {
      cursor: pointer;
    }

    #tanggal_upload {
      width: 212px;
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

    #inpage_wtappr {
      cursor: pointer;
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

    #hg_date_appr,
    #hg_tanggal_upload_appr,
    #hg_tanggal_upload_notappr,
    #hg_date_not_approved,
    #date_not_approved,
    #tanggal_upload_notappr,
    #date_approved,
    #hightlight_tanggal_upload,
    #tanggal_upload_appr,
    #inpage_date_not_approved,
    #inpage_tanggal_upload_notappr {
      width: 213px;
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

    .center {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      padding: 10px;
    }

    #namaguruchat,
    #tglsendguru,
    #namakepsek,
    #tglsendkepsek {
      font-size: 12px;
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

      .swal2-show {
        width: 75% !important;
        font-size: 10px !important;
        font-weight: bold;
      }

      #dotTo {
        margin-left: 23px;
      }

      .center h4 {
        font-size: 12px;
      }

      #boxTitle {
        font-size: 10px;
      }

      #addIcon {
        top: 2px;
      }

      #namaguru {
        font-size: 12px;
        margin-top: 4px;
      }

      #namaguruchat,
      #tglsendguru,
      #namakepsek,
      #tglsendkepsek {
        font-size: 10px;
      }

      #tglpublish {
        font-size: 9px;
      }

      #filterDate {
        display: flex;
        flex-direction: column;
      }

      #swal2-title {
        font-size: 15px;
      }

      #tanggal_upload {
        width: 100%;
      }

      #hg_date_appr,
      #hg_tanggal_upload_appr,
      #hg_tanggal_upload_notappr,
      #hg_date_not_approved,
      #date_not_approved,
      #tanggal_upload_notappr,
      #date_approved,
      #tanggal_upload_appr,
      #hightlight_tanggal_upload,
      #inpage_date_not_approved,
      #inpage_tanggal_upload_notappr {
        width: 100%;
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

      .center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 10px;
      }

      #komenkosong {
        font-size: 10px;
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
              <input type="hidden" id="id_daily_waiiting">
              <input type="hidden" id="nip_daily_waiiting">
              <div class="row">
                <div class="col-sm-9">
                  <div class="form-group">
                    <label id="label_status_wt"> STATUS : <strong style="color: yellowgreen;" id="label_status_wait_appr"> Waiting Approval <i id="icon_change" class="fa fa-fw fa-hourglass-half" style="color: yellowgreen;"></i> </strong> </label>
                  </div>
                </div>
              </div>

              <div class="row">

                <div class="col-sm-6">
                  <div class="form-group">
                    <label> FROM </label>
                    <input type="text" readonly class="form-control" name="pengirim" id="pengirim">
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

              <div class="form-group reason">
                <label for="reason">REASON (Optional)</label>
                <input type="text" id="reason_modal_default" class="form-control" placeholder="Reason ... (Optional)">
              </div>

            </form>

          </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="close_approve_wait" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="button" id="cancel_not_approve" class="btn btn-primary">Cancel Not Approve</button>
        <button type="button" id="not_approve" class="btn btn-danger">Not Approve <i class="glyphicon glyphicon-remove"></i> </button>
        <button type="button" id="approve" class="btn btn-success">Approve <i class="glyphicon glyphicon-ok"></i> </button>
        <button type="button" id="save_not_approve" class="btn btn-danger">Not Approve <i class="glyphicon glyphicon-remove"></i> </button>
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

              <div class="row">

                <div class="col-sm-6">
                  <div class="form-group">
                    <label> FROM </label>
                    <input type="text" readonly class="form-control" name="hg_pengirim_appr" id="hg_pengirim_appr">
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="date_approved"> DATE APPROVE </label>
                    <input type="text" id="hg_date_appr" name="date_approved" readonly class="form-control">
                  </div>
                </div>

              </div>

              <div class="form-group">
                <label for="hg_siswa_daily_appr" id="lbl_std_or_gp_hg_appr"> STUDENT </label>
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
          <input type="hidden" id="hg_nip_guru_lookdaily" name="nipguru_lookdaily">
          <input type="hidden" id="hg_nama_guru_lookdaily" name="guru_lookdaily">
          <input type="hidden" id="hg_nis_siswa_lookdaily" name="nis_or_idgroup_lookdaily">
          <input type="hidden" id="hg_nama_siswa_lookdaily" name="nama_siswa_or_groupkelas_lookdaily">
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

              <div class="row">

                <div class="col-sm-6">
                  <div class="form-group">
                    <label> FROM </label>
                    <input type="text" readonly class="form-control" name="hg_pengirim_notappr" id="hg_pengirim_notappr">
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="hg_date_not_approved"> DATE NOT APPROVE </label>
                    <input type="text" id="hg_date_not_approved" name="hg_date_not_approved" readonly class="form-control">
                  </div>
                </div>

              </div>

              <div class="form-group">
                <label for="hg_siswa_daily_notappr" id="lbl_std_or_gp_hg_noappr">STUDENT</label>
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
                <label for="hg_reason_notappr">REASON NOT APPROVED</label>
                <div id="hg_reason_notappr" style="height: 150px;border: 1px solid #eee;padding: 10px; background-color: #eee;" class="form-control">
                  
                </div>
                <!-- <textarea style="height: 150px;" class="form-control" id="main_daily" rows="3" placeholder="Announcement ..."></textarea> -->
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
              <input type="hidden" id="highlight_nip_daily_waiiting">
              <div class="row">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label id="label_status_wt"> STATUS : <strong style="color: yellowgreen;" id="hightlight_status_wait_appr"> Waiting Approval <i id="icon_change" class="fa fa-fw fa-hourglass-half" style="color: yellowgreen;"></i> </strong> </label>
                  </div>
                </div>
              </div>

              <div class="row">

                <div class="col-sm-6">
                  <div class="form-group">
                    <label> FROM </label>
                    <input type="text" readonly class="form-control" name="hightlight_pengirim" id="hightlight_pengirim">
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
                <label for="hightlight_siswa_daily" id="lbl_std_or_gp_hg_wtappr">STUDENT</label>
                <input type="text" id="hightlight_siswa_daily" name="hightlight_siswa_daily" readonly class="form-control">
              </div>

              <div class="form-group gambar_banner">
                <label for="banner"> UPLOADED PHOTO </label>
                <!-- <input type="text" id="banner" name=""> -->
                <img class="img-responsive pad" id="hightlight_foto_upload" alt="Photo">
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
        <button type="button" id="hightlight_cancel_not_approve" class="btn btn-primary">Cancel Not Approve</button>
        <button type="button" id="hightlight_not_approve" class="btn btn-danger">Not Approve <i class="glyphicon glyphicon-remove"></i> </button>
        <button type="button" id="hightlight_approve" class="btn btn-success">Approve <i class="glyphicon glyphicon-ok"></i> </button>
        <button type="button" id="hightlight_save_not_approve" class="btn btn-danger">Not Approve <i class="glyphicon glyphicon-remove"></i> </button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- Modal untuk Menu Waiting Approval -->
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
              <input type="hidden" id="inpage_nip_daily_waiiting_wt_appr">
              <div class="row">
                <div class="col-sm-8">
                  <div class="form-group">
                    <label id="label_status_wt"> STATUS : <strong style="color: yellowgreen;" id="hightlight_status_wait_appr"> Waiting Approval <i id="icon_change" class="fa fa-fw fa-hourglass-half" style="color: yellowgreen;"></i> </strong> </label>
                  </div>
                </div>
              </div>

              <div class="row">

                <div class="col-sm-6">
                  <div class="form-group">
                    <label> FROM </label>
                    <input type="text" readonly class="form-control" name="inpage_pengirim_wt_appr" id="inpage_pengirim_wt_appr">
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
                <label for="inpage_siswa_daily_wt_appr" id="lbl_std_or_gp_inpage_wtappr">STUDENT</label>
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

              <div class="form-group inpage_reason">
                <label for="inpage_reason">REASON (Optional)</label>
                <input type="text" id="inpage_reason" class="form-control" placeholder="Reason ... (Optional)">
              </div>

            </form>

          </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="inpage_close_wt_appr" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="button" id="inpage_cancel_not_approve_wt_appr" class="btn btn-primary">Cancel Not Approve</button>
        <button type="button" id="inpage_not_approve_wt_appr" class="btn btn-danger">Not Approve <i class="glyphicon glyphicon-remove"></i> </button>
        <button type="button" id="inpage_approve_wt_appr" class="btn btn-success">Approve <i class="glyphicon glyphicon-ok"></i> </button>
        <button type="button" id="inpage_save_notappr_wt_appr" class="btn btn-danger">Not Approve <i class="glyphicon glyphicon-remove"></i> </button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- Akhir Modal untuk Menu Waiting Approval -->

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

              <div class="row">

                <div class="col-sm-6">
                  <div class="form-group">
                    <label> FROM </label>
                    <input type="text" readonly class="form-control" name="inpage_pengirim_appr" id="inpage_pengirim_appr">
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="inpage_date_approved"> DATE APPROVE </label>
                    <input type="text" id="inpage_date_approved" name="inpage_date_approved" readonly class="form-control">
                  </div>
                </div>

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
        <form id="form_inpage_appr" method="post">
          <input type="hidden" id="inpage_frompage_lookdaily" name="frompage_lookdaily" value="status_approved">
          <input type="hidden" id="inpage_roomkey_lookdaily" name="roomkey_lookdaily">
          <input type="hidden" id="inpage_nip_guru_lookdaily" name="nipguru_lookdaily">
          <input type="hidden" id="inpage_nama_guru_lookdaily" name="guru_lookdaily">
          <input type="hidden" id="inpage_nama_siswa_lookdaily" name="nama_siswa_or_groupkelas_lookdaily">
          <input type="hidden" id="inpage_nis_siswa_lookdaily" name="nis_or_idgroup_lookdaily">
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

              <div class="row">

                <div class="col-sm-6">
                  <div class="form-group">
                    <label> FROM </label>
                    <input type="text" readonly class="form-control" name="inpage_pengirim_notappr" id="inpage_pengirim_notappr">
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="inpage_date_not_approved"> DATE NOT APPROVE </label>
                    <input type="text" id="inpage_date_not_approved" name="inpage_date_not_approved" readonly class="form-control">
                  </div>
                </div>

              </div>

              <div class="form-group">
                <label for="inpage_siswa_daily_notappr" id="lbl_std_or_gp_inpage_notappr">STUDENT</label>
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

              <div class="row">

                <div class="col-sm-6">
                  <div class="form-group">
                    <label> FROM </label>
                    <input type="text" readonly class="form-control" name="pengirim_appr" id="pengirim_appr">
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="date_approved"> DATE APPROVE </label>
                    <input type="text" id="date_approved" name="date_approved" readonly class="form-control">
                  </div>
                </div>

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
        <form id="formDfAppr" method="post">
          <input type="hidden" id="df_frompage_lookdaily" name="frompage_lookdaily" value="homepage">
          <input type="hidden" id="df_roomkey_lookdaily" name="roomkey_lookdaily">
          <input type="hidden" id="df_nis_or_idgroup_lookdaily" name="nis_or_idgroup_lookdaily">
          <input type="hidden" id="df_nama_siswa_lookdaily" name="nama_siswa_or_groupkelas_lookdaily">
          <input type="hidden" id="df_nip_guru_lookdaily" name="nipguru_lookdaily">
          <input type="hidden" id="df_nama_guru_lookdaily" name="guru_lookdaily">
          <input type="hidden" id="df_foto_upload_lookdaily" name="foto_upload_lookdaily">
          <input type="hidden" id="df_tgl_posting" name="tgl_posting_lookdaily">
          <input type="hidden" id="df_tglori_posting_lookdaily" name="tglori_posting_lookdaily">
          <input type="hidden" id="df_jdl_posting_lookdaily" name="jdl_posting_lookdaily">
          <input type="hidden" id="df_isi_posting_lookdaily" name="isi_posting_lookdaily">
          <button type="submit" name="redirectLookDaily" id="df_lookdaily_appr" class="btn btn-primary"> <i class="glyphicon glyphicon-eye-open"></i> Lookdaily </button>
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

              <div class="row">

                <div class="col-sm-6">
                  <div class="form-group">
                    <label> FROM </label>
                    <input type="text" readonly class="form-control" name="pengirim_notappr" id="pengirim_notappr">
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="date_not_approved"> DATE NOT APPROVE </label>
                    <input type="text" id="date_not_approved" name="date_not_approved" readonly class="form-control">
                  </div>
                </div>

              </div>

              <div class="form-group">
                <label for="siswa_daily_notappr" id="lbl_std_or_gp_df_notappr">STUDENT</label>
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

          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" id="untukNotifApprKepsek" data-toggle="dropdown">
              <i class="glyphicon glyphicon-thumbs-up"></i>
              <span class="label label-warning" id="notifApproveList"> </span>
            </a>
            <ul class="dropdown-menu">
              <li class="header" id="pesanSdhApprove" style="text-align: center;"> </li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" id="isi_pesan_sdh_approve">
                  
                </ul>
              </li>
              <li class="footer"><a href="status_approved" id="v_all"> View all (<strong id="viewAllStatAppr"></strong>) Status Approved </a></li>
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
            <a href="#" class="dropdown-toggle" id="untukNotifWaitKepsek" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning" id="notifWaitingList"></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header" id="pesanBelumApprove" style="text-align: center; background-color: lightyellow;"> </li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" id="isi_pesan_belum_approve">
                  
                </ul>
              </li>
              <li class="footer"><a href="status_waiting_approval" id="v_all"> View all (<strong id="viewAllStatWait"></strong>) Status Waiting </a></li>
            </ul>
          </li>

          <!-- <li class="active"> -->
            <!-- <a><i class="glyphicon glyphicon-stats"></i> <?php echo $ata['tahun']; ?> Semester <?php echo $ata['semester']; ?></a> -->
            <!-- <a><i class="glyphicon glyphicon-stats"></i> <?= $currTahun; ?> Semester <?= $currSemester; ?></a> -->
          <!-- </li> -->

        <?php /*if(empty($_GET['thisaction']) or $_GET['thisaction']!='grafik'){ ?>
          <li>
            <a href="<?php echo $basekepsek; ?>a-control/<?php echo md5('testtoken').'/'.$t1=md5(date('H:i:s')); ?>"><i class="glyphicon glyphicon-stats"></i> Test Token</a>
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
                <span class="hidden-xs"><?php echo strtoupper(ucfirst($na['username'])); ?></span>
              </a>

            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header" style="height: 185px;">
                  <img src="<?php echo $base; ?>imgstatis/logo2.png" class="img-circle" alt="User Image">
                <p>
                  <?php echo strtoupper(ucfirst($na['nama'])); ?>
                  <small>(<?= ucfirst($isKepalaSekolah); ?>)</small>
                </p>
                <p style="font-size: 11px;"><?php echo $aplikasi['namasek']; ?></p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                    <button type="submit" name="change_pass" id="change_pass" class="btn btn-default btn-flat"> <i class="glyphicon glyphicon-pencil"></i> Ganti Password </button>
                </div>
                <div class="pull-right">
                  <!-- <form action="<?= $basekepsek; ?>" method="post"> -->
                    <button type="submit" id="sg_out" name="sg_out" class="btn btn-default btn-flat"> Sign Out </button>
                  <!-- </form> -->
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
          <p><?php echo strtoupper(ucfirst($na['username'])); ?> </p>
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
          <a href="<?= $basekepsek; ?>" id="dashboard"><i style="color: white;" class="glyphicon glyphicon-th-large text-primary"></i> <span id=""> DASHBOARD </span> </a>
        </li>

        <!-- DAILY -->
        <li>
          <a href="#" id="aList1">
            <i class="glyphicon glyphicon-list-alt"></i> <span id="titleList1"> DAILY </span>
          </a>
          <ul class="treeview-menu">

            <li>
              <a href="#">
                <i class="glyphicon glyphicon-zoom-in text-primary"></i> <span id="isiList2"> Query </span>
              </a>
              <ul class="treeview-menu">
                <li> 
                  <a href="<?= $basekepsek; ?>querydailystudent" id="query_data_siswa"><i class="fa fa-user text-primary" style="margin-left: 2.1px;"></i> <span style="margin-left: 4px;"> </span> Student </a>
                </li>
                <li> 
                  <a href="<?= $basekepsek; ?>querydailygroup" id="query_data_group"><i class="fa fa-users text-primary" style="margin-left: -1px;"></i> <span style="margin-left: 7px;"> </span> Group </a>
                </li>
                <li> 
                  <a href="<?= $basekepsek; ?>querydailyteacher" id="query_data_guru"><i class="glyphicon glyphicon-zoom-in text-primary" style="margin-left: 1px;"></i> <span style="margin-left: 5px;"> </span> Teacher </a>
                </li>
              </ul>
            </li>

            <li>
              <a href="#">
                <i class="glyphicon glyphicon-check text-primary"></i> <span id="isiList3"> Status </span>
              </a>
              <ul class="treeview-menu">
                <li> 
                  <a href="<?= $basekepsek; ?>status_waiting_approval" id="stat_wait"><i class="glyphicon glyphicon-hourglass text-primary"></i> <span style="margin-left: 7px;"> </span> Waiting Approval </a>
                </li>
                <li> 
                  <a href="<?= $basekepsek; ?>status_approved" id="stat_appr"><i class="glyphicon glyphicon-thumbs-up text-primary"></i> <span style="margin-left: 7px;"> </span> Approved </a>
                </li>
                <li> 
                  <a href="<?= $basekepsek; ?>status_not_approved" id="stat_not_appr"><i class="glyphicon glyphicon-thumbs-down text-primary"></i> <span style="margin-left: 7px;"> </span> Not Approved </a>
                </li>
              </ul>
            </li>

          </ul>
        </li>

        <!-- GROUP -->
        <li>
          <a href="#" id="listgroup">
            <i class="fa-solid fa-users"></i> <span id="groupList" style="margin-left: 2px;"> GROUP </span>
          </a>
          <ul class="treeview-menu">

            <li>
              <a href="<?= $basekepsek; ?>listgroup" id="menulistgroup"><i class="fa-solid fa-list" style="color: #337ab7;"></i> <span id="listgp" style="margin-left: 7px;"> List Group </span> </a>
            </li>

          </ul>
        </li>

        <!-- MAINTENANCE -->
        <li>
          <a href="#" id="list_maintenance">
            <i class="glyphicon glyphicon-cog"></i> <span id="titleList2"> MAINTENANCE </span>
          </a>
          <ul class="treeview-menu">
            
            <li>
              <a href="<?= $basekepsek; ?>changepassword" id="changepassword"><i class="glyphicon glyphicon-wrench text-primary" id="create"></i> <span id="isiList4"> Change Password </span> </a>
            </li>

            <li>
              <a href="<?= $basekepsek; ?>changenumberphone" id="changenumberphone"><i class="glyphicon glyphicon-phone text-primary" id="create"></i> <span id="isiList4"> Change Number Phone </span> </a>
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
    elseif($act == 'createdaily') {
      require 'view/daily/create/index.php';
    } elseif($act == 'querydailystudent') {
      require 'view/daily/query/querydailysiswa.php';
    } elseif($act == 'querydailygroup') {
      require 'view/daily/query/querydailygroup.php';
    } elseif($act == 'querydailyteacher') {
      require 'view/daily/query/querydailyteacher.php';
    } elseif($act == 'listdaily') {
      require 'view/daily/query/listdaily.php';
    } elseif($act == 'teachercreatedaily') {
      require 'view/daily/query/teachercreatedaily.php';
    } elseif($act == 'lookactivity') {
      require 'view/daily/query/lookactivity.php';
    } elseif($act == 'createdailybyteacher') {
      require 'view/daily/query/createdailybyteacher.php';
    } elseif($act == 'teachercreategroupdaily') {
      require 'view/daily/query/teachercreategroupdaily.php';
    }

    #region group
    elseif ($act == 'listgroup') {
      require 'view/group/list/index.php';
    }

    elseif($act == 'groupclass') {
      require 'view/group/anggota/index.php';
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

    #region change password
    else if ($act == 'changenumberphone') {
      require 'view/maintenance/change_number_phone/index.php';
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

  let createDailys  = `<?= $createDaily; ?>`

  $("#sg_out").click(function(){
    $.ajax({
      url   : `<?= $basekepsek; ?>logout`,
      type  : 'POST',
      data  : {
        is_out : true,
      },
      success:function(data){
        
        let callBack = JSON.parse(data).is_val
        if (callBack == true) {
          localStorage.removeItem("showpopup");
          Swal.fire({
            title: 'LOG OUT',
            icon: "success"
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
  })

  function isLogOut() {
    document.location.href = `<?= $base; ?>`
  }

  let inPage = `<?= $diMenu; ?>`

  $(document).ready(function(){

    $("#approve").click(function() {
      $.ajax({
        url  : `<?= $basekepsek; ?>data`,
        type : "POST",
        data : {
          daily_id  : $("#id_daily_waiiting").val(),
          nip_guru  : $("#nip_daily_waiiting").val()
        },
        success:function(data) {

          console.log(JSON.parse(data).status_approve);
          let validNumberPhone = JSON.parse(data).number_phone_teacher;
          let createdByTeacher = JSON.parse(data).created_by_teacher;

          if (validNumberPhone == true) {
            
            Swal.fire({
              title : "Approve Daily",
              icon  : "success",
              timer : 1000
            });

            $("#not_approve").hide();
            $("#approve").hide();
            $("#close_approve_wait").click();

            if (inPage == 'dashboard') {

              setTimeout(function(){
                location.href = `<?= $basekepsek; ?>`;
              }, 1500);

            } else if (inPage == 'waiting') {

              setTimeout(function(){
                location.href = `<?= $basekepsek; ?>status_waiting_approval`;
              }, 1500);

            } else if (inPage == 'approved') {

              setTimeout(function(){
                location.href = `<?= $basekepsek; ?>status_approved`;
              }, 1500);

            }

          } else if (validNumberPhone == false) {

            Swal.fire({
              title : `Invalid Number Phone Teacher ${createdByTeacher}`,
              icon  : "warning",
              timer : 1500
            });

            setTimeout(function(){
              location.href = `<?= $basekepsek; ?>`;
            }, 1500);

          }

        }

      })

    });

    $("#change_pass").click(function() {
      location.href = `<?= $basekepsek; ?>changepassword`
    });

    $("#not_approve").click(function(e){
      e.preventDefault();
      $("#approve").hide();
      $("#not_approve").hide();
      $("#cancel_not_approve").show();
      $("#save_not_approve").show();
      $(".reason").show();
      $("#reason_modal_default").focus();
      $('#modal-default').scrollTop($('#modal-default')[0].scrollHeight);
    });

    $("#save_not_approve").click(function() {

      $.ajax({
        url  : `<?= $basekepsek; ?>data`,
        type : "POST",
        data : {
          daily_id_not_appr  : $("#id_daily_waiiting").val(),
          is_reason          : $("#reason_modal_default").val(),
          nipguru            : $("#nip_daily_waiiting").val()
        },
        success:function(data) {

          console.log(JSON.parse(data).status_approve);

          Swal.fire({
            title : "Not Approved",
            icon  : "success",
            timer : 1000
          });

          $("#reason_modal_default").val('');
          $("#not_approve").hide();
          $("#approve").hide();
          $("#close_approve_wait").click();

          if (inPage == 'dashboard') {

            setTimeout(function(){
              location.href = `<?= $basekepsek; ?>`;
            }, 1500);

          } else if (inPage == 'waiting') {

            setTimeout(function(){
              location.href = `<?= $basekepsek; ?>status_waiting_approval`;
            }, 1500);

          } else if (inPage == 'not_approved') {

            setTimeout(function(){
              location.href = `<?= $basekepsek; ?>status_not_approved`;
            }, 1500);

          }

        }

      });

    });

    $("#hightlight_save_not_approve").click(function() {

      $.ajax({
        url  : `<?= $basekepsek; ?>data`,
        type : "POST",
        data : {
          daily_id_not_appr   : $("#highlight_id_daily_waiiting").val(),
          is_reason           : $("#hightlight_reason").val(),
          nipguru             : $("#highlight_nip_daily_waiiting").val()
        },
        success:function(data) {

          Swal.fire({
            title : "Not Approved",
            icon  : "success",
            timer : 1000
          });

          $("#hightlight_not_approve").hide();
          $("#hightlight_approve").hide();
          $("#hightlight_close_approve_wait").click();

          setTimeout(function(){
            location.href = `<?= $basekepsek; ?>`;
          }, 1500);

        }

      });

    });

    $("#inpage_save_notappr_wt_appr").click(function(){
      $.ajax({
        url  : `<?= $basekepsek; ?>data`,
        type : "POST",
        data : {
          daily_id_not_appr  : $("#inpage_id_daily_waiiting_wt_appr").val(),
          is_reason          : $("#inpage_reason").val(),
          nipguru            : $("#inpage_nip_daily_waiiting_wt_appr").val()
        },
        success:function(data) {

          Swal.fire({
            title : "Not Approved",
            icon  : "success",
            timer : 1000
          });

          $("#inpage_cancel_not_approve_wt_appr").hide();
          $("#inpage_save_notappr_wt_appr").hide();
          $("#inpage_close_wt_appr").click();

          setTimeout(function(){
            location.href = `<?= $basekepsek; ?>status_waiting_approval`;
          }, 1500);

        }

      });
    });
    
    $("#cancel_not_approve").click(function() {
      $("#save_not_approve").hide();
      $(".reason").hide();
      $("#cancel_not_approve").hide();
      $("#approve").show();
      $("#not_approve").show();
    });

    $("#hightlight_cancel_not_approve").click(function() {

      $("#hightlight_save_not_approve").hide();
      $(".hightlight_reason").hide();
      $("#hightlight_cancel_not_approve").hide();
      $("#hightlight_approve").show();
      $("#hightlight_not_approve").show();

    });

    $("#inpage_cancel_not_approve_wt_appr").click(function() {

      $("#inpage_save_notappr_wt_appr").hide();
      $(".inpage_reason").hide();
      $("#inpage_cancel_not_approve_wt_appr").hide();
      $("#inpage_approve_wt_appr").show();
      $("#inpage_not_approve_wt_appr").show();

    })

    $("#hightlight_approve").click(function(e){
      e.preventDefault();

      $.ajax({
        url  : `<?= $basekepsek; ?>data`,
        type : "POST",
        data : {
          daily_id  : $("#highlight_id_daily_waiiting").val(),
          nip_guru  : $("#highlight_nip_daily_waiiting").val()
        },
        success:function(data) {

          console.log(JSON.parse(data).status_approve);
          let validNumberPhone = JSON.parse(data).number_phone_teacher;
          let createdByTeacher = JSON.parse(data).created_by_teacher;

          if (validNumberPhone == true) {

            Swal.fire({
              title : "Approve Daily",
              icon  : "success",
              timer : 1000
            });

            $("#hightlight_not_approve").hide();
            $("#hightlight_approve").hide();
            $("#hightlight_close_approve_wait").click();

            setTimeout(function(){
              location.href = `<?= $basekepsek; ?>`;
            }, 1500);

          } else if (validNumberPhone == false) {

            Swal.fire({
              title : `Invalid Number Phone Teacher ${createdByTeacher}`,
              icon  : "warning",
              timer : 1500
            });

            setTimeout(function(){
              location.href = `<?= $basekepsek; ?>`;
            }, 1500);

          }

        }

      })
    });

    $("#hightlight_not_approve").click(function(e) {
      e.preventDefault();

      $("#hightlight_approve").hide();
      $("#hightlight_not_approve").hide();
      $("#hightlight_cancel_not_approve").show();
      $("#hightlight_save_not_approve").show();
      $(".hightlight_reason").show();
      $("#hightlight_reason").focus();
      $('#modal-hightlight-wt-appr').scrollTop($('#modal-hightlight-wt-appr')[0].scrollHeight);

    });

    $("#inpage_approve_wt_appr").click(function(e){

      e.preventDefault();
      $.ajax({
        url  : `<?= $basekepsek; ?>data`,
        type : "POST",
        data : {
          daily_id  : $("#inpage_id_daily_waiiting_wt_appr").val(),
          nip_guru  : $("#inpage_nip_daily_waiiting_wt_appr").val()
        },
        success:function(data) {

          console.log(JSON.parse(data).status_approve);

          Swal.fire({
            title : "Approve Daily",
            icon  : "success",
            timer : 1000
          });

          $("#inpage_not_approve_wt_appr").hide();
          $("#inpage_approve_wt_appr").hide();
          $("#inpage_close_wt_appr").click();

          setTimeout(function(){
            location.href = `<?= $basekepsek; ?>status_waiting_approval`;
          }, 1500);

        }

      })

    });

    $("#inpage_not_approve_wt_appr").click(function(e){
      e.preventDefault();
      $("#inpage_approve_wt_appr").hide();
      $("#inpage_not_approve_wt_appr").hide();
      $("#inpage_cancel_not_approve_wt_appr").show();
      $("#inpage_save_notappr_wt_appr").show();
      $(".inpage_reason").show();
      $("#inpage_reason").focus();
      $('#inpage-wt-appr').scrollTop($('#inpage-wt-appr')[0].scrollHeight);
    });

    const loadData = () => {

      setInterval(function(){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {

            let jumlahWtAppr  = JSON.parse(this.responseText).notif_not_yet_appr;
            let jumlahAppr    = JSON.parse(this.responseText).notif_appr;
            let jumlahNtAppr  = JSON.parse(this.responseText).notif_not_appr;

            $("#viewAllStatWait").text(JSON.parse(this.responseText).notif_not_yet_appr);
            $("#viewAllStatAppr").text(JSON.parse(this.responseText).notif_appr);
            $("#viewAllStatNotAppr").text(JSON.parse(this.responseText).notif_not_appr);

            if (jumlahAppr == 0) {

              $("#notifApproveList").hide();
              $("#pesanSdhApprove").html(`Belum ada daily yang di Approved`);
              $("#pesanSdhApprove").css("background-color", "whitesmoke");
              $(".apprlist").hide();

            } else if (jumlahAppr != 0) {

              $("#notifApproveList").show();
              $("#notifApproveList").text(JSON.parse(this.responseText).notif_appr);
              // $("#untukNotifApprKepsek").append(JSON.parse(this.responseText).elementNotifAppr);
              $("#pesanSdhApprove").html(`<span style="font-weight: bold;"> ${JSON.parse(this.responseText).notif_appr} </span> Daily Sudah di Approve`);
              $("#pesanSdhApprove").css("background-color", "lightyellow");
              $("#isi_pesan_sdh_approve").html(JSON.parse(this.responseText).isi_notif_approved);

            }

            if (jumlahNtAppr == 0) {

              $("#notifNotApproveList").hide();
              $("#pesanTdkDiApprove").html(`Belum ada daily yang Tidak Disetujui`);
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
            
            if(jumlahWtAppr == 0) {

              $("#pesanBelumApprove").html(`Belum ada daily yang di buat oleh guru`);
              $("#pesanBelumApprove").css({
                "background-color" : "whitesmoke",
                "color"            : "black"
              });
              $("#isi_pesan_belum_approve").hide();
              $("#notifWaitingList").hide();

            } else if(jumlahWtAppr != 0) {

              $("#notifWaitingList").show();
              $("#isi_pesan_belum_approve").show();
              $("#notifWaitingList").html(JSON.parse(this.responseText).notif_not_yet_appr);
              $("#pesanBelumApprove").html(`Ada <span style="font-weight: bold;">${JSON.parse(this.responseText).notif_not_yet_appr}</span> Daily Menunggu Persetujuan Untuk Di Posting`);
              $("#isi_pesan_belum_approve").html(JSON.parse(this.responseText).isi_notif_not_yet_appr);

            }

            $(".wtlist").click(function(e){
              e.preventDefault();

              let dataDailyId     = $(this).data('daily_id');
              let dataDailyNIP    = $(this).data('nip_guru');
              let dataSender      = $(this).data('pengirim');
              let dataSiswa       = $(this).data('siswa_blmappr');
              let dataTglUpload   = $(this).data('tgl_upload');
              let dataImage       = $(this).data('img');
              let dataJudul       = $(this).data('judul');
              let dataDaily       = $(this).data('isian');
              let grouporstd      = $(this).data('group_or_std'); 

              let image         = document.querySelector("img[id='foto_upload']");

              if (grouporstd == 'group') {
                $("#lbl_std_or_gp").text('GROUP');
              } else {
                $("#lbl_std_or_gp").text('STUDENT');
              }

              $("#modal-default").modal('show');
              $("#save_not_approve").hide();
              $(".reason").hide();
              $("#cancel_not_approve").hide();
              $("#pengirim").val(dataSender);
              $("#tanggal_upload").val(dataTglUpload);
              $("#title_daily").val(dataJudul);
              $("#siswa_daily").val(dataSiswa);
              $("#id_daily_waiiting").val(dataDailyId);
              $("#nip_daily_waiiting").val(dataDailyNIP);
              image.setAttribute("src", `../image_uploads/${dataImage}`);
              $("#main_daily").html(dataDaily)

              let buttonNotApprove    = document.querySelector("#not_approve")
              let elementModalContent = document.querySelector(".modal-content")

              document.addEventListener('click', function(e) {
                if ( !buttonNotApprove.contains(e.target) && !forms.contains(e.target) && !elementModalContent.contains(e.target) ) {

                  $(".reason").hide()
                  $("#cancel_not_approve").hide()
                  $("#save_not_approve").hide()
                  $("#not_approve").show()
                  $("#approve").show()
                  $("#reason").val("")
                
                } 
              })

              // $.ajax({
              //   url   : `<?= $basekepsek; ?>status_approved`,
              //   type  : 'POST',
              //   data  : {
              //     pengirim : sender,
              //     judul    : judul
              //   },
              //   success:function(data) {
              //     $("#isi_konten").html(data)
              //   }
              // })
            });

            $(".apprlist").click(function(e) {

              e.preventDefault();
              $("#modal-default-appr").modal('show');

              let dataDailyId       = $(this).data('daily_id');
              let dataSender        = $(this).data('pengirim');
              let dataNipGuru       = $(this).data('nip_guru');
              let dataGuru          = $(this).data('nama_guru_lengkap');
              let dataNisSiswa      = $(this).data('nis_siswa_was_appr');
              let dataSiswaOrGroup  = $(this).data('siswaorgroup_was_appr');
              let dataTglAppr       = $(this).data('tgl_approved');
              let dataTglUploadAppr = $(this).data('tgl_upload');
              let dataImageAprr     = $(this).data('img');
              let dataTitleAppr     = $(this).data('judul');
              let dataIsiAppr       = $(this).data('isian');
              let dataDfRoomKey     = $(this).data('room_key');
              let dataDfTglOri      = $(this).data('tgl_ori');
              let grouporstd        = $(this).data('group_or_std'); 

              let imageAppr         = document.querySelector("img[id='foto_upload_appr']"); 

              if (`<?= $thisPage; ?>` == `<?= $basekepsek; ?>lookactivity/${dataDfRoomKey}`) {

                $('#formDfAppr').attr('action', `${dataDfRoomKey}`);

              } else if (`<?= $thisPage; ?>` == `<?= $basekepsek; ?>querydailystudent`) {

                $('#formDfAppr').attr('action', `lookactivity/${dataDfRoomKey}`);

              } else if (`<?= $thisPage; ?>` == `<?= $basekepsek; ?>teachercreatedaily`) {

                $('#formDfAppr').attr('action', `lookactivity/${dataDfRoomKey}`);

              } else if (`<?= $thisPage; ?>` == `<?= $basekepsek; ?>teachercreategroupdaily`) {

                $('#formDfAppr').attr('action', `lookactivity/${dataDfRoomKey}`);

              } else if (`<?= $thisPage; ?>` == `<?= $basekepsek; ?>querydailyteacher`) {

                $('#formDfAppr').attr('action', `lookactivity/${dataDfRoomKey}`);

              } else if (`<?= $thisPage; ?>` == `<?= $basekepsek; ?>createdailybyteacher`) {

                $('#formDfAppr').attr('action', `lookactivity/${dataDfRoomKey}`);

              } else if (`<?= $thisPage; ?>` == `<?= $basekepsek; ?>status_waiting_approval`) {

                $('#formDfAppr').attr('action', `lookactivity/${dataDfRoomKey}`);

              } else if (`<?= $thisPage; ?>` == `<?= $basekepsek; ?>status_approved`) {

                $('#formDfAppr').attr('action', `lookactivity/${dataDfRoomKey}`);

              } else if (`<?= $thisPage; ?>` == `<?= $basekepsek; ?>status_not_approved`) {

                $('#formDfAppr').attr('action', `lookactivity/${dataDfRoomKey}`);

              } else if (`<?= $thisPage; ?>` == `<?= $basekepsek; ?>listgroup`) {

                $('#formDfAppr').attr('action', `lookactivity/${dataDfRoomKey}`);

              } else if (`<?= $thisPage; ?>` == `<?= $basekepsek; ?>groupclass/${dataSiswaOrGroup}`) {

                $('#formDfAppr').attr('action', `<?= $basekepsek; ?>lookactivity/${dataDfRoomKey}`);

              } else if (`<?= $thisPage; ?>` == `<?= $basekepsek; ?>changepassword`) {

                $('#formDfAppr').attr('action', `lookactivity/${dataDfRoomKey}`);

              } else if (`<?= $thisPage; ?>` == `<?= $basekepsek; ?>changenumberphone`) {

                $('#formDfAppr').attr('action', `lookactivity/${dataDfRoomKey}`);

              } else if (`<?= $thisPage; ?>` != `<?= $basekepsek; ?>`) {
                
                $('#formDfAppr').attr('action', `<?= $basekepsek; ?>lookactivity/${dataDfRoomKey}`);

              } else {

                $('#formDfAppr').attr('action', `lookactivity/${dataDfRoomKey}`);

              }

              if (grouporstd == 'group') {
                $("#lbl_std_or_gp_df_appr").text('GROUP');
                $("#df_lookdaily_appr").attr('name', 'daily_group');
                $("#df_roomkey_lookdaily").attr('name', 'roomkey_group_lookdaily');
              } else {
                $("#lbl_std_or_gp_df_appr").text('STUDENT');
                $("#df_lookdaily_appr").attr('name', 'redirectLookDaily');
                $("#df_roomkey_lookdaily").attr('name', 'roomkey_lookdaily');
              }

              $("#date_approved").val(dataTglAppr);
              $("#pengirim_appr").val(dataSender);
              $("#tanggal_upload_appr").val(dataTglUploadAppr);
              $("#siswa_daily_appr").val(dataSiswaOrGroup);
              $("#df_lookdaily_appr").val(dataDailyId);
              $("#df_nama_guru_lookdaily").val(dataGuru);
              $("#df_nis_or_idgroup_lookdaily").val(dataNisSiswa);
              $("#df_nama_siswa_lookdaily").val(dataSiswaOrGroup);
              $("#df_foto_upload_lookdaily").val(dataImageAprr);
              $("#df_tgl_posting").val(dataTglAppr);
              $("#df_jdl_posting_lookdaily").val(dataTitleAppr);
              $("#df_isi_posting_lookdaily").val(dataIsiAppr);
              $("#df_roomkey_lookdaily").val(dataDfRoomKey);
              $("#df_tglori_posting_lookdaily").val(dataDfTglOri);
              $("#df_nip_guru_lookdaily").val(dataNipGuru);

              imageAppr.setAttribute("src", `../image_uploads/${dataImageAprr}`);

              $("#title_daily_appr").val(dataTitleAppr);
              $("#main_daily_appr").html(dataIsiAppr);

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
                $("#lbl_std_or_gp_df_notappr").text('GROUP');
              } else {
                $("#lbl_std_or_gp_df_notappr").text('STUDENT');
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

            })

          }
        };

        xhttp.open("GET", `<?= $basekepsek; ?>data`, true);
        xhttp.send();
      }, 1000);

    }

    loadData()

    let timeIsOut = `<?= $timeIsOut; ?>`

    if (timeIsOut == 1) {

      const myTimeout = setTimeout(showPopUp, 1000);

    }

    function showPopUp() {
      Swal.fire({
        title: 'TIME IS OUT',
        icon: "warning"
      });

      setTimeout(clearSession, 1200);
      
    }

    function clearSession() {
      localStorage.removeItem("showpopup");
      $.ajax({
        url : `<?= $basekepsek; ?>kepsek`,
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

  })

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

    $('#list_siswa').DataTable();

    $("#listgroup_kp").DataTable();

    $('#list_status').DataTable({
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

    $("#hightlight_list_siswa").DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });

    $("#list_group").DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });

    $("#list_tc").DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });

    $("#hightlight_list_anggota").DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
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
