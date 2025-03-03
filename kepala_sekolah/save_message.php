<?php 
	
	require '../php/config.php';

	$message = mysqli_real_escape_string($con,htmlspecialchars($_POST['message']));
	$room    = $_POST['room'];
	$c_user  = $_POST['user'];

	$cariRoomKomen     	= mysqli_query($con, "SELECT room_key FROM ruang_pesan WHERE room_key LIKE '%$room%' ");
	$getKeyRoomServer   = mysqli_fetch_array($cariRoomKomen);

	$sqlInsertChat  = mysqli_query($con, "
		INSERT INTO tbl_komentar 
		SET 
		code_user 		= '$c_user', 
		isi_komentar  	= '$message', 
		room_id   		= '$getKeyRoomServer[room_key]'
	");

	if ($sqlInsertChat === TRUE) {	    // echo "Message saved successfully!";
	    echo json_encode([
	        "stat_code"     => 200,
	        "message"       => "Pesan Berhasil Dikirim",
	        "room"          => $room,
	        "message_main"  => $message,
	    ]);
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}

?>