<?php 

  $timeOut        = $_SESSION['expire'];
    
  $timeRunningOut = time() + 5;

  $timeIsOut = 0;

  echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut . "<br>";

  $dataNIP = $_SESSION['nip_guru'];

  $queryGetDataGuru   = mysqli_query($con, "SELECT * FROM guru WHERE nip = '$dataNIP' ");
  $getDataGuru        = mysqli_fetch_array($queryGetDataGuru);
  $getDataGuru        = $getDataGuru['c_jabatan'];

  $patternSD          = "/SD/i";
  $matchSD            = preg_match($patternSD, $getDataGuru);

  $patternPAUD        = "/PAUD/i";
  $matchPAUD          = preg_match($patternPAUD, $getDataGuru);

  $patternALL         = "/SEMUA/i";
  $matchALL           = preg_match($patternALL, $getDataGuru);

  $jenjangPendidikan  = "";

  if ($matchSD == 1) {

    // echo "SD : " . $matchSD;exit;
    $jenjangPendidikan = "SD";

  } elseif($matchPAUD == 1) {

    // echo "PAUD/TK : " . $matchPAUD;exit;
    $jenjangPendidikan = "PAUD";

  } elseif ($matchALL == 1) {

    // echo "ALL : " . $matchALL;exit;
    $jenjangPendidikan = "SEMUA";

  }

  if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

    $_SESSION['time_out'] = "session_time_out";
    $timeIsOut = 1;
    // exit;

  } else {

    

  }

?>

  <script src="<?= $base; ?>theme/ckeditor/ckeditor.js"></script>
  <script src="<?= $base; ?>theme/ckeditor/samples/js/sample.js"></script>

  <style type="text/css">
    
    #jdl_daily {
      margin-bottom: 15px;
    }

  </style>

  <div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12">

      <?php if(isset($_SESSION['sukses']) && $_SESSION['sukses']=='berhasil'){?>
        <div style="display: none;" class="alert alert-warning alert-dismissable"> Berhasil Membuat Daily !
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          <?php unset($_SESSION['sukses']); ?>
        </div>
      <?php } ?> 

       <?php if(isset($_SESSION['error']) && $_SESSION['error'] == 'threat_found'){?>
        <div style="display: none;" class="alert alert-danger alert-dismissable"> Gagal Membuat Daily
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           <?php 
              unset($_SESSION['error']);
          ?>
        </div>
      <?php } ?>

      <?php if(isset($_SESSION['time_out']) && $_SESSION['time_out'] == 'session_time_out'){?>
        <div style="display: none;" class="alert alert-danger alert-dismissable"> Waktu Sesi Telah Habis
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           <?php 
              unset($_SESSION['time_out']);
          ?>
        </div>
      <?php } ?>
      
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title"> <i class="glyphicon glyphicon-new-window"></i> DAILY SISWA </h3>
          <span style="float:right;">
            <a class="btn btn-primary" onclick="openModalSiswa()">
              <i class="glyphicon glyphicon-plus"></i> Cari Siswa
            </a>
          </span>
        </div>
        <!-- /.box-header -->
       
        <form action="<?= $basegu; ?>view/daily/create/api_create" method="post">
          <input type="hidden" name="nipguru" value="<?= $dataNIP; ?>">
          <div class="box-body">
            <div class="row">
              <div class="col-sm-2">
                <div class="form-group">
                  <label>NIS<sup style="color: red; font-size: 10px;">*</sup></label>
                  <input type="hidden" id="nipGuru" value="<?= $dataNIP; ?>">
                  <input type="text" readonly required id="dataNis" name="nis_siswa" class="form-control">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <label>NAMA LENGKAP<sup style="color: red; font-size: 10px;">*</sup></label>
                  <input type="text" readonly required id="dataNama" name="nama_siswa" class="form-control">
                </div>
              </div>
              <!-- <div class="col-sm-2">
                <div class="form-group">
                  <label>JENJANG PENDIDIKAN<sup style="color: red; font-size: 10px;">*</sup></label>
                  <input type="text" class="form-control" id="dataPend" name="jenjang_pendidikan" readonly>
                </div>
              </div> -->
              <div class="col-sm-2">
                <div class="form-group">
                  <label> KELAS<sup style="color: red; font-size: 10px;">*</sup></label>
                  <input type="text" class="form-control" id="dataKelas" name="jenjang_pendidikan" readonly>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <div class="box">
                  <div class="box-header with-border">
                    <h3 class="box-title"> <i class="glyphicon glyphicon-pencil"></i> BUAT DAILY </h3>
                  </div>
                  <div class="box-body">
                    <div class="row">

                      <div class="col-sm-4">
                        <label> Judul Daily </label>
                        <input type="text" id="jdl_daily" class="form-control" name="">
                      </div>
                      <br>
                      <div class="col-sm-12">
              
                        <label> Tulis Daily </label>
                        <textarea id="editor" class="kepo" name="editor_catatan"></textarea>
                      
                      </div>

                    </div>
                  </div>  
                </div>
              </div>
            </div>
            
          </div>

          <div class="box-footer">
            <button type="submit" name="simpan_daily" id="btnSimpan" class="btn btn-success btn-circle"><i class="glyphicon glyphicon-ok"></i> Buat Daily </button>
          </div>
        </form>

      </div>

    </div>

  </div>

  <!-- Modal Cari Siswa -->
<div id="modalsiswa" class="modal"  data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> × </button>
        <h4 class="modal-title" id="myModalLabel"> <i class="glyphicon glyphicon-calendar"></i> Data Siswa </h4>
      </div>
      <div class="modal-body"> 
        <div class="box-body table-responsive">
            <table id="example1x" class="table table-bordered table-hover">
                <thead>
                    <tr>
                      <th style="text-align: center;" width="5%">NO</th>
                    <?php 
                        if(empty($_GET['q'])) {
                            echo '<th style="text-align: center;" width="12%">KELAS</th>';
                        } 
                    ?>
                      <th style="text-align: center;">NIS</th>
                      <th style="text-align: center;">NAMA</th>
                    </tr>
                </thead>
                <?php

                    $no = 1;
                    
                    if(isset($_GET['q'])) {
                      $smk=mysqli_query($con,"SELECT * FROM siswa where c_kelas='$_GET[q]' order by nama asc ");
                    } else {

                        if ($jenjangPendidikan == 'SD') {
                            $queryGetAllDataSiswa      = "SELECT * FROM siswa WHERE c_kelas LIKE '%SD%' ORDER BY c_kelas asc ";
                            $execqueryGetAllDataSiswa  = mysqli_query($con, $queryGetAllDataSiswa);
                        } else if ($jenjangPendidikan == "PAUD") {
                            $queryGetAllDataSiswa      = "
                              SELECT * FROM siswa 
                              WHERE 
                              c_kelas != 'TKBLULUS'
                              AND c_kelas NOT LIKE '%SD%'
                            ";
                            $execqueryGetAllDataSiswa  = mysqli_query($con, $queryGetAllDataSiswa);
                        }

                    }

                ?>

                <tbody>
                    <?php foreach ($execqueryGetAllDataSiswa as $data): ?>
                    <tr onclick="
                        siswaSelected(
                            `<?= $data['nis']; ?>`, 
                            `<?= $data['nama']; ?>`, 
                            `<?= $data['c_kelas']; ?>`
                            )
                        ">
                            <td style="text-align: center;"> <?= $no++; ?> </td>
                            <?php if ($jenjangPendidikan == "SD"): ?>
                              <td style="text-align: center;"> <?= str_replace(["SD"], " SD", $data['c_kelas']); ?> </td>
                            <?php elseif($jenjangPendidikan == "PAUD"): ?>
                              <td style="text-align: center;"> <?= $data['c_kelas']; ?> </td>
                            <?php endif ?>
                            <td style="text-align: center;"> <?= $data['nis']; ?> </td>
                            <td style="text-align: center;"> <?= $data['nama']; ?> </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>    
</div>

<script type="text/javascript" src="<?php echo $base; ?>theme/datetime/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript">

  $(document).ready( function () {
    $("#ketPendidikan1").hide();
    $("#ketPendidikan2").hide();
    $("#anotherJob1").hide();
    $("#anotherJob2").hide();
    $("#list_data_siswa").click();
    $("#tambahdatasiswa").css({
        "background-color" : "#ccc",
        "color" : "black"
    });

  });

  function openModalSiswa(){
    $('#modalsiswa').modal("show");
  }

  function createDailyx() {
    alert($(".cke_editable").val())
    // let dataString = $("#create_daily").serialize();

    $.ajax({
      type  : 'POST',
      url   : `<?= $basegu; ?>view/daily/create/api_create.php`,
      data  : $("#create_daily").serialize(),
      success:function(data){
        console.log(data)
        // let callBack = JSON.parse(data).data_callback
        // if (callBack != null) {
        //   Swal.fire({
        //     title: 'Daily Berhasil Di Buat',
        //     icon: "success"
        //   });
        //   console.log(callBack.nip_guru);
        // }
      }
    })

  }

  function siswaSelected(nis, nama, c_kelas){

    // alert(kode)

    $('#dataNis').val(nis);
    $('#dataNama').val(nama);
    $('#dataKelas').val(c_kelas);

    $('#modalsiswa').modal("hide");

  }

</script>