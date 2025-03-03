<?php  
    
    require '../php/config.php';
    require '../php/function.php';

    function rupiah($angka){
    
        $hasil_rupiah = "Rp " . number_format($angka,2,',','.');
        return $hasil_rupiah;
     
    }

    function rupiahFormat($angkax){
    
        $hasil_rupiahx = "Rp " . number_format($angkax,0,'.',',');
        return $hasil_rupiahx;
     
    }

    $arr    = [];
    $arrNIS = [];

    for ($i=0; $i < 393; $i++) { 
        $arr[] = random(5);
    }

    $ambildata_perhalaman = mysqli_query($con, 'SELECT c_siswa, nis, nama, c_kelas FROM siswa');

    // foreach ($ambildata_perhalaman as $data) {
    //     $arrNIS[] = $data['nis'];
    // }

    // for ($i=0; $i < count($arr); $i++) { 

    //      mysqli_query($con, "
    //         INSERT INTO akses_otm
    //         SET 
    //         nis_siswa = '$arrNIS[$i]',
    //         password  = '$arr[$i]'
    //     ");

    // }

    // var_dump($arr)

?>


<!DOCTYPE html>
<html>
<head>
    <title>EXPORT EXCEL</title>
</head>
<body>
    <style type="text/css">
        body{
            font-family: sans-serif;
        }
        table{
            margin: 20px auto;
            border-collapse: collapse;
        }
        table th,
        table td{
            border: 1px solid #3c3c3c;
            padding: 3px 8px;

        }
        a{
            background: blue;
            color: #fff;
            padding: 8px 10px;
            text-decoration: none;
            border-radius: 2px;
        }
    </style>

   

    <div style="overflow-x: auto; margin: 10px;">
                            
        <table id="example_semua" class="table table-bordered" border="1">
            <thead>
              <tr>
                 <th style="text-align: center;"> c_siswa </th>
                 <th style="text-align: center;"> nis </th>
                 <th style="text-align: center;"> nama </th>
                 <th style="text-align: center;"> c_kelas </th>
              </tr>
            </thead>
            <tbody>

                <?php foreach ($ambildata_perhalaman as $data): ?>
                    <tr>
                        <td> <?= $data['c_siswa']; ?> </td>
                        <td> <?= $data['nis']; ?> </td>
                        <td> <?= $data['nama']; ?> </td>
                        <td> <?= $data['c_kelas']; ?> </td>
                    </tr>
                <?php endforeach ?>

            </tbody>

        </table>

    </div>

</body>
</html>