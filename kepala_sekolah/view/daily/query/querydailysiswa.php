<?php  

  $timeOut        = $_SESSION['expire_paud'];
    
  $timeRunningOut = time() + 5;

  $timeIsOut = 0;

  $diMenu    = "querydailysiswa";
  // echo $_SESSION['c_kepsek_paud'];exit;

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

    if ($checkDataSD == 1) {

      $dataSiswa = mysqli_query($con,"SELECT * FROM siswa WHERE c_kelas != 'TKBLULUS' AND c_kelas != 'TKB' AND c_kelas != 'TKA' AND c_kelas != 'KB' order by nis asc");

    } else if ($checkDataPAUD == 1) {

      $dataSiswa = mysqli_query($con,"SELECT * FROM siswa WHERE c_kelas != '1SD' AND c_kelas != '2SD' AND c_kelas != '3SD' AND c_kelas != '4SD' AND c_kelas != '5SD' AND c_kelas != '6SD' AND c_kelas != 'TKBLULUS' order by nis asc ");

    }

  }

?>

<div class="box box-info">
  <div class="box-body table-responsive">

    <table id="list_siswa" class="table table-bordered table-hover">
      <thead>
        <tr style="background-color: lightyellow; font-weight: bold;">
          <th style="text-align: center; border: 1px solid black;" width="5%">NO</th>
          <!-- <th style="text-align: center;">NIS</th> -->
          <th style="text-align: center; border: 1px solid black;">NAME</th>
          <th style="text-align: center; border: 1px solid black;">GRADE</th>
          <th style="text-align: center; border: 1px solid black;"> DAILY </th>
        </tr>
      </thead>

      <tbody>
        <?php $no = 1; ?>
        <?php foreach($dataSiswa as $siswa) : ?>
          <tr>
            <td style="text-align: center;"> <?= $no++; ?> </td>
            <!-- <td style="text-align: center;"> <?= $siswa['nis']; ?> </td> -->
            <td style="text-align: center;"> <?= strtoupper($siswa['nama']); ?> </td>
            <td style="text-align: center;"> <?= str_replace(["SD"], " SD", $siswa['c_kelas']); ?> </td>
            <td style="text-align: center;"> 
              <form action="teachercreatedaily" method="post">
                <input type="hidden" name="nis" value="<?= $siswa['nis']; ?>">
                <input type="hidden" name="nama" value="<?= $siswa['nama']; ?>">
                <button class="btn btn-sm btn-primary" type="submit" name="send_data_student"> <i class="glyphicon glyphicon-eye-open"></i> DAILY </button> 
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
  newIcon.classList.add("fa");
  newIcon.classList.add("fa-user");

  let getTitleList1 = document.getElementById('isiList2').innerHTML;

  $(document).ready(function() {

    $("#aList1").click();
    $("#isiList2").click();
    $("#query_data_siswa").css({
        "background-color" : "#ccc",
        "color" : "black"
    });

    $("#isiMenu").css({
      "margin-left" : "5px"
    });

  })  

  document.getElementById('isiMenu').innerHTML = `<span style="font-weight: bold;"> QUERY - </span>` + `<span style="font-weight: bold;"> STUDENT </span>`

</script>