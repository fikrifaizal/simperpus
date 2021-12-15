<?php
include('../../../confiq.php');
include_once('../akses.php');

$nisn = "";
$nama = "";
$angkatan = "";
$keterangan = "";
$hidden = "";
$btn_addUpdate = "";
$icon_addUpdate = "";

$action = $_GET['action'];

// tambah data
if($action == "tambah"){
  $label_isbn = "Tekan Cek ISBN untuk mengisi data secara otomatis jika terdaftar di openlibrary.org";
  $btn_addUpdate = "Tambah";
  $icon_addUpdate = "bi-plus";

  // tambah ke database
  if(isset($_POST['tambah'])){
    if(!empty($_FILES['excel']['name'])){
      $ekstensi_diperbolehkan	= "xlsx";
      $namaFile	= $_FILES['excel']['name'];
      $ekstensiFile = explode(".", $namaFile);
      $ekstensiFile = strtolower(end($ekstensiFile));
  
      if($ekstensiFile == $ekstensi_diperbolehkan){
        include('../../../vendor/shuchkin/simplexlsx/src/SimpleXLSX.php');
  
        $excel = SimpleXLSX::parse($_FILES['excel']['tmp_name']);
        
        // 0 adalah colums dan 1 adalah rows
        $rowcol = $excel->dimension(0);
        if($rowcol[0] != 1 && $rowcol[1] != 1){
          foreach($excel->rows(0) as $key => $row){
            $q="";
            foreach ($row as $key => $cell) {
              // get data from excel
              if(str_contains($cell, "ubahyah")){
                $passwd = md5($cell);
                $q.="'".$passwd. "',";
              } else {
                $q.="'".$cell. "',";
              }
            }
            // insert into database
            $query = "INSERT INTO ".$excel->sheetName(0)." VALUES (".rtrim($q,",").")";
            $insert = mysqli_query($conn, $query);
          }
        }
      }
    }
    else {
      $nisn = $_POST['nisn'];
      $nama = $_POST['nama'];
      $angkatan = $_POST['angkatan'];
      $keterangan = $_POST['keterangan'];
      $getnumber = substr($angkatan,-1);
      $passwd = md5("ubahyah".$getnumber);

      $insert = "INSERT INTO `anggota`(`nomor_anggota`, `nama`, `jabatan`, `angkatan`, `password`)
                  VALUES ('$nisn', '$nama', '$keterangan', '$angkatan', '$passwd')";
      $tambahAnggota = mysqli_query($conn, $insert);
    }
    header("Location: anggota.php");
  }  
}

// edit data
elseif($action == "edit"){
  $nomoranggota = $_GET['nomor'];
  
  $hidden = "hidden";
  $btn_addUpdate = "Ubah";
  $icon_addUpdate = "bi-pencil";

  $getAnggota = "SELECT `nomor_anggota`, `nama`, `jabatan`, `angkatan` FROM `anggota` WHERE `nomor_anggota` LIKE '$nomoranggota'";
  $queryAnggota = mysqli_query($conn, $getAnggota);
  $data = mysqli_fetch_array($queryAnggota, MYSQLI_ASSOC);

  $nisn = $data['nomor_anggota'];
  $nama = $data['nama'];
  $angkatan = $data['angkatan'];
  $keterangan = $data['jabatan'];

  if(isset($_POST['tambah'])){
    $nisn = $_POST['nisn'];
    $nama = $_POST['nama'];
    $angkatan = $_POST['angkatan'];
    $keterangan = $_POST['keterangan'];
    
    $nomorklasifikasi = substr($rak,0,3).strtoupper(substr($getPenulis,0,3)).strtolower(substr($judul,0,1));

    $update = "UPDATE `anggota` set 
              `nomor_anggota`='$nisn', `nama`='$nama', `jabatan`='$keterangan', `angkatan`='$angkatan'
              WHERE `nomor_anggota` LIKE '$nisn'";
    $updateBuku = mysqli_query($conn, $update);

    header("Location: anggota.php");
  }
}

// delete data
elseif($action == "delete"){
  $nomoranggota = $_GET['nomor'];

  $getPeminjaman = "SELECT COUNT(`id_peminjaman`) as total FROM `transaksi` WHERE `nomor_anggota` LIKE '$nomoranggota'";
  $queryPeminjaman = mysqli_query($conn, $getPeminjaman);
  $data = mysqli_fetch_array($queryPeminjaman, MYSQLI_ASSOC);

  if($data['total'] >= 1){
    header("Location: anggota.php");
  } else {
    $delete = "DELETE FROM `anggota` WHERE `nomor_anggota` LIKE '$nomoranggota'";
    $deleteBuku = mysqli_query($conn, $delete);

    header("Location: anggota.php");
  }
}

// check function/method of str_contains
if (!function_exists('str_contains')) {
  function str_contains(string $haystack, string $needle): bool {
      return '' === $needle || false !== strpos($haystack, $needle);
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
        <h3>Tambah Anggota Baru</h3>
        <a href="anggota.php" class="btn btn-success btn-sm mb-3">
          <span><i class="bi bi-chevron-left"></i></span>
          <span>Kembali</span>
        </a>

        <div class="card border">
          <div class="card-body mt-2 mx-1">

            <!-- form input -->
            <form method="post" enctype="multipart/form-data">
              <?php 
                if($action == "tambah"){ ?>
                  <div class="form-group row">
                    <label for="label" class="col-sm-12 mb-2 col-form-label fw-bold text-center">Tambah data satuan*</label>
                  </div> <?php
                }
              ?>

              <!-- NISN -->
              <div class="form-group row">
                <label for="nisn" class="col-sm-2 col-form-label">NISN/NIP</label>
                <div class="col-sm-10">
                  <input type="text" name="nisn" class="form-control" id="nisn" value="<?= $nisn?>">
                </div>
              </div><br>

              <!-- Nama -->
              <div class="form-group row">
                <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                <div class="col-sm-10">
                  <input type="text" name="nama" class="form-control" id="nama" value="<?= $nama?>">
                </div>
              </div><br>

              <!-- Angkatan -->
              <div class="form-group row">
                <label for="angkatan" class="col-sm-2 col-form-label">Angkatan</label>
                <div class="col-sm-10">
                  <input type="text" name="angkatan" class="form-control" id="angkatan" value="<?= $angkatan?>">
                  <small id="isbn" class="form-text text-muted">
                    Format: yyyy, Isi angka 0 jika anggota baru adalah guru
                  </small>
                </div>
              </div><br>

              <!-- Keterangan -->
              <div class="form-group row">
                <label for="keterangan" class="col-sm-2 col-form-label">Keterangan</label>
                <div class="col-sm-10">
                  <?php 
                  $value = array("siswa", "guru");
                  if($action == "tambah"){
                    $count = 1;
                    foreach($value as $checkvalue){
                      echo '<div class="form-check form-check-inline">
                              <input class="form-check-input" type="radio" name="keterangan" id="keterangan'.$count.'" value="'.$checkvalue.'">
                              <label class="form-check-label" for="keterangan'.$count.'">'.ucfirst($checkvalue).'</label>
                            </div>';
                      $count++;
                    }
                  } elseif($action == "edit"){
                    $count = 1;
                    foreach($value as $checkvalue){
                      if($checkvalue == $keterangan){
                        echo '<div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="keterangan" id="keterangan'.$count.'" value="'.$checkvalue.'" checked>
                                <label class="form-check-label" for="keterangan'.$count.'">'.ucfirst($checkvalue).'</label>
                              </div>';
                      } else {
                        echo '<div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="keterangan" id="keterangan'.$count.'" value="'.$checkvalue.'">
                                <label class="form-check-label" for="keterangan'.$count.'">'.ucfirst($checkvalue).'</label>
                              </div>';                        
                      }
                      $count++;
                    }
                  }
                  ?>
                </div>

              <!-- Upload File -->
              <?php 
                if($action == "tambah"){ ?>
                  <hr class="mb-1 mt-2">
                  <div class="form-group row">
                    <label for="label" class="col-sm-12 mb-3 col-form-label fw-bold text-center">Tambah banyak data sekaligus*</label>
                  </div>
                  </div>
                  <div class="form-group row">
                    <label for="fileExcel" class="col-sm-2 col-form-label">File Excel</label>
                    <div class="col-sm-10">
                      <input type="file" name="excel" class="form-control" id="fileExcel" value="">
                      <small id="fileExcel" class="form-text text-muted">
                        File yang didukung: xlsx
                      </small>
                    </div>
                  </div> <?php
                }
              ?>

              <!-- Button -->
              <div class="form-group row mt-2">
                <label for="button" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col col-md-6 d-grid gap-2">
                      <button type="reset" name="reset" class="btn btn-danger btn-block" style="visibility: <?=$hidden?>;">
                        <span><i class="bi bi-arrow-repeat"></i></span>
                        <span>Reset</span>
                      </button>
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
              <?php 
                if($action == "tambah"){ ?>
                  <div class="form-group row">
                    <label for="label" class="col-sm-5 mt-1 col-form-label">*) pilih salah satu</label>
                  </div> <?php
                }
              ?>
            </form>
          </div>
        </div>
      </div>
    </main>
  </body>
</html>