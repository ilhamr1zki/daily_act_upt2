<?php  

	$timeOut        = $_SESSION['expire_paud'];
    
	$timeRunningOut = time() + 5;

	$timeIsOut      = 0;
  $no             = 1;
  $no_agg         = 1;
  $jumlah_data    = 0;

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

    $queryListGroupClass = mysqli_query($con, "
      SELECT *
      FROM
      group_kelas
      ORDER BY 
      nama_group_kelas ASC
    ");

    $queryAllDataStudents = mysqli_query($con, "
      SELECT *
      FROM
      siswa
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

    } else if (isset($_POST['delete_group'])) {

      $idGroup = htmlspecialchars($_POST['id_groups']);

      // Find Id Group Kelas
      $queryFindGroupKelas = mysqli_query($con, "
        SELECT nama_group_kelas FROM group_kelas
        WHERE id = '$idGroup'
      ");

      $dataGroupKelas = mysqli_real_escape_string($con, mysqli_fetch_array($queryFindGroupKelas)['nama_group_kelas']);
      // echo $dataGroupKelas;exit;

      // Find Id By Nama Group 
      $findIdGroupKelasByNamaGroup = mysqli_query($con, "
        SELECT id FROM group_kelas
        WHERE nama_group_kelas = '$dataGroupKelas'
      ");

      $getIdForUptGroupkelas = mysqli_fetch_array($findIdGroupKelasByNamaGroup)['id'];

      $execDeleteQuery = mysqli_query($con, "
        DELETE FROM group_kelas WHERE id = '$idGroup' ; 
      ");

      if ($execDeleteQuery == true) {

        $queryUpdateGroupSiswa = mysqli_query($con, "
          UPDATE siswa 
          SET group_kelas = NULL
          WHERE group_kelas = '$getIdForUptGroupkelas'
        ");

        if ($queryUpdateGroupSiswa) {

          $_SESSION['updt_group_kelas'] = "hps_berhasil";
          
          $queryListGroupClass = mysqli_query($con, "
            SELECT *
            FROM
            group_kelas
            ORDER BY 
            nama_group_kelas ASC
          ");

        }

      } else {

        $_SESSION['updt_group_kelas'] = "hps_gagal";
        
        $queryListGroupClass = mysqli_query($con, "
          SELECT *
          FROM
          group_kelas
          ORDER BY 
          nama_group_kelas ASC
        ");

      }

    } 

    if (isset($_POST['sv_add_std'])) {
      $groupKls       = mysqli_real_escape_string($con, htmlspecialchars($_POST['nm_group_kelas']));
      // echo $groupKls;exit;

      // Cari ID group kelas
      $findIdGroupKelas = mysqli_query($con, "
        SELECT id FROM group_kelas
        WHERE nama_group_kelas = '$groupKls'
      ");

      $getidGroupKelas = mysqli_fetch_array($findIdGroupKelas)['id'];

      if (empty($_POST['states'])) {
        $_SESSION['upt_dt']       = "gagal";
      } else {
        // echo "Ada Isian Array";exit;
        $tampungData[]  = $_POST['states'];

        // var_dump($tampungData[0]);exit;

        for ($i=0; $i < count($tampungData[0]); $i++) { 

          $execUpt = mysqli_query($con, '
            UPDATE siswa
            SET 
            group_kelas = "'. $getidGroupKelas .'"'. '
            WHERE nis = "' . $tampungData[0][$i] . '"
          ');

          if ($execUpt) {
            $jumlah_data + 1;
          }

        }

        if ($jumlah_data <= count($tampungData[0])) {
          $jml_berhasil   = count($tampungData[0]);
          $nama_group     = $groupKls;
          $_SESSION['upt_dt']       = "berhasil";
        }

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

<?php if(isset($_SESSION['updt_group_kelas']) && $_SESSION['updt_group_kelas'] == 'berhasil'){?>
  <div style="display: none;" class="alert alert-warning alert-dismissable"> <span style="color: white;"> GROUP KELAS BERHASIL DI UPDATE </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['updt_group_kelas']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['upt_dt']) && $_SESSION['upt_dt'] == 'berhasil'){?>
  <div style="display: none;" class="alert alert-warning alert-dismissable"> <span style="color: white;"> <?= $jml_berhasil; ?> SISWA BERHASIL DI UPDATE KE DALAM GROUP KELAS <?= $nama_group; ?> </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['upt_dt']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['upt_dt']) && $_SESSION['upt_dt'] == 'gagal'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: white;"> GAGAL UPDATE SISWA KE DALAM GROUP KELAS </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['upt_dt']);
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

  <center> 
    <h4 id="judul_daily">
      <strong> LIST GROUP KELAS </strong> 
    </h4> 
  </center>

  <br>

  <div class="box-body table-responsive">
    <table id="hightlight_list_siswa" style="text-align: center;" class="table table-bordered table-hover">

      <thead>
        <tr style="background-color: lightyellow; font-weight: bold;">
          <th style="text-align: center; border: 1px solid black;" width="5%">NO</th>
          <th style="text-align: center; border: 1px solid black;"> GROUP KELAS </th>
          <th style="text-align: center; border: 1px solid black;"> WALI KELAS </th>
          <th style="text-align: center; border: 1px solid black;"> ACTION </th>
          <!-- <th style="text-align: center;"> DAILY </th> -->
          <!-- Terdapat Administrasi Pembiayaan Yang Perlu Di Selesaikan -->
        </tr>
      </thead>

      <tbody>

        <?php foreach ($queryListGroupClass as $groupKelas): ?>
          
          <tr>
            <td style="text-align: center;"> <?= $no++; ?> </td>
            <td style="text-align: center;"> <?= $groupKelas['nama_group_kelas']; ?> </td>
            <td style="text-align: center;"> <?= str_replace([" S.Pd.I", "S.Pd", ",", " S.Sos", " S.Ag", " Lc.", "  A.Md", " S.Si", " M.Pd", " S.Psi."], "", $groupKelas['walas']); ?> </td>

            <td style="text-align: center;"> 

              <a href="<?= $basead; ?>groupclass/<?= $groupKelas['nama_group_kelas']; ?>" class="btn btn-success btn-sm"> ANGGOTA </a> |
              <button class="btn btn-sm btn-danger" data-target="#hapus<?= $groupKelas['id']; ?>" data-toggle="modal"> DELETE </button>

            </td>
          </tr>

          <div id="hapus<?php echo $groupKelas['id']; ?>" class="modal" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-red">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title" id="myModalLabel"> <i class="glyphicon glyphicon-trash"></i> Konfirmasi Hapus Group Kelas </h4>
                </div>
                <div class="modal-body">
                  <h4> <b> Anda Yakin Ingin Menghapus Group Kelas <?= $groupKelas['nama_group_kelas']; ?> ? </b> </h4>
                  <p>Jika Anda Menghapus Data Ini, Akan Berpengaruh Pada : </p>
                  <ul>
                    <li><b> Data Siswa yang ada di Group Kelas <?= $groupKelas['nama_group_kelas']; ?> Terhapus </b></li>
                  </ul>
                </div>
                <div class="modal-footer">
                  <form action="<?= $basead; ?>listgroupclass" method="POST">
                  <input type="hidden" name="id_groups" value="<?= $groupKelas['id']; ?>">
                  <button class="btn btn-success" type="submit" name="delete_group"> <i class="glyphicon glyphicon-ok"></i> Lanjutkan</a> </button>
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

<div id="modaltambahsiswa" class="modal"  data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> × </button>
        <h4 class="modal-title" id="myModalLabel"> <i class="glyphicon glyphicon-list-alt"></i> Data Siswa </h4>
      </div>
      <form method="post">
        <div class="modal-body">
          <div class="row">
              
            <div class="col-sm-3">
              <div class="form-group">
                <label>GROUP KELAS</label>
                <input type="text" name="nm_group_kelas" id="nama_group_kelas" class="form-control" readonly>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="form-group">
                <label>NAMA WALAS</label>
                <input type="text" disabled name="nm_walas" id="nm_walas" class="form-control" readonly>
              </div>
            </div>

          </div>
          <br>
          <div class="row">
            <div class="col-sm-11">
              <div class="form-group">
                <label> TAMBAH ANGGOTA </label>
                <select class="js-example-basic-multiple js-states form-control" name="states[]" placeholder="dsdas" multiple="multiple">
                  <option value=""> -- PILIH -- </option>
                  <?php foreach ($queryAllDataStudents as $data): ?>
                    <option value="<?= $data['nis']; ?>"> <?= $data['nis']; ?> - <?= $data['nama']; ?> </option>
                  <?php endforeach ?>
                </select>
              </div>
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

<div id="modalsiswa" class="modal" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> × </button>
        <h4 class="modal-title" id="myModalLabel"> <i class="glyphicon glyphicon-list-alt"></i> Data Siswa </h4>
      </div>
      <div class="modal-body"> 
        <input type="hidden" id="groupkelas" name="">
        <input type="hidden" id="namawalas" name="">
        <button class="btn btn-sm btn-success" data-groupkelas="" data-namawalas="" style="margin-bottom: 10px;" onclick="openModalTambahSiswa()"> Tambah Siswa </button>
        
      </div>
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
    newIcon.classList.add("glyphicon");
    newIcon.classList.add("glyphicon-list-alt");

    let getTitleList2 = document.getElementById('g_kls').innerHTML;

    $("#isiMenu").css({
      "margin-left" : "5px",
      "font-weight" : "bold",
      "text-transform": "uppercase"
    });

    document.getElementById('isiMenu').innerHTML = getTitleList2

		$("#list_student").click();

    $("#listgroupclass").css({
      "background-color" 	: "#ccc",
      "color" 				: "black"
    });

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

  function editModal(id, groupKelas, walas) {

    $("#nama_group").val(groupKelas);
    $("#walas").val(walas.replace(",  ", ", "));
    $("#id_group").val(id);
    $('#modalsiswa').modal("show");

  }


</script>