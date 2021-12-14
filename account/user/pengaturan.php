<?php 
include('../../confiq.php');
include_once('akses.php');
$id = $_SESSION['id'];
$setDangerCondition = false;
$setDangerText = "";

if(isset($_POST['ubah'])){
  if($_POST['nomor'] == $id){
    if(md5($_POST['passwd']) == md5($_POST['passwdulang'])){
      $passwd = md5($_POST['passwd']);

      $update = "UPDATE `anggota` set `password`='$passwd' WHERE `nomor_anggota` LIKE '$id'";
      $updateBuku = mysqli_query($conn, $update);
      
      $setDangerCondition = true;
      $setDangerText = "Password berhasil diubah";
    } else {
      $setDangerCondition = true;
      $setDangerText = "Kedua password berbeda";
    }
  } else {
    $setDangerCondition = true;
    $setDangerText = "NISN/NIP berbeda";
  }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Home</title>
  </head>

  <body>
    <!-- sidebar & navbar -->
    <?php
      include('layout/sidebar.php');
    ?>

    <!-- konten -->
    <main>
      <div class="container-fluid content transition">
        <h3>Ubah Password</h3>
        <a href="home.php" class="btn btn-success btn-sm mb-3">
          <span><i class="bi bi-chevron-left"></i></span>
          <span>Kembali</span>
        </a>
        
        <div class="card border">
          <div class="card-body mt-2 mx-1">

            <!-- form input -->
            <form method="post" enctype="multipart/form-data">
              <!-- Password -->
              <div class="form-group row">
                <label for="passwdBaru" class="col-sm-2 col-form-label">Password Baru</label>
                <div class="col-sm-10">
                  <input type="password" name="passwd" class="form-control" id="passwdBaru" required>
                </div>
              </div><br>
              <!-- Ulang Password -->
              <div class="form-group row">
                <label for="passwdBaruUlang" class="col-sm-2 col-form-label">Ulangi Password Baru</label>
                <div class="col-sm-10">
                  <input type="password" name="passwdulang" class="form-control" id="passwdBaruUlang" required>
                </div>
              </div><hr>

              <h5>Konfirmasi</h5>
              <!-- nisn -->
              <div class="form-group row">
                <label for="nisn" class="col-sm-2 col-form-label">NISN/NIP</label>
                <div class="col-sm-10">
                  <input type="text" name="nomor" class="form-control" id="nisn" required>
                  <small id="isbn" class="form-text text-muted">
                    Guna mengonfirmasi anda adalah pemilik akun
                  </small>
                </div>
              </div>
              
              <!-- Button -->
              <div class="form-group row mt-2">
                <label for="button" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col col-md-6 d-grid gap-2">
                    </div>
                    <div class="col col-md-6 d-grid gap-2">
                      <button type="submit" name="ubah" class="btn btn-success btn-block">
                        <span><i class="bi bi-pencil"></i></span>
                        <span>Update</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
            
            <!-- Modal Danger -->
            <div class="modal fade" tabindex="-1" id="modalDanger" aria-hidden="true" >
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Peringatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p><?=$setDangerText?></p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">OK</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    
    <!-- Show Danger Modal -->
    <?php
      if($setDangerCondition) {
        echo '<script type="text/javascript">
                $(document).ready(function(){
                  $("#modalDanger").modal("show");
                });
              </script>';
      } 
    ?>
  </body>
</html>