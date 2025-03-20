<?php  

	$timeOut        = $_SESSION['expire_paud'];
    
  	$timeRunningOut = time() + 5;

	$timeIsOut 		= 0;
	$sesi      		= 0;
	$nama      		= "";
	$nisoridgroup   = 0;
	$guru      		= "";
	$key_room  		= "";
	$users     		= "";
	$sesiKomen 		= 1;

	$nipGuru   		= "";
	$nipKepsek      = "";

	date_default_timezone_set("Asia/Jakarta");

	$tglSkrngAwal 	= "";
	$tglSkrngAkhir 	= "";

	$fonnte_err 	= 0;

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

  	$is_SD      = "/SD/i";

  	$is_SD1 	= [$_SESSION['kls_siswa_paud']];
  	$is_PAUD 	= [$_SESSION['kls_siswa_paud']];

  	$foundDataSD    = preg_match($is_SD, $_SESSION['kls_siswa_paud']);
  	$foundDataPAUD  = 0;

  	if (in_array("KB", $is_PAUD) ) {
  		$foundDataPAUD = 1;
  		// echo "Kelas KB " . $foundDataPAUD;exit;
  	} else if (in_array("TKA", $is_PAUD) ) {
  		$foundDataPAUD = 1;
  		// echo "Kelas TKA " . $foundDataPAUD;exit;
  	} else if (in_array("TKB", $is_PAUD) ) {
  		$foundDataPAUD = 1;
  		// echo "Kelas TKB " . $foundDataPAUD;exit;
  	}

	$fromPage  		= "";
	$empty 			= "";
	$countDataChat  = 0;

	$isGroup 		= false;

	$nisotm   = $_SESSION['c_otm_paud'];

	// echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

	if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

		$_SESSION['form_success'] = "session_time_out";

	    $timeIsOut = 1;
	    error_reporting(1);

  	} else {

  		if (isset($_POST['krm'])) {

  			$roomKey    	= $_POST['roomkey'];
	  		$nama 			= htmlspecialchars($_POST['nama']);
	  		$nisOrIdGroup 	= htmlspecialchars($_POST['nis']);
	  		$guru 			= htmlspecialchars($_POST['guru']);
	  		$foto 			= htmlspecialchars($_POST['foto']);
	  		$tglPosting 	= $_POST['tglpost'];
	  		$tglOri     	= $_POST['tglori'];
	  		$judul      	= htmlspecialchars($_POST['judul']);
	  		$isi        	= $_POST['isi'];
	  		$nipGuru    	= $_POST['nipguru_lookdaily'];
	  		$users      	= $nisotm;

		  	$tglSkrngAwal   = date("Y-m-d") . " 00:00:00";
		  	$tglSkrngAkhir  = date("Y-m-d") . " 23:59:59";

	  		$sesi 		= 1;

	  		if ($tglOri < $tglSkrngAwal) {
		  		$sesiKomen = 0;
		  	} else {
		  		$sesiKomen = 1;
		  	}

		  	$getDataKomenOther = mysqli_query($con, "
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
		      	ruang_pesan.room_key LIKE '%$roomKey%'
		      	ORDER BY tbl_komentar.id
		    ");

		    $fromPage   = $_POST['frompage'];

		    $countDataChat = mysqli_num_rows($getDataKomenOther);

		    if ($foundDataSD == 1) {
		  		$nipKepsek = "2019032";
		  	} else if ($foundDataPAUD == 1) {
		  		$nipKepsek = "2019034";
		  	}

	  		$key_room   = $roomKey;
	  		$isGroup = false;
	  		
	  	} else if (isset($_POST['redirectLookDaily'])) {

	  		$roomKey    	= $_POST['roomkey_lookdaily'];
	  		$nama 			= htmlspecialchars($_POST['nama_siswa_or_groupkelas_lookdaily']);
	  		$nisOrIdGroup   = htmlspecialchars($_POST['nis_or_idgroup_lookdaily']);
	  		// echo $nisoridgroup;exit;
	  		$nipGuru 		= $_POST['nipguru_lookdaily'];
	  		$guru 			= htmlspecialchars($_POST['guru_lookdaily']);
	  		$foto 			= $_POST['foto_upload_lookdaily'];
	  		$tglPosting 	= $_POST['tgl_posting_lookdaily'];
	  		$tglOri     	= $_POST['tglori_posting_lookdaily'];
	  		$judul      	= htmlspecialchars($_POST['jdl_posting_lookdaily']);
	  		$isi 			= $_POST['isi_posting_lookdaily'];
	  		$users      	= $nisotm;
	  		$nipKepsek  	= $_POST['nipkepsek_lookdaily'];

	  		$sesi 			= 1;

	  		$getDataKomenOther = mysqli_query($con, "
		      SELECT 
		      tbl_komentar.room_id as r_id,
		      tbl_komentar.code_user as fromnip,
		      guru.nama as nama_guru,
		      siswa.nama as nama_siswa,
		      kepala_sekolah.nama as nama_kepsek,
		      tbl_komentar.stamp as tanggal_kirim,
		      tbl_komentar.isi_komentar as pesan
		      FROM 
		      tbl_komentar 
		      LEFT JOIN ruang_pesan
		      ON tbl_komentar.room_id = ruang_pesan.room_key
		      LEFT JOIN guru
		      ON tbl_komentar.code_user = guru.nip
		      LEFT JOIN daily_siswa_approved
		      ON ruang_pesan.daily_id = daily_siswa_approved.id
		      LEFT JOIN akses_otm
		      ON tbl_komentar.code_user = akses_otm.nis_siswa
		      LEFT JOIN siswa
		      ON akses_otm.nis_siswa = siswa.nis
		      LEFT JOIN kepala_sekolah
		      ON tbl_komentar.code_user = kepala_sekolah.nip
		      WHERE
		      ruang_pesan.room_key LIKE '%$roomKey%'
		      ORDER BY tbl_komentar.id
		    ");

	  		// Check Nis Or ID Group
	  		$queryCheckNis = mysqli_query($con, "
	  			SELECT nis FROM siswa WHERE nis = '$nisOrIdGroup'
	  		");

	  		$queryCheckIdGroup = mysqli_query($con, "
	  			SELECT id FROM group_kelas WHERE id = '$nisOrIdGroup'
	  		");

	  		$countCheckNis 		= mysqli_num_rows($queryCheckNis);
	  		$countCheckIDGroup 	= mysqli_num_rows($queryCheckIdGroup);

	  		// Get daily id
	  		$getNisFromDailySiswaAppr = mysqli_fetch_array(mysqli_query($con, "
	  			SELECT nis_siswa FROM daily_siswa_approved 
	  			WHERE id IN (
	  				SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomKey'
	  			)
	  		"));

	  		if ($nisotm == $getNisFromDailySiswaAppr['nis_siswa']) {

		  		if ($countCheckIDGroup == 1) {
		  			$isGroup = true;

		  			if (!empty(isset($_GET['q']))) {

		  				$countDataChat = mysqli_num_rows($getDataKomenOther);

			
					  	$arrTgl               = [];
						  
					  	$tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
					  	$tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

					  	$fromPage   = htmlspecialchars($_POST['frompage_lookdaily']);

					  	if ($tglOri < $tglSkrngAwal) {
					  		$sesiKomen = 0;
					  	} else {
					  		$sesiKomen = 1;
					  	}

					  	if ($foundDataSD == 1) {
					  		$nipKepsek = "2019032";
					  	} else if ($foundDataPAUD == 1) {
					  		$nipKepsek = "2019034";
					  	}

				  		$key_room   = $roomKey;

			  		} else {

			  			$sesi = 0;
			  			$isGroup = "noparams";
		  				$_SESSION['data'] = 'nodata';

			  		}

		  		} else if ($countCheckNis == 1) {

		  			if (!empty(isset($_GET['q']))) {

		  				$countDataChat = mysqli_num_rows($getDataKomenOther);

			
					  	$arrTgl               = [];
						  
					  	$tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
					  	$tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

					  	$fromPage   = htmlspecialchars($_POST['frompage_lookdaily']);

					  	if ($tglOri < $tglSkrngAwal) {
					  		$sesiKomen = 0;
					  	} else {
					  		$sesiKomen = 1;
					  	}

					  	if ($foundDataSD == 1) {
					  		$nipKepsek = "2019032";
					  	} else if ($foundDataPAUD == 1) {
					  		$nipKepsek = "2019034";
					  	}

				  		$key_room   = $roomKey;

			  		} else {

			  			$sesi = 0;
			  			$isGroup = "noparams";
		  				$_SESSION['data'] = 'nodata';

			  		}

		  		}

	  		} else {

	  			$_SESSION['data'] = 'user_invalid';
	  			$sesi = 2 ;

	  		}

	  	} elseif (isset($_POST['send_mssg'])) {

	  		$roomKey    	= $_POST['roomkey'];
	  		$nama 			= htmlspecialchars($_POST['nama']);
	  		$nisOrIdGroup 	= htmlspecialchars($_POST['nis']);
	  		$guru 			= htmlspecialchars($_POST['guru']);
	  		$foto 			= htmlspecialchars($_POST['foto']);
	  		$tglPosting 	= $_POST['tglpost'];
	  		$tglOri     	= $_POST['tglori'];
	  		$judul      	= htmlspecialchars($_POST['judul']);
	  		$isi   			= $_POST['isi'];

	  		$nipGuru    	= $_POST['nipguru_lookdaily'];

	  		$isKomen   	 	= mysqli_real_escape_string($con, htmlspecialchars($_POST['message'])) . "istextempty";
	  		$fromPage   	= $_POST['frompage'];
	  		$allNumberPhone = [];

	  		$queryDailyStd = mysqli_query($con, "
		        SELECT id FROM daily_siswa_approved WHERE id IN (
		          SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomKey'
		        )
	      	");

	      	$queryDailyGroup = mysqli_query($con, "
		        SELECT id FROM group_siswa_approved WHERE id IN (
		          SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomKey'
		        )
	      	");

	      	$countDailyStd    = mysqli_num_rows($queryDailyStd);
	      	$countDailyGroup  = mysqli_num_rows($queryDailyGroup);

	      	$isGroup = false;

	  		if ($isKomen == "istextempty") {
	  			// echo "Atas " . $isKomen;exit;

	  			$empty = "empty_comment";

			  	$arrTgl               = [];
				
			  	$countDataChat = 0;

			  	$tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
			  	$tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

		  		$sesi 		= 1;

		  		if (!empty(isset($_GET['q']))) {

				  	$getDataKomenOther = mysqli_query($con, "
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
				      	ruang_pesan.room_key LIKE '%$roomKey%'
				      	ORDER BY tbl_komentar.id
				    ");

				  	// Check Nis Or ID Group
				  	$queryCheckGroupKelasID = mysqli_query($con, "
				  		SELECT group_kelas_id FROM group_siswa_approved WHERE id = '$nisoridgroup'
				  	");

				  	$countCheckGroupKelasID = mysqli_num_rows($queryCheckGroupKelasID);

				  	if ($countCheckGroupKelasID == 1) {

				  		$nama = 'GROUP ' . $nama;
				  		$isGroup = true;
				  		$getDataGroupKelasID = mysqli_fetch_assoc($queryCheckGroupKelasID)['group_kelas_id'];
				  		// echo $getDataGroupKelasID;exit;

				  	}

				    $countDataChat = mysqli_num_rows($getDataKomenOther);

				  	$fromPage   	= htmlspecialchars($_POST['frompage']);

			  		$key_room   	= $roomKey;

		  		}

		  		$getDataKomenOther = mysqli_query($con, "
			      SELECT 
			      tbl_komentar.room_id as r_id,
			      tbl_komentar.code_user as fromnip,
			      guru.nama as nama_guru,
			      siswa.nama as nama_siswa,
			      kepala_sekolah.nama as nama_kepsek,
			      tbl_komentar.stamp as tanggal_kirim,
			      tbl_komentar.isi_komentar as pesan
			      FROM 
			      tbl_komentar 
			      LEFT JOIN ruang_pesan
			      ON tbl_komentar.room_id = ruang_pesan.room_key
			      LEFT JOIN guru
			      ON tbl_komentar.code_user = guru.nip
			      LEFT JOIN daily_siswa_approved
			      ON ruang_pesan.daily_id = daily_siswa_approved.id
			      LEFT JOIN akses_otm
			      ON tbl_komentar.code_user = akses_otm.nis_siswa
			      LEFT JOIN siswa
			      ON akses_otm.nis_siswa = siswa.nis
			      LEFT JOIN kepala_sekolah
			      ON tbl_komentar.code_user = kepala_sekolah.nip
			      WHERE
			      ruang_pesan.room_key LIKE '%$roomKey%'
			      ORDER BY tbl_komentar.id
			    ");

			    $countDataChat = mysqli_num_rows($getDataKomenOther);

		  		if ($tglOri < $tglSkrngAwal) {
			  		$sesiKomen = 0;
			  	} else {
			  		$sesiKomen = 1;
			  	}

			  	if ($foundDataSD == 1) {
			  		$nipKepsek = "2019032";
			  	} else if ($foundDataPAUD == 1) {
			  		$nipKepsek = "2019034";
			  	}

	  		} else {
	  			
	  			$isKomen = str_replace([" ", "  "], "", $isKomen);
	  			// echo "Bawah "  .$isKomen;exit;
	  			if ($isKomen == "istextempty") {
	  				
	  				$empty = "empty_comment";
				  	
				  	$arrTgl               = [];
					
				  	$countDataChat = 0;

				  	$tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
				  	$tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

			  		$sesi 		= 1;

			  		if (!empty(isset($_GET['q']))) {

					  	$getDataKomenOther = mysqli_query($con, "
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
					      	ruang_pesan.room_key LIKE '%$roomKey%'
					      	ORDER BY tbl_komentar.id
					    ");

					  	// Check Nis Or ID Group
					  	$queryCheckGroupKelasID = mysqli_query($con, "
					  		SELECT group_kelas_id FROM group_siswa_approved WHERE id = '$nisoridgroup'
					  	");

					  	$countCheckGroupKelasID = mysqli_num_rows($queryCheckGroupKelasID);

					  	if ($countCheckGroupKelasID == 1) {

					  		$nama = 'GROUP ' . $nama;
					  		$isGroup = true;
					  		$getDataGroupKelasID = mysqli_fetch_assoc($queryCheckGroupKelasID)['group_kelas_id'];
					  		// echo $getDataGroupKelasID;exit;

					  	}

					    $countDataChat = mysqli_num_rows($getDataKomenOther);

					  	$fromPage   	= htmlspecialchars($_POST['frompage']);

				  		$key_room   	= $roomKey;

			  		}

			  		$getDataKomenOther = mysqli_query($con, "
				      SELECT 
				      tbl_komentar.room_id as r_id,
				      tbl_komentar.code_user as fromnip,
				      guru.nama as nama_guru,
				      siswa.nama as nama_siswa,
				      kepala_sekolah.nama as nama_kepsek,
				      tbl_komentar.stamp as tanggal_kirim,
				      tbl_komentar.isi_komentar as pesan
				      FROM 
				      tbl_komentar 
				      LEFT JOIN ruang_pesan
				      ON tbl_komentar.room_id = ruang_pesan.room_key
				      LEFT JOIN guru
				      ON tbl_komentar.code_user = guru.nip
				      LEFT JOIN daily_siswa_approved
				      ON ruang_pesan.daily_id = daily_siswa_approved.id
				      LEFT JOIN akses_otm
				      ON tbl_komentar.code_user = akses_otm.nis_siswa
				      LEFT JOIN siswa
				      ON akses_otm.nis_siswa = siswa.nis
				      LEFT JOIN kepala_sekolah
				      ON tbl_komentar.code_user = kepala_sekolah.nip
				      WHERE
				      ruang_pesan.room_key LIKE '%$roomKey%'
				      ORDER BY tbl_komentar.id
				    ");

				    $countDataChat = mysqli_num_rows($getDataKomenOther);

			  		if ($tglOri < $tglSkrngAwal) {
				  		$sesiKomen = 0;
				  	} else {
				  		$sesiKomen = 1;
				  	}

				  	if ($foundDataSD == 1) {
				  		$nipKepsek = "2019032";
				  	} else if ($foundDataPAUD == 1) {
				  		$nipKepsek = "2019034";
				  	}

	  			} else if ($isKomen != "istextempty") {

	  				$apiFonnte 	= "https://api.fonnte.com/send";

	  				if ($foundDataSD == 1) {
				  		$nipKepsek = "2019032";
				  	} else if ($foundDataPAUD == 1) {
				  		$nipKepsek = "2019034";
				  	}

				  	$isKomen 	= str_replace(["istextempty"], "", $isKomen);
	  				$isi_komen	= mysqli_real_escape_string($con, htmlspecialchars($_POST['message']));

				  	$arrTgl               = [];
					
				  	$countDataChat = 0;

				  	$tglNow       = date("Y-m-d H:i:s");

				  	$tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
				  	$tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

			  		$sesi 		= 1;
			  		$sqlInsertChat  = mysqli_query($con, "
						INSERT INTO tbl_komentar 
						SET 
						code_user 		= '$nisotm', 
						isi_komentar  	= '$isi_komen', 
						room_id   		= '$roomKey',
						stamp 			= '$tglNow'
					");

					if ($sqlInsertChat === TRUE) {	    // echo "Message saved successfully!";
					    
						$getDataKomenOther = mysqli_query($con, "
					      SELECT 
					      tbl_komentar.room_id as r_id,
					      tbl_komentar.code_user as fromnip,
					      guru.nama as nama_guru,
					      siswa.nama as nama_siswa,
					      kepala_sekolah.nama as nama_kepsek,
					      tbl_komentar.stamp as tanggal_kirim,
					      tbl_komentar.isi_komentar as pesan
					      FROM 
					      tbl_komentar 
					      LEFT JOIN ruang_pesan
					      ON tbl_komentar.room_id = ruang_pesan.room_key
					      LEFT JOIN guru
					      ON tbl_komentar.code_user = guru.nip
					      LEFT JOIN daily_siswa_approved
					      ON ruang_pesan.daily_id = daily_siswa_approved.id
					      LEFT JOIN akses_otm
					      ON tbl_komentar.code_user = akses_otm.nis_siswa
					      LEFT JOIN siswa
					      ON akses_otm.nis_siswa = siswa.nis
					      LEFT JOIN kepala_sekolah
					      ON tbl_komentar.code_user = kepala_sekolah.nip
					      WHERE
					      ruang_pesan.room_key LIKE '%$roomKey%'
					      ORDER BY tbl_komentar.id
					    ");

					    $countDataChat = mysqli_num_rows($getDataKomenOther);

					    if ($tglOri < $tglSkrngAwal) {
					  		$sesiKomen = 0;
					  	} else {
					  		$sesiKomen = 1;
					  	}

					  	// // Find Number Phone Kepsek
					  	// $queryGetNumberPhone = mysqli_query($con, "
				  		// 	SELECT no_hp FROM guru WHERE nip = '$nipKepsek'
					  	// ");

					  	// $isNumberHeadMaster = mysqli_fetch_array($queryGetNumberPhone)['no_hp'];

					  	// // Find Number Phone Teacher
					  	// $queryGetNumberPhoneTeacher = mysqli_query($con, "
					  	// 	SELECT no_hp FROM guru WHERE nip = '$nipGuru'
					  	// ");

					  	// $isNumberTeacher = mysqli_fetch_array($queryGetNumberPhoneTeacher)['no_hp'];

					  	// // Find Number Phone Parents
					  	// $queryGetNumberPhoneParent = mysqli_query($con, "
					  	// 	SELECT no_hp FROM akses_otm WHERE nis_siswa = '$_SESSION[c_otm_paud]'
					  	// ");

					  	// $isNumberPhoneOTM 	= mysqli_fetch_array($queryGetNumberPhoneParent)['no_hp'];

					  	// $allNumberPhone[] 	= $isNumberHeadMaster;
					  	// $allNumberPhone[] 	= $isNumberTeacher;

					  	// // Kirim Notif Pesan baru group daily ke Kepsek, guru Yang dengan Nomer Fonnte OTM beserta Account token nya yang ada di menu setting di website fonnte
						// $curl = curl_init();

			          	// $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

			          	// $destination_number 	= implode(',', $allNumberPhone);

			          	// // Yang akan di kirimkan notif daily siswa, nomer Kepsek SD atau TK, guru yang membuat daily, dan OTM sesuai siswa yang di buat daily nya oleh guru
			          	// $target = $destination_number;

			          	// $pesan  = "*ADA PESAN BARU DARI DAILY SISWA _" . $nama . "_ YANG BELUM DI BACA !!!*" . "\n" . "\n" . $base . $roomKey. "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

			          	// curl_setopt_array($curl, array(
				        //     CURLOPT_URL => $apiFonnte,
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
			          	// ));

			          	// $response = curl_exec($curl);

			          	// if ($response == false) {

			          	// 	$getDataKomenOther = mysqli_query($con, "
						//       SELECT 
						//       tbl_komentar.room_id as r_id,
						//       tbl_komentar.code_user as fromnip,
						//       guru.nama as nama_guru,
						//       siswa.nama as nama_siswa,
						//       kepala_sekolah.nama as nama_kepsek,
						//       tbl_komentar.stamp as tanggal_kirim,
						//       tbl_komentar.isi_komentar as pesan
						//       FROM 
						//       tbl_komentar 
						//       LEFT JOIN ruang_pesan
						//       ON tbl_komentar.room_id = ruang_pesan.room_key
						//       LEFT JOIN guru
						//       ON tbl_komentar.code_user = guru.nip
						//       LEFT JOIN daily_siswa_approved
						//       ON ruang_pesan.daily_id = daily_siswa_approved.id
						//       LEFT JOIN akses_otm
						//       ON tbl_komentar.code_user = akses_otm.nis_siswa
						//       LEFT JOIN siswa
						//       ON akses_otm.nis_siswa = siswa.nis
						//       LEFT JOIN kepala_sekolah
						//       ON tbl_komentar.code_user = kepala_sekolah.nip
						//       WHERE
						//       ruang_pesan.room_key LIKE '%$roomKey%'
						//       ORDER BY tbl_komentar.id
						//     ");

						//     $countDataChat = mysqli_num_rows($getDataKomenOther);

						//     if ($tglOri < $tglSkrngAwal) {
						//   		$sesiKomen = 0;
						//   	} else {
						//   		$sesiKomen = 1;
						//   	}

			          	// 	$_SESSION['data'] = 'server_err';

			          	// }

			          	// curl_close($curl);

					} else {

						$_SESSION['fail_comment'] = "comment_err";

						$getDataKomenOther = mysqli_query($con, "
					      SELECT 
					      tbl_komentar.room_id as r_id,
					      tbl_komentar.code_user as fromnip,
					      guru.nama as nama_guru,
					      siswa.nama as nama_siswa,
					      kepala_sekolah.nama as nama_kepsek,
					      tbl_komentar.stamp as tanggal_kirim,
					      tbl_komentar.isi_komentar as pesan
					      FROM 
					      tbl_komentar 
					      LEFT JOIN ruang_pesan
					      ON tbl_komentar.room_id = ruang_pesan.room_key
					      LEFT JOIN guru
					      ON tbl_komentar.code_user = guru.nip
					      LEFT JOIN daily_siswa_approved
					      ON ruang_pesan.daily_id = daily_siswa_approved.id
					      LEFT JOIN akses_otm
					      ON tbl_komentar.code_user = akses_otm.nis_siswa
					      LEFT JOIN siswa
					      ON akses_otm.nis_siswa = siswa.nis
					      LEFT JOIN kepala_sekolah
					      ON tbl_komentar.code_user = kepala_sekolah.nip
					      WHERE
					      ruang_pesan.room_key LIKE '%$roomKey%'
					      ORDER BY tbl_komentar.id
					    ");

					    $countDataChat = mysqli_num_rows($getDataKomenOther);

					    if ($tglOri < $tglSkrngAwal) {
					  		$sesiKomen = 0;
					  	} else {
					  		$sesiKomen = 1;
					  	}

					}

	  			}

	  		} 

	  	} else if (isset($_POST['daily_group'])) {

	  		// echo $_POST['roomkey_group_lookdaily'];exit;

	  		$roomKey    	= htmlspecialchars($_POST['roomkey_group_lookdaily']);
	  		$nama 			= htmlspecialchars($_POST['nama_siswa_or_groupkelas_lookdaily']);
	  		$nisOrIdGroup   = htmlspecialchars($_POST['nis_or_idgroup_lookdaily']);

	  		$groupByIdGroup = mysqli_query($con, "
	  			SELECT nis, nama FROM siswa WHERE group_kelas = '$nisOrIdGroup'
	  		");

	  		foreach ($groupByIdGroup as $data) {
	  			$dbGroup[] = $data['nis'];
	  		}

	  		$guru 			= htmlspecialchars($_POST['guru_lookdaily']);
	  		$foto 			= htmlspecialchars($_POST['foto_upload_lookdaily']);
	  		$tglPosting 	= htmlspecialchars($_POST['tgl_posting_lookdaily']);
	  		$tglOri     	= htmlspecialchars($_POST['tglori_posting_lookdaily']);
	  		$judul      	= htmlspecialchars($_POST['jdl_posting_lookdaily']);
	  		$isi 			= $_POST['isi_posting_lookdaily'];
	  		$nipGuru 		= htmlspecialchars($_POST['nipguru_lookdaily']);

	  		$countDataChat 	= 0;

	  		$sesi 			= 1;

	  		$getDataKomenOther = mysqli_query($con, "
		      SELECT 
		      tbl_komentar.room_id as r_id,
		      tbl_komentar.code_user as fromnip,
		      guru.nama as nama_guru,
		      siswa.nama as nama_siswa,
		      kepala_sekolah.nama as nama_kepsek,
		      tbl_komentar.stamp as tanggal_kirim,
		      tbl_komentar.isi_komentar as pesan
		      FROM 
		      tbl_komentar 
		      LEFT JOIN ruang_pesan
		      ON tbl_komentar.room_id = ruang_pesan.room_key
		      LEFT JOIN guru
		      ON tbl_komentar.code_user = guru.nip
		      LEFT JOIN daily_siswa_approved
		      ON ruang_pesan.daily_id = daily_siswa_approved.id
		      LEFT JOIN akses_otm
		      ON tbl_komentar.code_user = akses_otm.nis_siswa
		      LEFT JOIN siswa
		      ON akses_otm.nis_siswa = siswa.nis
		      LEFT JOIN kepala_sekolah
		      ON tbl_komentar.code_user = kepala_sekolah.nip
		      WHERE
		      ruang_pesan.room_key LIKE '%$roomKey%'
		      ORDER BY tbl_komentar.id
		    ");

		    $isGroup = true;

		    // Cek jika nis otm merupakan anggota group kelas tersebut
		    $tampungDataNis = [];
		    $queryGetAllNis = mysqli_query($con, "
		    	SELECT nis FROM siswa WHERE group_kelas = '$nisOrIdGroup'
		    ");

		    foreach ($queryGetAllNis as $data_nis) {
		    	$tampungDataNis[] = $data_nis['nis'];
		    }

		    $matching_data_nis_in_group = 0;

		    for ($i=0; $i < count($tampungDataNis); $i++) { 

		    	if ($tampungDataNis[$i] == $nisotm) {
		    		$matching_data_nis_in_group = 1;
		    	}

		    }

		    if ($matching_data_nis_in_group == 1) {

		    	if (!empty(isset($_GET['q']))) {

	  				$countDataChat = mysqli_num_rows($getDataKomenOther);

		
				  	$arrTgl               = [];
					  
				  	$tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
				  	$tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

				  	$fromPage   = htmlspecialchars($_POST['frompage_lookdaily']);

				  	if ($tglOri < $tglSkrngAwal) {
				  		$sesiKomen = 0;
				  	} else {
				  		$sesiKomen = 1;
				  	}

				  	if ($foundDataSD == 1) {
				  		$nipKepsek = "2019032";
				  	} else if ($foundDataPAUD == 1) {
				  		$nipKepsek = "2019034";
				  	}

			  		$key_room   = $roomKey;

		  		} else {

		  			$sesi = 0;
		  			$isGroup = "noparams";
	  				$_SESSION['data'] = 'nodata';

		  		}

		    } else {

		    	$_SESSION['data'] = 'user_invalid';
	  			$sesi = 2 ;

		    }

	  	} else if (isset($_POST['send_mssg_grp'])) {

	  		$apiFonnte 		= "https://api.fonnte.com/send";

	  		$roomKey    	= htmlspecialchars($_POST['roomkey']);
	  		$nama 			= htmlspecialchars($_POST['nama']);
	  		$nisOrIdGroup	= htmlspecialchars($_POST['nis']);
	  		$guru 			= htmlspecialchars($_POST['guru']);
	  		$foto 			= htmlspecialchars($_POST['foto']);
	  		$tglPosting 	= htmlspecialchars($_POST['tglpost']);
	  		$tglOri     	= htmlspecialchars($_POST['tglori']);
	  		$judul      	= $_POST['judul'];
	  		$isi   			= $_POST['isi'];
	  		$users      	= $nisotm;

	  		$isGroup 		= true;

	  		$nipGuru 		= htmlspecialchars($_POST['nipguru_lookdaily']);

	  		$isKomen    	= mysqli_real_escape_string($con, htmlspecialchars($_POST['message']));
	  		$fromPage   	= htmlspecialchars($_POST['frompage']);

	  		$queryDailyStd = mysqli_query($con, "
		        SELECT id FROM daily_siswa_approved WHERE id IN (
		          SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomKey'
		        )
	      	");

	      	$queryGetGroupName = mysqli_query($con, "
	      		SELECT nama_group_kelas FROM group_kelas WHERE id = '$nisOrIdGroup'
	      	");

	      	$getGroupName = mysqli_fetch_array($queryGetGroupName)['nama_group_kelas'];

	      	if ($isKomen == NULL) {

	  			$empty = "empty_comment";

			  	$arrTgl               = [];
				
			  	$countDataChat = 0;

			  	$tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
			  	$tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

		  		$sesi 		= 1;

		  		if (!empty(isset($_GET['q']))) {

				  	$getDataKomenOther = mysqli_query($con, "
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
				      	ruang_pesan.room_key LIKE '%$roomKey%'
				      	ORDER BY tbl_komentar.id
				    ");

				    $countDataChat 	= mysqli_num_rows($getDataKomenOther);

				  	$fromPage   	= htmlspecialchars($_POST['frompage']);

			  		$key_room   	= $roomKey;

			  		if ($tglOri < $tglSkrngAwal) {
				  		$sesiKomen = 0;
				  	} else {
				  		$sesiKomen = 1;
				  	}

				  	if ($foundDataSD == 1) {
				  		$nipKepsek = "2019032";
				  	} else if ($foundDataPAUD == 1) {
				  		$nipKepsek = "2019034";
				  	}

	  				$_SESSION['data'] = 'empty_komentar';

		  		} else {

		  			$sesi = 0;
	  				$_SESSION['data'] = 'nodata';

		  		}

	  		} else {

			  	$arrTgl               = [];
				
			  	$countDataChat = 0;

			  	$tglNow       = date("Y-m-d H:i:s");

			  	$tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
			  	$tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

		  		$sesi 		= 1;

		  		$getAllNumberOTM = mysqli_query($con, "
			        SELECT no_hp FROM akses_otm
			        WHERE nis_siswa IN (
			          SELECT nis FROM siswa
			          WHERE group_kelas = '$nisOrIdGroup'
			        ) AND nis_siswa <> '$_SESSION[c_otm_paud]'
		      	");

		        $tampungNoHP 		= [];
		        $tampungNoHpOtm		= [];
	  			$allDataNumber 		= [];

		        // echo $destination_array;exit;
	          	if ($foundDataSD == 1) {
			  		$nipKepsek = "2019032";
			  	} else if ($foundDataPAUD == 1) {
			  		$nipKepsek = "2019034";
			  	}

			  	// Find Number Phone Kepsek
			  	$queryGetNumberPhone = mysqli_query($con, "
		  			SELECT no_hp FROM guru WHERE nip = '$nipKepsek'
			  	");

			  	$isNumberHeadMaster = mysqli_fetch_array($queryGetNumberPhone)['no_hp'];

			  	$formatNumberHeadMaster = substr($isNumberHeadMaster, 0, 2);

			  	// Find Number Phone Guru
			  	$queryGetNumberPhoneTeacher = mysqli_query($con, "
			  		SELECT no_hp FROM guru WHERE nip = '$nipGuru'
			  	");

			  	$isNumberTeacher = mysqli_fetch_array($queryGetNumberPhoneTeacher)['no_hp'];

			  	$formatNumberTeacher = substr($isNumberTeacher, 0, 2);

			  	// Jika Sudah Ada Nomer WA dan siap di pakai nyalakan kembali komentar di baris 1085 dan hapus di baris 1200 - 1234
			  	if ($isKomen != "kosongx") {

		  			$sqlInsertChat  = mysqli_query($con, "
						INSERT INTO tbl_komentar 
						SET 
						code_user 		= '$users', 
						isi_komentar  	= '$isKomen', 
						room_id   		= '$roomKey',
						stamp   		= '$tglNow'
					");

					if ($sqlInsertChat === TRUE) {	    // echo "Message saved successfully!";

						// Kirim Notif Pesan baru group daily ke Kepsek Yang dengan Nomer Fonnte OTM beserta Account token nya yang ada di menu setting di website fonnte
						// $curl = curl_init();

			          	// $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

			          	// // var_dump($tampungFormatNoHP);exit;
			          	// $destination_number 	= implode(',', $allDataNumber);

			          	// // Yang akan di kirimkan notif group, nomer Kepsek SD atau TK, guru yang membuat daily, dan semua OTM yang ada di anggota group
			          	// $target = $destination_number;
			          	// // echo $target;exit;
			          	// $pesan  = "*ADA PESAN BARU DARI DAILY GROUP _" . $getGroupName . "_ YANG BELUM DI BACA !!!*" . "\n" . "\n" . $base . $roomKey. "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

			          	// curl_setopt_array($curl, array(
				        //     CURLOPT_URL => $apiFonnte,
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
			          	// ));

			          	// $response = curl_exec($curl);

			          	// if ($response == false) {

			          	// 	// Jika Server Fonnte Bermasalah
			          	// 	$getDataKomenOther = mysqli_query($con, "
						//       SELECT 
						//       tbl_komentar.room_id as r_id,
						//       tbl_komentar.code_user as fromnip,
						//       guru.nama as nama_guru,
						//       siswa.nama as nama_siswa,
						//       kepala_sekolah.nama as nama_kepsek,
						//       tbl_komentar.stamp as tanggal_kirim,
						//       tbl_komentar.isi_komentar as pesan
						//       FROM 
						//       tbl_komentar 
						//       LEFT JOIN ruang_pesan
						//       ON tbl_komentar.room_id = ruang_pesan.room_key
						//       LEFT JOIN guru
						//       ON tbl_komentar.code_user = guru.nip
						//       LEFT JOIN daily_siswa_approved
						//       ON ruang_pesan.daily_id = daily_siswa_approved.id
						//       LEFT JOIN akses_otm
						//       ON tbl_komentar.code_user = akses_otm.nis_siswa
						//       LEFT JOIN siswa
						//       ON akses_otm.nis_siswa = siswa.nis
						//       LEFT JOIN kepala_sekolah
						//       ON tbl_komentar.code_user = kepala_sekolah.nip
						//       WHERE
						//       ruang_pesan.room_key LIKE '%$roomKey%'
						//       ORDER BY tbl_komentar.id
						//     ");

						//     $countDataChat = mysqli_num_rows($getDataKomenOther);

						//     if ($tglOri < $tglSkrngAwal) {
						//   		$sesiKomen = 0;
						//   	} else {
						//   		$sesiKomen = 1;
						//   	}

						//   	$_SESSION['data'] = 'server_err';	

			          	// } else {

			          	// 	$getDataKomenOther = mysqli_query($con, "
						//       SELECT 
						//       tbl_komentar.room_id as r_id,
						//       tbl_komentar.code_user as fromnip,
						//       guru.nama as nama_guru,
						//       siswa.nama as nama_siswa,
						//       kepala_sekolah.nama as nama_kepsek,
						//       tbl_komentar.stamp as tanggal_kirim,
						//       tbl_komentar.isi_komentar as pesan
						//       FROM 
						//       tbl_komentar 
						//       LEFT JOIN ruang_pesan
						//       ON tbl_komentar.room_id = ruang_pesan.room_key
						//       LEFT JOIN guru
						//       ON tbl_komentar.code_user = guru.nip
						//       LEFT JOIN daily_siswa_approved
						//       ON ruang_pesan.daily_id = daily_siswa_approved.id
						//       LEFT JOIN akses_otm
						//       ON tbl_komentar.code_user = akses_otm.nis_siswa
						//       LEFT JOIN siswa
						//       ON akses_otm.nis_siswa = siswa.nis
						//       LEFT JOIN kepala_sekolah
						//       ON tbl_komentar.code_user = kepala_sekolah.nip
						//       WHERE
						//       ruang_pesan.room_key LIKE '%$roomKey%'
						//       ORDER BY tbl_komentar.id
						//     ");

						//     $countDataChat = mysqli_num_rows($getDataKomenOther);

						//     if ($tglOri < $tglSkrngAwal) {
						//   		$sesiKomen = 0;
						//   	} else {
						//   		$sesiKomen = 1;
						//   	}

			          	// }

			          	// curl_close($curl);

						$getDataKomenOther = mysqli_query($con, "
					      SELECT 
					      tbl_komentar.room_id as r_id,
					      tbl_komentar.code_user as fromnip,
					      guru.nama as nama_guru,
					      siswa.nama as nama_siswa,
					      kepala_sekolah.nama as nama_kepsek,
					      tbl_komentar.stamp as tanggal_kirim,
					      tbl_komentar.isi_komentar as pesan
					      FROM 
					      tbl_komentar 
					      LEFT JOIN ruang_pesan
					      ON tbl_komentar.room_id = ruang_pesan.room_key
					      LEFT JOIN guru
					      ON tbl_komentar.code_user = guru.nip
					      LEFT JOIN daily_siswa_approved
					      ON ruang_pesan.daily_id = daily_siswa_approved.id
					      LEFT JOIN akses_otm
					      ON tbl_komentar.code_user = akses_otm.nis_siswa
					      LEFT JOIN siswa
					      ON akses_otm.nis_siswa = siswa.nis
					      LEFT JOIN kepala_sekolah
					      ON tbl_komentar.code_user = kepala_sekolah.nip
					      WHERE
					      ruang_pesan.room_key LIKE '%$roomKey%'
					      ORDER BY tbl_komentar.id
					    ");

					    $countDataChat = mysqli_num_rows($getDataKomenOther);

					    if ($tglOri < $tglSkrngAwal) {
					  		$sesiKomen = 0;
					  	} else {
					  		$sesiKomen = 1;
					  	}

					} else {

						$getDataKomenOther = mysqli_query($con, "
					      SELECT 
					      tbl_komentar.room_id as r_id,
					      tbl_komentar.code_user as fromnip,
					      guru.nama as nama_guru,
					      siswa.nama as nama_siswa,
					      kepala_sekolah.nama as nama_kepsek,
					      tbl_komentar.stamp as tanggal_kirim,
					      tbl_komentar.isi_komentar as pesan
					      FROM 
					      tbl_komentar 
					      LEFT JOIN ruang_pesan
					      ON tbl_komentar.room_id = ruang_pesan.room_key
					      LEFT JOIN guru
					      ON tbl_komentar.code_user = guru.nip
					      LEFT JOIN daily_siswa_approved
					      ON ruang_pesan.daily_id = daily_siswa_approved.id
					      LEFT JOIN akses_otm
					      ON tbl_komentar.code_user = akses_otm.nis_siswa
					      LEFT JOIN siswa
					      ON akses_otm.nis_siswa = siswa.nis
					      LEFT JOIN kepala_sekolah
					      ON tbl_komentar.code_user = kepala_sekolah.nip
					      WHERE
					      ruang_pesan.room_key LIKE '%$roomKey%'
					      ORDER BY tbl_komentar.id
					    ");

					    $countDataChat = mysqli_num_rows($getDataKomenOther);

					    if ($tglOri < $tglSkrngAwal) {
					  		$sesiKomen = 0;
					  	} else {
					  		$sesiKomen = 1;
					  	}
						
		  				$_SESSION['fail_comment'] = "comment_err";

					}

		  		} else {

		  			$getDataKomenOther = mysqli_query($con, "
				      SELECT 
				      tbl_komentar.room_id as r_id,
				      tbl_komentar.code_user as fromnip,
				      guru.nama as nama_guru,
				      siswa.nama as nama_siswa,
				      kepala_sekolah.nama as nama_kepsek,
				      tbl_komentar.stamp as tanggal_kirim,
				      tbl_komentar.isi_komentar as pesan
				      FROM 
				      tbl_komentar 
				      LEFT JOIN ruang_pesan
				      ON tbl_komentar.room_id = ruang_pesan.room_key
				      LEFT JOIN guru
				      ON tbl_komentar.code_user = guru.nip
				      LEFT JOIN daily_siswa_approved
				      ON ruang_pesan.daily_id = daily_siswa_approved.id
				      LEFT JOIN akses_otm
				      ON tbl_komentar.code_user = akses_otm.nis_siswa
				      LEFT JOIN siswa
				      ON akses_otm.nis_siswa = siswa.nis
				      LEFT JOIN kepala_sekolah
				      ON tbl_komentar.code_user = kepala_sekolah.nip
				      WHERE
				      ruang_pesan.room_key LIKE '%$roomKey%'
				      ORDER BY tbl_komentar.id
				    ");

				    $countDataChat = mysqli_num_rows($getDataKomenOther);

				    if ($tglOri < $tglSkrngAwal) {
				  		$sesiKomen = 0;
				  	} else {
				  		$sesiKomen = 1;
				  	}

				  	if ($foundDataSD == 1) {
				  		$nipKepsek = "2019032";
				  	} else if ($foundDataPAUD == 1) {
				  		$nipKepsek = "2019034";
				  	}

		  		}

			  	// Jika Format Number Phone Kepsek Benar
			  	// if ($formatNumberHeadMaster == "08") {

			  	// 	if ($formatNumberTeacher == "08") {

			  	// 		// Gabungan Nomer HP / WA OTM, Kepsek, Dan Guru
			  	// 		$allDataNumber[] 	= $isNumberHeadMaster;
			  	// 		$allDataNumber[] 	= $isNumberTeacher;

			  	// 		foreach ($getAllNumberOTM as $data) {
				//           $tampungNoHP[] = $data['no_hp'];
				//         }

				//         for ($i=0; $i < count($tampungNoHP); $i++) { 

				//           $noHp = substr($tampungNoHP[$i], 0, 2);

				//           if ($noHp == '08') {
				//             $change2 = substr($tampungNoHP[$i], 2, 12);
				//             // $thisNumberPhoneOTM = "628" . $change2;
				//             $allDataNumber[] = $tampungNoHP[$i];
				//           }

				//         }

			  	// 		$destination_number 	= implode(',', $allDataNumber);

			  	// 		if ($isKomen != "kosongx") {

				//   			$sqlInsertChat  = mysqli_query($con, "
				// 				INSERT INTO tbl_komentar 
				// 				SET 
				// 				code_user 		= '$users', 
				// 				isi_komentar  	= '$isKomen', 
				// 				room_id   		= '$roomKey'
				// 			");

				// 			if ($sqlInsertChat === TRUE) {	    // echo "Message saved successfully!";

				// 				// Kirim Notif Pesan baru group daily ke Kepsek Yang dengan Nomer Fonnte OTM beserta Account token nya yang ada di menu setting di website fonnte
				// 				$curl = curl_init();

				// 	          	$tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

				// 	          	// var_dump($tampungFormatNoHP);exit;
				// 	          	$destination_number 	= implode(',', $allDataNumber);

				// 	          	// Yang akan di kirimkan notif group, nomer Kepsek SD atau TK, guru yang membuat daily, dan semua OTM yang ada di anggota group
				// 	          	$target = $destination_number;
				// 	          	// echo $target;exit;
				// 	          	$pesan  = "*ADA PESAN BARU DARI DAILY GROUP _" . $getGroupName . "_ YANG BELUM DI BACA !!!*" . "\n" . "\n" . $base . $roomKey. "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

				// 	          	curl_setopt_array($curl, array(
				// 		            CURLOPT_URL => $apiFonnte,
				// 		            CURLOPT_RETURNTRANSFER => true,
				// 		            CURLOPT_ENCODING => '',
				// 		            CURLOPT_MAXREDIRS => 10,
				// 		            CURLOPT_TIMEOUT => 0,
				// 		            CURLOPT_FOLLOWLOCATION => true,
				// 		            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				// 		            CURLOPT_CUSTOMREQUEST => 'POST',
				// 		            CURLOPT_POSTFIELDS => array(
				// 		              'target' => $target,
				// 		              'message' => $pesan
				// 		            ),
				// 		            CURLOPT_HTTPHEADER => array(
				// 		              'Authorization:a3vjVjL3S6xHpDg7NiaE' //change TOKEN to your actual token
				// 		            ),
				// 	          	));

				// 	          	$response = curl_exec($curl);

				// 	          	if ($response == false) {

				// 	          		// Jika Server Fonnte Bermasalah
				// 	          		$getDataKomenOther = mysqli_query($con, "
				// 				      SELECT 
				// 				      tbl_komentar.room_id as r_id,
				// 				      tbl_komentar.code_user as fromnip,
				// 				      guru.nama as nama_guru,
				// 				      siswa.nama as nama_siswa,
				// 				      kepala_sekolah.nama as nama_kepsek,
				// 				      tbl_komentar.stamp as tanggal_kirim,
				// 				      tbl_komentar.isi_komentar as pesan
				// 				      FROM 
				// 				      tbl_komentar 
				// 				      LEFT JOIN ruang_pesan
				// 				      ON tbl_komentar.room_id = ruang_pesan.room_key
				// 				      LEFT JOIN guru
				// 				      ON tbl_komentar.code_user = guru.nip
				// 				      LEFT JOIN daily_siswa_approved
				// 				      ON ruang_pesan.daily_id = daily_siswa_approved.id
				// 				      LEFT JOIN akses_otm
				// 				      ON tbl_komentar.code_user = akses_otm.nis_siswa
				// 				      LEFT JOIN siswa
				// 				      ON akses_otm.nis_siswa = siswa.nis
				// 				      LEFT JOIN kepala_sekolah
				// 				      ON tbl_komentar.code_user = kepala_sekolah.nip
				// 				      WHERE
				// 				      ruang_pesan.room_key LIKE '%$roomKey%'
				// 				      ORDER BY tbl_komentar.id
				// 				    ");

				// 				    $countDataChat = mysqli_num_rows($getDataKomenOther);

				// 				    if ($tglOri < $tglSkrngAwal) {
				// 				  		$sesiKomen = 0;
				// 				  	} else {
				// 				  		$sesiKomen = 1;
				// 				  	}

				// 				  	$_SESSION['data'] = 'server_err';	

				// 	          	} else {

				// 	          		$getDataKomenOther = mysqli_query($con, "
				// 				      SELECT 
				// 				      tbl_komentar.room_id as r_id,
				// 				      tbl_komentar.code_user as fromnip,
				// 				      guru.nama as nama_guru,
				// 				      siswa.nama as nama_siswa,
				// 				      kepala_sekolah.nama as nama_kepsek,
				// 				      tbl_komentar.stamp as tanggal_kirim,
				// 				      tbl_komentar.isi_komentar as pesan
				// 				      FROM 
				// 				      tbl_komentar 
				// 				      LEFT JOIN ruang_pesan
				// 				      ON tbl_komentar.room_id = ruang_pesan.room_key
				// 				      LEFT JOIN guru
				// 				      ON tbl_komentar.code_user = guru.nip
				// 				      LEFT JOIN daily_siswa_approved
				// 				      ON ruang_pesan.daily_id = daily_siswa_approved.id
				// 				      LEFT JOIN akses_otm
				// 				      ON tbl_komentar.code_user = akses_otm.nis_siswa
				// 				      LEFT JOIN siswa
				// 				      ON akses_otm.nis_siswa = siswa.nis
				// 				      LEFT JOIN kepala_sekolah
				// 				      ON tbl_komentar.code_user = kepala_sekolah.nip
				// 				      WHERE
				// 				      ruang_pesan.room_key LIKE '%$roomKey%'
				// 				      ORDER BY tbl_komentar.id
				// 				    ");

				// 				    $countDataChat = mysqli_num_rows($getDataKomenOther);

				// 				    if ($tglOri < $tglSkrngAwal) {
				// 				  		$sesiKomen = 0;
				// 				  	} else {
				// 				  		$sesiKomen = 1;
				// 				  	}

				// 	          	}

				// 	          	curl_close($curl);

				// 			} else {

				// 				$sesi = 0;
				//   				$_SESSION['data'] = 'nodata';

				// 			}

				//   		} else {

				//   			$getDataKomenOther = mysqli_query($con, "
				// 		      SELECT 
				// 		      tbl_komentar.room_id as r_id,
				// 		      tbl_komentar.code_user as fromnip,
				// 		      guru.nama as nama_guru,
				// 		      siswa.nama as nama_siswa,
				// 		      kepala_sekolah.nama as nama_kepsek,
				// 		      tbl_komentar.stamp as tanggal_kirim,
				// 		      tbl_komentar.isi_komentar as pesan
				// 		      FROM 
				// 		      tbl_komentar 
				// 		      LEFT JOIN ruang_pesan
				// 		      ON tbl_komentar.room_id = ruang_pesan.room_key
				// 		      LEFT JOIN guru
				// 		      ON tbl_komentar.code_user = guru.nip
				// 		      LEFT JOIN daily_siswa_approved
				// 		      ON ruang_pesan.daily_id = daily_siswa_approved.id
				// 		      LEFT JOIN akses_otm
				// 		      ON tbl_komentar.code_user = akses_otm.nis_siswa
				// 		      LEFT JOIN siswa
				// 		      ON akses_otm.nis_siswa = siswa.nis
				// 		      LEFT JOIN kepala_sekolah
				// 		      ON tbl_komentar.code_user = kepala_sekolah.nip
				// 		      WHERE
				// 		      ruang_pesan.room_key LIKE '%$roomKey%'
				// 		      ORDER BY tbl_komentar.id
				// 		    ");

				// 		    $countDataChat = mysqli_num_rows($getDataKomenOther);

				// 		    if ($tglOri < $tglSkrngAwal) {
				// 		  		$sesiKomen = 0;
				// 		  	} else {
				// 		  		$sesiKomen = 1;
				// 		  	}

				// 		  	if ($foundDataSD == 1) {
				// 		  		$nipKepsek = "2019032";
				// 		  	} else if ($foundDataPAUD == 1) {
				// 		  		$nipKepsek = "2019034";
				// 		  	}

				//   		}

			  	// 	} else {

			  	// 		$getDataKomenOther = mysqli_query($con, "
				// 	      	SELECT 
				// 	      		tbl_komentar.room_id as r_id,
				// 		      	tbl_komentar.code_user as fromnip,
				// 	      		guru.nama as nama_guru,
				// 		      	siswa.nama as nama_siswa,
				// 		      	kepala_sekolah.nama as nama_kepsek,
				// 		      	tbl_komentar.stamp as tanggal_kirim,
				// 		      	tbl_komentar.isi_komentar as pesan
				// 	      	FROM 
				// 	      	tbl_komentar 
				// 	      	LEFT JOIN ruang_pesan
				// 	      	ON tbl_komentar.room_id = ruang_pesan.room_key
				// 	      	LEFT JOIN guru
				// 	      	ON tbl_komentar.code_user = guru.nip
				// 	      	LEFT JOIN daily_siswa_approved
				// 	      	ON ruang_pesan.daily_id = daily_siswa_approved.id
				// 	      	LEFT JOIN akses_otm
				// 	      	ON tbl_komentar.code_user = akses_otm.nis_siswa
				// 	      	LEFT JOIN siswa
				// 	      	ON akses_otm.nis_siswa = siswa.nis
				// 	      	LEFT JOIN kepala_sekolah
				// 	      	ON tbl_komentar.code_user = kepala_sekolah.nip
				// 	      	WHERE
				// 	      		ruang_pesan.room_key LIKE '%$roomKey%'
				// 	      	ORDER BY tbl_komentar.id
				// 	    ");

				// 	    $countDataChat = mysqli_num_rows($getDataKomenOther);

				// 	    if ($tglOri < $tglSkrngAwal) {
				// 	  		$sesiKomen = 0;
				// 	  	} else {
				// 	  		$sesiKomen = 1;
				// 	  	}

				// 	  	if ($foundDataSD == 1) {
				// 	  		$nipKepsek = "2019032";
				// 	  	} else if ($foundDataPAUD == 1) {
				// 	  		$nipKepsek = "2019034";
				// 	  	}

				// 	  	$_SESSION['data'] = 'invalid_number_teacher';

			  	// 	}

			  	// } else {

			  	// 	// Jika Format Number Kepsek Salah
			  	// 	$getDataKomenOther = mysqli_query($con, "
				//       	SELECT 
				//       		tbl_komentar.room_id as r_id,
				// 	      	tbl_komentar.code_user as fromnip,
				//       		guru.nama as nama_guru,
				// 	      	siswa.nama as nama_siswa,
				// 	      	kepala_sekolah.nama as nama_kepsek,
				// 	      	tbl_komentar.stamp as tanggal_kirim,
				// 	      	tbl_komentar.isi_komentar as pesan
				//       	FROM 
				//       	tbl_komentar 
				//       	LEFT JOIN ruang_pesan
				//       	ON tbl_komentar.room_id = ruang_pesan.room_key
				//       	LEFT JOIN guru
				//       	ON tbl_komentar.code_user = guru.nip
				//       	LEFT JOIN daily_siswa_approved
				//       	ON ruang_pesan.daily_id = daily_siswa_approved.id
				//       	LEFT JOIN akses_otm
				//       	ON tbl_komentar.code_user = akses_otm.nis_siswa
				//       	LEFT JOIN siswa
				//       	ON akses_otm.nis_siswa = siswa.nis
				//       	LEFT JOIN kepala_sekolah
				//       	ON tbl_komentar.code_user = kepala_sekolah.nip
				//       	WHERE
				//       		ruang_pesan.room_key LIKE '%$roomKey%'
				//       	ORDER BY tbl_komentar.id
				//     ");

				//     $countDataChat = mysqli_num_rows($getDataKomenOther);

				//     if ($tglOri < $tglSkrngAwal) {
				//   		$sesiKomen = 0;
				//   	} else {
				//   		$sesiKomen = 1;
				//   	}

				//   	if ($foundDataSD == 1) {
				//   		$nipKepsek = "2019032";
				//   	} else if ($foundDataPAUD == 1) {
				//   		$nipKepsek = "2019034";
				//   	}

				//   	$_SESSION['data'] = 'invalid_number_kepsek';

			  	// }

	  		} 

	  	} else {
	  		$sesi = 0;
	  		$_SESSION['data'] = 'nodata';
	  	}

  	}

?>

	<style type="text/css">
		
		#ld{
			font-size: 13px;
			font-family: cursive;
			color: black;
			animation: blink 1s linear infinite;
		}

		@property --num {
		  syntax: "<integer>";
		  initial-value: 0;
		  inherits: false;
		}

		#ld {
		  animation: counter 13s infinite alternate ease-in-out;
		  counter-reset: num var(--num);
		}
		#krgAwal::after {
		  content: counter(num);
		}

		@keyframes counter {
		  from {
		    --num: 0;
		  }
		  to {
		    --num: 10;
		  }
		}

		@keyframes blink{
			0%{opacity: 0;}
			50%{opacity: .5;}
			100%{opacity: 1;}
		}

	</style>

	<?php if ($sesi == 1): ?>

		<?php if(isset($_SESSION['data']) && $_SESSION['data'] == 'empty_komentar'){?>
      		<div style="display: none;" class="alert alert-danger alert-dismissable"> Tidak Bisa Mengirim Komentar Kosong ! 
             	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             	<?php 
	             	unset($_SESSION['data']);
             	?>
          	</div>
        <?php } ?>

        <?php if(isset($_SESSION['data']) && $_SESSION['data'] == 'invalid_number_kepsek'){?>
      		<div style="display: none;" class="alert alert-danger alert-dismissable"> Maaf Ada Kesalahan Sistem dalam Mengirim Komentar ! 
             	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             	<?php 
	             	unset($_SESSION['data']);
             	?>
          	</div>
        <?php } ?>

        <?php if(isset($_SESSION['data']) && $_SESSION['data'] == 'invalid_number_teacher'){?>
      		<div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> Maaf Ada Kesalahan Sistem dalam Mengirim Komentar ! </span> 
             	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             	<?php 
	             	unset($_SESSION['data']);
             	?>
          	</div>
        <?php } ?>

        <?php if(isset($_SESSION['data']) && $_SESSION['data'] == 'server_err'){?>
        	<?php 
             	unset($_SESSION['data']);
             	$fonnte_err = 1;
         	?>
        <?php } ?>

        <?php if(isset($_SESSION['fail_comment']) && $_SESSION['fail_comment'] == 'comment_err'){?>
      		<div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> Gagal Mengirim Komentar ! </span> 
             	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             	<?php 
	             	unset($_SESSION['fail_comment']);
             	?>
          	</div>
        <?php } ?>
		
		<div class="row">
			<div class="col-md-7">
			    <!-- Box Comment -->
			    <div class="box box-widget">
			      <div class="box-header with-border">
			        <div class="user-block">
			          <img class="img-circle" src="<?= $base; ?>theme/dist/img/logo2.png" alt="User Image">
			          <span class="username" id="namaguru"> <?= $guru; ?> </span>
			          <span class="description" id="tglpublish"> Published Date - <?= $tglPosting; ?> </span>
			        </div>
			        <!-- /.user-block -->
			      </div>
			      <!-- /.box-header -->
			      <div class="box-body">
			        <h4 style="color: black;"> <strong> TITLE : </strong> <?= $judul; ?>  </h4>
			        <img class="img-responsive pad" src="<?= $base; ?>image_uploads/<?= $foto; ?>" alt="Photo" style="width: auto; height: 300px;">
			        <?= $isi; ?>
			      </div>

			    </div>

			    <?php if (!empty(isset($_GET['q']) ) && isset($_POST['roomkey_lookdaily']) ): ?>

			    	<div class="tombol-hide" style="display: none;">
				    	<form action="<?= $roomKey; ?>" method="post">
				    		<input type="hidden" name="frompage" value="<?= $fromPage; ?>">
				    		<input type="hidden" name="roomkey" value="<?= $roomKey; ?>">
				        	<input type="hidden" name="nis" value="<?= $nisOrIdGroup; ?>">
				        	<input type="hidden" name="nama" value="<?= strtoupper($nama); ?>">
				        	<input type="hidden" name="guru" value="<?= strtoupper($guru); ?>">
				        	<input type="hidden" name="foto" value="<?= $foto; ?>">
				        	<input type="hidden" name="tglpost" value="<?= $tglPosting; ?>">
				        	<input type="hidden" name="tglori" value="<?= $tglOri; ?>">
				        	<input type="hidden" name="judul" value="<?= $judul; ?>">
				    		<input type="hidden" name="isi" value="<?= $isi; ?>">
				    		<input type="hidden" name="nipguru_lookdaily" value="<?= $nipGuru; ?>">
				    		<button type="hidden" name="krm" id="try_std"> send hide </button>
				    	</form>
				    </div>
			    	
			    <?php elseif(!empty(isset($_GET['q']) ) && isset($_POST['krm'])): ?>

			    	<div class="tombol-hide" style="display: none;">
				    	<form action="<?= $roomKey; ?>" method="post">
				    		<input type="hidden" name="frompage" value="<?= $fromPage; ?>">
				    		<input type="hidden" name="roomkey" value="<?= $roomKey; ?>">
				        	<input type="hidden" name="nis" value="<?= $nisOrIdGroup; ?>">
				        	<input type="hidden" name="nama" value="<?= strtoupper($nama); ?>">
				        	<input type="hidden" name="guru" value="<?= strtoupper($guru); ?>">
				        	<input type="hidden" name="foto" value="<?= $foto; ?>">
				        	<input type="hidden" name="tglpost" value="<?= $tglPosting; ?>">
				        	<input type="hidden" name="tglori" value="<?= $tglOri; ?>">
				        	<input type="hidden" name="judul" value="<?= $judul; ?>">
				    		<input type="hidden" name="isi" value="<?= $isi; ?>">
				    		<input type="hidden" name="nipguru_lookdaily" value="<?= $nipGuru; ?>">
				    		<button type="hidden" name="krm" id="try_std"> send hide </button>
				    	</form>
				    </div>

			    <?php elseif(!empty(isset($_GET['q']) ) && isset($_POST['daily_group'])): ?>

			    	<?php  

			    		$queryDailyGroup = mysqli_query($con, "
					        SELECT id, group_kelas_id FROM group_siswa_approved WHERE id IN (
					          SELECT daily_id FROM ruang_pesan WHERE room_key = '$_GET[q]'
					        )
				      	");

		    			$getDataGroupKelasID = mysqli_fetch_assoc($queryDailyGroup)['group_kelas_id'];

		    		?>

			    	<div class="tombol-hide" style="display: none;">
				    	<form action="<?= $_GET['q']; ?>" method="post">
				    		<input type="hidden" name="frompage" value="<?= $fromPage; ?>">
				    		<input type="hidden" name="roomkey" value="<?= $roomKey; ?>">
				        	<input type="hidden" name="nis" value="<?= $nisOrIdGroup; ?>">
				        	<input type="hidden" name="nama" value="<?= strtoupper($nama); ?>">
				        	<input type="hidden" name="guru" value="<?= strtoupper($guru); ?>">
				        	<input type="hidden" name="message" value="kosongx">
				        	<input type="hidden" name="foto" value="<?= $foto; ?>">
				        	<input type="hidden" name="nipguru_lookdaily" value="<?= $nipGuru; ?>">
				        	<input type="hidden" name="tglpost" value="<?= $tglPosting; ?>">
				        	<input type="hidden" name="tglori" value="<?= $tglOri; ?>">
				        	<input type="hidden" name="judul" value="<?= $judul; ?>">
				    		<input type="hidden" name="isi" value="<?= $isi; ?>">
		        			<input type="hidden" name="nipguru_lookdaily" value="<?= $nipGuru; ?>">
				    		<button type="hidden" name="send_mssg_grp" id="try_group"> send hide </button>
				    	</form>
				    </div>

			    <?php elseif( !empty(isset($_GET['q']) ) && isset($_POST['send_mssg_grp']) ): ?>

			    	<?php  

			    		$queryDailyGroup = mysqli_query($con, "
					        SELECT id, group_kelas_id FROM group_siswa_approved WHERE id IN (
					          SELECT daily_id FROM ruang_pesan WHERE room_key = '$_GET[q]'
					        )
				      	");

		    			$getDataGroupKelasID = mysqli_fetch_assoc($queryDailyGroup)['group_kelas_id'];

		    		?>

			    	<div class="tombol-hide" style="display: none;">
				    	<form action="<?= $_GET['q']; ?>" method="post">
				    		<input type="hidden" name="frompage" value="<?= $fromPage; ?>">
				    		<input type="hidden" name="roomkey" value="<?= $roomKey; ?>">
				        	<input type="hidden" name="nis" value="<?= $nisOrIdGroup; ?>">
				        	<input type="hidden" name="nama" value="<?= strtoupper($nama); ?>">
				        	<input type="hidden" name="guru" value="<?= strtoupper($guru); ?>">
				        	<input type="hidden" name="message" value="kosongx">
				        	<input type="hidden" name="foto" value="<?= $foto; ?>">
				        	<input type="hidden" name="tglpost" value="<?= $tglPosting; ?>">
				        	<input type="hidden" name="tglori" value="<?= $tglOri; ?>">
				        	<input type="hidden" name="judul" value="<?= $judul; ?>">
				    		<input type="hidden" name="isi" value="<?= $isi; ?>">
		        			<input type="hidden" name="nipguru_lookdaily" value="<?= $nipGuru; ?>">
		        			<input type="hidden" name="nipkepsek_lookdaily" value="<?= $nipKepsek; ?>">
				    		<button type="hidden" name="send_mssg_grp" id="try_group"> send hide </button>
				    	</form>
				    </div>

			    <?php else: ?>

			    	<div class="tombol-hide" style="display: none;">
				    	<form action="<?= $roomKey; ?>" method="post">
				    		<input type="hidden" name="frompage" value="<?= $fromPage; ?>">
				    		<input type="hidden" name="roomkey" value="<?= $roomKey; ?>">
				        	<input type="hidden" name="nis" value="<?= $nisOrIdGroup; ?>">
				        	<input type="hidden" name="nama" value="<?= strtoupper($nama); ?>">
				        	<input type="hidden" name="guru" value="<?= strtoupper($guru); ?>">
				        	<input type="hidden" name="foto" value="<?= $foto; ?>">
				        	<input type="hidden" name="tglpost" value="<?= $tglPosting; ?>">
				        	<input type="hidden" name="tglori" value="<?= $tglOri; ?>">
				        	<input type="hidden" name="judul" value="<?= $judul; ?>">
				    		<input type="hidden" name="isi" value="<?= $isi; ?>">
				    		<input type="hidden" name="nipguru_lookdaily" value="<?= $nipGuru; ?>">
				    		<button type="hidden" name="krm" id="try_std"> send hide </button>
				    	</form>
				    </div>

			    <?php endif ?>

			</div>
		 	
		 	<div class="col-md-5">
		    <!-- DIRECT CHAT SUCCESS -->
			    <div class="box box-primary direct-chat direct-chat-primary">
			      <div class="box-header with-border" style="background-color: gainsboro;">
			        <h3 class="box-title" style="color: black;"> <strong> COMMENTS </strong> </h3>
			      </div>
			      <!-- /.box-header -->
			      <div class="box-body">
			        <!-- Conversations are loaded here -->
			        <div class="direct-chat-messages" id="comments-box" style="height: 400px !important;">

			        	<!-- Jika Isi Chat Masih Kosong -->
			        	<?php if ($countDataChat == 0): ?>

			        		<?php if ($tglOri < $tglSkrngAwal): ?>

								<div class="center" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 10px;">
								  <h4 style="font-weight: bold;"> TIDAK ADA KOMENTAR ! </h4>
								</div>

							<?php elseif($tglOri >= $tglSkrngAwal && $tglOri <= $tglSkrngAkhir) : ?>

								<div class="center" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 10px;">
								  <h4 style="font-weight: bold;"> BELUM ADA KOMENTAR ! </h4>
								</div>

			        		<?php endif ?>

			        	<?php else: ?>

			        		<?php if (isset($_POST['daily_group'])): ?>

			        			<?php foreach ($getDataKomenOther as $data): ?>

								    <?php if ($data['fromnip'] == $nisotm): ?>

								    	<div class="direct-chat-msg right">
								            <div class="direct-chat-info clearfix">
								              <span id="namasiswa" class="direct-chat-name pull-right"> <?= strtoupper($data['nama_siswa']); ?> </span>
								              <span id="datesend" class="direct-chat-timestamp pull-left"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          	</div>
								          	<img class="direct-chat-img" src="<?= $base; ?>imgstatis/df.jpg" alt="Message User Image">
								          	<div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
							          	</div>

							        <?php elseif ($data['fromnip'] == $nipKepsek): ?>

								    	<div class="direct-chat-msg">
								            <div class="direct-chat-info clearfix">
								              <span id="namakepsek" class="direct-chat-name pull-left"> HEADMASTER : <?= $data['nama_kepsek']; ?> </span>
								              <span id="tglchat" class="direct-chat-timestamp pull-right"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          	</div>
								          	<img class="direct-chat-img" src="<?= $base; ?>imgstatis/icon_chat.png" alt="Message User Image">
								          	<div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
							          	</div>

							        <?php elseif ($data['fromnip'] == $nipGuru): ?>

					        			<div class="direct-chat-msg">
								          <div class="direct-chat-info clearfix">
								            <span id="namaguruchat" class="direct-chat-name pull-left"> TEACHER : <?= strtoupper($data['nama_guru']); ?> </span>
								            <span class="direct-chat-timestamp pull-right"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          </div>
								          <img class="direct-chat-img" src="<?= $base; ?>imgstatis/icon_chat.png" alt="Message User Image">
								          <div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
								        </div>

								    <?php else: ?>

								    	<div class="direct-chat-msg">
								          <div class="direct-chat-info clearfix">
								            <span id="namaguruchat" class="direct-chat-name pull-left"> PARENTS : <?= strtoupper($data['nama_siswa']); ?> </span>
								            <span class="direct-chat-timestamp pull-right"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          </div>
								          <img class="direct-chat-img" src="<?= $base; ?>imgstatis/df.jpg" alt="Message User Image">
								          <div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
								        </div>
					        			
				        			<?php endif ?>
				        		
				        		<?php endforeach ?>

				        	<?php elseif(isset($_POST['send_mssg_grp'])): ?>

			        			<?php foreach ($getDataKomenOther as $data): ?>

							        <?php if ($data['fromnip'] == $nipKepsek): ?>

								    	<div class="direct-chat-msg">
								            <div class="direct-chat-info clearfix">
								              <span id="kepsekchat" class="direct-chat-name pull-left"> HEADMASTER : <?= $data['nama_kepsek']; ?> </span>
								              <span id="tglsendkepsek" class="direct-chat-timestamp pull-right"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          	</div>
								          	<img class="direct-chat-img" src="<?= $base; ?>imgstatis/icon_chat.png" alt="Message User Image">
								          	<div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
							          	</div>

							        <?php elseif ($data['fromnip'] == $users): ?>

					        			<div class="direct-chat-msg right">
								          <div class="direct-chat-info clearfix">
								            <span id="namaguruchat" class="direct-chat-name pull-right"> <?= strtoupper($data['nama_siswa']); ?> </span>
								            <span id="tglsendguru" class="direct-chat-timestamp pull-left"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          </div>
								          <img class="direct-chat-img" src="<?= $base; ?>imgstatis/df.jpg" alt="Message User Image">
								          <div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
								        </div>

								    <?php elseif($data['fromnip'] == $nipGuru): ?>

								    	<div class="direct-chat-msg">
								            <div class="direct-chat-info clearfix">
							            		<span id="nama_sswa" class="direct-chat-name pull-left"> TEACHER : <?= strtoupper($data['nama_guru']); ?> </span>
								              	<span id="time_send" class="direct-chat-timestamp pull-right"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          	</div>
								          	<img class="direct-chat-img" src="<?= $base; ?>imgstatis/icon_chat.png" alt="Message User Image">
								          	<div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
							          	</div>

								    <?php else: ?>

								    	<div class="direct-chat-msg">
								            <div class="direct-chat-info clearfix">
							            		<span id="nama_sswa" class="direct-chat-name pull-left"> PARENTS : <?= strtoupper($data['nama_siswa']); ?> </span>
								              	<span id="time_send" class="direct-chat-timestamp pull-right"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          	</div>
								          	<img class="direct-chat-img" src="<?= $base; ?>imgstatis/df.jpg" alt="Message User Image">
								          	<div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
							          	</div>
					        			
					        		<?php endif ?>
					        		
					        	<?php endforeach ?>

				        	<?php else: ?>

				        		<?php foreach ($getDataKomenOther as $data): ?>

								    <?php if ($data['fromnip'] == $nisotm): ?>

								    	<div class="direct-chat-msg right">
								            <div class="direct-chat-info clearfix">
								              <span id="namasiswa" class="direct-chat-name pull-right"> <?= strtoupper($data['nama_siswa']); ?> </span>
								              <span id="datesend" class="direct-chat-timestamp pull-left"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          	</div>
								          	<img class="direct-chat-img" src="<?= $base; ?>imgstatis/df.jpg" alt="Message User Image">
								          	<div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
							          	</div>

							        <?php elseif ($data['fromnip'] == $nipKepsek): ?>

								    	<div class="direct-chat-msg">
								            <div class="direct-chat-info clearfix">
								              <span id="namakepsek" class="direct-chat-name pull-left"> HEADMASTER : <?= $data['nama_kepsek']; ?> </span>
								              <span id="tglchat" class="direct-chat-timestamp pull-right"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          	</div>
								          	<img class="direct-chat-img" src="<?= $base; ?>imgstatis/icon_chat.png" alt="Message User Image">
								          	<div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
							          	</div>

							        <?php elseif ($data['fromnip'] == $nipGuru): ?>

					        			<div class="direct-chat-msg">
								          <div class="direct-chat-info clearfix">
								            <span id="namaguruchat" class="direct-chat-name pull-left"> TEACHER : <?= strtoupper($data['nama_guru']); ?> </span>
								            <span class="direct-chat-timestamp pull-right"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          </div>
								          <img class="direct-chat-img" src="<?= $base; ?>imgstatis/icon_chat.png" alt="Message User Image">
								          <div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
								        </div>
					        			
				        			<?php endif ?>
				        		
				        		<?php endforeach ?>
			        			
			        		<?php endif ?>
			        		
			        	<?php endif ?>

		          	</div>

			      </div>
			      <!-- /.box-body -->
			      <div class="box-footer">
			        <span class="input-group-btn">


			        	<?php if (isset($_POST['redirectLookDaily'])): ?>

			        		<button id="refresh_btn_std" style="margin-bottom: 10px; font-weight: bold;" class="btn btn-light btn-flat"> <i class="glyphicon glyphicon-refresh"></i> Refresh to Update Data </button>

        			 		<form action="<?= $_GET['q']; ?>" method="post">
					          	<div class="input-group" id="tombolComment">
					            	<input type="text" id="message-input" name="message" placeholder="Type Message ..." class="form-control">
					            	<input type="hidden" name="frompage" value="<?= $fromPage; ?>">
					            	<input type="hidden" name="roomkey" value="<?= $roomKey; ?>">
						        	<input type="hidden" name="nis" value="<?= $nisOrIdGroup; ?>">
						        	<input type="hidden" name="nama" value="<?= strtoupper($nama); ?>">
						        	<input type="hidden" name="guru" value="<?= strtoupper($guru); ?>">
						        	<input type="hidden" name="foto" value="<?= $foto; ?>">
						        	<input type="hidden" name="tglpost" value="<?= $tglPosting; ?>">
						        	<input type="hidden" name="tglori" value="<?= $tglOri; ?>">
						        	<input type="hidden" name="judul" value="<?= $judul; ?>">
						        	<input type="hidden" name="isi" value="<?= $isi; ?>">
						        	<input type="hidden" name="nipguru_lookdaily" value="<?= $nipGuru; ?>">
					                <span class="input-group-btn">
					                  <button type="submit" name="send_mssg" id="send-btn" class="btn btn-success btn-flat">Send</button>
					                </span>
					          	</div>
					        </form>
			        		
		        		<?php elseif(isset($_POST['send_mssg'])): ?>

		        			<button id="refresh_btn_std" style="margin-bottom: 10px; font-weight: bold;" class="btn btn-light btn-flat"> <i class="glyphicon glyphicon-refresh"></i> Refresh to Update Data </button>

        			 		<form action="<?= $_GET['q']; ?>" method="post">
					          	<div class="input-group" id="tombolComment">
					            	<input type="text" id="message-input" name="message" placeholder="Type Message ..." class="form-control">
					            	<input type="hidden" name="frompage" value="<?= $fromPage; ?>">
					            	<input type="hidden" name="roomkey" value="<?= $roomKey; ?>">
						        	<input type="hidden" name="nis" value="<?= $nisOrIdGroup; ?>">
						        	<input type="hidden" name="nama" value="<?= strtoupper($nama); ?>">
						        	<input type="hidden" name="guru" value="<?= strtoupper($guru); ?>">
						        	<input type="hidden" name="foto" value="<?= $foto; ?>">
						        	<input type="hidden" name="tglpost" value="<?= $tglPosting; ?>">
						        	<input type="hidden" name="tglori" value="<?= $tglOri; ?>">
						        	<input type="hidden" name="judul" value="<?= $judul; ?>">
						        	<input type="hidden" name="isi" value="<?= $isi; ?>">
						        	<input type="hidden" name="nipguru_lookdaily" value="<?= $nipGuru; ?>">
					                <span class="input-group-btn">
					                  <button type="submit" name="send_mssg" id="send-btn" class="btn btn-success btn-flat">Send</button>
					                </span>
					          	</div>
					        </form>

			        	<?php elseif(isset($_POST['daily_group'])): ?>

			        		<button id="refresh_btn_group" style="margin-bottom: 10px; font-weight: bold;" class="btn btn-light btn-flat"> <i class="glyphicon glyphicon-refresh"></i> Refresh to Update Data </button>
		                  		
	                  		<form action="<?= $roomKey; ?>" method="post">
					          <div class="input-group" id="tombolComment">
					            	<input type="text" id="message-input" name="message" placeholder="Type Message ..." class="form-control">
					            	<input type="hidden" name="frompage" value="<?= $fromPage; ?>">
					            	<input type="hidden" name="roomkey" value="<?= $roomKey; ?>">
						        	<input type="hidden" name="nis" value="<?= $nisOrIdGroup; ?>">
						        	<input type="hidden" name="nama" value="<?= strtoupper($nama); ?>">
						        	<input type="hidden" name="guru" value="<?= strtoupper($guru); ?>">
						        	<input type="hidden" name="foto" value="<?= $foto; ?>">
						        	<input type="hidden" name="tglpost" value="<?= $tglPosting; ?>">
						        	<input type="hidden" name="tglori" value="<?= $tglOri; ?>">
						        	<input type="hidden" name="judul" value="<?= $judul; ?>">
						        	<input type="hidden" name="isi" value="<?= $isi; ?>">
				        			<input type="hidden" name="nipguru_lookdaily" value="<?= $nipGuru; ?>">
					                <span class="input-group-btn">
					                  <button type="submit" name="send_mssg_grp" id="send-btn" class="btn btn-success btn-flat">Send</button>
					                </span>
					          </div>
					        </form>

			        	<?php elseif(isset($_POST['send_mssg_grp'])): ?>

	        				<button id="refresh_btn_group" style="margin-bottom: 10px; font-weight: bold;" class="btn btn-light btn-flat"> <i class="glyphicon glyphicon-refresh"></i> Refresh to Update Data </button>
		                  		
	                  		<form action="<?= $roomKey; ?>" method="post">
					          <div class="input-group" id="tombolComment">
					            	<input type="text" id="message-input" name="message" placeholder="Type Message ..." class="form-control">
					            	<input type="hidden" name="frompage" value="<?= $fromPage; ?>">
					            	<input type="hidden" name="roomkey" value="<?= $roomKey; ?>">
						        	<input type="hidden" name="nis" value="<?= $nisOrIdGroup; ?>">
						        	<input type="hidden" name="nama" value="<?= strtoupper($nama); ?>">
						        	<input type="hidden" name="guru" value="<?= strtoupper($guru); ?>">
						        	<input type="hidden" name="foto" value="<?= $foto; ?>">
						        	<input type="hidden" name="tglpost" value="<?= $tglPosting; ?>">
						        	<input type="hidden" name="tglori" value="<?= $tglOri; ?>">
						        	<input type="hidden" name="judul" value="<?= $judul; ?>">
						        	<input type="hidden" name="isi" value="<?= $isi; ?>">
				        			<input type="hidden" name="nipguru_lookdaily" value="<?= $nipGuru; ?>">
					                <span class="input-group-btn">
					                  <button type="submit" name="send_mssg_grp" id="send-btn" class="btn btn-success btn-flat">Send</button>
					                </span>
					          </div>
					        </form>

			        	<?php else: ?>

			        		<button id="refresh_btn_std" style="margin-bottom: 10px; font-weight: bold;" class="btn btn-light btn-flat"> <i class="glyphicon glyphicon-refresh"></i> Refresh to Update Data </button>

        			 		<form action="<?= $roomKey; ?>" method="post">
					          	<div class="input-group" id="tombolComment">
					            	<input type="text" id="message-input" name="message" placeholder="Type Message ..." class="form-control">
					            	<input type="hidden" name="frompage" value="<?= $fromPage; ?>">
					            	<input type="hidden" name="roomkey" value="<?= $roomKey; ?>">
						        	<input type="hidden" name="nis" value="<?= $nisOrIdGroup; ?>">
						        	<input type="hidden" name="nama" value="<?= strtoupper($nama); ?>">
						        	<input type="hidden" name="guru" value="<?= strtoupper($guru); ?>">
						        	<input type="hidden" name="foto" value="<?= $foto; ?>">
						        	<input type="hidden" name="tglpost" value="<?= $tglPosting; ?>">
						        	<input type="hidden" name="tglori" value="<?= $tglOri; ?>">
						        	<input type="hidden" name="judul" value="<?= $judul; ?>">
						        	<input type="hidden" name="isi" value="<?= $isi; ?>">
						        	<input type="hidden" name="nipguru_lookdaily" value="<?= $nipGuru; ?>">
					                <span class="input-group-btn">
					                  <button type="submit" name="send_mssg" id="send-btn" class="btn btn-success btn-flat">Send</button>
					                </span>
					          	</div>
					        </form>

			        	<?php endif ?>

	                </span>

			      </div>
			      <!-- /.box-footer-->
			    </div>
			    <!--/.direct-chat -->
		  	</div>

		</div>

		<div class="wrapx" style="display: flex; justify-content: flex-end;">

			<div class="row">
				
				<div class="col-md-3">

					<?php if($fromPage == "homepage"): ?>

						<form action="<?= $basewam; ?>" method="post">
							<input type="hidden" name="nama" value="<?= $nama; ?>">
							<input type="hidden" name="nis" value="<?= $nisoridgroup; ?>">
			        		<button class="btn btn-sm btn-primary" type="submit" name="send_data_student"> <span class="glyphicon glyphicon-log-out" id="cancel"></span> Kembali </button>
			        	</form>
						<br>

					<?php elseif($fromPage == "querydailystudent"): ?>

						<form action="<?= $basewam; ?><?= $fromPage; ?>" method="post">
							<input type="hidden" name="nama" value="<?= $nama; ?>">
							<input type="hidden" name="nis" value="<?= $nisoridgroup; ?>">
			        		<button class="btn btn-sm btn-primary" type="submit" name="send_data_student"> <span class="glyphicon glyphicon-log-out" id="cancel"></span> Kembali </button>
			        	</form>
						<br>

					<?php elseif($fromPage == "querydailygroup"): ?>

						<form action="<?= $basewam; ?><?= $fromPage; ?>" method="post">
			        		<button class="btn btn-sm btn-primary" type="submit"> <span class="glyphicon glyphicon-log-out" id="cancel"></span> Kembali </button>
			        	</form>
						<br>

					<?php endif ?>

				</div>

			</div>

		</div>

	<?php elseif($sesi == 0): ?>

		<div class="row">
		    <div class="col-xs-12 col-md-12 col-lg-12">

		        <?php if(isset($_SESSION['data']) && $_SESSION['data'] == 'nodata'){?>
		          <div style="display: none;" class="alert alert-danger alert-dismissable"> Tidak Ada Data Yang Di Kirim! 
		             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		             <?php 
		             	unset($_SESSION['data']);
		             	$sesi = 0; 
		             ?>
		          </div>
		        <?php } ?>

		    </div>
		</div>

	<?php elseif($sesi == 2): ?>

		<div class="row">
		    <div class="col-xs-12 col-md-12 col-lg-12">

		        <?php if(isset($_SESSION['data']) && $_SESSION['data'] == 'user_invalid'){?>
		          <div style="display: none;" class="alert alert-danger alert-dismissable"> AKSES DI ALIHKAN ! 
		             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		             <?php
		             	$nisOrIdGroup = 2;
		             	unset($_SESSION['data']);
		             ?>
		          </div>
		        <?php } ?>

		    </div>
		</div>

	<?php endif ?>

<script type="text/javascript">
	
	let rooms 		= `<?= $key_room; ?>`
	let nis_siswa  	= `<?= $nisotm; ?>`
	let nip_guru    = `<?= $nipGuru; ?>`
	let komenSes 	= `<?= $sesiKomen; ?>`
	let emptyKomen  = `<?= $empty; ?>`
	let fonnteError = `<?= $fonnte_err; ?>`

    function firstLoad(rmId) {
	    $.ajax({
	        url     : `<?= $basewam; ?>data_chat`,
	        method  : 'POST',
	        data    : {
	            room_id  	: rmId,
	            users    	: `<?= $users; ?>`,
	            nip_guru 	: nip_guru,
	            nip_kepsek  : `<?= $nipKepsek; ?>`,
	        },
	        success:function(data) {
	          	$('#comments-box').scrollTop($('#comments-box')[0].scrollHeight);
	          	let jumlahChat        = JSON.parse(data).jumlah_chat;
        		let tglPosting        = JSON.parse(data).tgl_posting;

	            if(jumlahChat == 0) {
	            	// Tanggal Posting Kemarin
	            	if(tglPosting < `<?= $tglSkrngAwal; ?>`) {

		            	$('#comments-box').html(`
		            		<div class="center">
							  <h4 id="komenkosong" style="font-weight: bold;"> TIDAK ADA KOMENTAR ! </h4>
							</div>`
						);

		            // Tanggal Posting Hari Ini
		            } else if (tglPosting >= `<?= $tglSkrngAwal; ?>` && tglPosting <= `<?= $tglSkrngAkhir; ?>`) {

		            	$('#comments-box').html(`
		            		<div class="center">
							  <h4 id="komenkosong" style="font-weight: bold;"> BELUM ADA KOMENTAR ! </h4>
							</div>`
						);
						$("#message-input").focus();

		            }

	            } else {
		            
		            setTimeout(function() {
		                $('#comments-box').html(JSON.parse(data).isi_chat);
		                // $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
		            }, 1000);
		            $("#message-input").focus();

	            }

	        }
	    })
	}

	function firstLoadEnd(rmId) {
	    $.ajax({
	        url     : `<?= $basewam; ?>data_chat`,
	        method  : 'POST',
	        data    : {
	            room_id  	: rmId,
	            users    	: `<?= $users; ?>`,
	            nip_guru 	: nip_guru,
	            nip_kepsek  : `<?= $nipKepsek; ?>`,
	        },
	        success:function(data) {
	          	$('#comments-box').scrollTop($('#comments-box')[0].scrollHeight);
	          	let jumlahChat        = JSON.parse(data).jumlah_chat;
        		let tglPosting        = JSON.parse(data).tgl_posting;

	            if(jumlahChat == 0) {
	            	// Tanggal Posting Kemarin
	            	if(tglPosting < `<?= $tglSkrngAwal; ?>`) {

		            	$('#comments-box').html(`
		            		<div class="center">
							  <h4 id="komenkosong" style="font-weight: bold;"> TIDAK ADA KOMENTAR ! </h4>
							</div>`
						);

		            // Tanggal Posting Hari Ini
		            } else if (tglPosting >= `<?= $tglSkrngAwal; ?>` && tglPosting <= `<?= $tglSkrngAkhir; ?>`) {

		            	$('#comments-box').html(`
		            		<div class="center">
							  <h4 id="komenkosong" style="font-weight: bold;"> BELUM ADA KOMENTAR ! </h4>
							</div>`
						);
						$("#message-input").focus();

		            }

	            } else {
		            
		            setTimeout(function() {
		                $('#comments-box').html(JSON.parse(data).isi_chat);
		                // $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
		            }, 1000);
		            $("#message-input").focus();

	            }

	        }
	    })
	}

	$(document).ready( function () {

		if (fonnteError == 1) {
			console.log("Server Fonnte Sedang Bermasalah !")
		}

		if(emptyKomen == 'empty_comment') {

			Swal.fire({
			  title: "Perhatian !",
			  text: "Tidak Bisa Mengirim Komentar Yang Kosong !",
			  icon: "warning"
			});

			let element = document.querySelector(".swal2-confirm");
			element.addEventListener("click", function() {
			  	document.getElementById("message-input").focus();
			});

		}

    	if (`<?= $nisOrIdGroup; ?>` == 0) {

			if (`<?= $isGroup; ?>` == "noparams") {
				setTimeout(() => {
					location.href = `<?= $basewam; ?>querydailygroup`
				}, 1000);	
			} else {
				setTimeout(() => {
					location.href = `<?= $basewam; ?>querydailystudent`
				}, 1000);
			}

		} else if (`<?= $nisOrIdGroup; ?>` == 2) {

			Swal.fire({
			  title: "Perhatian !",
			  text: "Akses Dialihkan !",
			  icon: "warning"
			});

			setTimeout(() => {
				location.href = `<?= $basewam; ?>`
			}, 2500);	

		} else {

			$('#comments-box').scrollTop($('#comments-box')[0].scrollHeight);

			if (komenSes != 0) {
				
				$("#refresh_btn_group").click(function(){
					$("#try_group").click();
				});

				$("#refresh_btn_std").click(function(){
					$("#try_std").click();
				});

			}

			$("#message-input").focus();

	  		let session = `<?= $sesi; ?>` 
			
			if (session == 0) {
				setTimeout(linkRedirect, 1200);
			}

		    $("#aList1").click();

		    setTimeout(clickSubMenu, 500);

		    if (komenSes == 0) {
		    
		    	Swal.fire("Sesi Comment telah berakhir");
		      	$("#tombolComment").hide();
		      	$("#refresh_btn").hide();
		      	$("#refresh_btn_std").hide();
		      	$("#refresh_btn_group").hide();
		    	// setTimeout(function() {
			    //   firstLoad(`<?= $key_room; ?>`)
			    // }, 1000); 

		    } else {

		    	// setTimeout(function() {
			    //   firstLoad(`<?= $key_room; ?>`)
			    // }, 1000);

		    }

		}

	    function clickSubMenu() {
	      $("#isiList2").click();

	      if (`<?= $isGroup; ?>`) {

	      	$("#query_data_group").css({
	          "background-color" : "#ccc",
	          "color" : "black"
	      	});

	      } else {

	      	$("#query_data_siswa").css({
	          "background-color" : "#ccc",
	          "color" : "black"
	      	});

	      }
	      
	    }

	    // // Send button click event
     //    $('#send-btn').click(function(e){
     //        e.preventDefault();        
     //        var message = $('#message-input').val();
     //        if (message !== "") {
     //        	// alert(rooms)
     //            appendMessage(message, rooms, `<?= $users; ?>`, nis_siswa);
                
     //            $('#message-input').val(''); // Clear input field after sending
     //            $('#message-input').focus();
     //            $('#message-input').click();
     //        } else {
     //          alert('Tidak Boleh Kosong')
     //        }
     //        // $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
     //        // $("#chat-box").animate({ scrollTop: $('#chat-box').prop("scrollHeight")}, 1000);
     //    });

	    let titleLists1   = ' - ACTIVITY ' + document.getElementById('titleList1').innerHTML
	    let elementWrap   = document.getElementById('boxTitle')

		let newIcon = document.getElementById("addIcon");
		newIcon.classList.remove("fa");

		if (`<?= $isGroup; ?>`) {

			newIcon.classList.add("fa");
			newIcon.classList.add("fa-users");

		} else {

			newIcon.classList.add("fa");
			newIcon.classList.add("fa-user");

		}

		let getTitleList1 = document.getElementById('isiList2').innerHTML;
		$("#isiMenu").css({
			"margin-left" : "5px"
		});

		let createElementSpanNama = document.createElement('span')
		createElementSpanNama.id  = 'spanIsiNama'

		if (`<?= $isGroup; ?>`) {
			createElementSpanNama.textContent += "GROUP " + `<?= $nama; ?>`
		} else {
			createElementSpanNama.textContent += `<?= $nama; ?>`
		}

		elementWrap.appendChild(createElementSpanNama)

		$("#spanIsiNama").css({
			"font-weight" : "bold"
		});

		document.getElementById('isiMenu').innerHTML = `<span style="font-weight: bold;"> QUERY </span>` + `<span style="font-weight: bold;"> ${titleLists1} </span> ` + ' -'

		function linkRedirect() {
			location.href = `<?= $basewam; ?>querydailystudent`
	    }

  	});

  	function appendMessage(message, rm, usr, dataNisOtm) {
	    $.ajax({
	        url     : `<?= $basewam; ?>save_message`,
	        method  : 'POST',
	        data    : {
	            message : message,
	            room    : rm,
	            user    : usr,
	            nisotm  : dataNisOtm
	        },
	        success:function(res) {
	          console.log(JSON.parse(res).room)
	            $.ajax({
	                url     : `<?= $basewam; ?>data`,
	                method  : 'POST',
	                data    : {
	                    room_id : JSON.parse(res).room,
	                    users   : `<?= $users; ?>`
	                },
	                success:function(respon) {
	                    firstLoad(`<?= $key_room; ?>`)

	                    // $('#chat-box').append(`<div id='me'>` + JSON.parse(res).message_main + '</div>');
	                    // fromMe(rm)
	                }
	            });
	        }
	    });
  	}

</script>