<?php  

  $timeOut        = $_SESSION['expire_paud'];
    
  $timeRunningOut = time() + 5;

  $timeIsOut = 0;

  // echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

  if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

    $_SESSION['form_success'] = "session_time_out";

    $timeIsOut = 1;
    error_reporting(1);
      // exit;

  } else {

    $str            = $_SESSION['c_guru_paud'];
    $patternSD      = "/SD/i";
    $checkDataSD    = preg_match($patternSD, $str);

    $str1           = $_SESSION['c_guru_paud'];
    $patternTK      = "/PAUD/i";
    $checkDataPAUD  = preg_match($patternTK, $str1);

    $dataSchollSD   = $checkDataSD . "SD";
    $dataSchollPAUD = $checkDataPAUD . "PAUD";

    if ($dataSchollSD == "1SD") {

      $dataSiswa = mysqli_query($con,"SELECT * FROM siswa WHERE c_kelas != 'TKBLULUS' AND c_kelas != 'TKB' AND c_kelas != 'TKA' AND c_kelas != 'KB' order by nis asc ");

    } elseif($dataSchollPAUD == "1PAUD") {

      $dataSiswa = mysqli_query($con,"SELECT * FROM siswa WHERE c_kelas != '1SD' AND c_kelas != '2SD' AND c_kelas != '3SD' AND c_kelas != '4SD' AND c_kelas != '5SD' AND c_kelas != '6SD' AND c_kelas != 'TKBLULUS' order by nis asc ");

    }

  }

?>

<div class="box box-info">
  <div class="box-body table-responsive">

    <table id="list_siswa1" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th style="text-align: center;" width="5%">NO</th>
          <th style="text-align: center;">NIS</th>
          <th style="text-align: center;">NAMA</th>
          <th style="text-align: center;">KELAS</th>
          <!-- <th style="text-align: center;">GENDER</th> -->
          <th style="text-align: center;"> DAILY </th>
        </tr>
      </thead>

      <tbody>
        <?php $no = 1; ?>
        <?php foreach($dataSiswa as $siswa) : ?>
          <tr>
            <td style="text-align: center;"> <?= $no++; ?> </td>
            <td style="text-align: center;"> <?= $siswa['nis']; ?> </td>
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
    $("#isiListQuery").click();
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