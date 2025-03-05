<?php 

	if (isset($_POST['spp'])) {
		$sppx = str_replace(['"'],"", $_POST['spp']);
		echo json_encode(intval($sppx));
	}

?>