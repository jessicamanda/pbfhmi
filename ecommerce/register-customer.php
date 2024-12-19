<?php session_start(); ?>
<?php include "../koneksi.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" href="assets/img/logo.jpeg">
  <title>
    <?= $appname ?>
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />
</head>

<body class="">
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <center>
            <div class="card shadow-lg" style="max-width: 360px;">
              <div class="card-header pb-0 text-start">
                <p class="mb-0">Welcome to,</p>
                <h4 class="font-weight-bolder" style="color: #0F5220;"><?= $appname ?> App</h4>
              </div>
              <div class="card-body ">
                <form method="post">
                  <div class="mb-3">
                    <input type="text" name="nama_lengkap" class="form-control form-control-lg" placeholder="Isi nama lengkap Anda" aria-label="nama_lengkap">
                  </div>
                  <div class="mb-3">
                    <input type="number" name="nohp" class="form-control form-control-lg" placeholder="No Hp" aria-label="nohp">
                  </div>
                  <div class="mb-3">
                    <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" aria-label="Username">
                  </div>
                  <div class="mb-3">
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password">
                  </div>
                  <div class="text-center">
                    <button type="submit" name="register" class="btn btn-lg btn-lg w-100 mt-4 mb-0 text-white" style="background-color: #0F5220;">Register</button>
                  </div>
                  <div class="text-center">
                    <p>
                      Sudah punya akun? <a href="./login-customer.php">Login</a>
                    </p>
                  </div>
                </form>
                <?php
                if (isset($_POST['register'])) {
                  $nama_lengkap = htmlspecialchars($_POST['nama_lengkap']);
                  $nohp = htmlspecialchars($_POST['nohp']);
                  $username = htmlspecialchars($_POST['username']);
                  $password = htmlspecialchars($_POST['password']);

                  $con->query("INSERT INTO user (nama_lengkap, nohp, username, password, role) VALUES ('$nama_lengkap','$nohp', '$username','$password', 'pelanggan')");
                  echo "<script>alert('User berhasil disimpan'); document.location.href='./login-customer.php';</script>";
                }
                ?>
              </div>
            </div>
          </center>
        </div>
      </div>
    </section>
  </main>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/argon-dashboard.min.js?v=2.0.4"></script>
</body>