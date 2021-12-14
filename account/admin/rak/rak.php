<?php
include('../../../confiq.php');
include_once('../akses.php');
$query = "SELECT * FROM `rak`";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Rak Buku</title>
  </head>

  <body>
    <!-- sidebar & navbar -->
    <?php
      include('../layout/sidebar.php');
    ?>
    
    <!-- konten -->
    <main>
      <div class="container-fluid content transition">
        <h3>Rak Buku</h3>
        
        <div class="card border">
          <div class="card-body mt-2 mx-1">

            <!-- table -->
            <div class="table-responsive">
              <div>
                <a href="addUpdateRak.php?action=tambah" class="btn btn-success btn-sm">
                  <span><i class="bi bi-plus"></i></span>
                  <span>Tambah Rak</span>
                </a>
              </div><hr class="my-3">
              <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                  <tr>
                    <th>Nomor Rak</th>
                    <th width="28%">Nama Rak</th>
                    <th width="25%">Klasifikasi</th>
                    <th width="16%">Jumlah Buku</th>
                    <th width="16%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    // fetch data menjadi array asosisasi
                    while($data = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                      // menampilkan data
                      echo "<tr><td name='id'>".$data['nomor_rak']."</td>";
                      echo "<td>".$data['nama_rak']."</td>";
                      echo "<td>".$data['kategori']."</td>";
                      echo "<td>".$data['jumlah_buku']."</td>";
                      echo "<td class='text-center'>
                              <a href='addUpdateRak.php?action=edit&&nomor_rak=".$data['nomor_rak']."' class='btn btn-warning btn-sm' data-toggle='tooltip'>
                                <span><i class='bi bi-pencil'></i><span>
                                <span>Ubah</span>
                              </a>
                              <a href='addUpdateRak.php?action=delete&&nomor_rak=".$data['nomor_rak']."' class='btn btn-danger btn-sm' data-toggle='tooltip'>
                                <span><i class='bi bi-trash'></i></span>
                                <span>Hapus</span>
                              </a>
                            </td></tr>";
                    }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>
  </body>
</html>