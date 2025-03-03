<?php  

	$timeOut        = $_SESSION['expire'];
    
  	$timeRunningOut = time() + 5;

	$timeIsOut 		= 0;
	$sesi      		= 0;
	$namagroupkelas = "";
	$jumlahData 	= 0;

	$no 			= 1;
	$dataEmpty      = 0;

	$countDataActivity = "";

	$diMenu     	= "teachercreategroupdaily";
	$namaguru 		= $_SESSION['nama_guru'];

	// echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

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

  		if (isset($_POST['send_data_group'])) {

  			$id_group  		= htmlspecialchars($_POST['id_group']);
  			$namagroupkelas	= htmlspecialchars($_POST['nama_group_kelas']);

  			// echo $_SESSION['nip_guru'];exit;

  			// Cari Id Group kelas di tabel group siswa approved
  			$dataActivityGroupFromTeacher = mysqli_query($con, "
  				SELECT
	  			guru.nama as nama_guru,
	  			guru.nip as nip_guru,
	  			group_kelas.id as id_group_kelas,
	  			group_kelas.nama_group_kelas as nama_group_kelas,
	  			group_siswa_approved.image as foto_upload,
	  			group_siswa_approved.title_daily as judul_daily,
	  			group_siswa_approved.id as id_group_approved,
	  			group_siswa_approved.isi_daily as isi_daily,
	  			group_siswa_approved.tanggal_disetujui_atau_tidak as daily_tanggal_disetujui_atau_tidak,
	  			ruang_pesan.room_key as room_key
	  			FROM group_siswa_approved
	  			LEFT JOIN guru
	  			ON group_siswa_approved.from_nip = guru.nip
	  			LEFT JOIN group_kelas
	  			ON group_siswa_approved.group_kelas_id = group_kelas.id
	  			LEFT JOIN ruang_pesan
	  			ON group_siswa_approved.id = ruang_pesan.daily_id
	  			WHERE group_siswa_approved.status_approve = 1
	  			AND group_siswa_approved.group_kelas_id = '$id_group'
	  			AND guru.nip = '$_SESSION[nip_guru]'
	  			ORDER BY group_siswa_approved.tanggal_disetujui_atau_tidak DESC
  			");

  			$countDataActivity = mysqli_num_rows($dataActivityGroupFromTeacher);
  			// echo $countDataActivity;exit;

  		} else {
  			
  			$dataActivityGroupFromTeacher = [];
  			$sesi      = 2;

  		}

  		$jumlahData = 5;

  	}

  	$upperName = strtoupper($namagroupkelas);

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
	        <thead style="background-color: lightyellow;">
	            <tr>
	                <th style="text-align: center;" width="5%">NO</th>
			        <th style="text-align: center;"> GROUP CLASS </th>
			        <th style="text-align: center;"> TITLE </th>
		          	<th style="text-align: center;"> DATE POSTED </th>
		          	<th style="text-align: center;"> LOOK DAILY </th>
	            </tr>
	        </thead>
	        <tbody>

	        	<?php $dataEmpty = 1; ?>
        		<?php foreach ($dataActivityGroupFromTeacher as $data): ?>
			      	
			      	<tr style="background-color: limegreen; color: white; font-weight: bold;">
				        <td style="text-align: center;">  <?= $no++; ?> </td>
				        <td style="text-align: center;">  <?= strtoupper($data['nama_group_kelas']) ?> </td>
				        <td style="text-align: center;">  <?= $data['judul_daily'] ?> </td>
				        <td style="text-align: center;">  <?= formatDateEnglish($data['daily_tanggal_disetujui_atau_tidak']); ?> </td>
				        <td style="text-align: center;">
					        <form action="lookactivity/<?= $data['room_key']; ?>" method="post">
					        	<input type="hidden" name="frompage_lookdaily" value="<?= $diMenu; ?>">
					        	<input type="hidden" name="roomkey_group_lookdaily" value="<?= $data['room_key']; ?>">
					        	<input type="hidden" name="id_group_approved" value="<?= $data['id_group_approved']; ?>">
					        	<input type="hidden" name="nis_or_idgroup_lookdaily" value="<?= $data['id_group_kelas']; ?>"> 
					        	<input type="hidden" name="nama_siswa_or_groupkelas_lookdaily" value="<?= strtoupper($data['nama_group_kelas']); ?>">
					        	<input type="hidden" name="guru_lookdaily" value="<?= strtoupper($data['nama_guru']); ?>">
					        	<input type="hidden" name="foto_upload_lookdaily" value="<?= strtoupper($data['foto_upload']); ?>">
					        	<input type="hidden" name="tgl_posting_lookdaily" value="<?= format_tgl_indo($data['daily_tanggal_disetujui_atau_tidak']); ?>">
					        	<input type="hidden" name="nipguru_lookdaily" value="<?= $data['nip_guru']; ?>">
					        	<input type="hidden" name="tglori_posting_lookdaily" value="<?= $data['daily_tanggal_disetujui_atau_tidak']; ?>">
					        	<input type="hidden" name="jdl_posting_lookdaily" value="<?= $data['judul_daily']; ?>">
					        	<input type="hidden" name="isi_posting_lookdaily" value="<?= $data['isi_daily']; ?>">
					        	<button class="btn btn-sm btn-primary" style="text-align: center;" type="submit" name="daily_group"> <i class="glyphicon glyphicon-eye-open"></i> LOOK DAILY </button>
					        </form>
					    </td>
			      	</tr>

		      	<?php endforeach ?>

	        </tbody>
	    </table>

	    <div class="row" style="float: right; margin-top: 10px; margin-right: 5px;">
					
			<div class="col-sm-3">
        		<button class="btn btn-sm btn-primary" id="backto_querydailygroup"> <span class="glyphicon glyphicon-log-out" style="transform: scaleX(-1);"></span> Kembali </button>	
				<br>
			</div>

		</div>

	  </div>
	</div>

<!-- <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script> -->
<script src="view/daily/query/dataTables1.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script> -->
<script src="view/daily/query/moment.min.js"></script>
<!-- <script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script> -->
<script src="view/daily/query/dateTime.min.js"></script>

<script type="text/javascript">
	
	let minDate, maxDate;
 
		 // Custom filtering function which will search data in column four between two values
		DataTable.ext.search.push(function (settings, data, dataIndex) {
			let min = minDate.val();
		    let max = maxDate.val();
		    let date = new Date(data[4]);

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
		  let table = new DataTable('#example');
		   
		  // Refilter the table
		  document.querySelectorAll('#min, #max').forEach((el) => {
		      el.addEventListener('change', () => table.draw());
		  });

</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">

	if (`<?= $countDataActivity; ?>` == 0 && `<?= $sesi; ?>` != 2) {
		Swal.fire({
	        title: 'TIDAK ADA DAILY GROUP CLASS <?= $namagroupkelas; ?> YANG DIBUAT OLEH <?= $namaguru; ?> !',
	        icon: "warning"
      	});
	}
	
	$(document).ready( function () {

    	let sesiForm = `<?= $sesi; ?>`

    	if (sesiForm == 2) {
    		const noData = setTimeout(showPopUpNoData, 1000);
    	}

	    $("#aList1").click();

	    setTimeout(clickSubMenu, 500);

	    function clickSubMenu() {
		    $("#isiListQuery").click();
		    $("#query_data_group").css({
		        "background-color" : "#ccc",
		        "color" : "black"
		    });
	    }

	    $("#backto_querydailygroup").click(function(){
	    	location.href = `<?= $basegu; ?>querydailygroup`
	    });

	    let titleLists1   = document.getElementById('titleList1').innerHTML

		let newIcon = document.getElementById("addIcon");
		newIcon.classList.remove("fa");
		newIcon.classList.add("glyphicon");
		newIcon.classList.add("glyphicon-zoom-in");

		let getTitleList1 = document.getElementById('isiList2').innerHTML;
		$("#isiMenu").css({
			"margin-left" : "5px"
		});

		$("#spanIsiNama").css({
			"font-weight" : "bold"
		});

		document.getElementById('isiMenu').innerHTML =  `<span style="font-weight: bold;"> QUERY </span>`+ ' - <span style="font-weight: bold;"> TEACHER CREATE DAILY </span> - ' + `<span style="font-weight: bold;"> GROUP <?= $upperName; ?> </span>` 

	    function showPopUpNoData() {
	      Swal.fire({
	        title: 'TIDAK ADA GROUP CLASS YANG DIPILIH!',
	        icon: "warning"
	      });

	      setTimeout(redirectToPageDailyStudent, 1200);
	      
	    }

	    function redirectToPageDailyStudent() {
	    	location.href = `<?= $basegu; ?>querydailygroup`;
	    }

	    function showPopUp() {
	      Swal.fire({
	        title: 'TIME IS OUT',
	        icon: "warning"
	      });

	      setTimeout(clearSession, 1200);
	      
	    }

	    function clearSession() {
	      $.ajax({
	        url : `../../../admin.php`,
	        type : 'POST',
	        data : {
	          clearSess : "out"
	        },
	        success:function(data) {
	          let checkDataOut = JSON.parse(data).clear
	          if(checkDataOut == true) {
	            document.location.href = `<?= $base; ?>login`
	          }
	        }

	      })
	    }

  	});

</script>