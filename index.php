<?php 
include_once('confiq.php');
//cek login
// user
if(isset($_COOKIE['id'])){
  $id = $_COOKIE['id'];
  $keterangan = $_COOKIE['keterangan'];

  if($keterangan == "user"){
    $sql = "SELECT `nomor_anggota`, `nama` FROM anggota
            WHERE `nomor_anggota`='$id'";

    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if(!empty($data)){
      //jika valid, set session login
      $_SESSION['id'] = $data['nomor_anggota'];
      $_SESSION['nama'] = $data['nama'];
      $_SESSION['keterangan'] = "user";

      header('location: account\user\home.php');
      exit();
    }
  }
  // admin
  elseif($keterangan == "admin"){  
    $sql = "SELECT `nomor_petugas`, `nama` FROM petugas
            WHERE `nomor_petugas`='$id'";
  
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    if(!empty($data)){
      //jika valid, set session login
      $_SESSION['id'] = $data['nomor_petugas'];
      $_SESSION['nama'] = $data['nama'];
      $_SESSION['keterangan'] = "admin";

      header('location: account\admin\home.php');
      exit();
    }
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Simperpus</title>
    <!-- favicon -->
    <link rel="shortcut icon" href="file/logo-login.png">
  </head>
  <body>
    <!-- sidebar & navbar -->
    <?php
      include('account/basic/layout/sidebar.html');
      include('home.php');
    ?>
  </body>
</html>