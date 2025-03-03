<?php  
  
  require 'php/config.php'; 

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
    $result = $tanggal ." ". $bulan ." ". $tahun . " ";       
    return($result);  
  }

  // echo $_GET['roomkey'];
  $rolekepsek   = password_verify("*_@1154ct1vit135_*kepsek", $_GET['role']);
  $roleguru     = password_verify("*_@1154ct1vit135_*guru", $_GET['role']);
  $roleotm      = password_verify("*_@1154ct1vit135_*otm", $_GET['role']);
  $roomkeys     = htmlspecialchars($_GET['roomkey']);
  $stdorgroup   = htmlspecialchars($_GET['stdorgroup']);

  $kepsek_std   = 0;
  $kepsek_group = 0;

  $guru_std     = 0;
  $guru_group   = 0;

  $otm_std      = 0;
  $otm_group    = 0;
  $nipKepsek    = "";

  // Kepsek Daily Personal
  if ($rolekepsek == 1 && $stdorgroup == 0) {

    $kepsek_std = 1;

    // Cari Daily Student
    $queryFindStudentName = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nama AS nama_siswa 
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getStudentName = mysqli_fetch_assoc($queryFindStudentName)['nama_siswa'];

    // Cari NIS Siswa
    $queryFindStudentNIS = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nis AS nis_siswa 
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getNis = mysqli_fetch_assoc($queryFindStudentNIS)['nis_siswa'];

    // Cari Foto Daily Siswa 
    $queryFindImageDailyStd = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nis AS nis_siswa,
      daily_siswa_approved.image as foto_daily
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getImageUploadDailyStd = mysqli_fetch_assoc($queryFindImageDailyStd)['foto_daily'];

    // Cari Tanggal Daily Siswa
    $queryFindDateDailyStd = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nis AS nis_siswa,
      daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getDateDailyStd = mysqli_fetch_assoc($queryFindDateDailyStd)['tgl_disetujui'];

    // Cari Judul Daily Siswa
    $queryFindTitleDailyStd = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nis AS nis_siswa,
      daily_siswa_approved.title_daily as judul_daily
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getTitleDailyStd = mysqli_fetch_assoc($queryFindTitleDailyStd)['judul_daily'];

    // Cari Isi Daily Siswa
    $queryFindContentDailyStd = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nis AS nis_siswa,
      daily_siswa_approved.isi_daily as isi_daily
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getContentDailyStd = mysqli_fetch_assoc($queryFindContentDailyStd)['isi_daily'];

  } 

  // Kepsek Daily Group
  else if ($rolekepsek == 1 && $stdorgroup == 1) {

    $kepsek_group = 1;

    // Cari Daily Group
    $queryFindNameGroup = mysqli_query($con, "
      SELECT 
      group_siswa_approved.id AS id_group,
      group_kelas.nama_group_kelas AS nama_group 
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getNameGroup = mysqli_fetch_assoc($queryFindNameGroup)['nama_group'];

    // Cari Daily Id Group
    $queryFindIdGroup = mysqli_query($con, "
      SELECT 
      group_kelas.id AS id_group
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getIdGroup = mysqli_fetch_assoc($queryFindIdGroup)['id_group'];

    // Cari Foto Daily Group 
    $queryFindImageDailyGroup = mysqli_query($con, "
      SELECT 
      group_siswa_approved.image AS foto_daily
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getImageUploadDailyGroup = mysqli_fetch_assoc($queryFindImageDailyGroup)['foto_daily'];

    // Cari Tanggal Daily Group
    $queryFindDateDailyGroup = mysqli_query($con, "
      SELECT 
      group_siswa_approved.tanggal_disetujui_atau_tidak AS tgl_disetujui
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getDateDailyGroup = mysqli_fetch_assoc($queryFindDateDailyGroup)['tgl_disetujui'];

    // Cari Judul Daily Group
    $queryFindTitleDailyGroup = mysqli_query($con, "
      SELECT 
      group_siswa_approved.title_daily AS judul_daily
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getTitleDailyGroup = mysqli_fetch_assoc($queryFindTitleDailyGroup)['judul_daily'];

    // Cari Isi Daily Group
    $queryFindContentDailyGroup = mysqli_query($con, "
      SELECT 
      group_siswa_approved.isi_daily AS isi_daily 
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getContentDailyGroup = mysqli_fetch_assoc($queryFindContentDailyGroup)['isi_daily'];

  } 

  // Guru Daily Personal
  else if ($roleguru == 1 && $stdorgroup == 0) {

    $guru_std = 1;

    // Cari Daily Student
    $queryFindStudentName = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nama AS nama_siswa 
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getStudentName = mysqli_fetch_assoc($queryFindStudentName)['nama_siswa'];

    // Cari NIS Siswa
    $queryFindStudentNIS = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nis AS nis_siswa 
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getNis = mysqli_fetch_assoc($queryFindStudentNIS)['nis_siswa'];

    // Cari Foto Daily Siswa 
    $queryFindImageDailyStd = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nis AS nis_siswa,
      daily_siswa_approved.image as foto_daily
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getImageUploadDailyStd = mysqli_fetch_assoc($queryFindImageDailyStd)['foto_daily'];

    // Cari Tanggal Daily Siswa
    $queryFindDateDailyStd = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nis AS nis_siswa,
      daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getDateDailyStd = mysqli_fetch_assoc($queryFindDateDailyStd)['tgl_disetujui'];

    // Cari Judul Daily Siswa
    $queryFindTitleDailyStd = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nis AS nis_siswa,
      daily_siswa_approved.title_daily as judul_daily
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getTitleDailyStd = mysqli_fetch_assoc($queryFindTitleDailyStd)['judul_daily'];

    // Cari Isi Daily Siswa
    $queryFindContentDailyStd = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nis AS nis_siswa,
      daily_siswa_approved.isi_daily as isi_daily
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getContentDailyStd = mysqli_fetch_assoc($queryFindContentDailyStd)['isi_daily'];

  } 

  // Guru Daily Group
  else if ($roleguru == 1 && $stdorgroup == 1) {

    $guru_group = 1;

    // Cari Daily Group
    $queryFindNameGroup = mysqli_query($con, "
      SELECT 
      group_siswa_approved.id AS id_group,
      group_kelas.nama_group_kelas AS nama_group 
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getNameGroup = mysqli_fetch_assoc($queryFindNameGroup)['nama_group'];

    // Cari Daily Id Group
    $queryFindIdGroup = mysqli_query($con, "
      SELECT 
      group_kelas.id AS id_group
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getIdGroup = mysqli_fetch_assoc($queryFindIdGroup)['id_group'];

    // Cari Foto Daily Group 
    $queryFindImageDailyGroup = mysqli_query($con, "
      SELECT 
      group_siswa_approved.image AS foto_daily
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getImageUploadDailyGroup = mysqli_fetch_assoc($queryFindImageDailyGroup)['foto_daily'];

    // Cari Tanggal Daily Group
    $queryFindDateDailyGroup = mysqli_query($con, "
      SELECT 
      group_siswa_approved.tanggal_disetujui_atau_tidak AS tgl_disetujui
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getDateDailyGroup = mysqli_fetch_assoc($queryFindDateDailyGroup)['tgl_disetujui'];

    // Cari Judul Daily Group
    $queryFindTitleDailyGroup = mysqli_query($con, "
      SELECT 
      group_siswa_approved.title_daily AS judul_daily
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getTitleDailyGroup = mysqli_fetch_assoc($queryFindTitleDailyGroup)['judul_daily'];

    // Cari Isi Daily Group
    $queryFindContentDailyGroup = mysqli_query($con, "
      SELECT 
      group_siswa_approved.isi_daily AS isi_daily 
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getContentDailyGroup = mysqli_fetch_assoc($queryFindContentDailyGroup)['isi_daily'];
    // echo $getContentDailyGroup;exit;

  } 

  // OTM Daily Personal
  else if ($roleotm == 1 && $stdorgroup == 0) {

    $otm_std = 1;

    // Cari NIP Guru
    $queryFindNip = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      guru.nip AS nip_guru 
      FROM daily_siswa_approved
      LEFT JOIN guru
      ON daily_siswa_approved.from_nip = guru.nip
      WHERE daily_siswa_approved.from_nip IN (
        SELECT created_by FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getNIP = mysqli_fetch_array($queryFindNip)['nip_guru'];

    // Cari Daily Student
    $queryFindStudentName = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nama AS nama_siswa 
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getStudentName = mysqli_fetch_assoc($queryFindStudentName)['nama_siswa'];

    // Cari NIS Siswa
    $queryFindStudentNIS = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nis AS nis_siswa 
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getNis = mysqli_fetch_assoc($queryFindStudentNIS)['nis_siswa'];

    // Cari Kelas Siswa
    $queryFindStudentClass = mysqli_query($con, "
      SELECT c_kelas FROM siswa WHERE nis = '$getNis'
    ");

    $getStudentClass = mysqli_fetch_array($queryFindStudentClass)['c_kelas'];

    $is_SD          = "/SD/i";

    $foundDataSD    = preg_match($is_SD, $getStudentClass);

    if ($foundDataSD == 1) {

      $nipKepsek = "2019032";

    } else {

      $nipKepsek = "2019034";

    }

    // Cari Foto Daily Siswa 
    $queryFindImageDailyStd = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nis AS nis_siswa,
      daily_siswa_approved.image as foto_daily
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getImageUploadDailyStd = mysqli_fetch_assoc($queryFindImageDailyStd)['foto_daily'];

    // Cari Tanggal Daily Siswa
    $queryFindDateDailyStd = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nis AS nis_siswa,
      daily_siswa_approved.tanggal_disetujui_atau_tidak as tgl_disetujui
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getDateDailyStd = mysqli_fetch_assoc($queryFindDateDailyStd)['tgl_disetujui'];

    // Cari Judul Daily Siswa
    $queryFindTitleDailyStd = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nis AS nis_siswa,
      daily_siswa_approved.title_daily as judul_daily
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getTitleDailyStd = mysqli_fetch_assoc($queryFindTitleDailyStd)['judul_daily'];

    // Cari Isi Daily Siswa
    $queryFindContentDailyStd = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      siswa.nis AS nis_siswa,
      daily_siswa_approved.isi_daily as isi_daily
      FROM daily_siswa_approved
      LEFT JOIN siswa
      ON daily_siswa_approved.nis_siswa = siswa.nis
      WHERE daily_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getContentDailyStd = mysqli_fetch_assoc($queryFindContentDailyStd)['isi_daily'];

  } 

  // OTM Daily Group
  else if ($roleotm == 1 && $stdorgroup == 1) {
    
    $otm_group = 1;

    // Cari Daily Group
    $queryFindNameGroup = mysqli_query($con, "
      SELECT 
      group_siswa_approved.id AS id_group,
      group_kelas.nama_group_kelas AS nama_group 
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getNameGroup = mysqli_fetch_assoc($queryFindNameGroup)['nama_group'];

    // Cari NIP Guru
    $queryFindNip = mysqli_query($con, "
      SELECT 
      daily_siswa_approved.id AS id_std,
      guru.nip AS nip_guru 
      FROM daily_siswa_approved
      LEFT JOIN guru
      ON daily_siswa_approved.from_nip = guru.nip
      WHERE daily_siswa_approved.from_nip IN (
        SELECT created_by FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getNIP   = mysqli_fetch_array($queryFindNip)['nip_guru'];

    $positionTeacher  = mysqli_fetch_array(mysqli_query($con, "
      SELECT c_jabatan FROM guru WHERE nip = '$getNIP'
    "));

    $is_SD          = "/SD/i";

    $foundDataSD    = preg_match($is_SD, $positionTeacher['c_jabatan']);

    if ($foundDataSD == 1) {

      $nipKepsek = "2019032";

    } else {

      $nipKepsek = "2019034";

    }

    // Cari Daily Id Group
    $queryFindIdGroup = mysqli_query($con, "
      SELECT 
      group_kelas.id AS id_group
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getIdGroup = mysqli_fetch_assoc($queryFindIdGroup)['id_group'];

    $getNis = $_SESSION['c_otm'];

    // Cari Foto Daily Group 
    $queryFindImageDailyGroup = mysqli_query($con, "
      SELECT 
      group_siswa_approved.image AS foto_daily
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getImageUploadDailyGroup = mysqli_fetch_assoc($queryFindImageDailyGroup)['foto_daily'];

    // Cari Tanggal Daily Group
    $queryFindDateDailyGroup = mysqli_query($con, "
      SELECT 
      group_siswa_approved.tanggal_disetujui_atau_tidak AS tgl_disetujui
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getDateDailyGroup = mysqli_fetch_assoc($queryFindDateDailyGroup)['tgl_disetujui'];

    // Cari Judul Daily Group
    $queryFindTitleDailyGroup = mysqli_query($con, "
      SELECT 
      group_siswa_approved.title_daily AS judul_daily
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getTitleDailyGroup = mysqli_fetch_assoc($queryFindTitleDailyGroup)['judul_daily'];

    // Cari Isi Daily Group
    $queryFindContentDailyGroup = mysqli_query($con, "
      SELECT 
      group_siswa_approved.isi_daily AS isi_daily 
      FROM group_siswa_approved
      LEFT JOIN group_kelas
      ON group_siswa_approved.group_kelas_id = group_kelas.id
      WHERE group_siswa_approved.id IN (
        SELECT daily_id FROM ruang_pesan WHERE room_key = '$roomkeys'
      )
    ");

    $getContentDailyGroup = mysqli_fetch_assoc($queryFindContentDailyGroup)['isi_daily'];
    // echo $getContentDailyGroup;exit;

  }

  else {
    session_destroy();

  }

  // echo "Kepsek = " . $kepsek_std . "<br>" . "Guru = " . $guru_std . "<br>" . "OTM = " . $otm_std; 
  // exit;

  // Cari Nama Guru Pembuat Daily
  $queryFindNameTeacher = mysqli_query($con, "
    SELECT nama FROM guru WHERE nip IN (
      SELECT created_by FROM ruang_pesan WHERE room_key = '$roomkeys'
    )
  ");

  $getNameTeacher = mysqli_fetch_assoc($queryFindNameTeacher)['nama'];

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> AIIS - Daily Activity </title>
  <link rel="shortcut icon" href="imgstatis/favicon.jpg">
  <style type="text/css">
    /* Full page setup */
    html, body {
        height: 100%;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f0f0f0;
        font-family: Arial, sans-serif;
    }

    /* Loading container */
    .loading-container {
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        font-weight: bold;
        color: #3498db;
    }

    /* Text style */
    .loading-text {
        margin-right: 10px;
    }

    /* Dot styles */
    .dot1, .dot2, .dot3 {
        display: inline-block;
        width: 15px;
        height: 15px;
        margin-left: 5px;
        border-radius: 50%;
        background-color: #3498db;
        animation: dot-blink 1.5s infinite ease-in-out;
    }

    .dot2 {
        animation-delay: 0.3s;
    }

    .dot3 {
        animation-delay: 0.6s;
    }

    @media only screen and (max-width: 768px) {
        .loading-text {
            font-size: 23px;
        }
    }

    /* Animation for dots */
    @keyframes dot-blink {
        0%, 100% {
            opacity: 0;
        }
        50% {
            opacity: 1;
        }
    }

  </style>
</head>
<body>

  <div class="out" style="display: flex; flex-direction: column; margin-top: -100px;">
    
    <div class="icon">
        <center>
            <img src="<?= $base; ?>imgstatis/logo2.png" height="150px" style="margin-bottom: 15px;">
        </center>
    </div>

    <div class="loading-container">
        <span class="loading-text"> Loading </span>
        <div class="dot1"></div>
        <div class="dot2"></div>
        <div class="dot3"></div>
    </div>

  </div>

  <?php if ($guru_group == 1): ?>

    <form action="<?= $basegu; ?>lookactivity/<?= $roomkeys; ?>" method="post" style="display: none;">
      <input type="hidden" id="hg_frompage_lookdaily" name="frompage_lookdaily" value="homepage">
      <input type="hidden" id="hg_roomkey_lookdaily" name="roomkey_group_lookdaily" value="<?= $roomkeys; ?>">
      <input type="hidden" id="hg_nama_guru_lookdaily" name="guru_lookdaily" value="<?= $getNameTeacher; ?>">
      <input type="hidden" id="hg_nipguru_lookdaily" name="nipguru_lookdaily" value="<?= $_SESSION['nip_guru']; ?>">
      <input type="hidden" id="hg_nama_siswa_lookdaily" name="nama_siswa_or_groupkelas_lookdaily" value="<?= $getNameGroup; ?>">
      <input type="hidden" id="hg_nis_siswa_lookdaily" name="nis_or_idgroup_lookdaily" value="<?= $getIdGroup; ?>">
      <input type="hidden" id="hg_foto_upload_lookdaily" name="foto_upload_lookdaily" value="<?= $getImageUploadDailyGroup; ?>">
      <input type="hidden" id="hg_tgl_posting_lookdaily" name="tgl_posting_lookdaily" value="<?= format_tgl_indo($getDateDailyGroup) . substr($getDateDailyGroup, -8); ?>">
      <input type="hidden" id="hg_tglori_posting_lookdaily" name="tglori_posting_lookdaily" value="<?= $getDateDailyGroup; ?>">
      <input type="hidden" id="hg_jdl_posting_lookdaily" name="jdl_posting_lookdaily" value="<?= $getTitleDailyGroup; ?>">
      <input type="hidden" id="hg_isi_posting_lookdaily" name="isi_posting_lookdaily" value="<?= $getContentDailyGroup; ?>">
      <button type="submit" name="daily_group" id="btn_activity"></button>
    </form>

  <?php elseif($guru_std == 1): ?>

    <form action="<?= $basegu; ?>lookactivity/<?= $roomkeys; ?>" method="post" style="display: none;">

      <input type="hidden" id="hg_frompage_lookdaily" name="frompage_lookdaily" value="homepage">
      <input type="hidden" id="hg_roomkey_lookdaily" name="roomkey_lookdaily" value="<?= $roomkeys; ?>">
      <input type="hidden" id="hg_nama_guru_lookdaily" name="guru_lookdaily" value="<?= $getNameTeacher; ?>">
      <input type="hidden" id="hg_nipguru_lookdaily" name="nipguru_lookdaily" value="<?= $_SESSION['nip_guru']; ?>">
      <input type="hidden" id="hg_nama_siswa_lookdaily" name="nama_siswa_or_groupkelas_lookdaily" value="<?= $getStudentName; ?>">
      <input type="hidden" id="hg_nis_siswa_lookdaily" name="nis_or_idgroup_lookdaily" value="<?= $getNis; ?>">
      <input type="hidden" id="hg_foto_upload_lookdaily" name="foto_upload_lookdaily" value="<?= $getImageUploadDailyStd; ?>">
      <input type="hidden" id="hg_tgl_posting_lookdaily" name="tgl_posting_lookdaily" value="<?= format_tgl_indo($getDateDailyStd) . substr($getDateDailyStd, -8); ?>">
      <input type="hidden" id="hg_tglori_posting_lookdaily" name="tglori_posting_lookdaily" value="<?= $getDateDailyStd; ?>">
      <input type="hidden" id="hg_jdl_posting_lookdaily" name="jdl_posting_lookdaily" value="<?= $getTitleDailyStd; ?>">
      <input type="hidden" id="hg_isi_posting_lookdaily" name="isi_posting_lookdaily" value="<?= $getContentDailyStd; ?>">
      <button type="submit" name="redirectLookDaily" id="btn_activity"></button>

    </form>
    
  <?php elseif($otm_std == 1): ?>

    <form action="<?= $basewam; ?>lookactivity/<?= $roomkeys; ?>" method="post" style="display: none;">
      <input type="hidden" id="hg_frompage_lookdaily" name="frompage_lookdaily" value="homepage">
      <input type="hidden" id="hg_roomkey_lookdaily" name="roomkey_lookdaily" value="<?= $roomkeys; ?>">
      <input type="hidden" id="hg_nip_guru_lookdaily" name="nipguru_lookdaily" value="<?= $getNIP; ?>">
      <input type="hidden" id="hg_nip_kepsek_lookdaily" name="nipkepsek_lookdaily" value="<?= $nipKepsek; ?>">
      <input type="hidden" id="hg_nama_guru_lookdaily" name="guru_lookdaily" value="<?= $getNameTeacher; ?>">
      <input type="hidden" id="hg_nis_siswa_lookdaily" name="nis_or_idgroup_lookdaily" value="<?= $getNis; ?>">
      <input type="hidden" id="hg_nama_siswa_lookdaily" name="nama_siswa_or_groupkelas_lookdaily" value="<?= $getStudentName; ?>">
      <input type="hidden" id="hg_foto_upload_lookdaily" name="foto_upload_lookdaily" value="<?= $getImageUploadDailyStd; ?>">
      <input type="hidden" id="hg_tgl_posting" name="tgl_posting_lookdaily" value="<?= format_tgl_indo($getDateDailyStd) . substr($getDateDailyStd, -8); ?>">
      <input type="hidden" id="hg_tglori_posting_lookdaily" name="tglori_posting_lookdaily" value="<?= $getDateDailyStd; ?>">
      <input type="hidden" id="hg_jdl_posting_lookdaily" name="jdl_posting_lookdaily" value="<?= $getTitleDailyStd; ?>">
      <input type="hidden" id="hg_isi_posting_lookdaily" name="isi_posting_lookdaily" value="<?= $getContentDailyStd; ?>">
      <button type="submit" name="redirectLookDaily" id="btn_activity"></button>
    </form>

  <?php elseif($otm_group == 1): ?>

    <form action="<?= $basewam; ?>lookactivity/<?= $roomkeys; ?>" method="post" style="display: none;">
      <input type="hidden" id="hg_frompage_lookdaily" name="frompage_lookdaily" value="homepage">
      <input type="hidden" id="hg_roomkey_lookdaily" name="roomkey_group_lookdaily" value="<?= $roomkeys; ?>">
      <input type="hidden" id="hg_nip_guru_lookdaily" name="nipguru_lookdaily" value="<?= $getNIP; ?>">
      <input type="hidden" id="hg_nip_kepsek_lookdaily" name="nipkepsek_lookdaily" value="<?= $nipKepsek; ?>">
      <input type="hidden" id="hg_nama_guru_lookdaily" name="guru_lookdaily" value="<?= $getNameTeacher; ?>">
      <input type="hidden" id="hg_nis_siswa_lookdaily" name="nis_or_idgroup_lookdaily" value="<?= $getIdGroup; ?>">
      <input type="hidden" id="hg_nama_siswa_lookdaily" name="nama_siswa_or_groupkelas_lookdaily" value="<?= $getNameGroup; ?>">
      <input type="hidden" id="hg_foto_upload_lookdaily" name="foto_upload_lookdaily" value="<?= $getImageUploadDailyGroup; ?>">
      <input type="hidden" id="hg_tgl_posting" name="tgl_posting_lookdaily" value="<?= format_tgl_indo($getDateDailyGroup) . substr($getDateDailyGroup, -8); ?>">
      <input type="hidden" id="hg_tglori_posting_lookdaily" name="tglori_posting_lookdaily" value="<?= $getDateDailyGroup; ?>">
      <input type="hidden" id="hg_jdl_posting_lookdaily" name="jdl_posting_lookdaily" value="<?= $getTitleDailyGroup; ?>">
      <input type="hidden" id="hg_isi_posting_lookdaily" name="isi_posting_lookdaily" value="<?= $getContentDailyGroup; ?>">
      <button type="submit" name="daily_group" id="btn_activity"></button>
    </form>

  <?php elseif($kepsek_std == 1): ?>

    <form action="<?= $basekepsek; ?>lookactivity/<?= $roomkeys; ?>" method="post" style="display: none;">

      <input type="hidden" id="hg_frompage_lookdaily" name="frompage_lookdaily" value="homepage">
      <input type="hidden" id="hg_roomkey_lookdaily" name="roomkey_lookdaily" value="<?= $roomkeys; ?>">
      <input type="hidden" id="hg_nama_guru_lookdaily" name="guru_lookdaily" value="<?= $getNameTeacher; ?>">
      <input type="hidden" id="hg_nama_siswa_lookdaily" name="nama_siswa_or_groupkelas_lookdaily" value="<?= $getStudentName; ?>">
      <input type="hidden" id="hg_nis_siswa_lookdaily" name="nis_or_idgroup_lookdaily" value="<?= $getNis; ?>">
      <input type="hidden" id="hg_foto_upload_lookdaily" name="foto_upload_lookdaily" value="<?= $getImageUploadDailyStd; ?>">
      <input type="hidden" id="hg_tgl_posting_lookdaily" name="tgl_posting_lookdaily" value="<?= format_tgl_indo($getDateDailyStd) . substr($getDateDailyStd, -8); ?>">
      <input type="hidden" id="hg_tglori_posting_lookdaily" name="tglori_posting_lookdaily" value="<?= $getDateDailyStd; ?>">
      <input type="hidden" id="hg_jdl_posting_lookdaily" name="jdl_posting_lookdaily" value="<?= $getTitleDailyStd; ?>">
      <input type="hidden" id="hg_isi_posting_lookdaily" name="isi_posting_lookdaily" value="<?= $getContentDailyStd; ?>">
      <button type="submit" name="redirectLookDaily" id="btn_activity"></button>

    </form>

  <?php elseif($kepsek_group == 1): ?>

    <form action="<?= $basekepsek; ?>lookactivity/<?= $roomkeys; ?>" method="post" style="display: none;">
      <input type="hidden" id="hg_frompage_lookdaily" name="frompage_lookdaily" value="homepage">
      <input type="hidden" id="hg_roomkey_lookdaily" name="roomkey_group_lookdaily" value="<?= $roomkeys; ?>">
      <input type="hidden" id="hg_nip_guru_lookdaily" name="nipguru_lookdaily" value="<?= $getNIP; ?>">
      <input type="hidden" id="hg_nama_guru_lookdaily" name="guru_lookdaily" value="<?= $getNameTeacher; ?>">
      <input type="hidden" id="hg_nis_siswa_lookdaily" name="nis_or_idgroup_lookdaily" value="<?= $getIdGroup; ?>">
      <input type="hidden" id="hg_nama_siswa_lookdaily" name="nama_siswa_or_groupkelas_lookdaily" value="<?= $getNameGroup; ?>">
      <input type="hidden" id="hg_foto_upload_lookdaily" name="foto_upload_lookdaily" value="<?= $getImageUploadDailyGroup; ?>">
      <input type="hidden" id="hg_tgl_posting_lookdaily" name="tgl_posting_lookdaily" value="<?= format_tgl_indo($getDateDailyGroup) . substr($getDateDailyGroup, -8); ?>">
      <input type="hidden" id="hg_tglori_posting_lookdaily" name="tglori_posting_lookdaily" value="<?= $getDateDailyGroup; ?>">
      <input type="hidden" id="hg_jdl_posting_lookdaily" name="jdl_posting_lookdaily" value="<?= $getTitleDailyGroup; ?>">
      <input type="hidden" id="hg_isi_posting_lookdaily" name="isi_posting_lookdaily" value="<?= $getContentDailyGroup; ?>">
      <button type="submit" name="daily_group" id="btn_activity"></button>
    </form>

  <?php endif ?>

</body>
</html>

<script src="jquery.js"> </script>

<script type="text/javascript">

  $(document).ready(function() {

    setTimeout(() => {
      $("#btn_activity").click();
    }, 2000)

  });

</script>