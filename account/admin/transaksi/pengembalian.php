<?php
include('../../../confiq.php');
include_once('../akses.php');
$setDangerCondition = false;
$setDangerText = "";

$query = "SELECT transaksi.id_peminjaman, transaksi.nomor_anggota, anggota.nama as anggota, buku.judul
          FROM `transaksi`
          LEFT JOIN `buku` ON transaksi.nomor_klasifikasi = buku.nomor_klasifikasi
          LEFT JOIN `anggota` ON transaksi.nomor_anggota = anggota.nomor_anggota
          WHERE transaksi.selesai LIKE 0";

$result = mysqli_query($conn, $query);

// from transaksi
if(isset($_POST['transaksi'])){
  $getTransaksi = $_POST['transaksi'];
  
  $getCount = "SELECT COUNT(id_peminjaman) as total FROM `transaksi` WHERE `id_peminjaman` LIKE '$getTransaksi'";
  $queryCount = mysqli_query($conn, $getCount);
  $data = mysqli_fetch_array($queryCount, MYSQLI_ASSOC);

  if($data['total'] > 0){
    header("Location: cekPengembalian.php?method=transaksi&&nomor=$getTransaksi");
  } else {
    $setDangerCondition = true;
    $setDangerText = "Nomor Transaksi salah";
  }
}
// from anggota
elseif(isset($_POST['anggota'])){
  $getAnggota = $_POST['anggota'];
  
  $getCount = "SELECT COUNT(nomor_anggota) as total FROM `anggota` WHERE `nomor_anggota` LIKE '$getAnggota'";
  $queryCount = mysqli_query($conn, $getCount);
  $data = mysqli_fetch_array($queryCount, MYSQLI_ASSOC);

  if($data['total'] > 0){
    header("Location: cekPengembalian.php?method=anggota&&nomor=$getAnggota");
  } else {
    $setDangerCondition = true;
    $setDangerText = "Nomor Anggota salah";
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
        <h3>Pengembalian Buku</h3>
        
        <!-- Card Cari -->
        <div class="card border mb-3">
          <div class="card-header text-secondary">
            <span><i class="bi bi-search me-2"></i></span>
            <span>Cari Transaksi</span>
          </div>

          <div class="card-body mt-2 mx-1">
          
            <!-- nav-tabs button -->
            <ul class="nav nav-tabs nav-justified" id="pills-tab" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-transaksi" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Nomor Transaksi</button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-anggota" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Nomor Anggota</button>
              </li>
            </ul>

            <!-- nav-tabs content -->
            <div class="tab-content" id="pills-tabContent">

              <!-- Berdasarkan Nomor Transaksi -->
              <div class="tab-pane fade show active" id="pills-transaksi" role="tabpanel" aria-labelledby="pills-transaksi-tab">
                <!-- form -->
                <form method="post" enctype="multipart/form-data">
                  <!-- Nomor Transaksi -->
                  <div class="form-group row">
                    <div class="col-sm-12">
                      <input type="text" name="transaksi" class="form-control" autocomplete="off" id="nomortransaksi" placeholder="ketik nomor transaksi..." required>
                      <ul class="list-group ajax-autocomplete" id="showtransaksi">
                        <!-- autocomplete list -->
                      </ul>
                      <small id="nomortransaksi" class="form-text text-muted text-center">
                        Pastikan input Nomor Transaksi dengan benar
                      </small>
                    </div>
                  </div><br>

                  <!-- Button -->
                  <div class="form-group row">
                    <div class="col-sm-12">
                      <div class="row">
                        <div class="col col-md-6 d-grid gap-2">
                          <button type="reset" name="reset" class="btn btn-danger btn-block btn-sm">                        
                            <span><i class="bi bi-arrow-repeat"></i></span>
                            <span>Reset</span>
                          </button>
                        </div>
                        <div class="col col-md-6 d-grid gap-2">
                          <button type="submit" name="caritransaksi" class="btn btn-success btn-block btn-sm">
                            <span><i class="bi bi-search"></i></span>
                            <span>Cek</span>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>

              <!-- Berdasarkan Nomor Anggota -->
              <div class="tab-pane fade" id="pills-anggota" role="tabpanel" aria-labelledby="pills-anggota-tab">
                <!-- form -->
                <form method="post" enctype="multipart/form-data">
                  <!-- Nomor Anggota -->
                  <div class="form-group row">
                    <div class="col-sm-12">
                      <input type="text" name="anggota" class="form-control" autocomplete="off" id="nomoranggota2" placeholder="ketik nomor anggota..." required>
                      <ul class="list-group ajax-autocomplete" id="showanggota2">
                        <!-- autocomplete list -->
                      </ul>
                      <small id="nomoranggota2" class="form-text text-muted">
                        Pastikan input Nomor Anggota dengan benar
                      </small>
                    </div>
                  </div><br>

                  <!-- Button -->
                  <div class="form-group row">
                    <div class="col-sm-12">
                      <div class="row">
                        <div class="col col-md-6 d-grid gap-2">
                          <button type="reset" name="reset" class="btn btn-danger btn-block btn-sm">                        
                            <span><i class="bi bi-arrow-repeat"></i></span>
                            <span>Reset</span>
                          </button>
                        </div>
                        <div class="col col-md-6 d-grid gap-2">
                          <button type="submit" name="carianggota" class="btn btn-success btn-block btn-sm">
                            <span><i class="bi bi-search"></i></span>
                            <span>Cek</span>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        
        <hr class="my-4 dropdown-divider">
        
        <!-- Card Tabel -->
        <div class="card border">
          <div class="card-header text-secondary">
            <span><i class="bi bi-table me-2"></i></span>
            <span>Daftar Peminjaman Berjalan</span>
          </div>

          <div class="card-body mt-2 mx-1">
            <!-- table -->
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                  <tr>
                    <th>Nomor Transaksi</th>
                    <th>Nomor Anggota</th>
                    <th>Nama Peminjam</th>
                    <th>Judul Buku</th>
                    <th width="10%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    // fetch data menjadi array asosisasi
                    while($data = mysqli_fetch_array($result, MYSQLI_ASSOC)){                                           
                      // menampilkan data
                      echo "<tr><td name='id'>".$data['id_peminjaman']."</td>";
                      echo "<td>".$data['nomor_anggota']."</td>";
                      echo "<td>".$data['anggota']."</td>";
                      echo "<td>".$data['judul']."</td>";
                      echo "<td class='text-center'>
                            <a href='cekPengembalian.php?method=transaksi&&nomor=".$data['id_peminjaman']."' class='btn btn-primary btn-sm d-grid' data-toggle='tooltip'>
                              <span class='me-2'><i class='bi bi-hand-index'></i><span>
                              <span>Pilih</span>
                            </a></td></tr>";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">                  
                  <span><i class="bi bi-check"></i></span>
                  <span>Ok</span>
                </button>
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
        // Transaksi
        // Send Search Text to the server
        $("#nomortransaksi").keyup(function () {
          let searchText = $(this).val();
          if (searchText != "") {
            $.ajax({
              type: 'POST',
              url: 'support/search.php',
              data: { transaksi: searchText },
              success: function (data) {
                $("#showtransaksi").html(data);
              },
            });
          } else {
            $("#showtransaksi").html("");
          }
        });
        // Set searched text in input field on click of search button
        $(document).on("click", "a.ajax-transaksi", function () {
          $("#nomortransaksi").val($(this).text().split(" ",1));
          $("#showtransaksi").html("");
        });

        // Anggota 2
        // Send Search Text to the server
        $("#nomoranggota2").keyup(function () {
          let searchText = $(this).val();
          if (searchText != "") {
            $.ajax({
              type: 'POST',
              url: 'support/search.php',
              data: { anggota2: searchText },
              success: function (data) {
                $("#showanggota2").html(data);
              },
            });
          } else {
            $("#showanggota2").html("");
          }
        });
        // Set searched text in input field on click of search button
        $(document).on("click", "a.ajax-anggota2", function () {
          $("#nomoranggota2").val($(this).text().split(" ",1));
          $("#showanggota2").html("");
        });
      });
    </script>
  </body>
</html>