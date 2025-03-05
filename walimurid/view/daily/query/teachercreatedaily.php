<?php  

	$timeOut        = $_SESSION['expire'];
    
  	$timeRunningOut = time() + 5;

	$timeIsOut = 0;
	$sesi      = 0;
	$nama      = "";
	$jumlahData = 0;

	echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

	if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

		$_SESSION['form_success'] = "session_time_out";

	    $timeIsOut = 1;
	    error_reporting(1);

  	} else {

  		if (isset($_POST['nama'])) {
  			$nama = $_POST['nama'];
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
                <th style="text-align: center;">GURU</th>
                <th style="text-align: center;">SISWA</th>
                <th style="text-align: center;">PUBLISH</th>
                <th style="text-align: center;">DAILY</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">31231</td>
                <td style="text-align: center;">TEGUH IMAN</td>
                <td style="text-align: center;">ALVARO VIDI</td>
                <td style="text-align: center;">25 Januari 2024 08:19:19 </td>
                <td style="text-align: center;">
                	<form action="lookdaily" method="post">
                		<input type="hidden" name="nis" value="123xxx">
                		<input type="hidden" name="nama" value="System Architect">
                		<input type="hidden" name="guru" value="TEGUH IMAN">
                		<button type="submit" name="krm" class="btn btn-sm btn-primary"> Daily </button>
                	</form>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">31231</td>
                <td style="text-align: center;">AZMI</td>
                <td style="text-align: center;">ALVARO VIDI</td>
                <td style="text-align: center;">10 Februari 2024 08:19:19 </td>
                <td style="text-align: center;">
                	<form action="lookdaily" method="post">
                		<input type="hidden" name="nis" value="123xxx">
                		<input type="hidden" name="nama" value="System Architect">
                		<input type="hidden" name="guru" value="TEGUH IMAN">
                		<button type="submit" name="krm" class="btn btn-sm btn-primary"> Daily </button>
                	</form>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">312333999</td>
                <td style="text-align: center;">RAHMAT</td>
                <td style="text-align: center;">ALVARO VIDI</td>
                <td style="text-align: center;">01 Juli 2024 08:00:00</td>
                <td style="text-align: center;">
                	<form action="lookdaily" method="post">
                		<input type="hidden" name="nis" value="123xxx">
                		<input type="hidden" name="nama" value="System Architect">
                		<input type="hidden" name="guru" value="TEGUH IMAN">
                		<button type="submit" name="krm" class="btn btn-sm btn-primary"> Daily </button>
                	</form>
                </td>
            </tr>
            <tr>
                <td style="text-align: center;">777777</td>
                <td style="text-align: center;">Andri</td>
                <td style="text-align: center;">ALVARO VIDI</td>
                <td style="text-align: center;">01 Mei 2024 08:00:00</td>
                <td style="text-align: center;">
                	<form action="lookdaily" method="post">
                		<input type="hidden" name="nis" value="123xxx">
                		<input type="hidden" name="nama" value="ALVARO VIDI">
                		<input type="hidden" name="guru" value="Andri">
                		<button type="submit" name="krm" class="btn btn-sm btn-primary"> Daily </button>
                	</form>
                </td>
            </tr>
        </tbody>
        <tfoot>
        	<tr>
        		<th>
        			
        		</th>
        		<th>
        			
        		</th>
        		<th style="text-align: center;">
        			<br>
        			<form action="querydailysiswa" method="post">
		        		<button class="btn btn-sm btn-primary" type="submit"> <span class="glyphicon glyphicon-log-out" id="cancel"></span> Kembali </button>
		        	</form>
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

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script>

<script type="text/javascript">
	
	let minDate, maxDate;
 
		 // Custom filtering function which will search data in column four between two values
		DataTable.ext.search.push(function (settings, data, dataIndex) {
			let min = minDate.val();
		    let max = maxDate.val();
		    let date = new Date(data[3]);

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

		let timeIsOut = `<?= $timeIsOut; ?>`

	    if (timeIsOut == 1) {

	      const myTimeout = setTimeout(showPopUp, 1000);

	    } else {

	    	let sesiForm = `<?= $sesi; ?>`

	    	if (sesiForm == 1) {
	    		const noData = setTimeout(showPopUpNoData, 1000);
	    	}

		    $("#aList1").click();

		    setTimeout(clickSubMenu, 500);

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