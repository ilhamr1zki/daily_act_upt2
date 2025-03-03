<?php  
	
	require '../php/config.php'; 
	
	$arr = [];

	if (isset($_POST['is_out'])) {
		if ($_POST['is_out'] == true) {
			session_destroy();
			$arr['is_val'] = true;
		}

		echo json_encode($arr);

	} else {

		$arr['is_val'] = false;
		echo json_encode($arr);
		header("Location: $basekepsek");

	}

?>