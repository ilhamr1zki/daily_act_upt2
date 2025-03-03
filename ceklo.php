<?php require 'php/config.php';


if(isset($_POST['username']) and isset($_POST['password'])) {

  if($_POST['sebagai'] == 'admin'){

    $usernamenya = strtolower($_POST['username']);

     // Cek Data User Accounting
    $sqlGetUser         = "SELECT * FROM admin WHERE username = '$usernamenya' ";
    $execQueryGetUser   = mysqli_query($con, $sqlGetUser);

    $countData          = mysqli_num_rows($execQueryGetUser);

    if ($countData == 1) {

      $getData      = mysqli_fetch_array($execQueryGetUser);

      $dataPassword = $getData['password'];

      if (password_verify($_POST['password'], $dataPassword)) {

          session_start();
          $_SESSION['key_admin'] = $getData['c_admin'];
          $_SESSION['start_sess']   = time();
          // Session Will Be Expired after 30 Minute
          $_SESSION['expire']       = $_SESSION['start_sess'] + (30 * 60);
          header('location:admin/');
          exit;

      } else {

        session_start();
        $_SESSION['pesan'] = 'gagal';
        header('location:login');
        exit;
      }

    }

  } elseif ($_POST['sebagai'] == 'kepsek') {

    $usernamenya = strtolower($_POST['username']);

     // Cek Data User Accounting
    $sqlGetUser         = "SELECT * FROM admin WHERE username = '$usernamenya' ";
    $execQueryGetUser   = mysqli_query($con, $sqlGetUser);

    $countData          = mysqli_num_rows($execQueryGetUser);

    if ($countData == 1) {

      $getData      = mysqli_fetch_array($execQueryGetUser);

      $dataPassword = $getData['password'];

      if (password_verify($_POST['password'], $dataPassword)) {

          session_start();
          $_SESSION['x_admin'] = $getData['c_admin'];
          $_SESSION['start_sess']   = time();
          // Session Will Be Expired after 30 Minute
          $_SESSION['expire']       = $_SESSION['start_sess'] + (30 * 60);
          header('location:admin/');
          exit;

      } else {

        session_start();
        $_SESSION['pesan'] = 'gagal';
        header('location:login');
        exit;
      }

    }

  } elseif ($_POST['sebagai'] == 'guru') {

    $usernamenya = strtolower($_POST['username']);

     // Cek Data User Accounting
    $sqlGetUser         = "SELECT * FROM guru WHERE username = '$usernamenya' ";
    $execQueryGetUser   = mysqli_query($con, $sqlGetUser);

    $countData          = mysqli_num_rows($execQueryGetUser);

    if ($countData == 1) {

      $getData      = mysqli_fetch_array($execQueryGetUser);

      $dataPassword = $getData['password'];

      if (password_verify($_POST['password'], $dataPassword)) {

          session_start();
          $_SESSION['key_guru'] = $getData['c_guru'];
          $_SESSION['start_sess']   = time();
          // Session Will Be Expired after 30 Minute
          $_SESSION['expire']       = $_SESSION['start_sess'] + (30 * 60);
          header('location:guru/');
          exit;

      } else {

        session_start();
        $_SESSION['pesan'] = 'gagal';
        header('location:login');
        exit;
      }

    }

  }

  else{header('location:login');}
}
else{header('location:login');}
?>