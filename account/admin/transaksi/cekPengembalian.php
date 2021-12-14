<?php
include('../../../confiq.php');
include_once('../akses.php');
$setDangerCondition = false;
$setDangerText = "";

$nomortransaksi = "";
$nomorpeminjam = "";
$namapeminjam = "";
$nomorbuku = "";
$judulbuku = "";
$tanggalpinjam = "";
$tanggalkembali = "";
$durasi = 0;
$keterlambatan = 0;
$denda = 0;
$hidden = "";

$action = $_GET['method'];
// berdasarkan nomor transaksi
if($action == "transaksi"){
  $getNomorTransaksi = $_GET['nomor'];

  $query = "SELECT transaksi.*, anggota.nama as anggota, buku.judul, petugas.nama as petugas, anggota.jabatan
            FROM `transaksi`
            LEFT JOIN `buku` ON transaksi.nomor_klasifikasi = buku.nomor_klasifikasi
            LEFT JOIN `petugas` ON transaksi.nomor_petugas = petugas.nomor_petugas
            LEFT JOIN `anggota` ON transaksi.nomor_anggota = anggota.nomor_anggota
            WHERE transaksi.selesai LIKE 0 && transaksi.id_peminjaman LIKE '%$getNomorTransaksi%'";

  $result = mysqli_query($conn, $query);
  $data = mysqli_fetch_array($result, MYSQLI_ASSOC);

  // convert tanggal sekarang
  $today = strtotime(date('Y-m-d'));
  $convert = strtotime($data['tanggal_kembali']);

  // hitung keterlambatan
  $keterlambatan = $today-$convert;
  if($keterlambatan <= 0) {
    $keterlambatan = 0;
  } else {
    $keterlambatan = $keterlambatan/60/60/24;
    $hidden = "btn-hidden";
  }
  $denda = $keterlambatan*1000;

  $nomortransaksi = $data['id_peminjaman'];
  $nomorpeminjam = $data['nomor_anggota'];
  $namapeminjam = $data['anggota'];
  $nomorbuku = $data['nomor_klasifikasi'];
  $judulbuku = $data['judul'];
  $tanggalpinjam = $data['tanggal_pinjam'];
  $tanggalkembali = $data['tanggal_kembali'];
  $durasi = $data['durasi'];

  // selesai
  if(isset($_POST['selesai'])){
    $nomortransaksi = $_POST['nomortransaksi'];
    $tanggalkembali = date('Y-m-d');
    
    $keterlambatan = $_POST['keterlambatan'];

    $update = "UPDATE `transaksi` set
              `tanggal_kembali`='$tanggalkembali', `keterlambatan`='$keterlambatan', `selesai`=1
              WHERE `id_peminjaman` LIKE '$nomortransaksi'";
    $updateBuku = mysqli_query($conn, $update);

    header("Location: pengembalian.php");
  }
  // perpanjang
  elseif(isset($_POST['perpanjang'])){
    $nomortransaksi = $_POST['nomortransaksi'];
    $perpanjangan = $_POST['durasiperpanjangan'];
    $durasi = $durasi + $perpanjangan;

    $convert_tanggal = new DateTime($tanggalkembali);
    $tanggal_kembali = $convert_tanggal->modify("+$perpanjangan days")->format("Y-m-d");

    $update = "UPDATE `transaksi` set
              `tanggal_kembali`='$tanggal_kembali', `durasi`='$durasi'
              WHERE `id_peminjaman` LIKE '$nomortransaksi'";
    $updateBuku = mysqli_query($conn, $update);

    header("Location: pengembalian.php");
  }
}

// berdasarkan nomor anggota
elseif($action == "anggota"){
  $getNomorTransaksi = $_GET['nomor'];

  $query = "SELECT transaksi.*, anggota.nama as anggota, buku.judul, petugas.nama as petugas, anggota.jabatan
            FROM `transaksi`
            LEFT JOIN `buku` ON transaksi.nomor_klasifikasi = buku.nomor_klasifikasi
            LEFT JOIN `petugas` ON transaksi.nomor_petugas = petugas.nomor_petugas
            LEFT JOIN `anggota` ON transaksi.nomor_anggota = anggota.nomor_anggota
            WHERE transaksi.selesai LIKE 0 && transaksi.nomor_anggota LIKE '%$getNomorTransaksi%'";

  $result = mysqli_query($conn, $query);
  $data = mysqli_fetch_array($result, MYSQLI_ASSOC);

  // convert tanggal sekarang
  $today = strtotime(date('Y-m-d'));
  $convert = strtotime($data['tanggal_kembali']);

  // hitung keterlambatan
  $keterlambatan = $today-$convert;
  if($keterlambatan <= 0) {
    $keterlambatan = 0;
  } else {
    $keterlambatan = $keterlambatan/60/60/24;
    $hidden = "btn-hidden";
  }
  $denda = $keterlambatan*1000;

  $nomortransaksi = $data['id_peminjaman'];
  $nomorpeminjam = $data['nomor_anggota'];
  $namapeminjam = $data['anggota'];
  $nomorbuku = $data['nomor_klasifikasi'];
  $judulbuku = $data['judul'];
  $tanggalpinjam = $data['tanggal_pinjam'];
  $tanggalkembali = $data['tanggal_kembali'];
  $durasi = $data['durasi'];

  // selesai
  if(isset($_POST['selesai'])){
    $nomortransaksi = $_POST['nomortransaksi'];
    $tanggalkembali = date('Y-m-d');
    
    $keterlambatan = $_POST['keterlambatan'];

    $update = "UPDATE `transaksi` set
              `tanggal_kembali`='$tanggalkembali', `keterlambatan`='$keterlambatan', `selesai`=1
              WHERE `id_peminjaman` LIKE '$nomortransaksi'";
    $updateBuku = mysqli_query($conn, $update);

    header("Location: pengembalian.php");
  }
  // perpanjang
  elseif(isset($_POST['perpanjang'])){
    $nomortransaksi = $_POST['nomortransaksi'];
    $perpanjangan = $_POST['durasiperpanjangan'];
    $durasi = $durasi + $perpanjangan;

    $convert_tanggal = new DateTime($tanggalkembali);
    $tanggal_kembali = $convert_tanggal->modify("+$perpanjangan days")->format("Y-m-d");

    $update = "UPDATE `transaksi` set
              `tanggal_kembali`='$tanggal_kembali', `durasi`='$durasi'
              WHERE `id_peminjaman` LIKE '$nomortransaksi'";
    $updateBuku = mysqli_query($conn, $update);

    header("Location: pengembalian.php");
  }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Transaksi Berjalan</title>
    <style>
      .btn-hidden {
        visibility: hidden;
      }
    </style>
  </head>

  <body>
    <!-- sidebar & navbar -->
    <?php
      include('../layout/sidebar.php');
    ?>
    
    <!-- konten -->
    <main>
      <div class="container-fluid content transition">
        <h3>Pengembalian Buku</h3>
        <a href="pengembalian.php" class="btn btn-success btn-sm mb-3">
          <span><i class="bi bi-chevron-left"></i></span>
          <span>Kembali</span>
        </a>
        
        <!-- Card Cel -->
        <div class="card border mb-3">
          <div class="card-header">
            <span><i class="bi bi-search me-2"></i></span>
            <span>Cek Data Pengembalian</span>
          </div>

          <div class="card-body mt-2 mx-1">
            <form method="post" enctype="multipart/form-data">              
              
              <!-- Nomor Transaksi -->
              <div class="form-group row">
                <label for="nomorTransaksi" class="col-sm-3 col-form-label">Nomor Transaksi</label>
                <div class="col-sm-9">
                  <input type="text" name="nomortransaksi" class="form-control" id="nomorTransaksi" value="<?= $nomortransaksi;?>" readonly>
                </div>
              </div><br>
              
              <!-- Peminjam -->
              <div class="form-group row">
                <label for="peminjam" class="col-sm-3 col-form-label">Peminjam</label>
                <div class="col-sm-9">
                  <div class="row">
                    <div class="col-sm-6">
                      <input type="text" name="nomorpeminjam" class="form-control" id="peminjam" value="<?= $nomorpeminjam;?>" readonly>
                    </div>
                    <div class="col-sm-6">
                      <input type="text" name="namapeminjam" class="form-control" id="peminjam" value="<?= $namapeminjam;?>" readonly>
                    </div>
                  </div>
                </div>
              </div><br>
              
              <!-- Buku -->
              <div class="form-group row">
                <label for="buku" class="col-sm-3 col-form-label">Buku</label>
                <div class="col-sm-9">
                  <div class="row">
                    <div class="col-sm-6">
                      <input type="text" name="nomorbuku" class="form-control" id="buku" value="<?= $nomorbuku;?>" readonly>
                    </div>
                    <div class="col-sm-6">
                      <input type="text" name="judulbuku" class="form-control" id="buku" value="<?= $judulbuku;?>" readonly>
                    </div>
                  </div>
                </div>
              </div><br>
              
              <!-- Tanggal Peminjaman -->
              <div class="form-group row">
                <label for="tanggal" class="col-sm-3 col-form-label">Tanggal Peminjaman</label>
                <div class="col-sm-9">
                  <input type="text" name="tanggalpinjam" class="form-control" id="tanggal" value="<?= $tanggalpinjam;?>" readonly>
                </div>
              </div><br>

              <!-- Durasi -->
              <div class="form-group row">
                <label for="durasi" class="col-sm-3 col-form-label">Durasi Peminjaman</label>
                <div class="col-sm-9">
                  <input type="text" name="durasi" class="form-control" id="durasi" value="<?= $durasi;?> Hari" readonly>
                </div>
              </div><br>
              
              <!-- Batas Pengembalian -->
              <div class="form-group row">
                <label for="tanggal" class="col-sm-3 col-form-label">Batas Pengembalian</label>
                <div class="col-sm-9">
                  <input type="text" name="tanggalkembali" class="form-control" id="tanggal" value="<?= $tanggalkembali;?>" readonly>
                </div>
              </div><br>

              <!-- Keterlambatan -->
              <div class="form-group row">
                <label for="keterlambatan" class="col-sm-3 col-form-label">Keterlambatan</label>
                <div class="col-sm-9">
                  <input type="text" name="keterlambatan" class="form-control" id="keterlambatan" value="<?= $keterlambatan;?> Hari" readonly>
                </div>
              </div><br>

              <!-- Denda -->
              <div class="form-group row">
                <label for="denda" class="col-sm-3 col-form-label">Denda</label>
                <div class="col-sm-9">
                  <input type="text" name="denda" class="form-control" id="denda" value="Rp<?= $denda;?>" readonly>
                </div>
              </div><br>

              <!-- Button -->
              <div class="form-group row">
                <label for="button" class="col-sm-3 col-form-label"></label>
                <div class="col-sm-9">
                  <div class="row">
                    <div class="col col-md-6 d-grid gap-2">
                      <button type="button" name="perpanjang" class="btn btn-warning btn-block btn-sm <?= $hidden;?>" data-bs-toggle="modal" data-bs-target="#perpanjang">
                        <span><i class="bi bi-plus"></i></span>
                        <span>Perpanjang</span>
                      </button>
                    </div>
                    <div class="col col-md-6 d-grid gap-2">
                      <button type="button" name="konfirmasi" class="btn btn-success btn-block btn-sm" data-bs-toggle="modal" data-bs-target="#konfirmasi">
                        <span><i class="bi bi-check"></i></span>
                        <span>Selesaikan Peminjaman</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Modal Konfirmasi Selesai -->
              <div class="modal fade" id="konfirmasi" tabindex="-1" aria-labelledby="konfirmasiData" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalLabel">Konfirmasi</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <p>Apakah anda sudah yakin untuk menyelesaikan transaksi peminjaman ini?</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" name="selesai" class="btn btn-success">
                        <span><i class="bi bi-check"></i></span>
                        <span>Selesaikan Peminjaman</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Modal Input Durasi Perpanjangan -->
              <div class="modal fade" id="perpanjang" tabindex="-1" aria-labelledby="perpanjangPinjaman" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalLabel">Konfirmasi</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <p>Masukan durasi perpanjangan peminjaman buku</p>

                      <div class="form-group row">
                        <label for="tambahDurasi" class="col-sm-2 col-form-label">Durasi</label>
                        <div class="col-sm-10">
                          <select class="form-select" name="durasiperpanjangan" id="tambahDurasi" required>
                            <option value="3">3 Hari</option>
                            <option value="7">7 Hari</option>
                            <option value="14">14 Hari</option>
                          </select>
                        </div>
                      </div><br>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                      <button type="button" name="perpanjang" class="btn btn-warning btn-block" data-bs-toggle="modal" data-bs-target="#konfirmasidurasi">
                        <span><i class="bi bi-plus"></i></span>
                        <span>Perpanjang</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Modal Konfirmasi Perpanjang -->
              <div class="modal fade" id="konfirmasidurasi" aria-hidden="true" aria-labelledby="konfirmasiDurasi" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalToggleLabel2">Konfirmasi</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <p>Apakah anda sudah yakin untuk memperpanjang durasi transaksi peminjaman ini?</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-target="#perpanjang" data-bs-toggle="modal">Kembali</button>
                      <button type="submit" name="perpanjang" class="btn btn-warning btn-block">
                        <span><i class="bi bi-plus"></i></span>
                        <span>Perpanjang</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
  </body>
</html>