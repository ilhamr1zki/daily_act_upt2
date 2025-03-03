<?php 

	$timeOut        = $_SESSION['expire'];
    
    $timeRunningOut = time() + 5;

    $timeIsOut = 0;

    $level  = ['Administrator', 'Accounting'];
    $divisi = ['sd', 'tk'];

    $sesiErr = 0;

    $sesiPage = 0;
    $role = 0;

    $getDataUsers = mysqli_query($con, "
    	SELECT admin.c_admin id_users, admin.username username, admin.nama level 
    	FROM admin 
       	UNION 
       	SELECT accounting.c_accounting id_users, accounting.username username, accounting.nama level FROM 
       	accounting
    ");

    // echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

    if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

        $_SESSION['form_success'] = "session_time_out";
        $timeIsOut = 1;
        error_reporting(1);
        // exit;

    } else {

    	if (isset($_POST['editData_users'])) {

    		$sesiPage = 1;

    		$idUsers 			= htmlspecialchars($_POST['id_users']);
    		$nameOrUsername 	= htmlspecialchars(strtolower($_POST['username_users']));
    		$levelUsersX		= htmlspecialchars($_POST['lvl_users']);
    		$levelUsers         = str_replace(["_sd", "_tk"], "", $levelUsersX);
    		$divisiUsers		= str_replace(["Accounting_"], "", $levelUsersX);

    		if ($divisiUsers == 'sd') {
    			$role = 1;
    		} elseif ($divisiUsers == 'tk') {
    			$role = 1;
    		} else {
    			if ($idUsers == 'accounting1') {
    				$role = 1;
    			} elseif ($idUsers == 'accounting2') {
    				$role = 1;
    			} else {
    				$role = 0;
    			}
    		}

    	} elseif (isset($_POST['changePass_users'])) {

    		$sesiPage 			= 2;
    		$idUsers 			= htmlspecialchars($_POST['id_users']);

    	} elseif (isset($_POST['update_user'])) {

    		$idUsers    = htmlspecialchars($_POST['idUsers']);
    		$currLevel  = htmlspecialchars($_POST['curr_lvl']);
    		$getKeyUser = mysqli_query($con, "
    			SELECT c_admin id_key, nama FROM admin
				WHERE c_admin = '$idUsers'
				UNION
				SELECT c_accounting id_key, nama FROM accounting
				WHERE c_accounting = '$idUsers' 
    		");

    		$getLevelUsers = mysqli_fetch_array($getKeyUser);

    		// Replace Nama
    		$getLevelUser = str_replace(["_tk", "_sd"], "", $getLevelUsers['nama']);

    		if($currLevel != 'kosong') {

    			// Jika Accounting
    			if ($currLevel == 'Accounting') {

    				// Jika Data Level Berubah
    				if ($currLevel != $getLevelUser) {

    					if (isset($_POST['divisi_pendidikan'])) {
	    					$dataDivisi = htmlspecialchars($_POST['divisi_pendidikan']);

	    					if ($dataDivisi == 'kosong') {
	    						$_SESSION['form_error'] = 'no_divisi';
	    					} else if ($dataDivisi != 'kosong') {

	    						$nama 		= $currLevel . "_" . $dataDivisi;
	    						$username 	= htmlspecialchars($_POST['nm_username']);

								// Jika Users Pindah Level
	    						// Check jika level sebelumnya level Admin
	    						$checkDataADMIN = mysqli_query($con, "SELECT * FROM admin WHERE c_admin = '$idUsers' ");
	    						$checkDataADM 	= mysqli_num_rows($checkDataADMIN);
	    						$getPasswordUserADM = mysqli_fetch_array($checkDataADMIN);
	    						$dataPasswordUser = $getPasswordUserADM['password'];

	    						$checkDataACC = mysqli_query($con, "SELECT * FROM accounting WHERE c_accounting = '$idUsers' ");
	    						$checkDataACC = mysqli_num_rows($checkDataACC);
	    						
	    						if ($checkDataADM == 1) {
	    							
	    							// Jika data level sebelum nya adalah admin dan pindah level menjadi accounting, maka hapus data users tersebut dari table admin
	    							// Dan Menambahkan Data User Baru di table accounting

	    							$getDataKeyACC  = mysqli_query($con, "SELECT c_accounting FROM accounting");
					                $arrKsg = [];
					                foreach ($getDataKeyACC as $data) {
					                	$arrKsg[] = $data['c_accounting'];
					                }

					                $endData  	= end($arrKsg);
					                $removeStr 	= str_replace(["accounting"],"",$endData);
					                $addKey		= $removeStr + 1;
					                $addKey		= "accounting" . $addKey;

	    							$insertDataUserBaru = mysqli_query($con, "
	    								INSERT INTO accounting 
								        set
								        c_accounting 	= '$addKey',
								        nama 			= '$nama',
								        username 		= '$username',
								        password 		= '$dataPasswordUser'
	    							");

	    							if ($insertDataUserBaru) {
	    								$_SESSION['form_success'] = 'data_update';

	    								$hapusDataUsersADM = mysqli_query($con, "DELETE FROM admin WHERE c_admin = '$idUsers' ");
	    								$getDataUsers = mysqli_query($con, "
									    	SELECT admin.c_admin id_users, admin.username username, admin.nama level 
									    	FROM admin 
									       	UNION 
									       	SELECT accounting.c_accounting id_users, accounting.username username, accounting.nama level FROM 
									       	accounting
									    ");

	    							} elseif($insertDataUserBaru == false) {
	    								$_SESSION['form_error'] = 'fail_sql';
	    							}

	    						}

	    					}

	    				}

	    			// Jika Data Level Tidak Berubah
    				} else if ($currLevel == $getLevelUser) {

			    		$combined     = $currLevel . "_" . htmlspecialchars($_POST['divisi_pendidikan']);

    					$dataDivisi = htmlspecialchars($_POST['divisi_pendidikan']);

    					$nama 		= $combined;
						$username 	= htmlspecialchars($_POST['nm_username']);

						$updateUser = mysqli_query($con, "
			    			UPDATE accounting
							SET 
							nama 	 	= '$nama', 
							username 	= '$username'
							WHERE c_accounting = '$idUsers' 
						");

						if ($updateUser) {
							$_SESSION['form_success'] = 'data_update';
							$getDataUsers = mysqli_query($con, "
						    	SELECT admin.c_admin id_users, admin.username username, admin.nama level 
						    	FROM admin 
						       	UNION 
						       	SELECT accounting.c_accounting id_users, accounting.username username, accounting.nama level FROM 
						       	accounting
						    ");
						} elseif ($updateUser == false) {
							$_SESSION['form_error'] = 'fail_sql';
						}

    				}

    			// Jika Administrator
    			} else if ($currLevel == 'Administrator') {

    				// Jika Data Level Berubah
    				if ($currLevel != $getLevelUser) {

    					$nama 		= $currLevel;
						$username 	= htmlspecialchars($_POST['nm_username']);

						// Jika Users Pindah Level
						// Check jika level sebelumnya level Accounting
						$checkDatACCOUNTING = mysqli_query($con, "SELECT * FROM accounting WHERE c_accounting = '$idUsers' ");
						$checkDataACC 		= mysqli_num_rows($checkDatACCOUNTING);
						$getPasswordUserACC = mysqli_fetch_array($checkDatACCOUNTING);
						$dataPasswordUser   = $getPasswordUserACC['password'];

						$checkDataADM 		= mysqli_query($con, "SELECT * FROM admin WHERE c_admin = '$idUsers' ");
						$checkDataADM 		= mysqli_num_rows($checkDataADM);
						
						if ($checkDataACC == 1) {
							
							// Jika data level sebelum nya adalah accounting dan pindah level menjadi admin, maka hapus data users tersebut dari table accounting
							// Dan Menambahkan Data User Baru di table admin

							$getDataKeyAdm  = mysqli_query($con, "SELECT c_admin FROM admin");
			                $arrKsg = [];
			                foreach ($getDataKeyAdm as $data) {
			                	$arrKsg[] = $data['c_admin'];
			                }

			                $endData  	= end($arrKsg);
			                $removeStr 	= str_replace(["adm"],"",$endData);
			                $addKey		= $removeStr + 1;
			                $addKey		= "adm" . $addKey;

							$insertDataUserBaru = mysqli_query($con, "
								INSERT INTO admin 
						        set
						        c_admin 		= '$addKey',
						        nama 			= '$nama',
						        username 		= '$username',
						        password 		= '$dataPasswordUser'
							");

							if ($insertDataUserBaru) {
								$_SESSION['form_success'] = 'data_update';
								$hapusDataUsersAcc = mysqli_query($con, "DELETE FROM accounting WHERE c_accounting = '$idUsers' ");
								$getDataUsers = mysqli_query($con, "
							    	SELECT admin.c_admin id_users, admin.username username, admin.nama level 
							    	FROM admin 
							       	UNION 
							       	SELECT accounting.c_accounting id_users, accounting.username username, accounting.nama level FROM 
							       	accounting
							    ");
							} elseif($insertDataUserBaru == false) {
								$_SESSION['form_error'] = 'fail_sql';
							}

						}

	    			// Jika Data Level Tidak Berubah
    				} else if ($currLevel == $getLevelUser) {

			    		$combined     = $currLevel . "_" . htmlspecialchars($_POST['divisi_pendidikan']);

    					$dataDivisi = htmlspecialchars($_POST['divisi_pendidikan']);

    					$nama 		= $combined;
						$username 	= htmlspecialchars($_POST['nm_username']);

						$updateUser = mysqli_query($con, "
			    			UPDATE admin
							SET 
							nama 	 	= '$currLevel', 
							username 	= '$username'
							WHERE c_admin = '$idUsers' 
						");

						if ($updateUser) {
							$_SESSION['form_success'] = 'data_update';
							$getDataUsers = mysqli_query($con, "
						    	SELECT admin.c_admin id_users, admin.username username, admin.nama level 
						    	FROM admin 
						       	UNION 
						       	SELECT accounting.c_accounting id_users, accounting.username username, accounting.nama level FROM 
						       	accounting
						    ");
						} elseif ($updateUser == false) {
							$_SESSION['form_error'] = 'fail_sql';
						}

    				}

    			}

    		}

    	} elseif(isset($_POST['update_password'])) {

    		$idUsers = htmlspecialchars($_POST['idUsers']);

    		// Check Jika data user level sekarang Admin
    		$getLevelUserADM   	= mysqli_query($con, "SELECT c_admin FROM admin WHERE c_admin = '$idUsers' ");
    		$hitungDataUserADM	= mysqli_num_rows($getLevelUserADM);

    		// Check Jika data user level sekarang Accounting
    		$getLevelUserACC    = mysqli_query($con, "SELECT c_accounting FROM accounting WHERE c_accounting = '$idUsers' ");
    		$hitungDataUserACC	= mysqli_num_rows($getLevelUserACC);

    		if ($hitungDataUserADM == 1) {

    			$dataPasswordLama   = mysqli_query($con, "SELECT password FROM admin WHERE c_admin = '$idUsers' "); 

		        $getPassword = mysqli_fetch_assoc($dataPasswordLama)['password'];

		        $dataInputPasswordSkrg       = htmlspecialchars($_POST['curr_pass']);
		        $dataInputPasswordBaru       = htmlspecialchars($_POST['new_pass']);
		        // echo strlen($dataInputPasswordBaru);exit;
		        $dataInputKonfirmasiPassword = htmlspecialchars($_POST['conf_new_pass']);

		        if (password_verify($dataInputPasswordSkrg, $getPassword)) {

		            if (strlen($dataInputPasswordBaru) < 5 || strlen($dataInputKonfirmasiPassword) < 5) {

		                $_SESSION['form_success'] = "change_password_too_short";
		                $dataInputPasswordSkrg    = htmlspecialchars($_POST['curr_pass']);
		                $sesiPage = 3;

		            } else {

		                if ($dataInputPasswordBaru == $dataInputKonfirmasiPassword) {

		                    $generatePassword = password_hash($dataInputPasswordBaru, PASSWORD_DEFAULT);

		                    mysqli_query($con, "UPDATE admin SET password = '$generatePassword' WHERE c_admin = '$idUsers' ");

		                    $_SESSION['form_success'] = "change_password_success";

		                } else {
		                    $_SESSION['form_success'] = "new_password_error";
		                	$sesiPage = 3;
		                }

		            }

		        } else {
		            $_SESSION['form_success'] = "change_password_error";
                	$sesiPage = 4;
		        }

    		} elseif ($hitungDataUserACC == 1) {

    			$dataPasswordLama   = mysqli_query($con, "SELECT password FROM accounting WHERE c_accounting = '$idUsers' "); 

		        $getPassword = mysqli_fetch_assoc($dataPasswordLama)['password'];

		        $dataInputPasswordSkrg       = htmlspecialchars($_POST['curr_pass']);
		        $dataInputPasswordBaru       = htmlspecialchars($_POST['new_pass']);
		        // echo strlen($dataInputPasswordBaru);exit;
		        $dataInputKonfirmasiPassword = htmlspecialchars($_POST['conf_new_pass']);

		        if (password_verify($dataInputPasswordSkrg, $getPassword)) {

		            if (strlen($dataInputPasswordBaru) < 5 || strlen($dataInputKonfirmasiPassword) < 5) {

		                $_SESSION['form_success'] = "change_password_too_short";
		                $sesiPage = 3;

		            } else {

		                if ($dataInputPasswordBaru == $dataInputKonfirmasiPassword) {

		                    $generatePassword = password_hash($dataInputPasswordBaru, PASSWORD_DEFAULT);

		                    mysqli_query($con, "UPDATE accounting SET password = '$generatePassword' WHERE c_accounting = '$idUsers' ");

		                    $_SESSION['form_success'] = "change_password_success";

		                } else {
		                    $_SESSION['form_success'] = "new_password_error";
		                	$sesiPage = 3;
		                }

		            }

		        } else {
		            $_SESSION['form_success'] = "change_password_error";
                	$sesiPage = 4;
		        }

    		}

    	}

    }

?>

<div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12">

    	<?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'change_password_error'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Password Sekarang Salah
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['form_success']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_error']) && $_SESSION['form_error'] == 'data_duplicate'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Nama / Username Telah Terdaftar
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['form_error']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_error']) && $_SESSION['form_error'] == 'name_too_short'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Nama / Username Terlalu Pendek
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['form_error']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_error']) && $_SESSION['form_error'] == 'empty_level'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Tidak Ada Level User Yang Di Pilih
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['form_error']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'change_password_too_short'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Password Baru terlalu pendek!
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php 
             	unset($_SESSION['form_success']);
             ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_error']) && $_SESSION['form_error'] == 'divisi_empty'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Harap Pilih Divisi Pendidikan Terlebih Dahulu!
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php 
             	unset($_SESSION['form_error']); 
             	$sesiErr 						= 2;
             	$nameOrUsername 				= $nameOrUsername;
             	$dataInputPasswordBaru 			= $dataInputPasswordBaru;
                $dataInputKonfirmasiPassword 	= $dataInputKonfirmasiPassword;
             	$isLevel 						= $isLevel;
             ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'new_password_error'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Password Baru dan Konfirmasi Password Baru Tidak Sesuai!
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php 
             	unset($_SESSION['form_success']);
             ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['error_mysql']) && $_SESSION['error_mysql'] == 'gagal'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Gagal Menambahkan User Baru!
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php 
             	unset($_SESSION['error_mysql']);
             ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'change_password_success'){?>
          <div style="display: none;" class="alert alert-warning alert-dismissable"> User Baru Berhasil Di Tambahkan!
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php 
             	unset($_SESSION['form_success']);
             ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'data_update'){?>
          <div style="display: none;" class="alert alert-warning alert-dismissable"> Data Berhasil Di Update!
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php 
             	unset($_SESSION['form_success']);
             ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_error']) && $_SESSION['form_error'] == 'no_divisi'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Tidak Ada Divisi Pendidikan Yang Dipilih!
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php 
             	unset($_SESSION['form_error']); 
             ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_error']) && $_SESSION['form_error'] == 'fail_sql'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Gagal Update Users!
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php 
             	unset($_SESSION['form_error']);
             ?>
          </div>
        <?php } ?>

    </div>
</div>

<div class="box box-info">
	
	<div class="box-header with-border">
    	<h3 class="box-title"> <i class="glyphicon glyphicon-new-window"></i> Edit USER </h3>
    </div>

    <?php if ($sesiPage == 0): ?>
    	
    	<div class="box-body table-responsive">
	        <table id="table_users" class="table table-bordered table-hover">
	          	<thead>
		            <tr>
		              <th style="text-align: center;" width="5%"> NO </th>
		              <th style="text-align: center;"> ID </th>
		              <th style="text-align: center;"> NAMA / USERNAME </th>
		              <th style="text-align: center;"> LEVEL </th>
		              <th style="text-align: center;" width="25%"> ACTION </th>
		            </tr>
		        </thead>
		        <tbody>

	          		<?php $no = 1; ?>
	          		<?php foreach ($getDataUsers as $users): ?>
	          			<tr>
			                <td style="text-align: center;"> <?= $no++; ?> </td>
			                <td style="text-align: center;"> <?= $users['id_users']; ?> </td>
			                <td style="text-align: center;"> <?= $users['username']; ?> </td>
			                <td style="text-align: center;"> <?= str_replace(["_SD", "_TK"],"",strtoupper($users['level'])); ?> </td>
			                <td id="button_act">
			                	<form action="edituser" method="post">
			                		<input type="hidden" name="id_users" value="<?= $users['id_users']; ?>">
			                		<input type="hidden" name="username_users" value="<?= $users['username']; ?>">
			                		<input type="hidden" name="lvl_users" value="<?= $users['level']; ?>">

			                    	<button class="btn btn-sm btn-primary" type="submit" name="editData_users"> <i class="glyphicon glyphicon-pencil"></i> Edit </button>
			                	</form>
			                	<form action="edituser" method="post">
			                		<input type="hidden" name="id_users" value="<?= $users['id_users']; ?>">
			                  		<button class="btn btn-sm btn-success" name="changePass_users"> <i class="glyphicon glyphicon-cog"></i> Change Password </button>
			                	</form>
			                  	<button class="btn btn-sm btn-danger" name="deleteData_Siswa" onclick="deleteData(`<?= $users['id_users']; ?>`, `<?= $users['username']; ?>`)"> <i class="glyphicon glyphicon-trash"></i> Delete </button>
			                </td>
			            </tr>
	          		<?php endforeach ?>

	          	</tbody>
	        </table>
	    </div>

	<?php elseif($sesiPage == 1): ?>
	        
        <form role="form" method="post" action="edituser">

            <div class="box-body">

	            <div class="row">
	                <div class="col-sm-12">
	                	<div class="box">

		                	<div class="box-body">

		                    	<div class="col-sm-2">
			                        <div class="form-group">
					                    <label>ID</label>
					                    <input type="text" readonly value="<?= $idUsers; ?>" name="idUsers" class="form-control">
					                </div>
			                    </div>

			                    <div class="col-sm-3">
			                        <div class="form-group">
					                    <label>NAMA / USERNAME</label>
					                    <input type="text" value="<?= $nameOrUsername; ?>" onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9]/g, '')" id="nm_username" placeholder="Minimal 5 Karakter dan tanpa spasi" name="nm_username" class="form-control">
					                </div>
			                    </div>

			                    <div class="col-sm-2">
			                        <div class="form-group">
					                    <label>LEVEL</label>
					                    <select class="form-control form-select" name="curr_lvl" id="curr_lvl" onchange="selectLevel()">
					                      <option value="kosong"> -- PILIH -- </option>
					                      <?php foreach ($level as $lvl): ?>
					                      	<option value="<?= $lvl; ?>" <?=($lvl == $levelUsers )?'selected="selected"':''?> > <?= ($lvl == 'Administrator') ? "ADMINISTRATOR" : "ACCOUNTING" ; ?> </option>
					                      <?php endforeach ?>

					                      <!-- <?php foreach ($jenis_kelamin as $jk): ?>
					                        <?php if ($jk == "L"): ?>
					                          <option value="<?= $jk; ?>" <?=($jk == $jkSiswa )?'selected="selected"':''?> > Laki - Laki </option>
					                        <?php elseif($jk == "P"): ?>
					                          <option value="<?= $jk; ?>" <?=($jk == $jkSiswa )?'selected="selected"':''?> > Perempuan </option>
					                        <?php endif ?>
					                      <?php endforeach ?> -->
					                    </select>
				                    
				                  	</div>
			                    </div>

			                    <div class="col-sm-2" id="dvs_pend">
			                        <div class="form-group">
					                    <label>Divisi Pendidikan</label>
					                    <select class="form-control" name="divisi_pendidikan">
					                    	<option value="kosong"> -- PILIH -- </option>
					                    	
					                    	<?php if ($idUsers == 'accounting1'): ?>

					                    		<?php foreach ($divisi as $dvs): ?>
					                    			<option value="<?= $dvs; ?>" <?=($dvs == 'sd' )?'selected="selected"':''; ?> > 
						                    			<?= ($dvs == 'tk') ? "TK" : "SD" ; ?> 
						                    		</option>
				                    			<?php endforeach ?>

				                    		<?php elseif($idUsers == 'accounting2'): ?>
					                    		
					                    		<?php foreach ($divisi as $dvs): ?>
					                    			<option value="<?= $dvs; ?>" <?=($dvs == 'tk' )?'selected="selected"':''; ?> > 
					                    				<?= ($dvs == 'tk') ? "TK" : "SD" ; ?> 
					                    			</option>
				                    			<?php endforeach ?>
				                    		
				                    		<?php else: ?>

				                    			<?php foreach ($divisi as $dvs): ?>
						                    		<option value="<?= $dvs; ?>" <?= ($dvs == $divisiUsers ) ? 'selected="selected"' : ''; ?> > 
						                    			<?= ($dvs == 'tk') ? "TK" : "SD" ; ?>
						                    		</option>
						                    	<?php endforeach ?>

					                    	<?php endif ?>

					                    </select>
					                </div>
			                    </div>

			                    <?php if ($levelUsers == 'Accounting'): ?>
			                    	
			                    	<div class="col-sm-2" id="dvs_pend2">
				                        <div class="form-group">
						                    <label>Divisi Pendidikan</label>
						                    <select class="form-control" name="divisi_pendidikan">
						                    	<option value="kosong"> -- PILIH -- </option>
						                    	
						                    	<?php if ($idUsers == 'accounting1'): ?>

						                    		<?php foreach ($divisi as $dvs): ?>
						                    			<option value="<?= $dvs; ?>" <?=($dvs == 'sd' )?'selected="selected"':''; ?> > 
							                    			<?= ($dvs == 'tk') ? "TK" : "SD" ; ?> 
							                    		</option>
					                    			<?php endforeach ?>

					                    		<?php elseif($idUsers == 'accounting2'): ?>
						                    		
						                    		<?php foreach ($divisi as $dvs): ?>
						                    			<option value="<?= $dvs; ?>" <?=($dvs == 'tk' )?'selected="selected"':''; ?> > 
						                    				<?= ($dvs == 'tk') ? "TK" : "SD" ; ?> 
						                    			</option>
					                    			<?php endforeach ?>
					                    		
					                    		<?php else: ?>

					                    			<?php foreach ($divisi as $dvs): ?>
							                    		<option value="<?= $dvs; ?>" <?= ($dvs == $divisiUsers ) ? 'selected="selected"' : ''; ?> > 
							                    			<?= ($dvs == 'tk') ? "TK" : "SD" ; ?>
							                    		</option>
							                    	<?php endforeach ?>

						                    	<?php endif ?>

						                    </select>
						                </div>
				                    </div>

			                    <?php endif ?>

	                    	</div>

	                  	</div>
	                </div>
	            </div>
			
			</div>

            <div class="box-footer" id="button_form_edit">
              <button type="submit" name="update_user" id="btnSimpan" class="btn btn-success btn-circle"><i class="glyphicon glyphicon-floppy-disk"></i> Update Users </button>

        </form>  

	    <form action="edituser" method="POST">
	        <button type="submit" name="back" id="btnSimpan" class="btn btn-primary btn-circle"><i class="glyphicon glyphicon-log-out" id="cancel"></i> Kembali </button>
	    </form>

          	</div>

    <!-- Change Password -->
    <?php elseif($sesiPage == 2): ?>

    	<form role="form" method="post" action="edituser">

            <div class="box-body">

	            <div class="row">
	                <div class="col-sm-12">
	                	<div class="box">

		                	<div class="box-body">

		                		<div class="col-sm-2">
			                        <div class="form-group">
					                    <label>ID</label>
					                    <input type="text" value="<?= $idUsers; ?>" name="idUsers" readonly class="form-control">
					                </div>
			                    </div>

			                    <div class="col-sm-3">
			                        <div class="form-group">
					                    <label>PASSWORD SEKARANG</label>
					                    <input type="password" required name="curr_pass" placeholder="password sekarang" id="curr_pass" class="form-control" autofocus>
					                    <div class="checkbox" id="swp1" onmouseover="mouseOver1()">
				                            <i class="glyphicon glyphicon-eye-open" id="icnEye1"></i> <span id="said1"> Show </span> Password
				                        </div>  
					                </div>
			                    </div>

			                    <div class="col-sm-3">
			                        <div class="form-group">
					                    <label>PASSWORD BARU</label>
					                    <input type="password" required name="new_pass" placeholder="min 5 Karakter" id="new_pass" class="form-control">
					                    <div class="checkbox" id="swp2" onmouseover="mouseOver2()">
				                            <i class="glyphicon glyphicon-eye-open" id="icnEye2"></i> <span id="said2"> Show </span> Password
				                        </div> 
					                </div>
			                    </div>

			                    <div class="col-sm-3">
			                        <div class="form-group">
					                    <label>KONFIRMASI PASSWORD BARU</label>
					                    <input type="password" required name="conf_new_pass" id="conf_new_pass" placeholder="min 5 Karakter" class="form-control">
					                    <div class="checkbox" id="swp3" onmouseover="mouseOver3()">
				                            <i class="glyphicon glyphicon-eye-open" id="icnEye3"></i> <span id="said3"> Show </span> Password
				                        </div>  
					                </div>
			                    </div>

	                    	</div>

	                  	</div>
	                </div>
	            </div>
			
			</div>

            <div class="box-footer" id="button_form_edit">
              <button type="submit" name="update_password" id="btnSimpan" class="btn btn-success btn-circle"><i class="glyphicon glyphicon-floppy-disk"></i> Change Password </button>

        </form>  

	    <form action="edituser" method="POST">
	        <button type="submit" name="back" id="btnSimpan" class="btn btn-primary btn-circle"><i class="glyphicon glyphicon-log-out" id="cancel"></i> Kembali </button>
	    </form>

          	</div>

    <!-- New Password too short -->
  	<?php elseif($sesiPage == 3): ?>

  		<form role="form" method="post" action="edituser">

            <div class="box-body">

	            <div class="row">
	                <div class="col-sm-12">
	                	<div class="box">

		                	<div class="box-body">

		                		<div class="col-sm-2">
			                        <div class="form-group">
					                    <label>ID</label>
					                    <input type="text" value="<?= $idUsers; ?>" name="idUsers" readonly class="form-control">
					                </div>
			                    </div>

			                    <div class="col-sm-3">
			                        <div class="form-group">
					                    <label>PASSWORD SEKARANG</label>
					                    <input type="password" required name="curr_pass" placeholder="password sekarang" value="<?= $dataInputPasswordSkrg; ?>" id="curr_pass" class="form-control">
					                    <div class="checkbox" id="swp1" onmouseover="mouseOver1()">
				                            <i class="glyphicon glyphicon-eye-open" id="icnEye1"></i> <span id="said1"> Show </span> Password
				                        </div>  
					                </div>
			                    </div>

			                    <div class="col-sm-3">
			                        <div class="form-group">
					                    <label>PASSWORD BARU</label>
					                    <input type="password" required name="new_pass" placeholder="min 5 Karakter" id="new_pass" class="form-control" autofocus>
					                    <div class="checkbox" id="swp2" onmouseover="mouseOver2()">
				                            <i class="glyphicon glyphicon-eye-open" id="icnEye2"></i> <span id="said2"> Show </span> Password
				                        </div> 
					                </div>
			                    </div>

			                    <div class="col-sm-3">
			                        <div class="form-group">
					                    <label>KONFIRMASI PASSWORD BARU</label>
					                    <input type="password" required name="conf_new_pass" id="conf_new_pass" placeholder="min 5 Karakter" class="form-control">
					                    <div class="checkbox" id="swp3" onmouseover="mouseOver3()">
				                            <i class="glyphicon glyphicon-eye-open" id="icnEye3"></i> <span id="said3"> Show </span> Password
				                        </div>  
					                </div>
			                    </div>

	                    	</div>

	                  	</div>
	                </div>
	            </div>
			
			</div>

            <div class="box-footer" id="button_form_edit">
              <button type="submit" name="update_password" id="btnSimpan" class="btn btn-success btn-circle"><i class="glyphicon glyphicon-floppy-disk"></i> Change Password </button>

        </form>  

	    <form action="edituser" method="POST">
	        <button type="submit" name="back" id="btnSimpan" class="btn btn-primary btn-circle"><i class="glyphicon glyphicon-log-out" id="cancel"></i> Kembali </button>
	    </form>

          	</div>

    <!-- Wrong Current Password -->
    <?php elseif($sesiPage == 4): ?>

    	<form role="form" method="post" action="edituser">

            <div class="box-body">

	            <div class="row">
	                <div class="col-sm-12">
	                	<div class="box">

		                	<div class="box-body">

		                		<div class="col-sm-2">
			                        <div class="form-group">
					                    <label>ID</label>
					                    <input type="text" value="<?= $idUsers; ?>" name="idUsers" readonly class="form-control">
					                </div>
			                    </div>

			                    <div class="col-sm-3">
			                        <div class="form-group">
					                    <label>PASSWORD SEKARANG</label>
					                    <input type="password" required name="curr_pass" placeholder="password sekarang" autofocus id="curr_pass" class="form-control">
					                    <div class="checkbox" id="swp1" onmouseover="mouseOver1()">
				                            <i class="glyphicon glyphicon-eye-open" id="icnEye1"></i> <span id="said1"> Show </span> Password
				                        </div>  
					                </div>
			                    </div>

			                    <div class="col-sm-3">
			                        <div class="form-group">
					                    <label>PASSWORD BARU</label>
					                    <input type="password" required name="new_pass" placeholder="min 5 Karakter" id="new_pass" class="form-control">
					                    <div class="checkbox" id="swp2" onmouseover="mouseOver2()">
				                            <i class="glyphicon glyphicon-eye-open" id="icnEye2"></i> <span id="said2"> Show </span> Password
				                        </div> 
					                </div>
			                    </div>

			                    <div class="col-sm-3">
			                        <div class="form-group">
					                    <label>KONFIRMASI PASSWORD BARU</label>
					                    <input type="password" required name="conf_new_pass" id="conf_new_pass" placeholder="min 5 Karakter" class="form-control">
					                    <div class="checkbox" id="swp3" onmouseover="mouseOver3()">
				                            <i class="glyphicon glyphicon-eye-open" id="icnEye3"></i> <span id="said3"> Show </span> Password
				                        </div>  
					                </div>
			                    </div>

	                    	</div>

	                  	</div>
	                </div>
	            </div>
			
			</div>

            <div class="box-footer" id="button_form_edit">
              <button type="submit" name="update_password" id="btnSimpan" class="btn btn-success btn-circle"><i class="glyphicon glyphicon-floppy-disk"></i> Change Password </button>

        </form>  

	    <form action="edituser" method="POST">
	        <button type="submit" name="back" id="btnSimpan" class="btn btn-primary btn-circle"><i class="glyphicon glyphicon-log-out" id="cancel"></i> Kembali </button>
	    </form>

          	</div>

    <?php endif ?>

</div>

<script type="text/javascript">

	let roles  = `<?= $role; ?>`
	
	$("#dvs_pend").hide();
	$(document).ready( function () {
	    $("#list_users").click();
	    $("#edituser").css({
	        "background-color" : "#ccc",
	        "color" : "black"
	    });

	    $('#nm_username').bind('input', function(){
		    $(this).val(function(_, v){
		     return v.replace(/\s+/g, '');
		    });
		});

		$("#swp1").click(function(){
            let x = document.getElementById("curr_pass");
            if (x.type === "password") {
                $("#icnEye1").removeClass("glyphicon-eye-open");
                $("#icnEye1").addClass("glyphicon-eye-close");
                $("#said1").text('Close')
                x.type = "text";
            } else {
                x.type = "password";
                $("#icnEye1").removeClass("glyphicon-eye-close");
                $("#icnEye1").addClass("glyphicon-eye-open");
                $("#said1").text('Show')
            }
        })

        $("#swp2").click(function(){
            let x = document.getElementById("new_pass");
            if (x.type === "password") {
                $("#icnEye2").removeClass("glyphicon-eye-open");
                $("#icnEye2").addClass("glyphicon-eye-close");
                $("#said2").text('Close')
                x.type = "text";
            } else {
                x.type = "password";
                $("#icnEye2").removeClass("glyphicon-eye-close");
                $("#icnEye2").addClass("glyphicon-eye-open");
                $("#said2").text('Show')
            }
        })

        $("#swp3").click(function(){
            let x = document.getElementById("conf_new_pass");
            if (x.type === "password") {
                $("#icnEye3").removeClass("glyphicon-eye-open");
                $("#icnEye3").addClass("glyphicon-eye-close");
                $("#said3").text('Close')
                x.type = "text";
            } else {
                x.type = "password";
                $("#icnEye3").removeClass("glyphicon-eye-close");
                $("#icnEye3").addClass("glyphicon-eye-open");
                $("#said3").text('Show')
            }
        })

	});

	function selectLevel() {
		let dataLevelUser = document.getElementById("curr_lvl").value;
		if (roles == 0) {
		    if (dataLevelUser == 'Administrator') {
		      $("#dvs_pend").hide();
		    } else if (dataLevelUser == 'Accounting') {
		      $("#dvs_pend").show();
		    } else {
		      $("#dvs_pend").hide();
		    }
		} else if (roles == 1) {
			$("#dvs_pend").hide();
			if (dataLevelUser == 'Administrator') {
		      $("#dvs_pend2").hide();
		    } else if (dataLevelUser == 'Accounting') {
		      $("#dvs_pend2").show();
		    } else {
		      $("#dvs_pend2").hide();
		    }
		}
	}

	function deleteData(c_key, nm) {

	    Swal.fire({
	      title: "Are you sure ? ",
	      text: `Are you sure want to delete data ${nm} !`,
	      icon: "warning",
	      showCancelButton: true,
	      confirmButtonColor: "#008d4c",
	      cancelButtonColor: "#3c8dbc",
	      confirmButtonText: "Yes, delete it!"
	    }).then((result) => {
	      if (result.isConfirmed) {

	        $.ajax({
	          url : `<?= $basead; ?>apidatausers.php`,
	          type : "POST",
	          data : {
	            datakey : c_key
	          },
	          success:function(data){
	            if (data == 'sukses') {
	              setTimeout(refreshPage, 1000);
	              Swal.fire({
	                title: "Deleted!",
	                text: "Data successfully deleted",
	                icon: "success"
	              });
	            } else if (data == 'gagal') {
	              Swal.fire({
	                title: "Fail!",
	                text: "Deleted Unsuccessfully",
	                icon: "error"
	              });
	            }

	            function refreshPage() {
	              location.replace(`<?= $basead; ?>edituser`)
	            }

	          }
	        })
	      }
	    });

	}

	function mouseOver1() {
      document.getElementById("swp1").style.cursor = "pointer";
    }

    function mouseOver2() {
      document.getElementById("swp2").style.cursor = "pointer";
    }

    function mouseOver3() {
      document.getElementById("swp3").style.cursor = "pointer";
    }

</script>