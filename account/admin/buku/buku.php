<?php
include('../../../confiq.php');
include_once('../akses.php');
$query = "SELECT * FROM `buku` LEFT JOIN `rak` ON buku.nomor_rak = rak.nomor_rak";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Buku</title>
  </head>

  <body>
    <!-- sidebar & navbar -->
    <?php
      include('../layout/sidebar.php');
    ?>
    
    <!-- konten -->
    <main>
      <div class="container-fluid content transition">
        <h3>Buku</h3>
        
        <div class="card border">
          <div class="card-body mt-2 mx-1">

            <!-- table -->
            <div class="table-responsive">
              <div>
                <a href="addUpdateBuku.php?action=tambah" class="btn btn-success btn-sm">
                  <span><i class="bi bi-plus"></i></span>
                  <span>Tambah Buku</span>
                </a>
              </div><hr class="my-3">
              <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                  <tr>
                    <th width="10%">Nomor</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th width="7%">Tahun Terbit</th>
                    <th width="7%">Jumlah</th>
                    <th>Lokasi</th>
                    <th width="16%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    // fetch data menjadi array asosisasi
                    while($data = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                      // menampilkan data
                      echo "<tr><td name='id'>".substr($data['nomor_klasifikasi'],0,3)." ".substr($data['nomor_klasifikasi'],3,3)." ".substr($data['nomor_klasifikasi'],6,1)."</td>";
                      echo "<td>".$data['judul']."</td>";
                      echo "<td>".$data['penulis']."</td>";
                      echo "<td>".$data['tahun_terbit']."</td>";
                      echo "<td>".$data['stok']."</td>";
                      echo "<td>Rak ".$data['nomor_rak']." - ".$data['nama_rak']."</td>";
                      echo "<td class='text-center'>
                              <a href='addUpdateBuku.php?action=edit&&nomor=".$data['nomor_klasifikasi']."' class='btn btn-warning btn-sm' data-toggle='tooltip'>
                                <span><i class='bi bi-pencil'></i><span>
                                <span>Ubah</span>
                              </a>
                              <a href='addUpdateBuku.php?action=delete&&nomor=".$data['nomor_klasifikasi']."' class='btn btn-danger btn-sm' data-toggle='tooltip'>
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