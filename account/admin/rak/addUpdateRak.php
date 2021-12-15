<?php
include('../../../confiq.php');
include_once('../akses.php');

$nomorRak = "";
$namaRak = "";
$kategori = "";
$btn_addUpdate = "";
$icon_addUpdate = "";
$hidden = "";
$setDangerCondition = false;
$setDangerText = "";

$query = "SELECT * FROM `rak`";
$result = mysqli_query($conn, $query);

$action = $_GET['action'];
// tambah
if($action == "tambah"){
  $btn_addUpdate = "Tambah";
  $icon_addUpdate = "bi-plus";

  if(isset($_POST['tambah'])){
    $namaRak = ucwords($_POST['nama']);
    $kategori = $_POST['kategori'];
    
    $queryKategori = "SELECT `nomor_rak` FROM `rak` WHERE `kategori` LIKE '$kategori'";
    $getQueryKategori = mysqli_query($conn, $queryKategori);
    $getNumber = 0;
    $count = 1;

    while($data = mysqli_fetch_array($getQueryKategori, MYSQLI_ASSOC)){
      $getNumber = intval(substr($data['nomor_rak'],4));
      if($count == $getNumber){
        $count = $count+1;
      }
    }
    $nomorRak = substr($kategori,0,3)."/".strval($count);

    $insert = "INSERT INTO `rak`(`nomor_rak`, `nama_rak`, `jumlah_buku`, `kategori`)
                VALUES ('$nomorRak', '$namaRak', 0, '$kategori')";
    $tambahRak = mysqli_query($conn, $insert);

    $setDangerCondition = true;
    $setDangerText = "Data rak berhasil di upload ke database";
  }
}
// edit
elseif($action == "edit"){
  $nomorRak = $_GET['nomor_rak'];
  $btn_addUpdate = "Ubah";
  $icon_addUpdate = "bi-pencil";
  $hidden = "hidden";

  $getRak = "SELECT * FROM `rak` WHERE `nomor_rak` LIKE '$nomorRak'";
  $queryRak = mysqli_query($conn, $getRak);
  $data = mysqli_fetch_array($queryRak, MYSQLI_ASSOC);

  $namaRak = $data['nama_rak'];
  $kategori = $data['kategori'];

  if(isset($_POST['tambah'])){
    $namaRak = ucwords($_POST['nama']);

    $update = "UPDATE `rak` set `nama_rak`='$namaRak' WHERE `nomor_rak` LIKE '$nomorRak'";
    $updateRak = mysqli_query($conn, $update);

    $setDangerCondition = true;
    $setDangerText = "Data rak berhasil diubah";
  }
}
// delete
elseif($action == "delete"){
  $nomorRak = $_GET['nomor_rak'];

  $getJumlahBuku = "SELECT `jumlah_buku` FROM `rak` WHERE `nomor_rak` LIKE '$nomorRak'";
  $queryJumlahBuku = mysqli_query($conn, $getJumlahBuku);
  $data = mysqli_fetch_array($queryJumlahBuku, MYSQLI_ASSOC);

  if($data['jumlah_buku'] >= 1){
    header("Location: rak.php");
  } else {
    $delete = "DELETE FROM `rak` WHERE `nomor_rak` LIKE '$nomorRak'";
    $deleteRak = mysqli_query($conn, $delete);

    header("Location: rak.php");
  }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Tambah Rak</title>
  </head>

  <body>
    <!-- sidebar & navbar -->
    <?php
      include('../layout/sidebar.php');
    ?>

    <!-- konten -->
    <main>
      <div class="container-fluid content transition">
        <h3>Tambah Rak Baru</h3>
        <a href="rak.php" class="btn btn-success btn-sm mb-3">
          <span><i class="bi bi-chevron-left"></i></span>
          <span>Kembali</span>
        </a>

        <div class="card border">
          <div class="card-body mt-2 mx-1">

            <!-- form input -->
            <form method="post" enctype="multipart/form-data">
              <!-- Judul -->
              <div class="form-group row">
                <label for="namaRak" class="col-sm-2 col-form-label">Nama Rak</label>
                <div class="col-sm-10">
                  <input type="text" name="nama" class="form-control" id="namaRak" value="<?= $namaRak;?>">
                </div>
              </div><br>

              <!-- Klasifikasi -->
              <div class="form-group row">
                <label for="kategori" class="col-sm-2 col-form-label">Klasifikasi</label>
                <div class="col-sm-10">
                  <select class="form-select" name="kategori" id="kategori">
                    <?php
                      if($action == "tambah"){ ?>
                        <option selected disabled>-</option>
                        <option value="000 - Umum">000 - Umum</option> 
                        <option value="100 - Filsafat dan Teknologi">100 - Filsafat dan Teknologi</option> 
                        <option value="200 - Agama">200 - Agama</option> 
                        <option value="300 - Sosial">300 - Sosial</option> 
                        <option value="400 - Bahasa">400 - Bahasa</option> 
                        <option value="500 - Sains dan Matematika">500 - Sains dan Matematika</option> 
                        <option value="600 - Teknologi dan Teknik">600 - Teknologi dan Teknik</option> 
                        <option value="700 - Seni dan Rekreasi">700 - Seni dan Rekreasi</option> 
                        <option value="800 - Literatur dan Sastra">800 - Literatur dan Sastra</option> 
                        <option value="900 - Sejarah dan Geografi">900 - Sejarah dan Geografi</option> <?php
                      } elseif($action == "edit"){ ?>
                        <option disabled selected value="<?=$data['kategori']?>"><?=$data['kategori']?></option> <?php
                      }
                    ?>
                  </select>
                  <?php if($action == "edit"){ ?>
                    <small id="kategori" class="form-text text-muted">Data klasifikasi tidak bisa diubah</small> <?php
                  } ?>
                </div>
              </div><br>

              <!-- Button -->
              <div class="form-group row">
                <label for="button" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col col-md-6 d-grid gap-2">
                      <button type="reset" name="reset" class="btn btn-danger btn-block" style="visibility: <?=$hidden?>;">
                        <span><i class="bi bi-arrow-repeat"></i></span>
                        <span>Reset</span>
                    </div>
                    <div class="col col-md-6 d-grid gap-2">
                      <button type="submit" name="tambah" class="btn btn-success btn-block">
                        <span><i class="bi <?=$icon_addUpdate?>"></i></span>
                        <span><?=$btn_addUpdate?></span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            <!-- Modal Danger -->
            <div class="modal fade" tabindex="-1" id="modalDanger" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false" >
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p><?=$setDangerText?></p>
                  </div>
                  <div class="modal-footer">
                    <a href="rak.php" class="btn btn-secondary" >OK</a>
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