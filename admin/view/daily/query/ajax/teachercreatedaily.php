<?php  

	require '../../../../../php/config.php';
	require '../../../../../php/function.php';

	$timeOut        = $_SESSION['expire'];
    
	$timeRunningOut = time() + 5;

	$timeIsOut = 0;
	$sesi      = 0;
	$nama      = "";
	$jumlahData = 0;

	$dailyApproved = "";

	echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

	if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

		$_SESSION['form_success'] = "session_time_out";

    $timeIsOut = 1;
    error_reporting(1);

	} else {

		if (isset($_POST['nis'])) {
			
			$nis 	= htmlspecialchars($_POST['nis']);
			$nama 	= htmlspecialchars($_POST['nama']);
			$dailyApproved = mysqli_query($con, "
				SELECT
				daily_siswa_approved.id as id_daily,
				from_nip as nip,
				guru.nama AS nama_guru,
				admin.username AS nama_admin,
				nis_siswa as nis_siswa,
				siswa.nama as nama_siswa,
				title_daily as title_daily,
				isi_daily as isi_daily,
				image as image_daily,
				tanggal_disetujui as tanggal_publish
				FROM daily_siswa_approved
				LEFT JOIN guru
				ON daily_siswa_approved.from_nip = guru.nip
				LEFT JOIN admin
				ON daily_siswa_approved.from_nip = admin.c_admin
				left join siswa
				on daily_siswa_approved.nis_siswa = siswa.nis
				WHERE 
				daily_siswa_approved.status_approve = 1
				and nis_siswa = '$nis'
			");

			$countData = mysqli_num_rows($dailyApproved);

			if ($countData == 0) {
				echo "<script> alert(`Belum ada data daily yang di buat dengan nama $nama`) </script>";
			}

		} else {
			
			$sesi      = 1;

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

    <table id="example" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th style="text-align: center;">NIP</th>
                <th style="text-align: center;">FROM</th>
                <th style="text-align: center;">SISWA</th>
                <th style="text-align: center;">PUBLISH</th>
                <th style="text-align: center;">DAILY</th>
            </tr>
        </thead>
        <tbody>
        	<?php foreach ($dailyApproved as $data): ?>
        			
        		<tr>
        			
        			<td style="text-align: center;"> <?= $data['nip']; ?> </td>
        			<?php if ($data['nama_admin'] != null && $data['nama_guru'] == null): ?>
        				<td style="text-align: center;"> <?= $data['nama_admin']; ?> </td>
    				<?php elseif($data['nama_guru'] != null && $data['nama_admin'] == null): ?>
    					<?php 
    						$namaAwal = explode(" ", $data['nama_guru']);
    					?>
        				<td style="text-align: center;"> GURU - <?= $namaAwal[0]; ?> </td>
        			<?php endif ?>
	                <td style="text-align: center;"> <?= $data['nama_siswa']; ?> </td>
	                <td style="text-align: center;"> <?= $data['tanggal_publish']; ?> </td>
	                <td style="text-align: center;">
	            		<button type="submit" onclick="lookDaily(`<?= $data['id_daily']; ?>`, `<?= $data['nis_siswa']; ?>`, `<?= $data['nip']; ?>`)" name="krm" class="btn btn-sm btn-primary"> Daily </button>
	                </td>

        		</tr>

        	<?php endforeach ?>
            
        </tbody>
        <tfoot>
        	<tr>
        		<th>
        			
        		</th>
        		<th>
        			
        		</th>
        		<th style="text-align: center;">
        			<br>
        			<!-- <form action="querydailysiswa" method="post"> -->
		        		<button class="btn btn-sm btn-primary" onclick="backQueryDaily()"> <span class="glyphicon glyphicon-log-out" id="cancel"></span> Kembali </button>
		        	<!-- </form> -->
        		</th>
        		<th>
        			
        		</th>
        		<th>
        			
        		</th>
        	</tr>
        </tfoot>
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
		    var date = new Date(data[3]);

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
	
	$(document).ready( function () {

		let timeIsOut = `<?= $timeIsOut; ?>`

	    if (timeIsOut == 1) {

	      const myTimeout = setTimeout(showPopUp, 1000);

	    } else {

	    	let sesiForm = `<?= $sesi; ?>`

	    	if (sesiForm == 1) {
	    		const noData = setTimeout(showPopUpNoData, 1000);
	    	}

		    // $("#aList1").click();

		    // setTimeout(clickSubMenu, 500);

		    $('#bgnNama').click(function(){
			    var opnDiv = document.getElementById("bgnNama");
			    for (var i = 0; i < opnDiv.length; i++) {
			      if (opnDiv[i].className == 'dropdown user user-menu') {
			        alert("atas")
			        // var addClassPageNama  = document.getElementById("bgnNama");
			        // addClassPageNama.classList.add("open")
			      } else if(opnDiv[i].className == 'dropdown user user-menu open') {
			        // var addClassPageNama  = document.getElementById("bgnNama");
			        // addClassPageNama.classList.remove("open")
			      }
			    }

			    
			})

		    function clickSubMenu() {
		      $("#isiList2").click();
		      $("#query_data_siswa").css({
		          "background-color" : "#ccc",
		          "color" : "black"
		      });
		    }

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

	    }

	    

	    function showPopUpNoData() {
	      Swal.fire({
	        title: 'TIDAK ADA DATA MURID YANG DIPILIH!',
	        icon: "warning"
	      });

	      setTimeout(closePage, 1200);
	      
	    }

	    function closePage() {
	    	window.close();
	    }

  	});

  	function showPopUp() {
      Swal.fire({
        title: 'TIME IS OUT',
        icon: "warning"
      });

      setTimeout(clearSession, 1200);
      
    }

    function lookDaily(id_daily, nis, nip) {

    	$.post(`<?= $basead; ?>view/daily/query/lookdaily`, {
    		is_teacher  	: true,
    		id_daily        : id_daily,
    		nip 			: nip,
    		nis 			: nis
    	}, function(data) {
  			$("#isi_konten").html(data)
  		});

    }

    function clearSession() {
      $.ajax({
        url : `<?= $basead; ?>admin.php`,
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

  	function backQueryDaily() {

  		$.get('view/daily/query/ajax/querydailysiswa.php', function(data) {
  			$("#isi_konten").html(data)
  		});

  	}

</script>