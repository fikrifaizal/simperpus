<?php
include('../../../../confiq.php');
include_once('../../akses.php');

$query = "SELECT transaksi.id_peminjaman, anggota.nama as anggota, buku.judul, buku.isbn, transaksi.nomor_klasifikasi,
                transaksi.tanggal_pinjam, transaksi.durasi, transaksi.tanggal_kembali,
                transaksi.keterlambatan, petugas.nama as petugas, anggota.jabatan
          FROM `transaksi`
          LEFT JOIN `buku` ON transaksi.nomor_klasifikasi = buku.nomor_klasifikasi
          LEFT JOIN `petugas` ON transaksi.nomor_petugas = petugas.nomor_petugas
          LEFT JOIN `anggota` ON transaksi.nomor_anggota = anggota.nomor_anggota
          WHERE transaksi.selesai LIKE 0";

$result = mysqli_query($conn, $query);
$keterlambatan = 0;
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Transaksi Berjalan</title>
  </head>

  <body>
    <!-- sidebar & navbar -->
    <?php
      include('../../layout/sidebar.php');
    ?>
    
    <!-- konten -->
    <main>
      <div class="container-fluid content transition">
        <h3>Transaksi Berjalan</h3>
        
        <div class="card border">
          <div class="card-body mt-2 mx-1">

            <!-- table -->
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                  <tr>
                    <th>Nomor Transaksi</th>
                    <th>Nama Peminjam</th>
                    <th>Judul Buku</th>
                    <th>Tanggal Pinjam</th>
                    <th>Batas Pengembalian</th>
                    <th>Keterlambatan</th>
                    <th>Denda</th>
                    <th width="10%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    // fetch data menjadi array asosisasi
                    while($data = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                      $today = strtotime(date('Y-m-d'));
                      $convert = strtotime($data['tanggal_kembali']);
                      $id = $data['id_peminjaman'];

                      // hitung keterlambatan
                      $keterlambatan = $today-$convert;
                      if($keterlambatan <= 0) {
                        $keterlambatan = 0;
                      } else {
                        $keterlambatan = $keterlambatan/60/60/24;
                      }
                      $denda = $keterlambatan*1000;
                      
                      // menampilkan data
                      echo "<tr><td name='id'>".$data['id_peminjaman']."</td>";
                      echo "<td>".$data['anggota']."</td>";
                      echo "<td>".$data['judul']."</td>";
                      echo "<td>".$data['tanggal_pinjam']."</td>";
                      echo "<td>".$data['tanggal_kembali']."</td>";
                      echo "<td>".$keterlambatan." Hari</td>";
                      echo "<td>Rp".$denda."</td>";
                      echo "<td class='text-center'>
                            <a type='button' class='btn btn-primary d-grid btn-sm' data-bs-toggle='modal' data-bs-target='#detail".$data['id_peminjaman']."'>
                              Detail
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
                                <label class="col-sm-5">Nomor Transaksi</label>
                                <p class="col-sm-7"><?=$data['id_peminjaman']?></p>
                              </div>
                              <div class="row">
                                <label class="col-sm-5">Peminjam</label>
                                <p class="col-sm-7"><?=$data['anggota']?> (<?=$data['jabatan']?>)</p>
                              </div>
                              <div class="row">
                                <label class="col-sm-5">Nomor Buku</label>
                                <p class="col-sm-7"><?=$data['nomor_klasifikasi']?></p>
                              </div>
                              <div class="row">
                                <label class="col-sm-5">Buku</label>
                                <p class="col-sm-7"><?=$data['judul']?> (ISBN: <?=$data['isbn']?>)</p>
                              </div>
                              <div class="row">
                                <label class="col-sm-5">Tanggal Pinjam</label>
                                <p class="col-sm-7"><?=$data['tanggal_pinjam']?></p>
                              </div>
                              <div class="row">
                                <label class="col-sm-5">Durasi</label>
                                <p class="col-sm-7"><?=$data['durasi']?> Hari</p>
                              </div>
                              <div class="row">
                                <label class="col-sm-5">Batas Pengembalian</label>
                                <p class="col-sm-7"><?=$data['tanggal_kembali']?></p>
                              </div>
                              <div class="row">
                                <label class="col-sm-5">Keterlambatan</label>
                                <p class="col-sm-7"><?=$data['keterlambatan']?> hari</p>
                              </div>
                              <div class="row">
                                <label class="col-sm-5">Denda</label>
                                <p class="col-sm-7">Rp<?=($data['keterlambatan']*1000)?></p>
                              </div><hr>
                              <div class="row">
                                <label class="col-sm-5">Petugas</label>
                                <p class="col-sm-7"><?=$data['petugas']?></p>
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