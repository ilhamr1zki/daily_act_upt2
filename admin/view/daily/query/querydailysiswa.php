<?php  

  $timeOut        = $_SESSION['expire'];
    
  $timeRunningOut = time() + 5;

  $timeIsOut = 0;

  // echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

  if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

    $_SESSION['form_success'] = "session_time_out";

    $timeIsOut = 1;
    error_reporting(1);
      // exit;

  } else {

    $dataSiswa = mysqli_query($con, "
      SELECT * FROM data_murid_sd
      UNION 
      SELECT * FROM data_murid_tk
    ");

  }

?>

<div class="box box-info">
  <div class="box-body table-responsive">

    <table id="list_siswa" class="table table-bordered table-hover">
      <thead>
        <tr>
          <th style="text-align: center;" width="5%">NO</th>
          <th style="text-align: center;">NIS</th>
          <th style="text-align: center;">NAMA</th>
          <th style="text-align: center;">KELAS</th>
          <th style="text-align: center;">GENDER</th>
          <th style="text-align: center;"> DAILY </th>
        </tr>
      </thead>

      <tbody>
        <?php $no = 1; ?>
        <?php foreach($dataSiswa as $siswa) : ?>
          <tr>
            <td style="text-align: center;"> <?= $no++; ?> </td>
            <td style="text-align: center;"> <?= $siswa['nis']; ?> </td>
            <td style="text-align: center;"> <?= $siswa['nama']; ?> </td>
            <td style="text-align: center;"> <?= $siswa['kelas']; ?> </td>
            <?php if ($siswa['jk'] == 'L'): ?>
              <td style="text-align: center;"> LAKI - LAKI </td>
            <?php elseif($siswa['jk'] == 'P'): ?>
              <td style="text-align: center;"> PEREMPUAN </td>
            <?php endif ?>

            <td style="text-align: center;"> 
                <input type="hidden" name="nis" value="<?= $siswa['nis']; ?>">
                <input type="hidden" name="nama" value="<?= $siswa['nama']; ?>" id="nama_siswa">
                <button class="btn btn-sm btn-primary" onclick="lihatDaily(`<?= $siswa['nis']; ?>`, `<?= $siswa['nama']; ?>`)"> <i class="glyphicon glyphicon-eye-open"></i> DAILY </button> 
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

  $("#isiMenu").css({
    "margin-left" : "5px"
  });

  document.getElementById('isiMenu').innerHTML = `<span style="font-weight: bold;"> QUERY - </span>` + `<span style="font-weight: bold;"> SISWA </span>`

  function lihatDaily(nis, nama) {

    // alert(nis)
    $.ajax({
      type : "POST",
      url  : "view/daily/query/ajax/teachercreatedaily.php",
      data : {
        nama : nama,
        nis : nis,
      },
      success : function(data) {
        $("#isi_konten").html(data);
      }
    })

  }

</script>