<?php 
$user = explode(" ", $_SESSION['nama']);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,400;0,500;0,600;1,400;1,500;1,600&display=swap" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="/SimPerpus%20SMK%20Muh%201%20Surakarta/account/user/layout/style.css" />
  </head>
  
  <body>
    <!-- top navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="offcanvasExample">
          <span class="navbar-toggler-icon" data-bs-target="#sidebar"></span>
        </button>
        <a class="navbar-brand me-auto ms-lg-0 px-2 text-uppercase fw-bold title text-white">SIMPERPUS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavBar" aria-controls="topNavBar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="topNavBar">
          <div class="d-flex ms-auto my-3 my-lg-0">
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle ms-2 fw-bold text-white"  data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-person-fill"></i>
                  <span><?php echo $user[0]; ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end fade-down">
                  <li>
                    <a href="/SimPerpus%20SMK%20Muh%201%20Surakarta/account/user/pengaturan.php" class="dropdown-item">
                      <span class="me-2"><i class="bi bi-gear"></i></span>
                      <span>Ubah Password</span>
                    </a>
                  </li>
                  <li>
                    <a href="/SimPerpus%20SMK%20Muh%201%20Surakarta/auth/logout.php" class="dropdown-item">
                      <span class="me-2"><i class="bi bi-power"></i></span>
                      <span>Logout</span>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>          
        </div>
      </div>
    </nav>
    <!-- top navigation bar -->

    <!-- offcanvas -->
    <div class="offcanvas offcanvas-start sidebar-nav bg-light" tabindex="-1" id="sidebar">
      <div class="offcanvas-body p-0">
        <nav class="navbar-light">
          <ul class="navbar-nav">
            <li>
              <img class="logo" src="http://smkmuh1solo.sch.id/files/2018/02/SMKMUH1-Header.png" alt="" />
              <li class="px-4 logo-divider"><hr class="dropdown-divider bg-dark" /></li>
            </li>
            <li>
              <div class="text-gray-500 small fw-bold text-uppercase px-4 mt-3">Dashboard</div>
              <a href="/SimPerpus%20SMK%20Muh%201%20Surakarta/account/user/home.php" class="btn-sidebar nav-link px-4 text-dark">
                <span class="me-2"><i class="bi bi-house"></i></span>
                <span>Home</span>
              </a>
            </li>

            <li class="my-2 px-4"><hr class="dropdown-divider bg-dark" /></li>

            <li>
              <div class="text-gray-500 small fw-bold text-uppercase px-4">Daftar Buku</div>
              <a href="/SimPerpus%20SMK%20Muh%201%20Surakarta/account/user/buku/buku.php" class="btn-sidebar nav-link px-4 text-dark">
                <span class="me-2"><i class="bi bi-book"></i></span>
                <span>Buku</span>
              </a>
              <a href="/SimPerpus%20SMK%20Muh%201%20Surakarta/account/user/rak/rak.php" class="btn-sidebar nav-link px-4 text-dark">
                <span class="me-2"><i class="bi bi-bookshelf"></i></span>
                <span>Rak</span>
              </a>
            </li>

            <li class="my-2 px-4"><hr class="dropdown-divider bg-dark" /></li>

            <li>
              <div class="text-gray-500 small fw-bold text-uppercase px-4 text-secondary">Riwayat Peminjaman</div>
              <a href="/SimPerpus%20SMK%20Muh%201%20Surakarta/account/user/riwayat/selesai.php" class="btn-sidebar nav-link px-4 text-dark">
                <span class="me-2"><i class="bi bi-file-earmark-text"></i></span>
                <span>Selesai</span>
              </a>
              <a href="/SimPerpus%20SMK%20Muh%201%20Surakarta/account/user/riwayat/berjalan.php" class="btn-sidebar nav-link px-4 text-dark">
                <span class="me-2"><i class="bi bi-file-earmark-check"></i></span>
                <span>Berjalan</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>
    <!-- offcanvas -->

    <!-- Javascript Support -->
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  
    <!-- dataTables -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js" crossorigin="anonymous"></script>

    <!-- Chart -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
    
    <script>
      $(document).ready(function () {
        $('#dataTables-example').dataTable();
        $('a.btn-sidebar').click(function(){
          $('a.btn-sidebar.active').each(function(){
            $(this).removeClass('active');
          });
          $(this).addClass('active');
        });
      });
    </script>
  </body>
</html>