<?php 

	$timeOut        = $_SESSION['expire_paud'];
    
	$timeRunningOut = time() + 5;

	$timeIsOut      = 0;
	$isNoParam 		= 0;
	$no 			= 1;

	error_reporting(1);

	if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

	    $_SESSION['form_success'] = "session_time_out";

	    $timeIsOut = 1;
	    error_reporting(1);

  	} else {

		// echo $_GET['q'];
		if ($_GET['q'] == false) {
			$isNoParam  = 1; 
		} 

		$queryGetDataWalas	= mysqli_query($con, "
			SELECT walas FROM group_kelas
			WHERE nama_group_kelas = '$_GET[q]'
		");

		$getDataWalas = mysqli_fetch_array($queryGetDataWalas)['walas'];
		// echo str_replace([" S.Pd", ","], "", $getDataWalas);exit;

		$findIdGroupKelas = mysqli_query($con, "
        SELECT id FROM group_kelas
        WHERE nama_group_kelas = '$_GET[q]'
      ");

      $getidGroupKelas = mysqli_fetch_array($findIdGroupKelas)['id'];

		$queryGetAllDataStudentByGroup = mysqli_query($con, "

			SELECT * FROM siswa
			WHERE group_kelas = '$getidGroupKelas'
			GROUP BY nis ASC

		");

		if (isset($_POST['update_siswa'])) {

			$nis 		= htmlspecialchars($_POST['nis']);
			$nama 	= mysqli_real_escape_string($con, htmlspecialchars($_POST['nama_siswa']));
			$nohpwa 	= htmlspecialchars($_POST['nohpotm']);

			if ($nohpwa == null) {

				$_SESSION['update_data'] = 'empty_number';

				$findIdGroupKelas = mysqli_query($con, "
		        SELECT id FROM group_kelas
		        WHERE nama_group_kelas = '$_GET[q]'
		      ");

		      $getidGroupKelas = mysqli_fetch_array($findIdGroupKelas)['id'];

				$queryGetAllDataStudentByGroup = mysqli_query($con, "

					SELECT * FROM siswa
					WHERE group_kelas = '$getidGroupKelas'
					GROUP BY nis ASC

				");

			} else {

				$queryUpdateNamaSiswa = mysqli_query($con, "
					UPDATE siswa 
					SET
					nama 	= '$nama',
					hp 	= '$nohpwa'
					WHERE nis = '$nis'
				");

				if ($queryUpdateNamaSiswa) {

					$queryUpdateAksesOTM = mysqli_query($con, "
						UPDATE akses_otm 
						SET
						no_hp 	= '$nohpwa'
						WHERE nis_siswa = '$nis'
					");

					if ($queryUpdateAksesOTM) {

						$_SESSION['update_nama_siswa'] = 'berhasil';

						$findIdGroupKelas = mysqli_query($con, "
				        SELECT id FROM group_kelas
				        WHERE nama_group_kelas = '$_GET[q]'
				      ");

				      $getidGroupKelas = mysqli_fetch_array($findIdGroupKelas)['id'];

						$queryGetAllDataStudentByGroup = mysqli_query($con, "

							SELECT * FROM siswa
							WHERE group_kelas = '$getidGroupKelas'
							GROUP BY nis ASC

						");

					} else {

						$_SESSION['update_nama_siswa'] = 'gagal_akses_otm';

						$queryGetAllDataStudentByGroup = mysqli_query($con, "

							SELECT * FROM siswa
							WHERE group_kelas = '$_GET[q]'
							GROUP BY nis ASC

						");

					}

				} else {
					
					$_SESSION['update_nama_siswa'] = 'gagal';

					$queryGetAllDataStudentByGroup = mysqli_query($con, "

						SELECT * FROM siswa
						WHERE group_kelas = '$_GET[q]'
						GROUP BY nis ASC

					");

				}

			}

		}

		if (isset($_POST['out_group'])) {

			$nis_siswa = htmlspecialchars($_POST['nis_siswa']);
			
			// find group kelas by nis
			$queryGetGroupKelas = mysqli_query($con, "
				SELECT group_kelas FROM siswa
				WHERE nis = '$nis_siswa'
			");

			$id_group_kelas = mysqli_fetch_array($queryGetGroupKelas)['group_kelas'];

			$findIdGroupKelasForOut = mysqli_query($con, "
				SELECT nama_group_kelas FROM group_kelas
				WHERE id = '$id_group_kelas'
			");

			$group_kelas 	 = mysqli_fetch_array($findIdGroupKelasForOut)['nama_group_kelas'];

			$queryUpdateGroupSiswa = mysqli_query($con, "
				UPDATE siswa 
				SET 
				group_kelas = NULL
				WHERE nis = '$nis_siswa'
			");

			if ($queryUpdateGroupSiswa) {
				$g_kelas = $group_kelas;
				$_SESSION['update_group_siswa'] = 'berhasil';

				$findIdGroupKelas = mysqli_query($con, "
		        SELECT id FROM group_kelas
		        WHERE nama_group_kelas = '$_GET[q]'
		      ");

		      $getidGroupKelas = mysqli_fetch_array($findIdGroupKelas)['id'];

				$queryGetAllDataStudentByGroup = mysqli_query($con, "

					SELECT * FROM siswa
					WHERE group_kelas = '$getidGroupKelas'
					GROUP BY nis ASC

				");

			} else {

				$g_kelas = $group_kelas;
				$_SESSION['update_group_siswa'] = 'gagal';

				$queryGetAllDataStudentByGroup = mysqli_query($con, "

					SELECT * FROM siswa
					WHERE group_kelas = '$_GET[q]'
					GROUP BY nis ASC

				");

			}


		}	

		// $countDt = mysqli_num_rows($queryGetAllDataStudentByGroup);
		// echo $countDt;exit;

  	}


?>

<style type="text/css">
	
	#lbl_walas {
		width: 5%;
		margin-top: 6px;
	}

	#div_btn {
		display: flex;
		justify-content: flex-end;
		gap: 10px;
	}

	@media (max-width: 992px) {
		#div_btn {
			display: flex;
			flex-direction: column;
		}
	}

</style>

<?php if(isset($_SESSION['update_group_siswa']) && $_SESSION['update_group_siswa'] == 'berhasil'){?>
  <div style="display: none;" class="alert alert-warning alert-dismissable"> <span style="color: white;"> BERHASIL UPDATE DATA GROUP KELAS <?= $g_kelas; ?> </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['update_group_siswa']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['update_group_siswa']) && $_SESSION['update_group_siswa'] == 'gagal'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: white;"> GAGAL UPDATE DATA GROUP KELAS <?= $g_kelas; ?> </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['update_group_siswa']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['update_data']) && $_SESSION['update_data'] == 'empty_number'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> NO HP / WA TIDAK BOLEH KOSONG ! </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['update_data']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['update_nama_siswa']) && $_SESSION['update_nama_siswa'] == 'berhasil'){?>
  <div style="display: none;" class="alert alert-warning alert-dismissable"> <span style="color: white;"> BERHASIL UPDATE DATA SISWA </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['update_nama_siswa']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['update_nama_siswa']) && $_SESSION['update_nama_siswa'] == 'gagal_akses_otm'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> GAGAL UPDATE NO HP OTM </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['update_nama_siswa']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['update_nama_siswa']) && $_SESSION['update_nama_siswa'] == 'gagal'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: white;"> GAGAL UPDATE DATA SISWA </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['update_nama_siswa']);
    ?>
  </div>
<?php } ?>

<div class="box box-info">

	<br>
	<div class="form-group">
		<label class="col-md-1 control-label" id="lbl_walas"> WALAS </label>
		<div class="col-md-3">
			<input type="" value="<?= str_replace([" S.Pd.I", "  S.Pd", ",", "Lc.", " S.Sos", "  S.Ag", "  A.Md", "  S.Si", "  M.Pd", "  S.Psi."], "", $getDataWalas); ?>" name="" readonly class="form-control">
		</div>
	</div>

	<br>

  	<center> 
	    <h4 id="judul_daily">
	      <strong> LIST STUDENT CLASS <?= $_GET['q'] ?> </strong> 
	    </h4> 
  	</center>

  	<div class="box-body table-responsive">

  		<div class="form-group" id="div_btn">
	    	<a href="<?= $basead; ?>addtogroupclass/<?= $_GET['q'] ?>" class="btn btn-sm btn-success"> TAMBAH ANGGOTA </a>
  		</div>

	    <br><br>

	    <table id="hightlight_list_siswa" style="text-align: center;" class="table table-bordered table-hover">

	      	<thead>
		        <tr style="background-color: lightyellow; font-weight: bold;">
		          <th style="text-align: center; border: 1px solid black;" width="5%">NO</th>
		          <th style="text-align: center; border: 1px solid black;"> NIS </th>
		          <th style="text-align: center; border: 1px solid black;"> NAMA </th>
		          <th style="text-align: center; border: 1px solid black;"> ACTION </th>
		          <!-- <th style="text-align: center;"> DAILY </th> -->
		          <!-- Terdapat Administrasi Pembiayaan Yang Perlu Di Selesaikan -->
		        </tr>
	      	</thead>

	      	<tbody>
	        
	      		<?php foreach ($queryGetAllDataStudentByGroup as $data): ?>
	      			
	      			<tr>
	      				<td> <?= $no++; ?> </td>
	      				<td> <?= $data['nis']; ?> </td>
	      				<td> <?= strtoupper($data['nama']); ?> </td>
	      				<td> 
	      					<button class="btn btn-sm btn-primary" onclick="editModal(`<?= $data['nama']; ?>`, `<?= $data['nis']; ?>`, `<?= $data['hp']; ?>`)"> <i class="glyphicon glyphicon-pencil"></i> EDIT </button> |
	      					<button class="btn btn-sm btn-danger" data-target="#hapus<?= $data['nis']; ?>" data-toggle="modal"> <i class="glyphicon glyphicon-ban-circle"></i> KELUARKAN SISWA DARI GROUP KELAS </button>
	      				</td>
	      			</tr>

	      			<div id="hapus<?php echo $data['nis']; ?>" class="modal" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			            <div class="modal-dialog">
			              	<div class="modal-content">
				                <div class="modal-header bg-red">
				                  	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				                  	<h4 class="modal-title" id="myModalLabel"> <i class="glyphicon glyphicon-trash"></i> Konfirmasi Mengeluarkan Siswa Dari Group Kelas </h4>
				                </div>
				                <div class="modal-body">
				                  	<h4> <b> Anda Yakin Ingin Mengeluarkan Siswa <?= strtoupper($data['nama']); ?> dari Group Kelas <?= $_GET['q']; ?> ? </b> </h4>
				                </div>
			                	<div class="modal-footer">
				                	<form method="POST" action="#">
				                    	<input type="hidden" name="nis_siswa" value="<?= $data['nis']; ?>">
				                    	<button class="btn btn-success" type="submit" name="out_group"> <i class="glyphicon glyphicon-ok"></i> Iya, Yakin </a> </button>
				                  	</form>
		                  			<button class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Batal </button>
			                	</div>
			              	</div>
			            </div>
		          	</div>

	      		<?php endforeach ?>

	      	</tbody>

	    </table>

	    <br>
	    <center> <a href="<?= $basead; ?>listgroupclass" class="btn btn-sm btn-primary"> <i class="glyphicon glyphicon-arrow-left"></i> Kembali </a> </center>
	    <br>

  	</div>

</div>

<div id="modalsiswa" class="modal" data-bs-backdrop="static" data-bs-keyboard="false">
  	<div class="modal-dialog modal-md">
	    <div class="modal-content">
	      	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> × </button>
		        <h4 class="modal-title" id="myModalLabel"> <i class="glyphicon glyphicon-list-alt"></i> Data Siswa </h4>
	      	</div>

	      	<form method="post">
		        <div class="modal-body">
		        	<div class="row">
		        		<div class="col-sm-4">
		          		<label> NIS </label>
		        		</div>
		          	<div class="col-sm-4" style="top: -4px;">
		          		<input type="text" readonly name="nis" id="nis" class="form-control" value="ajdnajsdn">
		          	</div>
		        	</div>
		          <br>
		          <div class="row">
		            <div class="col-sm-4">
		              <label> NAMA </label>
		            </div>
		            <div class="col-sm-8" style="top: -4px;">
		              <input type="text" name="nama_siswa" id="nama_siswa" class="form-control" value="ajdnajsdn">
		            </div>
		          </div>
		          <br>
		          <div class="row">
		            <div class="col-sm-4">
		              <label> NO HP/WA ORANG TUA </label>
		            </div>
		            <div class="col-sm-4" style="top: -4px;">
		              <input type="text" required pattern="[0-9]*" inputmode="numeric" onkeypress="return onlyNumberKey(event)" value="" maxlength="13" class="form-control" name="nohpotm" id="nohpotm">
		            </div>
		          </div>
		        </div>
		        <div class="modal-footer">
		          	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal()">Close</button>
		          	<button type="submit" name="update_siswa" class="btn btn-success">Save changes</button>
		        </div>
	      	</form>

	    </div>
  	</div>    
</div>

<script type="text/javascript">

	let noParam = `<?= $isNoParam; ?>`
	
	$(document).ready(function() {

		if (noParam == 1) {
			document.location.href = `<?= $basead; ?>listgroupclass`
		}

		let newIcon = document.getElementById("addIcon");
	    newIcon.classList.remove("fa");
	    newIcon.classList.add("glyphicon");
	    newIcon.classList.add("glyphicon-list-alt");

	    let getTitleList2 = document.getElementById('g_kls').innerHTML;

	    $("#isiMenu").css({
	      "margin-left" : "5px",
	      "font-weight" : "bold",
	      "text-transform": "uppercase"
	    });

	    document.getElementById('isiMenu').innerHTML = 'GROUP CLASS ' + `<?= $_GET['q']; ?>`

		$("#list_student").click();

	    $("#listgroupclass").css({
	      "background-color" 	: "#ccc",
	      "color" 				: "black"
	    });

	});

	function editModal(nama, nis, nohpotm) {

    	$("#nama_siswa").val(nama);
    	$("#nis").val(nis);
    	$("#nohpotm").val(nohpotm);
    	$('#modalsiswa').modal("show");

  	}

  	function closeModal() {
    	$("#modalsiswa").modal('hide');
  	}

  	function onlyNumberKey(evt) {

     // Only ASCII character in that range allowed
     let ASCIICode = (evt.which) ? evt.which : evt.keyCode
     if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
         return false;
     return true;
 	}

</script>