<?php date_default_timezone_set('Asia/Jakarta');
function random($length){
  $data='1234567890AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSstuuUvVwWxXyYyZz';
  $string='';
  for($i=1;$i<=$length;$i++){
    $pos=rand(0,strlen($data)-1);
    $string.=$data[$pos];
  }
  return $string;
}
require '../../php/config.php';
if(empty($_GET['smkakh']) or empty($_GET['q'])){
	header('location:../../login');
}
else{
	require 'action.php';
	$smk=new action();
	$akh=($_GET['smkakh']);
  if($akh==md5('logout_act1_guru_paud')){ 
    $res = ["is_logout"];
    echo json_encode($res);
    session_destroy();
    unset($_SESSION['key_guru_paud']);
    // header('location:../../');
  }
//kelas
  else if($akh==md5('addkelas')){ $c_kelas=random(9);
    $smk->addkelas($con,$c_kelas,$_POST['kelas']);
  }
  else if($akh==md5('editkelas')){
    $smk->editkelas($con,$_POST['c_kelas'],$_POST['kelas']);
  }
  else if($akh==md5('hapuskelas')){
    $smk->hapuskelas($con,$_GET['q']);
  }
//siswa
  else if($akh==md5('addsiswa')){ $c_siswa=random(9); $tl=date('Y-m-d',strtotime($_POST['tl']));
    $smk->addsiswa($con,$c_siswa,$_POST['c_kelas'],$_POST['nisn'],$_POST['nama'],$_POST['jk'],$_POST['alamat'],$tl, 
    $_POST['_thnjoin'] ?? 0, $_POST['_nmpanggilan'] ?? "", $_POST['_klpselect'] ?? "", $_POST['_beratbadan'] ?? "", $_POST['_tinggibadan'] ?? "",
    $_POST['_ukuranbaju'] ?? "", $_POST['_alamatrumah'] ?? "", $_POST['_telp'] ?? "", $_POST['_hp'] ?? "", $_POST['_email'] ?? "", $_POST['_nmayah'] ?? "",
    $_POST['_pendayah'] ?? "", $_POST['_pekerjaanayah'] ?? "", $_POST['_temptglayah'] ?? "", $_POST['_nmibu'] ?? "", $_POST['_pendibu'] ?? "",
    $_POST['_pekerjaanibu'] ?? "", $_POST['_temptglibu'] ?? "", $_POST['_nis'] ?? "");
  }
  else if($akh==md5('editsiswa')){ $tl=date('Y-m-d',strtotime($_POST['tl'] ?? "1900-01-01"));
    $smk->editsiswa($con,$_POST['c_siswa'], $_POST['_nis'] ?? "", $_POST['nisn'] ?? "",$_POST['nama'],$_POST['jk'] ?? "",$_POST['alamat'] ?? "",$tl, 
    $_POST['_thnjoin'] ?? 0, $_POST['_nmpanggilan'] ?? "", $_POST['_klpselect'] ?? "", $_POST['_beratbadan'] ?? "", $_POST['_tinggibadan'] ?? "",
    $_POST['_ukuranbaju'] ?? "", $_POST['_alamatrumah'] ?? "", $_POST['_telp'] ?? "", $_POST['_hp'] ?? "", $_POST['_email'] ?? "", $_POST['_nmayah'] ?? "",
    $_POST['_pendayah'] ?? "", $_POST['_pekerjaanayah'] ?? "", $_POST['_temptglayah'] ?? "", $_POST['_nmibu'] ?? "", $_POST['_pendibu'] ?? "",
    $_POST['_pekerjaanibu'] ?? "", $_POST['_temptglibu'] ?? "", $_POST['_kelasselect'] ?? "");
  }
//guru
  else if($akh==md5('addguru')){ $c_guru=random(9); $tl=date('Y-m-d',strtotime($_POST['tl']));$tgljoin=date('Y-m-d',strtotime($_POST['tgljoin'] ?? "1900-01-01"));
    $smk->addguru($con,$c_guru,$_POST['nip'],$_POST['nama'],$_POST['alamat'] ?? "",
    $tl,$_POST['username'],$_POST['password'], $tgljoin, $_POST['jabatan'] ?? "", $_POST['jk'] ?? "", $_POST['_alamatrumah'] ?? "",
    $_POST['_pendidikan'] ?? "", $_POST['_jurusan'] ?? "", $_POST['_email'] ?? "");
  }
  else if($akh==md5('editguru')){ $tl=date('Y-m-d',strtotime($_POST['tl']));$tgljoin=date('Y-m-d',strtotime($_POST['tgljoin'] ?? "1900-01-01"));
    $smk->editguru($con,$_POST['c_guru'],$_POST['nip'],$_POST['nama'] ?? "",$_POST['alamat'] ?? "",
    $tl,$_POST['username'],$_POST['password'] ?? "", $tgljoin, $_POST['jabatan'] ?? "", $_POST['jk'] ?? "", $_POST['_alamatrumah'] ?? "",
    $_POST['_pendidikan'] ?? "", $_POST['_jurusan'] ?? "", $_POST['_email'] ?? "");
  }
  else if($akh==md5('hapusguru')){
    $dd=mysqli_fetch_array(mysqli_query($con,"SELECT * from guru where c_guru='$_GET[q]' "));
    if($dd['foto']!=NULL){
        unlink('../../guru/'.$dd['foto'].'');
    }
    $smk->hapusguru($con,$_GET['q']);
  }
//wali murid
  else if($akh==md5('editwalimurid')){
    $cor=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM walimurid where c_siswa='$_POST[c_siswa]' "));
    if($cor==NULL){ $c_walimurid=random(9);
      $smk->inputwalimurid($con,$c_walimurid,$_POST['c_siswa'],$_POST['nama'],$_POST['username'],$_POST['password']);
    }
    else{
      $smk->editwalimurid($con,$_POST['c_walimurid'],$_POST['c_siswa'],$_POST['nama'],$_POST['username'],$_POST['password']);
    }
  }
//mapel
  else if($akh==md5('addmapel')){ $c_mapel=random(9);
    $smk->addmapel($con,$c_mapel,$_POST['mapel'],$_POST['sl']);
  }
  else if($akh==md5('editmapel')){
    $smk->editmapel($con,$_POST['c_mapel'],$_POST['mapel'],$_POST['sl']);
  }
//guru mapel
  else if($akh==md5('addgurumapel')){ $c_gurumapel=random(9);
    $cekdulu=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM gurumapel where c_kelas='$_POST[c_kelas]' and c_mapel='$_POST[c_mapel]' "));
    if($cekdulu==NULL){
       $smk->addgurumapel($con,$c_gurumapel,$_POST['c_guru'],$_POST['c_kelas'],$_POST['c_mapel']);
    }
    else{
      session_start();
      $_SESSION['pesan']='gagal';
      header('location:../../addgurumapel');
    }
  }
  else if($akh==md5('editgurumapel')){
    $cekdulu=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM gurumapel where c_kelas='$_POST[c_kelas]' and c_mapel='$_POST[c_mapel]' "));
    if($cekdulu==NULL){
       $smk->editgurumapel($con,$_POST['c_gurumapel'],$_POST['c_guru'],$_POST['c_kelas'],$_POST['c_mapel']);
    }
    else{
      session_start();
      $_SESSION['pesan']='gagal';
      header('location:../../editgurumapel/'.$_POST['c_gurumapel'].'');
    }
  }
//wali kelas
  else if($akh==md5('addwalikelas')){ $c_walikelas=random(9);
    $cekdulu=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM walikelas where c_kelas='$_POST[c_kelas]' OR c_guru='$_POST[c_guru]' "));
    if($cekdulu==NULL){
       $smk->addwalikelas($con,$c_walikelas,$_POST['c_guru'],$_POST['c_kelas'],$_POST['username'],$_POST['password']);
    }
    else{
      session_start();
      $_SESSION['pesan']='gagal';
      header('location:../../addwalikelas');
    }
  }
  else if($akh==md5('editwalikelas')){
    /*$cekdulu=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM walikelas where c_kelas='$_POST[c_kelas]' and c_guru='$_POST[c_guru]' "));
    if($cekdulu==NULL){
       $smk->editwalikelas($con,$_POST['c_walikelas'],$_POST['c_guru'],$_POST['c_kelas'],$_POST['username'],$_POST['password']);
    }
    else{
      session_start();
      $_SESSION['pesan']='gagal';
      header('location:../../editwalikelas/'.$_POST['c_walikelas'].'');
    }*/
    $smk->editwalikelas($con,$_POST['c_walikelas'],$_POST['c_guru'],$_POST['c_kelas'],$_POST['username'],$_POST['password']);
  }
//tahun akademik
  else if($akh==md5('addta')){ $c_ta=random(9);
    $smk->addta($con,$c_ta,$_POST['tahun'],$_POST['semester']);
  }
  else if($akh==md5('editta')){
    $smk->editta($con,$_POST['c_ta'],$_POST['tahun'],$_POST['semester']);
  }
  else if($akh==md5('aktifkan')){
    $smk->aktifkan($con,$_GET['q']);
  }
//tipe nilai
  else if($akh==md5('addtipenilai')){ $c_tipenilai=random(9);
    $smk->addtipenilai($con,$c_tipenilai,$_POST['tipe'],$_POST['ket'],$_POST['bobot'],$_POST['p']);
  }
  else if($akh==md5('edittipenilai')){
    $smk->edittipenilai($con,$_POST['c_tipenilai'],$_POST['tipe'],$_POST['ket'],$_POST['bobot'],$_POST['p']);
  }
//aplikasi 
  else if($akh==md5('aplikasi')){
    $smk->aplikasi($con,$_POST['alamat'],$_POST['kepsek'],$_POST['nipkepsek'],$_POST['namasek']);
  }
//backup  
  else if($akh==md5('backupdb')){
    $file='backup_db_eraport-'.date('d-m-Y').'-'.date('H-i-s').'-.sql';
    $smk->backup($con,$file);
    backup_tables($dbhost,$dbuser,$dbpass,$dbname,$file);
    session_start();
    $_SESSION['pesan']='backup';
    header('location:../../backup');
  }
//extra
  else if($akh==md5('addextra')){ $c_extra=random(9);
    $smk->addextra($con,$c_extra,$_POST['extra']);
  }
  else if($akh==md5('editextra')){ $c_extra=random(9);
    $smk->editextra($con,$_POST['c_extra'],$_POST['extra']);
  }
//setting guru mapel
  else if($akh==md5('settinggurumapel')){
    mysqli_query($con,"DELETE FROM gurumapel where c_guru='$_POST[c_guru]' ");
    $m= $_POST['mapel'];$t= count($m);
    for($a=0;$a<$t;$a++){
      $c_gurumapel=random(9); $cm=substr($m[$a], 0,9); $ck=substr($m[$a], 10,9);
      $smk->settinggurumapel($con,$c_gurumapel,$_POST['c_guru'],$cm,$ck);
    }
    session_start();
    $_SESSION['pesan']='berhasil';
    header('location:../../settinggurumapel/'.$_POST['c_guru'].'');
  }

  //region master jilid
  else if($akh==md5('addmjilid')){ 
    $smk->addmasterjilid($con, $_POST['_nmjilidadd'],$_POST['_seqjilidadd'], "00");
  }
  else if($akh==md5('editmjilid')){
    $smk->editmasterjilid($con, $_POST['_nmjilidedit'], $_POST['_seqjilidedit'], "gg", $_POST['_idedit']);
  }

  //kenaikan jilid
  else if($akh==md5('addnaikjilid')){ 
    $_tglnaikjilid=date('Y-m-d',strtotime($_POST['_tglnaikjilid']));
    if (isset($_POST['btnsetupjilid'])) {
      $smk->addnaikjilid($con, $_POST['_idjilid'] ?? 0,$_POST['_seqnext'] ?? 0, $_POST['_idsiswa'] ?? "", $_POST['_nmsiswa'] ?? "", $_tglnaikjilid , $_POST['_jilidcur'] ?? "", $_POST['_entryby'] ?? "");
      
    } else if (isset($_POST['btnnaikjilid'])) {
      $smk->updatenaikjilid($con, $_POST['_idjilid'] ?? 0,$_POST['_seqnext'] ?? 0, $_POST['_idsiswa'] ?? "", $_POST['_nmsiswa'] ?? "", $_tglnaikjilid , $_POST['_jilidcur'] ?? "", $_POST['_entryby'] ?? "");
    } else {
      // No button was clicked
    }

  }
  //endregion master jilid

  // region master juz
  else if ($akh == md5("addmjuz")) {

    $dataIsiSurahAwal      = $_POST['addIsiSurahAwal'];
    $dataIsiSurahAkhir     = $_POST['addIsiSurahAkhir'];

    $parentJuz             = $_POST['parentjuz'];

    $urutan                = $_POST['urutan'];

    $keteranganAyatSrhAwl  = "";
    $keteranganAyatSrhAkr  = "";

    if ($_POST['keteranganAyatSrhAwl'] == true && $_POST['keteranganAyatSrhAkr'] == true) {

      $keteranganAyatSrhAwl  = $_POST['keteranganAyatSrhAwl'];
      $keteranganAyatSrhAkr  = $_POST['keteranganAyatSrhAkr'];

    } else if ($_POST['keteranganAyatSrhAwl'] == true && $_POST['keteranganAyatSrhAkr'] == false) {

      $keteranganAyatSrhAwl  = $_POST['keteranganAyatSrhAwl'];

    }

    // ambil data surat dari bagian surah awal
    $isiSurahAwal  = $dataIsiSurahAwal;

    // Ambil data surat dari table kumpulan surat untuk surah awal
    $queryGetDataSurahAwal = mysqli_query($con, "SELECT nama_surah FROM kumpulan_surah WHERE nomer_surah = '$isiSurahAwal' ");

    // ambil data surat dari bagian surah akhir
    $isiSurahAkhir  = $dataIsiSurahAkhir;

    // Ambil data surat dari table kumpulan surat untuk surah akhir
    $queryGetDataSurahAkhir = mysqli_query($con, "SELECT nama_surah FROM kumpulan_surah WHERE nomer_surah = '$isiSurahAkhir' ");

    // mendapatkan data surah awal pertama
    $getDataSurahAwal = mysqli_fetch_assoc($queryGetDataSurahAwal)['nama_surah'];

    // mendapatkan data surah awal terakhir
    $getDataSurahAkhir = mysqli_fetch_assoc($queryGetDataSurahAkhir)['nama_surah'];

    // Jika tidak ada keterangan ayat di surah awal dan surah akhir
    if ($keteranganAyatSrhAwl == '' && $keteranganAyatSrhAkr == '') {
      
      // echo $getDataSurahAwal . " - " . $getDataSurahAkhir . " " . $urutan;exit;
      $ketAyatPadaSurah = addslashes($getDataSurahAwal) .' - '. addslashes($getDataSurahAkhir);
      // echo $ketAyatPadaSurah;exit;

      // insert database

      $smk->addmasterjuz($con, $ketAyatPadaSurah, $urutan, $parentJuz);
      exit;

    // Jika ada keterangan ayat di surah awal saja 
    } else if ( $keteranganAyatSrhAwl !== '' && $keteranganAyatSrhAkr == '' ) {

      // echo $getDataSurahAwal  . " (". $keteranganAyatSrhAwl .")" . " - " . $getDataSurahAkhir. " (". $keteranganAyatSrhAkr .")" ;exit;

      $ketAyatPadaSurah = addslashes($getDataSurahAwal)  . " (". $keteranganAyatSrhAwl .")" ;

      // insert database
      $smk->addmasterjuz($con, $ketAyatPadaSurah, $urutan, $parentJuz);
      exit;

    // Jika semua nya ada dan lengkap 
    } else {

      // echo "bawah" . "<br>" . $getDataSurahAwal  . " (". $keteranganAyatSrhAwl .")" . " - " . $getDataSurahAkhir. " (". $keteranganAyatSrhAkr .")";exit;

      $ketAyatPadaSurah =  addslashes($getDataSurahAwal) . " (". $keteranganAyatSrhAwl .")" . " - " . addslashes($getDataSurahAkhir) . " (". $keteranganAyatSrhAkr .")" ;

      // insert database
      $smk->addmasterjuz($con, $ketAyatPadaSurah, $urutan, $parentJuz);
      exit;    

    }
    
  } else if ( $akh == md5('editmjuz') ) {

  $id                 = $_POST['idjuzedit'];
  $juz                = $_POST['isijuzedit'];
  $urutan             = $_POST['seqjuzedit'];
  $idParentJuz        = $_POST['idparentjuzedit'];

  $smk->editmasterjuz($con, $id, $juz, $urutan, $idParentJuz);
  exit;

} else if ( $akh == md5('editmketsurah') ) {

  $id                 = $_POST['idketsurahedit'];
  $ketAyatPadaSurah   = addslashes($_POST['ketsurahedit']);
  $urutan             = $_POST['seqketsurahedit'];
  $idParentJuz        = $_POST['idparentjuzketedit'];

  $smk->editmasterjuz($con, $id, $ketAyatPadaSurah, $urutan, $idParentJuz);
  exit;

} else if ( $akh == md5('hapusmjuz') ) {

  $smk->hapusmjuz($con, $_POST['idhapusjuz']);
  exit;  

}
  // endregion master juz 

#region pustaka
else if($akh==md5('addpustaka')){ 
  $targetDir = "../../wwwupload/"; 
  $nama_file = basename($_FILES["_filedok"]["name"]);
  $smk->addpustaka($con, $_POST['_nmdok'] ?? "",$_POST['_deskripsiadd'] ?? "", $nama_file ?? "", $_POST['_userdok'] ?? "");
  if (! empty($_FILES['_filedok']['tmp_name'])) {
    copy($_FILES['_filedok']['tmp_name'],"../../wwwupload/".$nama_file);
   // move_uploaded_file($_FILES["_userdok"]["tmp_name"], $targetFile);
  }
}
else if($akh==md5('editpustaka')){
  $smk->updatepustaka($con, $_POST['_nmdokeddit'], $_POST['_deskripsiedit'], $_POST['_idedit']);
}
#end region pustaka


//semua hapus jadi satu
  else if($akh==md5('hapuswalimurid')){
    $smk->hapuswalimurid($con,$_GET['q']);
  }
  else if($akh==md5('hapuswalikelas')){
    $smk->hapuswalikelas($con,$_GET['q']);
  }
  else if($akh==md5('hapusguru')){
    $smk->hapusguru($con,$_GET['q']);
  }
  else if($akh==md5('hapusgurumapel')){
    $smk->hapusgurumapel($con,$_GET['q']);
  }
  else if($akh==md5('hapussiswa')){
    $ck=mysqli_fetch_array(mysqli_query($con,"SELECT * from siswa where c_siswa='$_GET[q]' "));
    $smk->hapussiswa($con,$_GET['q'],$ck['c_kelas']);
  }
  else if($akh==md5('hapussiswa2')){
    $smk->hapussiswa2($con,$_GET['q']);
  }
  else if($akh==md5('hapuskelas')){
    $smk->hapuskelas($con,$_GET['q']);
  }
  else if($akh==md5('hapusmapel')){
    $smk->hapusmapel($con,$_GET['q']);
  }
  else if($akh==md5('hapustipenilai')){
    $smk->hapustipenilai($con,$_GET['q']);
  }
  else if($akh==md5('hapusta')){
    $smk->hapusta($con,$_GET['q']);
  }
  else if($akh==md5('hapusextra')){
    $smk->hapusextra($con,$_GET['q']);
  }
  else if($akh==md5('hapusbackup')){
    $ck=mysqli_fetch_array(mysqli_query($con,"SELECT * from backup where c_backup='$_GET[q]' ")); unlink('../backupdb/'.$ck['file'].'');
    $smk->hapusbackup($con,$_GET['q']);
  }

  //hapus m jilid
  else if($akh==md5('hapusmjilid')){
    $smk->hapusmjilid($con,$_POST['_idhapus']);
  }

  //hapus pustaka
  else if($akh==md5('hapuspustaka')){
    $filename = $_POST['_filedokdel'] ?? "";
    
    $smk->hapuspustaka($con,$_POST['_idhapus']);
    if(file_exists("../../wwwupload/".$filename)) {
			// Jika file ada pada folder, maka file gambar dihapus
			unlink("../../wwwupload/".$filename); 
		}
  }

  else{
    session_destroy();
    session_unset($_SESSION['key_admin']);
    header('location:../../login');
    //echo "string";
  }
}

function backup_tables($host,$user,$pass,$name,$nama_file,$tables = '*')
{
  //untuk koneksi database
  $link = mysqli_connect($host,$user,$pass,$name);

  if($tables == '*')
  {
    $tables = array();
    $result = mysqli_query($GLOBALS["___mysqli_ston"], 'SHOW TABLES');
    while($row = mysqli_fetch_row($result))
    {
      $tables[] = $row[0];
    }
  }else{
    //jika hanya table-table tertentu
    $tables = is_array($tables) ? $tables : explode(',',$tables);
  }
  
  //looping dulu ah
  foreach($tables as $table)
  {
    $result = mysqli_query($GLOBALS["___mysqli_ston"], 'SELECT * FROM '.$table);
    $hitung= mysqli_num_rows($result);
    $num_fields = (($___mysqli_tmp = mysqli_num_fields($result)) ? $___mysqli_tmp : false);
    
    //menyisipkan query drop table untuk nanti hapus table yang lama
    @$return.= 'DROP TABLE '.$table.';';
    $row2 = mysqli_fetch_row(mysqli_query($GLOBALS["___mysqli_ston"], 'SHOW CREATE TABLE '.$table));
    $return.= "\n\n".$row2[1].";\n\n";
    $return.= "INSERT INTO ".$table." VALUES\n";
    
      
    for ($i = 1; $i <= $hitung; $i++) 
    {
      
      
      $row = mysqli_fetch_row($result);
      $return.='(';
        //menyisipkan query Insert. untuk nanti memasukan data yang lama ketable yang baru dibuat. so toy mode : ON
        for($j=0; $j<$num_fields; $j++) 
        {
          //akan menelusuri setiap baris query didalam
          $row[$j] = addslashes($row[$j]);
          $row[$j] = ereg_replace("\n","\\n",$row[$j]);
          if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
          if ($j<($num_fields-1)) { $return.= ','; }
        }
        if($i==$hitung)
        {
          $return.=');';
        }
        else
        {
          $return.="),\n";
        }
      
      }
      
    
    
    $return.="\n\n\n";
  }
  
  //simpan file di folder yang anda tentukan sendiri. kalo saya sech folder "DATA"
  $nama_file;
  
  $handle = fopen('../backupdb/'.$nama_file,'w+');
  fwrite($handle,$return);
  fclose($handle);
}
?>
