<?php
include('../../../confiq.php');
include_once('../akses.php');
$id_user = $_SESSION['id'];

$query = "SELECT transaksi.id_peminjaman, transaksi.nomor_klasifikasi,
                transaksi.tanggal_pinjam, transaksi.tanggal_kembali, transaksi.keterlambatan,
                buku.judul, buku.isbn, petugas.nama as petugas, anggota.jabatan
          FROM `transaksi`
          LEFT JOIN `buku` ON transaksi.nomor_klasifikasi = buku.nomor_klasifikasi
          LEFT JOIN `petugas` ON transaksi.nomor_petugas = petugas.nomor_petugas
          LEFT JOIN `anggota` ON transaksi.nomor_anggota = anggota.nomor_anggota
          WHERE transaksi.selesai LIKE 1 AND transaksi.nomor_anggota LIKE $id_user";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Transaksi Selesai</title>
  </head>

  <body>
    <!-- sidebar & navbar -->
    <?php
      include('../layout/sidebar.php');
    ?>
    
    <!-- konten -->
    <main>
      <div class="container-fluid content transition">
        <h3>Transaksi Selesai</h3>
        
        <div class="card border">
          <div class="card-body mt-2 mx-1">

            <!-- table -->
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                  <tr>
                    <th width="12%">Nomor Transaksi</th>
                    <th>Nama Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Keterlambatan</th>
                    <th width="12%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    // fetch data menjadi array asosisasi
                    while($data = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                      // menampilkan data
                      echo "<tr><td name='id'>".$data['id_peminjaman']."</td>";
                      echo "<td>".$data['judul']."</td>";
                      echo "<td>".date('d-m-Y', strtotime($data['tanggal_pinjam']))."</td>";
                      echo "<td>".date('d-m-Y', strtotime($data['tanggal_kembali']))."</td>";
                      echo "<td>".$data['keterlambatan']." Hari</td>";
                      echo "<td class='text-center'>
                            <a type='button' class='btn btn-primary d-grid btn-sm' data-bs-toggle='modal' data-bs-target='#detail".$data['id_peminjaman']."'>
                              <span class='me-2'><i class='bi bi-info-circle'></i><span>
                              <span>Detail</span>
                            </a></td></tr>";?>
                      
                      <!-- Modal -->
                      <div class="modal fade" id="detail<?=$data['id_peminjaman']?>" tabindex="-1" aria-labelledby="detailTransaksi" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="detailTransaksi">Detail Transaksi</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <div class="row">
                                <label class="col-sm-4">Nomor Transaksi</label>
                                <p class="col-sm-8"><?=$data['id_peminjaman']?></p>
                              </div>
                              <div class="row">
                                <label class="col-sm-4">Nomor Buku</label>
                                <p class="col-sm-8"><?=$data['nomor_klasifikasi']?> (ISBN: <?=$data['isbn']?>)</p>
                              </div>
                              <div class="row">
                                <label class="col-sm-4">Buku</label>
                                <p class="col-sm-8"><?=$data['judul']?></p>
                              </div>
                              <div class="row">
                                <label class="col-sm-4">Tanggal Pinjam</label>
                                <p class="col-sm-7"><?=date('d-m-Y', strtotime($data['tanggal_pinjam']))?></p>
                              </div>
                              <div class="row">
                                <label class="col-sm-4">Tanggal Kembali</label>
                                <p class="col-sm-7"><?=date('d-m-Y', strtotime($data['tanggal_kembali']))?></p>
                              </div>
                              <div class="row">
                                <label class="col-sm-4">Keterlambatan</label>
                                <p class="col-sm-8"><?=$data['keterlambatan']?> hari</p>
                              </div>
                              <div class="row">
                                <label class="col-sm-4">Denda</label>
                                <p class="col-sm-8">Rp<?=($data['keterlambatan']*1000)?></p>
                              </div><hr>
                              <div class="row">
                                <label class="col-sm-4">Petugas</label>
                                <p class="col-sm-8"><?=$data['petugas']?></p>
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