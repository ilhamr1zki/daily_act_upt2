<?php  
	
	require '../php/config.php'; 
  	require '../php/function.php';

  	// Cek status login user jika tidak ada session
  	if (!$user->isLoggedInOTM()) { 
		header("location:../"); //Redirect ke halaman login  
  	}

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

  	$arr                  = [];
  	$status_approve       = "";
  	$forIsiNotifSdhAppr   = "";
  	$output_all_approve   = "";
  	$isiStatusDaily       = "";
  	$isiPesan             = '';
  
  	if (isset($_POST['room_id'])) {
        
	    $room_id = $_POST['room_id'];
	    $users   = $_POST['users'];

	    $getDataChatOther = mysqli_query($conn, "
	      SELECT 
	      tbl_komentar.room_id as r_id,
	      tbl_komentar.code_user as nip_guru,
	      guru.nama as nama_guru,
	      tbl_komentar.stamp as tanggal_kirim,
	      tbl_komentar.isi_komentar as pesan
	      FROM 
	      tbl_komentar 
	      LEFT JOIN ruang_pesan
	      ON tbl_komentar.room_id = ruang_pesan.room_key
	      LEFT JOIN guru
	      ON tbl_komentar.code_user = guru.nip
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

	    $getDataAll   = mysqli_fetch_array($getDataChatOther);
	    $getDataSelf  = mysqli_fetch_array($getDataFrom_MySelf);

	    $count        = mysqli_num_rows($getDataChatOther);
	    $fromAll      = [];
	    $fromMe       = [];
	    $pesanSemua   = [];
	    $pesanSaya    = [];

	    foreach ($getDataChatOther as $all_data) {

	      $fromAll[] = $all_data['nama_guru'];

	      if ($all_data['nip_guru'] == $users) {
	        
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



	      } else {
	        $pesanSemua[] = '
	          <div class="direct-chat-msg">
	            <div class="direct-chat-info clearfix">
	              <span class="direct-chat-name pull-left">'. $all_data['nama_guru'] .'</span>
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

  	$execQueryAppr        = '';

  	$dataFound            = '';
  	$nis                  = $_SESSION['c_otm_paud'];

  	date_default_timezone_set("Asia/Jakarta");
  	$tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
  	$tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

  	$arrTgl['tgl_awal']   = $tglSkrngAwal;
  	$arrTgl['tgl_akhir']  = $tglSkrngAkhir;

  	// Get Id Group
  	$queryGetIdGroup = mysqli_query($con, "
  		SELECT group_kelas FROM siswa WHERE nis = '$nis'
  	");

  	$getIdGroup = mysqli_fetch_assoc($queryGetIdGroup)['group_kelas'];
  	// echo $getIdGroup;exit;

  	// Array Penampung Data Sudah di Approve
  	  $tampungDataTglUploadOri      		= [];
  	  $tampungDataRoomKey           		= [];
	  $tampungDataID_sdhAppr        		= [];
	  $tampungDataNIP_sdhAppr       		= [];
	  $tampungDataGuru_sdhAppr      		= [];
	  $tampungDataPengirim_sdhAppr  		= [];
	  $tampungDataUsername_sdhAppr  		= [];
	  $tampungDataTglDisetujui      		= [];
	  $tampungDataTglUpload_sdhAppr 		= [];
	  $tampungDataJamUpload_sdhAppr 		= [];
	  $tampungDataJamDisetujui      		= [];
	  $tampungDataImage_sdhAppr     		= [];
	  $tampungDataNis_sdhAppr 				= [];
	  $tampungDataJudul_sdhAppr     		= [];
	  $tampungDataSiswaOrNamaGroup_sdhAppr 	= [];
	  $tampungDataIsi_sdhAppr       		= [];
	  $elementNotifAppr            	 		= '';

	  $countCheckPersonal  					= "";
  	// Akhir Array Penampung Data Sudah di Approve

	 // Array Semua Data
	  $tampungSemuaDataTglUploadOri      			= [];
  	  $tampungSemuaDataRoomKey           			= [];
	  $tampungSemuaDataID_sdhAppr        			= [];
	  $tampungSemuaDataNIP_sdhAppr       			= [];
	  $tampungSemuaDataGuru_sdhAppr      			= [];
	  $tampungSemuaDataPengirim_sdhAppr  			= [];
	  $tampungSemuaDataUsername_sdhAppr  			= [];
	  $tampungSemuaDataTglDisetujui      			= [];
	  $tampungSemuaDataTglUpload_sdhAppr 			= [];
	  $tampungSemuaDataJamUpload_sdhAppr 			= [];
	  $tampungSemuaDataJamDisetujui      			= [];
	  $tampungSemuaDataImage_sdhAppr     			= [];
	  $tampungSemuaDataNis_sdhAppr 					= [];
	  $tampungSemuaDataJudul_sdhAppr     			= [];
	  $tampungSemuaDataSiswaOrNamaGroup_sdhAppr 	= [];
	  $tampungSemuaDataIsi_sdhAppr       			= [];
	  $elementSemuaNotifAppr            	 		= '';

	  $countSemuaCheckPersonal  					= "";
	 // Akhir Array Semua Data 

	$is_group 	= "group";
	$is_std 	= "std";

	############################################################################################################
	// Data Sudah di Approve

    	$queryApproved         = mysqli_query($con, "
	     	SELECT 
	 		guru.nip as nip_guru,
	 		guru.nama as nama_guru,
	 		guru.username as username_guru,
	 		siswa.nis AS nis_or_id_group_kelas,
	 		siswa.nama as nama_siswa_or_group,
	 		ruang_pesan.room_key as room_key,
	 		daily_siswa_approved.id as daily_id,
	 		daily_siswa_approved.title_daily as judul_daily,
	 		daily_siswa_approved.isi_daily as isi_daily,
	 		daily_siswa_approved.tanggal_dibuat as tgl_dibuat,
	 		daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_posted,
	 		daily_siswa_approved.image as foto_upload,
	 		ruang_pesan.daily_id as daily_id
	 		FROM daily_siswa_approved
	 		LEFT JOIN guru
	 		ON daily_siswa_approved.from_nip = guru.nip
	 		LEFT JOIN siswa
	 		ON daily_siswa_approved.nis_siswa = siswa.nis
	 		LEFT JOIN ruang_pesan
	 		ON daily_siswa_approved.id = ruang_pesan.daily_id
	 		WHERE daily_siswa_approved.nis_siswa = '$nis'
	 		AND daily_siswa_approved.tanggal_dibuat >= '$arrTgl[tgl_awal]' 
			AND daily_siswa_approved.tanggal_dibuat <= '$arrTgl[tgl_akhir]'
	 		AND daily_siswa_approved.status_approve = 1
	 		UNION
	 		SELECT 
	 		group_siswa_approved.from_nip as nip_guru,
	 		guru.nama as nama_guru,
	 		guru.username as username_guru,
	 		group_kelas.id AS id_group,
	 		group_kelas.nama_group_kelas as nama_group,
	 		ruang_pesan.room_key as room_key,
	 		group_siswa_approved.group_kelas_id as id_group,
	 		group_siswa_approved.title_daily as judul_daily,
	 		group_siswa_approved.isi_daily as isi_daily,
	 		group_siswa_approved.tanggal_dibuat as tgl_dibuat,
	 		group_siswa_approved.tanggal_disetujui_atau_tidak as tgl_posted,
	 		group_siswa_approved.image as foto_upload,
	 		ruang_pesan.daily_id as daily_id
	 		FROM group_siswa_approved
	 		LEFT JOIN guru
	 		ON group_siswa_approved.from_nip = guru.nip
	 		LEFT JOIN ruang_pesan
	 		ON group_siswa_approved.id = ruang_pesan.daily_id
	 		LEFT JOIN group_kelas
	 		ON group_kelas.id = group_siswa_approved.group_kelas_id
	 		WHERE group_siswa_approved.group_kelas_id = '$getIdGroup'
	 		AND group_siswa_approved.tanggal_dibuat >= '$arrTgl[tgl_awal]' 
			AND group_siswa_approved.tanggal_dibuat <= '$arrTgl[tgl_akhir]'
	 		AND group_siswa_approved.status_approve = 1
	 		ORDER BY tgl_dibuat DESC 
    	");

    	$queryApprovedAll      = mysqli_query($con, "
	      	SELECT 
	 		guru.nip as nip_guru,
	 		guru.nama as nama_guru,
	 		guru.username as username_guru,
	 		siswa.nis AS nis_or_id_group_kelas,
	 		siswa.nama as nama_siswa_or_group,
	 		ruang_pesan.room_key as room_key,
	 		daily_siswa_approved.id as daily_id,
	 		daily_siswa_approved.title_daily as judul_daily,
	 		daily_siswa_approved.isi_daily as isi_daily,
	 		daily_siswa_approved.tanggal_dibuat as tgl_dibuat,
	 		daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_posted,
	 		daily_siswa_approved.image as foto_upload,
	 		ruang_pesan.daily_id as daily_id
	 		FROM daily_siswa_approved
	 		LEFT JOIN guru
	 		ON daily_siswa_approved.from_nip = guru.nip
	 		LEFT JOIN siswa
	 		ON daily_siswa_approved.nis_siswa = siswa.nis
	 		LEFT JOIN ruang_pesan
	 		ON daily_siswa_approved.id = ruang_pesan.daily_id
	 		WHERE daily_siswa_approved.nis_siswa = '$nis'
	 		AND daily_siswa_approved.status_approve = 1
	 		UNION
	 		SELECT 
	 		group_siswa_approved.from_nip as nip_guru,
	 		guru.nama as nama_guru,
	 		guru.username as username_guru,
	 		group_kelas.id AS id_group,
	 		group_kelas.nama_group_kelas as nama_group,
	 		ruang_pesan.room_key as room_key,
	 		group_siswa_approved.group_kelas_id as id_group,
	 		group_siswa_approved.title_daily as judul_daily,
	 		group_siswa_approved.isi_daily as isi_daily,
	 		group_siswa_approved.tanggal_dibuat as tgl_dibuat,
	 		group_siswa_approved.tanggal_disetujui_atau_tidak as tgl_posted,
	 		group_siswa_approved.image as foto_upload,
	 		ruang_pesan.daily_id as daily_id
	 		FROM group_siswa_approved
	 		LEFT JOIN guru
	 		ON group_siswa_approved.from_nip = guru.nip
	 		LEFT JOIN ruang_pesan
	 		ON group_siswa_approved.id = ruang_pesan.daily_id
	 		LEFT JOIN group_kelas
	 		ON group_kelas.id = group_siswa_approved.group_kelas_id
	 		WHERE group_siswa_approved.group_kelas_id = '$getIdGroup'
	 		AND group_siswa_approved.status_approve = 1
	 		ORDER BY tgl_posted DESC
    	");

    	foreach ($queryApproved as $data_appr) {

    	  $tampungDataTglUploadOri[]      			= $data_appr['tgl_posted'];
    	  $tampungDataRoomKey[] 		  			= $data_appr['room_key'];
	      $tampungDataID_sdhAppr[]        			= $data_appr['daily_id'];
	      $tampungDataNIP_sdhAppr[]       			= $data_appr['nip_guru'];
	      $tampungDataNis_sdhAppr[] 				= $data_appr['nis_or_id_group_kelas'];
	      $tampungDataUsername_sdhAppr[]  			= strtoupper($data_appr['username_guru']);
	      $tampungDataSiswaOrNamaGroup_sdhAppr[]    = strtoupper($data_appr['nama_siswa_or_group']);
	      $tampungDataPengirim_sdhAppr[]  			= $data_appr['nama_guru'];
	      $tampungDataTglDiUpload[]       			= substr($data_appr['tgl_dibuat'], 0, 11);
	      $tampungDataTglDisetujui[]      			= substr($data_appr['tgl_posted'], 0, 11);
	      $tampungDataJamDiUpload[]       			= substr($data_appr['tgl_dibuat'], 11, 19);
	      $tampungDataJamDisetujui[]      			= substr($data_appr['tgl_posted'], 11, 19);
	      $tampungDataImage_sdhAppr[]     			= $data_appr['foto_upload'];
	      $tampungDataJudul_sdhAppr[]     			= $data_appr['judul_daily'];
	      $tampungDataIsi_sdhAppr[]       			= $data_appr['isi_daily'];

    	}

    	foreach ($queryApprovedAll as $data_all_appr) {

    	  $tampungSemuaDataTglUploadOri[]      			= $data_all_appr['tgl_posted'];
    	  $tampungSemuaDataRoomKey[] 		  			= $data_all_appr['room_key'];
	      $tampungSemuaDataID_sdhAppr[]        			= $data_all_appr['daily_id'];
	      $tampungSemuaDataNIP_sdhAppr[]       			= $data_all_appr['nip_guru'];
	      $tampungSemuaDataNis_sdhAppr[] 				= $data_all_appr['nis_or_id_group_kelas'];
	      $tampungSemuaDataUsername_sdhAppr[]  			= strtoupper($data_all_appr['username_guru']);
	      $tampungSemuaDataSiswaOrNamaGroup_sdhAppr[]   = strtoupper($data_all_appr['nama_siswa_or_group']);
	      $tampungSemuaDataPengirim_sdhAppr[]  			= $data_all_appr['nama_guru'];
	      $tampungSemuaDataTglDiUpload[]       			= $data_all_appr['tgl_posted'];
	      $tampungSemuaDataTglDisetujui[]      			= tgl_indo(substr($data_all_appr['tgl_posted'], 0, -8));
	      $tampungSemuaDataJamDiUpload[]       			= substr($data_all_appr['tgl_dibuat'], 11, 19);
	      $tampungSemuaDataJamDisetujui[]      			= substr($data_all_appr['tgl_posted'], -8);
	      $tampungSemuaDataImage_sdhAppr[]     			= $data_all_appr['foto_upload'];
	      $tampungSemuaDataJudul_sdhAppr[]     			= $data_all_appr['judul_daily'];
	      $tampungSemuaDataIsi_sdhAppr[]       			= $data_all_appr['isi_daily'];

    	}

    	// var_dump($tampungSemuaDataTglDisetujui);exit;

      	$countDataApproved    = mysqli_num_rows($queryApproved);
	    $countDataApprovedAll = mysqli_num_rows($queryApprovedAll);

	    if($countDataApproved > 5) {

	      for ($i = 0; $i < 5; $i++) {

	        $explode                = explode(" ", $tampungDataPengirim_sdhAppr[$i]);
	        $isiPesanNamaSiswa      = "";
	        $isiPesan               = "";
	        $isiNamaGuru      		= "";
	        
	        $allStdOrGroup 	= strlen($tampungDataSiswaOrNamaGroup_sdhAppr[$i]) > 13 ? $isiPesanNamaSiswa .= substr($tampungDataSiswaOrNamaGroup_sdhAppr[$i], 0, 13) . " ..." : $tampungDataSiswaOrNamaGroup_sdhAppr[$i];
	        $semuaPesan 	= strlen($tampungDataJudul_sdhAppr[$i]) > 15 ? $isiPesan .= substr($tampungDataJudul_sdhAppr[$i], 0, 15) . " ..." : $tampungDataJudul_sdhAppr[$i];
	        $semuaNamaGuru  = strlen($tampungDataPengirim_sdhAppr[$i]) > 17 ? $isiNamaGuru .= substr($tampungDataPengirim_sdhAppr[$i], 0, 17) . " ..." : $tampungDataPengirim_sdhAppr[$i];

	        $dailyId  		= $tampungDataID_sdhAppr[$i];

	        // Check Id Personal Or Id Group
	      	$queryIdPersonal = mysqli_query($con, "
		        SELECT id FROM daily_siswa_approved
		        WHERE id = '$dailyId'
	      	");

	      	$queryCheckIdGroup = mysqli_query($con, "
		        SELECT id FROM group_siswa_approved
		        WHERE id = '$dailyId'
	      	");

	      	$countCheckPersonal = mysqli_num_rows($queryIdPersonal);
	      	$countCheckIdGroup  = mysqli_num_rows($queryCheckIdGroup);

	        if ($countCheckPersonal == 1) {

	      		$forIsiNotifSdhAppr .= '
		          	<li style="background-color: aqua;" class="apprlist" data-group_or_std="'. $is_std .'" data-nip_guru="'. $tampungDataNIP_sdhAppr[$i] .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-username_guru = "'. $tampungDataUsername_sdhAppr[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNis_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataPengirim_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-siswa_was_appr="'. $tampungDataSiswaOrNamaGroup_sdhAppr[$i] .'" data-tgl_upload = "'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] . '" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
		            	<a href="#">
			              	<h4 style="font-size: 12px;">
			                	<small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
			              	</h4>
				            <div class="pull-left" style="margin-left: 10px; margin-top: 15px;">
				                <img src="../imgstatis/logo2.png" style="width: 35px;">
				            </div>
			              	<h4 style="font-size: 12px; margin-top: 24px;">
		                  		<p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 15px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $semuaNamaGuru . '</strong> </p>
		                  	</h4>
			              	<h4 style="font-size: 12px;">
			                	<p style="font-size: 13px;margin-left: 59px;"> 
				                  	<strong> TITLE </strong> <span style="margin-left: 17.5px;"> : </span> 
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
		          	<li style="background-color: greenyellow;" class="apprlist" data-group_or_std="'. $is_group .'" data-nip_guru="'. $tampungDataNIP_sdhAppr[$i] .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-username_guru = "'. $tampungDataUsername_sdhAppr[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNis_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataPengirim_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-siswa_was_appr="'. $tampungDataSiswaOrNamaGroup_sdhAppr[$i] .'" data-tgl_upload = "'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] . '" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
		            	<a href="#">
			              	<h4 style="font-size: 12px;">
			                	<small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
			              	</h4>
				            <div class="pull-left" style="margin-left: 10px; margin-top: 15px;">
				                <img src="../imgstatis/logo2.png" style="width: 35px;">
				            </div>
			              	<h4 style="font-size: 12px; margin-top: 24px;">
		                  		<p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 15px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $semuaNamaGuru . '</strong> </p>
		                  	</h4>
			              	<h4 style="font-size: 12px;">
			                	<p style="font-size: 13px;margin-left: 59px;"> 
				                  	<strong> TITLE </strong> <span style="margin-left: 17.5px;"> : </span> 
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

	    } else if ($countDataApproved < 6) {

	      for ($i = 0; $i < $countDataApproved; $i++) {

	        $explode                = explode(" ", $tampungDataPengirim_sdhAppr[$i]);

	        $isiNamaSiswaOrGroup    = "";
	        $isiNamaGuru      		= "";
	        $isiPesan               = "";
	        

	        $allStdOrGroup 	= strlen($tampungDataSiswaOrNamaGroup_sdhAppr[$i]) > 13 ? $isiNamaSiswaOrGroup .= substr($tampungDataSiswaOrNamaGroup_sdhAppr[$i], 0, 13) . " ..." : $tampungDataSiswaOrNamaGroup_sdhAppr[$i];
	        $semuaNamaGuru  = strlen($tampungDataPengirim_sdhAppr[$i]) > 17 ? $isiNamaGuru .= substr($tampungDataPengirim_sdhAppr[$i], 0, 17) . " ..." : $tampungDataPengirim_sdhAppr[$i];
	        $semuaPesan 	= strlen($tampungDataJudul_sdhAppr[$i]) > 17 ? $isiPesan .= substr($tampungDataJudul_sdhAppr[$i], 0, 17) . " ..." : $tampungDataJudul_sdhAppr[$i];

	        $dailyId  = $tampungDataID_sdhAppr[$i];

	        // Check Id Personal Or Id Group
	      	$queryIdPersonal = mysqli_query($con, "
		        SELECT id FROM daily_siswa_approved
		        WHERE id = '$dailyId'
	      	");

	      	$queryCheckIdGroup = mysqli_query($con, "
		        SELECT id FROM group_siswa_approved
		        WHERE id = '$dailyId'
	      	");

	      	$countCheckPersonal = mysqli_num_rows($queryIdPersonal);
	      	$countCheckIdGroup  = mysqli_num_rows($queryCheckIdGroup);

	      	if ($countCheckPersonal == 1) {

	      		$forIsiNotifSdhAppr .= '
		          	<li style="background-color: aqua;" class="apprlist" data-group_or_std="'. $is_std .'" data-nip_guru="'. $tampungDataNIP_sdhAppr[$i] .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-username_guru = "'. $tampungDataUsername_sdhAppr[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNis_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataPengirim_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-siswa_was_appr="'. $tampungDataSiswaOrNamaGroup_sdhAppr[$i] .'" data-tgl_upload = "'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] . '" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
		            	<a href="#">
			              	<h4 style="font-size: 12px;">
			                	<small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
			              	</h4>
				            <div class="pull-left" style="margin-left: 10px; margin-top: 15px;">
				                <img src="../imgstatis/logo2.png" style="width: 35px;">
				            </div>
			              	<h4 style="font-size: 12px; margin-top: 24px;">
		                  		<p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 15px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $semuaNamaGuru . '</strong> </p>
		                  	</h4>
			              	<h4 style="font-size: 12px;">
			                	<p style="font-size: 13px;margin-left: 59px;"> 
				                  	<strong> TITLE </strong> <span style="margin-left: 17.5px;"> : </span> 
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
		          	<li style="background-color: greenyellow;" class="apprlist" data-group_or_std="'. $is_group .'" data-nip_guru="'. $tampungDataNIP_sdhAppr[$i] .'" data-tgl_ori="'. $tampungDataTglUploadOri[$i] .'" data-room_key="'. $tampungDataRoomKey[$i] .'" data-username_guru = "'. $tampungDataUsername_sdhAppr[$i] .'" data-nis_siswa_was_appr="'. $tampungDataNis_sdhAppr[$i] .'" data-nama_guru_lengkap="'. $tampungDataPengirim_sdhAppr[$i] .'" data-pengirim="'. $explode[0] .'" data-siswa_was_appr="'. $tampungDataSiswaOrNamaGroup_sdhAppr[$i] .'" data-tgl_upload = "'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] . '" data-tgl_approved ="'. tgl_indo($tampungDataTglDisetujui[$i]) . " " . $tampungDataJamDisetujui[$i] .'" data-img="'. $tampungDataImage_sdhAppr[$i] .'" data-judul="'. $tampungDataJudul_sdhAppr[$i] .'" data-isian="'. $tampungDataIsi_sdhAppr[$i] .'" data-toggle="modal">
		            	<a href="#">
			              	<h4 style="font-size: 12px;">
			                	<small style="font-size: 12px;float: right;margin-top: -13px;"> <i class="fa fa-clock-o" style="margin-right: 5px;"></i>'. getDateTimeDiff($tampungDataTglDisetujui[$i]." ".$tampungDataJamDisetujui[$i]) . '</small>
			              	</h4>
				            <div class="pull-left" style="margin-left: 10px; margin-top: 15px;">
				                <img src="../imgstatis/logo2.png" style="width: 35px;">
				            </div>
			              	<h4 style="font-size: 12px; margin-top: 24px;">
		                  		<p style="font-size: 13px;margin-left: 60px;"> <strong> FROM </strong> <span style="margin-left: 15px;"> : </span> <strong id="from_daily" style="margin-left: 7px;">'. $semuaNamaGuru . '</strong> </p>
		                  	</h4>
			              	<h4 style="font-size: 12px;">
			                	<p style="font-size: 13px;margin-left: 59px;"> 
				                  	<strong> TITLE </strong> <span style="margin-left: 17.5px;"> : </span> 
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

	    // var_dump($tampungSemuaDataPengirim_sdhAppr);exit;

	    for ($i=0; $i < $countDataApprovedAll; $i++) { 

	    	$explode                	= explode(" ", $tampungSemuaDataPengirim_sdhAppr[$i]);
	        $isiSemuaPesanNamaSiswa     = "";
	        $isiSemuaPesan              = "";
	        $isiSemuaNamaGuru      		= "";
	        
	        $semuaPesanAppr 	= strlen($tampungSemuaDataJudul_sdhAppr[$i]) > 15 ? $isiSemuaPesan .= substr($tampungSemuaDataJudul_sdhAppr[$i], 0, 15) . " ..." : $tampungSemuaDataJudul_sdhAppr[$i];
	        $semuaNamaGuruAppr 	= strlen($tampungSemuaDataPengirim_sdhAppr[$i]) > 17 ? $isiSemuaNamaGuru .= substr($tampungSemuaDataPengirim_sdhAppr[$i], 0, 17) . " ..." : $tampungSemuaDataPengirim_sdhAppr[$i];

	        $dailyId  		= $tampungSemuaDataID_sdhAppr[$i];

	        // Check Id Personal Or Id Group
	      	$queryIdPersonal = mysqli_query($con, "
		        SELECT id FROM daily_siswa_approved
		        WHERE id = '$dailyId'
	      	");

	      	$queryCheckIdGroup = mysqli_query($con, "
		        SELECT id FROM group_siswa_approved
		        WHERE id = '$dailyId'
	      	");

	      	$countAllCheckPersonal = mysqli_num_rows($queryIdPersonal);
	      	$countAllCheckIdGroup  = mysqli_num_rows($queryCheckIdGroup);

	      	if ($countAllCheckPersonal == 1) {

	      		$output_all_approve .= '
		        	<div>
		              	<div class="box-body" style="background-color: aqua; margin-top: -9px; border: 3px solid black;">
		              		<div class="pull-left" style="margin-left: 10px; margin-top: 27px;">
				                <img src="../imgstatis/logo2.png" style="width: 35px;">
				            </div>
			              	<div class="box-body detail_status" data-tgl_ori="'. $tampungSemuaDataTglDiUpload[$i] .'" data-nisoridgroup="'. $tampungSemuaDataNis_sdhAppr[$i] .'" data-namaguru="'. $tampungSemuaDataPengirim_sdhAppr[$i] .'" data-nipguru="'. $tampungSemuaDataNIP_sdhAppr[$i] .'" data-isi_daily="'. $tampungSemuaDataIsi_sdhAppr[$i] .'" data-title_daily="'. $tampungSemuaDataJudul_sdhAppr[$i] .'" data-foto_upload="'. $tampungSemuaDataImage_sdhAppr[$i] .'" data-namasiswa_or_group="'. $tampungSemuaDataSiswaOrNamaGroup_sdhAppr[$i] .'" data-group_orstd='. "std" .' data-tgl_posted="'. $tampungSemuaDataTglDisetujui[$i] . '" data-time_posted='. $tampungSemuaDataJamDisetujui[$i] . ' data-roomkey='. $tampungSemuaDataRoomKey[$i] .' data-from="'.$tampungSemuaDataPengirim_sdhAppr[$i].'" data-toggle="modal" data-target="modal-default" style="position: relative; width: auto; margin-top: 13px; margin-left: 60px; margin-bottom: 15px;">
				                
				                <p style="font-size:13px;"> <strong> FROM <span style="margin-left: 8.5px;"> : <span style="margin-left: 5px;"> '. $tampungSemuaDataPengirim_sdhAppr[$i] . ' </strong> </p>
				                <p style="font-size:13px;"> <strong> TITLE <span style="margin-left: 10px;"> :  </strong> </span> <strong style="margin-left: 5px;"> '. (strlen($tampungSemuaDataJudul_sdhAppr[$i]) > 48 ? substr($tampungSemuaDataJudul_sdhAppr[$i], 0,48) . " ..." : $tampungSemuaDataJudul_sdhAppr[$i]) .' </strong> </p>
				               
				            </div>
			            </div>
		            </div>
		            <br>
		            <br>
		       	';

	      	} else if ($countAllCheckIdGroup == 1) {

	      		$output_all_approve .= '
		        	<div>
		              	<div class="box-body" style="background-color: greenyellow; margin-top: -9px; border: 3px solid black;">
		              		<div class="pull-left" style="margin-left: 10px; margin-top: 27px;">
				                <img src="../imgstatis/logo2.png" style="width: 35px;">
				            </div>
			              	<div class="box-body detail_status" data-tgl_ori="'. $tampungSemuaDataTglDiUpload[$i] .'" data-nisoridgroup="'. $tampungSemuaDataNis_sdhAppr[$i] .'" data-namaguru="'. $tampungSemuaDataPengirim_sdhAppr[$i] .'" data-nipguru="'. $tampungSemuaDataNIP_sdhAppr[$i] .'" data-isi_daily="'. $tampungSemuaDataIsi_sdhAppr[$i] .'" data-title_daily="'. $tampungSemuaDataJudul_sdhAppr[$i] .'" data-foto_upload="'. $tampungSemuaDataImage_sdhAppr[$i] .'" data-namasiswa_or_group="'. $tampungSemuaDataSiswaOrNamaGroup_sdhAppr[$i] .'" data-group_orstd='. "group" .' data-tgl_posted="'. $tampungSemuaDataTglDisetujui[$i] . '" data-time_posted='. $tampungSemuaDataJamDisetujui[$i] . ' data-roomkey='. $tampungSemuaDataRoomKey[$i] .' data-from="'.$tampungSemuaDataPengirim_sdhAppr[$i].'" data-toggle="modal" data-target="modal-default" style="position: relative; width: auto; margin-top: 13px; margin-left: 60px; margin-bottom: 15px;">
				                
				                <p style="font-size:13px;"> <strong> FROM <span style="margin-left: 8.5px;"> : <span style="margin-left: 5px;"> '. $tampungSemuaDataPengirim_sdhAppr[$i] . ' </strong> </p>
				                <p style="font-size:13px;"> <strong> TITLE <span style="margin-left: 10px;"> :  </strong> </span> <strong style="margin-left: 5px;"> '. (strlen($tampungSemuaDataJudul_sdhAppr[$i]) > 48 ? substr($tampungSemuaDataJudul_sdhAppr[$i], 0,48) . " ..." : $tampungSemuaDataJudul_sdhAppr[$i]) .' </strong> </p>
				               
				            </div>
			            </div>
		            </div>
		            <br>
		            <br>
		       	';

	      	}

	    }

    // Akhir Data Sudah di Approve
    ############################################################################################################

	##############################
  	// Data All Info 

	  	$forIsiNotifInfo 	= '';
	  	$dataArrNIS 		= [];
	  	$dataArrNama 		= [];
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
		  	info_pengumuman_pembayaran.nis = '$nis' 
			AND 
		  	info_pengumuman_pembayaran.status_pembayaran = 0 
		  	OR
		  	info_pengumuman_pembayaran.status_pembayaran IS NULL
		  	ORDER BY info_pengumuman_pembayaran.tanggal_dibuat DESC
	  	");

  		foreach($dataAllInfo as $data) {

		  	$dataArrNIS[]         	= $data['nis_siswa'];
		  	$dataArrNama[] 			= strtoupper($data['nama_siswa']);
		  	$dataArrJenisBayar[] 	= $data['jenis_bayar'];
		  	$dataArrKetBayar[]    	= $data['ket_bayar'];
		  	$dataArrNominalByr[]  	= $data['nominal'];
		  	$dataArrTglDibuat[]   	= substr($data['tgl_dibuat'], 0, 11);
		  	$dataArrJamDibuat[]   	= substr($data['tgl_dibuat'], 11, 19);
  		}

  		$jumlahDataInfo = count($dataArrNama);

  		if ($jumlahDataInfo > 5) {
  			for ($i = 0; $i < 5; $i++) {

  				// $explode                = explode(" ", $tampungDataPengirim_sdhAppr[$i]);
      			$isiPesanNamaSiswa      = "";
      			$isiPesan               = "";
        
      			$semuaNamaSiswaOrGroup 		= strlen($dataArrNama[$i]) > 17 ? $isiPesanNamaSiswa .= substr($dataArrNama[$i], 0, 17) . " ..." : $dataArrNama[$i];
      			$semuaPesan 			= strlen($dataArrJenisBayar[$i]) > 13 ? $isiPesan .= substr($dataArrJenisBayar[$i], 0, 13) . " ..." : $dataArrJenisBayar[$i];

      			$forIsiNotifInfo .= '
        			<li class="infolist" data-nominal="'. $dataArrNominalByr[$i] .'" data-nama_siswa="'. $dataArrNama[$i] .'" data-nis_siswa="'. $dataArrNIS[$i] .'" data-tgl_upload = "'. tgl_indo($dataArrTglDibuat[$i]) . " " . $dataArrJamDibuat[$i] . '" data-jenis_byr="'. $dataArrJenisBayar[$i] .'" data-ket_byr="'. $dataArrKetBayar[$i] .'" data-toggle="modal">
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
		                  			<strong id="title_daily" style="margin-left: 7px;"> 
		                    		'. $semuaNamaSiswaOrGroup .'
		                  			</strong> 
		                		</p>
		              		</h4>
			              	<h4 style="font-size: 12px;">
				                <p style="font-size: 15px;margin-left: 45px;"> 
				                  <strong> INFO </strong> <span style="margin-left: 15.5px;"> : </span> 
				                  <strong id="title_daily" style="margin-left: 7px; font-size: 13px;"> 
				                    '. ' Pembayaran ' . $semuaPesan .'
				                  </strong> 
				                </p>
			              	</h4>
	            		</a>
          			</li>
      			';

  			}

		} elseif ($jumlahDataInfo <= 5) {

		  	for ($i = 0; $i < $jumlahDataInfo; $i++) {

		  		// $explode                = explode(" ", $tampungDataPengirim_sdhAppr[$i]);
		      	$isiPesanNamaSiswa      = "";
		      	$isiPesan               = "";
		        
		      	$semuaNamaSiswaOrGroup = strlen($dataArrNama[$i]) > 17 ? $isiPesanNamaSiswa .= substr($dataArrNama[$i], 0, 17) . " ..." : $dataArrNama[$i];
		      	$semuaPesan 		= strlen($dataArrJenisBayar[$i]) > 13 ? $isiPesan .= substr($dataArrJenisBayar[$i], 0, 13) . " ..." : $dataArrJenisBayar[$i];

		      	$forIsiNotifInfo .= '
		        	<li class="infolist" data-nominal="'. rupiahFormat($dataArrNominalByr[$i]) .'" data-nama_siswa="'. $dataArrNama[$i] .'" data-nis_siswa="'. $dataArrNIS[$i] .'" data-tgl_upload = "'. tgl_indo($dataArrTglDibuat[$i]) . " " . $dataArrJamDibuat[$i] . '" data-jenis_byr="'. $dataArrJenisBayar[$i] .'" data-ket_byr="'. $dataArrKetBayar[$i] .'" data-toggle="modal">
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
			                    '. $semuaNamaSiswaOrGroup .'
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

		}

  // Akhir Data All Info
	##############################

    $arr['notif_appr']              = $countDataApproved;
  	$arr['notif_appr_all']          = $countDataApprovedAll;

  	// Isi notif sudah di approve
	$arr['isi_notif_approved']      = $forIsiNotifSdhAppr;
	$arr['elementNotifAppr']        = $elementNotifAppr;
  	// Akhir isi notif sudah di approve

	// Notif Info
  	$arr['notif_info']      		= $jumlahDataInfo;
  	$arr['isi_notif_info']  		= $forIsiNotifInfo;
  	// Akhir Notif Info

  	$arr['all_daily'] 				= $output_all_approve;

  	echo json_encode($arr);

?>