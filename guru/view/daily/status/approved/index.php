<?php  
	
	$timeOut        = $_SESSION['expire_paud'];
    
	$timeRunningOut = time() + 5;

	$timeIsOut = 0;
	$sesi      = 0;

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

	function format_tgl_indo_appr($date){  
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

	// echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut . "<br>";

	if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

		$_SESSION['form_success'] = "session_time_out";

    $timeIsOut = 1;
    error_reporting(1);

	} else {

		// echo $_SESSION['nip_guru'];exit;

		$queryApprovedDaily         = mysqli_query($con, "
		    SELECT *
		  	FROM (
		  		SELECT 
		  			daily_siswa_approved.id as daily_id,
			      	daily_siswa_approved.from_nip as from_nip,
			      	daily_siswa_approved.image as foto,
			      	daily_siswa_approved.isi_daily as isi_daily,
			      	daily_siswa_approved.nis_siswa as nis_or_id_group_kelas,
			      	daily_siswa_approved.title_daily as judul,
			      	daily_siswa_approved.tanggal_dibuat as tgl_dibuat,
			      	daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
					daily_siswa_approved.status_approve AS status_approve,
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
        		UNION
		      	SELECT 
				  	group_siswa_approved.id as group_daily_id,
		      		group_siswa_approved.from_nip as from_nip,
		      		group_siswa_approved.image as foto,
		      		group_siswa_approved.isi_daily as isi_daily,
		      		group_siswa_approved.group_kelas_id as group_kelas_id,
		      		group_siswa_approved.title_daily as judul,
		      		group_siswa_approved.tanggal_dibuat as tgl_dibuat,
		      		group_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui,
					group_siswa_approved.status_approve AS status_approve,
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
		       ) AS U
		 	WHERE 
			 	U.status_approve = 1
			 	AND U.from_nip = '$_SESSION[nip_guru_paud]'
		     	ORDER BY U.tgl_disetujui DESC
	  	");

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

    <table id="example" border="1" class="display nowrap" style="width:100%">
        <thead>
            <tr style="background-color: lightyellow;">
                <th style="text-align: center;" width="5%">NO</th>
			          <th style="text-align: center;"> DAILY </th>
			          <th style="text-align: center;"> TITLE </th>
			          <th style="text-align: center;"> STATUS </th>
			          <th style="text-align: center;"> DATE POSTED </th>
            </tr>
        </thead>
        <tbody>
        	
        	<?php foreach ($queryApprovedDaily as $appr_d): ?>

        		<?php  

	      			$nisOrGroupID = $appr_d['nis_or_id_group_kelas'];
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

	      			<tr id="tr_dashboard" style="background-color: limegreen; color: white; font-weight: bold;" onclick="showDataApproved(
	      					`group`,
			      			`<?= $appr_d['room_key']; ?>`,
				      		`<?= $appr_d['daily_id']; ?>`,
				      		`<?= $appr_d['tgl_disetujui']; ?>`,
				      		`<?= format_tgl_indo_appr($appr_d['tgl_disetujui']); ?>`,
				      		`<?= $appr_d['nama_guru']; ?>`,
				      		`<?= format_tgl_indo_appr($appr_d['tgl_dibuat']); ?>`,
				      		`<?= $appr_d['nis_or_id_group_kelas']; ?>`,
				      		`<?= strtoupper($appr_d['nama_siswa_or_nama_group_kelas']); ?>`,
				      		`<?= $appr_d['foto']; ?>`,
				      		`<?= $appr_d['judul']; ?>`,
				      		`<?= $appr_d['isi_daily']; ?>`
				      	)">
				        <td style="text-align: center;"> <?= $no++; ?> </td>
				        <td style="text-align: center;"> GROUP <?= strtoupper($appr_d['nama_siswa_or_nama_group_kelas']) ?> </td>
				        <td style="text-align: center;"> <?= strtoupper($appr_d['judul']) ?> </td>
				        <td style="text-align: center;"> APPROVED <i style="color: gold;" class="glyphicon glyphicon-ok"></i> </td>
				        <td style="text-align: center;"> <?= formatDateEnglish($appr_d['tgl_disetujui']); ?> </td>
			      	</tr>

	      		<?php elseif($countNis == 1): ?>

	      			<tr id="tr_dashboard" style="background-color: limegreen; color: white; font-weight: bold;" onclick="showDataApproved(
	      					`std`,
			      			`<?= $appr_d['room_key']; ?>`,
				      		`<?= $appr_d['daily_id']; ?>`,
				      		`<?= $appr_d['tgl_disetujui']; ?>`,
				      		`<?= format_tgl_indo_appr($appr_d['tgl_disetujui']); ?>`,
				      		`<?= $appr_d['nama_guru']; ?>`,
				      		`<?= format_tgl_indo_appr($appr_d['tgl_dibuat']); ?>`,
				      		`<?= $appr_d['nis_or_id_group_kelas']; ?>`,
				      		`<?= strtoupper($appr_d['nama_siswa_or_nama_group_kelas']); ?>`,
				      		`<?= $appr_d['foto']; ?>`,
				      		`<?= $appr_d['judul']; ?>`,
				      		`<?= $appr_d['isi_daily']; ?>`
				      	)">
				        <td style="text-align: center;"> <?= $no++; ?> </td>
				        <td style="text-align: center;"> <?= strtoupper($appr_d['nama_siswa_or_nama_group_kelas']) ?> </td>
				        <td style="text-align: center;"> <?= strtoupper($appr_d['judul']) ?> </td>
				        <td style="text-align: center;"> APPROVED <i style="color: gold;" class="glyphicon glyphicon-ok"></i> </td>
				        <td style="text-align: center;"> <?= formatDateEnglish($appr_d['tgl_disetujui']); ?> </td>
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

</script>

<script type="text/javascript">

	let grouporstd = '';

	function showDataApproved(stdOrGroup, roomKey, daily_id, dateOri, dateApproved, sender, datePosted, nis, nm, photo, title, main) {

		grouporstd = stdOrGroup;

		$("#inpage-approved").modal('show');
		$('#inpg_appr').attr('action', `lookactivity/${roomKey}`);

		$("#inpage_date_approved").val(dateApproved);
		$("#inpage_pengirim_appr").val(sender);
		$("#inpage_tanggal_upload_appr").val(datePosted);
		$("#inpage_siswa_daily_appr").val(nm);
		let imageInPage     	= document.querySelector("img[id='inpage_foto_upload_appr']");
		imageInPage.setAttribute("src", `../image_uploads/${photo}`);
		$("#inpage_title_daily_appr").val(title);
		$("#inpage_main_daily_appr").html(main);

		if (grouporstd == "std") {
			$("#lbl_std_or_gp_inpage_appr").text("STUDENT");
			$("#inpage_roomkey_lookdaily").attr('name', 'roomkey_lookdaily');
			$("#inpage_roomkey_lookdaily").val(roomKey);
			$("#inpage_lookdaily_appr").attr('name', 'redirectLookDaily');
		} else if (grouporstd == "group") {
			$("#lbl_std_or_gp_inpage_appr").text("GROUP");
			$("#inpage_lookdaily_appr").attr('name', 'daily_group');
			$("#inpage_roomkey_lookdaily").attr('name', 'roomkey_group_lookdaily');
			$("#inpage_roomkey_lookdaily").val(roomKey);
		}

		$("#inpage_nama_guru_lookdaily").val(sender);
		$("#inpage_nis_siswa_lookdaily").val(nis);
		$("#inpage_nama_siswa_lookdaily").val(nm);
		$("#inpage_foto_upload_lookdaily").val(photo);
		$("#inpage_tgl_posting_lookdaily").val(dateApproved);
		$("#inpage_jdl_posting_lookdaily").val(title);
		$("#inpage_isi_posting_lookdaily").val(main);
		$("#inpage_tglori_posting_lookdaily").val(dateOri);

	}

	$(document).ready(function() {

		$("#aList1").click();
		$("#isiList3").click();
		$("#stat_appr").css({
      "background-color" : "#ccc",
      "color" : "black"
  	});

  	$("#addIcon").css({
      "top" : "3px"
  	});

  	$("#isiMenu").css({
      "margin-left" : "5px"
  	});

  	let newIcon = document.getElementById("addIcon");
  	newIcon.classList.remove("fa");
	newIcon.classList.add("glyphicon");
	newIcon.classList.add("glyphicon-thumbs-up");

	document.getElementById('isiMenu').innerHTML = `<span style="font-weight: bold;"> STATUS - </span>` + `<span style="font-weight: bold;"> APPROVED </span>`
		

	})
	

</script>