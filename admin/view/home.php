<?php  

    $timeOut        = $_SESSION['expire'];
    
    $timeRunningOut = time() + 5;

    $timeIsOut = 0;

    // echo "Random String : " . random(5) . "<br>";

    echo "Waktu Habis : " . $timeOut . " Waktu Berjalan : " . $timeRunningOut;

    if ($timeRunningOut == $timeOut || $timeRunningOut > $timeOut) {

        $_SESSION['form_success'] = "session_time_out";
        $timeIsOut = 1;
        // exit;
        error_reporting(1);

    } 

    $getDataKepsek = mysqli_query($con, "
        SELECT nip, nama, username FROM guru 
        WHERE 
        nip = 2019032 
        OR nip = 2019034 
    ");

    $arrNip = [];
    $arrNam = [];
    $arrUsr = [];

    $passwordKepsek = password_hash("aiiskepsek", PASSWORD_DEFAULT);
    // echo "<br>" . $passwordKepsek;

    // foreach ($getDataKepsek as $kepsek) {
    //     $arrNip[] = $kepsek['nip'];
    //     $arrNam[] = $kepsek['nama'];
    //     $arrUsr[] = $kepsek['username'];
        
    // }

    // for ($i=0; $i < count($arrNip); $i++) {
    //     mysqli_query($con, "
    //         INSERT INTO kepala_sekolah
    //         SET 
    //         nip         = '$arrNip[$i]',
    //         nama        = '$arrNam[$i]',
    //         username    = '$arrUsr[$i]',
    //         password    = '$passwordKepsek' 
    //     ");
    // }


?>