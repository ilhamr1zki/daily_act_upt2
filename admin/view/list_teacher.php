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

    } else if (isset($_POST['delete_teacher'])) {

      $nipGuru = htmlspecialchars($_POST['nipguru']);
      // echo $nipGuru;exit;

      // Check Apakah Data Nip Guru Ada sebagai walas
      $queryCheckDataNIP = mysqli_query($con, "
        SELECT nip FROM group_kelas 
        WHERE nip = '$nipGuru'
      ");

      $countDataNipOnGroupKelas = mysqli_num_rows($queryCheckDataNIP);
      // echo $countDataNipOnGroupKelas;exit;

      if ($countDataNipOnGroupKelas == 1) {

        // Jika ada data NIP Guru sebagai Walas, Maka reset kolom group_kelas berdasarkan Kelas Walas tersebut menjadi null

        $queryCheckDataGroup = mysqli_query($con, "
          SELECT nama_group_kelas FROM group_kelas 
          WHERE nip = '$nipGuru'
        ");

        $queryCheckDataWalas = mysqli_query($con, "
          SELECT walas FROM group_kelas 
          WHERE nip = '$nipGuru'
        ");

        $dataNip    = mysqli_fetch_array($queryCheckDataNIP)['nip'];
        $dataGroup  = mysqli_real_escape_string($con, mysqli_fetch_array($queryCheckDataGroup)['nama_group_kelas']);
        $dataWalas  = mysqli_fetch_array($queryCheckDataWalas)['walas'];

        // Find ID by name group
        $queryFindIdByNameGroup = mysqli_query($con, "
          SELECT id FROM group_kelas
          WHERE nama_group_kelas = '$dataGroup'
        ");

        $getIdNameGroups = mysqli_fetch_array($queryFindIdByNameGroup)['id'];
        // echo $getIdNameGroups;exit;

        $queryUpdateGroupSiswa = mysqli_query($con, "
          UPDATE siswa 
          SET
          group_kelas = NULL
          WHERE group_kelas = '$getIdNameGroups'
        ");

        if ($queryUpdateGroupSiswa) {

          $queryDelDataFromTblGroupKelas = mysqli_query($con, "
            DELETE FROM group_kelas 
            WHERE nip = '$nipGuru'
          ");

          if ($queryDelDataFromTblGroupKelas) {

            $queryDelDataFromTblGuru = mysqli_query($con, "
              DELETE FROM guru 
              WHERE nip = '$nipGuru'
            ");

            if ($queryDelDataFromTblGuru) {
              $walasDihapus = $dataWalas;
              $_SESSION['data_guru_hapus'] = "berhasil";

              $queryListTeacher = mysqli_query($con, "
                SELECT *
                FROM
                guru
                ORDER BY 
                nip ASC
              ");

            }

          }

        }

        // Jika Tidak Ada data Nip Di table Group Kelas
      } elseif($countDataNipOnGroupKelas == 0) {

        $queryCheckDataWalas = mysqli_query($con, "
          SELECT nama FROM guru 
          WHERE nip = '$nipGuru'
        ");

        $dataWalas  = mysqli_fetch_array($queryCheckDataWalas)['nama'];

        // Hapus Data Guru di table Guru
        $queryDelDataFromTblGuru = mysqli_query($con, "
          DELETE FROM guru 
          WHERE nip = '$nipGuru'
        ");

        if ($queryDelDataFromTblGuru) {
          $walasDihapus = $dataWalas;

          $queryListTeacher = mysqli_query($con, "
            SELECT *
            FROM
            guru
            ORDER BY 
            nip ASC
          ");

          $_SESSION['data_guru_hapus'] = "berhasil";
        }

      }

    } 

    if (isset($_POST['sv_add_std'])) {
      $groupKls       = htmlspecialchars($_POST['nm_group_kelas']);
      // echo $groupKls;exit;
      $tampungData[]  = $_POST['states'];
      // echo count($tampungData[0]);exit;
      // echo count($tampungData);exit;
      // echo count($tampungData[0]);exit;
      for ($i=0; $i < count($tampungData[0]); $i++) { 
        // echo "Index ke " . $i . " " . $tampungData[0][$i] . "<br>";

        $execUpt = true;

        // $execUpt = mysqli_query($con, '
        //   UPDATE siswa
        //   SET 
        //   group_kelas = "'. $groupKls .'"'. '
        //   WHERE nis = "' . $tampungData[0][$i] . '"
        // ');

        if ($execUpt) {
          $jumlah_data + 1;
        }

      }

      if ($jumlah_data <= count($tampungData[0])) {
        // echo "Berhasil Di Update " . count($tampungData[0]) . " Data ";
        $jml_berhasil   = count($tampungData[0]);
        $nama_group     = $groupKls;
        $_SESSION['upt_dt']       = "berhasil";
      }
      // $tesx = mysqli_query($con, "
      //   UPDATE siswa
      // ");
      // var_dump($tampungData[0][1]);
      // exit;
    }

    if (isset($_POST['update_guru'])) {

      $nama     = mysqli_real_escape_string($con, strtoupper(htmlspecialchars($_POST['nama_guru'])));
      $nip      = htmlspecialchars($_POST['nip']);
      $jabatan  = mysqli_real_escape_string($con, htmlspecialchars($_POST['jabatan']));
      $nohpwa   = htmlspecialchars($_POST['nohpwa']);

      $queryUpdateGuru = mysqli_query($con, "
        UPDATE guru 
        SET
        nama        = '$nama',
        c_jabatan   = '$jabatan',
        no_hp       = '$nohpwa'
        WHERE nip = '$nip'
      ");

      if ($queryUpdateGuru) {

        $findNipFromTblGroupKelas = mysqli_query($con, "
          SELECT nip FROM group_kelas
          WHERE nip = '$nip'
        ");

        $countNipTblGroupKelas = mysqli_num_rows($findNipFromTblGroupKelas);

        if ($countNipTblGroupKelas == 1) {
          // echo "Ada " . $countNipTblGroupKelas;exit;

          $queryUpdateGuruFromTblGroupKelas = mysqli_query($con, "
            UPDATE group_kelas 
            SET
            walas       = '$nama'
            WHERE nip   = '$nip'
          ");

          if ($queryUpdateGuruFromTblGroupKelas) {
            
            $_SESSION['update_data'] = "berhasil";

            $queryListTeacher = mysqli_query($con, "
              SELECT *
              FROM
              guru
              ORDER BY 
              nip ASC
            ");

          }

        } else {
          // echo "Tidak Ada " . $countNipTblGroupKelas;exit;

          $_SESSION['update_data'] = "berhasil";

          $queryListTeacher = mysqli_query($con, "
            SELECT *
            FROM
            guru
            ORDER BY 
            nip ASC
          ");

        }

      } else {
        $_SESSION['update_data'] = "gagal";
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
  <div style="display: none;" class="alert alert-warning alert-dismissable"> <span style="color: white;"> BERHASIL UPDATE DATA GURU </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['update_data']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['data_guru_hapus']) && $_SESSION['data_guru_hapus'] == 'berhasil'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: white;"> DATA GURU <?= strtoupper($walasDihapus); ?> BERHASIL DI HAPUS ! </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['data_guru_hapus']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['update_data']) && $_SESSION['update_data'] == 'gagal'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> DATA GURU GAGAL DI UPDATE ! </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['update_data']);
    ?>
  </div>
<?php } ?>

<div class="box box-info">

  <center> 
    <h4 id="judul_daily">
      <strong> LIST ALL TEACHER </strong> 
    </h4> 
  </center>

  <div class="box-body table-responsive">
	
  <div class="isflex" style="display: flex; gap: 10px; margin-bottom: 30px;">

  	<a href="<?= $basead; ?>ekspordataguru" class="btn btn-sm btn-success"> <i class="glyphicon glyphicon-export"></i> EKSPOR AKSES LOGIN GURU </a>

    <a href="<?= $basead; ?>addteacher" class="btn btn-sm btn-warning"> TAMBAH GURU </a>

  </div>

    <table id="hightlight_list_siswa" style="text-align: center;" class="table table-bordered table-hover">

      <thead>
        <tr style="background-color: lightyellow; font-weight: bold;">
          <th style="text-align: center; border: 1px solid black;" width="5%">NO</th>
          <th style="text-align: center; border: 1px solid black;"> NIP </th>
          <th style="text-align: center; border: 1px solid black;"> NAMA </th>
          <th style="text-align: center; border: 1px solid black;"> JABATAN </th>
          <th style="text-align: center; border: 1px solid black;"> ACTION </th>
          <!-- <th style="text-align: center;"> DAILY </th> -->
          <!-- Terdapat Administrasi Pembiayaan Yang Perlu Di Selesaikan -->
        </tr>
      </thead>

      <tbody>

        <?php foreach ($queryListTeacher as $data): ?>
          
          <tr>
            <td style="text-align: center;"> <?= $no++; ?> </td>
            <td style="text-align: center;"> <?= $data['nip']; ?> </td>
            <td style="text-align: center;"> <?= str_replace([" S.PD", ","], "", strtoupper($data['nama'])); ?> </td>
            <td style="text-align: center;"> <?= str_replace(["_"], " ", $data['c_jabatan']); ?> </td>

            <td style="text-align: center;"> 

              <button class="btn btn-sm btn-primary" onclick="editModal(`<?= $data['nama']; ?>`, `<?= $data['nip']; ?>`, `<?= $data['c_jabatan']; ?>`, `<?= $data['no_hp']; ?>`)"> EDIT </button> | 
              <button class="btn btn-sm btn-danger" data-target="#hapus<?= $data['nip']; ?>" data-toggle="modal"> DELETE </button>

            </td>
          </tr>

          <div id="hapus<?php echo $data['nip']; ?>" class="modal" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header bg-red">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title" id="myModalLabel"> <i class="glyphicon glyphicon-trash"></i> Konfirmasi Hapus Data Guru </h4>
                </div>
                <div class="modal-body">
                  <h4> <b> Anda Yakin Ingin Menghapus Data Guru <?= strtoupper($data['nama']); ?> ? </b> </h4>
                  <p>Jika Anda Menghapus Data Ini, Akan Berpengaruh Pada : </p>
                  <ul>
                    <li><b> Data Group Kelas Terhapus </b></li>
                  </ul>
                </div>
                <div class="modal-footer">
                  <form method="POST">
                    <input type="hidden" name="nipguru" value="<?= $data['nip']; ?>">
                    <button class="btn btn-success" type="submit" name="delete_teacher"> <i class="glyphicon glyphicon-ok"></i> Lanjutkan</a> </button>
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
        <h4 class="modal-title" id="myModalLabel"> <i class="glyphicon glyphicon-list-alt"></i> Data Guru </h4>
      </div>

      <form method="post">
        <div class="modal-body">
        	<div class="row">
        		<div class="col-sm-3">
          		<label> NIP </label>
        		</div>
          	<div class="col-sm-4" style="top: -4px;">
          		<input type="text" readonly name="nip" id="nip" class="form-control" value="ajdnajsdn">
          	</div>
        	</div>
          <br>
          <div class="row">
            <div class="col-sm-3">
              <label> NAMA </label>
            </div>
            <div class="col-sm-9" style="top: -4px;">
              <input type="text" name="nama_guru" id="nama_guru" class="form-control" value="ajdnajsdn">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-sm-3">
              <label> JABATAN </label>
            </div>
            <div class="col-sm-9" style="top: -4px;">
              <input type="text" name="jabatan" id="jabatan" class="form-control" value="ajdnajsdn">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-sm-3">
              <label> NO HP/WA </label>
            </div>
            <div class="col-sm-9" style="top: -4px;">
              <input type="text" name="nohpwa" pattern="[0-9]*" inputmode="numeric" onkeypress="return onlyNumberKey(event)" maxlength="13" id="nohpwa" class="form-control" value="">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeModal()">Close</button>
          <button type="submit" name="update_guru" class="btn btn-success">Save changes</button>
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
	    newIcon.classList.add("glyphicon");
	    newIcon.classList.add("glyphicon-user");

	    let getTitleList2 = document.getElementById('addtc').innerHTML;

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

  function editModal(nama, nip, jabat, nohp) {

    $("#nip").val(nip);
    $("#nama_guru").val(nama);
    $("#jabatan").val(jabat);
    $("#nohpwa").val(nohp);
    $("#walas").val(nip.replace(",  ", ", "));
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