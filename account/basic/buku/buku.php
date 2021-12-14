<?php
include('../../../confiq.php');
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
      include('../layout/sidebar.html');
    ?>
    
    <!-- konten -->
    <main>
      <div class="container-fluid content transition">
        <h3>Buku</h3>
        
        <div class="card border">
          <div class="card-body mt-2 mx-1">

            <!-- table -->
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                  <tr>
                    <th width="10%">Nomor</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th width="7%">Tahun Terbit</th>
                    <th width="7%">Jumlah</th>
                    <th>Lokasi</th>
                    <th width="12%">Aksi</th>
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
                            <a type='button' class='btn btn-primary d-grid btn-sm' data-bs-toggle='modal' data-bs-target='#detail".$data['nomor_klasifikasi']."'>
                              <span class='me-2'><i class='bi bi-info-circle'></i><span>
                              <span>Detail</span>
                            </a></td></tr>";?>
                      
                            <!-- Modal -->
                            <div class="modal fade" id="detail<?=$data['nomor_klasifikasi']?>" tabindex="-1" aria-labelledby="detailBuku" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="detailBuku">Detail Buku</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="row">
                                      <label class="col-sm-4">Nomor Klasifikasi</label>
                                      <p class="col-sm-8"><?=$data['nomor_klasifikasi']?></p>
                                    </div>
                                    <div class="row">
                                      <label class="col-sm-4">ISBN</label>
                                      <p class="col-sm-8"><?=$data['isbn']?></p>
                                    </div>
                                    <div class="row">
                                      <label class="col-sm-4">Judul</label>
                                      <p class="col-sm-8"><?=$data['judul']?></p>
                                    </div>
                                    <div class="row">
                                      <label class="col-sm-4">Penulis</label>
                                      <p class="col-sm-7"><?=$data['penulis']?></p>
                                    </div>
                                    <div class="row">
                                      <label class="col-sm-4">Penerbit</label>
                                      <p class="col-sm-7"><?=$data['penerbit']?></p>
                                    </div>
                                    <div class="row">
                                      <label class="col-sm-4">Tahun Terbit</label>
                                      <p class="col-sm-8"><?=$data['tahun_terbit']?></p>
                                    </div>
                                    <div class="row">
                                      <label class="col-sm-4">Penempatan Rak</label>
                                      <p class="col-sm-8"><?=$data['nomor_rak']." - ".$data['nama_rak']?></p>
                                    </div>
                                    <div class="row">
                                      <label class="col-sm-4">Ketersediaan Buku</label>
                                      <p class="col-sm-8"><?=$data['stok']?></p>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div><?php
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