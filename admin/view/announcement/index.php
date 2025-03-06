<?php 

    $timeOut        = $_SESSION['expire_paud'];
    
    $timeRunningOut = time() + 5;

    $timeIsOut      = 0;

    $tampungDataNis = [];
    $tampungDataPw  = [];

    $dataJenisPembayaran = ["SPP", "REGISTRASI", "PANGKAL", "LAINNYA"];

    $no             = 1;

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

    // echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

    if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

        $_SESSION['form_success'] = "session_time_out";

        $timeIsOut = 1;
        error_reporting(1);
          // exit;

     } else {

        date_default_timezone_set("Asia/Jakarta");
        $arrTgl               = [];
          
        $tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
        $tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

        $tglSkrng             = date("Y-m-d H:i:s");

        $queryAllStudent = mysqli_query($con, 
            "SELECT * FROM siswa WHERE c_kelas <> 'TKBLULUS' order by c_kelas asc"
        );

        if (isset($_POST['kirim_ann'])) {

            $nis                = htmlspecialchars($_POST['nis_siswa']);
            $nama               = mysqli_real_escape_string($con, htmlspecialchars($_POST['nama_siswa']));
            $jenis_pembayaran   = htmlspecialchars($_POST['jns_pmb']);
            $keterangan         = mysqli_real_escape_string($con, htmlspecialchars($_POST['keterangan']));
            $nominal            = str_replace(["Rp. ", "."], "", htmlspecialchars($_POST['nominal']));

            // echo $nominal;exit;
            if ($keterangan == null && $nominal == null) {

                $_SESSION['empty_field'] = "empty";

                date_default_timezone_set("Asia/Jakarta");
                $arrTgl               = [];
                  
                $tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
                $tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

                $tglSkrng             = date("Y-m-d H:i:s");

                $queryAllStudent = mysqli_query($con, 
                    "SELECT * FROM siswa WHERE c_kelas <> 'TKBLULUS' order by c_kelas asc"
                );

            } else if ($keterangan == null) {

                $_SESSION['empty_field'] = "empty2";

                date_default_timezone_set("Asia/Jakarta");
                $arrTgl               = [];
                  
                $tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
                $tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

                $tglSkrng             = date("Y-m-d H:i:s");

                $queryAllStudent = mysqli_query($con, 
                    "SELECT * FROM siswa WHERE c_kelas <> 'TKBLULUS' order by c_kelas asc"
                );

            } else if ($nominal == null) {

                $_SESSION['empty_field'] = "empty3";

                date_default_timezone_set("Asia/Jakarta");
                $arrTgl               = [];
                  
                $tglSkrngAwal         = date("Y-m-d") . " 00:00:00";
                $tglSkrngAkhir        = date("Y-m-d") . " 23:59:59";

                $tglSkrng             = date("Y-m-d H:i:s");

                $queryAllStudent = mysqli_query($con, 
                    "SELECT * FROM siswa WHERE c_kelas <> 'TKBLULUS' order by c_kelas asc"
                );

            } else {

                $insertDB           = mysqli_query($con, "
                    INSERT INTO info_pengumuman_pembayaran 
                    SET
                    nis                   = '$nis',
                    jenis_info_pembayaran = '$jenis_pembayaran',
                    keterangan            = '$keterangan',
                    nominal               = '$nominal',
                    tanggal_dibuat        = '$tglSkrng' 
                ");

                if ($insertDB == true) {

                    $_SESSION['success'] = 'create_info';

                }

            }

        }

     }

?>

<style type="text/css">
    
    .ikon-tambah {
        top: 2px !important;
        margin-right: 5px;
    }

    .new1 {
        border-top: 1px solid black;
    }

    #tr_ann {
        cursor: pointer;
    }

</style>

    <?php if(isset($_SESSION['success']) && $_SESSION['success'] == 'create_info'){?>
        <div style="display: none;" class="alert alert-warning alert-dismissable"> INFO PENGUMUMAN BERHASIL DIBUAT!
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           <?php 
              unset($_SESSION['success']);
          ?>
        </div>
    <?php } ?>

    <?php if(isset($_SESSION['empty_field']) && $_SESSION['empty_field'] == 'empty'){?>
        <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> KETERANGAN DAN NOMINAL HARAP DI ISI ! </span>
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           <?php 
              unset($_SESSION['empty_field']);
          ?>
        </div>
    <?php } ?>

    <?php if(isset($_SESSION['empty_field']) && $_SESSION['empty_field'] == 'empty2'){?>
        <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> KETERANGAN PEMBAYARAN HARAP DI ISI ! </span>
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           <?php 
              unset($_SESSION['empty_field']);
          ?>
        </div>
    <?php } ?>

    <?php if(isset($_SESSION['empty_field']) && $_SESSION['empty_field'] == 'empty3'){?>
        <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> NOMINAL HARAP DI ISI ! </span>
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           <?php 
              unset($_SESSION['empty_field']);
          ?>
        </div>
    <?php } ?>

<div class="box box-info">
   
    <form action="<?= $basead; ?>createinfo" method="post">
      <div class="box-body">
        <div class="row">

            <div class="col-sm-2">  
                <div class="form-group">
                    <label style="color: white;">NIS</label>
                    <a onclick="openModalSiswa()" class="form-control btn btn-primary"> <i class="glyphicon glyphicon-search"></i> Cari Siswa </a>
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-group">
                  <label>NIS<sup style="color: red; font-size: 10px;">*</sup></label>
                  <input type="text" readonly required id="dataNis" name="nis_siswa" class="form-control">
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label>NAMA LENGKAP<sup style="color: red; font-size: 10px;">*</sup></label>
                    <input type="text" readonly required id="dataNama" name="nama_siswa" class="form-control">
                </div>
            </div>

            <div class="col-sm-2">
                <div class="form-group">
                    <label> KELAS<sup style="color: red; font-size: 10px;">*</sup></label>
                    <input type="text" class="form-control" id="dataKelas" name="jenjang_pendidikan" readonly>
                </div>
            </div>

        </div>

        <hr class="new1">

        <div class="row">
            
            <div class="col-sm-3">
                <div class="form-group">
                  <label>JENIS PEMBAYARAN<sup style="color: red; font-size: 10px;">*</sup></label>
                    <select class="form-control" name="jns_pmb">
                        <?php foreach ($dataJenisPembayaran as $data): ?>
                            <?php if ($data == "REGISTRASI"): ?>
                                <option value="<?= $data; ?>"> REGISTRASI / DAFTAR ULANG </option>
                            <?php else: ?>
                                <option value="<?= $data; ?>"> <?= $data; ?> </option>
                            <?php endif ?>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-5">
                <div class="form-group">
                    <label>KETERANGAN<sup style="color: red; font-size: 10px;">*</sup></label>
                    <input type="text" name="keterangan" placeholder="Keterangan Pembayaran ..." class="form-control">
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label>NOMINAL<sup style="color: red; font-size: 10px;">*</sup></label>
                    <input type="text" name="nominal" id="nominal" placeholder="Rp 1000.000" class="form-control">
                </div>
            </div>

        </div>

        <div class="row" id="btn_send">
                
            <div class="col-sm-5">
                <div class="form-group">
                    <button type="submit" name="kirim_ann" class="btn btn-success btn-sm"> SEND </button>
                </div>
            </div>

        </div>

      </div>

    </form>

</div>

<!-- Modal Cari Siswa -->
<div id="modalsiswa" class="modal"  data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> × </button>
        <h4 class="modal-title" id="myModalLabel"> <i class="glyphicon glyphicon-list-alt"></i> Data Siswa </h4>
      </div>
      <div class="modal-body"> 
        <div class="box-body table-responsive">
            <table id="example1x" class="table table-bordered table-hover">
                <thead>
                    <tr>
                      <th style="text-align: center;" width="5%">NO</th>
                      <th style="text-align: center;"> KELAS </th>
                      <th style="text-align: center;">NIS</th>
                      <th style="text-align: center;">NAMA</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($queryAllStudent as $data): ?>
                        
                        <tr id="tr_ann" style="text-align: center;" onclick="siswaSelected(
                            `<?= strtoupper($data['nama']); ?>`,
                            `<?= $data['nis']; ?>`,
                            `<?= $data['c_siswa']; ?>`,
                            `<?= str_replace(["SD"], " SD", $data['c_kelas']); ?>`)
                        ">
                            <td> <?= $no++; ?> </td>
                            <td> <?= str_replace(["SD"], " SD", $data['c_kelas']); ?> </td>
                            <td> <?= $data['nis']; ?> </td>
                            <td> <?= strtoupper($data['nama']); ?> </td>
                        </tr>
                        
                    <?php endforeach ?>
                </tbody>

            </table>
        </div>
      </div>
    </div>
  </div>    
</div>

<script type="text/javascript">

    function openModalSiswa(){
        $('#modalsiswa').modal("show");
    }

    let newIcon = document.getElementById("addIcon");
    newIcon.classList.remove("fa");
    newIcon.classList.add("glyphicon");
    newIcon.classList.add("glyphicon-envelope");
    newIcon.classList.add("ikon-tambah");

    document.getElementById('isiMenu').innerHTML = `<span style="font-weight: bold;"> ANNOUNCEMENT - </span>` + `<span style="font-weight: bold;"> CREATE INFO </span>`
    
    $(document).ready( function () {
        $("#btn_send").hide();
        $("#list_announcement").click();
        $("#createinfo").css({
            "background-color" : "#ccc",
            "color" : "black"
        });
    });

    let nominalBayar  = document.getElementById('nominal')

    nominalBayar.addEventListener('keyup', function(e){
        // tambahkan 'Rp.' pada saat form di ketik
        // alert("ok")
        // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
        nominalBayar.value = formatRupiah(this.value, 'Rp. ');
    });

    function siswaSelected(nama, nis, c_siswa, c_kelas) {

        $('#dataNis').val(nis);
        $('#dataNama').val(nama);
        $('#dataKelas').val(c_kelas);
        $("#btnSimpan").show();
        $("#btn_send").show();
        $('#modalsiswa').modal("hide");

    }

    function formatRupiah(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split             = number_string.split(','),
        sisa              = split[0].length % 3,
        rupiah_spp        = split[0].substr(0, sisa),
        ribuan            = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah_spp += separator + ribuan.join('.');
        }

        rupiah_spp = split[1] != undefined ? rupiah_spp + ',' + split[1] : rupiah_spp;
        return prefix == undefined ? rupiah_spp : (rupiah_spp ? 'Rp. ' + rupiah_spp : '');
    }

</script>