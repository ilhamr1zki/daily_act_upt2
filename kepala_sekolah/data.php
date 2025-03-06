<?php  

  require '../php/config.php'; 
  require '../php/function.php'; 

  error_reporting(0);  

  // Cek status login user jika tidak ada session
  if (!$user->isLoggedInHeadMaster()) { 

    header("location:../"); //Redirect ke halaman login  
  }

  $getDataBagian  = $_SESSION['c_kepsek_paud'];

  $is_SD      = "/SD/i";
  $is_PAUD    = "/PAUD/i";
  $sd         = false;
  $paud       = false;
  $byTeacher  = "";

  $thisNumberPhoneTeacher = "";
  $thisNumberPhoneOTM     = "";

  $isValidNumber = true;

  $foundDataSD    = preg_match($is_SD, $getDataBagian);
  $foundDataPAUD  = preg_match($is_PAUD, $getDataBagian);

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

  $arr                  = [];
  $status_approve       = "";
  $forImage             = '';
  $forIsiNotifBlmAppr   = "";
  $forIsiNotifSdhAppr   = ""; 
  $forIsiNotifTdkDiAppr = "";
  $isiStatusDaily       = "";
  $isiPesan             = '';

  $execQueryAppr        = '';

  $dataFound            = '';

  $tampungNomerHP = [];
  $tampungNoHpOtm = [];

  // Array Penampung Data Belum di Approve
  $tampungDataID_blmAppr            = [];
  $tampungDataNIP_blmAppr           = [];
  $tampungDataNisOrIdGroup_blmAppr  = [];
  $tampungDataPengirim_blmAppr      = [];
  $tampungDataSiswa_blmAppr         = [];
  $tampungDataSiswa_sdhAppr         = [];
  $tampungDataTglUpload_blmAppr     = [];
  $tampungDataJamUpload_blmAppr     = [];
  $tampungDataImage_blmAppr         = [];
  $tampungDataJudul_blmAppr         = [];
  $tampungDataIsi_blmAppr           = [];
  $elementNotifWaiting              = '';
  // Akhir Array Penampung Data Belum di Approve

  // Array Penampung Data Sudah di Approve
  $tampungDataTglUploadOri          = [];
  $tampungDataRoomKey               = [];
  $tampungDataID_sdhAppr            = [];
  $tampungDataNisOrIdGroup_sdhAppr  = [];
  $tampungDataNIP_sdhAppr           = [];
  $tampungDataGuru_sdhAppr          = [];
  $tampungDataPengirim_sdhAppr      = [];
  $tampungDataTglDisetujui          = [];
  $tampungDataTglUpload_sdhAppr     = [];
  $tampungDataJamUpload_sdhAppr     = [];
  $tampungDataJamDisetujui          = [];
  $tampungDataImage_sdhAppr         = [];
  $tampungDataNis_siswa_sdhAppr     = [];
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
  $elementNotifNotAppr                = '';
  // Akhir Array Penampung Data Tidak di Approve

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

  if (isset($_POST['room_id'])) {
        
    $room_id      = $_POST['room_id'];
    $usr_kepsek   = $_POST['users'];
    $usr_guru     = $_POST['usr_guru'];
    $usr_otm      = $_POST['usr_otm'];

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
      guru.nama as nama_guru,
      kepala_sekolah.nama as nama_kepsek
      FROM 
      ruang_pesan
      LEFT JOIN tbl_komentar
      ON tbl_komentar.room_id = ruang_pesan.room_key
      LEFT JOIN guru
      ON tbl_komentar.code_user = guru.nip
      LEFT JOIN kepala_sekolah
      ON ruang_pesan.created_by = kepala_sekolah.nip
      LEFT JOIN akses_otm
      ON tbl_komentar.code_user = akses_otm.nis_siswa
      LEFT JOIN siswa
      ON akses_otm.nis_siswa = siswa.nis
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

      if ($all_data['fromnip'] == $usr_kepsek) {
        
        $pesanSemua[] = '
        <div class="direct-chat-msg right">
          <div class="direct-chat-info clearfix">
            <span class="direct-chat-name pull-right">'. $all_data['nama_kepsek'] .'</span>
            <span class="direct-chat-timestamp pull-left">'. format_tgl_indo($all_data['tanggal_kirim']) . '</span>
          </div>
          <img class="direct-chat-img" src="'. $base . 'theme/dist/img/icon_chat.png'. '" alt="Message User Image">
          <div class="direct-chat-text">'. htmlspecialchars($all_data['pesan']) .'</div>
          </div>
        </div>';

      } else if ($all_data['fromnip'] == $usr_guru) {
      
        $pesanSemua[] = '
          <div class="direct-chat-msg">
            <div class="direct-chat-info clearfix">
              <span class="direct-chat-name pull-left">'. $all_data['nama_guru'] .'</span>
              <span class="direct-chat-timestamp pull-right">'. format_tgl_indo($all_data['tanggal_kirim']) .'</span>
            </div>
            <img class="direct-chat-img" src="'. $base . 'theme/dist/img/icon_chat.png'. '" alt="Message User Image">
            <div class="direct-chat-text">'. htmlspecialchars($all_data['pesan']) .'</div>
          </div>';
      
      } else if ($all_data['fromnip'] == $usr_otm) {

        $pesanSemua[] = '
          <div class="direct-chat-msg">
            <div class="direct-chat-info clearfix">
              <span class="direct-chat-name pull-left">'. $all_data['nama_siswa'] . ' (Wali Murid)' .'</span>
              <span class="direct-chat-timestamp pull-right">'. format_tgl_indo($all_data['tanggal_kirim']) .'</span>
            </div>
            <img class="direct-chat-img" src="'. $base . 'theme/dist/img/icon_chat.png'. '" alt="Message User Image">
            <div class="direct-chat-text">'. htmlspecialchars($all_data['pesan']) .'</div>
          </div>';

      }

    }

    foreach ($getDataFrom_MySelf as $myself) {
      $fromMe[]  = $myself['nama_kepsek'];
      $pesanSaya[] = '
        <div class="direct-chat-msg right">
          <div class="direct-chat-info clearfix">
            <span class="direct-chat-name pull-right">'. $myself['nama_kepsek'] .'</span>
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

  // Lookdaily Teacher
  if (isset($_POST['is_teacher'])) {
    $arr['is_page']       = 'is_teacher';

    $dataID     = htmlspecialchars($_POST['id_daily']);
    $dataNIP    = htmlspecialchars($_POST['nip']);
    $dataNIS    = htmlspecialchars($_POST['nis']);

    $checkDataADM   = mysqli_query($con, "SELECT c_admin FROM admin WHERE c_admin = '$dataNIP' ");
    $checkDataGuru  = mysqli_query($con, "SELECT nama FROM guru WHERE nip = '$dataNIP' ");

    $countADM       = mysqli_num_rows($checkDataADM);
    $countGuru      = mysqli_num_rows($checkDataADM);

    // check jika nip adalah admin
    if ($countADM == 1) {

      // Cari Data Approved berdasarkan nis siswa
      $queryGetApproved = mysqli_query($con, "
        SELECT 
        from_nip as from_nip,
        nis_siswa as nis_siswa,
        siswa.nama as nama_siswa,
        admin.username as nama_user,
        isi_daily as isi_daily
        from daily_siswa_approved
        left join admin
        on daily_siswa_approved.from_nip = admin.c_admin
        left join siswa
        on daily_siswa_approved.nis_siswa = siswa.nis
        where 
        daily_siswa_approved.id = '$dataID'
        AND
        daily_siswa_approved.status_approve = 1 
        AND 
        daily_siswa_approved.from_nip = '$dataNIP'
      ");

      $getDataIsiDaily  = mysqli_fetch_array($queryGetApproved)['isi_daily'];
      $getDataCreatedBy = mysqli_fetch_array($queryGetApproved)['nama_user'];

      $arr['created_by']    = $getDataCreatedBy;
      $arr['display_html']  = $getDataIsiDaily;

    }

  } 

  // Jika daily di approve
  else if (isset($_POST['daily_id']) ) {

    $dailyId = $_POST['daily_id'];
    $nip     = $_POST['nip_guru'];

    $queryGetNumberHeadMaster = mysqli_query($con, "
      SELECT no_hp FROM guru WHERE nip = '$_SESSION[nip_kepsek_paud]'
    ");

    $getNumberHeadMaster = mysqli_fetch_array($queryGetNumberHeadMaster)['no_hp'];

    $queryFindTeacher = mysqli_query($con, "
      SELECT nama FROM guru WHERE nip = '$nip'
    ");

    $byTeacher = mysqli_fetch_array($queryFindTeacher)['nama'];

    date_default_timezone_set("Asia/Jakarta");

    $tglSkrng       = date("Y-m-d H:i:s");

    // Cari daily Id apakah daily std atau group
    $queryFindDailyIdStd = mysqli_query($con, "
      SELECT id FROM daily_siswa_approved WHERE id = '$dailyId'
    ");

    $queryFindDailyIdGroup = mysqli_query($con, "
      SELECT id FROM group_siswa_approved WHERE id = '$dailyId'
    ");

    $countDailyIdStd    = mysqli_num_rows($queryFindDailyIdStd);
    $countDailyIdGroup  = mysqli_num_rows($queryFindDailyIdGroup);

    if ($countDailyIdStd == 1) {

      $execQueryAppr      = mysqli_query($con, "
        UPDATE daily_siswa_approved 
        SET 
        status_approve    = '1',
        tanggal_disetujui_atau_tidak = '$tglSkrng'
        WHERE daily_siswa_approved.id = '$dailyId'
      ");

      $randomString = random(9);

      $queryGetStudentName  = mysqli_query($con, "
        SELECT nama FROM siswa WHERE nis IN (
          SELECT nis_siswa FROM daily_siswa_approved WHERE id = '$dailyId'
        ) 
      ");

      $getStudentName     = mysqli_fetch_array($queryGetStudentName)['nama'];

      $queryGetNumberPhoneParents = mysqli_query($con, "
        SELECT no_hp FROM akses_otm WHERE nis_siswa IN (
          SELECT nis_siswa FROM daily_siswa_approved WHERE id = '$dailyId'
        )
      ");

      $getNumberPhoneParent = mysqli_fetch_array($queryGetNumberPhoneParents)['no_hp'];

      $numberPhoneHeadmasterAndParent     = [];
      $numberPhoneHeadmasterAndParent[]   = $getNumberHeadMaster;
      $numberPhoneHeadmasterAndParent[]   = $getNumberPhoneParent;

      $destination_number = implode(",", $numberPhoneHeadmasterAndParent);

      $createRoomChat = mysqli_query($con, "
        INSERT INTO ruang_pesan
        SET
        room_key          = '$randomString',
        created_by        = '$nip',
        room_session      = 1,
        daily_id          = '$dailyId',
        created_date_room = '$tglSkrng'
      ");

      // Baris 402 Nyalakan jika sudah siap dengan fonnte
      // if ($execQueryAppr == true && $createRoomChat == true) {

      //   // Kirim Notif Ke Guru Yang Upload Daily dengan Nomer Fonnte Kepsek beserta Account token nya yang ada di menu setting di wwebsite fonnte
      //   $curl_ke_guru = curl_init();

      //   $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

      //   // Get Room Key 
      //   $queryFindRoomKey = mysqli_query($con, "
      //     SELECT room_key FROM ruang_pesan WHERE daily_id IN (
      //       SELECT id FROM daily_siswa_approved WHERE id = '$dailyId'
      //     )
      //   ");

      //   $isRoomKey = mysqli_fetch_assoc($queryFindRoomKey)['room_key'];

      //   $queryGetNumberTeacher = mysqli_query($con, "
      //     SELECT no_hp FROM guru WHERE nip = '$nip'
      //   ");

      //   $thisNumberPhoneTeacher = mysqli_fetch_array($queryGetNumberTeacher)['no_hp'];

      //   // Yang akan di kirimkan notif daily siswa, nomer Guru yang upload daily tersebut
      //   $target = $thisNumberPhoneTeacher;
      //   $pesan  = "*DAILY SISWA _". strtoupper($getStudentName) ."_ YANG ANDA BUAT TELAH DI APPROVE ✅ OLEH KEPALA SEKOLAH*" . "\n" . "\n" . $base . $isRoomKey. "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

      //   curl_setopt_array($curl_ke_guru, array(
      //     CURLOPT_URL => 'https://api.fonnte.com/send',
      //     CURLOPT_RETURNTRANSFER => true,
      //     CURLOPT_ENCODING => '',
      //     CURLOPT_MAXREDIRS => 10,
      //     CURLOPT_TIMEOUT => 0,
      //     CURLOPT_FOLLOWLOCATION => true,
      //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      //     CURLOPT_CUSTOMREQUEST => 'POST',
      //     CURLOPT_POSTFIELDS => array(
      //       'target' => $target,
      //       'message' => $pesan
      //     ),
      //     CURLOPT_HTTPHEADER => array(
      //       'Authorization:a3vjVjL3S6xHpDg7NiaE' //change TOKEN to your actual token
      //     ),
      //   ));

      //   $response_ke_guru = curl_exec($curl_ke_guru);

      //   if ($response_ke_guru == true) {

      //       $curlheadmaster_andparents = curl_init();

      //       $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

      //       // Yang akan di kirimkan notif daily siswa, nomer Kepsek Sesuai Divisi dan orang tua murid yang di buat daily nya oleh guru
      //       $target = $destination_number;
      //       $pesan  = "*ADA NOTIF BARU DAILY ACTIVITY YANG BELUM DI BACA !*". "\n" . "\n" . $base . $isRoomKey. "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

      //       curl_setopt_array($curlheadmaster_andparents, array(
      //         CURLOPT_URL => 'https://api.fonnte.com/send',
      //         CURLOPT_RETURNTRANSFER => true,
      //         CURLOPT_ENCODING => '',
      //         CURLOPT_MAXREDIRS => 10,
      //         CURLOPT_TIMEOUT => 0,
      //         CURLOPT_FOLLOWLOCATION => true,
      //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      //         CURLOPT_CUSTOMREQUEST => 'POST',
      //         CURLOPT_POSTFIELDS => array(
      //           'target' => $target,
      //           'message' => $pesan
      //         ),
      //         CURLOPT_HTTPHEADER => array(
      //           'Authorization:v5UjWfsmUcB1SQMBeyxR' //change TOKEN to your actual token
      //         ),
      //       ));

      //       $responseheadmaster_andparents = curl_exec($curlheadmaster_andparents);

      //       if ($responseheadmaster_andparents) {
      //         $arr['status_approve'] = true;
      //       }

      //       curl_close($curlheadmaster_andparents);
      //   }

      //   curl_close($curl_ke_guru);

      // } else {

      //   $arr['status_approve'] = false;

      // }

      if ($execQueryAppr == true && $createRoomChat == true) {
        $arr['status_approve'] = true;
      } else {
        $arr['status_approve'] = false;
      }

    } else if ($countDailyIdGroup == 1) {

      $getNumberTeacher = mysqli_query($con, "
        SELECT no_hp FROM guru WHERE nip = '$nip'
      ");

      $getDataGroupKelasID = mysqli_query($con, "
        SELECT group_kelas_id FROM group_siswa_approved WHERE id = '$dailyId'
      ");

      $isGroupKelasID = mysqli_fetch_array($getDataGroupKelasID)['group_kelas_id'];

      $getAllNumberOTM = mysqli_query($con, "
        SELECT no_hp FROM akses_otm
        WHERE nis_siswa IN (
          SELECT nis FROM siswa
          WHERE group_kelas = '$isGroupKelasID'
        )
      ");

      $numberPhoneTeacher = mysqli_fetch_array($getNumberTeacher)['no_hp'];
      $changeFormatNumberPhoneTeacher = substr($numberPhoneTeacher, 0, 2);

      if ($changeFormatNumberPhoneTeacher == '08') {
        $isValidNumber = true;
        $change1 = substr($numberPhoneTeacher, 2, 12);
        $thisNumberPhoneTeacher = "628" . $change1;

        // findNameGroupForNotif
        $queryFindNameGroup = mysqli_query($con, "
          SELECT nama_group_kelas FROM group_kelas 
          WHERE id IN (
            SELECT group_kelas_id FROM group_siswa_approved WHERE id = '$dailyId'
          )
        ");

        $getNameGroup = mysqli_fetch_assoc($queryFindNameGroup)['nama_group_kelas'];

        $tampungNoHP = [];

        foreach ($getAllNumberOTM as $data) {
          $tampungNoHP[] = $data['no_hp'];
        }

        for ($i=0; $i < count($tampungNoHP); $i++) { 

          $noHp = substr($tampungNoHP[$i], 0, 2);

          if ($noHp == '08') {
            $change2 = substr($tampungNoHP[$i], 2, 12);
            $thisNumberPhoneOTM = "628" . $change2;
            $tampungNoHpOtm[] = $thisNumberPhoneOTM;
          }

        }

        $destination_numbergroupotm = implode(',', $tampungNoHpOtm);

        // echo $destination_numbergroupotm;exit;

        // echo $getNameGroup;exit;

        $execQueryAppr      = mysqli_query($con, "
          UPDATE group_siswa_approved 
          SET 
          status_approve    = '1',
          tanggal_disetujui_atau_tidak = '$tglSkrng'
          WHERE group_siswa_approved.id = '$dailyId'
        ");

        $randomString = random(9);

        $createRoomChat = mysqli_query($con, "
          INSERT INTO ruang_pesan
          SET
          room_key          = '$randomString',
          created_by        = '$nip',
          room_session      = 1,
          daily_id          = '$dailyId',
          created_date_room = '$tglSkrng'
        ");

        if ($execQueryAppr == true && $createRoomChat == true) {

          // Kirim Notif Ke Guru Yang Upload Daily dan OTM sesuai group kelas dengan Nomer Fonnte Kepsek beserta Account token nya yang ada di menu setting di wwebsite fonnte
          $curl_ke_guru = curl_init();

          $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

          // Get Room Key 
          $queryFindRoomKey = mysqli_query($con, "
            SELECT room_key FROM ruang_pesan WHERE created_date_room IN (
              SELECT tanggal_disetujui_atau_tidak FROM group_siswa_approved WHERE id = '$dailyId'
            )
          ");

          $isRoomKey = mysqli_fetch_assoc($queryFindRoomKey)['room_key'];

          // Yang akan di kirimkan notif group, nomer Guru yang upload daily tersebut
          $target = $thisNumberPhoneTeacher;
          $pesan  = "*DAILY GROUP _". $getNameGroup ."_ YANG ANDA BUAT TELAH DI APPROVE ✅ OLEH KEPALA SEKOLAH*" . "\n" . "\n" . $base . $isRoomKey. "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

          curl_setopt_array($curl_ke_guru, array(
            CURLOPT_URL => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
              'target' => $target,
              'message' => $pesan,
              'delay' => '3-5'
            ),
            CURLOPT_HTTPHEADER => array(
              'Authorization:a3vjVjL3S6xHpDg7NiaE' //change TOKEN to your actual token
            ),
          ));

          $response_ke_guru = curl_exec($curl_ke_guru);

          if ($response_ke_guru) {

            $curl_otm = curl_init();

            $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

            // Yang akan di kirimkan notif group, nomer Group Kelas OTM
            $target = $destination_numbergroupotm;
            // $target = "6289515998565,62895334303884,6288212660849,62895329874975";
            $pesan  = "*ADA NOTIF BARU DAILY ACTIVITY YANG BELUM DI BACA !*". "\n" . "\n" . $base . $isRoomKey. "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

            curl_setopt_array($curl_otm, array(
              CURLOPT_URL => 'https://api.fonnte.com/send',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $pesan,
                'delay' => '3-5'
              ),
              CURLOPT_HTTPHEADER => array(
                'Authorization:a3vjVjL3S6xHpDg7NiaE' //change TOKEN to your actual token
              ),
            ));

            $response_otm = curl_exec($curl_otm);

            if ($response_otm) {
              $curl_kepsek = curl_init();

              $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

              // Yang akan di kirimkan notif group, nomer Kepsek Sesuai Divisi
              $target = $getNumberHeadMaster;
              $pesan  = "*ADA NOTIF BARU DAILY ACTIVITY YANG BELUM DI BACA !*". "\n" . "\n" . $base . $isRoomKey. "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

              curl_setopt_array($curl_kepsek, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                  'target' => $target,
                  'message' => $pesan,
                  'delay' => '3-5'
                ),
                CURLOPT_HTTPHEADER => array(
                  'Authorization:a3vjVjL3S6xHpDg7NiaE' //change TOKEN to your actual token
                ),
              ));

              $response_kepsek = curl_exec($curl_kepsek);

              if ($response_kepsek) {
                $arr['status_approve'] = true;
              }

              curl_close($curl_kepsek);

            }

            curl_close($curl_otm);

          }

          curl_close($curl_ke_guru);

        } else {
          $arr['status_approve'] = false;
        }

      } else {

        $isValidNumber = false;
        $change1 = substr($numberPhoneTeacher, 2, 12);
        $thisNumberPhoneTeacher = $change1;

      }


    }

  }

  // Jika daily di tolak
  else if (isset($_POST['daily_id_not_appr'])) {

    $idGroupOrNis = $_POST['daily_id_not_appr'];

    $reason  = mysqli_real_escape_string($con, htmlspecialchars($_POST['is_reason'])) . "empty_val";
    $nip     = htmlspecialchars($_POST['nipguru']);

    date_default_timezone_set("Asia/Jakarta");

    $tglSkrng       = date("Y-m-d H:i:s");

    // Check apakah daily id berupa id group kelas atau nis siswa
    $queryCheckGroupID = mysqli_query($con, "
      SELECT group_kelas_id FROM group_siswa_approved WHERE id = '$idGroupOrNis'
    ");

    $queryCheckNis_siswa = mysqli_query($con, "
      SELECT nis_siswa FROM daily_siswa_approved WHERE id = '$idGroupOrNis'
    ");

    // findNameGroupForNotif
    $queryFindNameGroup = mysqli_query($con, "
      SELECT nama_group_kelas FROM group_kelas 
      WHERE id IN (
        SELECT group_kelas_id FROM group_siswa_approved WHERE id = '$idGroupOrNis'
      )
    ");

    $getNameGroup = mysqli_fetch_assoc($queryFindNameGroup)['nama_group_kelas'];

    $countIdGroup   = mysqli_num_rows($queryCheckGroupID);
    $countNis_siswa = mysqli_num_rows($queryCheckNis_siswa);

    // echo $countNis_siswa;exit;

    if ($countIdGroup == 1) {

      $execQueryNotAppr      = mysqli_query($con, "
        UPDATE group_siswa_approved 
        SET 
        status_approve    = '2',
        tanggal_disetujui_atau_tidak = '$tglSkrng'
        WHERE group_siswa_approved.id = '$idGroupOrNis'
      ");

      if ($execQueryNotAppr == true) {

        if ($reason == "empty_val") {

          $execQueryInsertReason = mysqli_query($con, "
            INSERT INTO reason 
            SET
            is_reason = 'no_comment',
            daily_siswa_id = '$idGroupOrNis'
          ");

          if ($execQueryNotAppr == true && $execQueryInsertReason == true) {

            // Kirim Notif Ke Guru Yang Upload Daily dengan Nomer Fonnte Kepsek beserta Account token nya yang ada di menu setting di wwebsite fonnte
            $curl = curl_init();

            $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

            // Get Room Key 
            $queryFindRoomKey = mysqli_query($con, "
              SELECT room_key FROM ruang_pesan WHERE daily_id IN (
                SELECT id FROM daily_siswa_approved WHERE id = '$idGroupOrNis'
              )
            ");

            $isRoomKey = mysqli_fetch_assoc($queryFindRoomKey)['room_key'];

            $queryGetNumberTeacher = mysqli_query($con, "
              SELECT no_hp FROM guru WHERE nip = '$nip'
            ");

            $thisNumberPhoneTeacher = mysqli_fetch_array($queryGetNumberTeacher)['no_hp'];

            // Yang akan di kirimkan notif group, nomer Guru yang upload daily tersebut
            $target = $thisNumberPhoneTeacher;
            $pesan  = "*DAILY GROUP _". strtoupper($getNameGroup) ."_ YANG ANDA BUAT TIDAK DI APPROVE ❌ OLEH KEPALA SEKOLAH*" . "\n" . "\n" . $basegu . "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.fonnte.com/send',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $pesan
              ),
              CURLOPT_HTTPHEADER => array(
                'Authorization:a3vjVjL3S6xHpDg7NiaE' //change TOKEN to your actual token
              ),
            ));

            $response = curl_exec($curl);

            if ($response) {
              $arr['status_not_approve'] = true;
            } else {
              $arr['status_not_approve'] = false;
            }

            curl_close($curl);

          } else {
            $arr['status_not_approve'] = false;
          }

        } else if ($reason != "empty_val") {

          $removeStr = str_replace(["empty_val"], "", $reason);

          $execQueryInsertReason = mysqli_query($con, "
            INSERT INTO reason 
            SET
            is_reason = '$removeStr',
            daily_siswa_id = '$idGroupOrNis'
          ");

          if ($execQueryNotAppr == true && $execQueryInsertReason == true) {

            // Kirim Notif Ke Guru Yang Upload Daily dengan Nomer Fonnte Kepsek beserta Account token nya yang ada di menu setting di wwebsite fonnte
            $curl = curl_init();

            $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

            // Get Room Key 
            $queryFindRoomKey = mysqli_query($con, "
              SELECT room_key FROM ruang_pesan WHERE daily_id IN (
                SELECT id FROM daily_siswa_approved WHERE id = '$idGroupOrNis'
              )
            ");

            $isRoomKey = mysqli_fetch_assoc($queryFindRoomKey)['room_key'];

            $queryGetNumberTeacher = mysqli_query($con, "
              SELECT no_hp FROM guru WHERE nip = '$nip'
            ");

            $thisNumberPhoneTeacher = mysqli_fetch_array($queryGetNumberTeacher)['no_hp'];

            // Yang akan di kirimkan notif group, nomer Guru yang upload daily tersebut
            $target = $thisNumberPhoneTeacher;
            $pesan  = "*DAILY GROUP _". strtoupper($getNameGroup) ."_ YANG ANDA BUAT TIDAK DI APPROVE ❌ OLEH KEPALA SEKOLAH*" . "\n" . "\n" . $basegu . "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.fonnte.com/send',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $pesan
              ),
              CURLOPT_HTTPHEADER => array(
                'Authorization:a3vjVjL3S6xHpDg7NiaE' //change TOKEN to your actual token
              ),
            ));

            $response = curl_exec($curl);

            if ($response) {
              $arr['status_not_approve'] = true;
            } else {
              $arr['status_not_approve'] = false;
            }

            curl_close($curl);

          } else {
            $arr['status_not_approve'] = false;
          }

        }

      } else {

        // echo "Gagal";
        $arr['status_not_approve'] = false;

      }

    } else if ($countNis_siswa == 1) {

      $execQueryNotAppr      = mysqli_query($con, "
        UPDATE daily_siswa_approved 
        SET 
        status_approve    = '2',
        tanggal_disetujui_atau_tidak = '$tglSkrng'
        WHERE daily_siswa_approved.id = '$idGroupOrNis'
      ");

      if ($execQueryNotAppr == true) {

        if ($reason == "empty_val") {

          $execQueryInsertReason = mysqli_query($con, "
            INSERT INTO reason 
            SET
            is_reason = 'no_comment',
            daily_siswa_id = '$idGroupOrNis'
          ");

          if ($execQueryNotAppr == true && $execQueryInsertReason == true) {

            $queryGetStudentName  = mysqli_query($con, "
              SELECT nama FROM siswa WHERE nis IN (
                SELECT nis_siswa FROM daily_siswa_approved WHERE id = '$idGroupOrNis'
              ) 
            ");

            $getStudentName     = mysqli_fetch_array($queryGetStudentName)['nama'];

            // Kirim Notif Ke Guru Yang Upload Daily dengan Nomer Fonnte Kepsek beserta Account token nya yang ada di menu setting di wwebsite fonnte
            $curl = curl_init();

            $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

            // Get Room Key 
            $queryFindRoomKey = mysqli_query($con, "
              SELECT room_key FROM ruang_pesan WHERE daily_id IN (
                SELECT id FROM daily_siswa_approved WHERE id = '$idGroupOrNis'
              )
            ");

            $isRoomKey = mysqli_fetch_assoc($queryFindRoomKey)['room_key'];

            $queryGetNumberTeacher = mysqli_query($con, "
              SELECT no_hp FROM guru WHERE nip = '$nip'
            ");

            $thisNumberPhoneTeacher = mysqli_fetch_array($queryGetNumberTeacher)['no_hp'];

            // Yang akan di kirimkan notif group, nomer Guru yang upload daily tersebut
            $target = $thisNumberPhoneTeacher;
            $pesan  = "*DAILY SISWA _". strtoupper($getStudentName) ."_ YANG ANDA BUAT TIDAK DI APPROVE ❌ OLEH KEPALA SEKOLAH*" . "\n" . "\n" . $basegu . "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.fonnte.com/send',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $pesan
              ),
              CURLOPT_HTTPHEADER => array(
                'Authorization:a3vjVjL3S6xHpDg7NiaE' //change TOKEN to your actual token
              ),
            ));

            $response = curl_exec($curl);

            if ($response) {
              $arr['status_not_approve'] = true;
            } else {
              $arr['status_not_approve'] = false;
            }

            curl_close($curl);

          } else {
            $arr['status_not_approve'] = false;
          }

        } else if ($reason != "empty_val") {

          $removeStr = str_replace(["empty_val"], "", $reason);

          $execQueryInsertReason = mysqli_query($con, "
            INSERT INTO reason 
            SET
            is_reason = '$removeStr',
            daily_siswa_id = '$idGroupOrNis'
          ");

          if ($execQueryNotAppr == true && $execQueryInsertReason == true) {

            $queryGetStudentName  = mysqli_query($con, "
              SELECT nama FROM siswa WHERE nis IN (
                SELECT nis_siswa FROM daily_siswa_approved WHERE id = '$idGroupOrNis'
              ) 
            ");

            $getStudentName     = mysqli_fetch_array($queryGetStudentName)['nama'];

            // Kirim Notif Ke Guru Yang Upload Daily dengan Nomer Fonnte Kepsek beserta Account token nya yang ada di menu setting di wwebsite fonnte
            $curl = curl_init();

            $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

            // Get Room Key 
            $queryFindRoomKey = mysqli_query($con, "
              SELECT room_key FROM ruang_pesan WHERE daily_id IN (
                SELECT id FROM daily_siswa_approved WHERE id = '$idGroupOrNis'
              )
            ");

            $isRoomKey = mysqli_fetch_assoc($queryFindRoomKey)['room_key'];

            $queryGetNumberTeacher = mysqli_query($con, "
              SELECT no_hp FROM guru WHERE nip = '$nip'
            ");

            $thisNumberPhoneTeacher = mysqli_fetch_array($queryGetNumberTeacher)['no_hp'];

            // Yang akan di kirimkan notif group, nomer Guru yang upload daily tersebut
            $target = $thisNumberPhoneTeacher;
            $pesan  = "*DAILY SISWA _". strtoupper($getStudentName) ."_ YANG ANDA BUAT TIDAK DI APPROVE ❌ OLEH KEPALA SEKOLAH*" . "\n" . "\n" . $basegu . "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://api.fonnte.com/send',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => array(
                'target' => $target,
                'message' => $pesan
              ),
              CURLOPT_HTTPHEADER => array(
                'Authorization:a3vjVjL3S6xHpDg7NiaE' //change TOKEN to your actual token
              ),
            ));

            $response = curl_exec($curl);

            if ($response) {
              $arr['status_not_approve'] = true;
            } else {
              $arr['status_not_approve'] = false;
            }

            curl_close($curl);

          } else {
            $arr['status_not_approve'] = false;
          }

        }

      } else {

        // echo "Gagal";
        $arr['status_not_approve'] = false;

      }

    }

  }

  $forImage .= '
    <div class="col-sm-4">
      <label for="exampleInputEmail1"> Upload Image Daily (*Tidak lebih dari 2 MB) </label>
      <input type="file" class="form-control fileGambar" name="banner" id="buat_banner">
      <img src="" id="gambar">
    </div>'
  ;

  if ($foundDataSD == 1) {

    $sd = true;

    ############################################################################################################
    // Data Belum Approve

      $queryNotYetAppr         = mysqli_query($con, "
        SELECT *
        FROM (
            SELECT 
              daily_siswa_approved.id as daily_id,
              daily_siswa_approved.departemen as departemen,
              daily_siswa_approved.from_nip as from_nip,
              guru.username as username_guru,
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
              group_siswa_approved.departemen as departemen,
              group_siswa_approved.from_nip as from_nip,
              guru.username as username_guru,
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
          AND U.departemen = 'SD'
          ORDER BY U.tgl_dibuat DESC
      ");

      foreach ($queryNotYetAppr as $not_yet_appr) {

        $tampungDataID_blmAppr[]            = $not_yet_appr['daily_id'];
        $tampungDataSiswa_blmAppr[]         = strtoupper($not_yet_appr['nama_siswa_or_nama_group_kelas']);
        $tampungDataNIP_blmAppr[]           = $not_yet_appr['from_nip'];
        $tampungDataPengirim_blmAppr[]      = strtoupper($not_yet_appr['username_guru']);
        $tampungDataTglUpload_blmAppr[]     = substr($not_yet_appr['tgl_dibuat'], 0, 11);
        $tampungDataJamUpload_blmAppr[]     = substr($not_yet_appr['tgl_dibuat'], 11, 19);
        $tampungDataImage_blmAppr[]         = $not_yet_appr['foto'];
        $tampungDataJudul_blmAppr[]         = $not_yet_appr['judul'];
        $tampungDataNisOrIdGroup_blmAppr[]  = $not_yet_appr['nis_or_id_group_kelas'];
        $tampungDataIsi_blmAppr[]           = $not_yet_appr['isi_daily'];

      }

      $data5 = count($tampungDataJudul_blmAppr);

      $check = strlen($tampungDataTglUpload_blmAppr[0]);

      if ($data5 > 5) {

        // hanya mengambil 5 data saja untuk notif
        for ($i = 0; $i < 5; $i++) { 

          $explode               = explode(" ", $tampungDataPengirim_blmAppr[$i]);
          $isiPesanNamaSiswa     = "";
          $isiPesan              = "";
          
          $isiPesanNamaSiswa     = "";
          $isiPesan              = "";
          
          $semuaNisOrIdGroup  = $tampungDataNisOrIdGroup_blmAppr[$i];
          $semuaNamaSiswa     = strlen($tampungDataSiswa_blmAppr[$i]) > 13 ? $isiPesanNamaSiswa .= substr($tampungDataSiswa_blmAppr[$i], 0, 13) . " ..." : $tampungDataSiswa_blmAppr[$i]; 
          $semuaPesan         = strlen($tampungDataJudul_blmAppr[$i]) > 15 ? $isiPesan .= substr($tampungDataJudul_blmAppr[$i], 0, 15) . " ..." : $tampungDataJudul_blmAppr[$i];

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
              <li class="wtlist" data-group_or_std="'. std .'" data-nip_guru="'. $tampungDataNIP_blmAppr[$i] .'" data-siswa_blmappr="'. $tampungDataSiswa_blmAppr[$i] .'" data-daily_id="'. $tampungDataID_blmAppr[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_blmAppr[$i]) . " " . $tampungDataJamUpload_blmAppr[$i] .'" data-pengirim="'. $tampungDataPengirim_blmAppr[$i] .'" data-img="'. $tampungDataImage_blmAppr[$i] .'" data-judul="'. $tampungDataJudul_blmAppr[$i] .'" data-isian="'. $tampungDataIsi_blmAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglUpload_blmAppr[$i]." ".$tampungDataJamUpload_blmAppr[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 23px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 30px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $tampungDataPengirim_blmAppr[$i] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> STUDENT </strong> <span style="margin-left: 9.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> TITLE </strong> <span style="margin-left: 31px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaPesan .'
                      </strong> 
                    </p>
                  </h4>
                </a>
              </li>
            ';

          } else if ($countCheckIdGroup == 1) {

            $forIsiNotifBlmAppr .= '
              <li class="wtlist" data-group_or_std="'. group .'" data-nip_guru="'. $tampungDataNIP_blmAppr[$i] .'" data-siswa_blmappr="'. $tampungDataSiswa_blmAppr[$i] .'" data-daily_id="'. $tampungDataID_blmAppr[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_blmAppr[$i]) . " " . $tampungDataJamUpload_blmAppr[$i] .'" data-pengirim="'. $tampungDataPengirim_blmAppr[$i] .'" data-img="'. $tampungDataImage_blmAppr[$i] .'" data-judul="'. $tampungDataJudul_blmAppr[$i] .'" data-isian="'. $tampungDataIsi_blmAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglUpload_blmAppr[$i]." ".$tampungDataJamUpload_blmAppr[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 23px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 30px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $tampungDataPengirim_blmAppr[$i] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> GROUP </strong> <span style="margin-left: 21.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> TITLE </strong> <span style="margin-left: 31px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
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

        // hanya mengambil 5 data saja untuk notif
        for ($i = 0; $i < count($tampungDataJudul_blmAppr); $i++) { 

          // $explode               = explode(" ", $tampungDataPengirim_blmAppr[$i]);
          $isiPesanNamaSiswa     = "";
          $isiPesan              = "";
          
          $semuaNisOrIdGroup  = $tampungDataNisOrIdGroup_blmAppr[$i];
          $semuaNamaSiswa     = strlen($tampungDataSiswa_blmAppr[$i]) > 13 ? $isiPesanNamaSiswa .= substr($tampungDataSiswa_blmAppr[$i], 0, 13) . " ..." : $tampungDataSiswa_blmAppr[$i]; 
          $semuaPesan         = strlen($tampungDataJudul_blmAppr[$i]) > 15 ? $isiPesan .= substr($tampungDataJudul_blmAppr[$i], 0, 15) . " ..." : $tampungDataJudul_blmAppr[$i];

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
              <li class="wtlist" data-group_or_std="'. std .'" data-nip_guru="'. $tampungDataNIP_blmAppr[$i] .'" data-siswa_blmappr="'. $tampungDataSiswa_blmAppr[$i] .'" data-daily_id="'. $tampungDataID_blmAppr[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_blmAppr[$i]) . " " . $tampungDataJamUpload_blmAppr[$i] .'" data-pengirim="'. $tampungDataPengirim_blmAppr[$i] .'" data-img="'. $tampungDataImage_blmAppr[$i] .'" data-judul="'. $tampungDataJudul_blmAppr[$i] .'" data-isian="'. $tampungDataIsi_blmAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglUpload_blmAppr[$i]." ".$tampungDataJamUpload_blmAppr[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 23px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 30px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $tampungDataPengirim_blmAppr[$i] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> STUDENT </strong> <span style="margin-left: 9.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> TITLE </strong> <span style="margin-left: 31px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaPesan .'
                      </strong> 
                    </p>
                  </h4>
                </a>
              </li>
            ';

          } else if ($countCheckIdGroup == 1) {

            $forIsiNotifBlmAppr .= '
              <li class="wtlist" data-group_or_std="'. group .'" data-nip_guru="'. $tampungDataNIP_blmAppr[$i] .'" data-siswa_blmappr="'. $tampungDataSiswa_blmAppr[$i] .'" data-daily_id="'. $tampungDataID_blmAppr[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_blmAppr[$i]) . " " . $tampungDataJamUpload_blmAppr[$i] .'" data-pengirim="'. $tampungDataPengirim_blmAppr[$i] .'" data-img="'. $tampungDataImage_blmAppr[$i] .'" data-judul="'. $tampungDataJudul_blmAppr[$i] .'" data-isian="'. $tampungDataIsi_blmAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglUpload_blmAppr[$i]." ".$tampungDataJamUpload_blmAppr[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 23px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 30px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $tampungDataPengirim_blmAppr[$i] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> GROUP </strong> <span style="margin-left: 21.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> TITLE </strong> <span style="margin-left: 31px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
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

      $countDataNotYetAppr            = mysqli_num_rows($queryNotYetAppr);

    // Akhir Data Belum Approve
    ############################################################################################################

    // Data Sudah di Approve

      $queryApproved         = mysqli_query($con, "
        SELECT *
        FROM (
          SELECT 
            daily_siswa_approved.id as daily_id,
            daily_siswa_approved.departemen as departemen,
            daily_siswa_approved.from_nip as from_nip,
            guru.username as username_guru,
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
            group_siswa_approved.departemen as departemen,
            group_siswa_approved.from_nip as from_nip,
            guru.username as username_guru,
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
          AND U.departemen = 'SD'
          ORDER BY U.tgl_disetujui DESC
      ");

      foreach ($queryApproved as $data_appr) {

        $tampungDataTglUploadOri[]          = $data_appr['tgl_disetujui'];
        $tampungDataRoomKey[]               = $data_appr['room_key'];
        $tampungDataID_sdhAppr[]            = $data_appr['daily_id'];
        $tampungDataNisOrIdGroup_sdhAppr[]  = $data_appr['nis_or_id_group_kelas'];
        $tampungDataNIP_sdhAppr[]           = $data_appr['from_nip'];
        $tampungDataPengirim_sdhAppr[]      = strtoupper($data_appr['username_guru']);
        $tampungDataGuru_sdhAppr[]          = strtoupper($data_appr['nama_guru']);
        $tampungDataNis_siswa_sdhAppr[]     = $data_appr['nis_or_id_group_kelas'];
        $tampungDataSiswa_sdhAppr[]         = strtoupper($data_appr['nama_siswa_or_nama_group_kelas']);
        $tampungDataTglUpload_sdhAppr[]     = substr($data_appr['tgl_dibuat'], 0, 11);
        $tampungDataTglDisetujui[]          = substr($data_appr['tgl_disetujui'], 0, 11);
        $tampungDataJamUpload_sdhAppr[]     = substr($data_appr['tgl_dibuat'], 11, 19);
        $tampungDataJamDisetujui[]          = substr($data_appr['tgl_disetujui'], 11, 19);
        $tampungDataImage_sdhAppr[]         = $data_appr['foto'];
        $tampungDataJudul_sdhAppr[]         = $data_appr['judul'];
        $tampungDataIsi_sdhAppr[]           = $data_appr['isi_daily'];

      }

      $data5Appr = count($tampungDataJudul_sdhAppr);

      if ($data5Appr > 5) {

        for ($i = 0; $i < 5; $i++) { 

          $explode               = explode(" ", $tampungDataPengirim_sdhAppr[$i]);
          $isiPesanNamaSiswa     = "";
          $isiPesan              = "";
          
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
              <li class="apprlist" data-group_or_std="'. std .'" data-nip_guru="'. $tampungDataNIP_sdhAppr[$i] .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNisOrIdGroup_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataGuru_sdhAppr[$i] .'" data-daily_id="'. $tampungDataID_sdhAppr[$i] .'" data-siswaorgroup_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_sdhAppr[$i]) . " " . $tampungDataJamUpload_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 27px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> STUDENT </strong> <span style="margin-left: 6.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 28px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaPesan .'
                      </strong> 
                    </p>
                  </h4>
                </a>
              </li>
            ';

          } else if ($countCheckIdGroup == 1) {

            $forIsiNotifSdhAppr .= '
              <li class="apprlist" data-group_or_std="'. group .'" data-nip_guru="'. $tampungDataNIP_sdhAppr[$i] .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNisOrIdGroup_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataGuru_sdhAppr[$i] .'" data-daily_id="'. $tampungDataID_sdhAppr[$i] .'" data-siswaorgroup_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_sdhAppr[$i]) . " " . $tampungDataJamUpload_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 27px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> GROUP </strong> <span style="margin-left: 18.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 28px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaPesan .'
                      </strong> 
                    </p>
                  </h4>
                </a>
              </li>
            ';

          }

        }

      } else if ($data5Appr < 6) {

        for ($i = 0; $i < count($tampungDataJudul_sdhAppr); $i++) { 

          $explode               = explode(" ", $tampungDataPengirim_sdhAppr[$i]);
          $isiPesanNamaSiswa     = "";
          $isiPesan              = "";

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
              <li class="apprlist" data-group_or_std="'. std .'" data-nip_guru="'. $tampungDataNIP_sdhAppr[$i] .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNis_siswa_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataGuru_sdhAppr[$i] .'" data-daily_id="'. $tampungDataID_sdhAppr[$i] .'" data-siswaorgroup_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_sdhAppr[$i]) . " " . $tampungDataJamUpload_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 27px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> STUDENT </strong> <span style="margin-left: 6.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 28px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaPesan .'
                      </strong> 
                    </p>
                  </h4>
                </a>
              </li>
            ';

          } else if ($countCheckIdGroup == 1) {

            $forIsiNotifSdhAppr .= '
              <li class="apprlist" data-group_or_std="'. group .'" data-nip_guru="'. $tampungDataNIP_sdhAppr[$i] .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNisOrIdGroup_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataGuru_sdhAppr[$i] .'" data-daily_id="'. $tampungDataID_sdhAppr[$i] .'" data-siswaorgroup_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_sdhAppr[$i]) . " " . $tampungDataJamUpload_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 27px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> GROUP </strong> <span style="margin-left: 18.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 28px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
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

      $countDataApproved = mysqli_num_rows($queryApproved);

    // Akhir Data Sudah di Approve
    ############################################################################################################

    ############################################################################################################
    // Data Tidak di Approve

      $queryNotApproved         = mysqli_query($con, "
        SELECT *
        FROM (
          SELECT 
            daily_siswa_approved.id as daily_id,
            daily_siswa_approved.departemen as departemen,
            daily_siswa_approved.from_nip as from_nip,
            guru.username as username_guru,
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
            group_siswa_approved.departemen as departemen,
            group_siswa_approved.from_nip as from_nip,
            guru.username as username_guru,
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
          AND U.departemen = 'SD'
          ORDER BY U.tgl_disetujui DESC
      ");

      foreach ($queryNotApproved as $data_not_appr) {

        $tampungDataID_tdkDiAppr[]            = $data_not_appr['daily_id'];
        $tampungDataNIP_tdkDiAppr[]           = $data_not_appr['from_nip'];
        $tampungDataPengirim_tdkDiAppr[]      = strtoupper($data_not_appr['username_guru']);
        $tampungDataNisOrIdGroup_tdkDiAppr[]  = $data_not_appr['nis_or_id_group_kelas'];
        $tampungDataSiswa_tdkDiAppr[]         = $data_not_appr['nama_siswa_or_nama_group_kelas'];
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
          $semuaNamaSiswa     = strlen($tampungDataSiswa_tdkDiAppr[$i]) > 15 ? $isiPesanNamaSiswa .= substr($tampungDataSiswa_tdkDiAppr[$i], 0, 15) . " ..." : $tampungDataSiswa_tdkDiAppr[$i];
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
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 25px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> STUDENT </strong> <span style="margin-left: 4.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 26.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
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
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 25px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> GROUP </strong> <span style="margin-left: 17px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 26.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
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
          
          $explode               = explode(" ", $tampungDataPengirim_tdkDiAppr[$i]);
          $isiPesanNamaSiswa     = "";
          $isiPesan              = "";
          
          $semuaNisOrIdGroup  = $tampungDataNisOrIdGroup_tdkDiAppr[$i];
          $semuaNamaSiswa     = strlen($tampungDataSiswa_tdkDiAppr[$i]) > 15 ? $isiPesanNamaSiswa .= substr($tampungDataSiswa_tdkDiAppr[$i], 0, 15) . " ..." : $tampungDataSiswa_tdkDiAppr[$i];
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
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 25px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> STUDENT </strong> <span style="margin-left: 4.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 26.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
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
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 25px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> GROUP </strong> <span style="margin-left: 17px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 26.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
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

      $countDataNotApproved = mysqli_num_rows($queryNotApproved);

    // Akhir Data Tidak Di Approve
    ############################################################################################################

  } else if ($foundDataPAUD == 1) {

    $paud = true;

    ############################################################################################################
    // Data Belum Approve

      $queryNotYetAppr         = mysqli_query($con, "
        SELECT *
        FROM (
          SELECT 
            daily_siswa_approved.id as daily_id,
            daily_siswa_approved.departemen as departemen,
            daily_siswa_approved.from_nip as from_nip,
            guru.username as username_guru,
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
            group_siswa_approved.departemen as departemen,
            group_siswa_approved.from_nip as from_nip,
            guru.username as username_guru,
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
          AND U.departemen = 'PAUD'
          ORDER BY U.tgl_dibuat DESC
      ");

      foreach ($queryNotYetAppr as $not_yet_appr) {

        $tampungDataID_blmAppr[]            = $not_yet_appr['daily_id'];
        $tampungDataSiswa_blmAppr[]         = strtoupper($not_yet_appr['nama_siswa_or_nama_group_kelas']);
        $tampungDataNIP_blmAppr[]           = $not_yet_appr['from_nip'];
        $tampungDataPengirim_blmAppr[]      = strtoupper($not_yet_appr['username_guru']);
        $tampungDataTglUpload_blmAppr[]     = substr($not_yet_appr['tgl_dibuat'], 0, 11);
        $tampungDataJamUpload_blmAppr[]     = substr($not_yet_appr['tgl_dibuat'], 11, 19);
        $tampungDataImage_blmAppr[]         = $not_yet_appr['foto'];
        $tampungDataJudul_blmAppr[]         = $not_yet_appr['judul'];
        $tampungDataNisOrIdGroup_blmAppr[]  = $not_yet_appr['nis_or_id_group_kelas'];
        $tampungDataIsi_blmAppr[]           = $not_yet_appr['isi_daily'];

      }

      $check = strlen($tampungDataTglUpload_blmAppr[0]);

      $data5 = count($tampungDataJudul_blmAppr);

      if ($data5 > 5) {

        // hanya mengambil 5 data saja untuk notif
        for ($i = 0; $i < 5; $i++) { 

          $explode               = explode(" ", $tampungDataPengirim_blmAppr[$i]);
          $isiPesanNamaSiswa     = "";
          $isiPesan              = "";
          
          $semuaNisOrIdGroup  = $tampungDataNisOrIdGroup_blmAppr[$i];
          $semuaNamaSiswa     = strlen($tampungDataSiswa_blmAppr[$i]) > 13 ? $isiPesanNamaSiswa .= substr($tampungDataSiswa_blmAppr[$i], 0, 13) . " ..." : $tampungDataSiswa_blmAppr[$i]; 
          $semuaPesan         = strlen($tampungDataJudul_blmAppr[$i]) > 15 ? $isiPesan .= substr($tampungDataJudul_blmAppr[$i], 0, 15) . " ..." : $tampungDataJudul_blmAppr[$i];

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
              <li class="wtlist" data-group_or_std="'. std .'" data-nip_guru="'. $tampungDataNIP_blmAppr[$i] .'" data-siswa_blmappr="'. $tampungDataSiswa_blmAppr[$i] .'" data-daily_id="'. $tampungDataID_blmAppr[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_blmAppr[$i]) . " " . $tampungDataJamUpload_blmAppr[$i] .'" data-pengirim="'. $tampungDataPengirim_blmAppr[$i] .'" data-img="'. $tampungDataImage_blmAppr[$i] .'" data-judul="'. $tampungDataJudul_blmAppr[$i] .'" data-isian="'. $tampungDataIsi_blmAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglUpload_blmAppr[$i]." ".$tampungDataJamUpload_blmAppr[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 23px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 30px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $tampungDataPengirim_blmAppr[$i] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> STUDENT </strong> <span style="margin-left: 9.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> TITLE </strong> <span style="margin-left: 31px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaPesan .'
                      </strong> 
                    </p>
                  </h4>
                </a>
              </li>
            ';

          } else if ($countCheckIdGroup == 1) {

            $forIsiNotifBlmAppr .= '
              <li class="wtlist" data-group_or_std="'. group .'" data-nip_guru="'. $tampungDataNIP_blmAppr[$i] .'" data-siswa_blmappr="'. $tampungDataSiswa_blmAppr[$i] .'" data-daily_id="'. $tampungDataID_blmAppr[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_blmAppr[$i]) . " " . $tampungDataJamUpload_blmAppr[$i] .'" data-pengirim="'. $tampungDataPengirim_blmAppr[$i] .'" data-img="'. $tampungDataImage_blmAppr[$i] .'" data-judul="'. $tampungDataJudul_blmAppr[$i] .'" data-isian="'. $tampungDataIsi_blmAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglUpload_blmAppr[$i]." ".$tampungDataJamUpload_blmAppr[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 23px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 30px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $tampungDataPengirim_blmAppr[$i] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> GROUP </strong> <span style="margin-left: 21.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> TITLE </strong> <span style="margin-left: 31px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
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

        // hanya mengambil 5 data saja untuk notif
        for ($i = 0; $i < count($tampungDataJudul_blmAppr); $i++) { 

          $explode               = explode(" ", $tampungDataPengirim_blmAppr[$i]);
          $isiPesanNamaSiswa     = "";
          $isiPesan              = "";
          
          $semuaNisOrIdGroup  = $tampungDataNisOrIdGroup_blmAppr[$i];
          $semuaNamaSiswa     = strlen($tampungDataSiswa_blmAppr[$i]) > 13 ? $isiPesanNamaSiswa .= substr($tampungDataSiswa_blmAppr[$i], 0, 13) . " ..." : $tampungDataSiswa_blmAppr[$i]; 
          $semuaPesan         = strlen($tampungDataJudul_blmAppr[$i]) > 15 ? $isiPesan .= substr($tampungDataJudul_blmAppr[$i], 0, 15) . " ..." : $tampungDataJudul_blmAppr[$i];

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
              <li class="wtlist" data-group_or_std="'. std .'" data-nip_guru="'. $tampungDataNIP_blmAppr[$i] .'" data-siswa_blmappr="'. $tampungDataSiswa_blmAppr[$i] .'" data-daily_id="'. $tampungDataID_blmAppr[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_blmAppr[$i]) . " " . $tampungDataJamUpload_blmAppr[$i] .'" data-pengirim="'. $tampungDataPengirim_blmAppr[$i] .'" data-img="'. $tampungDataImage_blmAppr[$i] .'" data-judul="'. $tampungDataJudul_blmAppr[$i] .'" data-isian="'. $tampungDataIsi_blmAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglUpload_blmAppr[$i]." ".$tampungDataJamUpload_blmAppr[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 23px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 30px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $tampungDataPengirim_blmAppr[$i] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> STUDENT </strong> <span style="margin-left: 9.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> TITLE </strong> <span style="margin-left: 31px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaPesan .'
                      </strong> 
                    </p>
                  </h4>
                </a>
              </li>
            ';

          } else if ($countCheckIdGroup == 1) {

            $forIsiNotifBlmAppr .= '
              <li class="wtlist" data-group_or_std="'. group .'" data-nip_guru="'. $tampungDataNIP_blmAppr[$i] .'" data-siswa_blmappr="'. $tampungDataSiswa_blmAppr[$i] .'" data-daily_id="'. $tampungDataID_blmAppr[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_blmAppr[$i]) . " " . $tampungDataJamUpload_blmAppr[$i] .'" data-pengirim="'. $tampungDataPengirim_blmAppr[$i] .'" data-img="'. $tampungDataImage_blmAppr[$i] .'" data-judul="'. $tampungDataJudul_blmAppr[$i] .'" data-isian="'. $tampungDataIsi_blmAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglUpload_blmAppr[$i]." ".$tampungDataJamUpload_blmAppr[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 23px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 30px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $tampungDataPengirim_blmAppr[$i] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> GROUP </strong> <span style="margin-left: 21.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> TITLE </strong> <span style="margin-left: 31px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
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

      $countDataNotYetAppr            = mysqli_num_rows($queryNotYetAppr);

    // Akhir Data Belum Approve
    ############################################################################################################

    ############################################################################################################
    // Data Sudah di Approve

      $queryApproved         = mysqli_query($con, "
        SELECT *
        FROM (
          SELECT 
            daily_siswa_approved.id as daily_id,
            daily_siswa_approved.departemen as departemen,
            daily_siswa_approved.from_nip as from_nip,
            guru.username as username_guru,
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
            group_siswa_approved.departemen as departemen,
            group_siswa_approved.from_nip as from_nip,
            guru.username as username_guru,
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
          AND U.departemen = 'PAUD'
          ORDER BY U.tgl_disetujui DESC
      ");

      foreach ($queryApproved as $data_appr) {

        $tampungDataTglUploadOri[]          = $data_appr['tgl_disetujui'];
        $tampungDataRoomKey[]               = $data_appr['room_key'];
        $tampungDataID_sdhAppr[]            = $data_appr['daily_id'];
        $tampungDataNisOrIdGroup_sdhAppr[]  = $data_appr['nis_or_id_group_kelas'];
        $tampungDataNIP_sdhAppr[]           = $data_appr['from_nip'];
        $tampungDataPengirim_sdhAppr[]      = strtoupper($data_appr['username_guru']);
        $tampungDataGuru_sdhAppr[]          = strtoupper($data_appr['nama_guru']);
        $tampungDataNis_siswa_sdhAppr[]     = $data_appr['nis_or_id_group_kelas'];
        $tampungDataSiswa_sdhAppr[]         = strtoupper($data_appr['nama_siswa_or_nama_group_kelas']);
        $tampungDataTglUpload_sdhAppr[]     = substr($data_appr['tgl_dibuat'], 0, 11);
        $tampungDataTglDisetujui[]          = substr($data_appr['tgl_disetujui'], 0, 11);
        $tampungDataJamUpload_sdhAppr[]     = substr($data_appr['tgl_dibuat'], 11, 19);
        $tampungDataJamDisetujui[]          = substr($data_appr['tgl_disetujui'], 11, 19);
        $tampungDataImage_sdhAppr[]         = $data_appr['foto'];
        $tampungDataJudul_sdhAppr[]         = $data_appr['judul'];
        $tampungDataIsi_sdhAppr[]           = $data_appr['isi_daily'];

      }

      $data5Appr = count($tampungDataJudul_sdhAppr);

      if ($data5Appr > 5) {

        for ($i = 0; $i < 5; $i++) { 

          $explode               = explode(" ", $tampungDataPengirim_sdhAppr[$i]);
          $isiPesanNamaSiswa     = "";
          $isiPesan              = "";
          
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
              <li class="apprlist" data-group_or_std="'. std .'" data-nip_guru="'. $tampungDataNIP_sdhAppr[$i] .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNis_siswa_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataGuru_sdhAppr[$i] .'" data-daily_id="'. $tampungDataID_sdhAppr[$i] .'" data-siswaorgroup_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_sdhAppr[$i]) . " " . $tampungDataJamUpload_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 27px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> STUDENT </strong> <span style="margin-left: 6.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 28px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaPesan .'
                      </strong> 
                    </p>
                  </h4>
                </a>
              </li>
            ';

          } else if ($countCheckIdGroup == 1) {

            $forIsiNotifSdhAppr .= '
              <li class="apprlist" data-group_or_std="'. group .'" data-nip_guru="'. $tampungDataNIP_sdhAppr[$i] .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNisOrIdGroup_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataGuru_sdhAppr[$i] .'" data-daily_id="'. $tampungDataID_sdhAppr[$i] .'" data-siswaorgroup_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_sdhAppr[$i]) . " " . $tampungDataJamUpload_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 27px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> GROUP </strong> <span style="margin-left: 18.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 28px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaPesan .'
                      </strong> 
                    </p>
                  </h4>
                </a>
              </li>
            ';

          }

        }

      } else if ($data5Appr < 6) {

        for ($i = 0; $i < count($tampungDataJudul_sdhAppr); $i++) { 

          $explode               = explode(" ", $tampungDataPengirim_sdhAppr[$i]);
          $isiPesanNamaSiswa     = "";
          $isiPesan              = "";
          
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
              <li class="apprlist" data-group_or_std="'. std .'" data-nip_guru="'. $tampungDataNIP_sdhAppr[$i] .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNis_siswa_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataGuru_sdhAppr[$i] .'" data-daily_id="'. $tampungDataID_sdhAppr[$i] .'" data-siswaorgroup_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_sdhAppr[$i]) . " " . $tampungDataJamUpload_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 27px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> STUDENT </strong> <span style="margin-left: 6.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 28px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaPesan .'
                      </strong> 
                    </p>
                  </h4>
                </a>
              </li>
            ';

          } else if ($countCheckIdGroup == 1) {

            $forIsiNotifSdhAppr .= '
              <li class="apprlist" data-group_or_std="'. group .'" data-nip_guru="'. $tampungDataNIP_sdhAppr[$i] .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNisOrIdGroup_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataGuru_sdhAppr[$i] .'" data-daily_id="'. $tampungDataID_sdhAppr[$i] .'" data-siswaorgroup_was_appr="'. $tampungDataSiswa_sdhAppr[$i] .'" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_sdhAppr[$i]) . " " . $tampungDataJamUpload_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 27px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> GROUP </strong> <span style="margin-left: 18.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 28px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
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

      $countDataApproved = mysqli_num_rows($queryApproved);

    // Akhir Data Sudah di Approve
    ############################################################################################################

    ############################################################################################################
    // Data Tidak di Approve

      $queryNotApproved         = mysqli_query($con, "
        SELECT *
        FROM (
          SELECT 
            daily_siswa_approved.id as daily_id,
            daily_siswa_approved.departemen as departemen,
            daily_siswa_approved.from_nip as from_nip,
            guru.username as username_guru,
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
            group_siswa_approved.departemen as departemen,
            group_siswa_approved.from_nip as from_nip,
            guru.username as username_guru,
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
          AND U.departemen = 'PAUD'
          ORDER BY U.tgl_disetujui DESC
      ");

      foreach ($queryNotApproved as $data_not_appr) {

        $tampungDataID_tdkDiAppr[]            = $data_not_appr['daily_id'];
        $tampungDataNIP_tdkDiAppr[]           = $data_not_appr['from_nip'];
        $tampungDataPengirim_tdkDiAppr[]      = strtoupper($data_not_appr['username_guru']);
        $tampungDataNisOrIdGroup_tdkDiAppr[]  = $data_not_appr['nis_or_id_group_kelas'];
        $tampungDataSiswa_tdkDiAppr[]         = $data_not_appr['nama_siswa_or_nama_group_kelas'];
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

          // echo $countCheckIdGroup;exit;

          if ($countCheckNis == 1) { 

            $forIsiNotifTdkDiAppr .= '
              <li class="notapprlist" data-group_or_std="'. std .'" data-reason_daily="'. $tampungDataIsiAlasan_tdkDiAppr[$i] .'" data-siswa_not_appr="'. $tampungDataSiswa_tdkDiAppr[$i] .'" data-tgl_noapproved ="'. tgl_indo($tampungDataTglTdkDisetujui[$i]) . " " . $tampungDataJamTdkDisetujui[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_tdkDiAppr[$i]) . " " . $tampungDataJamUpload_tdkDiAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage_tdkDiAppr[$i] .'" data-judul="'. $tampungDataJudul_tdkDiAppr[$i] .'" data-isian="'. $tampungDataIsi_tdkDiAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglTdkDisetujui[$i]." ".$tampungDataJamTdkDisetujui[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 25px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> STUDENT </strong> <span style="margin-left: 4.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 26.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
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
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 25px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> GROUP </strong> <span style="margin-left: 17px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 26.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
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

          // echo $countCheckIdGroup;exit;

          if ($countCheckNis == 1) { 

            $forIsiNotifTdkDiAppr .= '
              <li class="notapprlist" data-group_or_std="'. std .'" data-reason_daily="'. $tampungDataIsiAlasan_tdkDiAppr[$i] .'" data-siswa_not_appr="'. $tampungDataSiswa_tdkDiAppr[$i] .'" data-tgl_noapproved ="'. tgl_indo($tampungDataTglTdkDisetujui[$i]) . " " . $tampungDataJamTdkDisetujui[$i] .'" data-tgl_upload="'. tgl_indo($tampungDataTglUpload_tdkDiAppr[$i]) . " " . $tampungDataJamUpload_tdkDiAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-img="'. $tampungDataImage_tdkDiAppr[$i] .'" data-judul="'. $tampungDataJudul_tdkDiAppr[$i] .'" data-isian="'. $tampungDataIsi_tdkDiAppr[$i] .'" data-toggle="modal">
                <a href="#">
                  <h4 style="font-size: 12px;">
                    <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglTdkDisetujui[$i]." ".$tampungDataJamTdkDisetujui[$i]) . '</small>
                  </h4>
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 25px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> STUDENT </strong> <span style="margin-left: 4.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 26.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
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
                  <div class="pull-left" style="margin-left: 10px; margin-top: 25px;">
                    <img src="../imgstatis/logo2.png" style="width: 35px;">
                  </div>
                  <h4 style="font-size: 12px; margin-top: 24px;">
                    <p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 25px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $explode[0] . '</strong> </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                    <strong> GROUP </strong> <span style="margin-left: 17px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
                        '. $semuaNamaSiswa .'
                      </strong> 
                    </p>
                  </h4>
                  <h4 style="font-size: 12px;">
                    <p style="font-size: 13px;margin-left: 60px;"> 
                      <strong> TITLE </strong> <span style="margin-left: 26.5px;"> : </span> 
                      <strong id="title_daily" style="margin-left: 7px;"> 
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

      $countDataNotApproved = mysqli_num_rows($queryNotApproved);

    // Akhir Data Tidak Di Approve
    ############################################################################################################

  }

  // Send TO Front-End
  $arr['notif_not_yet_appr']      = $countDataNotYetAppr;
  $arr['notif_appr']              = $countDataApproved;
  $arr['notif_not_appr']          = $countDataNotApproved;

  $arr['upload_img']              = $forImage;
  // $arr['hitung_str']              = $tampungDataJamUpload;

  // Isi notif belum di approve
  $arr['isi_notif_not_yet_appr']  = $forIsiNotifBlmAppr;
  // Akhir notif belum di approve

  // Isi notif sudah di approve
  $arr['isi_notif_approved']      = $forIsiNotifSdhAppr;
  // Akhir isi notif sudah di approve

  // Isi Notif tidak di approve
  $arr['isi_notif_not_approved']  = $forIsiNotifTdkDiAppr;
  // Akhir isi notif tidak di approve

  $arr['is_sd']                   = $sd;
  $arr['is_paud']                 = $paud;
  $arr['code_kepsek']             = $_SESSION['c_kepsek_paud'];

  // Message Chat
  $arr['dari_orang_Lain']         = $getDataAll['pengirim_pesan'];
  $arr['dari_saya']               = $getDataSelf['nama_kepsek'];
  $arr['count']                   = $count;
  $arr['isi_chat']                = $arrOther;
  $arr['dari_chat']               = $fromAll;
  $arr['isi_chatx']               = $arrMe;
  $arr['dari_chatx']              = $fromMe;
  $arr['nomer_hp']                = $tampungNomerHP;
  $arr['number_phone_teacher']    = $isValidNumber;
  $arr['created_by_teacher']      = $byTeacher;
  // End Message Chat

  echo json_encode($arr);

  exit;

?>