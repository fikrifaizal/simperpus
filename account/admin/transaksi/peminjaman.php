<?php
include('../../../confiq.php');
include_once('../akses.php');
$admin_id = $_SESSION['id'];
$admin_name = $_SESSION['nama'];
$setDangerCondition = false;
$setDangerText = "";
$newStokBuku = 0;
$newStokRak = 0;
$nomorklasifikasi = 0;
$nomorrak = 0;

if(isset($_POST['tambah'])){
  $nomorklasifikasi = $_POST['buku'];

  $getBuku = "SELECT COUNT(nomor_klasifikasi) as buku, stok, nomor_rak FROM `buku` WHERE `nomor_klasifikasi` LIKE '$nomorklasifikasi'";
  $queryBuku = mysqli_query($conn, $getBuku);
  $data = mysqli_fetch_array($queryBuku, MYSQLI_ASSOC);
  $newStokBuku = $data['stok']-1;
  $nomorrak = $data['nomor_rak'];

  $getRak = "SELECT `jumlah_buku` FROM `rak` WHERE `nomor_rak` LIKE '$nomorrak'";
  $queryRak = mysqli_query($conn, $getRak);
  $dataRak = mysqli_fetch_array($queryRak, MYSQLI_ASSOC);
  $newStokRak = $dataRak['jumlah_buku']-1;
  
  if($data['buku'] > 0 && $data['stok'] > 0){
    $anggota = $_POST['anggota'];

    $getBuku = "SELECT COUNT(nomor_anggota) as anggota FROM `anggota` WHERE `nomor_anggota` LIKE '$anggota'";
    $queryBuku = mysqli_query($conn, $getBuku);
    $data = mysqli_fetch_array($queryBuku, MYSQLI_ASSOC);

    if($data['anggota'] > 0){
      $nomorklasifikasi = $_POST['buku'];
      $anggota = $_POST['anggota'];
      $tanggal_pinjam = date('Y-m-d');
      $durasi = $_POST['durasi'];
      $convert_tanggal = new DateTime($tanggal_pinjam);
      $tanggal_kembali = $convert_tanggal->modify("+$durasi days")->format("Y-m-d");
    
      $query = "INSERT INTO `transaksi`(`nomor_klasifikasi`, `nomor_anggota`, `nomor_petugas`, `tanggal_pinjam`, `durasi`, `tanggal_kembali`, `keterlambatan`, `selesai`)
                VALUES ('$nomorklasifikasi', '$anggota', '$admin_id', '$tanggal_pinjam', '$durasi', '$tanggal_kembali', 0, 0)";
      $tambahTransaksi = mysqli_query($conn, $query);

      $queryBuku = "UPDATE `buku` set `stok`='$newStokBuku' WHERE `nomor_klasifikasi` LIKE '$nomorklasifikasi'";
      $updateBuku = mysqli_query($conn, $queryBuku);

      $queryRak = "UPDATE `rak` set `jumlah_buku`='$newStokRak' WHERE `nomor_rak` LIKE '$nomorrak'";
      $updateRak = mysqli_query($conn, $queryRak);

      $setDangerCondition = true;
      $setDangerText = "Transaksi Peminjaman berhasil";
    } else {
      $setDangerCondition = true;
      $setDangerText = "Nomor Anggota salah";
    }
  } else {
    $setDangerCondition = true;
    $setDangerText = "Nomor Klasifikasi Buku salah atau Stok Buku habis";
  }
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Transaksi Berjalan</title>
    <style>
      .ajax-autocomplete {
        position: absolute;
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
        <h3>Tambah Peminjaman</h3>
        
        <div class="card border">
          <div class="card-body mt-2 mx-1">
            
            <!-- form -->
            <form method="post" enctype="multipart/form-data">
              <!-- Nomor Klasifikasi -->
              <div class="form-group row">
                <label for="nomorklasifikasi" class="col-sm-2 col-form-label">Nomor Klasifikasi</label>
                <div class="col-sm-10">
                  <input type="text" name="buku" class="form-control" autocomplete="off" id="nomorklasifikasi">
                  <ul class="list-group ajax-autocomplete" id="showklasifikasi">
                    <!-- autocomplete list -->
                  </ul>
                  <small id="nomorklasifikasi" class="form-text text-muted">
                    Pastikan input Nomor Klasifikasi Buku dengan benar
                  </small>
                </div>
              </div><br>
              
              <!-- Nomor Anggota -->
              <div class="form-group row">
                <label for="nomoranggota" class="col-sm-2 col-form-label">Nomor Anggota</label>
                <div class="col-sm-10">
                  <input type="text" name="anggota" class="form-control" autocomplete="off" id="nomoranggota">
                  <ul class="list-group ajax-autocomplete" id="showanggota">
                    <!-- autocomplete list -->
                  </ul>
                  <small id="nomoranggota" class="form-text text-muted">
                    Pastikan input Nomor Anggota dengan benar
                  </small>
                </div>
              </div><br>

              <!-- Durasi -->
              <div class="form-group row">
                <label for="tambahDurasi" class="col-sm-2 col-form-label">Durasi</label>
                <div class="col-sm-10">
                  <select class="form-select" name="durasi" id="tambahDurasi">
                    <option selected disabled></option>
                    <option value="3">3 Hari</option>
                    <option value="7">7 Hari</option>
                    <option value="14">14 Hari</option>
                    <option value="30">30 Hari</option>
                  </select>
                </div>
              </div><br>

              <!-- Petugas -->
              <div class="form-group row">
                <label for="autofillPetugas" class="col-sm-2 col-form-label">Petugas</label>
                <div class="col-sm-10">
                  <input type="text" name="petugas" class="form-control" id="autofillPetugas" value="<?=$admin_name?>" readonly>
                </div>
              </div><br>

              <!-- Button -->
              <div class="form-group row">
                <label for="button" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col col-md-6 d-grid gap-2">
                      <button type="reset" name="reset" class="btn btn-danger btn-block btn-sm">                        
                        <span><i class="bi bi-arrow-repeat"></i></span>
                        <span>Reset</span>
                      </button>
                    </div>
                    <div class="col col-md-6 d-grid gap-2">
                      <!-- Button Modal -->
                      <button type="button" name="cekdata" class="btn btn-success btn-block btn-sm" data-bs-toggle="modal" data-bs-target="#cekData">
                        <span><i class="bi bi-plus"></i></span>
                        <span>Tambah</span>
                      </button>
                    </div>
                  </div>
                </div>
                
                <!-- Modal Konfirmasi -->
                <div class="modal fade" id="cekData" tabindex="-1" aria-labelledby="cekDataPinjam" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Konfirmasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <p>Apakah anda sudah yakin untuk menambah transaksi peminjaman ini?</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="tambah" class="btn btn-success">
                          <span><i class="bi bi-plus"></i></span>
                          <span>Tambah</span>
                        </button>
                      </div>
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
    
    <!-- autocomplete fill -->
    <script>
      $(document).ready(function () {
        // Buku
        // Send Search Text to the server
        $("#nomorklasifikasi").keyup(function () {
          let searchText = $(this).val();
          if (searchText != "") {
            $.ajax({
              type: 'POST',
              url: 'support/search.php',
              data: { buku: searchText },
              success: function (data) {
                $("#showklasifikasi").html(data);
              },
            });
          } else {
            $("#showklasifikasi").html("");
          }
        });
        // Set searched text in input field on click of search button
        $(document).on("click", "a.ajax-klasifikasi", function () {
          $("#nomorklasifikasi").val($(this).text().split(" ",1));
          $("#showklasifikasi").html("");
        });

        // Anggota
        // Send Search Text to the server
        $("#nomoranggota").keyup(function () {
          let searchText = $(this).val();
          if (searchText != "") {
            $.ajax({
              type: 'POST',
              url: 'support/search.php',
              data: { anggota: searchText },
              success: function (data) {
                $("#showanggota").html(data);
              },
            });
          } else {
            $("#showanggota").html("");
          }
        });
        // Set searched text in input field on click of search button
        $(document).on("click", "a.ajax-anggota", function () {
          $("#nomoranggota").val($(this).text().split(" ",1));
          $("#showanggota").html("");
        });
      });
    </script>
  </body>
</html>