<?php  

  $timeOut        = $_SESSION['expire_paud'];
    
  $timeRunningOut = time() + 5;

  $timeIsOut = 0;
  $no        = 1;
  $diMenu    = "querydailysiswa";
  // echo $_SESSION['c_kepsek'];exit;

  $str            = $_SESSION['c_guru_paud'];
  $patternSD      = "/SD/i";
  $checkDataSD    = preg_match($patternSD, $str);

  $str1           = $_SESSION['c_guru_paud'];
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

      // $dataGroupDaily = mysqli_query($con, "
      //   SELECT id, nama_group_kelas, walas
      //   FROM group_kelas
      //   WHERE nip IN (
      //     SELECT nip FROM guru WHERE c_jabatan LIKE '%SD%'
      //   )
      //   ORDER BY nama_group_kelas ASC
      // ");

      $dataGroupDaily = mysqli_query($con, "
        SELECT id, nama_group_kelas, walas FROM group_kelas WHERE nip = '$_SESSION[nip_guru_paud]'
      ");

    } else if ($checkDataPAUD == 1) {

      // $dataGroupDaily = mysqli_query($con, "
      //   SELECT id, nama_group_kelas, walas
      //   FROM group_kelas
      //   WHERE nip IN (
      //     SELECT nip FROM guru WHERE c_jabatan LIKE '%PAUD%'
      //   )
      //   ORDER BY nama_group_kelas ASC
      // ");

      $dataGroupDaily = mysqli_query($con, "
        SELECT id, nama_group_kelas, walas FROM group_kelas WHERE nip = '$_SESSION[nip_guru_paud]'
      ");

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
        <tr style="background-color: lightyellow; font-weight: bold;">
          <th style="text-align: center; border: 1px solid black;" width="5%">NO</th>
          <th style="text-align: center; border: 1px solid black;"> GROUP KELAS </th>
          <th style="text-align: center; border: 1px solid black;"> WALI KELAS </th>
          <th style="text-align: center; border: 1px solid black;"> DAILY </th>
          <!-- <th style="text-align: center;"> DAILY </th> -->
          <!-- Terdapat Administrasi Pembiayaan Yang Perlu Di Selesaikan -->
        </tr>
      </thead>

      <tbody>

        <?php foreach ($dataGroupDaily as $data): ?>
          
          <tr>
            <td style="text-align: center;"> <?= $no++; ?> </td>
            <td style="text-align: center;"> <?= $data['nama_group_kelas']; ?> </td>
            <td style="text-align: center;"> <?= str_replace([" S.Pd.I", "S.Pd", ",", " S.Sos", " S.Ag", " Lc.", "  A.Md", " S.Si", " M.Pd", " S.Psi."], "", $data['walas']); ?> </td>

            <td style="text-align: center;"> 
              <form action="teachercreategroupdaily" method="post">
                <input type="hidden" name="id_group" value="<?= $data['id']; ?>">
                <input type="hidden" name="nama_group_kelas" value="<?= $data['nama_group_kelas']; ?>">
                <button class="btn btn-sm btn-primary" type="submit" name="send_data_group"> <i class="glyphicon glyphicon-eye-open"></i> DAILY </button> 
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
    $("#isiListQuery").click();
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