<?php  

	$timeOut        = $_SESSION['expire_paud'];
    
	$timeRunningOut = time() + 5;

	$timeIsOut      = 0;
  $no             = 1;
  $no_agg         = 1;
  $jumlah_data    = 0;
  $delSuccess     = 0;

  $spd  = "S.PD";
  $ssos = "S.SOS";
  $sag  = "S.AG";
  $lc   = "LC";
  $amd  = "A.MD";
  $ssi  = "S.SI";
  $mpd  = "M.PD";

  $tampungData      = [];
  $tampungDataTrue  = [];

  $symbol = ",";

  $queryAllStudent = mysqli_query($con, 
    "SELECT * FROM siswa WHERE c_kelas <> 'TKBLULUS' AND group_kelas <> NULL order by group_kelas asc"
  );

  if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

    $_SESSION['form_success'] = "session_time_out";

    $timeIsOut = 1;
    error_reporting(1);

  } else {

    $queryListTeacher = mysqli_query($con, "
      SELECT *
      FROM
      guru
      ORDER BY 
      nip ASC
    ");

    $queryAllDataStudents = mysqli_query($con, "
      SELECT 
      siswa.nis as nis,
      siswa.nama as nama,
      siswa.c_kelas as c_kelas,
      siswa.group_kelas as group_kelas,
      group_kelas.nama_group_kelas as nama_group
      FROM
      siswa
      LEFT JOIN group_kelas
      ON siswa.group_kelas = group_kelas.id
      WHERE c_kelas <> 'TKBLULUS'
      ORDER BY nama ASC
    ");

    if (isset($_POST['save_data'])) {

      $idGroupKelas = htmlspecialchars($_POST['id_group']);
      $groupKelas   = mysqli_real_escape_string($con, htmlspecialchars($_POST['nama_group']));
      $waliKelas    = mysqli_real_escape_string($con, htmlspecialchars($_POST['walas']));

      $ambilGelar   = substr($waliKelas, -3);
      $ambilGelar2  = substr($waliKelas, -2);
      // echo $ambilGelar2;exit;

      if ($ambilGelar == "LC.") {

        $waliKelas    = str_replace(["LC."], "Lc.", $waliKelas); 

        $execUpdateQuery = mysqli_query($con, "
          UPDATE group_kelas 
          SET
          nama_group_kelas  = '$groupKelas',
          walas             = '$waliKelas'
          WHERE 
          id                = '$idGroupKelas'
        ");

        if ($execUpdateQuery == true) {

          $_SESSION['updt_group_kelas'] = "berhasil";

        } else {

          $_SESSION['updt_group_kelas'] = "gagal";

        }

      } else if ($ambilGelar == "lc.") {

        $waliKelas    = str_replace(["lc."], "Lc.", $waliKelas); 

        $execUpdateQuery = mysqli_query($con, "
          UPDATE group_kelas 
          SET
          nama_group_kelas  = '$groupKelas',
          walas             = '$waliKelas'
          WHERE 
          id                = '$idGroupKelas'
        ");

        if ($execUpdateQuery == true) {

          $_SESSION['updt_group_kelas'] = "berhasil";

        } else {

          $_SESSION['updt_group_kelas'] = "gagal";

        }

      } else if ($ambilGelar == "Lc.") {

        $waliKelas    = str_replace(["Lc."], "Lc.", $waliKelas); 

        $execUpdateQuery = mysqli_query($con, "
          UPDATE group_kelas 
          SET
          nama_group_kelas  = '$groupKelas',
          walas             = '$waliKelas'
          WHERE 
          id                = '$idGroupKelas'
        ");

        if ($execUpdateQuery == true) {

          $_SESSION['updt_group_kelas'] = "berhasil";

        } else {

          $_SESSION['updt_group_kelas'] = "gagal";

        }

      } else if ($ambilGelar2 == "LC") {

        $waliKelas    = str_replace(["lc", "LC", "Lc"], "Lc.", $waliKelas); 
        $waliKelas    = str_replace([".."], ".", $waliKelas);

        $execUpdateQuery = mysqli_query($con, "
          UPDATE group_kelas 
          SET
          nama_group_kelas  = '$groupKelas',
          walas             = '$waliKelas'
          WHERE 
          id                = '$idGroupKelas'
        ");

        if ($execUpdateQuery == true) {

          $_SESSION['updt_group_kelas'] = "berhasil";

        } else {

          $_SESSION['updt_group_kelas'] = "gagal";

        }

      } else if ($ambilGelar2 == "Lc") {

        $waliKelas    = str_replace(["lc", "LC", "Lc"], "Lc.", $waliKelas); 
        $waliKelas    = str_replace([".."], ".", $waliKelas);

        $execUpdateQuery = mysqli_query($con, "
          UPDATE group_kelas 
          SET
          nama_group_kelas  = '$groupKelas',
          walas             = '$waliKelas'
          WHERE 
          id                = '$idGroupKelas'
        ");

        if ($execUpdateQuery == true) {

          $_SESSION['updt_group_kelas'] = "berhasil";

        } else {

          $_SESSION['updt_group_kelas'] = "gagal";

        }

      } else if ($ambilGelar2 == "lc") {

        $waliKelas    = str_replace(["lc", "LC", "Lc"], "Lc.", $waliKelas); 
        $waliKelas    = str_replace([".."], ".", $waliKelas);

        $execUpdateQuery = mysqli_query($con, "
          UPDATE group_kelas 
          SET
          nama_group_kelas  = '$groupKelas',
          walas             = '$waliKelas'
          WHERE 
          id                = '$idGroupKelas'
        ");

        if ($execUpdateQuery == true) {

          $_SESSION['updt_group_kelas'] = "berhasil";

        } else {

          $_SESSION['updt_group_kelas'] = "gagal";

        }

      } else {

        if(preg_match("/$symbol/i", $waliKelas)) {

          $start  = strpos($waliKelas, ','); 
          $arr    = explode(',', $waliKelas); 

          $waliKelas  = strtoupper($arr[0]) . ", " . $arr[1]; 

          $execUpdateQuery = mysqli_query($con, "
            UPDATE group_kelas 
            SET
            nama_group_kelas  = '$groupKelas',
            walas             = '$waliKelas'
            WHERE id = '$idGroupKelas'
          ");

          if ($execUpdateQuery == true) {

            $_SESSION['updt_group_kelas'] = "berhasil";
            $queryListGroupClass = mysqli_query($con, "
              SELECT *
              FROM
              group_kelas
              ORDER BY 
              nama_group_kelas ASC
            ");

          } else {

            $_SESSION['updt_group_kelas'] = "gagal";

          }

        } else {

          $waliKelas = mysqli_real_escape_string($con, strtoupper(htmlspecialchars($_POST['walas'])));

          $execUpdateQuery = mysqli_query($con, "
            UPDATE group_kelas 
            SET
            nama_group_kelas  = '$groupKelas',
            walas             = '$waliKelas'
            WHERE id = '$idGroupKelas'
          ");

          if ($execUpdateQuery == true) {

            $_SESSION['updt_group_kelas'] = "berhasil";
            $queryListGroupClass = mysqli_query($con, "
              SELECT *
              FROM
              group_kelas
              ORDER BY 
              nama_group_kelas ASC
            ");

          } else {

            $_SESSION['updt_group_kelas'] = "gagal";

          }

        }

      }

    } else if (isset($_POST['delete_std'])) {

      $nis_std = htmlspecialchars($_POST['nis_std']);

      // cari NIS
      $queryCariNis = mysqli_query($con, "
        SELECT nis, nama FROM siswa
        WHERE nis = '$nis_std'
      ");

      $countNis_Std = mysqli_num_rows($queryCariNis);
      
      if ($countNis_Std == 1) {

        // Get Nama
        $getNamaSiswa = mysqli_fetch_array($queryCariNis)['nama'];

        $queryExecDel = mysqli_query($con, "
          DELETE FROM siswa WHERE nis = '$nis_std' 
        ");

        $queryDelAksesOTM = mysqli_query($con, "
          DELETE FROM akses_otm WHERE nis_siswa = '$nis_std'
        ");

        if ($queryExecDel && $queryDelAksesOTM) {

          $queryAllDataStudents = mysqli_query($con, "
            SELECT 
            siswa.nis as nis,
            siswa.nama as nama,
            siswa.c_kelas as c_kelas,
            siswa.group_kelas as group_kelas,
            group_kelas.nama_group_kelas as nama_group
            FROM
            siswa
            LEFT JOIN group_kelas
            ON siswa.group_kelas = group_kelas.id
            WHERE c_kelas <> 'TKBLULUS'
            ORDER BY nama ASC
          ");

          $_SESSION['data_siswa_hapus'] = "berhasil";
          $siswaHapus = $getNamaSiswa;

        } else {

          $_SESSION['data_siswa_hapus'] = "gagal";

        } 

      }

    } 

    if (isset($_POST['update_siswa'])) {

      $nama     = mysqli_real_escape_string($con, strtoupper(htmlspecialchars($_POST['nama_siswa'])));
      $nis      = htmlspecialchars($_POST['nis']);

      $queryUpdateSiswa = mysqli_query($con, "
        UPDATE siswa 
        SET
        nama        = '$nama'
        WHERE nis   = '$nis'
      ");

      if ($queryUpdateSiswa) {

        $_SESSION['update_data'] = "berhasil";

        $queryAllDataStudents = mysqli_query($con, "
          SELECT 
          siswa.nis as nis,
          siswa.nama as nama,
          siswa.c_kelas as c_kelas,
          siswa.group_kelas as group_kelas,
          group_kelas.nama_group_kelas as nama_group
          FROM
          siswa
          LEFT JOIN group_kelas
          ON siswa.group_kelas = group_kelas.id
          WHERE c_kelas <> 'TKBLULUS'
          ORDER BY nama ASC
        ");

      } else {

        $_SESSION['update_data'] = "gagal";

        $queryAllDataStudents = mysqli_query($con, "
          SELECT 
          siswa.nis as nis,
          siswa.nama as nama,
          siswa.c_kelas as c_kelas,
          siswa.group_kelas as group_kelas,
          group_kelas.nama_group_kelas as nama_group
          FROM
          siswa
          LEFT JOIN group_kelas
          ON siswa.group_kelas = group_kelas.id
          WHERE c_kelas <> 'TKBLULUS'
          ORDER BY nama ASC
        ");

      }

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

<?php if(isset($_SESSION['update_data']) && $_SESSION['update_data'] == 'berhasil'){?>
  <div style="display: none;" class="alert alert-warning alert-dismissable"> <span style="color: white;"> BERHASIL UPDATE DATA SISWA </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['update_data']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['data_siswa_hapus']) && $_SESSION['data_siswa_hapus'] == 'berhasil'){?>
  <div style="display: none;" class="alert alert-warning alert-dismissable"> <span style="color: white;"> DATA SISWA <?= strtoupper($siswaHapus); ?> BERHASIL DI HAPUS ! </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['data_siswa_hapus']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['data_siswa_hapus']) && $_SESSION['data_siswa_hapus'] == 'gagal'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> DATA SISWA GAGAL DI HAPUS ! </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['data_siswa_hapus']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['update_data']) && $_SESSION['update_data'] == 'gagal'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> DATA SISWA GAGAL DI UPDATE ! </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['update_data']);
    ?>
  </div>
<?php } ?>

<div class="box box-info">

  <center> 
    <h4 id="judul_daily">
      <strong> LIST ALL STUDENTS </strong> 
    </h4> 
  </center>

  <div class="box-body table-responsive">
	
	<a href="<?= $basead; ?>addstudent" class="btn btn-sm btn-warning"> TAMBAH SISWA </a>
	<br><br>

    <table id="hightlight_list_siswa" style="text-align: center;" class="table table-bordered table-hover">

      <thead>
        <tr style="background-color: lightyellow; font-weight: bold;">
          <th style="text-align: center; border: 1px solid black;" width="5%">NO</th>
          <th style="text-align: center; border: 1px solid black;"> NIS </th>
          <th style="text-align: center; border: 1px solid black;"> NAMA </th>
          <th style="text-align: center; border: 1px solid black;"> KELAS </th>
          <th style="text-align: center; border: 1px solid black;"> GROUP KELAS </th>
          <th style="text-align: center; border: 1px solid black;"> ACTION </th>
          <!-- <th style="text-align: center;"> DAILY </th> -->
          <!-- Terdapat Administrasi Pembiayaan Yang Perlu Di Selesaikan -->
        </tr>
      </thead>

      <tbody>

        <?php foreach ($queryAllDataStudents as $data): ?>
          
          <tr>
            <td style="text-align: center;"> <?= $no++; ?> </td>
            <td style="text-align: center;"> <?= $data['nis']; ?> </td>
            <td style="text-align: center;"> <?= strtoupper($data['nama']); ?> </td>
            <td style="text-align: center;"> <?= str_replace(["SD"], " SD", $data['c_kelas']); ?> </td>
            <td style="text-align: center;"> <?= $data['nama_group']; ?> </td>

            <td style="text-align: center;"> 

              <button class="btn btn-sm btn-primary" onclick="editModal(
                `<?= $data['nis']; ?>`, 
                `<?= $data['nama']; ?>`, 
                `<?= $data['c_kelas']; ?>`,
                `<?= $data['nama_group']; ?>`
              )"> EDIT </button> | 
              <button class="btn btn-sm btn-danger" data-target="#hapus<?= $data['nis']; ?>" data-toggle="modal"> DELETE </button>

            </td>
          </tr>

          <div id="hapus<?= $data['nis']; ?>" class="modal" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-red">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title" id="myModalLabel"> <i class="glyphicon glyphicon-trash"></i> Konfirmasi Hapus Data Siswa </h4>
                </div>
                <div class="modal-body">
                  <h4> <b> Anda Yakin Ingin Menghapus Data Siswa <?= strtoupper($data['nama']); ?> ? </b> </h4>
                  <p>Jika Anda Menghapus Data Ini, Akan Berpengaruh Pada : </p>
                  <ul>
                    <li><b> Data Group Kelas Terhapus </b></li>
                  </ul>
                </div>
                <div class="modal-footer">
                  <form method="POST">
                    <input type="hidden" name="nis_std" value="<?= $data['nis']; ?>">
                    <button class="btn btn-success" type="submit" name="delete_std"> <i class="glyphicon glyphicon-ok"></i> Lanjutkan</a> </button>
                  </form>
                  <button class="btn btn-primary" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Tutup</button>
                </div>
              </div>
            </div>
          </div>

        <?php endforeach ?>
        
      </tbody>

    </table>
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
        		<div class="col-sm-2">
          		<label> NIS </label>
        		</div>
          	<div class="col-sm-4" style="top: -4px;">
          		<input type="text" readonly name="nis" id="nis" class="form-control" value="">
          	</div>
        	</div>
          <br>
          <div class="row">
            <div class="col-sm-2">
              <label> NAMA </label>
            </div>
            <div class="col-sm-10" style="top: -4px;">
              <input type="text" name="nama_siswa" id="nama_siswa" class="form-control" value="ajdnajsdn">
            </div>
          </div>
          <br>
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

	$(document).ready( function () {

    $('.js-example-basic-multiple').select2({
        placeholder: "-- PILIH SISWA --"
    });
		
    let newIcon = document.getElementById("addIcon");
    newIcon.classList.remove("fa");
    newIcon.classList.add("fa-classic");
    newIcon.classList.add("fa-solid");
    newIcon.classList.add("fa-user-graduate");
    newIcon.classList.add("fa-fw");

    let getTitleList2 = document.getElementById('addst').innerHTML;

    $("#isiMenu").css({
      "margin-left" : "5px",
      "font-weight" : "bold",
      "text-transform": "uppercase"
    });

    document.getElementById('isiMenu').innerHTML = getTitleList2

	});
	
  function openModalSiswa(group_kelas, walas){
    $('#modalsiswa').modal("show");
    $("#namawalas").val(walas);
    $("#groupkelas").val(group_kelas);
    localStorage.setItem("groupkelas", group_kelas);
    localStorage.setItem("namawalas", walas); 
  }

  function openModalTambahSiswa(){
    $('#modaltambahsiswa').modal("show");
    $('#modalsiswa').modal("hide");
    $('#nama_group_kelas').val(localStorage.getItem('groupkelas'));
    $('#nm_walas').val(localStorage.getItem('namawalas'));
  }

  function editModal(nis, nama, c_kelas, g_kelas) {

    $("#nis").val(nis);
    $("#nama_siswa").val(nama);
    $("#c_kelas").val(c_kelas);
    $('#modalsiswa').modal("show");

  }

  function closeModal() {
    $("#modalsiswa").modal('hide');
  }


</script>