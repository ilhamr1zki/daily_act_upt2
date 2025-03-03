<?php 

	$timeOut        = $_SESSION['expire'];
    
    $timeRunningOut = time() + 5;

    $timeIsOut = 0;

    $level  = ['Administrator', 'Accounting'];
    $divisi = ['sd', 'tk'];

    $sesiErr = 0;

    // echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

    if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

        $_SESSION['form_success'] = "session_time_out";
        $timeIsOut = 1;
        error_reporting(1);
        // exit;

    } else {

    	if (isset($_POST['create_user'])) {

    		$nameOrUsername 			 = htmlspecialchars(strtolower($_POST['nmusr']));

    		$dataInputPasswordBaru       = htmlspecialchars($_POST['pw1']);
	        // echo strlen($dataInputPasswordBaru);exit;
		    $dataInputKonfirmasiPassword = htmlspecialchars($_POST['pw2']);

		    $isLevel 					 = mysqli_real_escape_string($con, htmlspecialchars($_POST['opt_level']));

		    $countADM 					 = mysqli_query($con, "SELECT username FROM admin WHERE username = '$nameOrUsername' ");
		    $countACC 					 = mysqli_query($con, "SELECT username FROM accounting WHERE username = '$nameOrUsername' ");

		    $checkDataUsernameADM 		 = mysqli_num_rows($countADM);
		    $checkDataUsernameACC 		 = mysqli_num_rows($countACC);

		    // Level Admin
		    if ($isLevel == 'Administrator') {

		    	if ($checkDataUsernameADM == 1) {
		    		
		    		$_SESSION['form_error'] = "data_duplicate";

		    	} else if ($checkDataUsernameADM == 0) {

		    		if (strlen($nameOrUsername) < 5) {
		    			
		    			$_SESSION['form_error'] = "name_too_short";

		    		} else if (strlen($nameOrUsername) >= 5) {

		    			if (strlen($dataInputPasswordBaru) < 5 || strlen($dataInputKonfirmasiPassword) < 5) {

				            $_SESSION['form_error'] = "change_password_too_short";

				        } else {

				            if ($dataInputPasswordBaru == $dataInputKonfirmasiPassword) {

				                $generatePassword = password_hash($dataInputPasswordBaru, PASSWORD_DEFAULT);

				                $getDataKeyADM  = mysqli_query($con, "SELECT c_admin FROM admin");
				                $arrKsg = [];
				                foreach ($getDataKeyADM as $data) {
				                	$arrKsg[] = $data['c_admin'];
				                }

				                $endData  	= end($arrKsg);
				                $removeStr 	= str_replace(["adm"],"",$endData);
				                $addKey		= $removeStr + 1;
				                $addKey		= "adm" . $addKey;

				                $execUserBaru  = mysqli_query($con, "
				                	INSERT INTO admin 
							        set
							        c_admin 	= '$addKey',
							        nama 		= '$isLevel',
							        username 	= '$nameOrUsername',
							        password 	= '$generatePassword'
								");
				                // echo $addKey . "<br>" . $generatePassword;
				                
				                if ($execUserBaru) {

				                	$_SESSION['form_success'] = "change_password_success";

				                } else if ($execUserBaru == false) {

				                	$_SESSION['error_mysql'] = "gagal";

				                }

				            } else {

				                $_SESSION['form_error'] = "new_password_error";
				                $nameOrUsername 		= $nameOrUsername;
				                $isLevel 				= $isLevel; 

				            }

				        }

		    		}

		    	}
		    	// exit;

		    }

		    // Level Accounting
		    else if ($isLevel == 'Accounting') {

		    	$divisiPendidikan = htmlspecialchars($_POST['divisi_pend']);

		    	if ($checkDataUsernameACC == 1) {
		    		
		    		$_SESSION['form_error'] = "data_duplicate";

		    	} else if ($checkDataUsernameACC == 0) {

		    		if (strlen($nameOrUsername) < 5) {
		    			
		    			$_SESSION['form_error'] = "name_too_short";

		    		} else if (strlen($nameOrUsername) >= 5) {

		    			if (strlen($dataInputPasswordBaru) < 5 || strlen($dataInputKonfirmasiPassword) < 5) {

				            $_SESSION['form_error'] = "change_password_too_short";

				        } else {

				            if ($dataInputPasswordBaru == $dataInputKonfirmasiPassword) {

				            	if ($divisiPendidikan != 'kosong') {

				            		$generatePassword = password_hash($dataInputPasswordBaru, PASSWORD_DEFAULT);

					                $getDataKeyADM  = mysqli_query($con, "SELECT c_accounting FROM accounting");
					                $arrKsg = [];
					                foreach ($getDataKeyADM as $data) {
					                	$arrKsg[] = $data['c_accounting'];
					                }

					                $endData  	= end($arrKsg);
					                $removeStr 	= str_replace(["accounting"],"",$endData);
					                $addKey		= $removeStr + 1;
					                $addKey		= "accounting" . $addKey;
					                $isLevel    = $isLevel . "_" . $divisiPendidikan;
					                // echo $isLevel;exit;

					                $execUserBaru  = mysqli_query($con, "
					                	INSERT INTO accounting 
								        set
								        c_accounting 	= '$addKey',
								        nama 			= '$isLevel',
								        username 		= '$nameOrUsername',
								        password 		= '$generatePassword'
									");
					                // echo $addKey . "<br>" . $generatePassword;
					                
					                if ($execUserBaru) {

					                	$_SESSION['form_success'] = "change_password_success";

					                } else if ($execUserBaru == false) {

					                	$_SESSION['error_mysql'] = "gagal";

					                }

				            	} else {

				            		$_SESSION['form_error'] 		= "divisi_empty";
					                $nameOrUsername 				= $nameOrUsername;
					                $dataInputPasswordBaru 			= $dataInputPasswordBaru;
					                $dataInputKonfirmasiPassword 	= $dataInputKonfirmasiPassword;
					                $isLevel 						= $isLevel; 

				            	}

				            } else {
				            	
				                $_SESSION['form_error'] = "new_password_error";
				                $nameOrUsername 		= $nameOrUsername;
				                $isLevel 				= $isLevel; 

				            }

				        }

		    		}

		    	}
		    	// exit;

		    }

		    // Jika Selain Admin dan Accounting
		    else {

		    	$_SESSION['form_error'] = "empty_level";

		    }

    	}

    }

?>

<div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12">

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

        <?php if(isset($_SESSION['form_error']) && $_SESSION['form_error'] == 'change_password_too_short'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Password terlalu pendek!
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php 
             	unset($_SESSION['form_error']); 
             	$sesiErr = 1;
             	$nameOrUsername = $nameOrUsername;
             	$isLevel = $isLevel;
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

        <?php if(isset($_SESSION['form_error']) && $_SESSION['form_error'] == 'new_password_error'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Password dan Konfirmasi Password Tidak Sesuai!
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php 
             	unset($_SESSION['form_error']); 
             	$sesiErr = 1;
             	$nameOrUsername = $nameOrUsername;
             	$isLevel = $isLevel;
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

    </div>
</div>

<div class="box box-info">
	<div class="box-header with-border">
    	<h3 class="box-title"> <i class="glyphicon glyphicon-new-window"></i> Tambah USER </h3>
    </div>
    <form action="tambahuser" method="post">
    	<div class="box-body" style="margin: 10px;">
    		<div class="row">

    			<div class="col-sm-2">
    				<label> Nama / Username </label>
    				<?php if ($sesiErr == 1 || $sesiErr == 2): ?>
						<input type="text" name="nmusr" onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9]/g, '')" id="nmusr" value="<?= $nameOrUsername; ?>" required oninvalid="this.setCustomValidity('Minimal 5 Karakter')" minlength="5" oninput="this.setCustomValidity('')" class="form-control" placeholder="Tanpa spasi">
					<?php else: ?>
    					<input type="text" name="nmusr" onkeyup="this.value=this.value.replace(/[^a-zA-Z0-9]/g, '')" required oninvalid="this.setCustomValidity('Minimal 5 Karakter')" minlength="5" oninput="this.setCustomValidity('')" id="nmusr" class="form-control" placeholder="Tanpa spasi">
    				<?php endif ?>
    			</div>
    			
    			<div class="col-sm-3" id="pass1">
    				<label> Password </label>
    				<?php if ($sesiErr == 1): ?>
    					<input type="password" name="pw1" id="pw1" required oninvalid="this.setCustomValidity('Minimal 5 Karakter')" minlength="5" oninput="this.setCustomValidity('')" class="form-control" placeholder="Minimal 5 Karakter" autofocus>
    					<div class="checkbox" id="swp" onmouseover="mouseOver()">
    						<i class="glyphicon glyphicon-eye-open" id="icnEye"></i> <span id="said"> Show </span> Password
    					</div>
					<?php elseif($sesiErr == 2): ?>
    					<input type="password" name="pw1" value="<?= $dataInputPasswordBaru; ?>" id="pw1" required oninvalid="this.setCustomValidity('Minimal 5 Karakter')" minlength="5" oninput="this.setCustomValidity('')" class="form-control" placeholder="Minimal 5 Karakter">
    					<div class="checkbox" id="swp" onmouseover="mouseOver()">
    						<i class="glyphicon glyphicon-eye-open" id="icnEye"></i> <span id="said"> Show </span> Password
    					</div>
    				<?php else: ?>
    					<input type="password" name="pw1" id="pw1" required oninvalid="this.setCustomValidity('Minimal 5 Karakter')" minlength="5" oninput="this.setCustomValidity('')" class="form-control" placeholder="Minimal 5 Karakter">
    					<div class="checkbox" id="swp" onmouseover="mouseOver()">
    						<i class="glyphicon glyphicon-eye-open" id="icnEye"></i> <span id="said"> Show </span> Password
    					</div>
    				<?php endif ?>
    			</div>

    			<div class="col-sm-3">
    				<label> Konfirmasi Password </label>
    				
    				<?php if ($sesiErr == 2): ?>
    					
    					<input type="password" name="pw2" value="<?= $dataInputKonfirmasiPassword; ?>" id="pw2" required oninvalid="this.setCustomValidity('Minimal 5 Karakter')" minlength="5" oninput="this.setCustomValidity('')" class="form-control" placeholder="Minimal 5 Karakter">
	    				<div class="checkbox" id="swp2" onmouseover="mouseOver2()">
							<i class="glyphicon glyphicon-eye-open" id="icnEye2"></i> <span id="said2"> Show </span> Password
						</div>

    				<?php else: ?>

    					<input type="password" name="pw2" id="pw2" required oninvalid="this.setCustomValidity('Minimal 5 Karakter')" minlength="5" oninput="this.setCustomValidity('')" class="form-control" placeholder="Minimal 5 Karakter">
	    				<div class="checkbox" id="swp2" onmouseover="mouseOver2()">
							<i class="glyphicon glyphicon-eye-open" id="icnEye2"></i> <span id="said2"> Show </span> Password
						</div>	

    				<?php endif ?>
    				
    			</div>

    			<div class="col-sm-2">
    				<label> Level User </label>
    				<select class="form-control" id="opt_level" name="opt_level" onchange="levelUsers()">

    					<?php if ($sesiErr == 1): ?>

    						<option> -- PILIH -- </option>
	    					<?php foreach ($level as $lvl): ?>
	    						<?php if ($lvl == 'Administrator'): ?>
	    							<option value="<?= $lvl; ?>" <?= ($lvl == $isLevel ) ? 'selected="selected"' : '' ?> > ADMINISTRATOR </option>
	    						<?php elseif($lvl == 'Accounting'): ?>
	    							<option value="<?= $lvl; ?>" <?= ($lvl == $isLevel ) ? 'selected="selected"' : '' ?> > ACCOUNTING </option>
	    						<?php endif ?>
	    					<?php endforeach ?>

	    				<?php elseif ($sesiErr == 2): ?>

    						<option> -- PILIH -- </option>
	    					<?php foreach ($level as $lvl): ?>
	    						<?php if ($lvl == 'Administrator'): ?>
	    							<option value="<?= $lvl; ?>" <?= ($lvl == $isLevel ) ? 'selected="selected"' : '' ?> > ADMINISTRATOR </option>
	    						<?php elseif($lvl == 'Accounting'): ?>
	    							<option value="<?= $lvl; ?>" <?= ($lvl == $isLevel ) ? 'selected="selected"' : '' ?> > ACCOUNTING </option>
	    						<?php endif ?>
	    					<?php endforeach ?>
							
						<?php elseif($sesiErr == 0): ?>
	    					
	    					<option> -- PILIH -- </option>
	    					<?php foreach ($level as $lvl): ?>
	    						<?php if ($lvl == 'Administrator'): ?>
	    							<option value="<?= $lvl; ?>"> ADMINISTRATOR </option>
	    						<?php elseif($lvl == 'Accounting'): ?>
	    							<option value="<?= $lvl; ?>"> ACCOUNTING </option>
	    						<?php endif ?>
	    					<?php endforeach ?>
    					
    					<?php endif ?>

    				</select>
    			</div>

    			<div class="col-sm-2" id="accOnly">
    				<label> Divisi Pendidikan </label>
    				<select class="form-control" name="divisi_pend">
    					<option value="kosong"> -- PILIH -- </option>
    					<?php foreach ($divisi as $dvs): ?>

    						<?php if ($dvs == 'sd'): ?>
    							<option value="<?= $dvs; ?>"> SD </option>
    						<?php elseif($dvs == 'tk'): ?>
    							<option value="<?= $dvs; ?>"> TK </option>
    						<?php endif ?>

    					<?php endforeach ?>
    				</select>
    			</div>

    		</div>

    		<br>

    		<div class="row">
    			<div class="col-sm-3">
    				<button class="btn btn-sm btn-success" type="submit" name="create_user"> <i class="glyphicon glyphicon-floppy-disk"></i> Create </button>
    			</div>
    		</div>

    	</div>
    </form>
</div>

<script type="text/javascript">
	
	let sesErr 		= `<?= $sesiErr; ?>`

	$(document).ready( function () {

		if (sesErr == 0) {
			$('#nmusr').focus();		
			$("#accOnly").hide();
		} else if (sesErr == 2) {
			$("#accOnly").show();
		}

	    $("#list_users").click();
	    $("#tambahuser").css({
	        "background-color" : "#ccc",
	        "color" : "black"
	    });

	    $("#swp").click(function(){
	    	let x = document.getElementById("pw1");
			if (x.type === "password") {
				$("#icnEye").removeClass("glyphicon-eye-open");
				$("#icnEye").addClass("glyphicon-eye-close");
				$("#said").text('Close')
			    x.type = "text";
			} else {
				x.type = "password";
				$("#icnEye").removeClass("glyphicon-eye-close");
				$("#icnEye").addClass("glyphicon-eye-open");
				$("#said").text('Show')
			}
	    })

	    $("#swp2").click(function(){
	    	let x = document.getElementById("pw2");
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

	    $('#nmusr').bind('input', function(){
		    $(this).val(function(_, v){
		     return v.replace(/\s+/g, '');
		    });
		});

  	});

  	function mouseOver() {
	  document.getElementById("swp").style.cursor = "pointer";
	}

	function mouseOver2() {
	  document.getElementById("swp2").style.cursor = "pointer";
	}

	function levelUsers() {
	    let dataLevelUser = document.getElementById("opt_level").value;
	    if (dataLevelUser == 'Administrator') {
	      $("#accOnly").hide();
	    } else if (dataLevelUser == 'Accounting') {
	      $("#accOnly").show();
	    } else {
	      $("#accOnly").hide();
	    }
  	}

</script>