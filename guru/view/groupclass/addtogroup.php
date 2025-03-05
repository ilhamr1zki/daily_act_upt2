<?php 

	$timeOut        = $_SESSION['expire_paud'];
    
	$timeRunningOut = time() + 5;

	$timeIsOut      = 0;
	$isNoParam 		= 0;
	$no 			= 0;

	error_reporting(1);

	if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

    $_SESSION['form_success'] = "session_time_out";

    $timeIsOut = 1;
    error_reporting(1);

	} else {

  	// echo $_GET['q'];
  	$queryGetWalas = mysqli_query($con, "
  		SELECT walas FROM group_kelas
  		WHERE nama_group_kelas = '$_GET[q]'
  	");

  	$queryGetAllDataSiswa = mysqli_query($con, "
  		SELECT nis, nama, c_kelas FROM siswa 
  		WHERE c_kelas <> 'TKBLULUS' AND group_kelas is NULL 
  		ORDER BY nama ASC
  	");

  	$getWalas = mysqli_fetch_array($queryGetWalas)['walas'];

	}

?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style type="text/css">
    
  .select2-selection {
    height: 200px;
  }

</style>

<div class="box box-info">
   
  <form action="<?= $basegu; ?>groupclass/<?= $_GET['q']; ?>" method="post">
    <div class="box-body">

      <div class="row">
          
        <div class="col-sm-3">
          <div class="form-group">
            <label> WALAS </label>
            <input type="hidden" name="nm_group_kelas" value="<?= $_GET['q']; ?>">
            <input type="text" readonly name="walas" value="<?= str_replace([" S.Pd.I", "  S.Pd", ",", "Lc.", " S.Sos", "  S.Ag", "  A.Md", "  S.Si", "  M.Pd", "  S.Psi."], "", $getWalas); ?>" required id="nama_group" class="form-control">
          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <label> TAMBAH ANGGOTA </label>
            <select class="js-example-basic-multiple js-states form-control" name="states[]" placeholder="dsdas" multiple="multiple" style="height: 500px;">
                  <option value=""> -- PILIH -- </option>
                  <?php foreach ($queryGetAllDataSiswa as $data): ?>
                  	<option value="<?= $data['nis']; ?>"> <?= $data['nis']; ?> - <?= $data['nama']; ?> - <?= str_replace(["SD"], " SD", $data['c_kelas']); ?> </option>
                  <?php endforeach ?>
                </select>
          </div>
        </div>

      </div>

      <div class="row">
              
          <div class="col-sm-5">
              <div class="form-group" style="display: flex; gap: 10px;">
                  <a href="<?= $basegu; ?>groupclass/<?= $_GET['q']; ?>" class="btn btn-primary btn-sm"> <i class="glyphicon glyphicon-chevron-left"></i> KEMBALI </a>
                  <button type="submit" name="sv_add_std" class="btn btn-success btn-sm"> <i class="glyphicon glyphicon-floppy-saved"></i> SAVE </button>
              </div>
          </div>

      </div>

    </div>

  </form>

</div>

<script language="javascript" type="text/javascript">

  $(document).ready(function() {

  	$('.js-example-basic-multiple').select2({
        placeholder: "-- PILIH SISWA --"
    });

    let newIcon = document.getElementById("addIcon");
    newIcon.classList.remove("fa");
    newIcon.classList.add("glyphicon");
    newIcon.classList.add("glyphicon-list-alt");
    newIcon.classList.add("addStyle");

    let getTitleList2 = document.getElementById('g_kls').innerHTML;

    document.getElementById('isiMenu').innerHTML = `ADD TO GROUP CLASS <?= $_GET['q']; ?>`

    $("#nama_group").focus();

    $("#isgroupclass").click();

    $("#is_groupclass").css({
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

  function OpenCarisiswaModal(){
    $('#datamassiswa').modal("show");
  }

</script>


