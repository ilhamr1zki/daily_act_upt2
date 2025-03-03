<?php  

    $focus = 0;

    $tahunAjaran1   = "";
    $tahunAjaran2   = "";

    $checkTypeData1 = "";
    $checkTypeData2 = "";
    $reloadPage = 0;

    $isiSemester = [1, 2];

    $timeOut        = $_SESSION['expire'];
    
    $timeRunningOut = time() + 5;

    $timeIsOut = 0;
    $diMenu    = "changepassword";
    $getNumberPhone = 0;

    // echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

    if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

        error_reporting(1);
        $_SESSION['form_success'] = "session_time_out";
        $timeIsOut = 1;
        // exit;

    } else if (isset($_POST['simpan_ubah_nohp'])) {

        $noHp   = htmlspecialchars($_POST['nohp']); 

        $formatNumber = substr($noHp, 0, 2);

        if ($formatNumber == "08") {

            $sqlUpdateNoHp = mysqli_query($con, "
                UPDATE guru SET no_hp = '$noHp' WHERE nip = '$_SESSION[nip_kepsek]'
            ");

            if ($sqlUpdateNoHp == true) {

                $_SESSION['update_nohp'] = 'success';

                $queryNumberPhone = mysqli_query($con, "
                    SELECT no_hp FROM guru WHERE nip = '$_SESSION[nip_kepsek]'
                ");

                $getNumberPhone = mysqli_fetch_array($queryNumberPhone)['no_hp'];

            } else {

                $_SESSION['update_nohp'] = 'failed';

                $queryNumberPhone = mysqli_query($con, "
                    SELECT no_hp FROM guru WHERE nip = '$_SESSION[nip_kepsek]'
                ");

                $getNumberPhone = mysqli_fetch_array($queryNumberPhone)['no_hp'];

            }

        } else {

            $queryNumberPhone = mysqli_query($con, "
                SELECT no_hp FROM guru WHERE nip = '$_SESSION[nip_kepsek]'
            ");

            $getNumberPhone = mysqli_fetch_array($queryNumberPhone)['no_hp'];

            $_SESSION['update_nohp'] = 'format_invalid';

        }

    } else if (isset($_POST['reset_numberphone'])) {

        $queryResetNumberPhone = mysqli_query($con, "
            UPDATE guru SET no_hp = NULL WHERE nip = '$_SESSION[nip_kepsek]' 
        ");

        if ($queryResetNumberPhone) {

            $_SESSION['update_nohp'] = "reset_berhasil";
            
        }

    } else {

        $queryNumberPhone = mysqli_query($con, "
            SELECT no_hp FROM guru WHERE nip = '$_SESSION[nip_kepsek]'
        ");

        $getNumberPhone = mysqli_fetch_array($queryNumberPhone)['no_hp'];

    }

?>

<div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12">

        <?php if(isset($_SESSION['update_nohp']) && $_SESSION['update_nohp'] == 'success'){?>
          <div class="alert alert-warning alert-dismissable"> NO HP / WA Berhasil Di Ubah
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['update_nohp']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['update_nohp']) && $_SESSION['update_nohp'] == 'reset_berhasil'){?>
          <div class="alert alert-warning alert-dismissable"> NO HP / WA Berhasil Di Reset !
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['update_nohp']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['update_nohp']) && $_SESSION['update_nohp'] == 'failed'){?>
          <div style="color: yellow;" class="alert alert-danger alert-dismissable"> NO HP / WA Gagal Di Update
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['update_nohp']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['update_nohp']) && $_SESSION['update_nohp'] == 'format_invalid'){?>
          <div style="color: yellow;" class="alert alert-danger alert-dismissable"> FORMAT NO HP / WA INVALID
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['update_nohp']); ?>
          </div>
        <?php } ?>

    </div>
</div>

<div class="box box-info">
    <form action="#" method="post">
        <div class="box-body" style="margin: 10px;">

            <div class="row">

                <div class="col-sm-3">
                    <div class="form-group">
                        <label> NOMER HP / WA </label>
                        <?php if ($getNumberPhone == 0): ?>
                            <input type="text" required class="form-control" pattern="[0-9]*" inputmode="numeric" onkeypress="return onlyNumberKey(event)" maxlength="13" name="nohp" placeholder="Ex : 08xx xxxx xxxx" value="" id="nohp">
                        <?php else: ?>
                            <input type="text" required class="form-control" pattern="[0-9]*" inputmode="numeric" onkeypress="return onlyNumberKey(event)" maxlength="13" name="nohp" placeholder="Ex : 08xx xxxx xxxx" value="<?= $getNumberPhone; ?>" id="nohp">
                        <?php endif ?>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <?php if ($getNumberPhone == 0): ?>
                            <button class="btn btn-sm btn-success" name="simpan_ubah_nohp"> Save </button>
                        <?php elseif($getNumberPhone != 0): ?>
                            <button class="btn btn-sm btn-success" name="simpan_ubah_nohp"> Save </button>
                            <button class="btn btn-sm btn-danger" name="reset_numberphone"> Reset </button>
                        <?php endif ?>
                    </div>
                </div>

            </div> 

        </div>
    </form>

    
</div>

<script type="text/javascript">
        
    $(document).ready( function () {

        $("#password_lama").focus();    
        $("#list_maintenance").click();
        $("#changenumberphone").css({
            "background-color" : "#ccc",
            "color" : "black"
        });

        if (`<?= $getNumberPhone; ?>` == 0) {
            $("#nohp").focus();
        }

        let newIcon = document.getElementById("addIcon");
        newIcon.classList.remove("fa");
        newIcon.classList.add("glyphicon");
        newIcon.classList.add("glyphicon-phone");

        document.getElementById('isiMenu').innerHTML = `<span style="font-weight: bold; margin-left: 10px;"> MAINTENANCE - </span>` + `<span style="font-weight: bold;"> CHANGE NUMBER PHONE </span>`

    });

    function mouseOver1() {
      document.getElementById("swp1").style.cursor = "pointer";
    }

    function mouseOver2() {
      document.getElementById("swp2").style.cursor = "pointer";
    }

    function mouseOver3() {
      document.getElementById("swp3").style.cursor = "pointer";
    }

    function onlyNumberKey(evt) {

        // Only ASCII character in that range allowed
        let ASCIICode = (evt.which) ? evt.which : evt.keyCode
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
            return false;
        return true;
    }

</script>
