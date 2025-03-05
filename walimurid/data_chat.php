<?php  

	require '../php/config.php'; 

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

  	function tgl_indo($date){  
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
	    $result = $tanggal ." ". $bulan ." ". $tahun;       
	    return($result);  
  	}

  	$arr                  = [];
  	$arrOther             = [];
  	$arrMe                = [];
  
  	if (isset($_POST['room_id'])) {
        
	    $room_id  	= $_POST['room_id'];
	    $users    	= $_POST['users'];
	    $nipGuru 	= $_POST['nip_guru'];
	    $nipKepsek  = $_POST['nip_kepsek'];

	    $getDataChatOther = mysqli_query($con, "
	      SELECT 
	      tbl_komentar.room_id as r_id,
	      tbl_komentar.code_user as from_nip,
	      guru.nama as nama_guru,
	      siswa.nama as nama_siswa,
	      kepala_sekolah.nama as nama_kepsek,
	      tbl_komentar.stamp as tanggal_kirim,
	      tbl_komentar.isi_komentar as pesan
	      FROM 
	      tbl_komentar 
	      LEFT JOIN ruang_pesan
	      ON tbl_komentar.room_id = ruang_pesan.room_key
	      LEFT JOIN guru
	      ON tbl_komentar.code_user = guru.nip
	      LEFT JOIN daily_siswa_approved
	      ON ruang_pesan.daily_id = daily_siswa_approved.id
	      LEFT JOIN akses_otm
	      ON tbl_komentar.code_user = akses_otm.nis_siswa
	      LEFT JOIN siswa
	      ON akses_otm.nis_siswa = siswa.nis
	      LEFT JOIN kepala_sekolah
	      ON tbl_komentar.code_user = kepala_sekolah.nip
	      WHERE 
	      ruang_pesan.room_key LIKE '%$room_id%'
	      ORDER BY tbl_komentar.id
	    ");

	    $queryDataTglPosting = mysqli_query($con, "
	     	SELECT
            ruang_pesan.room_key AS room_key,
            ruang_pesan.daily_id AS id_daily,
            daily_siswa_approved.tanggal_disetujui_atau_tidak AS tanggal_diposting
            FROM ruang_pesan
            LEFT JOIN daily_siswa_approved
            ON ruang_pesan.daily_id = daily_siswa_approved.id
            WHERE 
			ruang_pesan.created_by = '$nipGuru'
            AND ruang_pesan.room_key = '$room_id'
	    ");

	    $getDataFrom_MySelf = mysqli_query($con, "
	      SELECT 
	      room_key as r_key,
	      tbl_komentar.room_id as room_id,
	      tbl_komentar.isi_komentar as pesan,
	      tbl_komentar.code_user as from_pesan,
	      tbl_komentar.stamp as tanggal_kirim,
	      guru.nama as nama_guru
	      FROM 
	      ruang_pesan
	      LEFT JOIN tbl_komentar
	      ON tbl_komentar.room_id = ruang_pesan.room_key
	      LEFT JOIN guru
	      ON tbl_komentar.code_user = guru.nip
	      WHERE 
	      ruang_pesan.room_key LIKE '%$room_id%'
	      ORDER BY tbl_komentar.id
	    ");

	    $getDataAll   		= mysqli_fetch_array($getDataChatOther);
	    $getDataSelf  		= mysqli_fetch_array($getDataFrom_MySelf);
	    $getDataTglPosting  = mysqli_fetch_array($queryDataTglPosting);

	    $count        = mysqli_num_rows($getDataChatOther);
	    $fromAll      = [];
	    $fromMe       = [];
	    $pesanSemua   = [];
	    $pesanSaya    = [];

	    foreach ($getDataChatOther as $all_data) {

	      $fromAll[] = $all_data['nama_guru'];

	      if ($all_data['from_nip'] == $users) {
	        
	        $pesanSemua[] = '
	        <div class="direct-chat-msg right">
	          <div class="direct-chat-info clearfix">
	            <span class="direct-chat-name pull-right">'. strtoupper($all_data['nama_siswa']) .'</span>
	            <span class="direct-chat-timestamp pull-left">'. tgl_indo($all_data['tanggal_kirim']) . ' ' . substr($all_data['tanggal_kirim'], 11, 19) . '</span>
	          </div>
	          <img class="direct-chat-img" src="'. $base . 'imgstatis/icon_chat.png'. '" alt="Message User Image">
	          <div class="direct-chat-text">'. htmlspecialchars($all_data['pesan']) .'</div>
	          </div>
	        </div>';

	      } else if ($all_data['from_nip'] == $nipKepsek) {

	        $pesanSemua[] = '
	          <div class="direct-chat-msg">
	            <div class="direct-chat-info clearfix">
	              <span class="direct-chat-name pull-left">'. $all_data['nama_kepsek'] .'</span>
	              <span class="direct-chat-timestamp pull-right">'. tgl_indo($all_data['tanggal_kirim']) . ' ' . substr($all_data['tanggal_kirim'], 11, 19)  .'</span>
	            </div>
	            <img class="direct-chat-img" src="'. $base . 'imgstatis/icon_chat.png'. '" alt="Message User Image">
	            <div class="direct-chat-text">'. htmlspecialchars($all_data['pesan']) .'</div>
	          </div>';

	      } else if ($all_data['from_nip'] == $nipGuru) {

	      	$pesanSemua[] = '
	          <div class="direct-chat-msg">
	            <div class="direct-chat-info clearfix">
	              <span class="direct-chat-name pull-left">'. $all_data['nama_guru'] .'</span>
	              <span class="direct-chat-timestamp pull-right">'. tgl_indo($all_data['tanggal_kirim']) . ' ' . substr($all_data['tanggal_kirim'], 11, 19)  .'</span>
	            </div>
	            <img class="direct-chat-img" src="'. $base . 'imgstatis/icon_chat.png'. '" alt="Message User Image">
	            <div class="direct-chat-text">'. htmlspecialchars($all_data['pesan']) .'</div>
	          </div>';

	      }

	    }

	    foreach ($getDataFrom_MySelf as $myself) {
	      $fromMe[]  = $myself['nama_guru'];
	      $pesanSaya[] = '
	        <div class="direct-chat-msg right">
	          <div class="direct-chat-info clearfix">
	            <span class="direct-chat-name pull-right">'. $myself['nama_guru'] .'</span>
	            <span class="direct-chat-timestamp pull-left">'. tgl_indo($myself['tanggal_kirim']) . ' ' . substr($myself['tanggal_kirim'], 11, 19)  . '</span>
	          </div>
	          <img class="direct-chat-img" src="'. $base . 'imgstatis/icon_chat.png'. '" alt="Message User Image">
	          <div class="direct-chat-text">'. htmlspecialchars($myself['pesan']) .'</div>
	          </div>
	        </div>'
	      ;
	    }

	    for ($i=0; $i < count($pesanSemua); $i++) { 
	      $arrOther[] = $pesanSemua[$i];
	    }

	    for ($i=0; $i < count($pesanSaya); $i++) { 
	      $arrMe[] = $pesanSaya[$i];
	    }    

  	}

  	// Message Chat
	  // $arr['dari_orang_Lain']         = $getDataAll['nama_guru'];
	  // $arr['dari_saya']               = $getDataSelf['nama_guru'];
	  $arr['count']                 = $count;
	  $arr['isi_chat']              = $arrOther;
	  $arr['dari_chat']             = $fromAll;
	  $arr['isi_chatx']             = $arrMe;
	  $arr['dari_chatx']            = $fromMe;
	  $arr['jumlah_chat'] 			= mysqli_num_rows($getDataChatOther);
	  $arr['tgl_posting'] 			= $getDataTglPosting['tanggal_diposting']; 	
  	// End Message Chat

	echo json_encode($arr);

?>