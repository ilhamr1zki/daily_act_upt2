<?php 

  $timeOut        = $_SESSION['expire_paud'];
  // echo $_SESSION['nip_guru'] . "<br>";
    
  $timeRunningOut = time() + 5;

  $timeIsOut = 0;

  $tampungDataNis = [];
  $tampungDataPw  = [];

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

  function rupiahFormat($angkax){
    
    $hasil_rupiahx = "Rp " . number_format($angkax,0,'.','.');
    return $hasil_rupiahx;
   
  }

  $no = 1; 
    // echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

  if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

    $_SESSION['form_success'] = "session_time_out";

    $timeIsOut = 1;
    error_reporting(1);

  } else {

    date_default_timezone_set("Asia/Jakarta");
    $arrTgl               = [];
      
    $tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
    $tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

    $queryGetAllInfo = mysqli_query($con, "
      SELECT
      info_pengumuman_pembayaran.id as id_info,
      info_pengumuman_pembayaran.jenis_info_pembayaran as jenis_bayar,
      info_pengumuman_pembayaran.keterangan as ket_bayar,
      info_pengumuman_pembayaran.tanggal_dibuat as tgl_dibuat,
      info_pengumuman_pembayaran.tanggal_update as tgl_update,
      info_pengumuman_pembayaran.status_pembayaran as stat_byr,
      info_pengumuman_pembayaran.nominal as nominal_byr,
      info_pengumuman_pembayaran.nis as nis_siswa,
      siswa.nama as nama_siswa
      FROM info_pengumuman_pembayaran
      LEFT JOIN siswa
      ON info_pengumuman_pembayaran.nis = siswa.nis
      ORDER BY info_pengumuman_pembayaran.tanggal_dibuat DESC 
    ");

  }

?>

<div class="box box-info">

  <div class="box-header with-border">
      <h3 class="box-title" id="boxTitle"> <i class="glyphicon glyphicon-th-large"></i> <span style="margin-left: 10px; font-weight: bold;"> DASHBOARD </span> </h3>
    </div>

    <center> 
      <h4 id="judul_daily">
        <strong> <u> INFO PEMBAYARAN YANG TELAH DI BUAT UNTUK WALI MURID </u> </strong> 
      </h4> 
    </center>

    <div class="box-body table-responsive">

      <a href="<?= $basead; ?>ekspordatawalimurid" class="btn btn-sm btn-success"> <i class="glyphicon glyphicon-export"></i> EKSPOR AKSES LOGIN WALI MURID (OTM) </a>
      <br><br>

      <table id="hightlight_list_siswa" style="text-align: center;" class="table table-bordered table-hover">

        <thead>
          <tr style="background-color: lightyellow; font-weight: bold;">
            <th style="text-align: center; border: 1px solid black;" width="5%">NO</th>
            <th style="text-align: center; border: 1px solid black;"> SISWA </th>
            <th style="text-align: center; border: 1px solid black;"> KETERANGAN </th>
            <th style="text-align: center; border: 1px solid black;"> JENIS PEMBAYARAN </th>
            <th style="text-align: center; border: 1px solid black;"> NOMINAL BAYAR </th>
            <th style="text-align: center; border: 1px solid black;"> STATUS </th>
            <th style="text-align: center; border: 1px solid black;"> TANGGAL BUAT </th>
            <th style="text-align: center; border: 1px solid black;"> TANGGAL UPDATE </th>
            <!-- <th style="text-align: center;"> DAILY </th> -->
            <!-- Terdapat Administrasi Pembiayaan Yang Perlu Di Selesaikan -->
          </tr>
        </thead>

        <tbody>

          <?php foreach ($queryGetAllInfo as $data): ?>
            
            <?php if ($data['stat_byr'] == 1): ?>
              
              <tr style="background-color: limegreen; color: white; font-weight: bold;" id="list_dashboard" onclick="statusOk(`<?= $data['jenis_bayar']; ?>`)">
                <td> <?= $no++; ?> </td>
                <td> <?= strtoupper($data['nama_siswa']); ?> </td>
                <td> <?= $data['ket_bayar']; ?> </td>
                <td> <?= $data['jenis_bayar']; ?> </td>
                <td> <?= rupiahFormat($data['nominal_byr']); ?> </td>
                <?php if ($data['stat_byr'] == NULL || $data['stat_byr'] == 0): ?>
                  <td> BELUM BAYAR </td>
                <?php elseif($data['stat_byr'] != 0): ?>
                  <td> SUDAH DIBAYAR </td>
                <?php endif ?>
                <td> <?= format_tgl_indo($data['tgl_dibuat']); ?> </td>
                <?php if ($data['tgl_update'] == NULL): ?>
                  <td> <strong> - </strong> </td>
                <?php else: ?>
                  <td> <?= format_tgl_indo($data['tgl_update']); ?> </td>
                <?php endif ?>
              </tr>

            <?php else: ?>

              <tr id="list_dashboard" onclick="updateStatus(
                `<?= $data['id_info']; ?>`,
                `<?= $data['nis_siswa']; ?>`,
                `<?= $data['nama_siswa']; ?>`,
                `<?= $data['jenis_bayar']; ?>`
              )">
                <td> <?= $no++; ?> </td>
                <td> <?= strtoupper($data['nama_siswa']); ?> </td>
                <td> <?= $data['ket_bayar']; ?> </td>
                <td> <?= $data['jenis_bayar']; ?> </td>
                <td> <?= rupiahFormat($data['nominal_byr']); ?> </td>
                <?php if ($data['stat_byr'] == NULL || $data['stat_byr'] == 0): ?>
                  <td> BELUM BAYAR </td>
                <?php elseif($data['stat_byr'] != 0): ?>
                  <td> SUDAH DIBAYAR </td>
                <?php endif ?>
                <td> <?= format_tgl_indo($data['tgl_dibuat']); ?> </td>
                <?php if ($data['tgl_update'] == NULL): ?>
                  <td> <strong> - </strong> </td>
                <?php else: ?>
                  <td> <?= format_tgl_indo($data['tgl_update']); ?> </td>
                <?php endif ?>
              </tr>

            <?php endif ?>

          <?php endforeach ?>
          
        </tbody>

      </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">

  function updateStatus(id, nis, nama, jns_byr) {
    
    Swal.fire({
      title: `Anda Yakin Ingin Update Pembayaran ${jns_byr} ${nama} ?`,
      showCancelButton: true,
      confirmButtonText: "UPDATE",
      denyButtonText: `Don't save`
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        $.ajax({
          url     : `<?= $basead; ?>data`,
          method  : 'POST',
          data    : {
            tesdata : nis,
            idbayar : id
          },
          success:function(data) {
            
            let dataRes = JSON.parse(data).update_pembayaran;

            if (dataRes == 'berhasil') {
              Swal.fire("Pembayaran Berhasil DiUpdate !", "", "success");
              setTimeout(() => {
                location.href = `<?= $basead; ?>`
              }, 1000);
            } else {
              Swal.fire("Pembayaran Gagal DiUpdate !", "", "warning");
            }
            console.log(JSON.parse(data).update_pembayaran);
          }
        });
      } else if (result.isDenied) {
        Swal.fire("Changes are not saved", "", "info");
      }
    });

    let newIcon = document.querySelector(".swal2-confirm");
    newIcon.classList.add("btn-sm");
    newIcon.classList.add("btn-success");

    let newIconCancel = document.querySelector(".swal2-cancel");
    newIconCancel.classList.add("btn-sm");

  }

  function statusOk(jenis_pembayaran) {

    Swal.fire({
      title: "SUDAH DI BAYAR !",
      text: `PEMBAYARAN UANG ${jenis_pembayaran} SUDAH DI BAYAR !`,
      icon: "success"
    });

  }
    
  $(document).ready(function(){

    $("#dashboard").css({
      "background-color" : "#ccc",
      "color" : "black"
    });

  })

</script>