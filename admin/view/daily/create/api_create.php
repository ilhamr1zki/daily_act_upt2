<?php  

	require '../../../../php/config.php';

	$arr = [];
	$description = "";

	// $description = $_POST['editor_catatan'];

	// echo "Isi " . $description;exit;

	if (isset($_POST['simpan_daily'])) {
		$judulDaily  		= htmlspecialchars($_POST['jdl_daily']);
		$dataNIS      		= htmlspecialchars($_POST['nis_siswa']); 
		$dataNIP     		= htmlspecialchars($_POST['fromNIP']); 
		$isData 			= $_POST['editor_catatan'];

	  	$patternXSS         = "/script/i";
	  	$patternXSS2        = "/src/i";
	  	$patternXSS3        = "/href/i";
	  	$patternXSS4        = "/iframe/i";
	  	$patternXSS5        = "/javascript/i";

	  	$foundThreat        = preg_match($patternXSS, $isData);
	  	$foundThreat2       = preg_match($patternXSS2, $isData);
	  	$foundThreat3       = preg_match($patternXSS3, $isData);
	  	$foundThreat4       = preg_match($patternXSS4, $isData);
	  	$foundThreat5       = preg_match($patternXSS5, $isData);

	  	if ($foundThreat == 1) {

	  		echo "Ditemukan Ancaman";
	  		$_SESSION['fail_form'] = "threat";
			header("Location:". "$basead" ."createdaily");
	  		exit;

	  	} elseif($foundThreat2 == 1) {

	  		echo "Ditemukan Ancaman";
	  		$_SESSION['fail_form'] = "threat";
			header("Location:". "$basead" ."createdaily");
	  		exit;

	  	} elseif($foundThreat3 == 1) {

	  		echo "Ditemukan Ancaman";
	  		$_SESSION['fail_form'] = "threat";
			header("Location:". "$basead" ."createdaily");
	  		exit;

	  	} elseif($foundThreat4 == 1) {

	  		echo "Ditemukan Ancaman";
	  		$_SESSION['fail_form'] = "threat";
			header("Location:". "$basead" ."createdaily");
	  		exit;

	  	} elseif($foundThreat5 == 1) {

	  		echo "Ditemukan Ancaman";
	  		$_SESSION['fail_form'] = "threat";
			header("Location:". "$basead" ."createdaily");
	  		exit;

	  	}  else {

	  		$namaFile       = $_FILES['banner']['name'];
			$tmpName 		= $_FILES['banner']['tmp_name'];

			$ekstensiGambarValid = ['jpg', 'jpeg', 'png'];

			$ekstensiGambar = explode('.', $namaFile);
			$ekstensiGambar = strtolower(end($ekstensiGambar) );

			if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
				echo "<script>
						alert('Yang Anda Upload Bukan File Gambar !');
					  </script>";
				return false;  
			}

			$namaFileBaru 	= uniqid();
			$namaFileBaru  .= '.';
			$namaFileBaru  .= $ekstensiGambar;

			move_uploaded_file($tmpName, '../../../../image_uploads/' . $namaFileBaru);

	  		mysqli_query($con, "
				INSERT INTO daily_siswa_approved 
				SET 
				from_nip 		= '$dataNIP',
				nis_siswa 		= '$dataNIS',
				title_daily     = '$judulDaily',
				isi_daily 		= '$isData',
				image           = '$namaFileBaru',
				status_approve	= 0
			");

	  	}

		// echo json_encode($_POST['editor_catatan']);

		// $nip         = htmlspecialchars($_POST['nipguru']);
		// $description = $_POST['editor_catatan'];

		

		// $queryCheckDataDaily = mysqli_query($con, "SELECT id, isi_daily FROM daily_siswa_approved WHERE nip_guru = '$nip' ");

		// $tampungData = [];

		// foreach ($queryCheckDataDaily as $data) {
		// 	$tampungData[] = $data['id'];
		// }

		// $dataEnd = end($tampungData);

		// mysqli_query($con, "
		// 	INSERT INTO history_log 
		// 	SET 
		// 	id_daily   	= '$dataEnd',
		// 	type_log 	= 'add'
		// ");

		$_SESSION['sukses'] = "berhasil";
		header("Location:". "$basead" ."createdaily");

		

		// echo $description;e

	// 	$dataNIP 				= htmlspecialchars($_POST['nip_guru']);
	// 	$saveData['nip_guru'] 	= $dataNIP; 
	// 	$arr['data_callback'] 	= $saveData;
		

	// 

	} else {

		// header("Location:". "$basead" ."createdaily");
		$_POST['editor_catatan'];

		echo json_encode($_POST['editor_catatan']);

		// $arr 				 = [];
		// $arr['title'] 		 = htmlspecialchars($_POST['jdl_daily']);
		// $arr['announcement'] = htmlspecialchars($_POST['editor_catatan']);
		// $arr['nama_gambar']  = $_FILES['banner']['name'];
		// $arr['tmp_name']  	 = $_FILES['banner']['tmp_name'];
		// $arr['status']  	 = "Success Add Data";

		// $namaFile       = $_FILES['banner']['name'];
		// $tmpName 		= $_FILES['banner']['tmp_name'];

		// $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];

		// $ekstensiGambar = explode('.', $namaFile);
		// $ekstensiGambar = strtolower(end($ekstensiGambar) );

		// if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
		// 	echo "<script>
		// 			alert('Yang Anda Upload Bukan File Gambar !');
		// 		  </script>";
		// 	return false;  
		// }

		// $namaFileBaru 	= uniqid();
		// $namaFileBaru  .= '.';
		// $namaFileBaru  .= $ekstensiGambar;

	}



?>