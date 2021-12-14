<?php
session_start();
session_destroy();

//hapus cookie
setcookie('id', '', 0, '/');
setcookie('keterangan', '', 0, '/');

header("location: ../index.php");
?>