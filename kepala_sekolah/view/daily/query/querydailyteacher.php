<?php  

  $timeOut        = $_SESSION['expire_paud'];
    
  $timeRunningOut = time() + 5;

  $timeIsOut   = 0;

  $diMenu         = "querydailysiswa";
  $nipKepsek      = $_SESSION['nip_kepsek'];
  $departemen     = "";
  $dataAllTeacher = [];

  $str            = $_SESSION['c_kepsek_paud'];
  $patternSD      = "/SD/i";
  $checkDataSD    = preg_match($patternSD, $str);

  $str1           = $_SESSION['c_kepsek_paud'];
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

  // echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

  if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

    $_SESSION['form_success'] = "session_time_out";

    $timeIsOut = 1;
    error_reporting(1);
      // exit;

  } else {

    if ($nipKepsek == '2019032') {
      $departemen = "SD";
    } elseif ($nipKepsek == '2019034') {
      $departemen = "PAUD";
    }

    if ($departemen == "SD") {

      $dataAllTeacher = mysqli_query($con, "
        SELECT * FROM guru 
        WHERE nip <> '2019032'
        AND c_jabatan LIKE '%SD%' 
        OR c_jabatan = 'Kepala_Unit Poros Masjid'
        ORDER BY nama ASC  
      ");

    } elseif ($departemen == "PAUD") {

      $dataAllTeacher = mysqli_query($con, "
        SELECT * FROM guru 
        WHERE nip <> '2019034'
        AND c_jabatan LIKE '%PAUD%' 
        OR c_jabatan = 'Kepala_Unit Poros Masjid'

        ORDER BY nama ASC  
      ");

    }

  }

?>

<div class="box box-info">
  <div class="box-body table-responsive">

    <table id="list_tc" class="table table-bordered table-hover">
      <thead>
        <tr style="background-color: lightyellow; font-weight: bold;">
          <th style="text-align: center; border: 1px solid black;" width="5%">NO</th>
          <!-- <th style="text-align: center;">NIS</th> -->
          <th style="text-align: center; border: 1px solid black;">NAME</th>
          <th style="text-align: center; border: 1px solid black;">TEACHER</th>
          <th style="text-align: center; border: 1px solid black;">ACTION</th>
        </tr>
      </thead>

      <tbody>
        <?php $no = 1; ?>
        <?php foreach($dataAllTeacher as $tc) : ?>
          <tr>
            <td style="text-align: center;"> <?= $no++; ?> </td>
            <td style="text-align: center;"> <?= str_replace([", S.PD"], "", strtoupper($tc['nama'])); ?> </td>
            <td style="text-align: center;"> <?= str_replace(["_"], " ", strtoupper($tc['c_jabatan'])); ?> </td>
            <td style="text-align: center;"> 
              <form action="createdailybyteacher" method="post">
                <input type="hidden" name="nama_guru" value="<?= strtoupper($tc['nama']); ?>">
                <input type="hidden" name="nip" value="<?= $tc['nip']; ?>">
                <button type="submit" name="send_data_student" class="btn btn-sm btn-primary"> LOOK </button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>

    </table>
  </div>
</div>
   
<script type="text/javascript">

  let titleLists1   = document.getElementById('titleList1').innerHTML

  let newIcon = document.getElementById("addIcon");
  newIcon.classList.remove("fa");
  newIcon.classList.add("glyphicon");
  newIcon.classList.add("glyphicon-zoom-in");

  let getTitleList1 = document.getElementById('isiList2').innerHTML;

  $(document).ready(function() {

    $("#aList1").click();
    $("#isiList2").click();
    $("#query_data_guru").css({
        "background-color" : "#ccc",
        "color" : "black"
    });

    $("#isiMenu").css({
      "margin-left" : "5px"
    });

  })

  document.getElementById('isiMenu').innerHTML = `<span style="font-weight: bold;"> QUERY - </span>` + `<span style="font-weight: bold;"> TEACHER </span>`

</script>