<?php  
	// libxml_use_internal_errors(true);
	$conn = mysqli_connect("localhost", "root", "", "pegawai");
	

	if (isset($_POST['import'])) {

		$err 		= "";
		$ekstensi   = "";
		$success    = "";

		$file_name  = $_FILES['excel']['name'];
		$file_data  = $_FILES['excel']['tmp_name']; 
		// echo $file_data;exit;

		$namaFile 	= $_FILES['excel']['name'];
		$ukuranFile = $_FILES['excel']['size'];
		$error 		= $_FILES['excel']['error'];
		$tmpName 	= $_FILES['excel']['tmp_name'];

		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file_data);
			$spreadsheet = $reader->load($file_data);
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			var_dump($sheetData);exit;

			$jumlahData = 0;
			for ($i=1; $i < count($sheetData); $i++) { 
				$bulanBayar = $sheetData[$i][3];
				$namaSiswa = $sheetData[$i][5];

				echo "Bulan Bayar : " . $bulanBayar . "<br>";
				echo "Nama : " . $namaSiswa . "<br>";
			}

		// cek apakah tidak ada file yang diupload
		if( $error === 4 ) {
			$_SESSION['form_success'] = "empty_form";
		} else if ($error != 4) {

			echo "Ke sini";

			$ekstensi = pathinfo($file_name)['extension'];
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file_data);
			$spreadsheet = $reader->load($file_data);
			var_dump($spreadsheet);exit;
			$sheetData = $spreadsheet->getActiveSheet()->toArray();

			$jumlahData = 0;
			for ($i=1; $i < count($sheetData); $i++) { 
				$bulanBayar = $sheetData[$i][3];
				$namaSiswa = $sheetData[$i][5];

				echo "Bulan Bayar : " . $bulanBayar . "<br>";
				echo "Nama : " . $namaSiswa . "<br>";
			}

		}

		// cek apakah yang diupload adalah file ber tipe xls, xlsx
		$ekstensiFileValid 	= ['xlsx', 'xls'];
		$ekstensiFile 		= explode('.', $namaFile);
		$ekstensiFile 		= strtolower(end($ekstensiFile) );

		if( !in_array($ekstensiFile, $ekstensiFileValid) ) {
			$_SESSION['form_success'] = "type_fail";
		}

		// cek jika ukurannya terlalu besar
		if( $ukuranFile > 5000000 ) {
			$_SESSION['form_success'] = "size_too_big";
		} 

		$ekstensi_allowed = ['xls', 'xlsx'];
		if (!in_array($ekstensi, $ekstensi_allowed)) {
			$_SESSION['form_success'] = "type_fail";
		}

	}

	$data = mysqli_query($conn, "SELECT * FROM data_pegawai");

?>

<!DOCTYPE html>
<html>
<head>
	<title> Import Excel </title>
</head>
<body>

	<div class="row">
	    <div class="col-xs-12 col-md-12 col-lg-12">

	        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'type_fail'){?>
	          <div style="display: none;" class="alert alert-danger alert-dismissable"> Silahkan Masukan file bertipe xls, atau xlsx
	             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	             <?php unset($_SESSION['form_success']); ?>
	          </div>
	        <?php } ?>

	        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'empty_form'){?>
	          <div style="display: none;" class="alert alert-danger alert-dismissable"> Tidak Ada File Yang Di Upload
	             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	             <?php unset($_SESSION['form_success']); ?>
	          </div>
	        <?php } ?>

	        <?php if(isset($_SESSION['form_success']) && $_SESSION['form_success'] == 'size_too_big'){?>
	          <div style="display: none;" class="alert alert-danger alert-dismissable"> Ukuran File Terlalu Besar
	             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	             <?php unset($_SESSION['form_success']); ?>
	          </div>
	        <?php } ?>

	    </div>
	</div>

	<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title"> <i class="glyphicon glyphicon-new-window"></i> Import Data Excel </h3>
       
    </div>

    <form action="<?= $basead; ?>upload" enctype="multipart/form-data" method="post">
        <div class="box-body">

            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Import File Excel</label>
                        <input type="file" name="file_excel" accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="form-control" id="id_siswa" />
                        <input type="submit" name="uploadx" style="margin-top: 10px;" class="btn btn-sm btn-success" id="id_siswa" value="Import" />
                    </div>
                </div>
            </div> 

            
        </div>
    </form>

    <?php
	if (isset($_POST['uploadx'])) {

		// echo "SINI";exit;

		

		// Validasi apakah type file ber type xlsx, xls
		$namaFile 			= $_FILES['file_excel']['name'];
		$ekstensiFileValid 	= ['xlsx', 'xls'];
		$ekstensiFile 		= explode('.', $namaFile);
		$ekstensiFile 		= strtolower(end($ekstensiFile) );

		if( !in_array($ekstensiFile, $ekstensiFileValid) ) {
			echo "Type File Invalid. Yang Anda Masukan File Ber Type " . $ekstensiFile;exit;
			$_SESSION['form_success'] = "type_fail";
		}

		//upload data excel kedalam folder uploads
		$target_dir = "uploads/".basename($_FILES['file_excel']['name']);
		
		move_uploaded_file($_FILES['file_excel']['tmp_name'],$target_dir);

		$Reader = new SpreadsheetReader($target_dir);

		foreach ($Reader as $Key => $Row)
		{
			// import data excel mulai baris ke-2 (karena ada header pada baris 1)
			echo "Nomer KEY : " . $Key . "<br>";
			if ($Key < 1) continue;			
			echo "<br> Isinya : " . $Row[1];exit;
			$query= mysqli_query ($con,"INSERT INTO tbdata(id,nama,nik,umur,alamat) VALUES ('".$Row[0]."', '".$Row[1]."','".$Row[2]."','".$Row[3]."','".$Row[4]."')");
		}
		if ($query) {
				echo "Import data berhasil";
			}else{
				echo "<script>alert('Gagal')</script>";
			}
	}
	?>
	<h2>Data</h2>
	<table border="1">
		<tr>
			<th>ID</th>
			<th>NAMA</th>
			<th>NIK</th>
			<th>UMUR</th>
			<th>ALAMAT</th>
		<?php
		include 'koneksi.php';
		$data = mysqli_query($con, "SELECT * FROM tbdata");
		if ($data === FALSE){
			die(mysqli_error());
		}
		while($d = mysqli_fetch_assoc($data)){
			?>
			<tr>
				<td><?=$d['id']; ?></td>
				<td><?=$d['nama']; ?></td>
				<td><?=$d['nik']; ?></td>				
				<td><?=$d['umur']; ?></td>
				<td><?=$d['alamat']; ?></td>
			</tr>
			<?php 
		}
		?>
	</table>
    
</div>

<script type="text/javascript">

	$(document).ready(function(){
		$("#list_spp").click();
	    $("#upload_data").css({
	        "background-color" : "#ccc",
	        "color" : "black"
	    });
	})

</script>

</body>
</html>