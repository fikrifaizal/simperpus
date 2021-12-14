<?php
session_start();

if(!isset($_SESSION['status']) && $_SESSION['keterangan'] != "user"){
	header("location: /SimPerpus%20SMK%20Muh%201%20Surakarta/auth/login.php");
}
?>