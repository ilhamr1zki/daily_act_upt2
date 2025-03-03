<?php  

	require '../php/config.php'; 

	if (isset($_POST['dataNis'])) {
		$nis= $_POST['dataNis'];
		$dataHps = mysqli_query($con, "
			DELETE FROM data_murid_sd WHERE NIS IN
				(
					SELECT NIS FROM data_murid_tk WHERE NIS = '$nis'
				 	UNION 
				 	SELECT NIS FROM data_murid_sd WHERE NIS = '$nis'
				 )
			"
		);

		$perubahanData = mysqli_affected_rows($con);
		
		if ($perubahanData != 0) {
			echo "sukses";
		} else {
			echo "gagal";
		}

	}

?>