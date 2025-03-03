<?php  

	$timeOut        = $_SESSION['expire'];
    
	$timeRunningOut = time() + 5;

	$timeIsOut      = 0;
	$no             = 1;
	$no_agg 				= 1;

	$dataNama 			= [];

	$queryAllStudent = mysqli_query($con, 
	    "SELECT * FROM siswa WHERE c_kelas <> 'TKBLULUS' order by c_kelas asc"
  	);

 	if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

	    $_SESSION['form_success'] = "session_time_out";

	    $timeIsOut = 1;
	    error_reporting(1);

  	} else {

  		$queryListGroupClass = mysqli_query($con, "
	      SELECT *
	      FROM
	      group_kelas
	      ORDER BY 
	      nama_group_kelas ASC
	    ");

	    if (isset($_POST['simpan_ck'])) {
	    	echo $_POST['ini_ck'];
	    }

	    if (isset($_POST['sv_add_std'])) {

	    	$dataNama[] = $_POST['states'];

	    	var_dump($dataNama);exit;

	    }

  	}

?>

<style type="text/css">
	.select2-selection__choice__display {
		color: black;
	}
</style>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<?php if(isset($_SESSION['updt_group_kelas']) && $_SESSION['updt_group_kelas'] == 'berhasil'){?>
  <div style="display: none;" class="alert alert-warning alert-dismissable"> <span style="color: white;"> GROUP KELAS BERHASIL DI UPDATE </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['updt_group_kelas']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['updt_group_kelas']) && $_SESSION['updt_group_kelas'] == 'hps_berhasil'){?>
  <div style="display: none;" class="alert alert-warning alert-dismissable"> <span style="color: white;"> GROUP KELAS BERHASIL DI HAPUS </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['updt_group_kelas']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['updt_group_kelas']) && $_SESSION['updt_group_kelas'] == 'gagal'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> GROUP KELAS GAGAL DI UPDATE ! </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['updt_group_kelas']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['updt_group_kelas']) && $_SESSION['updt_group_kelas'] == 'hps_gagal'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> GROUP KELAS GAGAL DI HAPUS ! </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['updt_group_kelas']);
    ?>
  </div>
<?php } ?>

<div class="box box-info">

  	<form action="<?= $basead; ?>createinfo" method="post">

      	<div class="box-body">
	        <div class="row">

	            <div class="col-sm-2">  
	                <div class="form-group">
	                    <label style="color: white;">NIS</label>
	                    <a onclick="openModalSiswa()" class="form-control btn btn-primary"> <i class="glyphicon glyphicon-search"></i> Cari Siswa </a>
	                </div>
	            </div>

	            <div class="col-sm-2">
	                <div class="form-group">
	                  <label>NIS<sup style="color: red; font-size: 10px;">*</sup></label>
	                  <input type="text" readonly required id="dataNis" name="nis_siswa" class="form-control">
	                </div>
	            </div>

	            <div class="col-sm-6">
	                <div class="form-group">
	                    <label>NAMA LENGKAP<sup style="color: red; font-size: 10px;">*</sup></label>
	                    <input type="text" readonly required id="dataNama" name="nama_siswa" class="form-control">
	                </div>
	            </div>

	            <div class="col-sm-2">
	                <div class="form-group">
	                    <label> KELAS<sup style="color: red; font-size: 10px;">*</sup></label>
	                    <input type="text" class="form-control" id="dataKelas" name="jenjang_pendidikan" readonly>
	                </div>
	            </div>

	        </div>

        	<hr class="new1">

	        <div class="row">
	            
	            <div class="col-sm-4">
	                <div class="form-group">
	                  <label>LIST GROUP KELAS<sup style="color: red; font-size: 10px;">*</sup></label>
	                    <select class="form-control" name="jns_pmb">
	                    	<option value=""> -- PILIH -- </option>
	                        <?php foreach ($queryListGroupClass as $groupKelas): ?>
	                            <option value="<?= $groupKelas['nama_group_kelas']; ?>"> <?= $groupKelas['nama_group_kelas']; ?> - <?= $groupKelas['walas']; ?> </option>
	                        <?php endforeach ?>
	                    </select>
	                </div>
	            </div>

	        </div>

	        <div class="row">
	                
	            <div class="col-sm-5">
	                <div class="form-group">
	                    <button type="submit" name="kirim_ann" class="btn btn-success btn-sm"> SEND </button>
	                </div>
	            </div>

	        </div>

      	<div>

    </form>

</div>

<div id="modalsiswa" class="modal"  data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> × </button>
        <h4 class="modal-title" id="myModalLabel"> <i class="glyphicon glyphicon-list-alt"></i> Data Siswa </h4>
      </div>
      <form method="post">
	      <div class="modal-body">
	      	<div class="row">
	      		<div class="col-sm-11">
			        <select class="js-example-basic-multiple" name="states[]" multiple="multiple">
							  <option value="GATHAN">GATHAN</option>
							  <option value="PASYA">PASYA</option>
							  <option value="RIDWAN">RIDWAN</option>
							  <option value="NANDA">NANDA</option>
							</select>
	      		</div>
	      	</div>
	      </div>
	      <div class="modal-footer">
	        <button type="submit" name="sv_add_std" class="btn btn-primary">Save changes</button>
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	    </form>
    </div>
  </div>    
</div>

<script type="text/javascript">
	
	$(document).ready(function() {

	 	$('.js-example-basic-multiple').select2();

		let newIcon = document.getElementById("addIcon");
    newIcon.classList.remove("fa");
    newIcon.classList.add("glyphicon");
    newIcon.classList.add("glyphicon-plus");
    newIcon.classList.add("addStyle");

    let getTitleList2 = document.getElementById('j_gk').innerHTML;

    document.getElementById('isiMenu').innerHTML = getTitleList2

    $("#nama_group").focus();

    $("#list_student").click();

    $("#joinwithgroup").css({
      "background-color"  : "#ccc",
      "color"             : "black"
    });

    $(".addStyle").css({
      "top" : "2px"
    });

    $("#isiMenu").css({
      "margin-left" : "5px",
      "font-weight" : "bold",
      "text-transform": "uppercase"
    });

	});

	function openModalSiswa(){
    $('#modalsiswa').modal("show");
	}

	function siswaSelected(nama, nis, c_siswa, c_kelas) {

    $('#dataNis').val(nis);
    $('#dataNama').val(nama);
    $('#dataKelas').val(c_kelas);
    $("#btnSimpan").show();
    $('#modalsiswa').modal("hide");

  }

</script>

