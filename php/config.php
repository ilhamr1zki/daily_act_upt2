<?php
$dbhost ='localhost';
$dbuser ='root';
$dbpass ='';
// $dbpass ='Admin@2023';
$dbname ='daily_act_paud';
$db_dsn = "mysql:dbname=$dbname;host=$dbhost";
try {
  $db = new PDO($db_dsn, $dbuser, $dbpass);
} catch (PDOException $e) {
  echo 'Connection failed: '.$e->getMessage();
}
include_once 'Auth.php';
$user = new Auth($db);
$con=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
($GLOBALS["___mysqli_ston"] = mysqli_connect($dbhost, $dbuser, $dbpass));mysqli_select_db($GLOBALS["___mysqli_ston"], $dbname);
/* css.plugin.hancon <?php echo $base; ?> */

$base='http://localhost/daily_act_upt_paud/';
$base1='http://localhost/daily_act_upt_paud/';
$basead='http://localhost/daily_act_upt_paud/admin/';
$basegu='http://localhost/daily_act_upt_paud/guru/';
$basekepsek='http://localhost/daily_act_upt_paud/kepala_sekolah/';
$basekepsek1='http://localhost/daily_act_upt_paud/kepala_sekolah/';
$basewam='http://localhost/daily_act_upt_paud/walimurid/';

$aplikasi=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM aplikasi limit 1"));
// $ata=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM tahun_ajaran where status='aktif' ")); 
// $c_ta = $ata['id_tahun_ajaran'];
?>
