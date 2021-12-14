<?php 
include('../../../../confiq.php');
include_once('../../akses.php');

// buku
if (isset($_POST['buku'])){
  $getText = $_POST['buku'];
  $getCount = "SELECT COUNT(`nomor_klasifikasi`) AS total FROM `buku` WHERE `nomor_klasifikasi` LIKE '%$getText%'";
  $queryCount = mysqli_query($conn, $getCount);
  $count = mysqli_fetch_array($queryCount, MYSQLI_ASSOC);

  if ($count['total'] > 0){    
    $getData = "SELECT * FROM `buku` WHERE `nomor_klasifikasi` LIKE '%$getText%'";
    $queryData = mysqli_query($conn, $getData);
    while($row = mysqli_fetch_array($queryData, MYSQLI_ASSOC)) {
      echo '<a type="button" class="list-group-item list-group-item-action ajax-klasifikasi">'.$row['nomor_klasifikasi'].' - '.$row['judul'].'</a>';
    }
  } else {
    echo '<p class="list-group-item list-group-item-dark disabled">Data Tidak Ditemukan</p>';
  }
}

// anggota
elseif (isset($_POST['anggota'])){
  $getText = $_POST['anggota'];
  $getCount = "SELECT COUNT(`nomor_anggota`) AS total FROM `anggota` WHERE `nomor_anggota` LIKE '%$getText%'";
  $queryCount = mysqli_query($conn, $getCount);
  $count = mysqli_fetch_array($queryCount, MYSQLI_ASSOC);

  if ($count['total'] > 0){    
    $getData = "SELECT `nomor_anggota`, `nama` FROM `anggota` WHERE `nomor_anggota` LIKE '%$getText%'";
    $queryData = mysqli_query($conn, $getData);
    while($row = mysqli_fetch_array($queryData, MYSQLI_ASSOC)) {
      echo '<a type="button" class="list-group-item list-group-item-action ajax-anggota">'.$row['nomor_anggota'].' - '.$row['nama'].'</a>';
    }
  } else {
    echo '<p class="list-group-item list-group-item-dark disabled">Data Tidak Ditemukan</p>';
  }
}

// transaksi
elseif (isset($_POST['transaksi'])){
  $getText = $_POST['transaksi'];
  $getCount = "SELECT COUNT(`id_peminjaman`) AS total FROM `transaksi` WHERE `id_peminjaman` LIKE '%$getText%' AND `selesai` LIKE 0";
  $queryCount = mysqli_query($conn, $getCount);
  $count = mysqli_fetch_array($queryCount, MYSQLI_ASSOC);

  if ($count['total'] > 0){    
    $getData = "SELECT transaksi.id_peminjaman, anggota.nama as anggota, buku.judul, buku.isbn,
                        transaksi.tanggal_pinjam, transaksi.durasi, transaksi.tanggal_kembali,
                        transaksi.keterlambatan, petugas.nama as petugas, anggota.jabatan
                FROM `transaksi`
                LEFT JOIN `buku` ON transaksi.nomor_klasifikasi = buku.nomor_klasifikasi
                LEFT JOIN `petugas` ON transaksi.nomor_petugas = petugas.nomor_petugas
                LEFT JOIN `anggota` ON transaksi.nomor_anggota = anggota.nomor_anggota
                WHERE `id_peminjaman` LIKE '%$getText%' AND transaksi.selesai LIKE 0";
    $queryData = mysqli_query($conn, $getData);
    while($row = mysqli_fetch_array($queryData, MYSQLI_ASSOC)) {
      echo '<a type="button" class="list-group-item list-group-item-action ajax-transaksi">'.$row['id_peminjaman'].' - '.$row['anggota'].' - '.$row['judul'].'</a>';
    }
  } else {
    echo '<p class="list-group-item list-group-item-dark disabled">Data Tidak Ditemukan</p>';
  }
}

// anggota di Halaman Pengembalian
elseif (isset($_POST['anggota2'])){
  $getText = $_POST['anggota2'];
  $getCount = "SELECT COUNT(`id_peminjaman`) AS total FROM `transaksi` WHERE `nomor_anggota` LIKE '%$getText%' AND `selesai` LIKE 0";
  $queryCount = mysqli_query($conn, $getCount);
  $count = mysqli_fetch_array($queryCount, MYSQLI_ASSOC);

  if ($count['total'] > 0){    
    $getData = "SELECT transaksi.id_peminjaman, transaksi.nomor_anggota, anggota.nama as anggota,
                        buku.judul, buku.isbn, transaksi.tanggal_pinjam, transaksi.durasi,
                        transaksi.tanggal_kembali, transaksi.keterlambatan,
                        petugas.nama as petugas, anggota.jabatan
                FROM `transaksi`
                LEFT JOIN `buku` ON transaksi.nomor_klasifikasi = buku.nomor_klasifikasi
                LEFT JOIN `petugas` ON transaksi.nomor_petugas = petugas.nomor_petugas
                LEFT JOIN `anggota` ON transaksi.nomor_anggota = anggota.nomor_anggota
                WHERE transaksi.nomor_anggota LIKE '%$getText%' AND transaksi.selesai LIKE 0";
    $queryData = mysqli_query($conn, $getData);
    while($row = mysqli_fetch_array($queryData, MYSQLI_ASSOC)) {
      echo '<a type="button" class="list-group-item list-group-item-action ajax-anggota2">'.$row['nomor_anggota'].' - '.$row['anggota'].' - '.$row['judul'].'</a>';
    }
  } else {
    echo '<p class="list-group-item list-group-item-dark disabled">Data Tidak Ditemukan</p>';
  }
}
?>

<style>
  .list-group-item:hover {
    background-color: #009B4C !important;
    color: #ffffff !important;
  }
</style>