<?php  

	require '../php/config.php'; 

	error_reporting(1);  

	// Cek status login user jika tidak ada session
  if (!$user->isLoggedInAdmin()) { 

    header("location:../"); //Redirect ke halaman login  
  }

	$countDataMessage = $user->countDataMessage();

	$arr 									= [];
	$status_approve 			= "";
	$forImage             = '';

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

  function rupiahFormat($angkax){
    
    $hasil_rupiahx = "Rp " . number_format($angkax,0,'.','.');
    return $hasil_rupiahx;
   
  }

  // Lookdaily Teacher
  if (isset($_POST['is_teacher'])) {
  	$arr['is_page'] 			= 'is_teacher';

  	$dataID     = htmlspecialchars($_POST['id_daily']);
  	$dataNIP  	= htmlspecialchars($_POST['nip']);
  	$dataNIS 		= htmlspecialchars($_POST['nis']);

  	$checkDataADM 	= mysqli_query($con, "SELECT c_admin FROM admin WHERE c_admin = '$dataNIP' ");
  	$checkDataGuru 	= mysqli_query($con, "SELECT nama FROM guru WHERE nip = '$dataNIP' ");

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

	  	$getDataIsiDaily 	= mysqli_fetch_array($queryGetApproved)['isi_daily'];
	  	$getDataCreatedBy = mysqli_fetch_array($queryGetApproved)['nama_user'];

	  	$arr['created_by'] 		= $getDataCreatedBy;
  		$arr['display_html'] 	= $getDataIsiDaily;

  	}

  }

  // Update Pembayaran Info Pembayaran
  if (isset($_POST['tesdata'])) {

    $id = $_POST['idbayar'];

    $findDataZero = mysqli_query($con, "
      SELECT id, nis, status_pembayaran
      FROM info_pengumuman_pembayaran
      WHERE id = '$id'
    ");

    $getDataZero = mysqli_fetch_array($findDataZero);

    if ($getDataZero['status_pembayaran'] == 0) {

      date_default_timezone_set("Asia/Jakarta");

      $tglSkrng       = date("Y-m-d H:i:s");

      $queryUpdatePembayaran = mysqli_query($con, "
        UPDATE info_pengumuman_pembayaran
        SET
        status_pembayaran = 1,
        tanggal_update = '$tglSkrng'
        WHERE id = '$id'
      ");

      if ($queryUpdatePembayaran == true) {
        $arr['update_pembayaran'] = "berhasil";
      } else {
        $arr['update_pembayaran'] = "tidak_berhasil";
      }

    } else if ($getDataZero['status_pembayaran'] == 1) {

      date_default_timezone_set("Asia/Jakarta");

      $tglSkrng       = date("Y-m-d H:i:s");

      $queryUpdatePembayaran = mysqli_query($con, "
        UPDATE info_pengumuman_pembayaran
        SET
        status_pembayaran = 0,
        tanggal_update = '$tglSkrng'
        WHERE id = '$id'
      ");

      if ($queryUpdatePembayaran == true) {
        $arr['update_pembayaran'] = "berhasil";
      } else {
        $arr['update_pembayaran'] = "tidak_berhasil";
      }

    }

  }
  // Akhir Update Pembayaran Info Pembayaran

  ##############################
  // Data All Info 

  $forIsiNotifInfo 		= '';
  $dataArrNIS 				= [];
  $dataArrNama 				= [];
  $dataArrJenisBayar	= [];
  $dataArrKetBayar    = [];
  $dataArrNominalByr  = [];
  $dataArrTglDibuat   = [];
  $dataArrJamDibuat   = [];

  $jumlahDataInfo     = 0;

  $dataAllInfo = mysqli_query($con, "
  	SELECT 
  	info_pengumuman_pembayaran.jenis_info_pembayaran as jenis_bayar,
  	info_pengumuman_pembayaran.keterangan as ket_bayar,
  	info_pengumuman_pembayaran.tanggal_dibuat as tgl_dibuat,
  	info_pengumuman_pembayaran.nis as nis_siswa,
  	info_pengumuman_pembayaran.nominal as nominal,
  	siswa.nama as nama_siswa
  	FROM info_pengumuman_pembayaran
  	LEFT JOIN siswa
  	ON info_pengumuman_pembayaran.nis = siswa.nis
  	WHERE 
  	info_pengumuman_pembayaran.status_pembayaran = 0 
  	OR
  	info_pengumuman_pembayaran.status_pembayaran IS NULL 
  	ORDER BY info_pengumuman_pembayaran.tanggal_dibuat DESC
  ");

  foreach($dataAllInfo as $data) {

  	$dataArrNIS[]         = $data['nis_siswa'];
  	$dataArrNama[] 				= strtoupper($data['nama_siswa']);
  	$dataArrJenisBayar[] 	= $data['jenis_bayar'];
  	$dataArrKetBayar[]    = $data['ket_bayar'];
  	$dataArrNominalByr[]  = $data['nominal'];
  	$dataArrTglDibuat[]   = substr($data['tgl_dibuat'], 0, 11);
  	$dataArrJamDibuat[]   = substr($data['tgl_dibuat'], 11, 19);
  }

  $jumlahDataInfo = count($dataArrNama);

  for ($i = 0; $i < $jumlahDataInfo; $i++) {

    // $explode                = explode(" ", $tampungDataPengirim_sdhAppr[$i]);
    $isiPesanNamaSiswa      = "";
    $isiPesan               = "";
      
    $semuaNamaSiswa = strlen($dataArrNama[$i]) > 17 ? $isiPesanNamaSiswa .= substr($dataArrNama[$i], 0, 17) . " ..." : $dataArrNama[$i];
    $semuaPesan     = strlen($dataArrJenisBayar[$i]) > 13 ? $isiPesan .= substr($dataArrJenisBayar[$i], 0, 13) . " ..." : $dataArrJenisBayar[$i];

    $forIsiNotifInfo .= '
      <li class="infolist" data-nominal_format="'. rupiahFormat($dataArrNominalByr[$i]) .'" data-nominal="'. $dataArrNominalByr[$i] .'" data-nama_siswa="'. $dataArrNama[$i] .'" data-nis_siswa="'. $dataArrNIS[$i] .'" data-tgl_upload = "'. tgl_indo($dataArrTglDibuat[$i]) . " " . $dataArrJamDibuat[$i] . '" data-jenis_byr="'. $dataArrJenisBayar[$i] .'" data-ket_byr="'. $dataArrKetBayar[$i] .'" data-toggle="modal">
          <a href="#">
            <h4 style="font-size: 12px;">
              <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($dataArrTglDibuat[$i]." ".$dataArrJamDibuat[$i]) . '</small>
            </h4>
            <div class="pull-left" style="margin-left: -1px; margin-top: 13px;">
              <img src="../imgstatis/logo2.png" style="width: 35px;">
            </div>
            <h4 style="font-size: 12px; margin-top: 20px;">
              <p style="font-size: 15px;margin-left: 45px;"> 
                <strong> SISWA </strong> <span style="margin-left: 6px;"> : </span> 
                <strong id="title_daily" style="margin-left: 3px;"> 
                  '. $semuaNamaSiswa .'
                </strong> 
              </p>
            </h4>
            <h4 style="font-size: 12px;">
              <p style="font-size: 15px;margin-left: 45px;"> 
                <strong> INFO </strong> <span style="margin-left: 15.5px;"> : </span> 
                <strong id="title_daily" style="margin-left: 3px; font-size: 13px;"> 
                  '. ' Pembayaran ' . $semuaPesan .'
                </strong> 
              </p>
            </h4>
          </a>
        </li>
    ';

  }

  // if ($jumlahDataInfo > 5) {
  // 	for ($i = 0; $i < 5; $i++) {

  // 		// $explode                = explode(" ", $tampungDataPengirim_sdhAppr[$i]);
  //     $isiPesanNamaSiswa      = "";
  //     $isiPesan               = "";
        
  //     $semuaNamaSiswa = strlen($dataArrNama[$i]) > 17 ? $isiPesanNamaSiswa .= substr($dataArrNama[$i], 0, 17) . " ..." : $dataArrNama[$i];
  //     $semuaPesan 		= strlen($dataArrJenisBayar[$i]) > 13 ? $isiPesan .= substr($dataArrJenisBayar[$i], 0, 13) . " ..." : $dataArrJenisBayar[$i];

  //     $forIsiNotifInfo .= '
  //       <li class="infolist" data-nominal_format="'. rupiahFormat($dataArrNominalByr[$i]) .'" data-nominal="'. $dataArrNominalByr[$i] .'" data-nama_siswa="'. $dataArrNama[$i] .'" data-nis_siswa="'. $dataArrNIS[$i] .'" data-tgl_upload = "'. tgl_indo($dataArrTglDibuat[$i]) . " " . $dataArrJamDibuat[$i] . '" data-jenis_byr="'. $dataArrJenisBayar[$i] .'" data-ket_byr="'. $dataArrKetBayar[$i] .'" data-toggle="modal">
  //           <a href="#">
  //             <h4 style="font-size: 12px;">
  //               <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($dataArrTglDibuat[$i]." ".$dataArrJamDibuat[$i]) . '</small>
  //             </h4>
  //             <div class="pull-left" style="margin-left: -1px; margin-top: 13px;">
  //               <img src="../imgstatis/logo2.png" style="width: 35px;">
  //             </div>
  //             <h4 style="font-size: 12px; margin-top: 20px;">
  //               <p style="font-size: 15px;margin-left: 45px;"> 
  //                 <strong> SISWA </strong> <span style="margin-left: 6px;"> : </span> 
  //                 <strong id="title_daily" style="margin-left: 7px;"> 
  //                   '. $semuaNamaSiswa .'
  //                 </strong> 
  //               </p>
  //             </h4>
  //             <h4 style="font-size: 12px;">
  //               <p style="font-size: 15px;margin-left: 45px;"> 
  //                 <strong> INFO </strong> <span style="margin-left: 15.5px;"> : </span> 
  //                 <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
  //                   '. ' Pembayaran ' . $semuaPesan .'
  //                 </strong> 
  //               </p>
  //             </h4>
  //           </a>
  //         </li>
  //     ';

  // 	}

  // } elseif ($jumlahDataInfo <= 5) {

  // 	for ($i = 0; $i < $jumlahDataInfo; $i++) {

  // 		// $explode                = explode(" ", $tampungDataPengirim_sdhAppr[$i]);
  //     $isiPesanNamaSiswa      = "";
  //     $isiPesan               = "";
        
  //     $semuaNamaSiswa = strlen($dataArrNama[$i]) > 17 ? $isiPesanNamaSiswa .= substr($dataArrNama[$i], 0, 17) . " ..." : $dataArrNama[$i];
  //     $semuaPesan 		= strlen($dataArrJenisBayar[$i]) > 13 ? $isiPesan .= substr($dataArrJenisBayar[$i], 0, 13) . " ..." : $dataArrJenisBayar[$i];

  //     $forIsiNotifInfo .= '
  //       <li class="infolist" data-nominal_format="'. rupiahFormat($dataArrNominalByr[$i]) .'" data-nominal="'. $dataArrNominalByr[$i] .'" data-nama_siswa="'. $dataArrNama[$i] .'" data-nis_siswa="'. $dataArrNIS[$i] .'" data-tgl_upload = "'. tgl_indo($dataArrTglDibuat[$i]) . " " . $dataArrJamDibuat[$i] . '" data-jenis_byr="'. $dataArrJenisBayar[$i] .'" data-ket_byr="'. $dataArrKetBayar[$i] .'" data-toggle="modal">
  //           <a href="#">
  //             <h4 style="font-size: 12px;">
  //               <small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($dataArrTglDibuat[$i]." ".$dataArrJamDibuat[$i]) . '</small>
  //             </h4>
  //             <div class="pull-left" style="margin-left: -1px; margin-top: 13px;">
  //               <img src="../imgstatis/logo2.png" style="width: 35px;">
  //             </div>
  //             <h4 style="font-size: 12px; margin-top: 20px;">
  //               <p style="font-size: 15px;margin-left: 45px;"> 
  //                 <strong> SISWA </strong> <span style="margin-left: 6px;"> : </span> 
  //                 <strong id="title_daily" style="margin-left: 3px;"> 
  //                   '. $semuaNamaSiswa .'
  //                 </strong> 
  //               </p>
  //             </h4>
  //             <h4 style="font-size: 12px;">
  //               <p style="font-size: 15px;margin-left: 45px;"> 
  //                 <strong> INFO </strong> <span style="margin-left: 15.5px;"> : </span> 
  //                 <strong id="title_daily" style="margin-left: 3px; font-size: 13px;"> 
  //                   '. ' Pembayaran ' . $semuaPesan .'
  //                 </strong> 
  //               </p>
  //             </h4>
  //           </a>
  //         </li>
  //     ';

  // 	}

  // }

  // Akhir Data All Info
	##############################

  $forImage .= '
      <div class="col-sm-4">
         <label for="exampleInputEmail1"> Upload Image Daily (*Tidak lebih dari 2 MB) </label>
         <input type="file" class="form-control fileGambar" name="banner" id="buat_banner">
         <img src="" id="gambar">
      </div>'
  ;

	

	$queryNotAppr  				= mysqli_query($con, "SELECT * FROM daily_siswa_approved WHERE status_approve = 0 ");
	$countDataNotAppr 		= mysqli_num_rows($queryNotAppr);

  $arr['notif_info']      = $jumlahDataInfo;
  $arr['isi_notif_info']  = $forIsiNotifInfo;

	echo json_encode($arr);

	exit;

?>