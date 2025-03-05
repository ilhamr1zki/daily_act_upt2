<?php  

    $focus = 0;

    error_reporting(0);

    // echo $_SESSION['key_admin'];

    $tahunAjaran1   = "";
    $tahunAjaran2   = "";

    $checkTypeData1 = "";
    $checkTypeData2 = "";
    $reloadPage = 0;

    $isiSemester = [1, 2];

    $timeOut        = $_SESSION['expire'];
    
    $timeRunningOut = time() + 5;

    $timeIsOut = 0;

    // echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

    if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

        $_SESSION['form_success'] = "session_time_out";

        $timeIsOut = 1;
        error_reporting(1);
        // exit;

    } else if (isset($_POST['simpan_form'])) {
        $tahunAjaran1 = $_POST['tahun_ajaran'];
        $tahunAjaran2 = $_POST['tahun_ajaran_2'];
        $checkTypeData1 = (int) $tahunAjaran1;
        $checkTypeData2 = (int) $tahunAjaran2;

        if ($_POST['tahun_ajaran'] == '' && $_POST['tahun_ajaran_2'] == '') {

            $_SESSION['form_empty'] = 'twoform_empty';
            $focus = 1;

        } else if ($_POST['tahun_ajaran'] == '') {

            $tahunAjaran2 = $_POST['tahun_ajaran_2'];
            $_SESSION['form_empty'] = 'form1_empty';
            $focus = 1;

        } else if ($_POST['tahun_ajaran_2'] == '') {

            $_SESSION['form_empty'] = 'form2_empty';
            $tahunAjaran1 = $_POST['tahun_ajaran'];
            $tahunAjaran2 = "";
            $focus = 2;

        } else {

            // Jika Kedua Input bukan angka
            if ($checkTypeData1 == 0 && $checkTypeData2 == 0) {

                $_SESSION['form_error'] = 'must_number';
                $tahunAjaran1 = "";
                $tahunAjaran2 = "";
                $focus = 1;

            // Jika input 1 bukan berupa angka
            } elseif ($checkTypeData1 == 0) {

                $_SESSION['form_error'] = 'must_number';
                $tahunAjaran1 = "";
                $tahunAjaran2 = "";
                $focus = 1;

            // Jika input 2 bukan berupa angka
            } elseif ($checkTypeData2 == 0) {

                $_SESSION['form_error'] = 'must_number';
                $tahunAjaran1 = "";
                $tahunAjaran2 = "";
                $focus = 1;

            } else if (strlen($_POST['tahun_ajaran']) == 4 && strlen($_POST['tahun_ajaran_2']) == 4) {

                // echo " OK masuk";exit;
                // Insert to DB
                $tahunAjaran = $_POST['tahun_ajaran'] . '/' . $_POST['tahun_ajaran_2'];
                $c_role      = $_SESSION['key_admin'];
                $semester    = $_POST['isi_semester'];
                $status      = "aktif";

                $queryGetDataAktif = mysqli_query($con, "SELECT * FROM tahun_ajaran WHERE c_role = '$c_role' ");

                $checkData = mysqli_num_rows($queryGetDataAktif);

                if ($checkData != 0) {

                    mysqli_query($con, "UPDATE `daily_activity`.`tahun_ajaran` SET `tahun`='$tahunAjaran', `semester`='$semester', `status`='$status' WHERE  `c_role`='$c_role'");

                    $reloadPage = 1;

                    $_SESSION['form_success'] = 'berhasil';

                } else {
                    // echo $countData;
                    mysqli_query($con, "INSERT INTO tahun_ajaran (`id_tahun_ajaran`, `c_role`, `tahun`, `semester`, `status`) VALUES ('', '$c_role', '$tahunAjaran', '$semester', '$status')");
                    $reloadPage = 1;

                    $_SESSION['form_success'] = 'berhasil';
                }

            } else if (strlen($_POST['tahun_ajaran']) != 4) {

                $tahunAjaran1 = "";
                $tahunAjaran2 = $_POST['tahun_ajaran_2'];
                $_SESSION['form_error'] = 'form1_must_number';
                $focus = 1;

            } elseif (strlen($_POST['tahun_ajaran_2']) != 4) {

                $_SESSION['form_error'] = 'form2_must_number';
                $tahunAjaran1 = $_POST['tahun_ajaran'];
                $tahunAjaran2 = "";
                $focus = 2;

            }

        }

    } else if (isset($_POST['reset_form'])) {

        mysqli_query($con, "UPDATE tahun_ajaran SET tahun = NULL, semester = NULL, status = NULL WHERE c_role = '$_SESSION[key_admin]' ");
        $_SESSION['form_success'] = 'reset_form';
        $focus = 1;
        $reloadPage = 1;

    }

    $dataTahun = mysqli_query($con, "SELECT * FROM tahun_ajaran WHERE status = 'aktif' AND c_role = '$_SESSION[key_admin]' ");
    $data_tahun = mysqli_query($con, "SELECT tahun FROM tahun_ajaran WHERE c_role = '$_SESSION[key_admin]' ");
    $data_tahun = mysqli_fetch_assoc($data_tahun)['tahun'];

    if ($data_tahun == NULL) {
        $data_tahun = "kosong";
    } else {
        $data_tahun;
    }

    $countData = mysqli_num_rows($dataTahun);

    if ($countData != 0) {

        $queryDataTahun     = mysqli_query($con, "SELECT tahun FROM tahun_ajaran WHERE status = 'aktif' AND c_role = '$_SESSION[key_admin]' ");
        $queryDataSemester  = mysqli_query($con, "SELECT semester FROM tahun_ajaran WHERE status = 'aktif' AND c_role = '$_SESSION[key_admin]' ");
        $queryDataStatus    = mysqli_query($con, "SELECT status FROM tahun_ajaran WHERE status = 'aktif'");

        $getDataTahun       = mysqli_fetch_assoc($queryDataTahun)['tahun'];
        $getDataSemester    = mysqli_fetch_assoc($queryDataSemester)['semester'];
        $getDataStatus      = mysqli_fetch_assoc($queryDataStatus)['status'];

        $tahunAjaran1  = substr($getDataTahun,0,4);
        $tahunAjaran2  = substr($getDataTahun,5,4);

        $getDataSemester;
    } else {
        // echo $countData;
        $tahunAjaran1  = "";
        $tahunAjaran2  = "";
        $getDataSemester = "";
    }

?>

<div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12">

        <?php if(isset($_SESSION['form_error']) && $_SESSION['form_error'] == 'must_number'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Input Invalid
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['form_error']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_error']) && $_SESSION['form_error'] == 'form1_must_number'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Tahun Ajaran di Input 1 Wajib Di isi 4 Digit Angka
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['form_error']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'session_time_out'){?>
            <div style="display: none;" class="alert alert-danger alert-dismissable"> Waktu Sesi Telah Habis
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php 
                    unset($_SESSION['form_success']); 
                ?>
            </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_error']) && $_SESSION['form_error'] == 'form2_must_number'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Tahun Ajaran di Input 2 Wajib Di isi 4 Digit Angka
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['form_error']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'berhasil'){?>
          <div style="display: none;" class="alert alert-warning alert-dismissable"> Data Berhasil Di Simpan
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['form_success']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'reset_form'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Data Tahun Ajaran Di Reset
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['form_success']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_empty']) && $_SESSION['form_empty'] == 'twoform_empty'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Form Input 1 & Input 2 Tidak Boleh Kosong
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['form_empty']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_empty']) && $_SESSION['form_empty'] == 'form1_empty'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Tahun Ajaran di Input 1 Harap Di Isi
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['form_empty']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_empty']) && $_SESSION['form_empty'] == 'form2_empty'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Tahun Ajaran di Input 2 Harap Di Isi
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['form_empty']); ?>
          </div>
        <?php } ?>

    </div>
</div>

<div class="box box-info">
    <form action="tahunajaran" method="post">
        <div class="box-body" style="margin: 10px;">

            <div class="row">

                <div class="col-sm-8">
                    <div class="form-group">
                        <label> Tahun Ajaran</label>
                        <input type="text" name="tahun_ajaran" placeholder="input 1" value="<?= $tahunAjaran1; ?>" id="tahun_ajaran">
                        <input type="text" id="slashx" value="/" readonly style="background-color: white; border: 0px;">
                        <input type="text" name="tahun_ajaran_2" placeholder="input 2" value="<?= $tahunAjaran2; ?>" id="tahun_ajaran_2">
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-sm-8">
                    <div class="form-group">
                        <label> Semester </label>
                        <select id="semesterx" style="text-align: center;" name="isi_semester">
                            <?php foreach ($isiSemester as $semester): ?>
                                <option value="<?= $semester; ?>" <?=($semester == $getDataSemester )?'selected="selected"':''?> > <?= $semester; ?> </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-sm-3" style="display: flex; gap: 10px;">
                    <div class="form-group">
                        <button class="btn btn-sm btn-success" name="simpan_form"> Save </button>
                    </div>
                    <?php if ($data_tahun != "kosong"): ?>
                        <div class="form-group">
                            <button class="btn btn-sm btn-danger" name="reset_form"> Reset </button>
                        </div>
                    <?php endif ?>
                </div>

            </div> 

        </div>
    </form>

    
</div>

<hr class="new1"></hr>

<script type="text/javascript">

    let newIcon = document.getElementById("addIcon");
    newIcon.classList.remove("fa");
    newIcon.classList.add("glyphicon");
    newIcon.classList.add("glyphicon-text-width");

    let getTitleList2 = document.getElementById('isiList3').innerHTML;
    
    $("#isiMenu").css({
        "margin-left" : "5px"
    });

    document.getElementById('isiMenu').innerHTML = getTitleList2    
        
    let dataFocus  = `<?= $focus; ?>`
    let reloadPage = `<?= $reloadPage; ?>`
    let tahunAjar1 = `<?= $tahunAjaran1; ?>` 
    let tahunAjar2 = `<?= $tahunAjaran2; ?>` 

    if (dataFocus == 1) {
        $("#tahun_ajaran").focus();
    } else if (dataFocus == 2) {
        $("#tahun_ajaran").val(`<?= $_POST['tahun_ajaran']; ?>`);
        $("#tahun_ajaran_2").focus();
    } else if (dataFocus == 3) {
        $("#tahun_ajaran").focus();
        $("#tahun_ajaran_2").val(`<?= $_POST['tahun_ajaran_2']; ?>`);
    } else if (dataFocus == 0) {
        let checkDatas = $("#tahun_ajaran").val();
        if(checkDatas == '') {
            $("#tahun_ajaran").focus();
        } 
    }

    if (reloadPage == 1) {
        setTimeout(refreshPage, 1000);
    }

    function refreshPage() {
        $.ajax({
            url : `<?= $basead; ?>tahunajaran`,
            type : 'post',
            data : {
                coba : "ok"
            },
            success:function(data){
                location.replace(`<?= $basead; ?>tahunajaran`)
            }
        })
    }

    $('#tahun_ajaran').keypress(function (e) {
        if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
    });

    $('#tahun_ajaran_2').keypress(function (e) {
        if (String.fromCharCode(e.keyCode).match(/[^0-9]/g)) return false;
    });

    $(document).ready( function () {
        $("#list_maintenance").click();
        $("#tahunajaran").css({
            "background-color" : "#ccc",
            "color" : "black"
        });
    });
    

</script>
