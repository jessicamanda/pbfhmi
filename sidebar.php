<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
  <div class="sidenav-header">
    <i
      class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
      aria-hidden="true"
      id="iconSidenav"></i>
    <a
      class="navbar-brand m-0"
      href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html "
      target="_blank">
      <!-- <img
        src="assets/img/logo.jpeg"
        class="navbar-brand-img h-100"
        alt="main_logo" /> -->
      <span class="ms-1 font-weight-bold" style="color:#0F5220;">
        <?= $appname ?>
      </span>
    </a>
  </div>
  <hr class="horizontal dark mt-0" />
  <div class="collapse navbar-collapse w-auto h-75" style="width: 100%;" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link <?php if (isset($_GET['hal'])) {
                              if (htmlspecialchars($_GET['hal']) == 'dashboard') {
                                echo "active";
                              }
                            } ?>" href="index.php?hal=dashboard">
          <div
            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="bi bi-speedometer2 text-sm opacity-10" style="color:#0F5220;"></i>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>

      <!-- PURCHASING -->
      <?php if ($_SESSION['admin']['role'] === 'ceo' || $_SESSION['admin']['role'] === 'purchasing'): ?>
        <li class="nav-item mt-3">
          <h6
            class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6" style="color:#6B565C;">
            Purchasing
          </h6>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'obat') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=obat">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-capsule text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Obat</span>
          </a>
        </li>



        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'tempat-penyimpanan') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=tempat-penyimpanan">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-capsule text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Tempat Penyimpanan</span>
          </a>
        </li>


        <li class="nav-item ">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'pembelian') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=pembelian">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-cash text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Pembelian</span>
          </a>
        </li>


        <!-- <li class="nav-item ">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'saatnya-order') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=saatnya-order">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-box text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Saatnya Order</span>
          </a>
        </li> -->


        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'obat-belum-datang') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=obat-belum-datang">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-box text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Obat Belum Datang</span>
          </a>
        </li>


        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'tagihan') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=tagihan">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-cash-coin text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Tagihan</span>
          </a>
        </li>


        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'terima-obat') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=terima-obat">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-dropbox text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Kedatangan Obat</span>
          </a>
        </li>
      <?php endif; ?>


      <!-- GUDANG -->
      <?php if ($_SESSION['admin']['role'] === 'ceo' || $_SESSION['admin']['role'] === 'gudang'): ?>
        <li class="nav-item mt-3">
          <h6
            class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6" style="color:#6B565C;">
            Gudang
          </h6>
        </li>


        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'stok') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=stok">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-capsule text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Stok</span>
          </a>
        </li>


        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'exp-terdekat') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=exp-terdekat">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-capsule text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Expired Terdekat</span>
          </a>
        </li>


        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'obat-macet') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=obat-macet">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-cash text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Obat Macet</span>
          </a>
        </li>


        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'obat-belum-datang') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=obat-belum-datang">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-box text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Obat Belum Datang</span>
          </a>
        </li>


        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'pareto') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=pareto">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-cash-coin text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Pareto</span>
          </a>
        </li>
      <?php endif; ?>


      <?php if ($_SESSION['admin']['role'] === 'ceo' || $_SESSION['admin']['role'] === 'akuntansi'): ?>
        <li class="nav-item mt-3">
          <h6
            class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6" style="color:#6B565C;">
            Akuntansi
          </h6>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'akun') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=akun">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-capsule text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Akun</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'transaksi') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=transaksi">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-cash text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Transaksi</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'kas') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=kas">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-cash-coin text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Kas</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'rekaplabarugi') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=rekaplabarugi">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-box text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Rekap Laba Rugi</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'rekapbukubesar') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=rekapbukubesar">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-box text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Rekap Buku Besar</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'saldoneraca') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=saldoneraca">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-cash text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Saldo Neraca</span>
          </a>
        </li>


      <?php endif; ?>


      <!--  Sales -->
      <?php if ($_SESSION['admin']['role'] === 'ceo' || $_SESSION['admin']['role'] === 'sales'): ?>
        <li class="nav-item mt-3">
          <h6
            class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6" style="color:#6B565C;">
            Sales
          </h6>
        </li>

        <?php if ($_SESSION['admin']['role'] === 'ceo'): ?>

          <li class="nav-item">
            <a class="nav-link <?php if (isset($_GET['hal'])) {
                                  if (htmlspecialchars($_GET['hal']) == 'data-sales') {
                                    echo "active";
                                  }
                                } ?>" href="index.php?hal=data-sales">

              <div
                class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                <i class="bi bi-person text-sm opacity-10" style="color: #0F5220;"></i>
              </div>
              <span class="nav-link-text ms-1">Data Sales</span>
            </a>
          </li>
        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'riwayat-absen-datang') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=riwayat-absen-datang">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-box-arrow-in-right text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Riwayat Absen Datang</span>
          </a>
        </li>


        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'riwayat-absen-pulang') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=riwayat-absen-pulang">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-box-arrow-in-left text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Riwayat Absen Pulang</span>
          </a>
        </li>


        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'penjualan-sales') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=penjualan-sales">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-cash text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Penjualan</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if (isset($_GET['hal'])) {
                                if (htmlspecialchars($_GET['hal']) == 'data-penjualan-sales') {
                                  echo "active";
                                }
                              } ?>" href="index.php?hal=data-penjualan-sales">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="bi bi-cash text-sm opacity-10" style="color: #0F5220;"></i>
            </div>
            <span class="nav-link-text ms-1">Data Penjualan</span>
          </a>
        </li>
      <?php endif; ?>

      <li class="nav-item">
        <a class="nav-link" href="index.php?logout">
          <div
            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="bi bi-box-arrow-in-left text-sm opacity-10" style="color: #0F5220;"></i>
          </div>
          <span class="nav-link-text ms-1">Logout</span>
        </a>
      </li>
    </ul>
  </div>
</aside>