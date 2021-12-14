<?php
include('../../../confiq.php');
include_once('../akses.php');
$query = "SELECT `nomor_anggota`, `nama`, `jabatan`, `angkatan` FROM `anggota`";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Anggota</title>
  </head>

  <body>
    <!-- sidebar & navbar -->
    <?php
      include('../layout/sidebar.php');
    ?>
    
    <!-- konten -->
    <main>
      <div class="container-fluid content transition">
        <h3>Anggota</h3>
        
        <div class="card border">
          <div class="card-body mt-2 mx-1">

            <!-- table -->
            <div class="table-responsive">
              <div>
                <a href="addUpdateAnggota.php?action=tambah" class="btn btn-success btn-sm">
                  <span><i class="bi bi-plus"></i></span>
                  <span>Tambah Anggota</span>
                </a>
              </div><hr class="my-3">
              <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                  <tr>
                    <th width="16%">Nomor Anggota</th>
                    <th>Nama</th>
                    <th width="16%">Keterangan</th>
                    <th width="10%">Angkatan</th>
                    <th width="16%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    // fetch data menjadi array asosisasi
                    while($data = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                      // menampilkan data
                      echo "<tr><td name='id'>".$data['nomor_anggota']."</td>";
                      echo "<td>".$data['nama']."</td>";
                      echo "<td>".$data['jabatan']."</td>";
                      echo "<td>".$data['angkatan']."</td>";
                      echo "<td class='text-center'>
                              <a href='addUpdateAnggota.php?action=edit&&nomor=".$data['nomor_anggota']."' class='btn btn-warning btn-sm' data-toggle='tooltip'>
                                <span><i class='bi bi-pencil'></i><span>
                                <span>Ubah</span>
                              </a>
                              <a href='addUpdateAnggota.php?action=delete&&nomor=".$data['nomor_anggota']."' class='btn btn-danger btn-sm' data-toggle='tooltip'>
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