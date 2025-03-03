<?php 

  $timeOut        = $_SESSION['expire'];
    
  $timeRunningOut = time() + 5;

  $timeIsOut = 0;

  // echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut . "<br>";

  $dataNIP = $_SESSION['nip_guru'];

  $queryGetDataGuru   = mysqli_query($con, "SELECT * FROM guru WHERE nip = '$dataNIP' ");
  $getDataGuru        = mysqli_fetch_array($queryGetDataGuru);
  $getDataGuru        = $getDataGuru['c_jabatan'];

  $patternSD          = "/SD/i";
  $matchSD            = preg_match($patternSD, $getDataGuru);

  $patternPAUD        = "/PAUD/i";
  $matchPAUD          = preg_match($patternPAUD, $getDataGuru);

  $patternALL         = "/Kepala_Unit/i";
  $matchALL           = preg_match($patternALL, $getDataGuru);

  $jenjangPendidikan  = "";
  $sesi               = 0;

  // echo $_SESSION['c_guru'];exit;

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

    #cke_12,
    #cke_20,
    #cke_22,
    #cke_26,
    #cke_33 {
      display: none;
    }


  </style>

  <div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12">

      <?php if(isset($_SESSION['create_room_std']) && $_SESSION['create_room_std']=='berhasil'){?>
        <div style="display: none;" class="alert alert-warning alert-dismissable"> BERHASIL MEMBUAT DAILY !
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        </div>
          <?php 
            $sesi = 1;
            unset($_SESSION['create_room_std']); 
          ?>
      <?php } ?> 

      <?php if(isset($_SESSION['create_room_std']) && $_SESSION['create_room_std']=='res_gagal'){?>
        <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> GAGAL MENGIRIM NOTIF WHATSAPP ! SERVER SEDANG DALAM GANGGUAN ! </span>
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        </div>
          <?php 
            unset($_SESSION['create_room_std']); 
          ?>
      <?php } ?>

      <?php if(isset($_SESSION['create_room_std']) && $_SESSION['create_room_std']=='gagal'){?>
        <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> GAGAL MEMBUAT DAILY ! </span>
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        </div>
          <?php 
            unset($_SESSION['create_room_std']); 
          ?>
      <?php } ?>

      <?php if(isset($_SESSION['fail_form']) && $_SESSION['fail_form'] == 'threat'){?>
        <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> GAGAL MEMBUAT DAILY </span>
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           <?php 
              unset($_SESSION['fail_form']);
          ?>
        </div>
      <?php } ?>


      <?php if(isset($_SESSION['main_daily_empty']) && $_SESSION['main_daily_empty'] == 'gagal'){?>
        <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> HARAP TULIS DAILY ! </span>
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           <?php 
              unset($_SESSION['main_daily_empty']);
          ?>
        </div>
      <?php } ?>

      <?php if(isset($_SESSION['title_empty']) && $_SESSION['title_empty'] == 'gagal'){?>
        <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> JUDUL DAILY TIDAK BOLEH KOSONG ! </span>
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           <?php 
              unset($_SESSION['title_empty']);
          ?>
        </div>
      <?php } ?>

      <?php if(isset($_SESSION['fail_img']) && $_SESSION['fail_img'] == 'no_foto'){?>
        <div style="display: none;" class="alert alert-danger alert-dismissable"> <span style="color: yellow;"> HARAP UPLOAD IMAGE DAILY ! </span>
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           <?php 
              unset($_SESSION['fail_img']);
          ?>
        </div>
      <?php } ?>

      <?php if(isset($_SESSION['nis_empty']) && $_SESSION['nis_empty'] == 'gagal'){?>
        <div style="display: none;" class="alert alert-danger alert-dismissable"> NIS Tidak Boleh Kosong !
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           <?php 
              unset($_SESSION['nis_empty']);
          ?>
        </div>
      <?php } ?>

      <?php if(isset($_SESSION['idgroup_empty']) && $_SESSION['idgroup_empty'] == 'gagal'){?>
        <div style="display: none;" class="alert alert-danger alert-dismissable"> GROUP Tidak Boleh Kosong !
           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           <?php 
              unset($_SESSION['idgroup_empty']);
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
          <h3 class="box-title"> <i class="fa-solid fa-user" style="margin-right: 10px;"></i> <strong id="cdastd"> CREATE DAILY ACTIVITY - STUDENT </strong> </h3>
          <span style="float:right;">
            <a class="btn btn-primary" id="btncarisiswa" onclick="openModalSiswa()">
              <i class="glyphicon glyphicon-plus"></i> Cari Siswa
            </a>
          </span>
        </div>
        <!-- /.box-header -->
       
        <form id="form_announ" action="<?= $basegu; ?>view/daily/create/api_create" method="post" enctype="multipart/form-data">
          <input type="hidden" name="nipguru" value="<?= $dataNIP; ?>">
          <div class="box-body">
            <div class="row">
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
              <!-- <div class="col-sm-2">
                <div class="form-group">
                  <label>JENJANG PENDIDIKAN<sup style="color: red; font-size: 10px;">*</sup></label>
                  <input type="text" class="form-control" id="dataPend" name="jenjang_pendidikan" readonly>
                </div>
              </div> -->
              <div class="col-sm-2">
                <div class="form-group">
                  <label> KELAS<sup style="color: red; font-size: 10px;">*</sup></label>
                  <input type="hidden" name="jenjang_pendidikan" id="dataKelas">
                  <input type="text" class="form-control" readonly disabled id="dataKelas1">
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <div class="box">
                  <div class="box-body">
                    <div class="row">
                      <div class="col-sm-4">
                        <label> Title Daily </label>
                        <input type="text" required id="jdl_daily" class="form-control" name="jdl_daily">
                      </div>
                    </div>
                    <div class="row buatGambar" style="margin-top: -20px; margin-bottom: 15px;">
                      <div id="imgPreview">
                        
                      </div>
                      <br>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
              
                        <label> Description Daily </label>
                        <textarea id="editor" name="editor_catatan"></textarea>
                      
                      </div>
                    </div>
                    </div>
                  </div>  
                  <div class="box-footer">
                    <button type="submit" name="simpan_daily" id="btnSimpan" class="btn btn-success btn-circle"><i class="glyphicon glyphicon-ok"></i> Buat Daily </button>
                  </div>
                </div>
              </div>
            </div>
            
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

                        } else if ($jenjangPendidikan == "SEMUA") {

                          $queryGetAllDataSiswa = "SELECT * FROM siswa WHERE c_kelas <> 'TKBLULUS' ";
                          $execqueryGetAllDataSiswa  = mysqli_query($con, $queryGetAllDataSiswa);
                          $countDatax = mysqli_num_rows($execqueryGetAllDataSiswa);
                        }

                    }

                ?>

                <tbody>
                    <?php foreach ($execqueryGetAllDataSiswa as $data): ?>
                    <tr id="tr_modalsiswa" onclick="
                        siswaSelected(
                            `<?= $data['nis']; ?>`, 
                            `<?= strtoupper($data['nama']); ?>`, 
                            `<?= $data['c_kelas']; ?>`
                            )
                        ">
                            <td style="text-align: center;"> <?= $no++; ?> </td>
                            <?php if ($jenjangPendidikan == "SD"): ?>
                              <td style="text-align: center;"> <?= str_replace(["SD"], " SD", $data['c_kelas']); ?> </td>
                            <?php elseif($jenjangPendidikan == "PAUD"): ?>
                              <td style="text-align: center;"> <?= $data['c_kelas']; ?> </td>
                            <?php else: ?>
                              <td style="text-align: center;"> <?= str_replace(["SD"], " SD", $data['c_kelas']); ?> </td>
                            <?php endif ?>
                            <td style="text-align: center;"> <?= $data['nis']; ?> </td>
                            <td style="text-align: center;"> <?= strtoupper($data['nama']); ?> </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>    
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript" src="<?php echo $base; ?>theme/datetime/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript">

  $(document).ready( function () {

    $("#btnSimpan").hide();

    let sesis = `<?= $sesi; ?>`

    if (sesis == 1) {
      Swal.fire({
        icon: "success",
        title: "Berhasil Membuat Daily !",
        text: "Silahkan Tunggu Persetujuan dari Kepala Sekolah !"
      });
    }

    $("#aList1").click();
    $("#subcreate").click();

    $("#create_data").css({
      "background-color" : "#ccc",
      "color" : "black"
    });

    $("#isiMenu").css({
      "margin-left" : "5px"
    });

    let xhttp = new XMLHttpRequest();
    
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        $(".buatGambar").append(JSON.parse(this.responseText).upload_img)
        $("#gambar").hide()
        const image = document.querySelector("img[id='gambar']"),
        input = document.querySelector(".fileGambar");
        let forImage  = '' 

        input.addEventListener("change", (event) => {
          let filePath          = input.value; 
          let allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

          if (!allowedExtensions.exec(filePath)) {

            alert("Please upload file having extensions .jpg/.jpeg/.png only !");
            $("#gambar").hide()
            input.value = '';
            return false;

          } else {

            const inputFile = input.files[0]
            const limit     = 20000;
            const size      = inputFile.size/1024;

            if (size > limit) {
              
              const err = new Error(`File too big : ${(size/1000).toFixed(2)} MB`);
              $("#gambar").hide()
              input.value = '';
              alert(err);

              return false;

            } else {

              // alert("Ok ukuran pas");

              // option 1 
              // if (input.files && input.files[0]) {
              //   let reader = new FileReader();
              //   reader.onload = function(e) {
              //     document.getElementById("imgPreview").innerHTML = '<img style = "width: 100%; height: 100%; margin-bottom: 15px;" src=" ' + e.target.result + ' " />';
              //   };
              //   reader.readAsDataURL(input.files[0]);
              // }

              // option 2
              $("#gambar").show()
              image.src = URL.createObjectURL(input.files[0]);
              const files = event.target.files;

              for (const file of files) {
                forImage = file.name;
              }

            }

          }

        })

        // $("form#form_announ").on("submit", function(e) {
          
        //   e.preventDefault()

        //   let formData = new FormData(this);
        //   $.ajax({
        //     url         : `<?= $basegu; ?>view/daily/create/api_create`,
        //     type        : "POST",
        //     data        : formData,
        //     cache       : false,
        //     processData : false,
        //     contentType : false,
        //     success     : function(data) {
        //       $('#form_announ').trigger("reset");
        //       $("#gambar").hide()
        //       Swal.fire({
        //         title : "Success send announcement",
        //         text  : "Waiting for Confirm",
        //         icon  : "success"
        //       });
        //       console.log(JSON.parse(data));
        //       // console.log(data);
        //     }
        //   })

        // });

      }
    };

    xhttp.open("GET", `<?= $basegu; ?>data.php`, true);
    xhttp.send();

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
      }
    })

  }

  function siswaSelected(nis, nama, c_kelas){

    let kelas = c_kelas.replace(/SD/g, " SD")

    $('#dataNis').val(nis);
    $('#dataNama').val(nama);
    $('#dataKelas').val(c_kelas);
    $('#dataKelas1').val(kelas);
    $("#btnSimpan").show();
    $('#modalsiswa').modal("hide");

  }

</script>
<script>

  initSample();

</script>