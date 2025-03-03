<?php  

    require '../php/config.php';

    $no = 1;

    $ambildata_perhalaman = mysqli_query($con, "
        SELECT nama, username, no_hp
        FROM guru
        ORDER BY nama ASC
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
        header("Content-Disposition: attachment; filename=data_login_aplikasi_activities_guru.xls");

    ?>

     <div style="overflow-x: auto; margin: 10px;">
                            
        <table id="example_semua" class="table table-bordered" border="1">
            <thead>
              <tr>
                 <th style="text-align: center;"> NO </th>
                 <th style="text-align: center;"> NAMA </th>
                 <th style="text-align: center;"> USERNAME </th>
                 <!-- <th style="text-align: center;"> NO HP </th> -->
              </tr>
            </thead>
            <tbody>

                <?php foreach ($ambildata_perhalaman as $data) : ?>

                    <tr>

                        <td style="text-align: center;"> <?= $no++; ?> </td>
                        <td style="text-align: center;"> <?= $data['nama']; ?> </td>
                        <td style="text-align: center;"> <?= $data['username']; ?> </td>
                        <!-- <td style="text-align: center;"> 0<?= $data['no_hp']; ?> </td> -->

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    </div>

</body>
</html>