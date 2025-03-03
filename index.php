<?php 

	session_start(); 
	if (isset($_SESSION['key_admin'])) { 
		header('location:control/'); 
	} else if (isset($_SESSION['c_guru'])) { 
		header('location:guru/'); 
	} else {
		header('location:login');
	} 

?>