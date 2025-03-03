<?php 

	$timeOut        = $_SESSION['expire'];
    
  	$timeRunningOut = time() + 5;

  	$timeIsOut = 0;

  	$diMenu    = "dashboard";

  	// NOTE
  	// Jika Sudah di Approve buat table room_server dan join dengan table daily_siswa_approved

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

  	// echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

  	if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

	    $_SESSION['form_success'] = "session_time_out";

	    $timeIsOut = 1;
	    error_reporting(1);
	      // exit;

 	} else {

	 	$getDataBagian  = $_SESSION['c_kepsek'];

	  	$is_SD      = "/SD/i";
	  	$is_PAUD    = "/PAUD/i";
	  	$sd         = false;
	  	$paud       = false;

	  	$foundDataSD    = preg_match($is_SD, $getDataBagian);
	  	$foundDataPAUD  = preg_match($is_PAUD, $getDataBagian);

	 	date_default_timezone_set("Asia/Jakarta");
	  	$arrTgl               = [];
		  
	  	$tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
	  	$tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

	  	if ($foundDataSD == 1) {

	  		$queryGetDataAppr = mysqli_query($con, "
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
		        	U.departemen = 'SD'
		        	AND U.tgl_dibuat >= '$tglSkrngAwal' 
					AND U.tgl_dibuat <= '$tglSkrngAkhir'  
		          	ORDER BY U.tgl_dibuat DESC
			");

	  	} else if ($foundDataPAUD == 1) {

	  		$queryGetDataAppr = mysqli_query($con, "
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
		        	U.departemen = 'PAUD'
		        	AND U.tgl_dibuat >= '$tglSkrngAwal' 
					AND U.tgl_dibuat <= '$tglSkrngAkhir'  
		          	ORDER BY U.tgl_dibuat DESC
			");

	  	}

	 }

?>

<div class="box box-info">

	<div class="box-header with-border">
      <h3 class="box-title" id="boxTitle"> <i class="glyphicon glyphicon-th-large"></i> <span style="margin-left: 10px; font-weight: bold;"> DASHBOARD </span> </h3>
    </div>

    <center> 
    	<h4 id="judul_daily">
    		<strong> <u> TODAY'S ACTIVITIES </u> </strong> 
    	</h4> 
    </center>

  	<div class="box-body table-responsive">

	    <table id="hightlight_list_siswa" style="text-align: center;" class="table table-bordered table-hover">
	      	<thead>
		        <tr style="background-color: lightyellow; font-weight: bold;">
		          <th style="text-align: center; border: 1px solid black;" width="5%">NO</th>
		          <th style="text-align: center; border: 1px solid black;"> TEACHER </th>
		          <th style="text-align: center; border: 1px solid black;"> DAILY </th>
		          <th style="text-align: center; border: 1px solid black;"> DAILY TITLE </th>
		          <th style="text-align: center; border: 1px solid black;"> DATE POSTED </th>
		          <th style="text-align: center; border: 1px solid black;"> STATUS </th>
		          <!-- <th style="text-align: center;"> DAILY </th> -->
		        </tr>
	      	</thead>

	      <?php $no = 1; ?>

      		<tbody>
	      	<?php foreach ($queryGetDataAppr as $appr): ?>

	      		<?php  

	      			$nisOrGroupID = $appr['nis_or_id_group_kelas'];
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

	      			<?php if ($appr['status_approve'] == 1): ?>

		      			<tr style="background-color: limegreen; color: white; font-weight: bold;" id="tr_dashboard" onclick="showData(
			      			`group`,
			      			`<?= $appr['room_key']; ?>`,
			      			`<?= $appr['daily_id']; ?>`,
			      			`<?= $appr['status_approve']; ?>` ,
			      			`<?= $appr['nama_guru']; ?>`, 
			      			`<?= $appr['tgl_disetujui']; ?>`,
			      			`<?= format_tgl_indo($appr['tgl_dibuat']); ?>`,
			      			`<?= format_tgl_indo($appr['tgl_disetujui']); ?>`,
			      			`<?= $appr['foto']; ?>`,
			      			`<?= $appr['from_nip']; ?>`,
			      			`<?= $appr['nis_or_id_group_kelas']; ?>`,
			      			`<?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?>`,
			      			`<?= $appr['judul']; ?>`,
			      			`<?= $appr['isi_daily']; ?>`
			      			)" data-status="<?= $appr['status_approve']; ?>" style="text-align: center;">
				        	<td id="tr_dashboard_no"> <?= $no++; ?> </td>
				        	<td id="tr_dashboard_guru"> <?= strtoupper($appr['nama_guru']); ?> </td>
				        	<td> 

				        		<?php if ($countIdGroup == 1): ?>
				        			
				        			GROUP <?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?> 

			        			<?php elseif($countNis == 1): ?>

			        				<?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?> 
				        		
				        		<?php endif ?>

				        	</td>
				        	<td> <?= $appr['judul']; ?> </td>
				        	<td> <?= format_tgl_indo($appr['tgl_disetujui']); ?> </td>
				        	<?php if ($appr['status_approve'] == 1): ?>
				        		<td> APPROVE <i style="color: gold;" class="glyphicon glyphicon-ok"></i> </td>
				        	<?php elseif($appr['status_approve'] == 0): ?>
				        		<td> WAITING <i class="glyphicon glyphicon-hourglass"></i> </td>
				        	<?php endif ?>
				        </tr>

		      		<?php elseif($appr['status_approve'] == 2): ?>

		      			<tr style="background-color: red; color: yellow;" id="tr_dashboard" onclick="showData(
		      				`group`,
			      			`<?= $appr['room_key']; ?>`,
			      			`<?= $appr['daily_id']; ?>`,
			      			`<?= $appr['status_approve']; ?>` ,
			      			`<?= $appr['nama_guru']; ?>`,
			      			`<?= $appr['tgl_disetujui']; ?>`,
			      			`<?= format_tgl_indo($appr['tgl_dibuat']); ?>`,
			      			`<?= format_tgl_indo($appr['tgl_disetujui']); ?>`,
			      			`<?= $appr['foto']; ?>`,
			      			`<?= $appr['from_nip']; ?>`,
			      			`<?= $appr['nis_or_id_group_kelas']; ?>`,
			      			`<?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?>`,
			      			`<?= $appr['judul']; ?>`,
			      			`<?= $appr['isi_daily']; ?>`,
			      			`<?= $appr['isi_alasan']; ?>`)" data-status="<?= $appr['status_approve']; ?>" style="text-align: center;">
				        	<td id="tr_dashboard_no"> <?= $no++; ?> </td>
				        	<td id="tr_dashboard_guru"> <?= $appr['nama_guru']; ?> </td>
				        	<td> 

				        		<?php if ($countIdGroup == 1): ?>
				        			
				        			GROUP <?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?> 

			        			<?php elseif($countNis == 1): ?>

			        				<?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?> 
				        		
				        		<?php endif ?>

				        	</td>
				        	<td> <?= $appr['judul']; ?> </td>
				        	<td> <?= format_tgl_indo($appr['tgl_disetujui']); ?> </td>
			        		<td> NOT APPROVE <i style="color: yellow;" class="glyphicon glyphicon-remove"></i> </td>
				        </tr>

		      		<?php elseif($appr['status_approve'] == 0): ?>
		      			
		      			<tr style="background-color: aqua;" id="tr_dashboard" onclick="showData(
		      				`group`,
			      			`<?= $appr['room_key']; ?>`,
			      			`<?= $appr['daily_id']; ?>`,
			      			`<?= $appr['status_approve']; ?>` ,
			      			`<?= $appr['nama_guru']; ?>`, 
			      			`<?= $appr['tgl_disetujui']; ?>`,
			      			`<?= format_tgl_indo($appr['tgl_dibuat']); ?>`,
			      			`<?= format_tgl_indo($appr['tgl_disetujui']); ?>`,
			      			`<?= $appr['foto']; ?>`,
			      			`<?= $appr['from_nip']; ?>`,
			      			`<?= $appr['nis_or_id_group_kelas']; ?>`,
			      			`<?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?>`,
			      			`<?= $appr['judul']; ?>`,
			      			`<?= $appr['isi_daily']; ?>`)" data-status="<?= $appr['status_approve']; ?>" style="text-align: center;">
				        	<td> <?= $no++; ?> </td>
				        	<td> <?= $appr['nama_guru']; ?> </td>
				        	<td> 

				        		<?php if ($countIdGroup == 1): ?>
				        			
				        			GROUP <?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?> 

			        			<?php elseif($countNis == 1): ?>

			        				<?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?> 
				        		
				        		<?php endif ?>

				        	</td>
				        	<td> <?= $appr['judul']; ?> </td>
				        	<td> <?= format_tgl_indo($appr['tgl_dibuat']); ?> </td>
				        	<?php if ($appr['status_approve'] == 1): ?>
				        		<td> APPROVE <i class="glyphicon glyphicon-ok"></i> </td>
				        	<?php elseif($appr['status_approve'] == 0): ?>
				        		<td> WAITING <i class="glyphicon glyphicon-hourglass"></i> </td>
				        	<?php endif ?>
				        </tr>

		      		<?php endif ?>

	      		<?php elseif($countNis == 1): ?>

	      			<?php if ($appr['status_approve'] == 1): ?>

		      			<tr style="background-color: limegreen; color: white; font-weight: bold;" id="tr_dashboard" onclick="showData(
			      			`std`,
			      			`<?= $appr['room_key']; ?>`,
			      			`<?= $appr['daily_id']; ?>`,
			      			`<?= $appr['status_approve']; ?>` ,
			      			`<?= $appr['nama_guru']; ?>`, 
			      			`<?= $appr['tgl_disetujui']; ?>`,
			      			`<?= format_tgl_indo($appr['tgl_dibuat']); ?>`,
			      			`<?= format_tgl_indo($appr['tgl_disetujui']); ?>`,
			      			`<?= $appr['foto']; ?>`,
			      			`<?= $appr['from_nip']; ?>`,
			      			`<?= $appr['nis_or_id_group_kelas']; ?>`,
			      			`<?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?>`,
			      			`<?= $appr['judul']; ?>`,
			      			`<?= $appr['isi_daily']; ?>`
			      			)" data-status="<?= $appr['status_approve']; ?>" style="text-align: center;">
				        	<td id="tr_dashboard_no"> <?= $no++; ?> </td>
				        	<td id="tr_dashboard_guru"> <?= strtoupper($appr['nama_guru']); ?> </td>
				        	<td> 

				        		<?php if ($countIdGroup == 1): ?>
				        			
				        			GROUP <?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?> 

			        			<?php elseif($countNis == 1): ?>

			        				<?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?> 
				        		
				        		<?php endif ?>

				        	</td>
				        	<td> <?= $appr['judul']; ?> </td>
				        	<td> <?= format_tgl_indo($appr['tgl_disetujui']); ?> </td>
				        	<?php if ($appr['status_approve'] == 1): ?>
				        		<td> APPROVE <i style="color: gold;" class="glyphicon glyphicon-ok"></i> </td>
				        	<?php elseif($appr['status_approve'] == 0): ?>
				        		<td> WAITING <i class="glyphicon glyphicon-hourglass"></i> </td>
				        	<?php endif ?>
				        </tr>

		      		<?php elseif($appr['status_approve'] == 2): ?>

		      			<tr style="background-color: red; color: yellow;" id="tr_dashboard" onclick="showData(
		      				`std`,
			      			`<?= $appr['room_key']; ?>`,
			      			`<?= $appr['daily_id']; ?>`,
			      			`<?= $appr['status_approve']; ?>` ,
			      			`<?= $appr['nama_guru']; ?>`,
			      			`<?= $appr['tgl_disetujui']; ?>`,
			      			`<?= format_tgl_indo($appr['tgl_dibuat']); ?>`,
			      			`<?= format_tgl_indo($appr['tgl_disetujui']); ?>`,
			      			`<?= $appr['foto']; ?>`,
			      			`<?= $appr['from_nip']; ?>`,
			      			`<?= $appr['nis_or_id_group_kelas']; ?>`,
			      			`<?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?>`,
			      			`<?= $appr['judul']; ?>`,
			      			`<?= $appr['isi_daily']; ?>`,
			      			`<?= $appr['isi_alasan']; ?>`)" data-status="<?= $appr['status_approve']; ?>" style="text-align: center;">
				        	<td id="tr_dashboard_no"> <?= $no++; ?> </td>
				        	<td id="tr_dashboard_guru"> <?= $appr['nama_guru']; ?> </td>
				        	<td> 

				        		<?php if ($countIdGroup == 1): ?>
				        			
				        			GROUP <?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?> 

			        			<?php elseif($countNis == 1): ?>

			        				<?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?> 
				        		
				        		<?php endif ?>

				        	</td>
				        	<td> <?= $appr['judul']; ?> </td>
				        	<td> <?= format_tgl_indo($appr['tgl_disetujui']); ?> </td>
			        		<td> NOT APPROVE <i style="color: yellow;" class="glyphicon glyphicon-remove"></i> </td>
				        </tr>

		      		<?php elseif($appr['status_approve'] == 0): ?>
		      			
		      			<tr style="background-color: aqua;" id="tr_dashboard" onclick="showData(
		      				`std`,
			      			`<?= $appr['room_key']; ?>`,
			      			`<?= $appr['daily_id']; ?>`,
			      			`<?= $appr['status_approve']; ?>` ,
			      			`<?= $appr['nama_guru']; ?>`, 
			      			`<?= $appr['tgl_disetujui']; ?>`,
			      			`<?= format_tgl_indo($appr['tgl_dibuat']); ?>`,
			      			`<?= format_tgl_indo($appr['tgl_disetujui']); ?>`,
			      			`<?= $appr['foto']; ?>`,
			      			`<?= $appr['from_nip']; ?>`,
			      			`<?= $appr['nis_or_id_group_kelas']; ?>`,
			      			`<?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?>`,
			      			`<?= $appr['judul']; ?>`,
			      			`<?= $appr['isi_daily']; ?>`)" data-status="<?= $appr['status_approve']; ?>" style="text-align: center;">
				        	<td> <?= $no++; ?> </td>
				        	<td> <?= $appr['nama_guru']; ?> </td>
				        	<td> 

				        		<?php if ($countIdGroup == 1): ?>
				        			
				        			GROUP <?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?> 

			        			<?php elseif($countNis == 1): ?>

			        				<?= strtoupper($appr['nama_siswa_or_nama_group_kelas']); ?> 
				        		
				        		<?php endif ?>

				        	</td>
				        	<td> <?= $appr['judul']; ?> </td>
				        	<td> <?= format_tgl_indo($appr['tgl_dibuat']); ?> </td>
				        	<?php if ($appr['status_approve'] == 1): ?>
				        		<td> APPROVE <i class="glyphicon glyphicon-ok"></i> </td>
				        	<?php elseif($appr['status_approve'] == 0): ?>
				        		<td> WAITING <i class="glyphicon glyphicon-hourglass"></i> </td>
				        	<?php endif ?>
				        </tr>

		      		<?php endif ?>
	      			
	      		<?php endif ?>

	      	<?php endforeach ?>
	      	</tbody>


	    </table>
  	</div>

</div>

<script type="text/javascript">

	let grouporstd      = "";

	function showData(stdOrGroup, roomKey, dailyID, stat, from, dateOri, datePosted, dateAppr, imgUpload, nip, nis, siswa, title, main, reason='ksg') {

		grouporstd = stdOrGroup;

		if (stat == 0) {
			// alert('Belum Di Approve');

			$("#modal-hightlight-wt-appr").modal('show');
			let dataHgDailyId   = dailyID;
          	let dataHgSender    = from;
          	let dataHgSiswa 	= siswa;
          	let dataHgTglUpload = datePosted;
          	let dataHgImage     = imgUpload;
          	let dataHgJudul     = title;
          	let dataHgDaily     = main;

          	let hgImage     = document.querySelector("img[id='hightlight_foto_upload']");

          	if (grouporstd == 'group') {
            	$("#lbl_std_or_gp_hg_wtappr").text('GROUP');
          	} else {
            	$("#lbl_std_or_gp_hg_wtappr").text('STUDENT');
          	}

          	$("#hightlight_save_not_approve").hide();
          	$("#hightlight_not_approve").show();
          	$("#hightlight_approve").show();
          	$(".hightlight_reason").hide();
          	$("#hightlight_cancel_not_approve").hide();
          	$("#hightlight_pengirim").val(dataHgSender);
          	$("#hightlight_tanggal_upload").val(dataHgTglUpload);
          	$("#hightlight_title_daily").val(dataHgJudul);
          	$("#hightlight_siswa_daily").val(dataHgSiswa);
          	$("#highlight_id_daily_waiiting").val(dataHgDailyId);
          	$("#highlight_nip_daily_waiiting").val(nip);
          	hgImage.setAttribute("src", `../image_uploads/${dataHgImage}`);
          	$("#hightlight_main_daily").html(dataHgDaily);

		} else if (stat == 1) {
			// alert("Sudah Di Approve");
			$("#modal-hightlight-appr").modal('show');
			$('#formHgAppr').attr('action', `lookactivity/${roomKey}`);
			let dataHgDailyId   = $(this).data('daily_id');
	      	let dataHgSender    = from;
	      	let dataHgSiswa 	= siswa;
	      	let dataHgTglAppr 	= dateAppr;
	      	let dataHgTglUpload = datePosted;
	      	let dataHgImage     = imgUpload;
	      	// alert(dataHgImage)
	      	let dataHgJudul     = $(this).data('judul');
	      	let dataHgDaily     = $(this).data('isian');

	      	let hgImage     = document.querySelector("img[id='hg_foto_upload_appr']");

	      	if (grouporstd == 'group') {
            	$("#lbl_std_or_gp_hg_appr").text('GROUP');
            	$("#hg_lookdaily_appr").attr('name', 'daily_group');
            	$("#hg_roomkey_lookdaily").attr('name', 'roomkey_group_lookdaily');
          	} else {
            	$("#lbl_std_or_gp_hg_appr").text('STUDENT');
            	$("#hg_lookdaily_appr").attr('name', 'redirectLookDaily');
            	$("#hg_roomkey_lookdaily").attr('name', 'roomkey_lookdaily');
          	}

	      	$("#hg_save_reason").hide();
	      	$(".hg_reason").hide();
	      	$("#hg_cancel_not_approve").hide();
	      	$("#hg_pengirim_appr").val(dataHgSender);
	      	$("#hg_siswa_daily_appr").val(dataHgSiswa);
	      	$("#hg_tanggal_upload_appr").val(dataHgTglUpload);
	      	$("#hg_title_daily_appr").val(title);
	      	$("#highlight_id_daily_waiiting").val(dataHgDailyId);

	      	// Isi Input Pada Modal
	      	$("#hg_date_appr").val(dataHgTglAppr);
	      	$("#hg_nis_siswa_lookdaily").val(nis);
	      	$("#hg_nama_siswa_lookdaily").val(siswa);
	      	$("#hg_nama_guru_lookdaily").val(from);
	      	$("#hg_jdl_posting_lookdaily").val(title);
	      	$("#hg_main_daily_appr").html(main);

	      	$("#hg_foto_upload_lookdaily").val(dataHgImage);
	      	$("#hg_tgl_posting_lookdaily").val(dataHgTglAppr);
	      	$("#hg_isi_posting_lookdaily").val(main);
	      	$("#hg_roomkey_lookdaily").val(roomKey);
	      	$("#hg_nip_guru_lookdaily").val(nip);
	      	$("#hg_tglori_posting_lookdaily").val(dateOri);

	      	hgImage.setAttribute("src", `../image_uploads/${dataHgImage}`);

		} else if (stat == 2) {

			$("#modal-hightlight-noappr").modal('show');
			$("#hg_date_not_approved").val(dateAppr);
			$("#hg_pengirim_notappr").val(from);
			$("#hg_tanggal_upload_notappr").val(datePosted);
			$("#hg_siswa_daily_notappr").val(siswa);
			$("#hg_title_daily_notappr").val(title);

			let hgImage     = document.querySelector("img[id='hg_foto_upload_notappr']");
			hgImage.setAttribute("src", `../image_uploads/${imgUpload}`);

			if (grouporstd == 'group') {
            	$("#lbl_std_or_gp_hg_noappr").text('GROUP');
          	} else {
            	$("#lbl_std_or_gp_hg_noappr").text('STUDENT');
          	}

			$("#hg_main_daily_notappr").html(main);

			if (reason != 'ksg') {
				if(reason == 'no_comment') {
	                $("#hg_div_default_reason").hide();
              	} else if (reason != 'no_comment') {
	                $("#hg_div_default_reason").show();
	                $("#hg_reason_notappr").text(reason); 
              	}
			}

		}

  	}
		
	$(document).ready(function(){

		$("#dashboard").css({
	      "background-color" : "#ccc",
	      "color" : "black"
	  	});

	})

</script>