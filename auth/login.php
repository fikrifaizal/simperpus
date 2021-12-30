<?php 
//memulai session
session_start();
include_once('../confiq.php');
$showModal = false;

// user
if(isset($_POST['loginuser'])){
  $id = $_POST['id'];
  $passwd = md5($_POST['passwd']);
  
  $sql = "SELECT `nomor_anggota`, `nama` FROM anggota
          WHERE `nomor_anggota`='$id' AND `password`='$passwd'";

  $result = mysqli_query($conn, $sql);
  $data = mysqli_fetch_array($result, MYSQLI_ASSOC);

  if(!empty($data)){
    $_SESSION['id'] = $data['nomor_anggota'];
    $_SESSION['nama'] = $data['nama'];
    $_SESSION['keterangan'] = "user";

    //cek remember me
    if($_POST['remember'] == "remember"){
      //set cookie
      setcookie('id', $id, time()+3600, '/');
      setcookie('keterangan', 'user', time()+3600, '/');
    }

    header("location: ..\account\user\home.php");
  } else{
    $showModal = true;
  }
}
// admin
elseif(isset($_POST['loginadmin'])){  
  $id = $_POST['id'];
  $passwd = md5($_POST['passwd']);
  
  $sql = "SELECT `nomor_petugas`, `nama` FROM petugas
          WHERE `nomor_petugas`='$id' AND `password`='$passwd'";

  $result = mysqli_query($conn, $sql);
  $data = mysqli_fetch_array($result, MYSQLI_ASSOC);

  if(!empty($data)){
    $_SESSION['id'] = $data['nomor_petugas'];
    $_SESSION['nama'] = $data['nama'];
    $_SESSION['keterangan'] = "admin";

    //cek remember me
    if($_POST['remember'] == "remember"){
      //set cookie
      setcookie('id', $id, time() + 3600, '/');
      setcookie('keterangan', 'admin', time()+3600, '/');
    }

    header("location: ..\account\admin\home.php");
  } else{
    $showModal = true;
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <!-- favicon -->
    <link rel="shortcut icon" href="../file/logo-login.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500;1,600&display=swap" />
    <link rel="stylesheet" href="login.css" />
  </head>
  
  <body class="text-center">
    <main class="form-signin">
      <div class="transition">
        <img class="logo mb-1" src="../file/logo-login.png" alt="">
        <h1 class="h3 mb-3">Sistem Informasi Perpustakaan</h1>

        <!-- nav-tabs button -->
        <ul class="nav nav-tabs nav-justified" id="pills-tab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-user-tab" data-bs-toggle="pill" data-bs-target="#pills-user" type="button" role="tab" aria-controls="pills-user" aria-selected="true">User</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-admin-tab" data-bs-toggle="pill" data-bs-target="#pills-admin" type="button" role="tab" aria-controls="pills-admin" aria-selected="false">Admin</button>
          </li>
        </ul>

        <!-- nav-tabs content -->
        <div class="tab-content" id="pills-tabContent">
            <!-- user -->
          <div class="tab-pane fade show active" id="pills-user" role="tabpanel" aria-labelledby="pills-user-tab">
            <form method="post">
              <div class="form-floating">
                <input type="text" name="id" class="form-control" id="floatingInput" placeholder="19106050000" required>
                <label for="floatingInput">NISN atau NIP</label>
              </div>
              <div class="form-floating">
                <input type="password" name="passwd" class="form-control" id="floatingPassword" placeholder="Password" required>
                <label for="floatingPassword">Password</label>
              </div>

              <div class="checkbox mb-3">
                <div class="form-check">
                  <label>
                    <input class="form-check-input" type="checkbox" name="remember" value="remember"> Remember me
                  </label>
                </div>
              </div>
              <button type="submit" name="loginuser" class="w-100 mb-1 btn btn-primary">Login</button>
              <a role="button" class="w-100 mb-2 btn btn-secondary" href="../home.phps">Kembali</a>
            </form>
          </div>

            <!-- admin -->
          <div class="tab-pane fade" id="pills-admin" role="tabpanel" aria-labelledby="pills-admin-tab">
            <form method="post">
              <div class="form-floating">
                <input type="text" name="id" class="form-control" id="floatingInput" placeholder="191101012001012001" required>
                <label for="floatingInput">NIP</label>
              </div>
              <div class="form-floating">
                <input type="password" name="passwd" class="form-control" id="floatingPassword" placeholder="Password" required>
                <label for="floatingPassword">Password</label>
              </div>
              
              <div class="checkbox mb-3">
                <div class="form-check">
                  <label>
                    <input class="form-check-input" type="checkbox" name="remember" value="remember"> Remember me
                  </label>
                </div>
              </div>
              <button type="submit" name="loginadmin" class="w-100 mb-1 btn btn-primary">Login</button>
              <a role="button" class="w-100 mb-2 btn btn-secondary" href="../home.php">Kembali</a>
            </form>
          </div>
        </div>
        
        <!-- Modal Danger -->
        <div class="modal fade" tabindex="-1" id="modalDanger" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Email atau Password salah!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Javascript Support -->
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    
    <!-- Show Danger Modal -->
    <?php
      if($showModal) {
        echo '<script type="text/javascript">
                $(document).ready(function(){
                  $("#modalDanger").modal("show");
                });
              </script>';
      } 
    ?>
  </body>
</html>