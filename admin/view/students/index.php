<?php  

  $timeOut        = $_SESSION['expire_paud'];
    
  $timeRunningOut = time() + 5;

  $timeIsOut      = 0;
  $symbol         = ",";

  if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

    $_SESSION['form_success'] = "session_time_out";

    $timeIsOut = 1;
    error_reporting(1);

  } else {

    $queryGoupKelas = mysqli_query($con, "
      SELECT nama_group_kelas FROM group_kelas;
    ");

    if(isset($_POST['save_data']) ) {

      $namaGroup    = str_replace([" "], "-", htmlspecialchars($_POST['nama_group']));
      $nipWalas     = htmlspecialchars($_POST['nip_walas']);

      $queryFindNip = mysqli_query($con, "
        SELECT nip FROM guru
        WHERE nip = '$nipWalas'
      ");

      $queryFindWalas = mysqli_query($con, "
        SELECT nama FROM guru
        WHERE nip = '$nipWalas'
      ");

      $getNip     = mysqli_fetch_array($queryFindNip)['nip'];
      $getWalas   = mysqli_real_escape_string($con, htmlspecialchars(mysqli_fetch_array($queryFindWalas)['nama']));

      // echo $getNip . " & " . $getWalas;exit;
      $checkNipOnGroupKelas = mysqli_query($con, "
        SELECT nip FROM group_kelas
        WHERE nip = '$getNip'
      ");

      $countDataNipGroupKelas = mysqli_num_rows($checkNipOnGroupKelas);

      if ($countDataNipGroupKelas == 1) {

        $isWalas = $getWalas;
        $_SESSION['add_group'] = "no_empty";


      } else {
        
        $execSaveData = mysqli_query($con, "
          INSERT INTO
          group_kelas
          SET
          nama_group_kelas  = '$namaGroup',
          walas             = '$getWalas',
          nip               = '$getNip'
        ");

        if ($execSaveData == true) {
          $_SESSION['form_success'] = "berhasil";
          // echo "Nama Group Berhasil Di Tambahkan";exit;
        } else {

          $_SESSION['form_success'] = "gagal";
          
        }

      }


    }
  }

?>

<style type="text/css">

  #opt {
    cursor: pointer;
  }

</style>

<?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'berhasil'){?>
  <div style="display: none;" class="alert alert-warning alert-dismissable"> <span style="color: white;"> GROUP KELAS BERHASIL DI TAMBAH </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['form_success']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['add_group']) && $_SESSION['add_group'] == 'no_empty'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: white;"> GAGAL MENAMBAHKAN GROUP KELAS BARU, KARENA GURU <?= $isWalas; ?> SUDAH MENJADI WALAS </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['add_group']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'gagal'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> GROUP KELAS GAGAL DI TAMBAH </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['form_success']);
    ?>
  </div>
<?php } ?>

<div class="box box-info">
   
  <form action="<?= $basead; ?>creategroupstudents" method="post">
    <div class="box-body">

      <div class="row">
          
        <div class="col-sm-3">
          <div class="form-group">
            <label>NAMA GROUP KELAS<sup style="color: red; font-size: 10px;">*</sup></label>
            <input type="text" name="nama_group" placeholder="Tanpa Spasi" required id="nama_group" class="form-control">
          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group">
            <label> NAMA WALAS <sup style="color: red; font-size: 10px;">*</sup></label>
            <select class="form-control" name="nip_walas">
              <option> -- PILIH -- </option>

              <?php 

                $queryAllTeacher = mysqli_query($con, "
                  SELECT * FROM guru
                  ORDER BY nama ASC
                ");

              ?>
            
              <?php foreach ($queryAllTeacher as $data): ?>
                
                <option value="<?= $data['nip']; ?>"> <?= $data['nip']; ?> - <?= str_replace([" S.Pd", ","], "", $data['nama']); ?> </option>

              <?php endforeach ?>

            </select>
          </div>
        </div>

      </div>

      <div class="row">
              
          <div class="col-sm-5">
              <div class="form-group">
                  <button type="submit" id="btn_sv_group" name="save_data" class="btn btn-success btn-sm"> SAVE </button>
              </div>
          </div>

      </div>

    </div>

  </form>

</div>

<script language="javascript" type="text/javascript">

  $(document).ready(function() {

    let newIcon = document.getElementById("addIcon");
    newIcon.classList.remove("fa");
    newIcon.classList.add("glyphicon");
    newIcon.classList.add("glyphicon-plus");
    newIcon.classList.add("addStyle");

    let getTitleList2 = document.getElementById('b_gk').innerHTML;

    document.getElementById('isiMenu').innerHTML = getTitleList2

    $("#nama_group").focus();

    $("#list_student").click();

    $("#creategroupstudents").css({
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
