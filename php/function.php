<?php 
function rp($str){
    $jum = strlen($str);
    $jumtitik = ceil($jum/3);
    $balik = strrev($str);
    
    $awal = 0;
    $akhir = 3;
    for($x=0;$x<$jumtitik;$x++){
      $a[$x] = substr($balik,$awal,$akhir)."."; 
      $awal+=3;
    }
    $hasil = implode($a);
    $hasilakhir = strrev($hasil);
    $hasilakhir = substr($hasilakhir,1,$jum+$jumtitik);
          
    return "".$hasilakhir."";
}
function tgl($date){  
  $array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember');
  $date = strtotime($date);
  $tanggal = date ('j', $date);
  $bulan = $array_bulan[date('n',$date)];
  $tahun = date('Y',$date); 
  $result = $tanggal ." ". $bulan ." ". $tahun;       
  return($result);  
}

function tglIndo($date){  
  $tanggal_indo = date_create($date);
  date_timezone_set($tanggal_indo,timezone_open("Asia/Jakarta"));
  $array_bulan = array(1=>'Januari','Februari','Maret', 'April', 'Mei', 'Juni','Juli','Agustus','September','Oktober', 'November','Desember');
  $date = strtotime($date);
  $tanggal = date ('d', $date);
  $bulan = $array_bulan[date('n',$date)];
  $tahun = date('Y',$date); 
  // $jamIndo = date("h:i:s", $date);
  $jamIndo = date_format($tanggal_indo, "H:i:s");
  $result = $tanggal ." ". $bulan ." ". $tahun . " " . $jamIndo;       
  return($result);  
}

function tglEng($date){  
  $tanggal_indo = date_create($date);
  date_timezone_set($tanggal_indo,timezone_open("Asia/Jakarta"));
  $array_bulan = array(1=>'January','February','March', 'April', 'May', 'June','July','August','September','October', 'November','Desember');
  $date = strtotime($date);
  $tanggal = date ('d', $date);
  $bulan = $array_bulan[date('n',$date)];
  $tahun = date('Y',$date); 
  $H     = date_format($tanggal_indo, "H") - 5;
  $i     = date_format($tanggal_indo, "i");
  $s     = date_format($tanggal_indo, "s");
  if ($H >= 10) {
    $H   = date_format($tanggal_indo, "H") -5;
  } else if($H < 10) {
    $H   = "0". $H;
  }
  $jamIndo = $H.":".$i.":".$s;
  $result = $tanggal ." ". $bulan ." ". $tahun . " " . $jamIndo;       
  return($result);  
}

function bulan($bulan){
  if($bulan=='01'){$namabulan="Januari";}
  elseif($bulan=='01'){$namabulan="Januari";}
  elseif($bulan=='02'){$namabulan="Februari";}
  elseif($bulan=='03'){$namabulan="Maret";}
  elseif($bulan=='04'){$namabulan="April";}
  elseif($bulan=='05'){$namabulan="Mei";}
  elseif($bulan=='06'){$namabulan="Juni";}
  elseif($bulan=='07'){$namabulan="Juli";}
  elseif($bulan=='08'){$namabulan="Agustus";}
  elseif($bulan=='09'){$namabulan="September";}
  elseif($bulan=='10'){$namabulan="Oktober";}
  elseif($bulan=='11'){$namabulan="November";}
  elseif($bulan=='12'){$namabulan="Desember";}
  return($namabulan);
}
function hari($hari){
  $daftar_hari = array( 'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu' );
  $hariini = date('l', strtotime($hari)); 
  return $daftar_hari[$hariini];
}
function h($h){
  if($h=='1'){$hr='Senin';}
  elseif($h=='2'){$hr='Selasa';}
  elseif($h=='3'){$hr='Rabu';}
  elseif($h=='4'){$hr='Kamis';}
  elseif($h=='5'){$hr='Jumat';}
  elseif($h=='6'){$hr='Sabtu';}
  elseif($h=='7'){$hr='Minggu';}
  return $hr;
}
function random($length){
  $data='1234567890AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSstuuUvVwWxXyYyZz';
  $string='';
  for($i=1;$i<=$length;$i++){
    $pos=rand(0,strlen($data)-1);
    //$string.=$data{$pos};
    $string.=$data[$pos]; //php 7.4 and later
  }
  return $string;
}
function l($linku){
  $l=substr(md5($linku), 0,9);
  return $l;
}
$pendidikans = array("SD", "SMP", "SMA", "D1", "D2", "D3", "S1", "S2", "S3");
$pekerjaans = array("WIRASWASTA", "KARYAWAN SWASTA", "PNS", "TNI", "POLRI", "PENSIUNAN");
?>