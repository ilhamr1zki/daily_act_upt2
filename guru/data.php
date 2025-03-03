<?php  

	require '../php/config.php'; 

	error_reporting(0);  

	// Cek status login user jika tidak ada session
  if (!$user->isLoggedInGuru()) { 

    header("location:../"); //Redirect ke halaman login  
  }

	$status_approve = "";

	function getDateTimeDiff($tanggal) {
    
    date_default_timezone_set("Asia/Jakarta");
    $now_timestamp = strtotime(date('Y-m-d H:i:s'));
    $diff_timestamp = $now_timestamp - strtotime($tanggal);

    if ($diff_timestamp < 60) {
      return 'Beberapa detik yang lalu';
    } else if ( $diff_timestamp >= 60 && $diff_timestamp < 3600 ) {
      return round($diff_timestamp/60) . ' Menit yang lalu';
    } else if ( $diff_timestamp >= 3600 && $diff_timestamp < 86400 ) {
      return round($diff_timestamp/3600) . ' Jam yang lalu';
    } else if ( $diff_timestamp >= 86400 && $diff_timestamp < (86400*30) ) {
      return round($diff_timestamp/(86400)). ' Hari yang lalu';
    } else if ( $diff_timestamp >= (86400*30) && $diff_timestamp < (86400*365) ) {
      return round($diff_timestamp/(86400*30)) . ' Bulan yang lalu';
    } else {
      return round($diff_timestamp/(86400*365)) . ' Tahun yang lalu';
    }

  }

  function tgl_indo($date){  
    $tanggal_indo = date_create($date);
    date_timezone_set($tanggal_indo,timezone_open("Asia/Jakarta"));
    $array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember');
    $date = strtotime($date);
    $tanggal = date ('d', $date);
    $bulan = $array_bulan[date('n',$date)];
    $tahun = date('Y',$date); 

    $H     = date_format($tanggal_indo, "H");
    $i     = date_format($tanggal_indo, "i");
    $s     = date_format($tanggal_indo, "s");
    // $jamIndo = date("h:i:s", $date);
    $jamIndo = date_format($tanggal_indo, "H:i:s");
    // echo $jamIndo;
    $result = $tanggal ." ". $bulan ." ". $tahun;       
    return($result);  
  }
  
  function format_tgl_indo($date){  
    $tanggal_indo = date_create($date);
    date_timezone_set($tanggal_indo,timezone_open("Asia/Jakarta"));
    $array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember');
    $date = strtotime($date);
    $tanggal = date ('d', $date);
    $bulan = $array_bulan[date('n',$date)];
    $tahun = date('Y',$date); 

    $H     = date_format($tanggal_indo, "H");
    $i     = date_format($tanggal_indo, "i");
    $s     = date_format($tanggal_indo, "s");
    // $jamIndo = date("h:i:s", $date);
    $jamIndo = date_format($tanggal_indo, "H:i:s");
    // echo $jamIndo;
    $result = $tanggal ." ". $bulan ." ". $tahun . " " . $jamIndo;       
    return($result);  
  }

  $arr                  = [];
  $arrOther             = [];
  $arrMe                = [];
  
  if (isset($_POST['room_id'])) {
        
    $room_id   = $_POST['room_id'];
    $usrGuru   = $_POST['usr_guru'];
    $nipKepsek = $_POST['nip_kepsek'];
    $nisOTM    = $_POST['nisotm'];

    $getDataKomenOther = mysqli_query($conn, "
      SELECT 
      tbl_komentar.room_id as r_id,
      tbl_komentar.code_user as fromnip,
      guru.nama as nama_guru,
      kepala_sekolah.nama as nama_kepsek,
      siswa.nama as nama_siswa,
      tbl_komentar.stamp as tanggal_kirim,
      tbl_komentar.isi_komentar as pesan
      FROM 
      tbl_komentar 
      LEFT JOIN ruang_pesan
      ON tbl_komentar.room_id = ruang_pesan.room_key
      LEFT JOIN guru
      ON tbl_komentar.code_user = guru.nip
      LEFT JOIN kepala_sekolah
      ON tbl_komentar.code_user = kepala_sekolah.nip
      LEFT JOIN akses_otm
      ON tbl_komentar.code_user = akses_otm.nis_siswa
      LEFT JOIN siswa
      ON akses_otm.nis_siswa = siswa.nis
      WHERE 
      ruang_pesan.room_key LIKE '%$room_id%'
      ORDER BY tbl_komentar.id
    ");

    $getDataFrom_MySelf = mysqli_query($conn, "
      SELECT 
      room_key as r_key,
      tbl_komentar.room_id as room_id,
      tbl_komentar.isi_komentar as pesan,
      tbl_komentar.code_user as from_pesan,
      tbl_komentar.stamp as tanggal_kirim,
      guru.nama as nama_guru
      FROM 
      ruang_pesan
      LEFT JOIN tbl_komentar
      ON tbl_komentar.room_id = ruang_pesan.room_key
      LEFT JOIN guru
      ON tbl_komentar.code_user = guru.nip
      WHERE 
      ruang_pesan.room_key LIKE '%$room_id%'
      ORDER BY tbl_komentar.id
    ");

    $getDataAll   = mysqli_fetch_array($getDataKomenOther);
    $getDataSelf  = mysqli_fetch_array($getDataFrom_MySelf);

    $count        = mysqli_num_rows($getDataKomenOther);
    $fromAll      = [];
    $fromMe       = [];
    $pesanSemua   = [];
    $pesanSaya    = [];

    foreach ($getDataKomenOther as $all_data) {

      $fromAll[] = $all_data['nama_guru'];

      if ($all_data['fromnip'] == $usrGuru) {
        
        $pesanSemua[] = '
        <div class="direct-chat-msg right">
          <div class="direct-chat-info clearfix">
            <span class="direct-chat-name pull-right">'. $all_data['nama_guru'] .'</span>
            <span class="direct-chat-timestamp pull-left">'. format_tgl_indo($all_data['tanggal_kirim']) . '</span>
          </div>
          <img class="direct-chat-img" src="'. $base . 'theme/dist/img/icon_chat.png'. '" alt="Message User Image">
          <div class="direct-chat-text">'. htmlspecialchars($all_data['pesan']) .'</div>
          </div>
        </div>';

      } else if ($all_data['fromnip'] == $nipKepsek) {

        $pesanSemua[] = '
          <div class="direct-chat-msg">
            <div class="direct-chat-info clearfix">
              <span class="direct-chat-name pull-left">'. $all_data['nama_kepsek'] .'</span>
              <span class="direct-chat-timestamp pull-right">'. format_tgl_indo($all_data['tanggal_kirim']) .'</span>
            </div>
            <img class="direct-chat-img" src="'. $base . 'theme/dist/img/icon_chat.png'. '" alt="Message User Image">
            <div class="direct-chat-text">'. htmlspecialchars($all_data['pesan']) .'</div>
          </div>';

      } else if ($all_data['fromnip'] == $nisOTM) {

        $pesanSemua[] = '
          <div class="direct-chat-msg">
            <div class="direct-chat-info clearfix">
              <span class="direct-chat-name pull-left">'. $all_data['nama_siswa'] .'</span>
              <span class="direct-chat-timestamp pull-right">'. format_tgl_indo($all_data['tanggal_kirim']) .'</span>
            </div>
            <img class="direct-chat-img" src="'. $base . 'theme/dist/img/icon_chat.png'. '" alt="Message User Image">
            <div class="direct-chat-text">'. htmlspecialchars($all_data['pesan']) .'</div>
          </div>';

      }

    }

    foreach ($getDataFrom_MySelf as $myself) {
      $fromMe[]  = $myself['nama_guru'];
      $pesanSaya[] = '
        <div class="direct-chat-msg right">
          <div class="direct-chat-info clearfix">
            <span class="direct-chat-name pull-right">'. $myself['nama_guru'] .'</span>
            <span class="direct-chat-timestamp pull-left">'. format_tgl_indo($myself['tanggal_kirim']) . '</span>
          </div>
          <img class="direct-chat-img" src="'. $base . 'theme/dist/img/icon_chat.png'. '" alt="Message User Image">
          <div class="direct-chat-text">'. htmlspecialchars($myself['pesan']) .'</div>
          </div>
        </div>'
      ;
    }

    for ($i=0; $i < count($pesanSemua); $i++) { 
      $arrOther[] = $pesanSemua[$i];
    }

    for ($i=0; $i < count($pesanSaya); $i++) { 
      $arrMe[] = $pesanSaya[$i];
    }    

  }

  $tampungDataTglUpload     = [];
  $tampungDataJamUpload     = [];
  $tampungDataImage         = [];
  $tampungDataJudul         = [];
  $tampungDataIsi           = [];
  $tampungDataNisOrIdGroup  = [];
  $tampungDataSiswa_blmAppr = [];
  $forImage                 = '';
  $forIsiNotifBlmAppr       = '';
  $forIsiNotifSdhAppr       = "";
  $elementNotifWaiting      = '';

  // Array Penampung Data Sudah di Approve
  $tampungDataTglUploadOri          = [];
  $tampungDataRoomKey               = [];
  $tampungDataID_sdhAppr            = [];
  $tampungDataNIP_sdhAppr           = [];
  $tampungDataNisOrIdGroup_sdhAppr  = [];
  $tampungDataSiswa_sdhAppr         = [];
  $tampungDataPengirim_sdhAppr      = [];
  $tampungDataTglDisetujui          = [];
  $tampungDataTglDiUpload           = [];
  $tampungDataJamDiUpload           = [];
  $tampungDataJamDisetujui          = [];
  $tampungDataImage_sdhAppr         = [];
  $tampungDataJudul_sdhAppr         = [];
  $tampungDataIsi_sdhAppr           = [];
  $elementNotifAppr                 = '';
  // Akhir Array Penampung Data Sudah di Approve

  // Array Penampung Data Tidak di Approve
  $tampungDataID_tdkDiAppr            = [];
  $tampungDataNIP_tdkDiAppr           = [];
  $tampungDataNisOrIdGroup_tdkDiAppr  = [];
  $tampungDataPengirim_tdkDiAppr      = [];
  $tampungDataTglTdkDisetujui         = [];
  $tampungDataTglUpload_tdkDiAppr     = [];
  $tampungDataJamUpload_tdkDiAppr     = [];
  $tampungDataJamTdkDisetujui         = [];
  $tampungDataImage_tdkDiAppr         = [];
  $tampungDataJudul_tdkDiAppr         = [];
  $tampungDataIsi_tdkDiAppr           = [];
  $tampungDataIsiAlasan_tdkDiAppr     = [];
  $elementNotifNotAppr            = '';
  // Akhir Array Penampung Data Tidak di Approve

  date_default_timezone_set("Asia/Jakarta");
  $arrTgl               = [];
  
  $tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
  $tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

  $arrTgl['tgl_awal']   = $tglSkrngAwal;
  $arrTgl['tgl_akhir']  = $tglSkrngAkhir;

  $forImage .= '
      <div class="col-sm-4" style="clear: left;">
         <label for="exampleInputEmail1"> Upload Image Daily (*Tidak lebih dari 2 MB) </label>
         <input type="file" class="form-control fileGambar" name="banner" id="buat_banner">
         <img src="" id="gambar">
      </div>'
  ;

	$queryMainDaily	 			= mysqli_query($con, "SELECT * FROM daily_siswa_approved WHERE status_approve = 1 ");
	$getDataMainDaily     = mysqli_fetch_array($queryMainDaily);

	$queryWaitingAppr  		= mysqli_query($con, "
    SELECT *
    FROM (
      SELECT 
        daily_siswa_approved.id as daily_id,
        daily_siswa_approved.from_nip as from_nip,
        daily_siswa_approved.image as foto,
        daily_siswa_approved.isi_daily as isi_daily,
        daily_siswa_approved.nis_siswa as nis_or_id_group_kelas,
        daily_siswa_approved.title_daily as judul,
        daily_siswa_approved.tanggal_dibuat as tgl_dibuat,
        daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
        daily_siswa_approved.status_approve AS status_approve,
        guru.nama as nama_guru,
        admin.username as nama_user,
        siswa.nama as nama_siswa_or_nama_group_kelas,
        ruang_pesan.room_key as room_key
      FROM daily_siswa_approved
      LEFT JOIN guru
        ON daily_siswa_approved.from_nip = guru.nip
        LEFT JOIN admin
        ON daily_siswa_approved.from_nip = admin.c_admin
        LEFT JOIN siswa
        ON daily_siswa_approved.nis_siswa = siswa.nis
        LEFT JOIN ruang_pesan
        ON ruang_pesan.daily_id = daily_siswa_approved.id
          UNION
      SELECT 
        group_siswa_approved.id as group_daily_id,
        group_siswa_approved.from_nip as from_nip,
        group_siswa_approved.image as foto,
        group_siswa_approved.isi_daily as isi_daily,
        group_siswa_approved.group_kelas_id as group_kelas_id,
        group_siswa_approved.title_daily as judul,
        group_siswa_approved.tanggal_dibuat as tgl_dibuat,
        group_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
        group_siswa_approved.status_approve AS status_approve,
        guru.nama as nama_guru,
        admin.username as nama_user,
        group_kelas.nama_group_kelas as nama_group_kelas,
        ruang_pesan.room_key as room_key
      FROM group_siswa_approved
          LEFT JOIN guru
        ON group_siswa_approved.from_nip = guru.nip
        LEFT JOIN admin
        ON group_siswa_approved.from_nip = admin.c_admin
        LEFT JOIN group_kelas
        ON group_siswa_approved.group_kelas_id = group_kelas.id
        LEFT JOIN ruang_pesan
        ON ruang_pesan.daily_id = group_siswa_approved.id
      ) AS U
    WHERE 
      U.status_approve = 0
      AND U.from_nip = '$_SESSION[nip_guru]'
      AND U.tgl_dibuat >= '$arrTgl[tgl_awal]' 
      AND U.tgl_dibuat <= '$arrTgl[tgl_akhir]'
      ORDER BY U.tgl_dibuat DESC
  ");

  $queryWaitingApprAll = mysqli_query($con, "
    SELECT *
    FROM (
      SELECT 
        daily_siswa_approved.id as daily_id,
        daily_siswa_approved.from_nip as from_nip,
        daily_siswa_approved.image as foto,
        daily_siswa_approved.isi_daily as isi_daily,
        daily_siswa_approved.nis_siswa as nis_or_id_group_kelas,
        daily_siswa_approved.title_daily as judul,
        daily_siswa_approved.tanggal_dibuat as tgl_dibuat,
        daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
        daily_siswa_approved.status_approve AS status_approve,
        guru.nama as nama_guru,
        admin.username as nama_user,
        siswa.nama as nama_siswa_or_nama_group_kelas,
        ruang_pesan.room_key as room_key
      FROM daily_siswa_approved
      LEFT JOIN guru
        ON daily_siswa_approved.from_nip = guru.nip
        LEFT JOIN admin
        ON daily_siswa_approved.from_nip = admin.c_admin
        LEFT JOIN siswa
        ON daily_siswa_approved.nis_siswa = siswa.nis
        LEFT JOIN ruang_pesan
        ON ruang_pesan.daily_id = daily_siswa_approved.id
      UNION
      SELECT 
        group_siswa_approved.id as group_daily_id,
        group_siswa_approved.from_nip as from_nip,
        group_siswa_approved.image as foto,
        group_siswa_approved.isi_daily as isi_daily,
        group_siswa_approved.group_kelas_id as group_kelas_id,
        group_siswa_approved.title_daily as judul,
        group_siswa_approved.tanggal_dibuat as tgl_dibuat,
        group_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
        group_siswa_approved.status_approve AS status_approve,
        guru.nama as nama_guru,
        admin.username as nama_user,
        group_kelas.nama_group_kelas as nama_group_kelas,
        ruang_pesan.room_key as room_key
      FROM group_siswa_approved
      LEFT JOIN guru
        ON group_siswa_approved.from_nip = guru.nip
        LEFT JOIN admin
        ON group_siswa_approved.from_nip = admin.c_admin
        LEFT JOIN group_kelas
        ON group_siswa_approved.group_kelas_id = group_kelas.id
        LEFT JOIN ruang_pesan
        ON ruang_pesan.daily_id = group_siswa_approved.id
      ) AS U
    WHERE 
      U.status_approve = 0
      AND U.from_nip = '$_SESSION[nip_guru]'
      ORDER BY U.tgl_dibuat DESC
  ");

  $countDataWaitApprAll = mysqli_num_rows($queryWaitingApprAll);

  foreach($queryWaitingAppr as $data) {

    $tampungDataTglUpload[]     = substr($data['tgl_dibuat'], 0, 11);
    $tampungDataSiswa_blmAppr[] = strtoupper($data['nama_siswa_or_nama_group_kelas']);
    $tampungDataJamUpload[]     = substr($data['tgl_dibuat'], 11, 19);
    $tampungDataImage[]         = $data['foto'];
    $tampungDataJudul[]         = $data['judul'];
    $tampungDataNisOrIdGroup[]  = $data['nis_or_id_group_kelas'];
    $tampungDataIsi[]           = $data['isi_daily'];

  }

  $data5 = count($tampungDataJudul);

  if ($data5 > 5 ) {
    $elementNotifWaiting .= 
      '<span class="label label-warning" id="notifWaitingList"> '. count($tampungDataJudul) .' </span>'
    ;

    // hanya mengambil 5 data saja untuk notif
    for ($i = 0; $i < 5; $i++) { 

      $explode               = explode(" ", $tampungDataPengirim[$i]);

      $isiPesanNamaSiswa     = "";
      $isiPesan              = "";

      $semuaNisOrIdGroup  = $tampungDataNisOrIdGroup[$i];
      $semuaNamaSiswa     = strlen($tampungDataSiswa_blmAppr[$i]) > 13 ? $isiPesanNamaSiswa .= substr($tampungDataSiswa_blmAppr[$i], 0, 13) . " ..." : $tampungDataSiswa_blmAppr[$i];
      $semuaPesan         = strlen($tampungDataJudul[$i]) > 15 ? $isiPesan .= substr($tampungDataJudul[$i], 0, 15) . " ..." : $tampungDataJudul[$i];

      // Check Nis Or Id Group
      $queryCheckNis = mysqli_query($con, "
        SELECT nama FROM siswa
        WHERE nis = '$semuaNisOrIdGroup'
      ");

      $queryCheckIdGroup = mysqli_query($con, "
        SELECT nama_group_kelas FROM group_kelas
        WHERE id = '$semuaNisOrIdGroup'
      ");

      $countCheckNis      = mysqli_num_rows($queryCheckNis);
      $countCheckIdGroup  = mysqli_num_rows($queryCheckIdGroup);

      if ($countCheckNis == 1) {

        $forIsiNotifBlmAppr .= '
          <li class="wtlist" data-group_or_std="'. std .'" data-siswa_blmappr="'. $tampungDataSiswa_blmAppr[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload[$i]) . " " . $tampungDataJamUpload[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage[$i] .'" data-judul="'. $tampungDataJudul[$i] .'" data-isian="'. $tampungDataIsi[$i] .'" data-toggle="modal">
            <a href="#">
              <h4 style="font-size: 12px; margin-top: 7px;">
                <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglUpload[$i]." ".$tampungDataJamUpload[$i]) . '</small>
              </h4>
              <div class="pull-left" style="margin-left: 10px; margin-top: 10px;">
                <img src="../imgstatis/logo2.png" style="width: 35px;">
              </div>
              <h4 style="font-size: 12px; margin-top: 17px;">
                <p style="font-size: 15px;margin-left: 60px;"> 
                  <strong> SISWA </strong> <span style="margin-left: 8px;"> : </span> 
                  <strong id="siswa_daily" style="margin-left: 7px; font-size: 13px;"> 
                    '. $semuaNamaSiswa .'
                  </strong> 
                </p>
              </h4>
              <h4 style="font-size: 12px;">
                <p style="font-size: 15px;margin-left: 60px;"> 
                  <strong> JUDUL </strong> <span style="margin-left: 5px;"> : </span> 
                  <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                    '. $semuaPesan .'
                  </strong> 
                </p>
              </h4>
            </a>
          </li>
        ';

      } else if ($countCheckIdGroup == 1) {

        $forIsiNotifBlmAppr .= '
          <li class="wtlist" data-group_or_std="'. group .'" data-siswa_blmappr="'. $tampungDataSiswa_blmAppr[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload[$i]) . " " . $tampungDataJamUpload[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage[$i] .'" data-judul="'. $tampungDataJudul[$i] .'" data-isian="'. $tampungDataIsi[$i] .'" data-toggle="modal">
            <a href="#">
              <h4 style="font-size: 12px; margin-top: 7px;">
                <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglUpload[$i]." ".$tampungDataJamUpload[$i]) . '</small>
              </h4>
              <div class="pull-left" style="margin-left: 10px; margin-top: 10px;">
                <img src="../imgstatis/logo2.png" style="width: 35px;">
              </div>
              <h4 style="font-size: 12px; margin-top: 17px;">
                <p style="font-size: 15px;margin-left: 60px;"> 
                  <strong> GROUP </strong> <span style="margin-left: 8px;"> : </span> 
                  <strong id="siswa_daily" style="margin-left: 7px; font-size: 13px;"> 
                    '. $semuaNamaSiswa .'
                  </strong> 
                </p>
              </h4>
              <h4 style="font-size: 12px;">
                <p style="font-size: 15px;margin-left: 60px;"> 
                  <strong> JUDUL </strong> <span style="margin-left: 11px;"> : </span> 
                  <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                    '. $semuaPesan .'
                  </strong> 
                </p>
              </h4>
            </a>
          </li>
        ';

      }

    }

  } else if ($data5 < 6) {

    $elementNotifWaiting .= 
      '<span class="label label-warning" id="notifWaitingList"> '. count($tampungDataJudul) .' </span>'
    ;

    // hanya mengambil 5 data saja untuk notif
    for ($i = 0; $i < count($tampungDataJudul); $i++) { 

      $explode               = explode(" ", $tampungDataPengirim[$i]);
      $isiPesanNamaSiswa     = "";
      $isiPesan              = "";

      $semuaNisOrIdGroup  = $tampungDataNisOrIdGroup[$i];
      $semuaNamaSiswa     = strlen($tampungDataSiswa_blmAppr[$i]) > 13 ? $isiPesanNamaSiswa .= substr($tampungDataSiswa_blmAppr[$i], 0, 13) . " ..." : $tampungDataSiswa_blmAppr[$i]; 
      $semuaPesan         = strlen($tampungDataJudul[$i]) > 15 ? $isiPesan .= substr($tampungDataJudul[$i], 0, 15) . " ..." : $tampungDataJudul[$i];

      // echo $semuaNisOrIdGroup;exit;

      // Check Nis Or Id Group
      $queryCheckNis = mysqli_query($con, "
        SELECT nama FROM siswa
        WHERE nis = '$semuaNisOrIdGroup'
      ");

      $queryCheckIdGroup = mysqli_query($con, "
        SELECT nama_group_kelas FROM group_kelas
        WHERE id = '$semuaNisOrIdGroup'
      ");

      $countCheckNis      = mysqli_num_rows($queryCheckNis);
      $countCheckIdGroup  = mysqli_num_rows($queryCheckIdGroup);

      if ($countCheckNis == 1) {

        $forIsiNotifBlmAppr .= '
          <li class="wtlist" data-group_or_std="'. std .'" data-siswa_blmappr="'. $tampungDataSiswa_blmAppr[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload[$i]) . " " . $tampungDataJamUpload[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage[$i] .'" data-judul="'. $tampungDataJudul[$i] .'" data-isian="'. $tampungDataIsi[$i] .'" data-toggle="modal">
            <a href="#">
              <h4 style="font-size: 12px; margin-top: 7px;">
                <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglUpload[$i]." ".$tampungDataJamUpload[$i]) . '</small>
              </h4>
              <div class="pull-left" style="margin-left: 10px; margin-top: 10px;">
                <img src="../imgstatis/logo2.png" style="width: 35px;">
              </div>
              <h4 style="font-size: 12px; margin-top: 17px;">
                <p style="font-size: 15px;margin-left: 60px;"> 
                  <strong> SISWA </strong> <span style="margin-left: 8px;"> : </span> 
                  <strong id="siswa_daily" style="margin-left: 7px; font-size: 13px;"> 
                    '. $semuaNamaSiswa .'
                  </strong> 
                </p>
              </h4>
              <h4 style="font-size: 12px;">
                <p style="font-size: 15px;margin-left: 60px;"> 
                  <strong> JUDUL </strong> <span style="margin-left: 5px;"> : </span> 
                  <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                    '. $semuaPesan .'
                  </strong> 
                </p>
              </h4>
            </a>
          </li>
        ';

      } else if ($countCheckIdGroup == 1) {

        $forIsiNotifBlmAppr .= '
          <li class="wtlist" data-group_or_std="'. group .'" data-siswa_blmappr="'. $tampungDataSiswa_blmAppr[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload[$i]) . " " . $tampungDataJamUpload[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage[$i] .'" data-judul="'. $tampungDataJudul[$i] .'" data-isian="'. $tampungDataIsi[$i] .'" data-toggle="modal">
            <a href="#">
              <h4 style="font-size: 12px; margin-top: 7px;">
                <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglUpload[$i]." ".$tampungDataJamUpload[$i]) . '</small>
              </h4>
              <div class="pull-left" style="margin-left: 10px; margin-top: 10px;">
                <img src="../imgstatis/logo2.png" style="width: 35px;">
              </div>
              <h4 style="font-size: 12px; margin-top: 17px;">
                <p style="font-size: 15px;margin-left: 60px;"> 
                  <strong> GROUP </strong> <span style="margin-left: 8px;"> : </span> 
                  <strong id="siswa_daily" style="margin-left: 7px; font-size: 13px;"> 
                    '. $semuaNamaSiswa .'
                  </strong> 
                </p>
              </h4>
              <h4 style="font-size: 12px;">
                <p style="font-size: 15px;margin-left: 60px;"> 
                  <strong> JUDUL </strong> <span style="margin-left: 11px;"> : </span> 
                  <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                    '. $semuaPesan .'
                  </strong> 
                </p>
              </h4>
            </a>
          </li>
        ';

      }

    }

  }

	$countDataWaitAppr 		     = mysqli_num_rows($queryWaitingAppr);
  
  ############################################################################################################
  // Data Sudah di Approve

    $queryApproved         = mysqli_query($con, "
      SELECT *
      FROM (
          SELECT 
            daily_siswa_approved.id as daily_id,
            daily_siswa_approved.from_nip as from_nip,
            daily_siswa_approved.image as foto,
            daily_siswa_approved.isi_daily as isi_daily,
            daily_siswa_approved.nis_siswa as nis_or_id_group_kelas,
            daily_siswa_approved.title_daily as judul,
            daily_siswa_approved.tanggal_dibuat as tgl_dibuat,
            daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
          daily_siswa_approved.status_approve AS status_approve,
          guru.nama as nama_guru,
            admin.username as nama_user,
            siswa.nama as nama_siswa_or_nama_group_kelas,
            ruang_pesan.room_key as room_key
          FROM daily_siswa_approved
          LEFT JOIN guru
            ON daily_siswa_approved.from_nip = guru.nip
            LEFT JOIN admin
            ON daily_siswa_approved.from_nip = admin.c_admin
            LEFT JOIN siswa
            ON daily_siswa_approved.nis_siswa = siswa.nis
            LEFT JOIN ruang_pesan
            ON ruang_pesan.daily_id = daily_siswa_approved.id
              UNION
          SELECT 
              group_siswa_approved.id as group_daily_id,
              group_siswa_approved.from_nip as from_nip,
              group_siswa_approved.image as foto,
              group_siswa_approved.isi_daily as isi_daily,
              group_siswa_approved.group_kelas_id as group_kelas_id,
              group_siswa_approved.title_daily as judul,
              group_siswa_approved.tanggal_dibuat as tgl_dibuat,
              group_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
            group_siswa_approved.status_approve AS status_approve,
            guru.nama as nama_guru,
              admin.username as nama_user,
              group_kelas.nama_group_kelas as nama_group_kelas,
              ruang_pesan.room_key as room_key
            FROM group_siswa_approved
                LEFT JOIN guru
              ON group_siswa_approved.from_nip = guru.nip
              LEFT JOIN admin
              ON group_siswa_approved.from_nip = admin.c_admin
              LEFT JOIN group_kelas
              ON group_siswa_approved.group_kelas_id = group_kelas.id
              LEFT JOIN ruang_pesan
              ON ruang_pesan.daily_id = group_siswa_approved.id
           ) AS U
      WHERE 
        U.status_approve = 1
        AND U.from_nip = '$_SESSION[nip_guru]'
        AND U.tgl_disetujui >= '$arrTgl[tgl_awal]' AND U.tgl_disetujui <= '$arrTgl[tgl_akhir]'
        ORDER BY U.tgl_disetujui DESC
    ");

    $queryApprovedAll      = mysqli_query($con, "
      SELECT *
      FROM (
          SELECT 
            daily_siswa_approved.id as daily_id,
            daily_siswa_approved.from_nip as from_nip,
            daily_siswa_approved.image as foto,
            daily_siswa_approved.isi_daily as isi_daily,
            daily_siswa_approved.nis_siswa as nis_or_id_group_kelas,
            daily_siswa_approved.title_daily as judul,
            daily_siswa_approved.tanggal_dibuat as tgl_dibuat,
            daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
          daily_siswa_approved.status_approve AS status_approve,
          guru.nama as nama_guru,
            admin.username as nama_user,
            siswa.nama as nama_siswa_or_nama_group_kelas,
            ruang_pesan.room_key as room_key
          FROM daily_siswa_approved
          LEFT JOIN guru
            ON daily_siswa_approved.from_nip = guru.nip
            LEFT JOIN admin
            ON daily_siswa_approved.from_nip = admin.c_admin
            LEFT JOIN siswa
            ON daily_siswa_approved.nis_siswa = siswa.nis
            LEFT JOIN ruang_pesan
            ON ruang_pesan.daily_id = daily_siswa_approved.id
              UNION
          SELECT 
              group_siswa_approved.id as group_daily_id,
              group_siswa_approved.from_nip as from_nip,
              group_siswa_approved.image as foto,
              group_siswa_approved.isi_daily as isi_daily,
              group_siswa_approved.group_kelas_id as group_kelas_id,
              group_siswa_approved.title_daily as judul,
              group_siswa_approved.tanggal_dibuat as tgl_dibuat,
              group_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
            group_siswa_approved.status_approve AS status_approve,
            guru.nama as nama_guru,
              admin.username as nama_user,
              group_kelas.nama_group_kelas as nama_group_kelas,
              ruang_pesan.room_key as room_key
            FROM group_siswa_approved
                LEFT JOIN guru
              ON group_siswa_approved.from_nip = guru.nip
              LEFT JOIN admin
              ON group_siswa_approved.from_nip = admin.c_admin
              LEFT JOIN group_kelas
              ON group_siswa_approved.group_kelas_id = group_kelas.id
              LEFT JOIN ruang_pesan
              ON ruang_pesan.daily_id = group_siswa_approved.id
           ) AS U
      WHERE 
        U.status_approve = 1
        AND U.from_nip = '$_SESSION[nip_guru]'
        ORDER BY U.tgl_disetujui DESC
    ");

    foreach ($queryApproved as $data_appr) {

      $tampungDataTglUploadOri[]          = $data_appr['tgl_disetujui'];
      $tampungDataRoomKey[]               = $data_appr['room_key'];
      $tampungDataID_sdhAppr[]            = $data_appr['daily_id'];
      $tampungDataNIP_sdhAppr[]           = $data_appr['from_nip'];
      $tampungDataNisOrIdGroup_sdhAppr[]  = $data_appr['nis_or_id_group_kelas'];
      $tampungDataSiswa_sdhAppr[]         = strtoupper($data_appr['nama_siswa_or_nama_group_kelas']);
      $tampungDataPengirim_sdhAppr[]      = $data_appr['nama_guru'];
      $tampungDataTglDiUpload[]           = substr($data_appr['tgl_dibuat'], 0, 11);
      $tampungDataTglDisetujui[]          = substr($data_appr['tgl_disetujui'], 0, 11);
      $tampungDataJamDiUpload[]           = substr($data_appr['tgl_dibuat'], 11, 19);
      $tampungDataJamDisetujui[]          = substr($data_appr['tgl_disetujui'], 11, 19);
      $tampungDataImage_sdhAppr[]         = $data_appr['foto'];
      $tampungDataJudul_sdhAppr[]         = $data_appr['judul'];
      $tampungDataIsi_sdhAppr[]           = $data_appr['isi_daily'];

    }

    $countDataApproved    = mysqli_num_rows($queryApproved);
    $countDataApprovedAll = mysqli_num_rows($queryApprovedAll);

    if($countDataApproved > 5) {

      for ($i = 0; $i < 5; $i++) {

        $explode                = explode(" ", $tampungDataPengirim_sdhAppr[$i]);
        $isiPesanNamaSiswa      = "";
        $isiPesan               = "";
        
        $semuaNisOrIdGroup  = $tampungDataNisOrIdGroup_sdhAppr[$i];
        $semuaNamaSiswa     = strlen($tampungDataSiswa_sdhAppr[$i]) > 13 ? $isiPesanNamaSiswa .= substr($tampungDataSiswa_sdhAppr[$i], 0, 13) . " ..." : $tampungDataSiswa_sdhAppr[$i];
        $semuaPesan         = strlen($tampungDataJudul_sdhAppr[$i]) > 15 ? $isiPesan .= substr($tampungDataJudul_sdhAppr[$i], 0, 15) . " ..." : $tampungDataJudul_sdhAppr[$i];

        // Check Nis Or Id Group
        $queryCheckNis = mysqli_query($con, "
          SELECT nama FROM siswa
          WHERE nis = '$semuaNisOrIdGroup'
        ");

        $queryCheckIdGroup = mysqli_query($con, "
          SELECT nama_group_kelas FROM group_kelas
          WHERE id = '$semuaNisOrIdGroup'
        ");

        $countCheckNis      = mysqli_num_rows($queryCheckNis);
        $countCheckIdGroup  = mysqli_num_rows($queryCheckIdGroup);

        if ($countCheckNis == 1) {

          $forIsiNotifSdhAppr .= '
            <li class="apprlist" data-siswaorgroup_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-nipguru="'. $tampungDataNIP_sdhAppr[$i] .'" data-group_or_std="'. std .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNisOrIdGroup_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataPengirim_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-siswa_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-tgl_upload = "'. tgl_indo($tampungDataTglDiUpload[$i]) . " " . $tampungDataJamDiUpload[$i] . '" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
              <a href="#">
                <h4 style="font-size: 12px;">
                  <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
                </h4>
                <div class="pull-left" style="margin-left: 10px; margin-top: 13px;">
                  <img src="../imgstatis/logo2.png" style="width: 35px;">
                </div>
                <h4 style="font-size: 12px; margin-top: 20px;">
                  <p style="font-size: 15px;margin-left: 60px;"> 
                    <strong> SISWA </strong> <span style="margin-left: 11px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                      '. $semuaNamaSiswa .'
                    </strong> 
                  </p>
                </h4>
                <h4 style="font-size: 12px;">
                  <p style="font-size: 15px;margin-left: 60px;"> 
                    <strong> JUDUL </strong> <span style="margin-left: 8.5px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 9px; font-size: 13px;"> 
                      '. $semuaPesan .'
                    </strong> 
                  </p>
                </h4>
              </a>
            </li>
          ';

        } elseif($countCheckIdGroup == 1) {

          $forIsiNotifSdhAppr .= '
            <li class="apprlist" data-siswaorgroup_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-nipguru="'. $tampungDataNIP_sdhAppr[$i] .'" data-group_or_std="'. group .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNisOrIdGroup_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataPengirim_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-siswa_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-tgl_upload = "'. tgl_indo($tampungDataTglDiUpload[$i]) . " " . $tampungDataJamDiUpload[$i] . '" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
              <a href="#">
                <h4 style="font-size: 12px;">
                  <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
                </h4>
                <div class="pull-left" style="margin-left: 10px; margin-top: 13px;">
                  <img src="../imgstatis/logo2.png" style="width: 35px;">
                </div>
                <h4 style="font-size: 12px; margin-top: 20px;">
                  <p style="font-size: 15px;margin-left: 60px;"> 
                    <strong> GROUP </strong> <span style="margin-left: 6px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                      '. $semuaNamaSiswa .'
                    </strong> 
                  </p>
                </h4>
                <h4 style="font-size: 12px;">
                  <p style="font-size: 15px;margin-left: 59px;"> 
                    <strong> JUDUL </strong> <span style="margin-left: 10px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                      '. $semuaPesan .'
                    </strong> 
                  </p>
                </h4>
              </a>
            </li>
          ';

        }


      }

    } else if ($countDataApproved < 6) {

      for ($i = 0; $i < $countDataApproved; $i++) {

        $explode                = explode(" ", $tampungDataPengirim_sdhAppr[$i]);
        $isiPesanNamaSiswa      = "";
        $isiPesan               = "";
        
        $semuaNisOrIdGroup  = $tampungDataNisOrIdGroup_sdhAppr[$i];
        $semuaNamaSiswa     = strlen($tampungDataSiswa_sdhAppr[$i]) > 13 ? $isiPesanNamaSiswa .= substr($tampungDataSiswa_sdhAppr[$i], 0, 13) . " ..." : $tampungDataSiswa_sdhAppr[$i];
        $semuaPesan         = strlen($tampungDataJudul_sdhAppr[$i]) > 15 ? $isiPesan .= substr($tampungDataJudul_sdhAppr[$i], 0, 15) . " ..." : $tampungDataJudul_sdhAppr[$i];

        // Check Nis Or Id Group
        $queryCheckNis = mysqli_query($con, "
          SELECT nama FROM siswa
          WHERE nis = '$semuaNisOrIdGroup'
        ");

        $queryCheckIdGroup = mysqli_query($con, "
          SELECT nama_group_kelas FROM group_kelas
          WHERE id = '$semuaNisOrIdGroup'
        ");

        $countCheckNis      = mysqli_num_rows($queryCheckNis);
        $countCheckIdGroup  = mysqli_num_rows($queryCheckIdGroup);

        if ($countCheckNis == 1) {

          $forIsiNotifSdhAppr .= '
            <li class="apprlist" data-siswaorgroup_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-nipguru="'. $tampungDataNIP_sdhAppr[$i] .'" data-group_or_std="'. std .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNisOrIdGroup_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataPengirim_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-siswa_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-tgl_upload = "'. tgl_indo($tampungDataTglDiUpload[$i]) . " " . $tampungDataJamDiUpload[$i] . '" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
              <a href="#">
                <h4 style="font-size: 12px;">
                  <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
                </h4>
                <div class="pull-left" style="margin-left: 10px; margin-top: 13px;">
                  <img src="../imgstatis/logo2.png" style="width: 35px;">
                </div>
                <h4 style="font-size: 12px; margin-top: 20px;">
                  <p style="font-size: 15px;margin-left: 60px;"> 
                    <strong> SISWA </strong> <span style="margin-left: 6px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                      '. $semuaNamaSiswa .'
                    </strong> 
                  </p>
                </h4>
                <h4 style="font-size: 12px;">
                  <p style="font-size: 15px;margin-left: 59px;"> 
                    <strong> JUDUL </strong> <span style="margin-left: 4px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                      '. $semuaPesan .'
                    </strong> 
                  </p>
                </h4>
              </a>
            </li>
          ';

        } elseif($countCheckIdGroup == 1) {

          $forIsiNotifSdhAppr .= '
            <li class="apprlist" data-siswaorgroup_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-nipguru="'. $tampungDataNIP_sdhAppr[$i] .'" data-group_or_std="'. group .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNisOrIdGroup_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataPengirim_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-siswa_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-tgl_upload = "'. tgl_indo($tampungDataTglDiUpload[$i]) . " " . $tampungDataJamDiUpload[$i] . '" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
              <a href="#">
                <h4 style="font-size: 12px;">
                  <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
                </h4>
                <div class="pull-left" style="margin-left: 10px; margin-top: 13px;">
                  <img src="../imgstatis/logo2.png" style="width: 35px;">
                </div>
                <h4 style="font-size: 12px; margin-top: 20px;">
                  <p style="font-size: 15px;margin-left: 60px;"> 
                    <strong> GROUP </strong> <span style="margin-left: 6px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                      '. $semuaNamaSiswa .'
                    </strong> 
                  </p>
                </h4>
                <h4 style="font-size: 12px;">
                  <p style="font-size: 15px;margin-left: 59px;"> 
                    <strong> JUDUL </strong> <span style="margin-left: 10px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                      '. $semuaPesan .'
                    </strong> 
                  </p>
                </h4>
              </a>
            </li>
          ';

        }

      }

    }

  // Akhir Data Sudah di Approve
  ############################################################################################################

  ############################################################################################################
  // Data Tidak di Approve

    $queryNotApproved         = mysqli_query($con, "
      SELECT *
      FROM (
        SELECT 
          daily_siswa_approved.id as daily_id,
          daily_siswa_approved.from_nip as from_nip,
          daily_siswa_approved.image as foto,
          daily_siswa_approved.isi_daily as isi_daily,
          daily_siswa_approved.nis_siswa as nis_or_id_group_kelas,
          daily_siswa_approved.title_daily as judul,
          daily_siswa_approved.tanggal_dibuat as tgl_dibuat,
          daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
          daily_siswa_approved.status_approve AS status_approve,
          reason.is_reason AS isi_alasan,
          guru.nama as nama_guru,
          admin.username as nama_user,
          siswa.nama as nama_siswa_or_nama_group_kelas,
          ruang_pesan.room_key as room_key
        FROM daily_siswa_approved
        LEFT JOIN guru
          ON daily_siswa_approved.from_nip = guru.nip
        LEFT JOIN admin
          ON daily_siswa_approved.from_nip = admin.c_admin
        LEFT JOIN siswa
          ON daily_siswa_approved.nis_siswa = siswa.nis
        LEFT JOIN ruang_pesan
          ON ruang_pesan.daily_id = daily_siswa_approved.id
        LEFT JOIN reason
          ON reason.daily_siswa_id = daily_siswa_approved.id
        UNION
        SELECT 
          group_siswa_approved.id as group_daily_id,
          group_siswa_approved.from_nip as from_nip,
          group_siswa_approved.image as foto,
          group_siswa_approved.isi_daily as isi_daily,
          group_siswa_approved.group_kelas_id as group_kelas_id,
          group_siswa_approved.title_daily as judul,
          group_siswa_approved.tanggal_dibuat as tgl_dibuat,
          group_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
          group_siswa_approved.status_approve AS status_approve,
          reason.is_reason AS isi_alasan,
          guru.nama as nama_guru,
          admin.username as nama_user,
          group_kelas.nama_group_kelas as nama_group_kelas,
          ruang_pesan.room_key as room_key
        FROM group_siswa_approved
        LEFT JOIN guru
          ON group_siswa_approved.from_nip = guru.nip
        LEFT JOIN admin
          ON group_siswa_approved.from_nip = admin.c_admin
        LEFT JOIN group_kelas
          ON group_siswa_approved.group_kelas_id = group_kelas.id
        LEFT JOIN ruang_pesan
          ON ruang_pesan.daily_id = group_siswa_approved.id
        LEFT JOIN reason
          ON reason.daily_siswa_id = group_siswa_approved.id
      ) AS U
      WHERE 
        U.status_approve = 2
        AND U.from_nip = '$_SESSION[nip_guru]'
        AND U.tgl_disetujui >= '$arrTgl[tgl_awal]' AND U.tgl_disetujui <= '$arrTgl[tgl_akhir]'
        ORDER BY U.tgl_disetujui DESC
    ");

    $queryNotApprovedAll      = mysqli_query($con, "
      SELECT *
      FROM (
        SELECT 
          daily_siswa_approved.id as daily_id,
          daily_siswa_approved.from_nip as from_nip,
          daily_siswa_approved.image as foto,
          daily_siswa_approved.isi_daily as isi_daily,
          daily_siswa_approved.nis_siswa as nis_or_id_group_kelas,
          daily_siswa_approved.title_daily as judul,
          daily_siswa_approved.tanggal_dibuat as tgl_dibuat,
          daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
          daily_siswa_approved.status_approve AS status_approve,
          reason.is_reason AS isi_alasan,
          guru.nama as nama_guru,
          admin.username as nama_user,
          siswa.nama as nama_siswa_or_nama_group_kelas,
          ruang_pesan.room_key as room_key
        FROM daily_siswa_approved
        LEFT JOIN guru
          ON daily_siswa_approved.from_nip = guru.nip
        LEFT JOIN admin
          ON daily_siswa_approved.from_nip = admin.c_admin
        LEFT JOIN siswa
          ON daily_siswa_approved.nis_siswa = siswa.nis
        LEFT JOIN ruang_pesan
          ON ruang_pesan.daily_id = daily_siswa_approved.id
        LEFT JOIN reason
          ON reason.daily_siswa_id = daily_siswa_approved.id
        UNION
        SELECT 
          group_siswa_approved.id as group_daily_id,
          group_siswa_approved.from_nip as from_nip,
          group_siswa_approved.image as foto,
          group_siswa_approved.isi_daily as isi_daily,
          group_siswa_approved.group_kelas_id as group_kelas_id,
          group_siswa_approved.title_daily as judul,
          group_siswa_approved.tanggal_dibuat as tgl_dibuat,
          group_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
          group_siswa_approved.status_approve AS status_approve,
          reason.is_reason AS isi_alasan,
          guru.nama as nama_guru,
          admin.username as nama_user,
          group_kelas.nama_group_kelas as nama_group_kelas,
          ruang_pesan.room_key as room_key
        FROM group_siswa_approved
        LEFT JOIN guru
          ON group_siswa_approved.from_nip = guru.nip
        LEFT JOIN admin
          ON group_siswa_approved.from_nip = admin.c_admin
        LEFT JOIN group_kelas
          ON group_siswa_approved.group_kelas_id = group_kelas.id
        LEFT JOIN ruang_pesan
          ON ruang_pesan.daily_id = group_siswa_approved.id
        LEFT JOIN reason
          ON reason.daily_siswa_id = group_siswa_approved.id
      ) AS U
      WHERE 
        U.status_approve = 2
        AND U.from_nip = '$_SESSION[nip_guru]'
        ORDER BY U.tgl_disetujui DESC
    ");

    foreach ($queryNotApproved as $data_not_appr) {

      $tampungDataID_tdkDiAppr[]            = $data_not_appr['daily_id'];
      $tampungDataNIP_tdkDiAppr[]           = $data_not_appr['from_nip'];
      $tampungDataNisOrIdGroup_tdkDiAppr[]  = $data_not_appr['nis_or_id_group_kelas'];
      $tampungDataPengirim_tdkDiAppr[]      = strtoupper($data_not_appr['username_guru']);
      $tampungDataSiswa_tdkDiAppr[]         = strtoupper($data_not_appr['nama_siswa_or_nama_group_kelas']);
      $tampungDataTglUpload_tdkDiAppr[]     = substr($data_not_appr['tgl_dibuat'], 0, 11);
      $tampungDataTglTdkDisetujui[]         = substr($data_not_appr['tgl_disetujui'], 0, 11);
      $tampungDataJamUpload_tdkDiAppr[]     = substr($data_not_appr['tgl_dibuat'], 11, 19);
      $tampungDataJamTdkDisetujui[]         = substr($data_not_appr['tgl_disetujui'], 11, 19);
      $tampungDataImage_tdkDiAppr[]         = $data_not_appr['foto'];
      $tampungDataJudul_tdkDiAppr[]         = $data_not_appr['judul'];
      $tampungDataIsi_tdkDiAppr[]           = $data_not_appr['isi_daily'];
      $tampungDataIsiAlasan_tdkDiAppr[]     = $data_not_appr['isi_alasan'];

    }

    $data5NotAppr = count($tampungDataJudul_tdkDiAppr);

    if ($data5NotAppr > 5) {

      for ($i = 0; $i < 5; $i++) { 

        $explode               = explode(" ", $tampungDataPengirim_tdkDiAppr[$i]);
        $isiPesanNamaSiswa     = "";
        $isiPesan              = "";
        
        $semuaNisOrIdGroup  = $tampungDataNisOrIdGroup_tdkDiAppr[$i];
        $semuaNamaSiswa     = strlen($tampungDataSiswa_tdkDiAppr[$i]) > 13 ? $isiPesanNamaSiswa .= substr($tampungDataSiswa_tdkDiAppr[$i], 0, 13) . " ..." : $tampungDataSiswa_tdkDiAppr[$i];
        $semuaPesan         = strlen($tampungDataJudul_tdkDiAppr[$i]) > 15 ? $isiPesan .= substr($tampungDataJudul_tdkDiAppr[$i], 0, 15) . " ..." : $tampungDataJudul_tdkDiAppr[$i];

        // Check Nis Or Id Group
        $queryCheckNis = mysqli_query($con, "
          SELECT nama FROM siswa
          WHERE nis = '$semuaNisOrIdGroup'
        ");

        $queryCheckIdGroup = mysqli_query($con, "
          SELECT nama_group_kelas FROM group_kelas
          WHERE id = '$semuaNisOrIdGroup'
        ");

        $countCheckNis      = mysqli_num_rows($queryCheckNis);
        $countCheckIdGroup  = mysqli_num_rows($queryCheckIdGroup);

        if ($countCheckNis == 1) {

          $forIsiNotifTdkDiAppr .= '
            <li class="notapprlist" data-group_or_std="'. std .'" data-reason_daily="'. $tampungDataIsiAlasan_tdkDiAppr[$i] .'" data-siswa_not_appr="'. $tampungDataSiswa_tdkDiAppr[$i] .'" data-tgl_noapproved ="'. tgl_indo($tampungDataTglTdkDisetujui[$i]) . " " . $tampungDataJamTdkDisetujui[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_tdkDiAppr[$i]) . " " . $tampungDataJamUpload_tdkDiAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage_tdkDiAppr[$i] .'" data-judul="'. $tampungDataJudul_tdkDiAppr[$i] .'" data-isian="'. $tampungDataIsi_tdkDiAppr[$i] .'" data-toggle="modal">
              <a href="#">
                <h4 style="font-size: 12px;">
                  <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglTdkDisetujui[$i]." ".$tampungDataJamTdkDisetujui[$i]) . '</small>
                </h4>
                <div class="pull-left" style="margin-left: 10px; margin-top: 15px;">
                  <img src="../imgstatis/logo2.png" style="width: 35px;">
                </div>
                <h4 style="font-size: 12px;">
                  <p style="font-size: 15px;margin-left: 60px; margin-top: 22px;"> 
                  <strong> SISWA </strong> <span style="margin-left: 6.5px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                      '. $semuaNamaSiswa .'
                    </strong> 
                  </p>
                </h4>
                <h4 style="font-size: 12px;">
                  <p style="font-size: 15px;margin-left: 60px;"> 
                    <strong> JUDUL </strong> <span style="margin-left: 4px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                      '. $semuaPesan .'
                    </strong> 
                  </p>
                </h4>
              </a>
            </li>
          ';

        } else if ($countCheckIdGroup == 1) {

          $forIsiNotifTdkDiAppr .= '
            <li class="notapprlist" data-group_or_std="'. group .'" data-reason_daily="'. $tampungDataIsiAlasan_tdkDiAppr[$i] .'" data-siswa_not_appr="'. $tampungDataSiswa_tdkDiAppr[$i] .'" data-tgl_noapproved ="'. tgl_indo($tampungDataTglTdkDisetujui[$i]) . " " . $tampungDataJamTdkDisetujui[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_tdkDiAppr[$i]) . " " . $tampungDataJamUpload_tdkDiAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage_tdkDiAppr[$i] .'" data-judul="'. $tampungDataJudul_tdkDiAppr[$i] .'" data-isian="'. $tampungDataIsi_tdkDiAppr[$i] .'" data-toggle="modal">
              <a href="#">
                <h4 style="font-size: 12px;">
                  <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglTdkDisetujui[$i]." ".$tampungDataJamTdkDisetujui[$i]) . '</small>
                </h4>
                <div class="pull-left" style="margin-left: 10px; margin-top: 15px;">
                  <img src="../imgstatis/logo2.png" style="width: 35px;">
                </div>
                <h4 style="font-size: 12px;">
                  <p style="font-size: 15px;margin-left: 60px; margin-top: 22px;"> 
                  <strong> GROUP </strong> <span style="margin-left: 6.5px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                      '. $semuaNamaSiswa .'
                    </strong> 
                  </p>
                </h4>
                <h4 style="font-size: 12px;">
                  <p style="font-size: 15px;margin-left: 60px;"> 
                    <strong> JUDUL </strong> <span style="margin-left: 9.5px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                      '. $semuaPesan .'
                    </strong> 
                  </p>
                </h4>
              </a>
            </li>
          ';

        }

      }

    } else if ($data5NotAppr < 6) {

      for ($i = 0; $i < count($tampungDataJudul_tdkDiAppr); $i++) { 

        $explode               = explode(" ", $tampungDataPengirim_tdkDiAppr[$i]);
        $isiPesanNamaSiswa     = "";
        $isiPesan              = "";
        
        $semuaNisOrIdGroup  = $tampungDataNisOrIdGroup_tdkDiAppr[$i];
        $semuaNamaSiswa     = strlen($tampungDataSiswa_tdkDiAppr[$i]) > 13 ? $isiPesanNamaSiswa .= substr($tampungDataSiswa_tdkDiAppr[$i], 0, 13) . " ..." : $tampungDataSiswa_tdkDiAppr[$i];
        $semuaPesan         = strlen($tampungDataJudul_tdkDiAppr[$i]) > 15 ? $isiPesan .= substr($tampungDataJudul_tdkDiAppr[$i], 0, 15) . " ..." : $tampungDataJudul_tdkDiAppr[$i];

        // Check Nis Or Id Group
        $queryCheckNis = mysqli_query($con, "
          SELECT nama FROM siswa
          WHERE nis = '$semuaNisOrIdGroup'
        ");

        $queryCheckIdGroup = mysqli_query($con, "
          SELECT nama_group_kelas FROM group_kelas
          WHERE id = '$semuaNisOrIdGroup'
        ");

        $countCheckNis      = mysqli_num_rows($queryCheckNis);
        $countCheckIdGroup  = mysqli_num_rows($queryCheckIdGroup);

        if ($countCheckNis == 1) {

          $forIsiNotifTdkDiAppr .= '
            <li class="notapprlist" data-group_or_std="'. std .'" data-reason_daily="'. $tampungDataIsiAlasan_tdkDiAppr[$i] .'" data-siswa_not_appr="'. $tampungDataSiswa_tdkDiAppr[$i] .'" data-tgl_noapproved ="'. tgl_indo($tampungDataTglTdkDisetujui[$i]) . " " . $tampungDataJamTdkDisetujui[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_tdkDiAppr[$i]) . " " . $tampungDataJamUpload_tdkDiAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage_tdkDiAppr[$i] .'" data-judul="'. $tampungDataJudul_tdkDiAppr[$i] .'" data-isian="'. $tampungDataIsi_tdkDiAppr[$i] .'" data-toggle="modal">
              <a href="#">
                <h4 style="font-size: 12px;">
                  <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglTdkDisetujui[$i]." ".$tampungDataJamTdkDisetujui[$i]) . '</small>
                </h4>
                <div class="pull-left" style="margin-left: 10px; margin-top: 15px;">
                  <img src="../imgstatis/logo2.png" style="width: 35px;">
                </div>
                <h4 style="font-size: 12px;">
                  <p style="font-size: 15px;margin-left: 60px; margin-top: 22px;"> 
                  <strong> SISWA </strong> <span style="margin-left: 11px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                      '. $semuaNamaSiswa .'
                    </strong> 
                  </p>
                </h4>
                <h4 style="font-size: 12px;">
                  <p style="font-size: 15px;margin-left: 60px;"> 
                    <strong> JUDUL </strong> <span style="margin-left: 8px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                      '. $semuaPesan .'
                    </strong> 
                  </p>
                </h4>
              </a>
            </li>
          ';

        } else if ($countCheckIdGroup == 1) {

          $forIsiNotifTdkDiAppr .= '
            <li class="notapprlist" data-group_or_std="'. group .'" data-reason_daily="'. $tampungDataIsiAlasan_tdkDiAppr[$i] .'" data-siswa_not_appr="'. $tampungDataSiswa_tdkDiAppr[$i] .'" data-tgl_noapproved ="'. tgl_indo($tampungDataTglTdkDisetujui[$i]) . " " . $tampungDataJamTdkDisetujui[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_tdkDiAppr[$i]) . " " . $tampungDataJamUpload_tdkDiAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage_tdkDiAppr[$i] .'" data-judul="'. $tampungDataJudul_tdkDiAppr[$i] .'" data-isian="'. $tampungDataIsi_tdkDiAppr[$i] .'" data-toggle="modal">
              <a href="#">
                <h4 style="font-size: 12px;">
                  <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglTdkDisetujui[$i]." ".$tampungDataJamTdkDisetujui[$i]) . '</small>
                </h4>
                <div class="pull-left" style="margin-left: 10px; margin-top: 15px;">
                  <img src="../imgstatis/logo2.png" style="width: 35px;">
                </div>
                <h4 style="font-size: 12px;">
                  <p style="font-size: 15px;margin-left: 60px; margin-top: 22px;"> 
                  <strong> GROUP </strong> <span style="margin-left: 6.5px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                      '. $semuaNamaSiswa .'
                    </strong> 
                  </p>
                </h4>
                <h4 style="font-size: 12px;">
                  <p style="font-size: 15px;margin-left: 60px;"> 
                    <strong> JUDUL </strong> <span style="margin-left: 9.5px;"> : </span> 
                    <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                      '. $semuaPesan .'
                    </strong> 
                  </p>
                </h4>
              </a>
            </li>
          ';

        }

      }

    }

    $countDataNotApproved     = mysqli_num_rows($queryNotApproved);
    $countDataNotApprovedAll  = mysqli_num_rows($queryNotApprovedAll);

  // Akhir Data Tidak Di Approve
  ############################################################################################################

  $arr['notif_appr']              = $countDataApproved;
  $arr['notif_appr_all']          = $countDataApprovedAll;
  $arr['notif_not_appr']          = $countDataNotApproved;
  $arr['notif_not_appr_all']      = $countDataNotApprovedAll;

	$arr['display_html']            = $getDataMainDaily['isi_daily'];
	$arr['waiting_list'] 	          = $countDataWaitAppr;
  $arr['wt_list_all']             = $countDataWaitApprAll;
  $arr['isi_waiting_list']        = $forIsiNotifBlmAppr;
  $arr['upload_img']              = $forImage;
  $arr['tees']                    = $tampungDataNIP_sdhAppr;
  $arr['elemNotifWait']           = $elementNotifWaiting;

  // Isi notif sudah di approve
  $arr['isi_notif_approved']      = $forIsiNotifSdhAppr;
  $arr['elementNotifAppr']        = $elementNotifAppr;
  $arr['tgl_ori']                 = $tampungDataTglUploadOri;
  // Akhir isi notif sudah di approve

  // Isi Notif tidak di approve
  $arr['isi_notif_not_approved']  = $forIsiNotifTdkDiAppr;
  // Akhir isi notif tidak di approve

  // Message Chat
  $arr['dari_orang_Lain']         = $getDataAll['pengirim_pesan'];
  $arr['dari_saya']               = $getDataSelf['nama_guru'];
  $arr['count']                   = $count;
  $arr['isi_chat']                = $arrOther;
  $arr['dari_chat']               = $fromAll;
  $arr['isi_chatx']               = $arrMe;
  $arr['dari_chatx']              = $fromMe;
  // End Message Chat

	echo json_encode($arr);

	exit;

?>