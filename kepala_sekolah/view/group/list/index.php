<?php  
	
	$timeOut        = $_SESSION['expire'];
    
  	$timeRunningOut = time() + 5;

  	$diMenu = "listgroup";

	$timeIsOut = 0;
	$sesi      = 0;

	if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

		$_SESSION['form_success'] = "session_time_out";

	    $timeIsOut = 1;
	    error_reporting(1);

	} else {

		$getDataBagian  = $_SESSION['c_kepsek'];

		$bagPaud        = "/PAUD/i";
		$isPaud      	= preg_match($bagPaud, $getDataBagian);

		if ($isPaud == 1) {

			$queryListGroupClass = mysqli_query($con, "
		      SELECT *
		      FROM
		      group_kelas
		      LEFT JOIN guru
		      ON guru.nip = group_kelas.nip
		      WHERE guru.c_jabatan LIKE '%PAUD%'
		      ORDER BY 
		      nama_group_kelas ASC
		    ");

		} elseif ($isPaud == 0) {

			$queryListGroupClass = mysqli_query($con, "
		      SELECT *
		      FROM
		      group_kelas
		      LEFT JOIN guru
		      ON guru.nip = group_kelas.nip
		      WHERE guru.c_jabatan NOT LIKE '%PAUD%'
		      ORDER BY 
		      nama_group_kelas ASC
		    ");

		} 

  		$no = 1;

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

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">

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
	  	<div class="box-body table-responsive">

		    <table id="listgroup_kp" class="table table-bordered table-hover" style="width:100%">
		        <thead>
		            <tr style="background-color: lightyellow;">
		                <th style="text-align: center; border: 1px solid black;" style="width: 10% !important;">NO</th>
			          	<th style="text-align: center; border: 1px solid black;"> GROUP </th>
			          	<th style="text-align: center; border: 1px solid black;"> WALAS </th>
          				<th style="text-align: center; border: 1px solid black;"> ACTION </th>
		            </tr>
		        </thead>
		        <tbody>
		            <?php foreach ($queryListGroupClass as $data): ?>
		            	
		            	<tr>
		            		<td style="text-align: center;"> <?= $no++; ?> </td>
		            		<td style="text-align: center;"> <?= $data['nama_group_kelas']; ?> </td>
		            		<td style="text-align: center;"> <?= $data['walas']; ?> </td>
		            		<td style="text-align: center;"> 

				              <a href="<?= $basekepsek; ?>groupclass/<?= $data['nama_group_kelas']; ?>" class="btn btn-success btn-sm"> <i class="fa-classic fa-solid fa-user-group fa-fw"> </i> ANGGOTA </a>

				            </td>
		            	</tr>

		            <?php endforeach ?>
		        </tbody>
		    </table>

	  	</div>
	</div>

<!-- <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script> -->
<script src="view/daily/query/dataTables1.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script> -->
<script src="view/daily/query/moment.min.js"></script>
<!-- <script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script> -->
<script src="view/daily/query/dateTime.min.js"></script>

<script type="text/javascript">

	let newIcon = document.getElementById("addIcon");
  	newIcon.classList.remove("fa");
  	newIcon.classList.add("fa-solid");
  	newIcon.classList.add("fa-list");

  	let getTitleList1 = document.getElementById('isiList2').innerHTML;

	$("#approve_in_page").click(function() {

      	$.ajax({
	        url  : `<?= $basekepsek; ?>data`,
	        type : "POST",
	        data : {
	          daily_id  : $("#id_daily_waiiting_in_page").val()
	        },
	        success:function(data) {

	          console.log(JSON.parse(data).status_approve);

	          Swal.fire({
	            title : "Approve",
	            icon  : "success",
	            timer : 1000
	          });

	          $("#not_approve_in_page").hide();
	          $("#approve_in_page").hide();
	          $("#close_not_yet_appr_in_page").click();

	        }

      	})

    })

	$(document).ready(function() {

		$("#listgroup").click();
	    $("#menulistgroup").css({
	        "background-color" : "#ccc",
	        "color" : "black"
	    });

	    $("#isiMenu").css({
	      "margin-left" : "5px"
	    });

	})

	document.getElementById('isiMenu').innerHTML = `<span style="font-weight: bold;"> LIST GROUP </span>`

</script>