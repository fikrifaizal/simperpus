<?php 
include('../../confiq.php');
include_once('akses.php');
$today = date('Y-m-d');
$yearmonth = date('Y');

// buku
$count_buku = "SELECT COUNT(nomor_klasifikasi) as buku FROM `buku`";
$query_buku = mysqli_query($conn, $count_buku);
$data_buku = mysqli_fetch_array($query_buku, MYSQLI_ASSOC);

// anggota
$count_anggota = "SELECT COUNT(nomor_anggota) as anggota FROM `anggota`";
$query_anggota = mysqli_query($conn, $count_anggota);
$data_anggota = mysqli_fetch_array($query_anggota, MYSQLI_ASSOC);

// peminjaman
$count_peminjaman = "SELECT COUNT(id_peminjaman) as peminjaman
                      FROM `transaksi` WHERE `selesai` LIKE 0 AND `tanggal_kembali` LIKE '$today'";
$query_peminjaman = mysqli_query($conn, $count_peminjaman);
$data_peminjaman = mysqli_fetch_array($query_peminjaman, MYSQLI_ASSOC);

// pengembalian
$count_pengembalian = "SELECT COUNT(id_peminjaman) as pengembalian
                        FROM `transaksi` WHERE `selesai` LIKE 1 AND `tanggal_kembali` LIKE '$today'";
$query_pengembalian = mysqli_query($conn, $count_pengembalian);
$data_pengembalian = mysqli_fetch_array($query_pengembalian, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Home</title>
    <style>
      /* card hover */      
      .card .icon {
        position: absolute;
        top: auto;
        bottom: 20px;
        right: 10px;
        z-index: 0;
        font-size: 72px;
        color: rgba(0, 0, 0, 0.20);
      }

      .card:hover .icon {
        font-size: 85px;
        transition: 1s;
        -webkit-transition: 1s;
      }

      .card:hover .card-footer {        
        z-index: 1;
        background: rgba(0, 0, 0, 0.3);
      }
    </style>
  </head>

  <body>
    <!-- sidebar & navbar -->
    <?php
      include('layout/sidebar.php');
    ?>

    <!-- konten -->
    <main>
      <div class="container-fluid content transition">
        <h3>Dashboard</h3>
        
        <!-- card info -->
        <div class="row">
          <div class="col-md-3 mb-3">
            <div class="card bg-primary">
              <div class="card-body mt-2 mx-1 text-white">
                <h8 class="">Banyak Buku</h8>
                <h4 class="fw-bold"><?=$data_buku['buku']?></h4>
                <div class="icon">
                  <i class="bi bi-book" aria-hidden="true"></i>
                </div>
              </div>
              <div class="card-footer btn text-white">
                <a href="buku/buku.php" class="text-white" style="text-decoration:none">
                  Lihat Detail
                </a>
                <i class="bi bi-arrow-right-circle"></i>
              </div>
            </div>
          </div>

          <div class="col-md-3 mb-3">
            <div class="card bg-danger">
              <div class="card-body mt-2 mx-1 text-white">
                <h8 class="">Banyak Anggota</h8>
                <h4 class="fw-bold"><?=$data_anggota['anggota']?></h4>
                <div class="icon">
                  <i class="bi bi-person" aria-hidden="true"></i>
                </div>
              </div>
              <div class="card-footer btn text-white">
                <a href="anggota/anggota.php" class="text-white" style="text-decoration:none">
                  Lihat Detail
                </a>
                <i class="bi bi-arrow-right-circle"></i>
              </div>
            </div>
          </div>

          <div class="col-md-3 mb-3">
            <div class="card bg-success">
              <div class="card-body mt-2 mx-1 text-white">
                <h8 class="">Peminjaman Hari Ini</h8>
                <h4 class="fw-bold"><?=$data_peminjaman['peminjaman']?></h4>
                <div class="icon">
                  <i class="bi bi-calendar-minus" aria-hidden="true"></i>
                </div>
              </div>
              <div class="card-footer btn text-white">
                <a href="transaksi/table/berjalan.php" class="text-white" style="text-decoration:none">
                  Lihat Detail
                </a>
                <i class="bi bi-arrow-right-circle"></i>
              </div>
            </div>
          </div>
          
          <div class="col-md-3 mb-3">
            <div class="card bg-secondary">
              <div class="card-body mt-2 mx-1 text-white">
                <h8 class="">Pengembalian Hari Ini</h8>
                <h4 class="fw-bold"><?=$data_pengembalian['pengembalian']?></h4>
                <div class="icon">
                  <i class="bi bi-calendar-check" aria-hidden="true"></i>
                </div>
              </div>
              <div class="card-footer btn text-white">
                <a href="transaksi/table/selesai.php" class="text-white" style="text-decoration:none">
                  Lihat Detail
                </a>
                <i class="bi bi-arrow-right-circle"></i>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="card h-100">
              <div class="card-header">
                <span class="me-2"><i class="bi bi-bar-chart-fill"></i></span>
                Peminjaman bulan Januari-Juni <?=$yearmonth?>
              </div>
              <div class="card-body">
                <canvas class="chart1" width="400" height="200"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="card h-100">
              <div class="card-header">
                <span class="me-2"><i class="bi bi-bar-chart-fill"></i></span>
                Peminjaman bulan Juli-Desember <?=$yearmonth?>
              </div>
              <div class="card-body">
                <canvas class="chart2" width="400" height="200"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
    <script>
      // Area Chart Januari-Juni
      const chart1 = document.querySelectorAll(".chart1");

      chart1.forEach(function (chart) {
        var ctx = chart.getContext("2d");
        var myChart = new Chart(ctx, {
          type: "line",
          data: {
            labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni"],
            datasets: [
              {
                label: "Jumlah Peminjaman",
                data: [
                  <?php 
                    $set_date = $yearmonth."-01";
                    $count_chart = "SELECT id_peminjaman FROM `transaksi` WHERE `tanggal_pinjam` LIKE '%$set_date%'";
                    $query_chart = mysqli_query($conn, $count_chart);
                    echo mysqli_num_rows($query_chart);
                  ?>,                  
                  <?php 
                    $set_date = $yearmonth."-02";
                    $count_chart = "SELECT id_peminjaman FROM `transaksi` WHERE `tanggal_pinjam` LIKE '%$set_date%'";
                    $query_chart = mysqli_query($conn, $count_chart);
                    echo mysqli_num_rows($query_chart);
                  ?>,                  
                  <?php 
                    $set_date = $yearmonth."-03";
                    $count_chart = "SELECT id_peminjaman FROM `transaksi` WHERE `tanggal_pinjam` LIKE '%$set_date%'";
                    $query_chart = mysqli_query($conn, $count_chart);
                    echo mysqli_num_rows($query_chart);
                  ?>,                  
                  <?php 
                    $set_date = $yearmonth."-04";
                    $count_chart = "SELECT id_peminjaman FROM `transaksi` WHERE `tanggal_pinjam` LIKE '%$set_date%'";
                    $query_chart = mysqli_query($conn, $count_chart);
                    echo mysqli_num_rows($query_chart);
                  ?>,                  
                  <?php 
                    $set_date = $yearmonth."-05";
                    $count_chart = "SELECT id_peminjaman FROM `transaksi` WHERE `tanggal_pinjam` LIKE '$%$set_date%'";
                    $query_chart = mysqli_query($conn, $count_chart);
                    echo mysqli_num_rows($query_chart);
                  ?>,                  
                  <?php 
                    $set_date = $yearmonth."-06";
                    $count_chart = "SELECT id_peminjaman FROM `transaksi` WHERE `tanggal_pinjam` LIKE '%$set_date%'";
                    $query_chart = mysqli_query($conn, $count_chart);
                    echo mysqli_num_rows($query_chart);
                  ?>
                ],
                borderColor: [
                  "rgba(255, 99, 132, 1)",
                  "rgba(54, 162, 235, 1)",
                  "rgba(255, 206, 86, 1)",
                  "rgba(75, 192, 192, 1)",
                  "rgba(153, 102, 255, 1)",
                  "rgba(255, 159, 64, 1)"
                ],
                borderWidth: 1,
              },
            ],
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
              },
            },
          },
        });
      });

      // Area Chart Juli-Desember
      const chart2 = document.querySelectorAll(".chart2");

      chart2.forEach(function (chart) {
        var ctx = chart.getContext("2d");
        var myChart = new Chart(ctx, {
          type: "line",
          data: {
            labels: ["Juli", "Agustus", "September", "Oktober", "November", "Desember"],
            datasets: [
              {
                label: "Jumlah Peminjaman",
                data: [
                  <?php 
                    $yearmonth = date('Y');
                    $set_date = $yearmonth."-07";
                    $count_chart = "SELECT id_peminjaman FROM `transaksi` WHERE `tanggal_pinjam` LIKE '%$set_date%'";
                    $query_chart = mysqli_query($conn, $count_chart);
                    echo mysqli_num_rows($query_chart);
                  ?>,                  
                  <?php 
                    $yearmonth = date('Y');
                    $set_date = $yearmonth."-08";
                    $count_chart = "SELECT id_peminjaman FROM `transaksi` WHERE `tanggal_pinjam` LIKE '%$set_date%'";
                    $query_chart = mysqli_query($conn, $count_chart);
                    echo mysqli_num_rows($query_chart);
                  ?>,                  
                  <?php 
                    $yearmonth = date('Y');
                    $set_date = $yearmonth."-09";
                    $count_chart = "SELECT id_peminjaman FROM `transaksi` WHERE `tanggal_pinjam` LIKE '%$set_date%'";
                    $query_chart = mysqli_query($conn, $count_chart);
                    echo mysqli_num_rows($query_chart);
                  ?>,                  
                  <?php 
                    $yearmonth = date('Y');
                    $set_date = $yearmonth."-10";
                    $count_chart = "SELECT id_peminjaman FROM `transaksi` WHERE `tanggal_pinjam` LIKE '%$set_date%'";
                    $query_chart = mysqli_query($conn, $count_chart);
                    echo mysqli_num_rows($query_chart);
                  ?>,                  
                  <?php 
                    $yearmonth = date('Y');
                    $set_date = $yearmonth."-11";
                    $count_chart = "SELECT id_peminjaman FROM `transaksi` WHERE `tanggal_pinjam` LIKE '%$set_date%'";
                    $query_chart = mysqli_query($conn, $count_chart);
                    echo mysqli_num_rows($query_chart);
                  ?>,                  
                  <?php 
                    $yearmonth = date('Y');
                    $set_date = $yearmonth."-12";
                    $count_chart = "SELECT id_peminjaman FROM `transaksi` WHERE `tanggal_pinjam` LIKE '%$set_date%'";
                    $query_chart = mysqli_query($conn, $count_chart);
                    echo mysqli_num_rows($query_chart);
                  ?>
                ],
                borderColor: [
                  "rgba(255, 99, 132, 1)",
                  "rgba(54, 162, 235, 1)",
                  "rgba(255, 206, 86, 1)",
                  "rgba(75, 192, 192, 1)",
                  "rgba(153, 102, 255, 1)",
                  "rgba(255, 159, 64, 1)"
                ],
                borderWidth: 1,
              },
            ],
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
              },
            },
          },
        });
      });
    </script>
  </body>
</html>