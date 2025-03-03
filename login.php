<?php 

  require 'php/config.php'; 

  $sesiLogin  = 0;
  $isGroup    = 0;

  // Cek status login user jika ada session
  if ($user->isLoggedInAdmin()) {
      header("location: $basead"); //redirect ke index
  } elseif ($user->isLoggedInHeadMaster()) {
      header("location: $basekepsek"); //redirect ke index
  } elseif ($user->isLoggedInGuru()) {
      header("location: $basegu"); //redirect ke index
  } elseif ($user->isLoggedInOTM()) {
      header("location: $basewam"); //redirect ke index
  }

  if (!empty($_GET['on'])) {

    $roomkey = $_GET['on'];

    // Cari Room Key Apakah Ada
    $queryFindRoomKey = mysqli_query($con, "

      SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkey'

    ");

    $countRoomKey = mysqli_num_rows($queryFindRoomKey);

    if ($countRoomKey == 1) {

      $sesiLogin = 1;
      
      $queryDailyStd = mysqli_query($con, "
        SELECT id FROM daily_siswa_approved WHERE id IN (
          SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkey'
        )
      ");

      $queryDailyGroup = mysqli_query($con, "
        SELECT id FROM group_siswa_approved WHERE id IN (
          SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkey'
        )
      ");

      $countDailyStd    = mysqli_num_rows($queryDailyStd);
      $countDailyGroup  = mysqli_num_rows($queryDailyGroup);

      if ($countDailyStd == 1) {
        $isGroup = 0;
      } else if ($countDailyGroup) {
        $isGroup = 1;
      }

    } else {

      header("Location: $base");

    }

  }

  $error = "no_session_error";

  if(isset($_POST['username']) and isset($_POST['password']) ){
    $username         = htmlspecialchars($_POST['username']);
    $password         = mysqli_real_escape_string($con, htmlspecialchars($_POST['password']));
    $isFromNotif      = htmlspecialchars($_POST['fromnotif']);
    $isRoomKey        = htmlspecialchars($_POST['room_key']);
    $personalorgroup  = htmlspecialchars($_POST['isgroup']);

    $role = $_POST['sebagai'];

    if ($role == 'admin') {

      if ($user->loginAdmin($username, $password)) {
        header("location: $basead");
      } else {
        
        $error      = $user->getLastError();

      }

    } else if ($role == 'kepsek') {

      if ($isFromNotif == "no") {

        if ($user->loginHeadMaster($username, $password)) {
          header("location: $basekepsek");
        } else {
          
          $error      = $user->getLastError();

        }

      } else if ($isFromNotif == "yes") {

        $hash = password_hash("*_@1154ct1vit135_*" . $role, PASSWORD_DEFAULT);

        if ($user->loginNotifHeadMaster($username, $password, $isRoomKey)) {
          header("location: fromnotif?role=$hash&roomkey=$isRoomKey&stdorgroup=$personalorgroup");
        } else {
          
          $error      = $user->getLastError();

        }


      }

    } else if ($role == 'guru') {

      if ($isFromNotif == "no") {

        if ($user->loginGuru($username, $password)) {
          header("location: $basegu");
        } else {
          
          $error      = $user->getLastError();

        }

      } else if ($isFromNotif == "yes") {

        $hash = password_hash("*_@1154ct1vit135_*" . $role, PASSWORD_DEFAULT);

        if ($user->loginNotifGuru($username, $password, $isRoomKey)) {
          header("location: fromnotif?role=$hash&roomkey=$isRoomKey&stdorgroup=$personalorgroup");
        } else {
          
          $error      = $user->getLastError();

        }

      }

    } else if ($role == 'otm') {

      if ($isFromNotif == "no") {

        if ($user->loginOtm($username, $password)) {
          header("location: $basewam");
        } else {
          $error      = $user->getLastError();
        }

      } else if ($isFromNotif == "yes") {
        
        $hash = password_hash("*_@1154ct1vit135_*" . $role, PASSWORD_DEFAULT);

        if ($user->loginNotifOtm($username, $password, $isRoomKey)) {
          header("location: fromnotif?role=$hash&roomkey=$isRoomKey&stdorgroup=$personalorgroup");
        } else {
          
          $error      = $user->getLastError();

        }

      }

    }
  } 

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> AIIS - Daily Activity </title>
  <link rel="icon" href="favicon.ico">
  <link rel="shortcut icon" href="imgstatis/favicon.jpg">
  <script type="text/javascript" src="jquery.js"></script> 
  
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="theme/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="theme/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="theme/plugins/iCheck/square/blue.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="theme/plugins/select2/select2.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="Theme/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="Theme/dist/css/skins/_all-skins.min.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <style type="text/css">
      #sh_pw {
        cursor: pointer;
      }
    </style>
  
</head>


<?php ?>
<body style="background:url(imgstatis/back1.jpg)
no-repeat center center fixed; background-size: cover;
 -webkit-background-size: cover; 
 -moz-background-size: cover; -o-background-size: cover;">
 <div class="row">
<div class="login-box">
  <div class="login-logo">
    <h3 style="color:#fff;">AIIS - Daily Activity<br><p><?php echo $aplikasi['namasek']; ?></p></h3>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg" style="font-size:100%;">Masukkan Username/NIS dan Password</p>  
    <?php 
      if($error != 'no_session_error'){
    ?>
      <p>
        <div style="display: none;" class="alert alert-danger alert-dismissable">Username atau Password Salah!
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        </div>
      </p>
    <?php 
      }
    ?>

    <?php if ($sesiLogin == 1 && $isGroup == 1): ?>

      <form action="#" method="post">
        <div class="form-group has-feedback">
          <input type="hidden" name="fromnotif" value="yes">
          <input type="hidden" name="room_key" value="<?= $roomkey; ?>">
          <input type="hidden" name="isgroup" value="<?= $isGroup; ?>">
          <input type="text" name="username" class="form-control" placeholder="Username/NIS" required="" autocomplete="" autofocus="">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" name="password" id="pw" class="form-control" placeholder="Password" required="" autocomplete="off">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <label> SHOW PASSWORD </label>
          <i class="glyphicon glyphicon-eye-close" id="sh_pw"></i>
        </div>
        <div class="form-group">
          <label>*Login Sebagai </label>
          <select name="sebagai" class="form-control form-select" id="select2">
            <option value="kepsek"> HEAD MASTER </option>
            <option value="guru"> TEACHER </option>
            <option value="otm"> PARENTS </option>
          </select>
        </div>
          <button type="submit" name="submit" class="btn btn-primary btn-block btn-flat" style="font-size: 15px;">Login <i class="glyphicon glyphicon-log-in"></i></button>

      </form> 

    <?php elseif($sesiLogin == 1 && $isGroup == 0): ?>

      <form action="#" method="post">
        <div class="form-group has-feedback">
          <input type="hidden" name="fromnotif" value="yes">
          <input type="hidden" name="room_key" value="<?= $roomkey; ?>">
          <input type="hidden" name="isgroup" value="<?= $isGroup; ?>">
          <input type="text" name="username" class="form-control" placeholder="Username/NIS" required="" autocomplete="" autofocus="">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" name="password" id="pw" class="form-control eye_paswd3" placeholder="Password" required="" autocomplete="off">
          <span id="myCheck" class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <label> SHOW PASSWORD </label>
          <i class="glyphicon glyphicon-eye-close" id="sh_pw"></i>
        </div>
        <div class="form-group">
          <label>*Login Sebagai </label>
          <select name="sebagai" class="form-control form-select" id="select2">
            <option value="kepsek"> HEAD MASTER </option>
            <option value="guru"> TEACHER </option>
            <option value="otm"> PARENTS </option>
          </select>
        </div>
          <button type="submit" name="submit" class="btn btn-primary btn-block btn-flat" style="font-size: 15px;">Login <i class="glyphicon glyphicon-log-in"></i></button>

      </form> 

    <?php elseif($sesiLogin == 0): ?>

      <form action="#" method="post">
        <div class="form-group has-feedback">
          <input type="hidden" name="fromnotif" value="no">
          <input type="hidden" name="room_key" value="kosong">
          <input type="hidden" name="isgroup" value="kosong">
          <input type="text" name="username" class="form-control" placeholder="Username/NIS" required="" autocomplete="" autofocus="">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" name="password" id="pw" class="form-control eye_paswd3" placeholder="Password" required="" autocomplete="off">
          <span id="myCheck" class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <label> SHOW PASSWORD </label>
          <i class="glyphicon glyphicon-eye-close" id="sh_pw"></i>
        </div>
        <div class="form-group">
          <label>*Login Sebagai </label>
          <select name="sebagai" class="form-control form-select" id="select2">
            <option value="admin"> ADMINISTRATOR </option> 
            <option value="kepsek"> HEAD MASTER </option>
            <option value="guru"> TEACHER </option>
            <option value="otm"> PARENTS </option>
          </select>
        </div>
          <button type="submit" name="submit" class="btn btn-primary btn-block btn-flat" style="font-size: 15px;">Login <i class="glyphicon glyphicon-log-in"></i></button>

      </form>  
      
    <?php endif ?>
    
  </div>
  <!-- /.login-box-body -->
</div>
</div>
<!-- jQuery 2.2.3 -->
<script src="theme/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="theme/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="theme/plugins/iCheck/icheck.min.js"></script>
<!-- Select2 -->
<script src="theme/plugins/select2/select2.full.min.js"></script>
<script>

  function myFunction() {
    document.getElementById("myCheck").click();
  } 

  $("#sh_pw").click(function(){
    let x = document.getElementById("pw");
      if (x.type === "password") {
        x.type = "text";
        $(this).removeClass("glyphicon-eye-close")
        $(this).addClass("glyphicon-eye-open")
      } else {
        x.type = "password";
        $(this).removeClass("glyphicon-eye-open")
        $(this).addClass("glyphicon-eye-close")
    }
  })

  $(function () {
    //Initialize Select2 Elements
    $("#select2").select2();
  });
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });

</script>
<script>
//angka 500 dibawah ini artinya pesan akan muncul dalam 0,5 detik setelah document ready
$(document).ready(function(){setTimeout(function(){$(".alert").fadeIn('fast');}, 100);});
//angka 3000 dibawah ini artinya pesan akan hilang dalam 3 detik setelah muncul
setTimeout(function(){$(".alert").fadeOut('fast');}, 5000);

</script>
</body>
</html>

