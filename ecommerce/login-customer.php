<?php session_start();?>
<?php include "../koneksi.php";?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" href="assets/img/logo.jpeg">
  <title>
    <?= $appname?>
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
                <h4 class="font-weight-bolder" style="color: #0F5220;"><?= $appname?> App</h4>
              </div>
              <div class="card-body">
                <form  method="post">
                  <div class="mb-3">
                    <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" aria-label="Username">
                  </div>
                  <div class="mb-3">
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password">
                  </div>
                  <div class="text-center">
                    <button type="submit" name="login" class="btn btn-lg btn-lg w-100 mt-4 mb-0 text-white" style="background-color: #0F5220;">Login</button>
                  </div>
                  <div class="text-center">
                    <p>
                      Belum punya akun? <a href="./register-customer.php">Register</a>
                    </p>
                </form>
                <?php 
                  if (isset($_POST['login'])) {
                      $username =$_POST['username'];
                      $password =$_POST['password'];
                  
                      // $sql = "SELECT * FROM user INNER JOIN transaksi ON transaksi.user_id = user.id WHERE username = '$username' AND (transaksi.provinsi != '' OR transaksi.provinsi IS NULL) LIMIT 1;";
                      $sql = "SELECT user.id, user.nama_lengkap, user.username, user.password, user.nohp, user.provinsi, user.kota, user.kecamatan, user.kelurahan, user.kode_pos, user.alamat, user.role, transaksi.instansi  FROM user LEFT JOIN transaksi ON transaksi.user_id = user.id WHERE username = '$username' AND (transaksi.provinsi != '' OR transaksi.provinsi IS NULL) LIMIT 1;";
                      $result = $con->query($sql);
                  
                      if ($result->num_rows > 0) {
                          $row = $result->fetch_assoc();
                          if ($password == $row['password']) {
                              $_SESSION['username'] = $row['username'];
                              $_SESSION['admin'] = $row;
                              header("Location: ./index.php"); // Redirect to dashboard or any other page
                              echo  "Berhasil";
                          } else {
                              $error = "Invalid password";
                              echo "
                                <script>
                                  alert('Password Salah');
                                  document.location.href='login-customer.php';
                                </script>
                              ";
                          }
                      } else {
                          $error = "Invalid username";
                          echo "
                          <script>
                            alert('Username Salah');
                            document.location.href='login-customer.php';
                          </script>
                        ";
                      }
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

</html>