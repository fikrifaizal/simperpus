<?php
/* mysqli_connect for database connection */

$dbServername = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'digilib_smkmuh1_surakarta';

// connect to database
$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

// check connection
if(!$conn -> connect_error){
}
else{
  echo 'Connection error: '.mysqli_connect_error();
}
?>