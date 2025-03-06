<?php  

	require '../php/config.php';

	$arr = [];
	$res = [];

	if (isset($_POST['reset_daily'])) {
		$arr[] = $_POST['is_daily'];

		if ($arr[0] == "all") {
			$queryDeleteDailySiswa = mysqli_query($con, "
				DELETE FROM daily_siswa_approved
			");

			if ($queryDeleteDailySiswa) {
				$queryDeleteGroupDailySiswa = mysqli_query($con, "
					DELETE FROM group_siswa_approved
				");

				if ($queryDeleteGroupDailySiswa) {
					$queryResetPenomoran_idgroupkelas = mysqli_query($con, "
						UPDATE penomoran_idgroupkelas
						SET nourut = 0; 
					");

					if ($queryResetPenomoran_idgroupkelas) {
						$queryDeleteRuangPesan = mysqli_query($con, "
							DELETE FROM ruang_pesan
						");

						if ($queryDeleteRuangPesan) {

							$queryDeleteKomentar = mysqli_query($con, "
								DELETE FROM tbl_komentar
							");

							if ($queryDeleteKomentar) {
								$res[] = "success";
							}

						}

					}

				}

			}

		} else {

			$res[] = "fail";

		}

		echo json_encode($res);
	}

?>