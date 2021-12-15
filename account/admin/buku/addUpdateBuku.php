<?php
include('../../../confiq.php');
include_once('../akses.php');

$nomorklasifikasi = "";
$isbn = "";
$judul = "";
$penulis = "";
$penerbit = "";
$tahunterbit = "";
$stok = "";
$readonly = "";
$disabled = "";
$hidden = "";
$label_isbn = "";
$btn_addUpdate = "";
$icon_addUpdate = "";
$openAlert = false;
$setDangerCondition = false;
$setDangerText = "";

$query = "SELECT * FROM `rak`";
$result = mysqli_query($conn, $query);

$action = $_GET['action'];

// tambah data
if($action == "tambah"){
  $label_isbn = "Tekan Cek ISBN untuk mengisi data secara otomatis jika terdaftar di openlibrary.org";
  $btn_addUpdate = "Tambah";
  $icon_addUpdate = "bi-plus";

  // cek isbn
  if(isset($_POST['cekIsbn'])){
    $isbn = $_POST['isbn'];
    
    $dataAPI = file_get_contents("https://openlibrary.org/search.json?isbn=$isbn");
    $json = json_decode($dataAPI, TRUE);
    $found = $json['numFound'];

    if($found >= 1){
      $readonly = "readonly";
      $disabled = "disabled";
      
      $judul = $json['docs'][0]['title'];
      $penulis = $json['docs'][0]['author_name'][0];
      $penerbit = $json['docs'][0]['publisher'][0];
      $tahunterbit = $json['docs'][0]['publish_year'][0];
    } else {
      $openAlert = true;
    }
  }
  // tambah ke database
  elseif(isset($_POST['tambah'])){
    $getIsbn = $_POST['isbn'];
    $getJudul = ucwords($_POST['judul']);
    $getPenulis = ucwords($_POST['penulis']);
    $getPenerbit = ucwords($_POST['penerbit']);
    $getTahunterbit = $_POST['tahun'];
    $stok = $_POST['stok'];
    $rak = $_POST['rak'];
    
    $nomorklasifikasi = substr($rak,0,3).strtoupper(substr($getPenulis,0,3)).strtolower(substr($getJudul,0,1));

    $insert = "INSERT INTO `buku`(`nomor_klasifikasi`, `isbn`, `nomor_rak`, `judul`, `penulis`, `penerbit`, `tahun_terbit`, `stok`)
                VALUES ('$nomorklasifikasi', '$getIsbn', '$rak', '$getJudul', '$getPenulis', '$getPenerbit', '$getTahunterbit', '$stok')";
    $tambahBuku = mysqli_query($conn, $insert);
    
    $setDangerCondition = true;
    $setDangerText = "Data buku berhasil di upload ke database";
  }
}

// edit data
elseif($action == "edit"){
  $nomorklasifikasi = $_GET['nomor'];
  
  $readonly = "readonly";
  $disabled = "disabled";
  $hidden = "hidden";
  $label_isbn = "ISBN tidak bisa diubah";
  $btn_addUpdate = "Ubah";
  $icon_addUpdate = "bi-pencil";

  $getBuku = "SELECT * FROM `buku` WHERE `nomor_klasifikasi` LIKE '$nomorklasifikasi'";
  $queryBuku = mysqli_query($conn, $getBuku);
  $data = mysqli_fetch_array($queryBuku, MYSQLI_ASSOC);

  $nomorklasifikasi = $data['nomor_klasifikasi'];
  $isbn = $data['isbn'];
  $judul = $data['judul'];
  $penulis = $data['penulis'];
  $penerbit = $data['penerbit'];
  $tahunterbit = $data['tahun_terbit'];
  $stok = $data['stok'];
  $rak = $data['nomor_rak'];

  // ubah data di database
  if(isset($_POST['tambah'])){
    $getJudul = ucwords($_POST['judul']);
    $getPenulis = ucwords($_POST['penulis']);
    $getPenerbit = ucwords($_POST['penerbit']);
    $getTahunterbit = $_POST['tahun'];
    $stok = $_POST['stok'];
    $rak = $_POST['rak'];
    
    $nomorklasifikasi = substr($rak,0,3).strtoupper(substr($getPenulis,0,3)).strtolower(substr($judul,0,1));

    $update = "UPDATE `buku` set 
              `nomor_klasifikasi`='$nomorklasifikasi', `nomor_rak`='$rak', `judul`='$getJudul',
              `penulis`='$getPenulis', `penerbit`='$getPenerbit',
              `tahun_terbit`='$getTahunterbit', `stok`='$stok'
              WHERE `isbn` LIKE '$isbn'";
    $updateBuku = mysqli_query($conn, $update);

    $setDangerCondition = true;
    $setDangerText = "Data buku berhasil diubah";
  }
}

// delete data
elseif($action == "delete"){
  $nomorklasifikasi = $_GET['nomor'];

  $getPeminjaman = "SELECT COUNT(`id_peminjaman`) as total FROM `transaksi` WHERE `nomor_klasifikasi` LIKE '$nomorklasifikasi'";
  $queryPeminjaman = mysqli_query($conn, $getPeminjaman);
  $data = mysqli_fetch_array($queryPeminjaman, MYSQLI_ASSOC);

  if($data['total'] >= 1){
    header("Location: buku.php");
  } else {
    $delete = "DELETE FROM `buku` WHERE `nomor_klasifikasi` LIKE '$nomorklasifikasi'";
    $deleteBuku = mysqli_query($conn, $delete);

    header("Location: buku.php");
  }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Tambah Buku</title>
  </head>

  <body>
    <!-- sidebar & navbar -->
    <?php
      include('../layout/sidebar.php');
    ?>

    <!-- konten -->
    <main>
      <div class="container-fluid content transition">
        <h3>Tambah Buku Baru</h3>
        <a href="buku.php" class="btn btn-success btn-sm mb-3">
          <span><i class="bi bi-chevron-left"></i></span>
          <span>Kembali</span>
        </a>

        <div class="card border">
          <div class="card-body mt-2 mx-1">

            <!-- form input -->
            <form method="post" enctype="multipart/form-data">
              <?php if($action == "edit"){ ?>
                <!-- Nomor Klasifikasi -->
                <div class="form-group row">
                  <label for="nomorklasifikasi" class="col-sm-2 col-form-label">Nomor Klasifikasi</label>
                  <div class="col-sm-10">
                    <input type="text" name="nomorklasifikasi" class="form-control" id="nomorbuku" value="<?= $nomorklasifikasi?>" <?= $readonly?>>
                    <small id="isbn" class="form-text text-muted">
                      Nomor tidak bisa diubah
                    </small>
                  </div>
                </div><br>
              <?php } ?>
              
              <!-- ISBN -->
              <div class="form-group row">
                <label for="isbn" class="col-sm-2 col-form-label">ISBN</label>
                <div class="col-sm-10">
                  <div class="input-group">
                    <input type="text" name="isbn" class="form-control" id="isbn" required value="<?= $isbn?>" <?= $readonly?>>
                    <div class="input-group-prepend">
                      <button class="btn btn-outline-secondary input-group-text" type="submit" name="cekIsbn" id="isbn" <?=$disabled?>>Cek ISBN</button>
                    </div>
                  </div>
                  <small id="isbn" class="form-text text-muted">
                    <?php if($openAlert){ ?>
                      <span class="text-danger">Data tidak terdaftar di openlibrary.org</span>
                    <?php } else {
                      echo $label_isbn;
                    } ?>
                  </small>
                </div>
              </div><br>

              <!-- Judul -->
              <div class="form-group row">
                <label for="judulBuku" class="col-sm-2 col-form-label">Judul Buku</label>
                <div class="col-sm-10">
                  <input type="text" name="judul" class="form-control" id="judulBuku" value="<?= $judul;?>">
                </div>
              </div><br>

              <!-- Penulis -->
              <div class="form-group row">
                <label for="penulisBuku" class="col-sm-2 col-form-label">Penulis</label>
                <div class="col-sm-10">
                  <input type="text" name="penulis" class="form-control" id="penulisBuku" value="<?= $penulis;?>">
                </div>
              </div><br>

              <!-- Penerbit -->
              <div class="form-group row">
                <label for="penerbitBuku" class="col-sm-2 col-form-label">Penerbit</label>
                <div class="col-sm-10">
                  <input type="text" name="penerbit" class="form-control" id="penerbitBuku" value="<?= $penerbit;?>">
                </div>
              </div><br>

              <!-- Tahun Terbit -->
              <div class="form-group row">
                <label for="tahunTerbit" class="col-sm-2 col-form-label">Tahun Terbit</label>
                <div class="col-sm-10">
                  <input type="text" name="tahun" class="form-control" id="tahunTerbit" value="<?= $tahunterbit;?>">
                </div>
              </div><br>

              <!-- Stok Buku -->
              <div class="form-group row">
                <label for="stokBuku" class="col-sm-2 col-form-label">Banyak Buku</label>
                <div class="col-sm-10">
                  <input type="number" name="stok" class="form-control" id="stokBuku" value="<?= $stok;?>">
                </div>
              </div><br>

              <!-- Rak -->
              <div class="form-group row">
                <label for="rakBuku" class="col-sm-2 col-form-label">Penempatan Rak</label>
                <div class="col-sm-10">
                  <select class="form-select" name="rak" id="rakBuku">
                    <?php
                      if($action == "tambah"){ ?>
                        <option selected disabled>-</option> <?php
                        while($data = mysqli_fetch_array($result, MYSQLI_ASSOC)){ ?>
                          <option value="<?=$data['nomor_rak']?>"><?=$data['nomor_rak']?> - <?=$data['nama_rak']?></option> <?php
                        }
                      } elseif($action == "edit"){ ?>
                        <option disabled>-</option> <?php
                        while($data = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                          if($data['nomor_rak'] == $rak){ ?>
                            <option selected value="<?=$data['nomor_rak']?>"><?=$data['nomor_rak']?> - <?=$data['nama_rak']?></option> <?php
                          } else { ?>
                            <option value="<?=$data['nomor_rak']?>"><?=$data['nomor_rak']?> - <?=$data['nama_rak']?></option> <?php
                          }
                        }
                      }
                    ?>
                  </select>
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
                    <a href="buku.php" class="btn btn-secondary" >OK</a>
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