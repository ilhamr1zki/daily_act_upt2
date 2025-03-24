<?php  

  $timeOut        = $_SESSION['expire_paud'];
    
  $timeRunningOut = time() + 5;

  $timeIsOut = 0;
  $no        = 1;
  $diMenu    = "querydailysiswa";
  // echo $_SESSION['c_kepsek'];exit;

  $str            = $_SESSION['bag_siswa_paud'];
  $patternSD      = "/SD/i";
  $checkDataSD    = preg_match($patternSD, $str);

  $str1           = $_SESSION['bag_siswa_paud'];
  $patternTK      = "/PAUD/i";
  $checkDataPAUD  = preg_match($patternTK, $str1);

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

  $nipKepsek = "";

  // echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

  if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

    $_SESSION['form_success'] = "session_time_out";

    $timeIsOut = 1;
    error_reporting(1);
      // exit;

  } else {

  	// Cari Id Group Kelas berdasarkan NIS
  	$queryFindIdGroup = mysqli_query($con, "
  		SELECT group_kelas FROM siswa WHERE nis = '$_SESSION[c_otm_paud]'
  	");

  	$getIdGroup = mysqli_fetch_assoc($queryFindIdGroup)['group_kelas'];
    // echo $getIdGroup;exit;

  	// Cari Apakah Daily Group APproved dengan id Group Kelas yang telah di tentukan
  	$groupApproved = mysqli_query($con, "
  		SELECT 
  		group_siswa_approved.from_nip as from_nip,
  		group_siswa_approved.title_daily as judul_daily, 
  		group_siswa_approved.isi_daily as isi_daily,
  		group_siswa_approved.image as foto_upload,
      group_siswa_approved.group_kelas_id AS group_kelas_id,
  		group_siswa_approved.tanggal_disetujui_atau_tidak as tgl_posted,
  		guru.nama as nama_guru,
  		guru.nip as nip_guru,
  		group_kelas.nama_group_kelas as nama_group_kelas,
      ruang_pesan.room_key AS room_key
  		from group_siswa_approved 
  		LEFT JOIN guru 
  		ON group_siswa_approved.from_nip = guru.nip
  		LEFT JOIN group_kelas
  		ON group_siswa_approved.group_kelas_id = group_kelas.id
      LEFT JOIN ruang_pesan
      ON group_siswa_approved.id = ruang_pesan.daily_id
  		WHERE 
  		group_siswa_approved.group_kelas_id = '$getIdGroup' 
  		AND group_siswa_approved.status_approve = 1
      ORDER BY tgl_posted DESC
  	");

    if ($checkDataSD == 1) {

      $dataGroupDaily = mysqli_query($con, "
        SELECT id, nama_group_kelas, walas
        FROM group_kelas
        WHERE nip IN (
          SELECT nip FROM guru WHERE c_jabatan LIKE '%SD%'
        )
        ORDER BY nama_group_kelas ASC
      ");

      $nipKepsek = "2019032";

    } else if ($checkDataPAUD == 1) {

      $dataGroupDaily = mysqli_query($con, "
        SELECT id, nama_group_kelas, walas
        FROM group_kelas
        WHERE nip IN (
          SELECT nip FROM guru WHERE c_jabatan LIKE '%PAUD%'
        )
        ORDER BY nama_group_kelas ASC
      ");

      $nipKepsek = "2019034";

    }

  }

?>

<div class="box box-info">

  <center> 
    <h4 id="judul_daily">
      <strong> LIST GROUP KELAS </strong> 
    </h4> 
  </center>

  <br>

  <div class="box-body table-responsive">
    <table id="list_group" style="text-align: center;" class="table table-bordered table-hover">

      <thead>
        <tr style="background-color: grey; font-weight: bold; color: white;">
          <th style="text-align: center; border: 1px solid white;" width="5%">NO</th>
          <th style="text-align: center; border: 1px solid white;"> FROM TEACHER </th>
          <th style="text-align: center; border: 1px solid white;"> ACTIVITY TITLE </th>
          <th style="text-align: center; border: 1px solid white;"> DATE POSTED </th>
          <th style="text-align: center; border: 1px solid white;"> ACTION </th>
          <!-- Terdapat Administrasi Pembiayaan Yang Perlu Di Selesaikan -->
        </tr>
      </thead>

      <tbody>

        <?php foreach ($groupApproved as $data): ?>
          
          <tr style="background-color: greenyellow;">
            <td style="text-align: center;"> <?= $no++; ?> </td>
            <td style="text-align: center;"> <?= $data['nama_guru']; ?> </td>

            <?php if (strlen($data['judul_daily']) > 50): ?>

              <td style="text-align: center;"> <?= substr($data['judul_daily'], 0,50); ?> <strong> ... </strong> </td>

            <?php else: ?>

              <td style="text-align: center;"> <?= $data['judul_daily'] ?> </td>
              
            <?php endif ?>

            <td style="text-align: center;"> <?= format_tgl_indo($data['tgl_posted']); ?> </td>
            <td style="text-align: center;"> 
              <form action="lookactivity/<?= $data['room_key']; ?>" method="post">

                <input type="hidden" name="frompage_lookdaily" value="querydailygroup">
                <input type="hidden" name="roomkey_group_lookdaily" value="<?= $data['room_key']; ?>">
                <input type="hidden" name="nipguru_lookdaily" value="<?= $data['from_nip']; ?>">
                <input type="hidden" name="nipkepsek_lookdaily" value="<?= $nipKepsek; ?>">
                <input type="hidden" name="guru_lookdaily" value="<?= $data['nama_guru']; ?>">
                <input type="hidden" name="nis_or_idgroup_lookdaily" value="<?= $data['group_kelas_id']; ?>">
                <input type="hidden" name="nama_siswa_or_groupkelas_lookdaily" value="<?= $data['nama_group_kelas']; ?>">
                <input type="hidden" name="foto_upload_lookdaily" value="<?= $data['foto_upload']; ?>">
                <input type="hidden" name="tgl_posting_lookdaily" value="<?= format_tgl_indo($data['tgl_posted']); ?>">
                <input type="hidden" name="tglori_posting_lookdaily" value="<?= $data['tgl_posted']; ?>">
                <input type="hidden" name="jdl_posting_lookdaily" value="<?= $data['judul_daily']; ?>">
                <input type="hidden" name="isi_posting_lookdaily" value="<?= $data['isi_daily']; ?>">
                <button class="btn btn-sm btn-primary" type="submit" name="daily_group"> <i class="glyphicon glyphicon-eye-open"></i> DAILY </button> 
              </form>
            </td>

          </tr>

        <?php endforeach ?>
        
      </tbody>

    </table>
  </div>

</div>
   
<script type="text/javascript">

  let titleLists1   = document.getElementById('titleList1').innerHTML

  let newIcon = document.getElementById("addIcon");
  newIcon.classList.remove("fa");
  newIcon.classList.add("fa");
  newIcon.classList.add("fa-users");

  let getTitleList1 = document.getElementById('isiList2').innerHTML;

  $(document).ready(function() {

    $("#aList1").click();
    $("#isiList2").click();
    $("#query_data_group").css({
        "background-color" : "#ccc",
        "color" : "black"
    });

    $("#isiMenu").css({
      "margin-left" : "5px"
    });

  })  

  document.getElementById('isiMenu').innerHTML = `<span style="font-weight: bold;"> QUERY - </span>` + `<span style="font-weight: bold;"> GROUP CLASS </span>`

</script>