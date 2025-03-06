<?php 
  
  date_default_timezone_set('Asia/Jakarta');
  $date=date('Y-m-d'); 
  
  require '../php/config.php'; 
  require '../php/function.php'; 
  
  // Cek status login user jika tidak ada session
  if (!$user->isLoggedInOTM()) { 

    header("location:../"); //Redirect ke halaman login  

  }
  
  $nama_role = $_SESSION['c_otm_paud'];

  $nama_user = $_SESSION['username_otm_paud'];
  $usrnm     = explode(" ", $nama_user);
  // echo $usrnm[0];exit;

  // echo $nama_role;exit;

  $thisPage =  (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

  $currTahun    = "";
  $currSemester = "";

  $createDaily  = 0; 

  $check = mysqli_num_rows(mysqli_query($con, "SELECT * FROM tahun_ajaran WHERE status = 'aktif' AND c_role = '$_SESSION[c_otm_paud]' "));

  if ($check != 0) {

    $currTahun = mysqli_query($con, "SELECT tahun FROM tahun_ajaran WHERE status = 'aktif' AND c_role = '$_SESSION[c_otm_paud]' ");
    $currTahun = mysqli_fetch_assoc($currTahun)['tahun'];
    // echo $currTahun;exit;
    $currSemester = mysqli_query($con, "SELECT semester FROM tahun_ajaran WHERE status = 'aktif' AND c_role = '$_SESSION[c_otm_paud]' ");
    $currSemester = mysqli_fetch_assoc($currSemester)['semester'];

  } else {

    $currTahun    = "";
    $currSemester = "";

  }

  $na = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM guru where c_guru = '$_SESSION[c_otm_paud]' ")); 

  if (isset($_POST['sg_out'])) {
    session_destroy();
    session_unset($_SESSION['c_otm_paud']);
    header("location:../login");
  }

  $kelas_siswa    = $_SESSION['bag_siswa_paud'];

  $siswa_sd       = "/SD/i";

  $foundDataSD    = preg_match($siswa_sd, $kelas_siswa);

  $nipkepsek      = "";

  if ($foundDataSD == 1) {
    $nipkepsek = "2019032";
  } else {
    $nipkepsek = "2019034";
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

    #tr_dashboard {
      cursor: pointer;
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

    #view_all:hover {
      background-color: #ddd;
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
      font-size: 10px !important;
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

    #jenis_pembayaran {
      width: 215px;
    }

    #tanggal_upload_appr,
    #hg_tanggal_upload_appr {
      width: 213px;
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

    .center {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      padding: 10px;
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

      .center h4 {
        font-size: 10px;
      }

      #boxTitle {
        font-size: 12px;
      }

      #addIcon {
        top: 2px;
      }

      #namaguru {
        font-size: 12px;
        margin-top: 4px;
      }

      #namakepsek,
      #namaguruchat,
      #kepsekchat {
        font-size: 10px;
      }

      #namakepsek {
        margin-top: 1px
      }

      #tglpublish,
      #namasiswa,
      #datesend {
        font-size: 9px;
      }

      #dotTo {
        margin-left: 23px;
      }

      #jenis_pembayaran {
        width: 100%;
      }

      #swal2-title {
        font-size: 11px;
      }

      #tanggal_upload_appr,
      #hg_tanggal_upload_appr {
        width: 100%;
      }

      .swal2-show {
        width: 50% !important;
        font-size: 10px !important;
        font-weight: bold;
      }

      #judul_daily {
        font-size: 15px;
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
                    <label> FROM </label>
                    <input type="text" readonly class="form-control" name="pengirim_appr" id="pengirim_appr">
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
                <label for="siswa_daily_appr" id="lbl_std_or_group_df_appr">STUDENT</label>
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
          <input type="hidden" id="df_nip_guru_lookdaily" name="nipguru_lookdaily">
          <input type="hidden" id="df_nip_kepsek_lookdaily" name="nipkepsek_lookdaily">
          <input type="hidden" id="df_nama_guru_lookdaily" name="guru_lookdaily">
          <input type="hidden" id="df_nis_siswa_lookdaily" name="nis_or_idgroup_lookdaily">
          <input type="hidden" id="df_nama_siswa_lookdaily" name="nama_siswa_or_groupkelas_lookdaily">
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

<div class="modal fade" tabindex="-1" role="dialog" id="modal-all-daily" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom-color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <center>
            <h4 class="modal-title" id="exampleModalScrollableTitle"> <strong> DAILY ACTIVITY </strong> </h4>
          </center>
      </div>
      <div class="modal-body" style="margin-bottom: 10px; overflow-y: scroll; max-height: 600px;">

          <div class="box-body" style="padding-left: 60px; padding-right: 60px;">

            <form role="form" id="forms">

              <div class="row">

                <div class="col-sm-6">
                  <div class="form-group">
                    <label> FROM </label>
                    <input type="text" readonly class="form-control" name="pengirim_appr" id="all_pengirim_appr">
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="form-group">
                    <label> DATE POSTED </label>
                    <input type="text" readonly id="all_tanggal_upload_appr" name="tanggal_upload_appr" class="form-control">
                  </div>
                </div>

              </div>

              <div class="form-group">
                <label for="siswa_daily_appr" id="all_lbl_std_or_group_df_appr">STUDENT</label>
                <input type="text" id="all_siswa_daily_appr" name="siswa_daily_appr" readonly class="form-control">
              </div>

              <div class="form-group gambar_banner_appr">
                <label for="banner"> UPLOADED PHOTO </label>
                <!-- <input type="text" id="banner" name=""> -->
                <img class="img-responsive pad" id="all_foto_upload_appr" alt="Photo">
              </div>

              <div class="form-group">
                <label for="title_daily_appr">TITLE DAILY ACTIVITY</label>
                <input type="text" id="all_title_daily_appr" name="title_daily_appr" readonly class="form-control">
              </div>

              <div class="form-group">
                <label for="main_daily_appr">DESCRIPTION DAILY ACTIVITY</label>
                <div class="isiDailyAppr" id="all_main_daily_appr" style="height: 150px;border: 1px solid #eee;padding: 10px; background-color: #eee;" class="form-control">
                  
                </div>
                <!-- <textarea style="height: 150px;" class="form-control" id="main_daily" rows="3" placeholder="Announcement ..."></textarea> -->
              </div>

            </form>

          </div>

      </div>
      <div class="modal-footer">
        <button type="button" id="all_close_approve" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <form id="formDfAllDaily" method="post">
          <input type="hidden" name="frompage_lookdaily" value="homepage">
          <input type="hidden" name="roomkey_group_lookdaily" id="mdl_roomkey_group_lookdaily">
          <input type="hidden" name="nipguru_lookdaily" id="mdl_nipguru_lookdaily">
          <input type="hidden" name="nipkepsek_lookdaily" id="mdl_nipkepsek_lookdaily">
          <input type="hidden" name="guru_lookdaily" id="mdl_guru_lookdaily">
          <input type="hidden" name="nis_or_idgroup_lookdaily" id="mdl_nis_or_idgroup_lookdaily">
          <input type="hidden" name="nama_siswa_or_groupkelas_lookdaily" id="mdl_nama_siswa_or_groupkelas_lookdaily">
          <input type="hidden" name="foto_upload_lookdaily" id="mdl_foto_upload_lookdaily">
          <input type="hidden" name="tgl_posting_lookdaily" id="mdl_tgl_posting_lookdaily">
          <input type="hidden" name="tglori_posting_lookdaily" id="mdl_tglori_posting_lookdaily">
          <input type="hidden" name="jdl_posting_lookdaily" id="mdl_jdl_posting_lookdaily">
          <input type="hidden" name="isi_posting_lookdaily" id="mdl_isi_posting_lookdaily">
          <button class="btn btn-sm btn-primary" id="mdl_all_daily" type="submit" name="daily_group"> <i class="glyphicon glyphicon-eye-open"></i> DAILY </button>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-default-all-status-approved">
  <div class="modal-dialog">
    <div class="modal-content" id="modal-content-semua-status">
      <div class="modal-header" style="border-bottom-color: white;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <center>
            <h4 class="modal-title"> <strong> All Daily Activity </strong> </h4>
          </center>
      </div>
      <div class="modal-body" id="modal-body-semua-status" style="overflow-y: scroll; max-height: 400px;">
        <!-- <ul class="menu" id="isi_pengumumans">
        </ul> -->
        <section class="content" id="content_all_body_status" style="cursor: pointer; padding: 10px;">

        </section>

      </div>
      <div class="modal-footer">
        <button type="button" id="close_approve_status" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-hg-appr">
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
                    <label> FROM </label>
                    <input type="text" readonly class="form-control" name="hg_pengirim_appr" id="hg_pengirim_appr">
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
                <label for="siswa_daily_appr" id="lbl_std_or_group">STUDENT</label>
                <input type="text" id="hg_siswa_daily_appr" name="hg_siswa_daily_appr" readonly class="form-control">
              </div>

              <div class="form-group gambar_banner_appr">
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
        <button type="button" id="close_approve" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <form id="formHgAppr" method="post">
          <input type="hidden" id="hg_frompage_lookdaily" name="frompage_lookdaily" value="homepage">
          <input type="hidden" id="hg_roomkey_lookdaily" name="roomkey_lookdaily">
          <input type="hidden" id="hg_nip_guru_lookdaily" name="nipguru_lookdaily">
          <input type="hidden" id="hg_nip_kepsek_lookdaily" name="nipkepsek_lookdaily">
          <input type="hidden" id="hg_nama_guru_lookdaily" name="guru_lookdaily">
          <input type="hidden" id="hg_nis_siswa_lookdaily" name="nis_or_idgroup_lookdaily">
          <input type="hidden" id="hg_nama_siswa_lookdaily" name="nama_siswa_or_groupkelas_lookdaily">
          <input type="hidden" id="hg_foto_upload_lookdaily" name="foto_upload_lookdaily">
          <input type="hidden" id="hg_tgl_posting" name="tgl_posting_lookdaily">
          <input type="hidden" id="hg_tglori_posting_lookdaily" name="tglori_posting_lookdaily">
          <input type="hidden" id="hg_jdl_posting_lookdaily" name="jdl_posting_lookdaily">
          <input type="hidden" id="hg_isi_posting_lookdaily" name="isi_posting_lookdaily">
          <button type="submit" name="redirectLookDaily" id="df_lookdaily_hgappr" class="btn btn-primary"> <i class="glyphicon glyphicon-eye-open"></i> Lookdaily </button>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

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
                <label> SISWA </label>
                <input type="text" readonly id="nm_siswa" name="nm_siswa" class="form-control">
              </div>

              <div class="form-group">
                <label> TANGGAL POSTING </label>
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

          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="glyphicon glyphicon-envelope" style="margin-top: 1px;"></i>
              <span class="label label-warning" id="notifInfoList"></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header" id="pesanInfo" style="text-align: center;">  </li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" id="isi_pesan_info">
                  
                </ul>
              </li>
              <!-- <li class="footer"><a href="listinfo" id="v_all"> Lihat Semua (<strong id="viewAllInfo"></strong>) Pemberitahuan </a></li> -->
            </ul>
          </li>

          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" id="untukNotifAppr" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning" id="notifApproveList">  </span>
            </a>
            <ul class="dropdown-menu">
              <li class="header msgAppr"> <center id="pesanSdhApprove">  </center> </li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu" id="isi_pesan_sudah_approve">
                  
                </ul>
              </li>
              <!-- <li class="footer"><a href="<?= $basewam; ?>querydailystudent" id="view_all"> View all (<strong id="viewAllStatAppr"></strong>) Daily Activity </a> </li> -->
              <li class="footer"><a href="javascript:void(0);" id="view_all"> View all (<strong id="viewAllStatAppr"></strong>) Daily Activity </a> </li>
            </ul>
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
          

          <li class="dropdown user user-menu">

            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo $base; ?>imgstatis/df.jpg" class="user-image" alt="User Image">
                <span class="hidden-xs"> <?= $_SESSION['username_otm_paud'] ?> </span>
              </a>

            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header" style="height: 185px;">
                  <img style="margin-bottom: 10px;" src="<?php echo $base; ?>imgstatis/df.jpg" class="img-circle" alt="User Image">
                <p style="margin-top: -9px; font-size: 13px;">
                  <?php echo ucfirst($_SESSION['username_otm_paud']); ?>
                </p>
                <p style="font-size: 12px; margin-top: -5px;">

                  <?php

                    $siswa_sd      = "/SD/i";

                    $foundDataSD    = preg_match($siswa_sd, $_SESSION['kls_siswa_paud']);

                    if ($foundDataSD == 1) {
                      $kelas = str_replace(["SD"], " SD", $_SESSION['kls_siswa_paud']);
                    } else {
                      $kelas = $_SESSION['kls_siswa_paud'];
                    }

                  ?>

                  KELAS <?= $kelas; ?>

                </p>
                <p style="font-size: 11px; margin-top: -3px;"><?php echo $aplikasi['namasek']; ?></p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <button type="submit" id="change_password" name="change_pass" class="btn btn-default btn-flat"> <i class="glyphicon glyphicon-pencil"></i> Ganti Password </button>
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
            <img src="<?php echo $base; ?>imgstatis/df.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p> <?= ucfirst($usrnm[0]); ?> </p>
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
          <a href="<?= $basewam; ?>" id="dashboard"><i style="color: white;" class="glyphicon glyphicon-th-large text-primary"></i> <span id=""> DASHBOARD </span> </a>
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
                  <a href="<?= $basewam; ?>querydailystudent" id="query_data_siswa"><i class="fa fa-user text-primary" style="margin-left: 3px;"></i> <span style="margin-left: 3px;"> </span> Student </a>
                </li>
                <li> 
                  <a href="<?= $basewam; ?>querydailygroup" id="query_data_group"><i class="fa fa-users text-primary"></i> <span style="margin-left: 6px;" id="sub_isiList2group"> Group Class </span> </a>
                </li>
              </ul>
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
              <a href="<?= $basewam; ?>changepassword" id="changepassword"><i class="glyphicon glyphicon-wrench text-primary"></i> <span id="isiList4"> Change Password </span> </a>
            </li>

            <li>
              <a href="<?= $basewam; ?>changenumberphone" id="changenumberphone"><i class="glyphicon glyphicon-phone text-primary"></i> <span id="isiList4"> Change Number Phone </span> </a>
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
    elseif($act == 'createdaily') {
      $createDaily = 1;
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
    }

    #region form change password
    elseif ($act == 'changepassword') {
      require 'view/maintenance/changepassword/index.php';
    } 

    elseif ($act == 'changenumberphone') {
      require 'view/maintenance/changenumberphone/index.php';
    }

    #region form maintenance
    elseif ($act == 'tahunajaran') {
      require 'view/maintenance/tahun_ajaran/index.php';
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

  $(document).ready(function(){

    let timeIsOut = `<?= $timeIsOut; ?>`

    if (timeIsOut == 1) {

      const myTimeout = setTimeout(showPopUp, 1000);

    }

    $("#change_password").click(function(){
      location.href = `<?= $basewam; ?>changepassword`
    });

    function clickSubMenu() {
      $("#isiList2").click();
      $("#query_data_siswa").css({
          "background-color" : "#ccc",
          "color" : "black"
      });
    }

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
      document.location.href = `<?= $base; ?>`
    }

    let role              = `<?= $nama_role; ?>`
    // alert(role)
    let showNotif         = document.getElementById('notifMasuk')

    $("#view_all").click(function(e) {
      e.preventDefault();
      $("#modal-default-all-status-approved").modal("show");

    });

    $("#sg_out").click(function(){
      $.ajax({
        url : `<?= $basewam; ?>a-control/<?= md5('logout_act1_parents_paud'); ?>/access`,
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

    const loadData = () => {

      setInterval(function(){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {

            let jumlahAppr        = JSON.parse(this.responseText).notif_appr;
            $("#viewAllStatAppr").text(JSON.parse(this.responseText).notif_appr_all);

            if (jumlahAppr == 0) {

              $("#notifApproveList").hide();
              $("#pesanSdhApprove").html(`Belum Ada Daily Yang Di Buat Hari Ini`);
              $(".msgAppr").css("background-color", "whitesmoke");
              $("#isi_pesan_sudah_approve").hide();
              $("#content_all_body_status").html(JSON.parse(this.responseText).all_daily)

            } else if (jumlahAppr != 0) {

              $("#notifApproveList").show();
              $("#isi_pesan_sudah_approve").show();
              $("#notifApproveList").text(JSON.parse(this.responseText).notif_appr);
              $("#pesanSdhApprove").html(`Ada <span style="font-weight: bold;"> ${JSON.parse(this.responseText).notif_appr} </span> Daily Hari Ini`);
              $(".msgAppr").css("background-color", "lightyellow");
              $("#isi_pesan_sudah_approve").html(JSON.parse(this.responseText).isi_notif_approved);
              $("#content_all_body_status").html(JSON.parse(this.responseText).all_daily)

            }

            $(".apprlist").click(function(e) {

              e.preventDefault();
              $("#modal-default-appr").modal('show');

              let dataDailyId       = $(this).data('daily_id');
              let dataSender        = $(this).data('pengirim');
              let dataGuru          = $(this).data('nama_guru_lengkap');
              let dataUsernameGuru  = $(this).data('username_guru');
              let dataNisSiswa      = $(this).data('nis_siswa_was_appr');
              let dataSiswa         = $(this).data('siswa_was_appr');
              let dataTglAppr       = $(this).data('tgl_approved');
              let dataTglUploadAppr = $(this).data('tgl_upload');
              let dataImageAprr     = $(this).data('img');
              let dataTitleAppr     = $(this).data('judul');
              let dataIsiAppr       = $(this).data('isian');
              let dataRoomKey       = $(this).data('room_key');
              let dataDfTglOri      = $(this).data('tgl_ori');
              let dataNipGuru       = $(this).data('nip_guru');
              let dataStdOrGroup    = $(this).data('group_or_std');

              // if (`<?= $thisPage; ?>` == `<?= $basewam; ?>lookactivity/${dataRoomKey}`) {

              //   $('#formDfAppr').attr('action', `${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` == `<?= $basewam; ?>createdailystudent`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` == `<?= $basewam; ?>createdailygroup`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` == `<?= $basewam; ?>querydailystudent`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);
              //   $('#df_frompage_lookdaily').val("querydailystudent");

              // } else if (`<?= $thisPage; ?>` == `<?= $basewam; ?>querydailygroup`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);
              //   $('#df_frompage_lookdaily').val("querydailygroup");

              // } else if (`<?= $thisPage; ?>` == `<?= $basewam; ?>status_waiting_approval`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` == `<?= $basewam; ?>status_approved`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` == `<?= $basewam; ?>status_not_approved`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` == `<?= $basewam; ?>changepassword`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` == `<?= $basewam; ?>changenumberphone`) {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // } else if (`<?= $thisPage; ?>` != `<?= $basewam; ?>`) {

              //   $('#formDfAppr').attr('action', `${dataRoomKey}`);

              // } else {

              //   $('#formDfAppr').attr('action', `lookactivity/${dataRoomKey}`);

              // }

              if (`<?= $thisPage; ?>` == `<?= $basewam; ?>lookactivity/${dataRoomKey}`) {

                $('#formDfAppr').attr('action', `${dataRoomKey}`);

              } else {

                $('#formDfAppr').attr('action', `<?= $basewam; ?>lookactivity/${dataRoomKey}`);

              }

              if (dataStdOrGroup == "group") {
                $("#lbl_std_or_group_df_appr").text("GROUP");
                $("#df_lookdaily_appr").attr('name', 'daily_group');
                $("#df_roomkey_lookdaily").attr('name', 'roomkey_group_lookdaily');
              } else if (dataStdOrGroup == "std") {
                $("#lbl_std_or_group_df_appr").text("STUDENT");
                $("#df_lookdaily_appr").attr('name', 'redirectLookDaily');
                $("#df_roomkey_lookdaily").attr('name', 'roomkey_lookdaily');
              }

              let imageAppr         = document.querySelector("img[id='foto_upload_appr']");

              $("#date_approved").val(dataTglAppr);
              $("#pengirim_appr").val(dataUsernameGuru);
              $("#siswa_daily_appr").val(dataSiswa);
              $("#tanggal_upload_appr").val(dataTglUploadAppr);

              imageAppr.setAttribute("src", `../image_uploads/${dataImageAprr}`);

              $("#title_daily_appr").val(dataTitleAppr);
              $("#main_daily_appr").html(dataIsiAppr);
              $("#df_nis_siswa_lookdaily").val(dataNisSiswa);
              $("#df_nama_siswa_lookdaily").val(dataSiswa);
              $("#df_nama_guru_lookdaily").val(dataGuru);
              $("#df_foto_upload_lookdaily").val(dataImageAprr);
              $("#df_tgl_posting").val(dataTglAppr);
              $("#df_jdl_posting_lookdaily").val(dataTitleAppr);
              $("#df_isi_posting_lookdaily").val(dataIsiAppr);
              $("#df_roomkey_lookdaily").val(dataRoomKey);
              $("#df_tglori_posting_lookdaily").val(dataDfTglOri);
              $("#df_nip_guru_lookdaily").val(dataNipGuru);
              $("#df_nip_kepsek_lookdaily").val(`<?= $nipkepsek; ?>`);

            });

            $(".detail_status").click(function(e) {

              let room_key            = $(this).data('roomkey');
              let allStdOrGroup       = $(this).data('group_orstd');
              let allpengirim         = $(this).data('from');
              let allTglPosted        = $(this).data('tgl_posted');
              let allTimePosted       = $(this).data('time_posted');
              let allNamaSiswaOrGroup = $(this).data('namasiswa_or_group');
              let allImageUpload      = $(this).data('foto_upload');
              let allTitleDaily       = $(this).data('title_daily');
              let allDescriptionDaily = $(this).data('isi_daily');
              let allNipDaily         = $(this).data('nipguru');
              let allNamaGuru         = $(this).data('namaguru');
              let allNisOrIdGroup     = $(this).data('nisoridgroup');
              let allDateOri          = $(this).data('tgl_ori');

              $('#formDfAllDaily').attr('action', `lookactivity/${room_key}`);
              let allImageAppr         = document.querySelector("img[id='all_foto_upload_appr']");

              allImageAppr.setAttribute("src", `../image_uploads/${allImageUpload}`);
              $("#modal-all-daily").modal("show");
              $("#modal-default-all-status-approved").modal("hide");

              if (allStdOrGroup == "group") {

                $("#all_lbl_std_or_group_df_appr").text("GROUP");
                $("#mdl_roomkey_group_lookdaily").attr('name', 'roomkey_group_lookdaily');
                $("#mdl_roomkey_group_lookdaily").val(room_key);
                $("#all_pengirim_appr").val(allpengirim);
                $("#all_tanggal_upload_appr").val(allTglPosted + ' ' + allTimePosted);
                $("#all_siswa_daily_appr").val(allNamaSiswaOrGroup);
                $("#mdl_nipguru_lookdaily").val(allNipDaily);
                $("#mdl_nipkepsek_lookdaily").val(`<?= $nipkepsek; ?>`);
                $("#mdl_guru_lookdaily").val(allNamaGuru);
                $("#mdl_nis_or_idgroup_lookdaily").val(allNisOrIdGroup);
                $("#mdl_foto_upload_lookdaily").val(allImageUpload);
                $("#mdl_nama_siswa_or_groupkelas_lookdaily").val(allNamaSiswaOrGroup);
                $("#mdl_tgl_posting_lookdaily").val(allTglPosted + " " + allTimePosted);
                $("#mdl_tglori_posting_lookdaily").val(allDateOri);

                $("#mdl_jdl_posting_lookdaily").val(allTitleDaily);
                $("#all_title_daily_appr").val(allTitleDaily);

                $("#mdl_isi_posting_lookdaily").val(allDescriptionDaily);
                $("#all_main_daily_appr").html(allDescriptionDaily);
                $("#mdl_all_daily").attr('name', 'daily_group');

              } else if (allStdOrGroup == "std") {
                
                $("#all_lbl_std_or_group_df_appr").text("STUDENT");

                $('#mdl_roomkey_group_lookdaily').attr('name', 'roomkey_lookdaily');
                $("#mdl_roomkey_group_lookdaily").val(room_key);

                $("#all_pengirim_appr").val(allpengirim);
                $("#all_tanggal_upload_appr").val(allTglPosted + ' ' + allTimePosted);
                $("#all_siswa_daily_appr").val(allNamaSiswaOrGroup);
                $("#mdl_nipguru_lookdaily").val(allNipDaily);
                $("#mdl_nipkepsek_lookdaily").val(`<?= $nipkepsek; ?>`);
                $("#mdl_guru_lookdaily").val(allNamaGuru);
                $("#mdl_nis_or_idgroup_lookdaily").val(allNisOrIdGroup);
                $("#mdl_foto_upload_lookdaily").val(allImageUpload);
                $("#mdl_nama_siswa_or_groupkelas_lookdaily").val(allNamaSiswaOrGroup);
                $("#mdl_tgl_posting_lookdaily").val(allTglPosted + " " + allTimePosted);
                $("#mdl_tglori_posting_lookdaily").val(allDateOri);

                $("#mdl_jdl_posting_lookdaily").val(allTitleDaily);
                $("#all_title_daily_appr").val(allTitleDaily);

                $("#mdl_isi_posting_lookdaily").val(allDescriptionDaily);
                $("#all_main_daily_appr").html(allDescriptionDaily);
                $("#mdl_all_daily").attr('name', 'redirectLookDaily');

              }

              

            });

            $("#all_close_approve").click(function(e) {
              $("#modal-default-all-status-approved").modal("show");
            });

            let jumlahInfo     = JSON.parse(this.responseText).notif_info;
            
            if (localStorage.getItem('jumlah_byr') != 'tidak') {
              let createLocalStorage = localStorage.setItem('jumlah_byr', 'ada');
              if(jumlahInfo > 0) {
                if (localStorage.getItem('jumlah_byr') == 'ada') {
                  localStorage.setItem('jumlah_byr', 'tidak');
                  Swal.fire("Terdapat Administrasi Pembiayaan Yang Perlu Di Selesaikan")
                }
              }

            } 

            if (jumlahInfo == 0) {

              $("#notifInfoList").hide();
              $("#pesanInfo").show();
              $("#pesanInfo").html(`Tidak Ada Info Pemberitahuan !`);
              $("#pesanInfo").css("background-color", "whitesmoke");
              $("#isi_pesan_info").html(JSON.parse(this.responseText).isi_notif_info);

            } else if(jumlahInfo > 0) {

              $("#notifInfoList").show();
              $("#pesanInfo").show();
              $("#pesanInfo").html(`Ada <span style="font-weight: bold;"> ${JSON.parse(this.responseText).notif_info} </span> Pemberitahuan`);
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
              let dataNominal    = $(this).data('nominal');

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

        xhttp.open("GET", `<?= $basewam; ?>data`, true);
        xhttp.send();
      }, 500)

    }

    loadData()

  })

  function isLogOut() {
    document.location.href = `<?= $base; ?>`
  }

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

    $('#list_group').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
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
      "info": true,
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

  // $("#example").DataTable();

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
