<?php  

	$timeOut        = $_SESSION['expire_paud'];
    
  	$timeRunningOut = time() + 5;

	$timeIsOut 			= 0;
	$sesi      			= 0;
	$nama      			= "";
	$nis_or_idgroup     = 0;
	$guru      			= "";
	$key_room  			= "";
	$users     			= "";
	$sesiKomen 			= 1;
	$isGroup 			= false;
	$fonnte_err 		= 0;

	$getDataGroupKelasID = "";

	$tglSkrngAwal 	= "";
	$tglSkrngAkhir 	= "";

	date_default_timezone_set("Asia/Jakarta");

	$nipKepsek = $_SESSION['nip_kepsek_paud'];

	$nipGuru   = "";
	$fromPage  = "";
	$diMenu    = "lookactivity";

	$countDataChat  = 0;

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

  	$empty = "";
  	$apiFonnte 	= "https://api.fonnte.com/send";

	// echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

	if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

		$_SESSION['form_success'] = "session_time_out";

	    $timeIsOut = 1;
	    error_reporting(1);

  	} else {

  		if (isset($_POST['krm'])) {

	  		$roomKey    	= $_POST['roomkey'];
	  		$nama 			= htmlspecialchars($_POST['nama']);
	  		$nis_or_idgroup = htmlspecialchars($_POST['nis']);
	  		// echo $nis_or_idgroup;exit;
	  		$guru 			= htmlspecialchars($_POST['guru']);
	  		$foto 			= htmlspecialchars($_POST['foto']);
	  		$tglPosting 	= $_POST['tglpost'];
	  		$tglOri     	= $_POST['tglori'];
	  		$judul      	= htmlspecialchars($_POST['judul']);
	  		$isi        	= $_POST['isi'];
	  		$users      	= $nipKepsek;

	  		$getNipGuru 	= mysqli_fetch_array(mysqli_query($con, "
	  			SELECT created_by FROM ruang_pesan WHERE room_key = '$roomKey'
	  		"));

	  		$nipGuru 		= $getNipGuru['created_by'];

	  		$nisOrIdGroup 	= $nis_or_idgroup;
			  
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

		  	// Check Nis Or ID Group
		  	$queryCheckGroupKelasID = mysqli_query($con, "
		  		SELECT id FROM group_kelas WHERE id = '$nis_or_idgroup'
		  	");

		  	$countCheckGroupKelasID = mysqli_num_rows($queryCheckGroupKelasID);

		  	if ($countCheckGroupKelasID == 1) {

		  		$isGroup = true;
		  		// echo $getDataGroupKelasID;exit;

		  	}

		    $countDataChat = mysqli_num_rows($getDataKomenOther);

		  	$fromPage   	= $_POST['frompage'];

	  		$key_room   	= $roomKey;
	  		
	  	} else if (isset($_POST['krm_group'])) {

	  		$roomKey    	= $_POST['roomkey'];
	  		$nama 			= htmlspecialchars($_POST['nama']);
	  		$nis_or_idgroup = htmlspecialchars($_POST['id_group_approved']);
	  		// echo $nis_or_idgroup;exit;
	  		$guru 			= htmlspecialchars($_POST['guru']);
	  		$foto 			= htmlspecialchars($_POST['foto']);
	  		$tglPosting 	= $_POST['tglpost'];
	  		$tglOri     	= $_POST['tglori'];
	  		$judul      	= htmlspecialchars($_POST['judul']);
	  		$isi        	= $_POST['isi'];
	  		$nipGuru    	= $_POST['nipguru_lookdaily'];
	  		$users      	= $nipKepsek;
			  
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

		  	// Check Nis Or ID Group
		  	$queryCheckGroupKelasID = mysqli_query($con, "
		  		SELECT group_kelas_id FROM group_siswa_approved WHERE id = '$nis_or_idgroup'
		  	");

		  	$countCheckGroupKelasID = mysqli_num_rows($queryCheckGroupKelasID);

		  	if ($countCheckGroupKelasID == 1) {

		  		$nama = 'GROUP ' . $nama;
		  		$isGroup = true;
		  		$getDataGroupKelasID = mysqli_fetch_assoc($queryCheckGroupKelasID)['group_kelas_id'];
		  		// echo $getDataGroupKelasID;exit;

		  	}

		    $countDataChat = mysqli_num_rows($getDataKomenOther);

		  	$fromPage   	= $_POST['frompage'];

	  		$key_room   	= $roomKey;
	  		
	  	} else if (isset($_POST['roomkey_lookdaily'])) {

	  		$roomKey    	= htmlspecialchars($_POST['roomkey_lookdaily']);
	  		$nama 			= htmlspecialchars($_POST['nama_siswa_or_groupkelas_lookdaily']);
	  		$nisOrIdGroup   = htmlspecialchars($_POST['nis_or_idgroup_lookdaily']);

	  		$guru 			= htmlspecialchars($_POST['guru_lookdaily']);
	  		$foto 			= htmlspecialchars($_POST['foto_upload_lookdaily']);
	  		$tglPosting 	= htmlspecialchars($_POST['tgl_posting_lookdaily']);
	  		$tglOri     	= htmlspecialchars($_POST['tglori_posting_lookdaily']);
	  		$judul      	= htmlspecialchars($_POST['jdl_posting_lookdaily']);
	  		$isi 			= $_POST['isi_posting_lookdaily'];
	  		$users      	= $nipGuru;

	  		$queryFindNip 	= mysqli_fetch_array(mysqli_query($con, "
	  			SELECT created_by FROM ruang_pesan WHERE room_key = '$roomKey'
	  		"));

	  		$nipGuru 		= $queryFindNip['created_by'];

	  		$nis_or_idgroup = $nisOrIdGroup;

	  		$countDataChat = 0;

	  		$sesi 		= 1;
	  		// echo $tglOri;exit;

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

	  		$positionHeadMaster = mysqli_fetch_array(mysqli_query($con, "
		    	SELECT c_jabatan FROM guru WHERE nip = '$nipGuru'
		    "));

		    $isKepsekSD 	= preg_match($kepsekSD, $positionHeadMaster['c_jabatan']);
		    $isKepsekPAUD 	= preg_match($kepsekPAUD, $positionHeadMaster['c_jabatan']);

		    // Check Jika Pembuat Daily adalah NIP Kepala Sekolah sd atau tk saat login dari notif
	  		if ($foundDataSD == $isKepsekSD) {

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

		  		$countDataChat = mysqli_num_rows($getDataKomenOther);
			  	$arrTgl               = [];
				  
			  	$tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
			  	$tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

			  	$fromPage   = $_POST['frompage_lookdaily'];

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

	  		} else if ($foundDataPAUD == $isKepsekPAUD) {

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

		  		$countDataChat = mysqli_num_rows($getDataKomenOther);
			  	$arrTgl               = [];
				  
			  	$tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
			  	$tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

			  	$fromPage   = $_POST['frompage_lookdaily'];

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

	  			$_SESSION['data'] = 'user_invalid';
	  			$sesi = 2 ;

	  		}


	  	} elseif (isset($_POST['send_mssg'])) {

	  		$roomKey    	= htmlspecialchars($_POST['roomkey']);
	  		$nama 			= htmlspecialchars($_POST['nama']);
	  		$nisOrIdGroup	= htmlspecialchars($_POST['nis']);
	  		$guru 			= htmlspecialchars($_POST['guru']);
	  		$foto 			= htmlspecialchars($_POST['foto']);
	  		$tglPosting 	= htmlspecialchars($_POST['tglpost']);
	  		$tglOri     	= htmlspecialchars($_POST['tglori']);
	  		$judul      	= $_POST['judul'];
	  		$isi   			= $_POST['isi'];
	  		$users      	= $nipGuru;

	  		$isKomen    	= mysqli_real_escape_string($con, htmlspecialchars($_POST['message']));
	  		$fromPage   	= htmlspecialchars($_POST['frompage']);
	  		$allNumberPhone = [];

	  		$queryFindNip 	= mysqli_fetch_array(mysqli_query($con, "
	  			SELECT created_by FROM ruang_pesan WHERE room_key = '$roomKey'
	  		"));

	  		$nipGuru 		= $queryFindNip['created_by'];

	  		$nis_or_idgroup = $nisOrIdGroup;

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

	      	if ($countDailyGroup == 1) {
	      		$isGroup = true;
	      	} elseif ($countDailyStd == 1) {
	      		$isGroup = false;
	      	}

	  		// echo $isKomen;exit;

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

				  	// Check Nis Or ID Group
				  	$queryCheckGroupKelasID = mysqli_query($con, "
				  		SELECT group_kelas_id FROM group_siswa_approved WHERE id = '$nisOrIdGroup'
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

			  	$arrTgl               = [];
				
			  	$countDataChat = 0;

			  	$tglNow       = date("Y-m-d H:i:s");

			  	$tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
			  	$tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

		  		$sesi 		= 1;
		  		$apiFonnte 	= "https://api.fonnte.com/send";

		  		// Kirim Notif Pesan baru personal daily ke guru dan Otm personal dengan Nomer Fonnte KEPSEK beserta Account token nya yang ada di menu setting di website fonnte
				$curl = curl_init();

	          	$tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

	          	$queryGetKelas 	= mysqli_query($con, "SELECT c_kelas FROM siswa WHERE nis = '$nisOrIdGroup' ");
	          	$getKelas_Siswa = mysqli_fetch_array($queryGetKelas)['c_kelas'];

	          	$is_SD      = "/SD/i";

	          	$foundDataSD    = preg_match($is_SD, $getKelas_Siswa);

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

			  	// Find Number Phone Teacher
			  	$queryGetNumberPhoneTeacher = mysqli_query($con, "
			  		SELECT no_hp FROM guru WHERE nip = '$nipGuru'
			  	");

			  	$isNumberTeacher = mysqli_fetch_array($queryGetNumberPhoneTeacher)['no_hp'];

			  	// Find Number Phone Parents
			  	$queryGetNumberPhoneParent = mysqli_query($con, "
			  		SELECT no_hp FROM akses_otm WHERE nis_siswa = '$nisOrIdGroup'
			  	");

			  	$isNumberPhoneOTM 	= mysqli_fetch_array($queryGetNumberPhoneParent)['no_hp'];

			  	// $allNumberPhone[] 	= $isNumberTeacher;
			  	// $allNumberPhone[] 	= $isNumberPhoneOTM;

			  	// $destination_number 	= implode(',', $allNumberPhone);

			  	// echo $destination_number;exit;

			  	$sqlInsertChat  = mysqli_query($con, "
					INSERT INTO tbl_komentar 
					SET 
					code_user 		= '$nipKepsek', 
					isi_komentar  	= '$isKomen', 
					room_id   		= '$roomKey',
					stamp  			= '$tglNow'
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

			  		// $allNumberPhone[] 	= $isNumberTeacher;
				  	// $allNumberPhone[] 	= $isNumberPhoneOTM;

				  	// $destination_number 	= implode(',', $allNumberPhone);

		          	// // Yang akan di kirimkan notif daily siswa, nomer hp guru yang membuat daily dan OTM sesuai siswa yang di buat daily nya oleh guru
		          	// $target = $destination_number;
		          	// // echo $target;exit;
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

		          	// 	// Server Fonnte Error
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

				  	$_SESSION['data'] = 'err_komen';

				}

	  		} 

	  	} else if (isset($_POST['send_mssg_grp'])) {

	  		$roomKey    	= htmlspecialchars($_POST['roomkey']);
	  		$nama 			= htmlspecialchars($_POST['nama']);
	  		$nisOrIdGroup	= htmlspecialchars($_POST['nis']);
	  		$guru 			= htmlspecialchars($_POST['guru']);
	  		$foto 			= htmlspecialchars($_POST['foto']);
	  		$tglPosting 	= htmlspecialchars($_POST['tglpost']);
	  		$tglOri     	= htmlspecialchars($_POST['tglori']);
	  		$judul      	= $_POST['judul'];
	  		$isi   			= $_POST['isi'];

	  		$getNipGuru     = mysqli_fetch_array(mysqli_query($con, "
	  			SELECT created_by FROM ruang_pesan WHERE room_key = '$roomKey'
	  		"));

	  		$nipGuru 		= $getNipGuru['created_by'];

	  		$nis_or_idgroup = $nisOrIdGroup;

	  		$isKomen    	= mysqli_real_escape_string($con, htmlspecialchars($_POST['message']));
	  		$fromPage   	= htmlspecialchars($_POST['frompage']);

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

	      	$getGroupName = mysqli_fetch_array(mysqli_query($con, "
	      		SELECT nama_group_kelas FROM group_kelas WHERE id = '$nisOrIdGroup'
	      	"));

	      	$countDailyStd    = mysqli_num_rows($queryDailyStd);
	      	$countDailyGroup  = mysqli_num_rows($queryDailyGroup);

	      	$getAllNumberOTM = mysqli_query($con, "
		        SELECT no_hp FROM akses_otm
		        WHERE nis_siswa IN (
		          SELECT nis FROM siswa
		          WHERE group_kelas = '$nisOrIdGroup'
		        )
	      	");

	      	// Find Number Phone Guru
		  	$queryGetNumberPhoneTeacher = mysqli_query($con, "
		  		SELECT no_hp FROM guru WHERE nip = '$nipGuru'
		  	");

		  	$isNumberTeacher = mysqli_fetch_array($queryGetNumberPhoneTeacher)['no_hp'];

	      	$allDataNumber[] 	= $isNumberTeacher;

	      	foreach ($getAllNumberOTM as $data) {
	          $tampungNoHP[] = $data['no_hp'];
	        }

	        for ($i=0; $i < count($tampungNoHP); $i++) { 

	          $noHp = substr($tampungNoHP[$i], 0, 2);

	          if ($noHp == '08') {
	            $allDataNumber[] = $tampungNoHP[$i];
	          }

	        }

	        // echo count($allDataNumber);exit;

	        // $destination_number 	= implode(',', $allDataNumber);
	        // echo $destination_number;exit;

	      	if ($countDailyGroup == 1) {
	      		$isGroup = true;
	      	} elseif ($countDailyStd == 1) {
	      		$isGroup = false;
	      	}

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

		  		} else {

		  			$sesi = 0;
	  				$_SESSION['data'] = 'nodata';

		  		}

	  		} else {

	  			$apiFonnte 	= "https://api.fonnte.com/send";
		
			  	$arrTgl               = [];
				
			  	$countDataChat = 0;

			  	$tglNow       = date("Y-m-d H:i:s");

			  	$tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
			  	$tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

		  		$sesi 		= 1;

		  		if ($isKomen != "kosongx") {

		  			$sqlInsertChat  = mysqli_query($con, "
						INSERT INTO tbl_komentar 
						SET 
						code_user 		= '$nipKepsek', 
						isi_komentar  	= '$isKomen', 
						room_id   		= '$roomKey',
						stamp   		= '$tglNow'
					");

					if ($sqlInsertChat === TRUE) {
					    
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

					  	// // Kirim Notif Pesan baru group daily ke Kepsek Yang dengan Nomer Fonnte Guru beserta Account token nya yang ada di menu setting di website fonnte
						// $curl 	= curl_init();

			          	// $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

			          	// // var_dump($tampungFormatNoHP);exit;
			          	// $destination_number 	= implode(',', $allDataNumber);

			          	// // Yang akan di kirimkan notif group, nomer Kepsek SD atau TK, guru yang membuat daily, dan semua OTM yang ada di anggota group
			          	// $target = $destination_number;
			          	// // echo $target;exit;
			          	// $pesan  = "*ADA PESAN BARU DARI DAILY GROUP _" . $getGroupName['nama_group_kelas'] . "_ YANG BELUM DI BACA !!!*" . "\n" . "\n" . $base . $roomKey. "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

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

						if ($tglOri < $tglSkrngAwal) {
					  		$sesiKomen = 0;
					  	} else {
					  		$sesiKomen = 1;
					  	}
					  	
						$_SESSION['data'] = 'err_komen';

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

	  		} 

	  	} else if (isset($_POST['daily_group'])) {

	  		$roomKey    	= htmlspecialchars($_POST['roomkey_group_lookdaily']);
	  		$nama 			= htmlspecialchars($_POST['nama_siswa_or_groupkelas_lookdaily']);
	  		$nisOrIdGroup   = htmlspecialchars($_POST['nis_or_idgroup_lookdaily']);

	  		$queryNipGuru 		= mysqli_fetch_array(mysqli_query($con, "
	  			SELECT nip FROM group_kelas WHERE id = '$nisOrIdGroup'
	  		"));

	  		$nipGuru 		= $queryNipGuru['nip'];

	  		$nis_or_idgroup = $nisOrIdGroup;

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

		    $positionHeadMaster = mysqli_fetch_array(mysqli_query($con, "
		    	SELECT c_jabatan FROM guru WHERE nip = '$nipGuru'
		    "));

		    $isKepsekSD 	= preg_match($kepsekSD, $positionHeadMaster['c_jabatan']);
		    $isKepsekPAUD 	= preg_match($kepsekPAUD, $positionHeadMaster['c_jabatan']);

		    // Check Jika Pembuat Daily adalah NIP Kepala Sekolah sd atau tk saat login dari notif
	  		if ($foundDataSD == $isKepsekSD) {

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
	  		
	  		} else if ($foundDataPAUD == $isKepsekPAUD) {

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

	  	} else {
	  		$sesi = 0;
	  		$_SESSION['data'] = 'nodata';
	  	}

  	}

?>

	<style type="text/css">

		@media only screen and (max-width: 768px) {

			#nama_sswa {
				font-size: 10px;
			}

			#time_send {
				font-size: 10px;
			}

		}

	</style>

	<?php if ($sesi == 1): ?>

		<?php if(isset($_SESSION['data']) && $_SESSION['data'] == 'server_err'){?>
      		<?php 
             	unset($_SESSION['data']);
             	$fonnte_err = 1;
         	?>
        <?php } ?>

        <?php if(isset($_SESSION['data']) && $_SESSION['data'] == 'err_komen'){?>
      		<div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> Gagal Mengirim Komentar !</span> 
             	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             	<?php 
	             	unset($_SESSION['data']);
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
			          <span class="username" id="namaguru"> <?= strtoupper($guru); ?> </span>
			          <span class="description" id="tglpublish"> Published Date - <?= $tglPosting; ?> </span>
			        </div>
			        <!-- /.user-block -->
			      </div>
			      <!-- /.box-header -->
			      <div class="box-body" id="isian_daily">
			        <h4 style="color: black;"> <strong> TITLE : </strong> <?= $judul; ?>  </h4>
			        <img class="img-responsive pad" src="<?= $base; ?>image_uploads/<?= $foto; ?>" alt="Photo" style="width: auto; height: 300px;">
			        <?= $isi; ?>
			        
			        <!-- <button type="button" class="btn btn-default btn-xs"><i class="fa fa-share"></i> Share</button>
			        <button type="button" class="btn btn-default btn-xs"><i class="fa fa-thumbs-o-up"></i> Like</button>
			        <span class="pull-right text-muted">127 likes - 3 comments</span> -->
			      </div>

			    </div>

			   	<?php if( !empty(isset($_GET['q']) ) && isset($_POST['daily_group']) ): ?>

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
				    		<button type="hidden" name="send_mssg_grp" id="try_group"> send hide </button>
				    	</form>
				    </div>

			   	<?php elseif (!empty(isset($_GET['q']) ) && isset($_POST['roomkey_lookdaily']) ) : ?>

			   		<div class="tombol-hide" style="display: none;">
				    	<form action="<?= $_GET['q']; ?>" method="post">
					      	
					      	<input type="hidden" id="hg_frompage_lookdaily" name="frompage_lookdaily" value="<?= $fromPage; ?>">
					      	<input type="hidden" id="hg_roomkey_lookdaily" name="roomkey_lookdaily" value="<?= $roomKey; ?>">
					      	<input type="hidden" id="hg_nama_guru_lookdaily" name="guru_lookdaily" value="<?= $guru; ?>">
					      	<input type="hidden" id="hg_nama_siswa_lookdaily" name="nama_siswa_or_groupkelas_lookdaily" value="<?= $nama; ?>">
					      	<input type="hidden" id="hg_nis_siswa_lookdaily" name="nis_or_idgroup_lookdaily" value="<?= $nisOrIdGroup; ?>">
					      	<input type="hidden" id="hg_foto_upload_lookdaily" name="foto_upload_lookdaily" value="<?= $foto; ?>">
					      	<input type="hidden" id="hg_tgl_posting_lookdaily" name="tgl_posting_lookdaily" value="<?= $tglPosting; ?>">
					      	<input type="hidden" id="hg_tglori_posting_lookdaily" name="tglori_posting_lookdaily" value="<?= $tglOri; ?>">
					      	<input type="hidden" id="hg_jdl_posting_lookdaily" name="jdl_posting_lookdaily" value="<?= $judul; ?>">
					      	<input type="hidden" id="hg_isi_posting_lookdaily" name="isi_posting_lookdaily" value="<?= $isi; ?>">
				    		<button type="hidden" id="try_std"> send hide </button>
				    	</form>
				    </div>

			   	<?php elseif( !empty(isset($_GET['q']) ) && isset($_POST['krm'])  ): ?>

			    	<?php  

			    		$queryDailyStd = mysqli_query($con, "
					        SELECT id FROM daily_siswa_approved WHERE id IN (
					          SELECT daily_id FROM ruang_pesan WHERE room_key = '$_GET[q]'
					        )
				      	");

			    		$queryDailyGroup = mysqli_query($con, "
					        SELECT id, group_kelas_id FROM group_siswa_approved WHERE id IN (
					          SELECT daily_id FROM ruang_pesan WHERE room_key = '$_GET[q]'
					        )
				      	");

				      	$countDailyStd    = mysqli_num_rows($queryDailyStd);
      					$countDailyGroup  = mysqli_num_rows($queryDailyGroup);

			    	?>

			    	<?php if ($countDailyGroup == 1): ?>

			    		<?php  

			    			$getDataGroupKelasID = mysqli_fetch_assoc($queryDailyGroup)['group_kelas_id'];

			    		?>

			    		<div class="tombol-hide" style="display: none;">
					    	<form action="<?= $_GET['q']; ?>" method="post">
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
					    		<button type="hidden" name="krm" id="try_group"> send hide </button>
					    	</form>
					    </div>

			    	<?php elseif($countDailyStd == 1): ?>

			    		<div class="tombol-hide" style="display: none;">
					    	<form action="<?= $_GET['q']; ?>" method="post">
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
					    		<button type="hidden" name="krm" id="try_std"> send hide </button>
					    	</form>
					    </div>
			    		
			    	<?php endif ?>

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
				    		<button type="hidden" name="send_mssg_grp" id="try_group"> send hide </button>
				    	</form>
				    </div>

			   	<?php else: ?>

				    <div class="tombol-hide" style="display: none;">
				    	<form action="<?= $roomKey; ?>" method="post">
				    		<input type="hidden" name="frompage" value="<?= $fromPage; ?>">
				    		<input type="hidden" name="roomkey" value="<?= $roomKey; ?>">
				        	<input type="hidden" name="nis" value="<?= $nis_or_idgroup; ?>">
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

			    <!-- /.box -->
			</div>
		  <!-- /.col -->
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

							        <?php if ($data['fromnip'] == $nipKepsek): ?>

								    	<div class="direct-chat-msg right">
								            <div class="direct-chat-info clearfix">
								              <span id="kepsekchat" class="direct-chat-name pull-right"> <?= $data['nama_kepsek']; ?> </span>
								              <span id="tglsendkepsek" class="direct-chat-timestamp pull-left"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          	</div>
								          	<img class="direct-chat-img" src="<?= $base; ?>imgstatis/icon_chat.png" alt="Message User Image">
								          	<div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
							          	</div>

							        <?php elseif ($data['fromnip'] == $nipGuru): ?>

					        			<div class="direct-chat-msg">
								          <div class="direct-chat-info clearfix">
								            <span id="namaguruchat" class="direct-chat-name pull-left"> <?= strtoupper($data['nama_guru']); ?> </span>
								            <span id="tglsendguru" class="direct-chat-timestamp pull-right"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          </div>
								          <img class="direct-chat-img" src="<?= $base; ?>imgstatis/icon_chat.png" alt="Message User Image">
								          <div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
								        </div>

								    <?php else: ?>

								    	<div class="direct-chat-msg">
								            <div class="direct-chat-info clearfix">
							            		<span id="nama_sswa" class="direct-chat-name pull-left"> PARENT : <?= strtoupper($data['nama_siswa']); ?> </span>
								              	<span id="time_send" class="direct-chat-timestamp pull-right"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          	</div>
								          	<img class="direct-chat-img" src="<?= $base; ?>imgstatis/df.jpg" alt="Message User Image">
								          	<div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
							          	</div>
					        			
					        		<?php endif ?>
					        		
					        	<?php endforeach ?>

			        		<?php elseif(isset($_POST['send_mssg_grp'])): ?>

			        			<?php foreach ($getDataKomenOther as $data): ?>

							        <?php if ($data['fromnip'] == $nipKepsek): ?>

								    	<div class="direct-chat-msg right">
								            <div class="direct-chat-info clearfix">
								              <span id="kepsekchat" class="direct-chat-name pull-right"> <?= $data['nama_kepsek']; ?> </span>
								              <span id="tglsendkepsek" class="direct-chat-timestamp pull-left"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          	</div>
								          	<img class="direct-chat-img" src="<?= $base; ?>imgstatis/icon_chat.png" alt="Message User Image">
								          	<div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
							          	</div>

							        <?php elseif ($data['fromnip'] == $nipGuru): ?>

					        			<div class="direct-chat-msg">
								          <div class="direct-chat-info clearfix">
								            <span id="namaguruchat" class="direct-chat-name pull-left"> <?= strtoupper($data['nama_guru']); ?> </span>
								            <span id="tglsendguru" class="direct-chat-timestamp pull-right"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          </div>
								          <img class="direct-chat-img" src="<?= $base; ?>imgstatis/icon_chat.png" alt="Message User Image">
								          <div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
								        </div>

								    <?php else: ?>

								    	<div class="direct-chat-msg">
								            <div class="direct-chat-info clearfix">
							            		<span id="nama_sswa" class="direct-chat-name pull-left"> PARENT : <?= strtoupper($data['nama_siswa']); ?> </span>
								              	<span id="time_send" class="direct-chat-timestamp pull-right"> <?= tgl_indo($data['tanggal_kirim']) .' '. substr($data['tanggal_kirim'], 11, 19); ?> </span>
								          	</div>
								          	<img class="direct-chat-img" src="<?= $base; ?>imgstatis/df.jpg" alt="Message User Image">
								          	<div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
							          	</div>
					        			
					        		<?php endif ?>
					        		
					        	<?php endforeach ?>

			        		<?php else: ?>

				        		<?php foreach ($getDataKomenOther as $data): ?>

				        			<?php if ($data['fromnip'] == $nipKepsek): ?>

								    	<div class="direct-chat-msg right">
								         	<div class="direct-chat-info clearfix">
									            <span id="namakepsek" class="direct-chat-name pull-right"> <?= $data['nama_kepsek']; ?> </span>
									            <span id="tglsendkepsek" class="direct-chat-timestamp pull-left"> <?= format_tgl_indo($data['tanggal_kirim']); ?> </span>
								          	</div>
								          	<img class="direct-chat-img" src="<?= $base; ?>imgstatis/icon_chat.png" alt="Message User Image">
								          	<div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
								        </div>

								    <?php elseif($data['fromnip'] == $nipGuru): ?>
					        			
								    	<div class="direct-chat-msg">
								         	<div class="direct-chat-info clearfix">
									            <span id="namaguruchat" class="direct-chat-name pull-left"> <?= strtoupper($data['nama_guru']); ?> </span>
									            <span id="tglsendguru" class="direct-chat-timestamp pull-right"> <?= format_tgl_indo($data['tanggal_kirim']); ?> </span>
								          	</div>
								          	<img class="direct-chat-img" src="<?= $base; ?>imgstatis/icon_chat.png" alt="Message User Image">
								          	<div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
								        </div>

								    <?php elseif($data['fromnip'] == $nis_or_idgroup): ?>

								    	<div class="direct-chat-msg">
								         	<div class="direct-chat-info clearfix">
									            <span id="nama_sswa" class="direct-chat-name pull-left"> PARENT : <?= strtoupper($data['nama_siswa']); ?> </span>
									            <span id="time_send" class="direct-chat-timestamp pull-right"> <?= format_tgl_indo($data['tanggal_kirim']); ?> </span>
								          	</div>
								          	<img class="direct-chat-img" src="<?= $base; ?>imgstatis/df.jpg" alt="Message User Image">
								          	<div class="direct-chat-text" style="overflow-wrap: break-word;"> <?= htmlspecialchars($data['pesan']); ?> </div>
								        </div>

					        		<?php endif ?>
					        		
					        	<?php endforeach ?>

					        <?php endif ?>
			        		
			        	<?php endif ?>

			        </div>
			        <!-- /.direct-chat-pane -->
			      </div>
			      <!-- /.box-body -->
			      <div class="box-footer">
			      	<span class="input-group-btn">

		                <?php if(isset($_POST['daily_group'])): ?>

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
					                <span class="input-group-btn">
					                  <button type="submit" name="send_mssg_grp" id="send-btn" class="btn btn-success btn-flat">Send</button>
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
					                <span class="input-group-btn">
					                  <button type="submit" name="send_mssg" id="send-btn" class="btn btn-success btn-flat">Send</button>
					                </span>
					          </div>
					        </form>

		                <?php elseif (isset($_POST['roomkey_lookdaily'])): ?>

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
					                <span class="input-group-btn">
					                  <button type="submit" name="send_mssg" id="send-btn" class="btn btn-success btn-flat">Send</button>
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
						        	<input type="hidden" name="nis" value="<?= $nis_or_idgroup; ?>">
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
		  <!-- /.col -->
		</div>

		<div class="wrapx" style="display: flex; justify-content: flex-end;">

			<div class="row">
				
				<div class="col-md-3">

					<?php if ($fromPage == "status_approved"): ?>
						
						<form action="<?= $basekepsek; ?><?= $fromPage; ?>" method="post">
							<input type="hidden" name="nama" value="<?= $nama; ?>">
							<input type="hidden" name="nis" value="<?= $nis_or_idgroup; ?>">
			        		<button class="btn btn-sm btn-primary" type="submit" name="send_data_student"> <span class="glyphicon glyphicon-log-out" id="cancel"></span> Kembali </button>
			        	</form>
						<br>
						
					<?php elseif($fromPage == "teachercreatedaily"): ?>

						<form action="<?= $basekepsek; ?><?= $fromPage; ?>" method="post">
							<input type="hidden" name="nama" value="<?= $nama; ?>">
							<input type="hidden" name="nis" value="<?= $nis_or_idgroup; ?>">
			        		<button class="btn btn-sm btn-primary" type="submit" name="send_data_student"> <span class="glyphicon glyphicon-log-out" id="cancel"></span> Kembali </button>
			        	</form>
						<br>

					<?php elseif($fromPage == "createdailybyteacher"): ?>

						<form action="<?= $basekepsek; ?><?= $fromPage; ?>" method="post">
							<input type="hidden" name="nama_guru" value="<?= $guru; ?>">
							<input type="hidden" name="nip" value="<?= $nipGuru; ?>">
			        		<button class="btn btn-sm btn-primary" type="submit" name="send_data_student"> <span class="glyphicon glyphicon-log-out" id="cancel"></span> Kembali </button>
			        	</form>
						<br>

					<?php elseif($fromPage == "teachercreategroupdaily"): ?>

						<form action="<?= $basekepsek; ?><?= $fromPage; ?>" method="post">
							<input type="hidden" name="id_group" value="<?= $getDataGroupKelasID; ?>">
							<input type="hidden" name="nama_group_kelas" value="<?= str_replace(["GROUP", ], "", $nama); ?>">
			        		<button class="btn btn-sm btn-primary" type="submit" name="send_data_group"> <span class="glyphicon glyphicon-log-out" id="cancel"></span> Kembali </button>
			        	</form>
						<br>

					<?php elseif($fromPage == "homepage"): ?>

						<form action="<?= $basegu; ?>" method="post">
							<input type="hidden" name="nama" value="<?= $nama; ?>">
							<input type="hidden" name="nis" value="<?= $nis_or_idgroup; ?>">
			        		<button class="btn btn-sm btn-primary" type="submit" name="send_data_student"> <span class="glyphicon glyphicon-log-out" id="cancel"></span> Kembali </button>
			        	</form>
						<br>

					<?php elseif($fromPage == "querydailygroup"): ?>

						<form action="<?= $fromPage; ?>" method="post">
							<input type="hidden" name="nama" value="<?= $nama; ?>">
							<input type="hidden" name="nis" value="<?= $nis_or_idgroup; ?>">
			        		<button class="btn btn-sm btn-primary" type="submit" name="send_data_student"> <span class="glyphicon glyphicon-log-out" id="cancel"></span> Kembali </button>
			        	</form>
						<br>

					<?php else: ?>

						<form action="createdailybyteacher" method="post">
							<input type="hidden" name="nama" value="<?= $nama; ?>">
							<input type="hidden" name="nis" value="<?= $nis_or_idgroup; ?>">
			        		<button class="btn btn-sm btn-primary" type="submit" name="send_data_student"> <span class="glyphicon glyphicon-log-out" id="cancel"></span> Kembali </button>
			        	</form>
						<br>

					<?php endif ?>
					<br>
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
		             	$nis_or_idgroup = 0;
		             	unset($_SESSION['data']);
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
		             	$nis_or_idgroup = 2;
		             	unset($_SESSION['data']);
		             ?>
		          </div>
		        <?php } ?>

		    </div>
		</div>

	<?php endif ?>

<script type="text/javascript">

	let rooms 		= `<?= $key_room; ?>`
	let nip_guru   	= `<?= $nipGuru; ?>`
	let komenSes 	= `<?= $sesiKomen; ?>`
	let nis_siswa   = `<?= $nis_or_idgroup; ?>`
	let emptyKomen  = `<?= $empty; ?>`
	let fonnteError = `<?= $fonnte_err; ?>`

	// if(localStorage.getItem("showpopup") == "tidak") {

	// 	// alert("ok")

	// } else if(localStorage.getItem("showpopup") != "muncul") {
	// 	localStorage.setItem("showpopup", "muncul");
	// }

	function firstLoad(rmId) {
	    $.ajax({
	        url     : `<?= $basekepsek; ?>data_komen`,
	        method  : 'POST',
	        data    : {
	            room_id 	: rmId,
	            nip_guru   	: `<?= $nipGuru; ?>`,
	            nip_kepsek  : `<?= $users; ?>`,
	            nis_siswa   : nis_siswa
	        },
	        success:function(data) {
	          	$('#comments-box').scrollTop($('#comments-box')[0].scrollHeight);
        		let jumlahKomen        = JSON.parse(data).jumlah_komen;
        		let tglPosting        = JSON.parse(data).tgl_posting;

	            if(jumlahKomen == 0) {
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
		            }, 100);
		            $("#message-input").focus();

	            }

	        }
	    })
	}
	
	$(document).ready( function () {

		// if (localStorage.getItem("showpopup") == "muncul") {
		// 	Swal.fire({
		// 	  title: "Perhatian !",
		// 	  text: "Setiap 10 Detik Halaman ini akan Refresh Screen !",
		// 	  icon: "warning"
		// 	});
		// 	localStorage.setItem("showpopup", "tidak");
		// 	// alert(se) 
		// }

		if (fonnteError == 1) {
			console.log("Server Fonnte sedang dalam gangguan");
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

	    if (`<?= $nis_or_idgroup; ?>` == 0) {

			if (`<?= $isGroup; ?>` == "noparams") {
				setTimeout(() => {
					location.href = `<?= $basekepsek; ?>querydailygroup`
				}, 1000);	
			} else {
				setTimeout(() => {
					location.href = `<?= $basekepsek; ?>querydailystudent`
				}, 1000);
			}

		} else if (`<?= $nis_or_idgroup; ?>` == 2) {

			Swal.fire({
			  title: "Perhatian !",
			  text: "Akses Dialihkan !",
			  icon: "warning"
			});

			setTimeout(() => {
				location.href = `<?= $basekepsek; ?>`
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
		      	$("#refresh_btn_group").hide();
		      	$("#refresh_btn_std").hide();
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

	      	if (`<?= $isGroup; ?>` == true) {

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

	    // $('#send-btn').click(function(e){
	    //     e.preventDefault();        
	    //     var message = $('#message-input').val();
	    //     if (message !== "") {
	    //       	// alert(`<?= $users; ?>`)
	    //         appendMessage(message, rooms, `<?= $users; ?>`);

	            
	    //         $('#message-input').val(''); // Clear input field after sending
	    //         $('#message-input').focus();
	    //         $('#message-input').click();
	    //     } else {
	    //       alert('Tidak Boleh Kosong')
	    //     }
	    //     // $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
	    //     // $("#chat-box").animate({ scrollTop: $('#chat-box').prop("scrollHeight")}, 1000);
	    // });

	    let titleLists1   = ' - ACTIVITY ' + document.getElementById('titleList1').innerHTML
	    let elementWrap   = document.getElementById('boxTitle')

		let newIcon = document.getElementById("addIcon");
		newIcon.classList.remove("fa");
		newIcon.classList.add("fa");
		newIcon.classList.add("fa-user");

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
			location.href = `<?= $basekepsek; ?>querydailystudent`
	    }

  	});

  	function appendMessage(message, rm, usr) {
	    $.ajax({
	        url     : `<?= $basekepsek; ?>save_message`,
	        method  : 'POST',
	        data    : {
	            message 	: message,
	            room    	: rm,
	            user    	: usr
	        },
	        success:function(res) {
	          console.log(JSON.parse(res).room)
	            $.ajax({
	                url     : `<?= $basekepsek; ?>data`,
	                method  : 'POST',
	                data    : {
	                    room_id 	: JSON.parse(res).room,
	                    usr_kepsek  : `<?= $users; ?>`,
	                    usr_guru    : nip_guru,
	                    usr_otm 	: nis_siswa
	                },
	                success:function(respon) {
	                    firstLoad(`<?= $key_room; ?>`)
	                }
	            });
	        }
	    });
	}

</script>