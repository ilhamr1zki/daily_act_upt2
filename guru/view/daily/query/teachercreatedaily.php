<?php  

	$timeOut        = $_SESSION['expire'];
    
  	$timeRunningOut = time() + 5;

	$timeIsOut 		= 0;
	$sesi      		= 0;
	$nama      		= "";
	$jumlahData 	= 0;
	$countData 		= 0;

	$no 			= 1;

	$nip_guru      	= $_SESSION['nip_guru'];
	$isTeacher 		= $_SESSION['nama_guru']; 

	$diMenu     	= "teachercreatedaily";

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

  		if (isset($_POST['send_data_student'])) {
  			
  			$nis 		   = $_POST['nis'];
  			$nama 		   = $_POST['nama'];

  			$dataActivityFromTeacher = mysqli_query($con, "
	  			SELECT
	  			guru.nama as nama_guru,
	  			guru.nip as nip_guru,
	  			siswa.nis as nis_siswa,
	  			siswa.nama as nama_siswa,
	  			daily_siswa_approved.nis_siswa as daily_nis_siswa,
	  			daily_siswa_approved.image as foto_upload,
	  			daily_siswa_approved.title_daily as judul_daily,
	  			daily_siswa_approved.isi_daily as isi_daily,
	  			daily_siswa_approved.tanggal_disetujui_atau_tidak as daily_tanggal_disetujui_atau_tidak,
	  			ruang_pesan.room_key as room_key
	  			FROM daily_siswa_approved
	  			LEFT JOIN guru
	  			ON daily_siswa_approved.from_nip = guru.nip
	  			LEFT JOIN siswa
	  			ON daily_siswa_approved.nis_siswa = siswa.nis
	  			LEFT JOIN ruang_pesan
	  			ON daily_siswa_approved.id = ruang_pesan.daily_id
	  			WHERE daily_siswa_approved.status_approve = 1
	  			AND daily_siswa_approved.from_nip = '$nip_guru'
	  			AND daily_siswa_approved.nis_siswa = '$nis'
	  			ORDER BY daily_siswa_approved.tanggal_disetujui_atau_tidak DESC
	  		");

  			$countData = mysqli_num_rows($dataActivityFromTeacher);

  		} else {

  			$dataActivityFromTeacher = [];
  			$sesi      = 1;
  			$countData;
  		}

  		$jumlahData = 5;

  	}

  	$upperName = strtoupper($nama);

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
			        <th style="text-align: center;"> STUDENT </th>
			        <th style="text-align: center;"> TITLE </th>
		          	<th style="text-align: center;"> PUBLISH DATE </th>
		          	<th style="text-align: center;"> LOOK DAILY </th>
	            </tr>
	        </thead>
	        <tbody>
	        	
	        	<?php foreach ($dataActivityFromTeacher as $data): ?>
				      	
			      	<tr style="background-color: limegreen; color: white; font-weight: bold;">
				        <td style="text-align: center;">  <?= $no++; ?> </td>
				        <td style="text-align: center;">  <?= strtoupper($data['nama_siswa']) ?> </td>
				        <td style="text-align: center;">  <?= $data['judul_daily'] ?> </td>
				        <td style="text-align: center;">  <?= formatDateEnglish($data['daily_tanggal_disetujui_atau_tidak']); ?> </td>
				        <td style="text-align: center;">
					        <form action="lookactivity/<?= $data['room_key']; ?>" method="post">
					        	<input type="hidden" name="frompage" value="<?= $diMenu; ?>">
					        	<input type="hidden" name="roomkey" value="<?= $data['room_key']; ?>">
					        	<input type="hidden" name="nis" value="<?= strtoupper($data['nis_siswa']); ?>">
					        	<input type="hidden" name="nama" value="<?= strtoupper($data['nama_siswa']); ?>">
					        	<input type="hidden" name="guru" value="<?= strtoupper($data['nama_guru']); ?>">
					        	<input type="hidden" name="foto" value="<?= strtoupper($data['foto_upload']); ?>">
					        	<input type="hidden" name="tglpost" value="<?= format_tgl_indo($data['daily_tanggal_disetujui_atau_tidak']); ?>">
					        	<input type="hidden" name="tglori" value="<?= $data['daily_tanggal_disetujui_atau_tidak']; ?>">
					        	<input type="hidden" name="judul" value="<?= $data['judul_daily']; ?>">
					        	<input type="hidden" name="isi" value="<?= $data['isi_daily']; ?>">
					        	<button class="btn btn-sm btn-primary" style="text-align: center;" type="submit" name="krm"> <i class="glyphicon glyphicon-eye-open"></i> LOOK DAILY </button>
					        </form>
					    </td>
			      	</tr>

		      	<?php endforeach ?>
	            
	        </tbody>
	    </table>

    	<div class="row" style="float: right; margin-top: 10px; margin-right: 5px;">
					
			<div class="col-sm-3">
        		<button class="btn btn-sm btn-primary" id="backto_querydailystd"> <span class="glyphicon glyphicon-log-out" id="cancel"></span> Kembali </button>	
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

<script type="text/javascript">
	
	$(document).ready( function () {

    	let sesiForm = `<?= $sesi; ?>`

    	if (sesiForm == 1) {
    		const noData = setTimeout(showPopUpNoData, 1000);
    	}

	    $("#aList1").click();

	    setTimeout(clickSubMenu, 500);

	    if (`<?= $countData; ?>` == 0) {
	    	Swal.fire({
		        title: `TIDAK ADA DAILY SISWA <?= $upperName; ?> YANG DIBUAT OLEH <?= $isTeacher; ?> !`,
		        icon: "warning"
	      	});
	    }

	    function clickSubMenu() {
	      $("#isiListQuery").click();
	      $("#query_data_siswa").css({
	          "background-color" : "#ccc",
	          "color" : "black"
	      });
	    }

	    $("#backto_querydailystd").click(function(){
	    	location.href = `<?= $basegu; ?>querydailystudent`
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

		document.getElementById('isiMenu').innerHTML =  `<span style="font-weight: bold;"> QUERY </span>`+ ' - <span style="font-weight: bold;"> TEACHER CREATE DAILY </span> - ' + `<span style="font-weight: bold;"> <?= $upperName; ?> </span>` 

	    function showPopUpNoData() {
	      Swal.fire({
	        title: 'TIDAK ADA DATA MURID YANG DIPILIH!',
	        icon: "warning"
	      });

	      setTimeout(redirectToPageDailyStudent, 1200);
	      
	    }

	    function redirectToPageDailyStudent() {
	    	location.href = `<?= $basegu; ?>querydailystudent`;
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