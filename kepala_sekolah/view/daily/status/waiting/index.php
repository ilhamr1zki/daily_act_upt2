<?php  
	
	$timeOut        = $_SESSION['expire'];
    
  	$timeRunningOut = time() + 5;

  	$diMenu = "waiting";

	$timeIsOut = 0;
	$sesi      = 0;

	// echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut . "<br>";

	function formatDateEnglish($date){  
	  $tanggal_indo = date_create($date);
	  date_timezone_set($tanggal_indo,timezone_open("Asia/Jakarta"));
	  $array_bulan = array(1=>'January','February','March', 'April', 'May', 'June','July','August','September','October', 'November','Desember');
	  $date = strtotime($date);
	  $tanggal = date ('d', $date);
	  $bulan = $array_bulan[date('n',$date)];
	  $tahun = date('Y',$date); 
	  $H     = date_format($tanggal_indo, "H");
	  $i     = date_format($tanggal_indo, "i");
	  $s     = date_format($tanggal_indo, "s");

	  $jamIndo = $H.":".$i.":".$s;
	  $result = $tanggal ." ". $bulan ." ". $tahun . " " . $jamIndo;       
	  return($result);  
	}

	function format_tgl_indo($date){  
	    $tanggal_indo = date_create($date);
	    date_timezone_set($tanggal_indo,timezone_open("Asia/Jakarta"));
	    $array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember');
	    $date = strtotime($date);
	    $tanggal = date ('d', $date);
	    $bulan = $array_bulan[date('n',$date)];
	    $tahun = date('Y',$date); 

	    $H     = date_format($tanggal_indo, "H");
	    $i     = date_format($tanggal_indo, "i");
	    $s     = date_format($tanggal_indo, "s");
	    // $jamIndo = date("h:i:s", $date);
	    $jamIndo = date_format($tanggal_indo, "H:i:s");
	    // echo $jamIndo;
	    $result = $tanggal ." ". $bulan ." ". $tahun . " " . $jamIndo;       
	    return($result);  
	}

	if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

		$_SESSION['form_success'] = "session_time_out";

	    $timeIsOut = 1;
	    error_reporting(1);

	} else {

		$getDataBagian  = $_SESSION['c_kepsek'];

		$is_SD      = "/SD/i";
	  	$is_PAUD    = "/PAUD/i";
	  	$sd         = false;
	  	$paud       = false;

	  	$foundDataSD    = preg_match($is_SD, $getDataBagian);
	  	$foundDataPAUD  = preg_match($is_PAUD, $getDataBagian);

	  	if ($foundDataSD == 1) {

	  		// echo $_POST['judul'];exit;
			$queryWaitingApprovedDaily = mysqli_query($con, "
			    SELECT *
		        FROM (
		          SELECT 
		            daily_siswa_approved.id as daily_id,
		            daily_siswa_approved.departemen as departemen,
		            daily_siswa_approved.from_nip as from_nip,
		            guru.username as username_guru,
		            daily_siswa_approved.image as foto,
		            daily_siswa_approved.isi_daily as isi_daily,
		            daily_siswa_approved.nis_siswa as nis_or_id_group_kelas,
		            daily_siswa_approved.title_daily as judul,
		            daily_siswa_approved.tanggal_dibuat as tgl_dibuat,
		            daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
		            daily_siswa_approved.status_approve AS status_approve,
		            reason.is_reason AS isi_alasan,
		            guru.nama as nama_guru,
		            admin.username as nama_user,
		            siswa.nama as nama_siswa_or_nama_group_kelas,
		            ruang_pesan.room_key as room_key
		          FROM daily_siswa_approved
		          LEFT JOIN guru
		            ON daily_siswa_approved.from_nip = guru.nip
		            LEFT JOIN admin
		            ON daily_siswa_approved.from_nip = admin.c_admin
		            LEFT JOIN siswa
		            ON daily_siswa_approved.nis_siswa = siswa.nis
		            LEFT JOIN ruang_pesan
		            ON ruang_pesan.daily_id = daily_siswa_approved.id
		            LEFT JOIN reason
		            ON reason.daily_siswa_id = daily_siswa_approved.id
		          UNION
		          SELECT 
		            group_siswa_approved.id as group_daily_id,
		            group_siswa_approved.departemen as departemen,
		            group_siswa_approved.from_nip as from_nip,
		            guru.username as username_guru,
		            group_siswa_approved.image as foto,
		            group_siswa_approved.isi_daily as isi_daily,
		            group_siswa_approved.group_kelas_id as group_kelas_id,
		            group_siswa_approved.title_daily as judul,
		            group_siswa_approved.tanggal_dibuat as tgl_dibuat,
		            group_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
		            group_siswa_approved.status_approve AS status_approve,
		            reason.is_reason AS isi_alasan,
		            guru.nama as nama_guru,
		            admin.username as nama_user,
		            group_kelas.nama_group_kelas as nama_group_kelas,
		            ruang_pesan.room_key as room_key
		          FROM group_siswa_approved
		            LEFT JOIN guru
		            ON group_siswa_approved.from_nip = guru.nip
		            LEFT JOIN admin
		            ON group_siswa_approved.from_nip = admin.c_admin
		            LEFT JOIN group_kelas
		            ON group_siswa_approved.group_kelas_id = group_kelas.id
		            LEFT JOIN ruang_pesan
		            ON ruang_pesan.daily_id = group_siswa_approved.id
		            LEFT JOIN reason
		            ON reason.daily_siswa_id = group_siswa_approved.id
		         ) AS U
		        WHERE 
		          U.status_approve = 0
		          AND U.departemen = 'SD'
		          ORDER BY U.tgl_dibuat DESC
	  		");

	  	} else if ($foundDataPAUD == 1) {

	  		// echo $_POST['judul'];exit;
			$queryWaitingApprovedDaily = mysqli_query($con, "
			    SELECT *
		        FROM (
		          SELECT 
		            daily_siswa_approved.id as daily_id,
		            daily_siswa_approved.departemen as departemen,
		            daily_siswa_approved.from_nip as from_nip,
		            guru.username as username_guru,
		            daily_siswa_approved.image as foto,
		            daily_siswa_approved.isi_daily as isi_daily,
		            daily_siswa_approved.nis_siswa as nis_or_id_group_kelas,
		            daily_siswa_approved.title_daily as judul,
		            daily_siswa_approved.tanggal_dibuat as tgl_dibuat,
		            daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
		            daily_siswa_approved.status_approve AS status_approve,
		            reason.is_reason AS isi_alasan,
		            guru.nama as nama_guru,
		            admin.username as nama_user,
		            siswa.nama as nama_siswa_or_nama_group_kelas,
		            ruang_pesan.room_key as room_key
		          FROM daily_siswa_approved
		          LEFT JOIN guru
		            ON daily_siswa_approved.from_nip = guru.nip
		            LEFT JOIN admin
		            ON daily_siswa_approved.from_nip = admin.c_admin
		            LEFT JOIN siswa
		            ON daily_siswa_approved.nis_siswa = siswa.nis
		            LEFT JOIN ruang_pesan
		            ON ruang_pesan.daily_id = daily_siswa_approved.id
		            LEFT JOIN reason
		            ON reason.daily_siswa_id = daily_siswa_approved.id
		          UNION
		          SELECT 
		            group_siswa_approved.id as group_daily_id,
		            group_siswa_approved.departemen as departemen,
		            group_siswa_approved.from_nip as from_nip,
		            guru.username as username_guru,
		            group_siswa_approved.image as foto,
		            group_siswa_approved.isi_daily as isi_daily,
		            group_siswa_approved.group_kelas_id as group_kelas_id,
		            group_siswa_approved.title_daily as judul,
		            group_siswa_approved.tanggal_dibuat as tgl_dibuat,
		            group_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
		            group_siswa_approved.status_approve AS status_approve,
		            reason.is_reason AS isi_alasan,
		            guru.nama as nama_guru,
		            admin.username as nama_user,
		            group_kelas.nama_group_kelas as nama_group_kelas,
		            ruang_pesan.room_key as room_key
		          FROM group_siswa_approved
		            LEFT JOIN guru
		            ON group_siswa_approved.from_nip = guru.nip
		            LEFT JOIN admin
		            ON group_siswa_approved.from_nip = admin.c_admin
		            LEFT JOIN group_kelas
		            ON group_siswa_approved.group_kelas_id = group_kelas.id
		            LEFT JOIN ruang_pesan
		            ON ruang_pesan.daily_id = group_siswa_approved.id
		            LEFT JOIN reason
		            ON reason.daily_siswa_id = group_siswa_approved.id
		         ) AS U
		        WHERE 
		          U.status_approve = 0
		          AND U.departemen = 'PAUD'
		          ORDER BY U.tgl_dibuat DESC
	  		");

	  	}

  		$no = 1;

	}

?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/datetime/1.5.2/css/dataTables.dateTime.min.css">

	<div class="box box-info">
	  	<div class="box-body table-responsive">

		  	<table border="0" cellspacing="5" cellpadding="5" id="tableFilter">
		      <tbody id="filterDate">
		        <tr>
		            <td> Filter Date From <span id="dotFrom"> : </span> </td>
		            <td><input type="text" id="min" name="min"></td>
		        </tr>
		        <tr>
		            <td> Filter Date To <span id="dotTo"> : </span> </td>
		            <td><input type="text" id="max" name="max"></td>
		        </tr>
		      </tbody>
		    </table>

		    <table id="example" class="display nowrap" style="width:100%">
		        <thead>
		            <tr style="background-color: lightyellow;">
		                <th style="text-align: center; border: 1px solid black;" width="5%">NO</th>
			          	<th style="text-align: center; border: 1px solid black;"> CREATED BY </th>
			          	<th style="text-align: center; border: 1px solid black;"> DAILY </th>
			          	<th style="text-align: center; border: 1px solid black;"> DAILY TITLE </th>
			          	<th style="text-align: center; border: 1px solid black;"> CREATED DATE </th>
			          	<th style="text-align: center; border: 1px solid black;"> STATUS </th>
		            </tr>
		        </thead>
		        <tbody>
		        	
		        	<?php foreach ($queryWaitingApprovedDaily as $waiting_appr): ?>

		        		<?php  

			      			$nisOrGroupID = $waiting_appr['nis_or_id_group_kelas'];
			      			// echo $nisOrGroupID;exit;

			      			// Check Group Id
			      			$queryCheckDataIdGroup = mysqli_query($con, "
			      				SELECT id FROM group_kelas WHERE id = '$nisOrGroupID'
			      			");

			      			// Check Nis
			      			$queryCheckDataNIS = mysqli_query($con, "
			      				SELECT nama FROM siswa WHERE nis = '$nisOrGroupID'
			      			");

			      			$countIdGroup 	= mysqli_num_rows($queryCheckDataIdGroup);

			      			// echo $countIdGroup;exit;

			      			$countNis 		= mysqli_num_rows($queryCheckDataNIS);

			      		?>

			      		<?php if ($countIdGroup == 1): ?>

			      			<tr id="inpage_wtappr" style="background-color: aqua;" onclick="showDataWaitAppr(
					      		`group`,
					      		`<?= $waiting_appr['daily_id']; ?>`,
					      		`<?= $waiting_appr['status_approve']; ?>`,
					      		`<?= $waiting_appr['nama_guru']; ?>`,
					      		`<?= $waiting_appr['from_nip']; ?>`,
					      		`<?= format_tgl_indo($waiting_appr['tgl_dibuat']); ?>`,
					      		`<?= $waiting_appr['tgl_disetujui']; ?>`,
					      		`<?= $waiting_appr['foto']; ?>`,
					      		`<?= strtoupper($waiting_appr['nama_siswa_or_nama_group_kelas']); ?>`,
					      		`<?= $waiting_appr['judul']; ?>`,
					      		`<?= $waiting_appr['isi_daily']; ?>`
					      	)">
						        <td style="text-align: center;"> <?= $no++; ?> </td>
						        <td style="text-align: center;"> <?= $waiting_appr['nama_guru'] ?> </td>
						        <td style="text-align: center;"> GROUP <?= strtoupper($waiting_appr['nama_siswa_or_nama_group_kelas']); ?> </td>
						        <td style="text-align: center;"> <?= $waiting_appr['judul'] ?> </td>
						        <td style="text-align: center;"> <?= formatDateEnglish($waiting_appr['tgl_dibuat']); ?> </td>
					        	<td style="text-align: center;"> WAITING <i class="glyphicon glyphicon-hourglass"></i> </td>

					      	</tr>

			      		<?php elseif($countNis == 1): ?>

			      			<tr id="inpage_wtappr" style="background-color: aqua;" onclick="showDataWaitAppr(
					      		`std`,
					      		`<?= $waiting_appr['daily_id']; ?>`,
					      		`<?= $waiting_appr['status_approve']; ?>`,
					      		`<?= $waiting_appr['nama_guru']; ?>`,
					      		`<?= $waiting_appr['from_nip']; ?>`,
					      		`<?= format_tgl_indo($waiting_appr['tgl_dibuat']); ?>`,
					      		`<?= $waiting_appr['tgl_disetujui']; ?>`,
					      		`<?= $waiting_appr['foto']; ?>`,
					      		`<?= strtoupper($waiting_appr['nama_siswa_or_nama_group_kelas']); ?>`,
					      		`<?= $waiting_appr['judul']; ?>`,
					      		`<?= $waiting_appr['isi_daily']; ?>`
					      	)">
						        <td style="text-align: center;"> <?= $no++; ?> </td>
						        <td style="text-align: center;"> <?= $waiting_appr['nama_guru'] ?> </td>
						        <td style="text-align: center;"> <?= strtoupper($waiting_appr['nama_siswa_or_nama_group_kelas']); ?> </td>
						        <td style="text-align: center;"> <?= $waiting_appr['judul'] ?> </td>
						        <td style="text-align: center;"> <?= formatDateEnglish($waiting_appr['tgl_dibuat']); ?> </td>
					        	<td style="text-align: center;"> WAITING <i class="glyphicon glyphicon-hourglass"></i> </td>

					      	</tr>
			      			
			      		<?php endif ?>

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

	function showDataWaitAppr(stdOrGroup, dailyID, stat, from, nip, datePosted, dateAppr, imgUpload, siswa, title, main) {

		grouporstd = stdOrGroup;

		$("#inpage-wt-appr").modal('show');
		let dataHgDailyId   = dailyID;
      	let dataHgSender    = from;
      	let dataHgSiswa 	= siswa;
      	let dataHgTglUpload = datePosted;
      	let dataHgImage     = imgUpload;
      	let dataHgJudul     = title;
      	let dataHgDaily     = main;

      	let hgImage     = document.querySelector("img[id='inpage_foto_upload_wt_appr']");

      	if (grouporstd == "group") {
      		$("#lbl_std_or_gp_inpage_wtappr").text("GROUP");
      	} else if (grouporstd == "std") {
      		$("#lbl_std_or_gp_inpage_wtappr").text("STUDENT");
      	}

      	$("#inpage_save_notappr_wt_appr").hide();
      	$(".inpage_reason").hide();
      	$("#inpage_not_approve_wt_appr").show();
      	$("#inpage_approve_wt_appr").show();
      	$("#inpage_cancel_not_approve_wt_appr").hide();
      	$("#inpage_pengirim_wt_appr").val(dataHgSender);
      	$("#inpage_tanggal_upload_wt_appr").val(dataHgTglUpload);
      	$("#inpage_title_daily_wt_appr").val(dataHgJudul);
      	$("#inpage_siswa_daily_wt_appr").val(dataHgSiswa);
      	$("#inpage_id_daily_waiiting_wt_appr").val(dataHgDailyId);
      	$("#inpage_nip_daily_waiiting_wt_appr").val(nip);
      	hgImage.setAttribute("src", `../image_uploads/${dataHgImage}`);
      	$("#inpage_main_daily_wt_appr").html(dataHgDaily);

  	}

	let newIcon = document.getElementById("addIcon");
  	newIcon.classList.remove("fa");
  	newIcon.classList.add("glyphicon");
  	newIcon.classList.add("glyphicon-hourglass");

  	let getTitleList1 = document.getElementById('isiList2').innerHTML;

  	var minDate =""; 
	var maxDate = "";
 
	// Custom filtering function which will search data in column four between two values
	DataTable.ext.search.push(function (settings, data, dataIndex) {
		var min = minDate.val();
	    var max = maxDate.val();
	    var date = new Date(data[4]);

	    if (
	        (min === null && max === null) ||
	        (min === null && date <= max) ||
	        (min <= date && max === null) ||
	        (min <= date && date <= max)
	    ) {
	        return true;
	    }
	    return false;
	});
		   
  	// Create date inputs
  	minDate = new DateTime('#min', {
      	format: 'MMMM Do YYYY'
  	});
  	maxDate = new DateTime('#max', {
      	format: 'MMMM Do YYYY'
  	});
   
  	// DataTables initialisation
  	var table = new DataTable('#example');
   
  	// Refilter the table
  	document.querySelectorAll('#min, #max').forEach((el) => {
      el.addEventListener('change', () => table.draw());
  	});

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

		$("#aList1").click();
	    $("#isiList3").click();
	    $("#stat_wait").css({
	        "background-color" : "#ccc",
	        "color" : "black"
	    });

	    $("#isiMenu").css({
	      "margin-left" : "5px"
	    });

	})

	document.getElementById('isiMenu').innerHTML = `<span style="font-weight: bold;"> STATUS - </span>` + `<span style="font-weight: bold;"> WAITING APPROVAL </span>`

</script>