<?php session_start(); if(empty($_SESSION['x_admin'])){ header('location:../login'); }
else {header('location:main');} ?>