<?php  

	require '../../../../php/config.php';

	$arr = [];
	$description = "";

	// $description = $_POST['editor_catatan'];

	// echo "Isi " . $description;exit;

	if (isset($_POST['simpan_daily'])) {

		// echo $_POST['id_group'];exit;

		$judulDaily  		= mysqli_real_escape_string($con, htmlspecialchars($_POST['jdl_daily']) );
		$dataNIS      		= htmlspecialchars($_POST['nis_siswa']); 
		$dataNIP     		= htmlspecialchars($_POST['nipguru']); 
		$isData 			= mysqli_real_escape_string($con, htmlspecialchars($_POST['editor_catatan'])) . "empty_or_no";

	  	$patternXSS         = "/script/i";
	  	$patternXSS2        = "/src/i";
	  	$patternXSS3        = "/href/i";
	  	$patternXSS4        = "/iframe/i";
	  	$patternXSS5        = "/javascript/i";

	  	$findDepartemenSD   = "/SD/i";
	  	$findDepartemenPAUD = "/PAUD/i";
	  	$allDepartement     = "/Kepala_Unit/i";

	  	$foundThreat        = preg_match($patternXSS, $isData);
	  	$foundThreat2       = preg_match($patternXSS2, $isData);
	  	$foundThreat3       = preg_match($patternXSS3, $isData);
	  	$foundThreat4       = preg_match($patternXSS4, $isData);
	  	$foundThreat5       = preg_match($patternXSS5, $isData);

	  	$isDepartemenSD  	= preg_match($findDepartemenSD, $_SESSION['c_guru']);
	  	$isDepartemenPAUD  	= preg_match($findDepartemenPAUD, $_SESSION['c_guru']);
	  	$isAllDepartement   = preg_match($allDepartement, $_SESSION['c_guru']);

	  	$queryGetStudentName	= mysqli_query($con, "SELECT nama FROM siswa WHERE nis = '$dataNIS' ");
	  	$getStudentName 		= mysqli_fetch_array($queryGetStudentName)['nama'];

	  	if ($dataNIS == '') {

	  		$_SESSION['nis_empty'] = "gagal";
			header("Location:". "$basegu" ."createdailystudent");

	  	} elseif ($dataNIS != '') {

	  		if ($judulDaily == '') {

				$_SESSION['title_empty'] = "gagal";
				header("Location:". "$basegu" ."createdailystudent");

			} elseif($judulDaily != '') {

				if ($isData == 'empty_or_no') {

					$_SESSION['main_daily_empty'] = "gagal";
					header("Location:". "$basegu" ."createdailystudent");

				} elseif ($isData != 'empty_or_no') {

					$isData = str_replace(["empty_or_no"], "", $isData);

					if ($foundThreat == 1) {

				  		$_SESSION['fail_form'] = "threat";
						header("Location:". "$basegu" ."createdailystudent");
				  		exit;

				  	} elseif($foundThreat2 == 1) {

				  		$_SESSION['fail_form'] = "threat";
						header("Location:". "$basegu" ."createdailystudent");
				  		exit;

				  	} elseif($foundThreat3 == 1) {

				  		$_SESSION['fail_form'] = "threat";
						header("Location:". "$basegu" ."createdailystudent");
				  		exit;

				  	} elseif($foundThreat4 == 1) {

				  		$_SESSION['fail_form'] = "threat";
						header("Location:". "$basegu" ."createdailystudent");
				  		exit;

				  	} elseif($foundThreat5 == 1) {

				  		$_SESSION['fail_form'] = "threat";
						header("Location:". "$basegu" ."createdailystudent");
				  		exit;

				  	} else {

						$namaFile       = $_FILES['banner']['name'];
						$tmpName 		= $_FILES['banner']['tmp_name'];

						$ekstensiGambarValid = ['jpg', 'jpeg', 'png'];

						$ekstensiGambar = explode('.', $namaFile);
						$ekstensiGambar = strtolower(end($ekstensiGambar) );

						if ($tmpName == '') {
							$_SESSION['fail_img'] = "no_foto";
							header("Location:". "$basegu" ."createdailystudent");
						} else if ($tmpName != '') {

							if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
								echo "<script>
										alert('Yang Anda Upload Bukan File Gambar !');
									  </script>";
								return false;  
							}

							$namaFileBaru 	= uniqid();
							$namaFileBaru  .= '.';
							$namaFileBaru  .= $ekstensiGambar;

							date_default_timezone_set("Asia/Jakarta");

			  				$tglSkrng       = date("Y-m-d H:i:s");

							$fileBaruKepsek = $namaFileBaru;
							$fileBaruGuru 	= $namaFileBaru;
							$fileBaruOtm    = $namaFileBaru;

							// move_uploaded_file($tmpName, '../../../../image_uploads/' . $namaFileBaru);

							if(move_uploaded_file($tmpName, '../../../../image_uploads/' . $namaFileBaru)){

			  					// echo "KESINI";exit;
							    copy('../../../../image_uploads/' . $fileBaruKepsek, '../../../../kepala_sekolah/image_uploads/' . $fileBaruKepsek);
							    copy('../../../../image_uploads/' . $fileBaruGuru, '../../../../guru/image_uploads/' . $fileBaruGuru);
							    copy('../../../../image_uploads/' . $fileBaruOtm, '../../../../walimurid/image_uploads/' . $fileBaruOtm);
							    //and so on...
							}

							if ($isDepartemenSD == 1) {

								$createRoomChatStd = mysqli_query($con, "
									INSERT INTO daily_siswa_approved 
									SET 
									departemen 		= 'SD',
									from_nip 		= '$dataNIP',
									nis_siswa 		= '$dataNIS',
									title_daily     = '$judulDaily',
									isi_daily 		= '$isData',
									image           = '$namaFileBaru',
									tanggal_dibuat  = '$tglSkrng',
									status_approve	= 0
								");

								if ($createRoomChatStd) {

									$_SESSION['create_room_std'] = "berhasil";

									// $curl = curl_init();

									// $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

									// Nomer HP Kepsek SD
									// $target = "6282110992502, 6289515998565";
									// $txt = [];

									// $original_array = ['895334303884', '82110992502/089515998565'];

									// for ($i=0; $i < count($original_array); $i++) {
										
									//     $check = substr($original_array[$i], 0, 1);
									    
									//     if($check == 8) {
									    
									//     	$changeFormat = "62" . str_replace(["/08"], ",628", $original_array[$i]);    
									//     	$txt[] = $changeFormat;
										
									//     } 
											
									// }

									// $queryGetDataNumberKepsek = mysqli_query($con, "
									// 	SELECT no_hp FROM guru WHERE nip = '2019032'
									// ");

									// $getNumberPhoneKepsek = mysqli_fetch_array($queryGetDataNumberKepsek)['no_hp'];

									// $destination_array = implode(',', $txt);

									// $pesan  = "*ADA NOTIF BARU DAILY SISWA _". strtoupper($getStudentName) ."_ UNTUK MENUNGGU PERSETUJUAN ANDA !*". "\n" . "\n" . $basekepsek . "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

									// curl_setopt_array($curl, array(
									//   CURLOPT_URL => 'https://api.fonnte.com/send',
									//   CURLOPT_RETURNTRANSFER => true,
									//   CURLOPT_ENCODING => '',
									//   CURLOPT_MAXREDIRS => 10,
									//   CURLOPT_TIMEOUT => 0,
									//   CURLOPT_FOLLOWLOCATION => true,
									//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
									//   CURLOPT_CUSTOMREQUEST => 'POST',
									//   CURLOPT_POSTFIELDS => array(
									//     'target' => $getNumberPhoneKepsek,
									//     'message' => $pesan
									//   ),
									//   CURLOPT_HTTPHEADER => array(
									//     'Authorization:a3vjVjL3S6xHpDg7NiaE' //change TOKEN to your actual token
									//   ),
									// ));

									// $response = curl_exec($curl);

									// if ($response == false) {

									// 	$_SESSION['create_room_std'] = "res_gagal";
									// 	header("Location:". "$basegu" ."createdailystudent");

									// } else {

									// 	curl_close($curl);

									// }

									header("Location:". "$basegu" ."createdailystudent");

								} else {

									$_SESSION['create_room_std'] = "gagal";
									header("Location:". "$basegu" ."createdailystudent");

								}

							} else if ($isDepartemenPAUD == 1) {

								$createRoomChatStd = mysqli_query($con, "
									INSERT INTO daily_siswa_approved 
									SET 
									departemen 		= 'PAUD',
									from_nip 		= '$dataNIP',
									nis_siswa 		= '$dataNIS',
									title_daily     = '$judulDaily',
									isi_daily 		= '$isData',
									image           = '$namaFileBaru',
									tanggal_dibuat  = '$tglSkrng',
									status_approve	= 0
								");

								if ($createRoomChatStd) {
									
									$_SESSION['create_room_std'] = "berhasil";

									// $curl = curl_init();

									// $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

									// Nomer HP Kepsek SD
									// $target = "6282110992502, 6289515998565";
									// $txt = [];

									// $original_array = ['895334303884', '82110992502/089515998565'];

									// for ($i=0; $i < count($original_array); $i++) {
										
									//     $check = substr($original_array[$i], 0, 1);
									    
									//     if($check == 8) {
									    
									//     	$changeFormat = "62" . str_replace(["/08"], ",628", $original_array[$i]);    
									//     	$txt[] = $changeFormat;
										
									//     } 
											
									// }

									// $queryGetDataNumberKepsek = mysqli_query($con, "
									// 	SELECT no_hp FROM guru WHERE nip = '2019034'
									// ");

									// $getNumberPhoneKepsek = mysqli_fetch_array($queryGetDataNumberKepsek)['no_hp'];

									// $destination_array = implode(',', $txt);
									
									// $pesan  = "*ADA NOTIF BARU DAILY SISWA _". strtoupper($getStudentName) ."_ UNTUK MENUNGGU PERSETUJUAN ANDA !*". "\n" . "\n" . $basekepsek . "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

									// curl_setopt_array($curl, array(
									//   CURLOPT_URL => 'https://api.fonnte.com/send',
									//   CURLOPT_RETURNTRANSFER => true,
									//   CURLOPT_ENCODING => '',
									//   CURLOPT_MAXREDIRS => 10,
									//   CURLOPT_TIMEOUT => 0,
									//   CURLOPT_FOLLOWLOCATION => true,
									//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
									//   CURLOPT_CUSTOMREQUEST => 'POST',
									//   CURLOPT_POSTFIELDS => array(
									//     'target' => $getNumberPhoneKepsek,
									//     'message' => $pesan
									//   ),
									//   CURLOPT_HTTPHEADER => array(
									//     'Authorization:a3vjVjL3S6xHpDg7NiaE' //change TOKEN to your actual token
									//   ),
									// ));

									// $response = curl_exec($curl);

									// if ($response == false) {

									// 	$_SESSION['create_room_std'] = "res_gagal";
									// 	header("Location:". "$basegu" ."create_room_std");

									// } else {

									// 	curl_close($curl);

									// }

									header("Location:". "$basegu" ."createdailystudent");
								
								} else {
								
									$_SESSION['create_room_std'] = "gagal";
									header("Location:". "$basegu" ."createdailystudent");
								
								}

							} else if ($isAllDepartement == 1) {

								// echo "Ke sini " . $dataNIS;exit;

								// Cari NIS apakah dia SD atau PAUD
								$queryFindDepartementByNis = mysqli_query($con, "
									SELECT c_kelas FROM siswa WHERE nis = '$dataNIS'
								");

								$getCodeKelas = mysqli_fetch_assoc($queryFindDepartementByNis)['c_kelas'];

								$isDepartSD   = preg_match($findDepartemenSD, $getCodeKelas);

								if ($isDepartSD == 1) {
									$getKelas = substr($getCodeKelas, 0, 1);
									// echo $getKelas;exit;

									$createRoomChatStd = mysqli_query($con, "
										INSERT INTO daily_siswa_approved 
										SET 
										departemen 		= 'SD',
										from_nip 		= '$dataNIP',
										nis_siswa 		= '$dataNIS',
										title_daily     = '$judulDaily',
										isi_daily 		= '$isData',
										image           = '$namaFileBaru',
										tanggal_dibuat  = '$tglSkrng',
										status_approve	= 0
									");

									if ($createRoomChatStd) {

										$_SESSION['create_room_std'] = "berhasil";
										header("Location:". "$basegu" ."createdailystudent");

									} else {
										$_SESSION['create_room_std'] = "gagal";
										header("Location:". "$basegu" ."createdailystudent");
									}

								} else {

									$createRoomChatStd = mysqli_query($con, "
										INSERT INTO daily_siswa_approved 
										SET 
										departemen 		= 'PAUD',
										from_nip 		= '$dataNIP',
										nis_siswa 		= '$dataNIS',
										title_daily     = '$judulDaily',
										isi_daily 		= '$isData',
										image           = '$namaFileBaru',
										tanggal_dibuat  = '$tglSkrng',
										status_approve	= 0
									");

									if ($createRoomChatStd) {

										$_SESSION['create_room_std'] = "berhasil";
										header("Location:". "$basegu" ."createdailystudent");

									} else {

										$_SESSION['create_room_std'] = "gagal";
										header("Location:". "$basegu" ."createdailystudent");

									}

								}
								

							}

						}

				  	}

				}

			}

	  	}

	} else if (isset($_POST['simpan_daily_group'])) {

		// echo "Untuk Group " . $_SESSION['c_guru'];exit;

		$judulDaily  		= mysqli_real_escape_string($con, htmlspecialchars($_POST['jdl_daily']) );
		$dataIdGroup 		= htmlspecialchars($_POST['id_group']);

		$dataNIP     		= htmlspecialchars($_POST['nipguru']); 
		$isData 			= mysqli_real_escape_string($con, htmlspecialchars($_POST['editor_catatan'])) . "empty_or_no";

		$patternXSS         = "/script/i";
	  	$patternXSS2        = "/src/i";
	  	$patternXSS3        = "/href/i";
	  	$patternXSS4        = "/iframe/i";
	  	$patternXSS5        = "/javascript/i";

	  	$findDepartemenSD   = "/SD/i";
	  	$findDepartemenPAUD = "/PAUD/i";

	  	$foundThreat        = preg_match($patternXSS, $isData);
	  	$foundThreat2       = preg_match($patternXSS2, $isData);
	  	$foundThreat3       = preg_match($patternXSS3, $isData);
	  	$foundThreat4       = preg_match($patternXSS4, $isData);
	  	$foundThreat5       = preg_match($patternXSS5, $isData);

	  	$isDepartemenSD  	= preg_match($findDepartemenSD, $_SESSION['c_guru']);
	  	$isDepartemenPAUD  	= preg_match($findDepartemenPAUD, $_SESSION['c_guru']);

	  	$queryGetNameGroup 	= mysqli_query($con, "SELECT nama_group_kelas FROM group_kelas WHERE id = '$dataIdGroup' ");
	  	$getNameGroupClass 	= mysqli_fetch_array($queryGetNameGroup)['nama_group_kelas'];

	  	// Check NIP guru walas
  		$nipGroupWalas = mysqli_fetch_array(mysqli_query($con, "
  			SELECT nip FROM group_kelas WHERE id = '$dataIdGroup'
  		"));

  		if ($nipGroupWalas['nip'] == $dataNIP) {

  			if ($dataIdGroup == '') {

		  		$_SESSION['idgroup_empty'] = "gagal";
				header("Location:". "$basegu" ."createdailygroup");

		  	} elseif ($dataIdGroup != '') {

		  		if ($judulDaily == '') {

					$_SESSION['title_group_empty'] = "gagal";
					header("Location:". "$basegu" ."createdailygroup");

				} elseif ($judulDaily != '') {

					if ($isData == 'empty_or_no') {

						$_SESSION['main_daily_empty'] = "gagal";
						header("Location:". "$basegu" ."createdailygroup");

					} elseif ($isData != 'empty_or_no') {

						$isData = str_replace(["empty_or_no"], "", $isData);

						if ($foundThreat == 1) {

					  		$_SESSION['fail_form'] = "threat";
							header("Location:". "$basegu" ."createdailygroup");
					  		exit;

					  	} elseif($foundThreat2 == 1) {

					  		$_SESSION['fail_form'] = "threat";
							header("Location:". "$basegu" ."createdailygroup");
					  		exit;

					  	} elseif($foundThreat3 == 1) {

					  		$_SESSION['fail_form'] = "threat";
							header("Location:". "$basegu" ."createdailygroup");
					  		exit;

					  	} elseif($foundThreat4 == 1) {

					  		$_SESSION['fail_form'] = "threat";
							header("Location:". "$basegu" ."createdailygroup");
					  		exit;

					  	} elseif($foundThreat5 == 1) {

					  		$_SESSION['fail_form'] = "threat";
							header("Location:". "$basegu" ."createdailygroup");
					  		exit;

					  	} else {

							$namaFile       = $_FILES['banner']['name'];
							$tmpName 		= $_FILES['banner']['tmp_name'];

							$ekstensiGambarValid = ['jpg', 'jpeg', 'png'];

							$ekstensiGambar = explode('.', $namaFile);
							$ekstensiGambar = strtolower(end($ekstensiGambar) );

							if ($tmpName == '') {
								$_SESSION['fail_img'] = "no_foto";
								header("Location:". "$basegu" ."createdailygroup");
							} else if ($tmpName != '') {

								if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
									echo "<script>
											alert('Yang Anda Upload Bukan File Gambar !');
										  </script>";
									return false;  
								}

								$namaFileBaru 	= uniqid();
								$namaFileBaru  .= '.';
								$namaFileBaru  .= $ekstensiGambar;
								
								$fileBaruKepsek = $namaFileBaru;
								$fileBaruGuru 	= $namaFileBaru;
								$fileBaruOtm    = $namaFileBaru;

								date_default_timezone_set("Asia/Jakarta");

				  				$tglSkrng       = date("Y-m-d H:i:s");

								// move_uploaded_file($tmpName, '../../../../image_uploads/' . $namaFileBaru);

				  				if(move_uploaded_file($tmpName, '../../../../image_uploads/' . $namaFileBaru)){

				  					// echo "KESINI";exit;
								    copy('../../../../image_uploads/' . $fileBaruKepsek, '../../../../kepala_sekolah/image_uploads/' . $fileBaruKepsek);
								    copy('../../../../image_uploads/' . $fileBaruGuru, '../../../../guru/image_uploads/' . $fileBaruGuru);
								    copy('../../../../image_uploads/' . $fileBaruOtm, '../../../../walimurid/image_uploads/' . $fileBaruOtm);
								}

								if ($isDepartemenSD == 1) {
									
									// move_uploaded_file($tmpName, '../../../../kepala_sekolah/image_uploads/' . $namaFileBaru);

									// echo "SD";exit;
									$seqc_sis=mysqli_fetch_array(mysqli_query($con,"SELECT (nourut + 1) as nourut FROM penomoran_idgroupkelas where kode='groupkelas' limit 1 "));
							      	$nomorurut = $seqc_sis['nourut'] ?? 0;

							      	$invID = str_pad($nomorurut, 4, '0', STR_PAD_LEFT);

							      	$kodseq = "groupkelas".$invID;

							      	$createRoomChatGroup = mysqli_query($con, "
										INSERT INTO group_siswa_approved 
										SET 
										id 				= '$kodseq',
										departemen 		= 'SD',
										from_nip 		= '$dataNIP',
										group_kelas_id 	= '$dataIdGroup',
										title_daily     = '$judulDaily',
										isi_daily 		= '$isData',
										image           = '$namaFileBaru',
										tanggal_dibuat  = '$tglSkrng',
										status_approve	= 0
									");

									if ($createRoomChatGroup) {

										$_SESSION['create_room_group'] = "berhasil";

										$penomoran=mysqli_query($con,"UPDATE penomoran_idgroupkelas set nourut='$nomorurut' where kode='groupkelas' ");

										if ($penomoran) {

											// $curl = curl_init();

											// $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

											// Nomer HP Kepsek SD
											// $target = "6282110992502, 6289515998565";
											// $txt = [];

											// $original_array = ['895334303884', '82110992502/089515998565'];

											// for ($i=0; $i < count($original_array); $i++) {
												
											//     $check = substr($original_array[$i], 0, 1);
											    
											//     if($check == 8) {
											    
											//     	$changeFormat = "62" . str_replace(["/08"], ",628", $original_array[$i]);    
											//     	$txt[] = $changeFormat;
												
											//     } 
													
											// }

											// $queryGetDataNumberKepsek = mysqli_query($con, "
											// 	SELECT no_hp FROM guru WHERE nip = '2019032'
											// ");

											// $getNumberPhoneKepsek = mysqli_fetch_array($queryGetDataNumberKepsek)['no_hp'];
											// echo $getNumberPhoneKepsek;exit;

											// $destination_array = implode(',', $txt);

											// $pesan  = "*ADA NOTIF BARU DAILY GROUP _". $getNameGroupClass ."_ UNTUK MENUNGGU PERSETUJUAN ANDA !*". "\n" . "\n" . $basekepsek . "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

											// curl_setopt_array($curl, array(
											//   CURLOPT_URL => 'https://api.fonnte.com/send',
											//   CURLOPT_RETURNTRANSFER => true,
											//   CURLOPT_ENCODING => '',
											//   CURLOPT_MAXREDIRS => 10,
											//   CURLOPT_TIMEOUT => 0,
											//   CURLOPT_FOLLOWLOCATION => true,
											//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
											//   CURLOPT_CUSTOMREQUEST => 'POST',
											//   CURLOPT_POSTFIELDS => array(
											//     'target' => $getNumberPhoneKepsek,
											//     'message' => $pesan
											//   ),
											//   CURLOPT_HTTPHEADER => array(
											//     'Authorization:a3vjVjL3S6xHpDg7NiaE' //change TOKEN to your actual token
											//   ),
											// ));

											// $response = curl_exec($curl);

											// if ($response == false) {

											// 	$_SESSION['create_room_group'] = "res_gagal";
											// 	header("Location:". "$basegu" ."createdailygroup");

											// } else {

											// 	curl_close($curl);

											// }

											header("Location:". "$basegu" ."createdailygroup");

										} else {

											header("Location:". "$basegu" ."createdailygroup");

										}

									} else {

										$_SESSION['create_room_group'] = "gagal";
										header("Location:". "$basegu" ."createdailygroup");

									}

								} else if ($isDepartemenPAUD == 1) {
									
									$seqc_sis=mysqli_fetch_array(mysqli_query($con,"SELECT (nourut + 1) as nourut FROM penomoran_idgroupkelas where kode='groupkelas' limit 1 "));
							      	$nomorurut = $seqc_sis['nourut'] ?? 0;

							      	$invID = str_pad($nomorurut, 4, '0', STR_PAD_LEFT);

							      	$kodseq = "groupkelas".$invID;

							      	$createRoomChatGroup = mysqli_query($con, "
										INSERT INTO group_siswa_approved 
										SET 
										id 				= '$kodseq',
										departemen 		= 'PAUD',
										from_nip 		= '$dataNIP',
										group_kelas_id 	= '$dataIdGroup',
										title_daily     = '$judulDaily',
										isi_daily 		= '$isData',
										image           = '$namaFileBaru',
										tanggal_dibuat  = '$tglSkrng',
										status_approve	= 0
									");

									if ($createRoomChatGroup) {

										$_SESSION['create_room_group'] = "berhasil";

										$penomoran=mysqli_query($con,"UPDATE penomoran_idgroupkelas set nourut='$nomorurut' where kode='groupkelas' ");

										if ($penomoran) {

											// $curl = curl_init();

											// $tkn    = "ao8uKDiJPQ7sMKHxidDJFwKPhFu7bLFjahKdhbpV";

											// Nomer HP Kepsek SD
											// $target = "6282110992502, 6289515998565";
											// $txt = [];

											// $original_array = ['895334303884', '82110992502/089515998565'];

											// for ($i=0; $i < count($original_array); $i++) {
												
											//     $check = substr($original_array[$i], 0, 1);
											    
											//     if($check == 8) {
											    
											//     	$changeFormat = "62" . str_replace(["/08"], ",628", $original_array[$i]);    
											//     	$txt[] = $changeFormat;
												
											//     } 
													
											// }

											// $queryGetDataNumberKepsek = mysqli_query($con, "
											// 	SELECT no_hp FROM guru WHERE nip = '2019034'
											// ");

											// $getNumberPhoneKepsek = mysqli_fetch_array($queryGetDataNumberKepsek)['no_hp'];

											// $destination_array = implode(',', $txt);

											// $pesan  = "*ADA NOTIF BARU DAILY GROUP _". $getNameGroupClass ."_ UNTUK MENUNGGU PERSETUJUAN ANDA !*". "\n" . "\n" . $basekepsek . "\n" . "\n" . "_*AKHYAR INTERNATIONAL ISLAMIC SCHOOL*_";

											// curl_setopt_array($curl, array(
											//   CURLOPT_URL => 'https://api.fonnte.com/send',
											//   CURLOPT_RETURNTRANSFER => true,
											//   CURLOPT_ENCODING => '',
											//   CURLOPT_MAXREDIRS => 10,
											//   CURLOPT_TIMEOUT => 0,
											//   CURLOPT_FOLLOWLOCATION => true,
											//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
											//   CURLOPT_CUSTOMREQUEST => 'POST',
											//   CURLOPT_POSTFIELDS => array(
											//     'target' => $getNumberPhoneKepsek,
											//     'message' => $pesan
											//   ),
											//   CURLOPT_HTTPHEADER => array(
											//     'Authorization:a3vjVjL3S6xHpDg7NiaE' //change TOKEN to your actual token
											//   ),
											// ));

											// $response = curl_exec($curl);

											// if ($response == false) {

											// 	$_SESSION['create_room_group'] = "res_gagal";
											// 	header("Location:". "$basegu" ."createdailygroup");

											// } else {

											// 	curl_close($curl);

											// }

											header("Location:". "$basegu" ."createdailygroup");

										} else {

											header("Location:". "$basegu" ."createdailygroup");

										}

									} else {
										$_SESSION['create_room_group'] = "gagal";
										header("Location:". "$basegu" ."createdailygroup");
									}

								}

							}

					  	}

					}

				}

		  	}

  		} else {
  			$_SESSION['invalid_nip'] = "gagal";
  			$_SESSION['namegroup']   = $getNameGroupClass;
			header("Location:". "$basegu" ."createdailygroup");
  		}

	} else {

		header("Location:". "$basegu");

	}

?>