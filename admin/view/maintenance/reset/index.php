<?php  

    $focus = 0;

    $tahunAjaran1   = "";
    $tahunAjaran2   = "";

    $checkTypeData1 = "";
    $checkTypeData2 = "";
    $reloadPage = 0;

    $isiSemester = [1, 2];

    $timeOut        = $_SESSION['expire_paud'];
    
    $timeRunningOut = time() + 5;

    $timeIsOut = 0;

    // echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

    if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

        error_reporting(1);
        $_SESSION['form_success'] = "session_time_out";
        $timeIsOut = 1;
        // exit;

    } else if (isset($_POST['reset_daily'])) {


        $arr = [];

        $daily = $_POST['is_daily'];
        // echo $daily;exit;

        $arr[] = $daily;
        echo json_encode($arr);
        // exit;

    }

?>

<div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12">

        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'change_password_success'){?>
          <div style="display: none;" class="alert alert-warning alert-dismissable"> Password Berhasil Di Ubah
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['form_success']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'change_password_error'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Password Sekarang Salah
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['form_success']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'new_password_error'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Password Baru Dan Konfirmasi Password Tidak Sama
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <?php unset($_SESSION['form_success']); ?>
          </div>
        <?php } ?>

        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'change_password_too_short'){?>
          <div style="display: none;" class="alert alert-danger alert-dismissable"> Panjang Password Baru Minimal 5 Karakter
             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
             <!-- <?php unset($_SESSION['form_success']); ?> -->
          </div>
        <?php } ?>

    </div>
</div>

<div class="box box-info">
    <!-- <form action="changepassword" method="post"> -->
        <div class="box-body" style="margin: 10px;">

            <div class="row">

                <div class="col-sm-3">
                    <div class="form-group">
                        <label> Reset All Data Daily </label>
                        <input type="text" disabled readonly class="form-control" name="reset_all" placeholder="RESET DAILY SISWA, GROUP, KOMENTAR" id="reset_all">
                    </div>  
                </div>

            </div>

            <div class="row">

                <div class="col-sm-3">
                    <div class="form-group">
                        <button class="btn btn-sm btn-danger" id="reset_daily"> RESET </button>
                    </div>
                </div>

            </div> 

        </div>
    <!-- </form> -->
    
</div>

<script type="text/javascript">
        
    $(document).ready( function () {

        $("#password_lama").focus();    
        $("#list_maintenance").click();
        $("#resetdata").css({
            "background-color" : "#ccc",
            "color" : "black"
        });

        $("#reset_daily").click(function() {
            $("#hapus").show();
        });

        $(".reset_btn").click(function() {

            $.ajax({
                url   : `<?= $basead; ?>resetdatadaily`,
                type  : 'POST',
                data  : {
                  reset_daily   : true,
                  is_daily      : "all"
                },
                success:function(data){
                    let res = JSON.parse(data)[0]
                    if (res == "success") {
                        Swal.fire({
                          title: "Berhasil di Reset",
                          icon: "success"
                        });
                        setTimeout(() => {
                            location.href = `<?= $basead; ?>resetdata`
                        }, 1000);
                    } 
                }
            });

        })

        $(".cancel").click(function() {
            $("#hapus").hide();
        })

        $(".cls").click(function() {
            $("#hapus").hide();
        })

        let newIcon = document.getElementById("addIcon");
        newIcon.classList.remove("fa");
        newIcon.classList.add("glyphicon");
        newIcon.classList.add("glyphicon-retweet");

        document.getElementById('isiMenu').innerHTML = `<span style="font-weight: bold; margin-left: 10px;"> MAINTENANCE - </span>` + `<span style="font-weight: bold;"> RESET </span>`

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

</script>
