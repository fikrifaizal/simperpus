<?php
include('../../../confiq.php');
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
      include('../layout/sidebar.html');
    ?>
    
    <!-- konten -->
    <main>
      <div class="container-fluid content transition">
        <h3>Rak Buku</h3>
        
        <div class="card border">
          <div class="card-body mt-2 mx-1">

            <!-- table -->
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                  <tr>
                    <th>Nomor Rak</th>
                    <th width="28%">Nama Rak</th>
                    <th width="25%">Klasifikasi</th>
                    <th width="16%">Jumlah Buku</th>
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