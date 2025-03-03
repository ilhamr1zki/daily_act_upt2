<?php  

    require '../php/config.php';

    $no = 1;

    $ambildata_perhalaman = mysqli_query($con, "
        SELECT siswa.nama, akses_otm.nis_siswa, akses_otm.`password`, akses_otm.no_hp
        FROM siswa
        LEFT JOIN akses_otm
        ON akses_otm.nis_siswa = siswa.nis
        WHERE siswa.c_kelas <> 'TKBLULUS'
    ");

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title> EXPORT EXCEL </title>
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

    <?php  

        header("Content-type: application/vnd-ms-excel");
        header("Content-Disposition: attachment; filename=data_login_otm.xls");

    ?>

     <div style="overflow-x: auto; margin: 10px;">
                            
        <table id="example_semua" class="table table-bordered" border="1">
            <thead>
              <tr>
                 <th style="text-align: center;"> NO </th>
                 <th style="text-align: center;"> NAMA </th>
                 <th style="text-align: center;"> NIS </th>
                 <th style="text-align: center;"> PASSWORD </th>
                 <th style="text-align: center;"> NO HP </th>
              </tr>
            </thead>
            <tbody>

                <?php foreach ($ambildata_perhalaman as $data) : ?>

                    <tr>

                        <td style="text-align: center;"> <?= $no++; ?> </td>
                        <td style="text-align: center;"> <?= $data['nama']; ?> </td>
                        <td style="text-align: center;"> <?= $data['nis_siswa']; ?> </td>
                        <td style="text-align: center;"> <?= $data['password']; ?> </td>
                        <td style="text-align: center;"> 0<?= $data['no_hp']; ?> </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</body>
</html>