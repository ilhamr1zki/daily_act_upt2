<?php  
  
  $timeOut        = $_SESSION['expire_paud'];
    
  $timeRunningOut = time() + 5;

  $timeIsOut      = 0;

  error_reporting(1);

  $arrJobFather = [
    "GURU",
    "DOSEN",
    "pegawai_pns",
    "pegawai_bumn",
    "karyawan_swasta",
    "POLRI",
    "TNI",
    "WIRASWASTA",
    "WIRAUSAHA"
  ];

  $arrJobMother = [
    "GURU",
    "DOSEN",
    "pegawai_pns",
    "pegawai_bumn",
    "karyawan_swasta",
    "POLRI",
    "TNI",
    "WIRASWASTA",
    "WIRAUSAHA",
    "IRT"
  ];

  $arrIsiPddAyah = [
    "SD/MI",
    "SMP/MTS",
    "SMA/SMK/MA",
    "D1",
    "D2",
    "D3",
    "S1",
    "S2",
    "S3"
  ];

  if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

    $_SESSION['form_success'] = "session_time_out";

    $timeIsOut = 1;
    // error_reporting(1);

  } else {

    $queryGetGroupKelas = mysqli_query($con, "
      SELECT nama_group_kelas FROM group_kelas
    ");

    $sqlKlp = mysqli_query($con,"SELECT * FROM m_klp ORDER BY nm_klp ASC");

    if (isset($_POST['btnSimpan'])) {

      // echo $_POST['_klpselect'];exit;

      $c_kelas      = htmlspecialchars($_POST['c_kelas']);
      $nisn         = htmlspecialchars($_POST['nisn']);
      $nama         = mysqli_real_escape_string($con, htmlspecialchars($_POST['nama']));
      $jk           = htmlspecialchars($_POST['jk']);
      $alamatlahir  = mysqli_real_escape_string($con, htmlspecialchars($_POST['alamat_lahir']));
      $tl           = htmlspecialchars(date('Y-m-d',strtotime($_POST['tl'])));
      $thnjoin      = htmlspecialchars($_POST['_thnjoin']);
      $panggilan    = mysqli_real_escape_string($con, htmlspecialchars($_POST['_nmpanggilan']));
      $c_klp        = htmlspecialchars($_POST['_klpselect']);
      $bbadan       = htmlspecialchars($_POST['_beratbadan']);
      $tbadan       = htmlspecialchars($_POST['_tinggibadan']);
      $ukuran_baju  = htmlspecialchars($_POST['_ukuranbaju']);
      $alamat       = mysqli_real_escape_string($con, htmlspecialchars($_POST['_alamatrumah']));
      $telp         = htmlspecialchars($_POST['_telp']);
      $hp           = htmlspecialchars($_POST['_hp']);
      $email        = htmlspecialchars($_POST['_email']);

      $nama_ayah    = mysqli_real_escape_string($con, htmlspecialchars($_POST['_nmayah']));
      $pendidikan_a = mysqli_real_escape_string($con, htmlspecialchars($_POST['_pendayah']));
      $pekerjaan_a  = str_replace(["_"], " ", strtoupper(mysqli_real_escape_string($con, htmlspecialchars($_POST['_pekerjaanayah']))) );
      $ttl_a        = mysqli_real_escape_string($con, htmlspecialchars($_POST['_temptglayah']));

      $nama_ibu     = mysqli_real_escape_string($con, htmlspecialchars($_POST['_nmibu']));
      $pendidikan_i = mysqli_real_escape_string($con, htmlspecialchars($_POST['_pendibu']));
      $pekerjaan_i  = str_replace(["_"], " ", strtoupper(mysqli_real_escape_string($con, htmlspecialchars($_POST['_pekerjaanibu']))) );
      $ttl_i        = mysqli_real_escape_string($con, htmlspecialchars($_POST['_temptglibu']));

      $nis          = htmlspecialchars($_POST['_nis']);

      // if(substr($c_kelas, -2) == "SD") {

      //   $kode = "SD";
      //   $kode2 = "SD";

      // } else {  

      //   $kode = "TK";
      //   $kode2 = "PTK";

      // }

      if ($c_klp == "KB") {

        $kode   = "TK";
        $kode2  = "PTK";

        $c_kelas = $c_klp;

      } else if ($c_klp == "TKA") {

        $kode   = "TK";
        $kode2  = "PTK";

        $c_kelas = $c_klp;

      } else if ($c_klp == "TKB") {

        $kode   = "TK";
        $kode2  = "PTK";

        $c_kelas = $c_klp;

      } else {

        $kode   = "SD";
        $kode2  = "SD";

        $c_kelas = str_replace([" A", " B"], "", $c_klp) . $kode;

      }

      // echo $c_klp . " & " . $kode2;exit;

      $seqc_sis=mysqli_fetch_array(mysqli_query($con,"SELECT (nourut + 1) as nourut FROM penomoranmas where kode='$kode2' limit 1 "));
      $nomorurut = $seqc_sis['nourut'] ?? 0;

      $invID = str_pad($nomorurut, 4, '0', STR_PAD_LEFT);

      $kodseq = $kode."".$invID;

      $formatNumber = substr($hp, 0, 2);

      if ($formatNumber == "08") {

        $queryInsertSiswa = mysqli_query($con, "
          INSERT INTO siswa 
          set 
          c_siswa         = '$kodseq', 
          c_kelas         = '$c_kelas',
          nisn            = '$nisn',
          nama            = '$nama',
          jk              = '$jk',
          tempat_lahir    = '$alamatlahir',
          tanggal_lahir   = '$tl', 
          tahun_join      = '$thnjoin', 
          panggilan       = '$panggilan', 
          c_klp           = '$c_klp', 
          berat_badan     = '$bbadan',
          tinggi_badan    = '$tbadan', 
          ukuran_baju     = '$ukuran_baju', 
          alamat          = '$alamat', 
          telp            = '$telp', 
          hp              = '$hp',
          email           = '$email', 
          nama_ayah       = '$nama_ayah', 
          pendidikan_ayah = '$pendidikan_a', 
          pekerjaan_ayah  = '$pekerjaan_a',
          ttl_ayah        = '$ttl_a', 
          nama_ibu        = '$nama_ibu', 
          pendidikan_ibu  = '$pendidikan_i', 
          pekerjaan_ibu   = '$pekerjaan_i',
          ttl_ibu         = '$ttl_i', 
          nis             = '$nis' 
        ");

        if ($queryInsertSiswa) {

          $penomoran=mysqli_query($con,"UPDATE penomoranmas set nourut='$nomorurut'  where kode='$kode2' ");

          if ($penomoran) {

            $rand_pass = random(5);

            $queryInsertAksesOTM = mysqli_query($con, "
              INSERT INTO akses_otm
              set 
              nis_siswa = '$nis',
              password  = '$rand_pass',
              no_hp     = '$hp'
            ");

            if ($queryInsertAksesOTM) {
              $_SESSION['insert_siswa'] = 'berhasil';
            } else {
              $_SESSION['insert_siswa'] = 'gagal_akses_otm';
            } 

          } else {

            $_SESSION['insert_siswa'] = 'gagal_update_penomoran';

          }

        } else {

          $_SESSION['insert_siswa'] = 'gagal';

        }

      } else {

        $_SESSION['insert_number_phone'] = 'format_number_invalid';

      }

    }

  }
  
?>

<?php if(isset($_SESSION['insert_number_phone']) && $_SESSION['insert_number_phone'] == 'format_number_invalid'){?>
  <div style="color: yellow;" class="alert alert-danger alert-dismissable"> FORMAT NO HP INVALID
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php unset($_SESSION['insert_number_phone']); ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['insert_siswa']) && $_SESSION['insert_siswa'] == 'berhasil'){?>
  <div style="display: none;" class="alert alert-warning alert-dismissable"> <span style="color: white;"> BERHASIL MENAMBAHKAN SISWA BARU </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['insert_siswa']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['insert_siswa']) && $_SESSION['insert_siswa'] == 'gagal'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: white;"> GAGAL MENAMBAHKAN SISWA BARU </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['insert_siswa']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['insert_siswa']) && $_SESSION['insert_siswa'] == 'gagal_akses_otm'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: white;"> GAGAL MENAMBAHKAN DATA BARU AKSES OTM </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['insert_siswa']);
    ?>
  </div>
<?php } ?>

<?php if(isset($_SESSION['insert_siswa']) && $_SESSION['insert_siswa'] == 'gagal_update_penomoran'){?>
  <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: white;"> GAGAL UPDATE PENOMORAN SISWA </span>
     <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
     <?php 
        unset($_SESSION['insert_siswa']);
    ?>
  </div>
<?php } ?>

<div class="row">
        <!-- left column -->
  <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"> <i class="glyphicon glyphicon-user"></i> Tambah Siswa Kelas </h3><span style="float:right;">  <a href="<?= $basead.'students'; ?>" class="btn btn-circle btn-primary"><i class="glyphicon glyphicon-th-list"></i> Lihat Siswa</a></span>

      </div>
      <!-- form start -->
      <form role="form" method="post">
        <div class="box-body">
          <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                <a href="<?= $basead; ?>importsiswa" class="btn btn-sm btn-success"> <i class="glyphicon glyphicon-upload"></i> IMPORT DATA EXCEL (xls) </a>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                <label>NIS<sup style="color: red;">*</sup> </label>
                <input type="text" required placeholder="Hanya Boleh Angka" id="nis_siswa" pattern="[0-9]*" inputmode="numeric" onkeypress="return onlyNumberKey(event)" maxlength="9" name="_nis" class="form-control">
              </div>
            </div>
              <div class="col-sm-2">
                  <div class="form-group">
                    <label>NISN<sup style="color: red;">*</sup> </label>
                    <input type="text" placeholder="Hanya Boleh Angka" id="inp_nisn" name="nisn" pattern="[0-9]*" inputmode="numeric" onkeypress="return onlyNumberKey(event)" maxlength="10" class="form-control">
                  </div>
              </div>
              <div class="col-sm-8">
                  <div class="form-group">
                    <label>NAMA LENGKAP</label>
                    <input type="text" required="" name="nama" class="form-control">
                  </div>
              </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>ALAMAT LAHIR</label>
                <input type="text" required="" name="alamat_lahir" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>TANGGAL LAHIR</label>
                <div class="controls input-append date form_date" data-date="1998-10-14" data-date-format="dd MM yyyy" data-link-field="dtp_input1">
                    <input class="form-control" type="text" name="tl" value="" required="">
                    <span class="add-on"><i class="icon-th"></i></span>
                </div>
              </div>
            </div>
          </div>
          <div class="row">

              <div class="col-sm-2">
                  <div class="form-group">
                    <label>JENIS KELAMIN </label>
                    <select class="form-control form-select" required="" name="jk">
                      <option value="">-- Pilih --</option>
                      <option value="L"> LAKI - LAKI </option>
                      <option value="P"> PEREMPUAN </option>
                    </select>
                  </div>
              </div>

              <div class="col-sm-2">
                <div class="form-group">
                  <label>KELAS</label>
                  <select class="form-control form-select" id="_klpselect" required="" name="_klpselect">
                    <option value="">-- Pilih --</option>
                    <?php foreach ($sqlKlp as $data): ?>
                        
                      <option value="<?= $data['nm_klp']; ?>"> <?= $data['nm_klp']; ?> </option>

                    <?php endforeach ?>

                  </select>
                </div>
              </div>

              <div class="col-sm-2">
                <div class="form-group">
                  <label>Tahun Join</label>
                  <input type="text" pattern="[0-9]*" inputmode="numeric" onkeypress="return onlyNumberKey(event)" maxlength="4" class="form-control" id="_thnjoin" name="_thnjoin" required="">
                </div>
              </div>

              <div class="col-sm-4">
                <div class="form-group">
                  <label>Nama Panggilan</label>
                  <input type="text" class="form-control" id="_nmpanggilan" name="_nmpanggilan">
                </div>
              </div>

          </div>

          <div class="row">
            
            <div class="col-sm-2">
                <div class="form-group">
                    <label>Berat Badan</label>
                    <input type="text" class="form-control" id="_beratbadan" name="_beratbadan">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label>Tinggi Badan</label>
                    <input type="text" class="form-control" id="_tinggibadan" name="_tinggibadan">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label>Ukuran Baju</label>
                    <input type="text" class="form-control" id="_ukuranbaju" name="_ukuranbaju">
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-4">
                  <div class="form-group">
                    <label>Alamat</label>
                    <input type="text" class="form-control" id="_alamatrumah" name="_alamatrumah">
                </div>
            </div>
            <div class="col-sm-2">
                  <div class="form-group">
                    <label>Telp.</label>
                    <input type="text" class="form-control" id="_telp" name="_telp" pattern="[0-9]*" inputmode="numeric" onkeypress="return onlyNumberKey(event)" maxlength="12">
                </div>
            </div>
            <div class="col-sm-2">
                  <div class="form-group">
                    <label>HP</label>
                    <input type="text" class="form-control" id="_hp" name="_hp" pattern="[0-9]*" inputmode="numeric" onkeypress="return onlyNumberKey(event)" maxlength="12">
                </div>
            </div>
            <div class="col-sm-4">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" id="_email" name="_email" >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
              <div class="box">
              <div class="box-header with-border">
                <h3 class="box-title"> <i class="glyphicon glyphicon-user"></i> Data Ayah</h3>
              </div>
                <div class="box-body">
                      <div class="row">
                          <div class="col-sm-4">
                            <div class="form-group">
                                <label>Nama Ayah</label>
                                <input type="text" class="form-control" id="_nmayah" name="_nmayah" required="">
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="form-group">
                                <label>Pendidikan Ayah</label>
                                <select id="_pendayah" name="_pendayah"  class="form-control form-select">
                                    <option value="">--Pilih--</option>
                                    <?php foreach ($arrIsiPddAyah as $data): ?>
                                      <option value="<?= $data; ?>"> <?= $data; ?> </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="form-group">
                                <label>Pekerjaan Ayah</label>
                                <select id="_pekerjaanayah" name="_pekerjaanayah" class="form-control form-select">
                                  <option value="">-- Pilih --</option>
                                  <?php foreach ($arrJobFather as $data): ?>
                                    <?php if ($data == "pegawai_pns"): ?>
                                      <option value="<?= $data; ?>"> PEGAWAI PNS </option>
                                    <?php elseif($data == "pegawai_bumn"): ?>
                                      <option value="<?= $data; ?>"> PEGAWAI BUMN / BUMD </option>
                                    <?php elseif($data == "karyawan_swasta"): ?>
                                      <option value="<?= $data; ?>"> KARYAWAN SWASTA </option>
                                    <?php else: ?>
                                      <option value="<?= $data; ?>"> <?= $data; ?> </option>
                                    <?php endif ?>
                                  <?php endforeach ?>
                                </select>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tempat Tgl. Ayah</label>
                                <input type="text" class="form-control" id="_temptglayah" name="_temptglayah">
                            </div>
                          </div>
                      </div>
                </div>

              </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"> <i class="glyphicon glyphicon-user"></i> Data Ibu</h3>
                </div>
                  <div class="box-body">
                       <div class="row">
                          <div class="col-sm-4">
                            <div class="form-group">
                                <label>Nama Ibu</label>
                                <input type="text" class="form-control" id="_nmibu" name="_nmibu" required="">
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="form-group">
                                <label>Pendidikan Ibu</label>
                                <select id="_pendibu" name="_pendibu"  class="form-control form-select">
                                    <option value="">--Pilih--</option>
                                    <?php foreach ($arrIsiPddAyah as $data): ?>
                                      <option value="<?= $data; ?>"> <?= $data; ?> </option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="form-group">
                                <label>Pekerjaan Ibu</label>
                                <select id="_pekerjaanibu" name="_pekerjaanibu" class="form-control form-select">
                                    <option value="">-- Pilih --</option>
                                    <?php foreach ($arrJobMother as $data): ?>
                                      <?php if ($data == "pegawai_pns"): ?>
                                        <option value="<?= $data; ?>"> PEGAWAI PNS </option>
                                      <?php elseif($data == "pegawai_bumn"): ?>
                                        <option value="<?= $data; ?>"> PEGAWAI BUMN / BUMD</option>
                                      <?php elseif($data == "karyawan_swasta"): ?>
                                        <option value="<?= $data; ?>"> KARYAWAN SWASTA </option>
                                      <?php else: ?>
                                        <option value="<?= $data; ?>"> <?= $data; ?> </option>
                                      <?php endif ?>
                                    <?php endforeach ?>
                                </select>
                            </div>
                          </div>
                          <div class="col-sm-3">
                            <div class="form-group">
                                <label>Tempat Tgl. Ibu</label>
                                <input type="text" class="form-control" id="_temptglibu" name="_temptglibu">
                            </div>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
        </div>

        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <button type="submit" name="btnSimpan" id="btnSimpan" class="btn btn-success btn-circle"><i class="glyphicon glyphicon-ok"></i> Simpan Siswa</button>
        </div>
      </form>
    </div>
    <!-- /.box -->
  </div>
</div>

<!-- jQuery 2.2.3 -->
<script src="<?php echo $base; ?>theme/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="<?php echo $base; ?>theme/datetime/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo $base; ?>theme/datetime/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script type="text/javascript">
    $('.form_datetime').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    forceParse: 0,
        showMeridian: 1
    });
  $('.form_date').datetimepicker({
        weekStart: 1,
        todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    forceParse: 0
    });
  $('.form_time').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 1,
    minView: 0,
    maxView: 1,
    forceParse: 0
    });
</script>

<script language="javascript" type="text/javascript">
  $(document).ready(function() {
      $('#nisn').val();

      $("#nis_siswa").focus();

      let newIcon = document.getElementById("addIcon");
      newIcon.classList.remove("fa");
      newIcon.classList.add("glyphicon");
      newIcon.classList.add("glyphicon-user");

      let getTitleList2 = document.getElementById('addst').innerHTML;
      document.getElementById('isiMenu').innerHTML = getTitleList2

      $('#inp_nis').keypress(function (e) {
        if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
      });

      $('#inp_nisn').keypress(function (e) {
        if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
      });

      $('#_telp').keypress(function (e) {
        if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
      });

      $('#_hp').keypress(function (e) {
        if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
      });

  });

  function onlyNumberKey(evt) {

    // Only ASCII character in that range allowed
    let ASCIICode = (evt.which) ? evt.which : evt.keyCode
    if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
        return false;
    return true;
  }


function SelesaiChanged(selesaival) {
    //alert( $('#_klpselect').val());
}
</script>
