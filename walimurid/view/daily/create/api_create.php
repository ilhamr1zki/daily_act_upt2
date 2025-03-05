<?php  

	require '../../../../php/config.php';

	$arr = [];
	$description = "";

	// $description = $_POST['editor_catatan'];

	// echo "Isi " . $description;exit;

	if (isset($_POST['simpan_daily'])) {

		$nip         = htmlspecialchars($_POST['nipguru']);
		$description = $_POST['editor_catatan'];

		mysqli_query($con, "
			INSERT INTO daily_siswa_approved 
			SET 
			nip_guru   		= '$nip',
			isi_daily 		= '$description',
			status_approve	= 0
		");

		$queryCheckDataDaily = mysqli_query($con, "SELECT id, isi_daily FROM daily_siswa_approved WHERE nip_guru = '$nip' ");

		$tampungData = [];

		foreach ($queryCheckDataDaily as $data) {
			$tampungData[] = $data['id'];
		}

		$dataEnd = end($tampungData);

		mysqli_query($con, "
			INSERT INTO history_log 
			SET 
			id_daily   	= '$dataEnd',
			type_log 	= 'add'
		");

		$_SESSION['sukses'] = "berhasil";
		header("Location:". "$basegu" ."createdaily");

		

		// echo $description;e

	// 	$dataNIP 				= htmlspecialchars($_POST['nip_guru']);
	// 	$saveData['nip_guru'] 	= $dataNIP; 
	// 	$arr['data_callback'] 	= $saveData;
		

	// 

	} else {

		header("Location:". "$basegu" ."createdaily");

	}

?>