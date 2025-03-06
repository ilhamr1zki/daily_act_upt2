<?php  

  $timeOut        = $_SESSION['expire_paud'];
    
  $timeRunningOut = time() + 5;

  $timeIsOut    = 0;
  $sesi         = 0;
  $nama         = "";
  $jumlahData   = 0;

  $no           = 1;

  $nis          = $_SESSION['c_otm_paud'];
  $diMenu       = "querydailystudent";

  $dataActivityFromTeacher = "";
  $countDataAct = 0;
  $namaSiswa    = $_SESSION['username_otm_paud']; 

  // echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

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

  if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

    $_SESSION['form_success'] = "session_time_out";

      $timeIsOut = 1;
      error_reporting(1);

  } else {

      if (isset($_POST['send_data_student'])) {

        $nis                  = $_SESSION['c_otm_paud'];

        // Get Id Group
        $queryGetIdGroup = mysqli_query($con, "
          SELECT group_kelas FROM siswa WHERE nis = '$nis'
        ");

        $getIdGroup = mysqli_fetch_assoc($queryGetIdGroup)['group_kelas'];

        $dataActivityFromTeacher = mysqli_query($con, "
          SELECT 
          guru.nip as nip_guru,
          guru.nama as nama_guru,
          guru.username as username_guru,
          siswa.nis AS nis_or_id_group_kelas,
          siswa.nama as nama_siswa_or_group,
          ruang_pesan.room_key as room_key,
          daily_siswa_approved.id as daily_id,
          daily_siswa_approved.title_daily as judul_daily,
          daily_siswa_approved.isi_daily as isi_daily,
          daily_siswa_approved.tanggal_dibuat as tgl_dibuat,
          daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_posted,
          daily_siswa_approved.image as foto_upload,
          ruang_pesan.daily_id as daily_id
          FROM daily_siswa_approved
          LEFT JOIN guru
          ON daily_siswa_approved.from_nip = guru.nip
          LEFT JOIN siswa
          ON daily_siswa_approved.nis_siswa = siswa.nis
          LEFT JOIN ruang_pesan
          ON daily_siswa_approved.id = ruang_pesan.daily_id
          WHERE daily_siswa_approved.nis_siswa = '$nis'
          AND daily_siswa_approved.status_approve = 1
          ORDER BY tgl_posted DESC
        ");

        $getNama = mysqli_fetch_array($dataActivityFromTeacher);

        $countDataAct = mysqli_num_rows($dataActivityFromTeacher);

        if ($countDataAct == 0) {

          $dataActivityFromTeacher = [];
          $upperName = strtoupper($namaSiswa);

        } elseif ($countDataAct != 0) {
          
          $dataActivityFromTeacher = mysqli_query($con, "
            SELECT
            guru.nama as nama_guru,
            guru.nip as nip_guru,
            siswa.nis as nis_siswa,
            siswa.nis AS nis_or_id_group_kelas,
            siswa.nama as nama_siswa_or_group,
            daily_siswa_approved.nis_siswa as daily_nis_siswa,
            daily_siswa_approved.image as foto_upload,
            daily_siswa_approved.title_daily as judul_daily,
            daily_siswa_approved.isi_daily as isi_daily,
            daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_posted,
            daily_siswa_approved.tanggal_disetujui_atau_tidak as daily_tanggal_disetujui_atau_tidak,
            ruang_pesan.room_key as room_key
            FROM daily_siswa_approved
            LEFT JOIN guru
            ON daily_siswa_approved.from_nip = guru.nip
            LEFT JOIN siswa
            ON daily_siswa_approved.nis_siswa = siswa.nis
            LEFT JOIN ruang_pesan
            ON daily_siswa_approved.id = ruang_pesan.daily_id
            WHERE daily_siswa_approved.status_approve = 1
            AND daily_siswa_approved.nis_siswa = '$nis'
            ORDER BY tgl_posted DESC
          ");

          $upperName = strtoupper($getNama['nama_siswa_or_group']);

        }

      } else {
        
        $nis                  = $_SESSION['c_otm_paud'];

        // Get Id Group
        $queryGetIdGroup = mysqli_query($con, "
          SELECT group_kelas FROM siswa WHERE nis = '$nis'
        ");

        $getIdGroup = mysqli_fetch_assoc($queryGetIdGroup)['group_kelas'];

        $dataActivityFromTeacher = mysqli_query($con, "
          SELECT 
          guru.nip as nip_guru,
          guru.nama as nama_guru,
          guru.username as username_guru,
          siswa.nis AS nis_or_id_group_kelas,
          siswa.nama as nama_siswa_or_group,
          ruang_pesan.room_key as room_key,
          daily_siswa_approved.id as daily_id,
          daily_siswa_approved.title_daily as judul_daily,
          daily_siswa_approved.isi_daily as isi_daily,
          daily_siswa_approved.tanggal_dibuat as tgl_dibuat,
          daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_posted,
          daily_siswa_approved.image as foto_upload,
          ruang_pesan.daily_id as daily_id
          FROM daily_siswa_approved
          LEFT JOIN guru
          ON daily_siswa_approved.from_nip = guru.nip
          LEFT JOIN siswa
          ON daily_siswa_approved.nis_siswa = siswa.nis
          LEFT JOIN ruang_pesan
          ON daily_siswa_approved.id = ruang_pesan.daily_id
          WHERE daily_siswa_approved.nis_siswa = '$nis'
          AND daily_siswa_approved.status_approve = 1
          ORDER BY tgl_posted DESC
        ");

        $getNama = mysqli_fetch_array($dataActivityFromTeacher);

        $countDataAct = mysqli_num_rows($dataActivityFromTeacher);

        if ($countDataAct == 0) {

          $dataActivityFromTeacher = [];
          $upperName = strtoupper($namaSiswa);

        } elseif ($countDataAct != 0) {
          
          $dataActivityFromTeacher = mysqli_query($con, "
            SELECT 
            guru.nip as nip_guru,
            guru.nama as nama_guru,
            guru.username as username_guru,
            siswa.nis AS nis_or_id_group_kelas,
            siswa.nama as nama_siswa_or_group,
            ruang_pesan.room_key as room_key,
            daily_siswa_approved.id as daily_id,
            daily_siswa_approved.title_daily as judul_daily,
            daily_siswa_approved.isi_daily as isi_daily,
            daily_siswa_approved.tanggal_dibuat as tgl_dibuat,
            daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_posted,
            daily_siswa_approved.image as foto_upload,
            ruang_pesan.daily_id as daily_id
            FROM daily_siswa_approved
            LEFT JOIN guru
            ON daily_siswa_approved.from_nip = guru.nip
            LEFT JOIN siswa
            ON daily_siswa_approved.nis_siswa = siswa.nis
            LEFT JOIN ruang_pesan
            ON daily_siswa_approved.id = ruang_pesan.daily_id
            WHERE daily_siswa_approved.nis_siswa = '$nis'
            AND daily_siswa_approved.status_approve = 1
            ORDER BY tgl_posted DESC
          ");

          $upperName = strtoupper($getNama['nama_siswa_or_group']);

        }


      }

      $jumlahData = 5;

    }

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

      <table id="example" border="1" class="display nowrap" style="width:100%">
          <thead style="background-color: grey; color: white;">
              <tr>
                <th style="text-align: center;" width="5%">NO</th>
                <th style="text-align: center;"> FROM TEACHER </th>
                <th style="text-align: center;"> ACTIVITY TITLE </th>
                <th style="text-align: center;"> DATE POSTED </th>
                <th style="text-align: center;"> LOOK DAILY </th>
              </tr>
          </thead>
          <tbody>
            
            <?php foreach ($dataActivityFromTeacher as $data): ?>
                
              <tr style="background-color: aqua; font-weight: bold;">
                <td style="text-align: center;">  <?= $no++; ?> </td>
                <td style="text-align: center;">  <?= strtoupper($data['nama_guru']); ?> </td>
                <td style="text-align: center;">  <?= $data['judul_daily'] ?> </td>
                <td style="text-align: center;">  <?= formatDateEnglish($data['tgl_posted']); ?> </td>
                <td style="text-align: center;">
                  <form action="lookactivity/<?= $data['room_key']; ?>" method="post">
                    <input type="hidden" name="frompage" value="<?= $diMenu; ?>">
                    <input type="hidden" name="roomkey" value="<?= $data['room_key']; ?>">
                    <input type="hidden" name="nis" value="<?= strtoupper($data['nis_or_id_group_kelas']); ?>">
                    <input type="hidden" name="nama" value="<?= strtoupper($data['nama_siswa_or_group']); ?>">
                    <input type="hidden" name="guru" value="<?= strtoupper($data['nama_guru']); ?>">
                    <input type="hidden" name="foto" value="<?= strtoupper($data['foto_upload']); ?>">
                    <input type="hidden" name="tglpost" value="<?= format_tgl_indo($data['tgl_posted']); ?>">
                    <input type="hidden" name="nipguru_lookdaily" value="<?= $data['nip_guru']; ?>">
                    <input type="hidden" name="tglori" value="<?= $data['tgl_posted']; ?>">
                    <input type="hidden" name="judul" value="<?= $data['judul_daily']; ?>">
                    <input type="hidden" name="isi" value="<?= $data['isi_daily']; ?>">
                    <button class="btn btn-sm btn-primary" style="text-align: center;" type="submit" name="krm"> <i class="glyphicon glyphicon-eye-open"></i> LOOK DAILY </button>
                  </form>
              </td>
              </tr>

            <?php endforeach ?>
              
          </tbody>
      </table>

    </div>
  </div>

<!-- <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script> -->
<script src="view/daily/query/dataTables1.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script> -->
<script src="view/daily/query/moment.min.js"></script>
<!-- <script src="https://cdn.datatables.net/datetime/1.5.2/js/dataTables.dateTime.min.js"></script> -->
<script src="view/daily/query/dateTime.min.js"></script>

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
  
  let jumahDataDaily = `<?= $countDataAct; ?>`

  $(document).ready( function () {

      if (jumahDataDaily == 0) {
        Swal.fire(`Tidak Ada Data Daily untuk <?= $namaSiswa; ?>`);
      }

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
    newIcon.classList.add("fa");
    newIcon.classList.add("fa-user");

    let getTitleList1 = document.getElementById('isiList2').innerHTML;
    $("#isiMenu").css({
      "margin-left" : "5px"
    });

    $("#spanIsiNama").css({
      "font-weight" : "bold"
    });

    document.getElementById('isiMenu').innerHTML =  `<span style="font-weight: bold;"> QUERY </span>`+ ' - <span style="font-weight: bold;"> QUERY DAILY STUDENT </span> - ' + `<span style="font-weight: bold;"> <?= $upperName; ?> </span>` 

      function showPopUpNoData() {
        Swal.fire({
          title: 'TIDAK ADA DATA MURID YANG DIPILIH!',
          icon: "warning"
        });

        setTimeout(redirectToPageDailyStudent, 1200);
        
      }

      function redirectToPageDailyStudent() {
        location.href = `<?= $basekepsek; ?>querydailystudent`;
      }

    });
</script>