<?php

    $nominal = "";

    function penyebut($nilai) {
        $nilai = abs($nilai);
        $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $temp = "";
        if ($nilai < 12) {
            $temp = " ". $huruf[$nilai];
        } else if ($nilai <20) {
            $temp = penyebut($nilai - 10). " belas";
        } else if ($nilai < 100) {
            $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " seratus" . penyebut($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " seribu" . penyebut($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
        }     
        return $temp;
    }
 
    function terbilang($nilai) {
        if($nilai<0) {
            $hasil = "minus ". trim(penyebut($nilai));
        } else {
            $hasil = trim(penyebut($nilai));
        }           
        return ucwords($hasil) . " Rupiah";
    }

    function rupiah($angka){
    
        $hasil_rupiah = "Rp " . number_format($angka,2,'.',',');
        return $hasil_rupiah;
     
    }

    function rupiahFormat($angkax){
    
        $hasil_rupiahx = "Rp " . number_format($angkax,0,'.',',');
        return $hasil_rupiahx;
     
    }

    $cetak_kuitansi_filter      = "";
    $terbilang_nominal_uang_spp = "";

    $uangSPP            = 0;
    $uangPangkal        = 0;
    $uangRegis          = 0;
    $uangSeragam        = 0;
    $uangBuku           = 0;
    $uangKegiatan       = 0;
    $uangLain2          = 0;
    $totalKeseluruhan   = 0;

    $isiUangSPP         = 0;
    $isiUangPangkal     = 0;
    $isiUangKegiatan    = 0;
    $isiUangBuku        = 0;
    $isiUangSeragam     = 0;
    $isiUangLain        = 0;
    $isiUangRegis       = 0;

    $ketUangSPP         = "";
    $ketUangPANGKAL     = "";
    $ketUangKegiatan    = "";
    $ketUangBuku        = "";
    $ketUangSeragam     = "";
    $ketUangRegistrasi  = "";
    $ketUangLain        = "";

    $terbilang_nominal  = 0; 

    $idSiswa        = "";
    $namaSiswa      = "";
    $nisSiswa       = "";
    $kelasSiswa     = "";
    $tglTf          = "";
    $bayarBulan     = "";
    $idInvoice      = "";

    $rupiah_terbilang = "";

    if (isset($_POST['cetak_kuitansi'])) {

        $nisSiswa          = $_POST['cetak_kuitansi_nis_siswa'];
        $namaSiswa         = $_POST['cetak_kuitansi_nama_siswa'];
        $kelasSiswa        = $_POST['cetak_kuitansi_kelas_siswa'];
        $idInvoice         = $_POST['cetak_kuitansi_id_invoice'];
        $tglTf             = $_POST['cetak_kuitansi_bukti_tf'];
        $bayarBulan        = $_POST['cetak_kuitansi_bulan_pembayaran'];

        $isiUangSPP        = $_POST['cetak_kuitansi_uang_spp'];
        $isiUangPangkal    = $_POST['cetak_kuitansi_uang_pangkal'];
        $isiUangKegiatan   = $_POST['cetak_kuitansi_uang_kegiatan'];
        $isiUangBuku       = $_POST['cetak_kuitansi_uang_buku'];
        $isiUangSeragam    = $_POST['cetak_kuitansi_uang_seragam'];
        $isiUangRegis      = $_POST['cetak_kuitansi_uang_registrasi'];
        $isiUangLain       = $_POST['cetak_kuitansi_uang_lain'];

        $ketUangSPP        = $_POST['cetak_kuitansi_ket_uang_spp'];
        $ketUangPANGKAL    = $_POST['cetak_kuitansi_ket_uang_pangkal'];
        $ketUangKegiatan   = $_POST['cetak_kuitansi_ket_uang_kegiatan'];
        $ketUangBuku       = $_POST['cetak_kuitansi_ket_uang_buku'];
        $ketUangSeragam    = $_POST['cetak_kuitansi_ket_uang_seragam'];
        $ketUangRegistrasi = $_POST['cetak_kuitansi_ket_uang_registrasi'];
        $ketUangLain       = $_POST['cetak_kuitansi_ket_uang_lain'];

        $cetak_kuitansi_filter = $_POST['cetak_kuitansi_filter'];

        // Bagian Data Siswa
        $nisSiswa        = $_POST['cetak_kuitansi_nis_siswa'];
        $namaSiswa       = $_POST['cetak_kuitansi_nama_siswa'];
        $kelasSiswa      = $_POST['cetak_kuitansi_kelas_siswa'];
        $idInvoice       = $_POST['cetak_kuitansi_id_invoice'];
        $tglTf           = date_create($_POST['cetak_kuitansi_bukti_tf']);
        $tglTf           = date_format($tglTf, "d-M-y");
        $bayarBulan      = $_POST['cetak_kuitansi_bulan_pembayaran'];        

        // echo $isiUangKegiatan;exit;

        // Bagian Keterangan Data Uang yang di bayarkan Siswa
        $uangSPP            = str_replace(["Rp. ",'"', ",", "."],"", $isiUangSPP);
        $formatUangSPP      = rupiahFormat($uangSPP);
        $isiUangSPP         = str_replace(["Rp "], "", $formatUangSPP);
        $ketUangSPP         = $_POST['cetak_kuitansi_ket_uang_spp'];

        $uangPangkal        = str_replace(["Rp. ",'"', ",", "."],"", $isiUangPangkal);
        $formatUangPangkal  = rupiahFormat($uangPangkal);
        $isiUangPangkal     = str_replace(["Rp "], "", $formatUangPangkal);
        $ketUangPANGKAL;

        $uangKegiatan        = str_replace(["Rp. ",'"', ",", "."],"", $isiUangKegiatan);
        $formatUangKegiatan  = rupiahFormat($uangKegiatan);
        $isiUangKegiatan     = str_replace(["Rp "], "", $formatUangKegiatan);
        $ketUangKegiatan;        

        $uangBuku           = str_replace(["Rp. ",'"', ",", "."],"", $isiUangBuku);
        $formatUangBuku     = rupiahFormat($uangBuku);
        $isiUangBuku        = str_replace(["Rp "], "", $formatUangBuku);
        $ketUangBuku;

        $uangSeragam        = str_replace(["Rp. ",'"', ",", "."],"", $isiUangSeragam);
        $formatUangSeragam  = rupiahFormat($uangSeragam);
        $isiUangSeragam     = str_replace(["Rp "], "", $formatUangSeragam);
        $ketUangSeragam;

        $uangRegis          = str_replace(["Rp. ",'"', ",", "."],"", $isiUangRegis);
        $formatUangRegis    = rupiahFormat($uangRegis);
        $isiUangRegis       = str_replace(["Rp "], "", $formatUangRegis);  
        $ketUangRegistrasi;

        $uangLain2          = str_replace(["Rp. ",'"', ",", "."],"", $isiUangLain);
        $formatUangLain     = rupiahFormat($uangLain2);
        $isiUangLain        = str_replace(["Rp "], "", $formatUangLain);  
        $ketUangLain;

        $totalKeseluruhan = $uangSPP + $uangPangkal + $uangRegis + $uangSeragam + $uangBuku + $uangKegiatan + $uangLain2;
        $formatUangKeseluruhan = rupiah($totalKeseluruhan);
        $formatUangKeseluruhan = str_replace(["Rp "], "", $formatUangKeseluruhan);
        // $terbilang = str_replace(["Rp. ",'"', "."],"", $terbilang_nominal_uang_spp);
        $rupiah_terbilang = terbilang($totalKeseluruhan);

        // if ($_POST['cetak_kuitansi_uang_spp'] = 'kosong') {

        //     $isiUangSPP;
        //     $isiUangPangkal     = $_POST['cetak_kuitansi_uang_pangkal'];
        //     $isiUangRegis;
        //     $isiUangSeragam;
        //     $isiUangBuku;
        //     $isiUangKegiatan;
        //     $isiUangLain;

        //     $uangSPP;
        //     $ketUangSPP;
        //     $uangSeragam;
        //     $uangBuku;
        //     $uangKegiatan;
        //     $uangLain2;

        //     // Data Form
        //     $terbilang_nominal   = str_replace(["Rp. ", '"'], "", $_POST['cetak_kuitansi_uang_pangkal']);

        //     $idSiswa                      = $_POST['cetak_kuitansi_id_siswa'];
        //     $namaSiswa                    = $_POST['cetak_kuitansi_nama_siswa'];
        //     $nisSiswa                     = $_POST['cetak_kuitansi_nis_siswa'];
        //     $kelasSiswa                   = $_POST['cetak_kuitansi_kelas_siswa'];
        //     $tglTf                        = date_create($_POST['cetak_kuitansi_bukti_tf']);
        //     $tglTf                        = date_format($tglTf, "d-M-y");
        //     $bayarBulan                   = $_POST['cetak_kuitansi_pembayaran_bulan'];
        //     $ketUangPANGKAL               = $_POST['cetak_kuitansi_ket_uang_pangkal'];

        //     $uangPangkal        = str_replace(["Rp. ",'"', ",", "."],"", $_POST['cetak_kuitansi_uang_pangkal']);
        //     $formatUangPangkal  = rupiahFormat($uangPangkal);
        //     $isiUangPangkal     = str_replace(["Rp "], "", $formatUangPangkal);
        //     $totalKeseluruhan = $uangSPP + $uangPangkal + $uangRegis + $uangSeragam + $uangBuku + $uangKegiatan + $uangLain2;
        //     $formatUangKeseluruhan = rupiah($totalKeseluruhan);
        //     $formatUangKeseluruhan = str_replace(["Rp "], "", $formatUangKeseluruhan);
        //     // echo $formatUangKeseluruhan;

        //     $terbilang = str_replace(["Rp. ",'"', "."],"", $terbilang_nominal);

        //     $rupiah_terbilang = terbilang($terbilang);

        // }
         
    } else {
        echo "Tidak ada data yang dikirim";
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuitansi</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: white;
        }

        .container {
            width: 70%;
            margin-top: 50px !important;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: white;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        #kw {
            margin-left: 385px;
            font-size: 20px;
            color: rgba(26, 18, 136, 1);
        }

        .header h1 {
            margin: 0;
        }

        .flex-container1 {
          display: flex;
          justify-content: center;
        }

        hr.new1 {
            width: 400px;
            margin-left: 10px;
            border-top: 3px solid black;
        }

        .flex-container1 > div {
/*          border: 1px solid black;*/
          margin: 10px;
          width: 50%;
/*          padding: 20px;*/
        }

        .details {
            margin-bottom: 20px;
        }

        .details p {
            margin: 5px 0;
        }

        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .item-table th,
        .item-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        .item-table th {
            background-color: #f2f2f2;
        }

        .total {
            text-align: right;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div style="display: flex;">
            <div class="foto">
                <img src="logo_stempel.png" style="width: 70px; height: 70px;">
            </div>
            <div class="kop-surat" style="color: rgba(26, 18, 136, 1);">
                <strong style="margin-left: 120px;">
                    <b>
                    SEKOLAH DASAR
                    </b>
                    <br>
                </strong>
                <strong style="margin-left: 15px;">
                    <b>
                    AKHYAR INTERNATIONAL ISLAMIC SCHOOL
                    </b>
                </strong>
                <p style="color: rgba(26, 18, 136, 1); font-size: 10px; margin-left: 80px;"> Komplek Green View, Jl. Green View Blok E No.3-4-5, <br></p>
                <p style="color: rgba(26, 18, 136, 1); font-size: 10px; margin-left: 43px;"> Kel. Jaka Setia, Kec. Bekasi Selatan, Kota Bekasi, Jawa Barat 17147 <br></p>
                <p style="color: rgba(26, 18, 136, 1); font-size: 10px; margin-left: 141px; margin-top: -10px;"> Telp. 021-82772882 <span id="kw"> <strong> KWITANSI PEMBAYARAN </strong> </span> <span style="color: red; font-size: 20px;"> <strong> <b> SD </b> </strong> </span> </p>
            </div>
        </div>

        <!-- <div class="details">
            <p> NIS  <span style="margin-left: 30px;"></span> : <span style="margin-left: 20px;">  </span> 202102106 </p>
            <p> NAMA  <span style="margin-left: 10px;"></span> : <span style="margin-left: 20px;">  </span> UWAIS AHZA RABBANI </p>
            <p> KELAS  <span style="margin-left: 5px;"></span> : <span style="margin-left: 20px;">  </span> 3 SD </p>
        </div> -->

        <div class="flex-container1">

            <div style="width: 80%;">
            
                <div>

                    <div class="details">
                        <p> NIS <span style="margin-left: 30px;"></span> : <span style="margin-left: 20px;">  </span> <strong> <?= $nisSiswa; ?> </strong> </p>
                        <!-- <p> NAMA  <span style="margin-left: 10px;"></span> : <span style="margin-left: 20px;">  </span> AIDIL HAMIZAN IBRAHIM PUSPANEGARA </p> -->
                        <p> NAMA  <span style="margin-left: 12px;"></span> : <span style="margin-left: 18px;"> </span> <strong> <?= $namaSiswa; ?> </strong> </p>
                        <p> KELAS  <span style="margin-left: 6px;"></span> : <span style="margin-left: 18px;"> </span> <strong> <?= $kelasSiswa; ?> </strong> </p>
                    </div>
                    
                </div>

            </div>

            <div>

                <div>

                    <div class="details">
                        <p> <strong> TAHUN AJARAN </strong> <span style="margin-left: 30px;"></span> : <span style="margin-left: 15px;">  </span>  </p>
                    </div> 
                    
                </div>

            </div>

            <div>
            
                <div>

                    <div class="details">
                        <p> NO <span style="margin-left: 42px;"></span> : <span style="margin-left: 20px;"> </span> <strong> <?= $idInvoice; ?> </strong> </p>
                        <!-- <p> ID Siswa <span style="margin-left: 3px;"></span> : <span style="margin-left: 20px;"> </span> <strong> <?= $idSiswa; ?> </strong> </p> -->
                        <p> Tanggal  <span style="margin-left: 10px;"></span> : <span style="margin-left: 20px;"> </span> <strong> <?= $tglTf; ?> </strong> </p>
                        <p> Bulan  <span style="margin-left: 25px;"></span> : <span style="margin-left: 20px;"> </span> <strong> <?= $bayarBulan; ?> </strong> </p>
                    </div>
                    
                </div>

            </div>  

        </div>

        <div class="flex-container1" style="margin-top: -35px; margin-left: 0px;">

            <div style="width: 100%;">
            
                <div>

                    <div class="details">
                        <p> <strong> Terbilang </strong> <span style="margin-left: 3px;"></span> : <input type="text" readonly style="text-align: center; width: 90%; font-weight: bold; font-style: italic;" value='<?= '"'. terbilang($totalKeseluruhan) . '"'; ?>' name=""> </p>
                    </div>
                    
                </div>

            </div>

        </div>

        <div class="flex-container1">

            <div style="width: 25%;">
            
                <div>

                    <div class="details">
                        <p> <strong> Untuk Pembayaran </strong> <span style="margin-left: 30px;"></span> :  </p>
                    </div>
                    
                </div>

            </div>

            <div style="margin-top: 16px;">

                <div>

                    <div class="details">
                        <strong> 
                            1. Uang SPP
                        </strong> 
                        <span style="margin-left: 59px;"> </span> <strong> Rp. </strong> <span style="margin-left: 4px;"></span> <input type="text" readonly value="<?= $isiUangSPP; ?>" style="text-align: end; font-weight: bold; width: 25%;" name="">
                    </div>

                    <div class="details">
                        <strong>
                            2. Uang Pangkal 
                        </strong>
                        <span style="margin-left: 28px;"> </span> <strong> Rp. </strong> <span style="margin-left: 5px;"></span> <input type="text" readonly value="<?= $isiUangPangkal; ?>" style="font-weight: bold; text-align: end; width: 25%;" name="">
                    </div>

                    <div class="details">
                        <strong>
                            3. Uang Kegiatan 
                        </strong>
                        <span style="margin-left: 22px;">  </span> <strong> Rp. </strong> <span style="margin-left: 5px;"></span> <input type="text" readonly value="<?= $isiUangKegiatan; ?>" style="font-weight: bold; text-align: end; width: 25%;" name="">
                    </div>

                    <div class="details">
                        <strong> 
                            4. Uang Buku 
                        </strong>
                        <span style="margin-left: 49px;">  </span> <strong> Rp. </strong> <span style="margin-left: 5px;"></span> <input type="text" readonly value="<?= $isiUangBuku; ?>" style="text-align: end; font-weight: bold; width: 25%;" name="">
                    </div>

                    <div class="details">
                        <strong> 
                            5. Uang Seragam 
                        </strong>
                        <span style="margin-left: 21px;">  </span> <strong> Rp. </strong> <span style="margin-left: 5px;"></span> <input type="text" readonly value="<?= $isiUangSeragam; ?>" style="font-weight: bold; text-align: end; width: 25%;" name="">
                    </div>

                    <div class="details">
                        <strong> 
                            6. Uang Registrasi 
                        </strong>
                        <span style="margin-left: 11px;"> </span> <strong> Rp. </strong> <span style="margin-left: 5px;"></span> <input type="text" readonly value="<?= $isiUangRegis; ?>" style="font-weight: bold; text-align: end; width: 25%;" name="">
                    </div>

                    <div class="details">
                        <strong> 
                            7. Uang Lain-lain 
                        </strong>
                        <span style="margin-left: 23px;"> </span> <strong> Rp. </strong> <span style="margin-left: 5px;"></span> <input type="text" readonly value="<?= $isiUangLain; ?>" style="font-weight: bold; text-align: end; width: 25%;" name="">
                    </div> 
                    
                </div>

            </div>

            <div style="margin-top: 16px;">

                <div>

                    <div class="details">
                        <input type="text" readonly value="<?= $ketUangSPP; ?>" name="" style="margin-left: -110px; width: 131%; font-weight: bold;">
                    </div>

                    <div class="details">
                        <input type="text" readonly value="<?= $ketUangPANGKAL; ?>" style="margin-left: -110px; width: 131%; font-weight: bold;">
                    </div>

                    <div class="details">
                        <input type="text" readonly value="<?= $ketUangKegiatan; ?>" name="" style="margin-left: -110px; width: 131%; font-weight: bold;">
                    </div>

                    <div class="details">
                        <input type="text" readonly="" value="<?= $ketUangBuku; ?>" name="" style="margin-left: -110px; width: 131%; font-weight: bold;">
                    </div>

                    <div class="details">
                        <input type="text" readonly="" value="<?= $ketUangSeragam; ?>" style="margin-left: -110px; width: 131%; font-weight: bold;">
                    </div>

                    <div class="details">
                        <input type="text" readonly value="<?= $ketUangRegistrasi; ?>" style="margin-left: -110px; width: 131%; font-weight: bold;">
                    </div>

                    <div class="details">
                        <input type="text" readonly="" value="<?= $ketUangLain; ?>" style="margin-left: -110px; width: 131%; font-weight: bold;">
                    </div> 
                    
                </div>

            </div>  

        </div>

        <!-- Jumlah -->
        <hr class="new1" />

        <div class="flex-container1" style="margin-top: -15px; margin-left: -98px;">

            <div style="width: 24%; margin-left: -50px;">
            
                <div>

                    <div class="details">
                        <p style="margin: 10px; font-size: 20px;"> <strong> Jumlah  <span style="margin-left: 30px;"></span> Rp. </strong> 
                    </div>
                    
                </div>

            </div>

            <div>

                <div>

                    <div class="details">
                        <input type="text" readonly value="<?= $formatUangKeseluruhan; ?>" name="" style="height: 30px; font-size: 20px; margin-top: 5px; text-align: end; margin-left: -100px; font-weight: bold; width: 200px;">
                    </div> 
                    
                </div>

            </div>

        </div>        

        <hr class="new1" / style="margin-top: -20px;">

        <!-- Akhir Jumlah -->

        <div class="flex-container1" style="margin-top: -15px;">

            <div style="width: 38%;">
            
                <div>

                    <div class="details">
                    </div>
                    
                </div>

            </div>

            <div>

                <div>

                    <div class="details">
                    </div> 
                    
                </div>

            </div>

            <div style="margin-top: -4px;">
            
                <div>

                    <div class="details">
                        <fieldset>
                          <legend> Bekasi, <?= $tglTf; ?> </legend>
                          <div class="ksg" style="display: flex;">
                            <div class="logos" style="margin-top: 5px;">
                                <img src="logo_stempel.png" width="75" height="75">  
                            </div>
                            <div class="stempel">
                                <img src="stempel.jpg" style="margin-left: 10px;" width="210" height="90">
                            </div>
                          </div>
                        </fieldset>
                    </div>
                    
                </div>

            </div>  

        </div>

        <div class="flex-container1" style="margin-top: -75px;margin-left: 15px;">

            <div style="width: 38%;">
            
                <div>

                    <div class="details">
                        Lembar 1: untuk Siswa
                    </div>
                    
                </div>

            </div>

            <div>

                <div>

                    <div class="details">
                    </div> 
                    
                </div>

            </div>

            <div style="margin-top: -4px;">
            
                <div>

                    <div class="details">
                    </div>
                    
                </div>

            </div>  

        </div>

    </div>
</body>

</html>