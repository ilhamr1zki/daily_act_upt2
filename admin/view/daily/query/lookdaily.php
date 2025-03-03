<?php  

	require '../../../../php/config.php';

	$timeOut        = $_SESSION['expire'];
    
  	$timeRunningOut = time() + 5;

	$timeIsOut = 0;
	$sesi      = 0;
	$nama      = "";
	$guru      = "";
	$siswa     = ""; 

	// echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

	if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

		$_SESSION['form_success'] = "session_time_out";

	    $timeIsOut = 1;
	    error_reporting(1);

  	} else {

  		if (isset($_POST['is_teacher'])) {
  			$sesi = 1;

		  	$dataID     = htmlspecialchars($_POST['id_daily']);
		  	$dataNIP  	= htmlspecialchars($_POST['nip']);
		  	// echo $dataNIP;exit;
		  	$dataNIS 		= htmlspecialchars($_POST['nis']);

		  	$checkDataADM 	= mysqli_query($con, "SELECT c_admin FROM admin WHERE c_admin = '$dataNIP' ");
		  	$checkDataGuru 	= mysqli_query($con, "SELECT nama FROM guru WHERE nip = '$dataNIP' ");

		  	$countADM       = mysqli_num_rows($checkDataADM);
		  	$countGuru      = mysqli_num_rows($checkDataGuru);
		  	// echo $countADM;exit;

		  	// check jika nip adalah admin
		  	if ($countADM == 1) {

		  		// Cari Data Approved berdasarkan nis siswa
			  	$queryGetApproved = mysqli_query($con, "
			  		SELECT 
			  		from_nip as from_nip,
			  		nis_siswa as nis_siswa,
			  		siswa.nama as nama_siswa,
			  		admin.username as created_by,
			  		image as gambar,
			  		tanggal_disetujui as tgl_publish,	
			  		title_daily as judul_daily,
			  		isi_daily as isi_daily
			  		from daily_siswa_approved
			  		left join admin
			  		on daily_siswa_approved.from_nip = admin.c_admin
			  		left join siswa
			  		on daily_siswa_approved.nis_siswa = siswa.nis
			  		where 
			  		daily_siswa_approved.id = '$dataID'
			  		AND
			  		daily_siswa_approved.status_approve = 1 
			  		AND 
			  		daily_siswa_approved.from_nip = '$dataNIP'
			  	");

			  	$getData	= mysqli_fetch_array($queryGetApproved);
			  	$siswa      = $getData['nama_siswa'];

		  	} else if ($countGuru == 1) {

		  		$queryGetApproved = mysqli_query($con, "
			  		SELECT 
			  		from_nip as from_nip,
			  		nis_siswa as nis_siswa,
			  		siswa.nama as nama_siswa,
			  		guru.nama as created_by,
			  		image as gambar,
			  		tanggal_disetujui as tgl_publish,
			  		title_daily as judul_daily,
			  		isi_daily as isi_daily
			  		from daily_siswa_approved
			  		left join guru
			  		on daily_siswa_approved.from_nip = guru.nip
			  		left join siswa
			  		on daily_siswa_approved.nis_siswa = siswa.nis
			  		where 
			  		daily_siswa_approved.id = '$dataID'
			  		AND
			  		daily_siswa_approved.status_approve = 1 
			  		AND 
			  		daily_siswa_approved.from_nip = '$dataNIP'
			  	");

			  	// $getDataIsiDaily 	= mysqli_fetch_array($queryGetApproved)['isi_daily'];
			  	$getData	= mysqli_fetch_array($queryGetApproved);
			  	$siswa      = $getData['nama_siswa'];
			  	// $getDataCreatedBy 	= mysqli_fetch_array($queryGetApproved)['nama_guru'];
			  	// echo $getData['judul_daily'];exit;
			  	// $getDataDatePublish	= mysqli_fetch_array($queryGetApproved)['tgl_publish'];

		  		

		  	} 

	  	} else {
	  		$sesi = 0;
	  		$_SESSION['data'] = 'nodata';
	  	}

  	}

?>

	<?php if ($sesi == 1): ?>
		
		<div class="row">
			<div class="col-md-7">
			    <!-- Box Comment -->
			    <div class="box box-widget">
			      <div class="box-header with-border">
			        <div class="user-block">
			        	<div class="place_img">
			        		<img class="img-circle" src="<?= $base; ?>theme/dist/img/logo2.png" alt="User Image">
			        	</div>
			        	<span class="username"> <?= $getData['created_by']; ?> </span>
			        	<span class="description"> Published Date - <?= $getData['tgl_publish']; ?> </span>
			        </div>
			        <!-- /.user-block -->
			      </div>
			      <!-- /.box-header -->
			      <div class="box-body">
			        <h4 style="color: blue;"> Title : <?= $getData['judul_daily']; ?> </h4>
			        <div class="isi_foto">
			        	<img class="img-responsive pad" src="<?= $base; ?>image_uploads/<?= $getData['gambar']; ?>" alt="Photo" style="width: auto; height: 300px;">	
			        </div>

			        <div id="isi_daily">
			        	<?= $getData['isi_daily']; ?>
			        	<!-- <h5> Kegiatan Alvaro Vidi pada tanggal 01 Juli 2024 : </h5>
				        <ul>
				          <li> Alvaro kurang ber semangat </li>
				          <li> Muroja'ah Al - Qur'an </li>
				          <li> Eskul Pramuka </li>
				          <li> Eskul Futsal </li>
				        </ul> -->
			        </div>
			        
			        <!-- <button type="button" class="btn btn-default btn-xs"><i class="fa fa-share"></i> Share</button>
			        <button type="button" class="btn btn-default btn-xs"><i class="fa fa-thumbs-o-up"></i> Like</button>
			        <span class="pull-right text-muted">127 likes - 3 comments</span> -->
			      </div>

			    </div>
			    <!-- /.box -->
			</div>
		  <!-- /.col -->
		  	<div class="col-md-5">
		    <!-- DIRECT CHAT SUCCESS -->
			    <div class="box box-primary direct-chat direct-chat-primary">
			      <div class="box-header with-border">
			        <h3 class="box-title"> Comments </h3>
			      </div>
			      <!-- /.box-header -->
			      <div class="box-body">
			        <!-- Conversations are loaded here -->
			        <div class="direct-chat-messages" style="height: 350px !important;">
			          <!-- Message. Default to the left -->
			          <div class="direct-chat-msg">
			            <div class="direct-chat-info clearfix">
			              <span class="direct-chat-name pull-left">Alexander Pierce</span>
			              <span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
			            </div>
			            <!-- /.direct-chat-info -->
			            <img class="direct-chat-img" src="<?= $base; ?>theme/dist/img/user1-128x128.jpg" alt="Message User Image"><!-- /.direct-chat-img -->
			            <div class="direct-chat-text">
			              Is this template really for free? That's unbelievable!
			            </div>
			            <!-- /.direct-chat-text -->
			          </div>
			          <!-- /.direct-chat-msg -->

			          <!-- Message to the right -->
			          <div class="direct-chat-msg right">
			            <div class="direct-chat-info clearfix">
			              <span class="direct-chat-name pull-right">Sarah Bullock</span>
			              <span class="direct-chat-timestamp pull-left">23 Jan 2:05 pm</span>
			            </div>
			            <!-- /.direct-chat-info -->
			            <img class="direct-chat-img" src="<?= $base; ?>theme/dist/img/user3-128x128.jpg" alt="Message User Image"><!-- /.direct-chat-img -->
			            <div class="direct-chat-text">
			              You better believe it! Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			              tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
			              quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
			              consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
			              cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
			              proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
			            </div>
			            <!-- /.direct-chat-text -->
			          </div>

			          <div class="direct-chat-msg">
			            <div class="direct-chat-info clearfix">
			              <span class="direct-chat-name pull-left">Alexander Pierce</span>
			              <span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
			            </div>
			            <!-- /.direct-chat-info -->
			            <img class="direct-chat-img" src="<?= $base; ?>theme/dist/img/user1-128x128.jpg" alt="Message User Image"><!-- /.direct-chat-img -->
			            <div class="direct-chat-text">
			              Is this template really for free? That's unbelievable!
			            </div>
			            <!-- /.direct-chat-text -->
			          </div>

			          <div class="direct-chat-msg right">
			            <div class="direct-chat-info clearfix">
			              <span class="direct-chat-name pull-right">Sarah Bullock</span>
			              <span class="direct-chat-timestamp pull-left">23 Jan 2:05 pm</span>
			            </div>
			            <!-- /.direct-chat-info -->
			            <img class="direct-chat-img" src="<?= $base; ?>theme/dist/img/user3-128x128.jpg" alt="Message User Image"><!-- /.direct-chat-img -->
			            <div class="direct-chat-text">
			              You better believe it! Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
			              tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.
			            </div>
			            <!-- /.direct-chat-text -->
			          </div>

			          <div class="direct-chat-msg">
			            <div class="direct-chat-info clearfix">
			              <span class="direct-chat-name pull-left">Alexander Pierce</span>
			              <span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
			            </div>
			            <!-- /.direct-chat-info -->
			            <img class="direct-chat-img" src="<?= $base; ?>theme/dist/img/user1-128x128.jpg" alt="Message User Image"><!-- /.direct-chat-img -->
			            <div class="direct-chat-text">
			              Is this template really for free? That's unbelievable!
			            </div>
			            <!-- /.direct-chat-text -->
			          </div>

			          <div class="direct-chat-msg">
			            <div class="direct-chat-info clearfix">
			              <span class="direct-chat-name pull-left">Alexander Pierce</span>
			              <span class="direct-chat-timestamp pull-right">23 Jan 2:00 pm</span>
			            </div>
			            <!-- /.direct-chat-info -->
			            <img class="direct-chat-img" src="<?= $base; ?>theme/dist/img/user1-128x128.jpg" alt="Message User Image"><!-- /.direct-chat-img -->
			            <div class="direct-chat-text">
			              Is this template really for free? That's unbelievable!
			            </div>
			            <!-- /.direct-chat-text -->
			          </div>
			          <!-- /.direct-chat-msg -->
			        </div>
			        <!--/.direct-chat-messages-->

			        <!-- Contacts are loaded here -->
			        <div class="direct-chat-contacts">
			          <ul class="contacts-list">
			            <li>
			              <a href="#">
			                <img class="contacts-list-img" src="<?= $base; ?>theme/dist/img/user1-128x128.jpg" alt="User Image">

			                <div class="contacts-list-info">
			                      <span class="contacts-list-name">
			                        Count Dracula
			                        <small class="contacts-list-date pull-right">2/28/2015</small>
			                      </span>
			                  <span class="contacts-list-msg">How have you been? I was...</span>
			                </div>
			                <!-- /.contacts-list-info -->
			              </a>
			            </li>
			            <!-- End Contact Item -->
			          </ul>
			          <!-- /.contatcts-list -->
			        </div>
			        <!-- /.direct-chat-pane -->
			      </div>
			      <!-- /.box-body -->
			      <div class="box-footer">
			        <form action="#" method="post">
			          <div class="input-group">
			            <input type="text" name="message" placeholder="Type Message ..." class="form-control">
			                <span class="input-group-btn">
			                  <button type="submit" class="btn btn-success btn-flat">Send</button>
			                </span>
			          </div>
			        </form>
			      </div>
			      <!-- /.box-footer-->
			    </div>
			    <!--/.direct-chat -->
		  	</div>
		  <!-- /.col -->
		</div>

		<div class="wrapx" style="display: flex; justify-content: flex-end;">

			<div class="row">
				
				<div class="col-md-3">
					<input type="hidden" id="nama_siswa_lookdaily" name="nama" value="<?= $nama; ?>">
	        		<button class="btn btn-sm btn-primary" type="submit" onclick="backToTeacherDaily(`<?= $dataNIS; ?>`, `<?= $siswa; ?>`)"> <span class="glyphicon glyphicon-log-out" id="cancel"></span> Kembali </button>
					<br>
				</div>

			</div>

		</div>

	<?php elseif($sesi == 0): ?>

		<div class="row">
		    <div class="col-xs-12 col-md-12 col-lg-12">

		        <?php if(isset($_SESSION['data']) && $_SESSION['data'] == 'nodata'){?>
		          <div style="display: none;" class="alert alert-danger alert-dismissable"> Tidak Ada Data Yang Di Kirim! 
		             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		             <?php 
		             	unset($_SESSION['data']);
		             ?>
		          </div>
		        <?php } ?>

		    </div>
		</div>

	<?php endif ?>

<script type="text/javascript">
	
	let session = `<?= $sesi; ?>` 
	
	$(document).ready( function () {

		if (session == 0) {
			setTimeout(linkRedirect, 1000);
		} 

	    let titleLists1   = ' - ACTIVITY ' + document.getElementById('titleList1').innerHTML
	    let elementWrap   = document.getElementById('boxTitle')
	    let nama_siswa    = `<?= $siswa; ?>`

		let newIcon = document.getElementById("addIcon");
		newIcon.classList.remove("fa");
		newIcon.classList.add("glyphicon");
		newIcon.classList.add("glyphicon-zoom-in");

		let getTitleList1 = document.getElementById('isiList2').innerHTML;
		$("#isiMenu").css({
			"margin-left" : "5px"
		});

		let createElementSpanNama = document.createElement('span')
		createElementSpanNama.id  = 'spanIsiNama'
		createElementSpanNama.textContent += `<?= $nama; ?>`

		elementWrap.appendChild(createElementSpanNama)

		$("#spanIsiNama").css({
			"font-weight" : "bold"
		});

		document.getElementById('isiMenu').innerHTML = `<span style="font-weight: bold;"> QUERY </span>` + `<span style="font-weight: bold;"> ${titleLists1} </span> ` + ' - ' + `<span style="font-weight: bold;"> ${nama_siswa} </span> `

		function linkRedirect() {
			window.close();
	    }

  	});

	function backToTeacherDaily(nis, nama) {
		$.post('view/daily/query/ajax/teachercreatedaily.php', {
			nis : nis,
			nama : nama
		}, function(data) {
			$("#spanIsiNama").remove();
			$("#isi_konten").html(data)
		});

	}

</script>