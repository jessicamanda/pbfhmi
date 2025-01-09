    <?php if ($_SESSION['admin']['role'] === 'sales'): ?>
        <div class="row ">
            <div class="col-md-3">
                <div class="card shadow p-3 mb-3">
                    <a href="index.php?hal=absen-datang">
                        <div class="position-relative p-3">
                            <center>
                                <h2><i class="bi bi-box-arrow-in-right"></i></h2>
                                <p>Absen Datang</p>
                            </center>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow p-3 mb-3">
                    <a href="index.php?hal=absen-pulang">
                        <div class="position-relative p-3">
                            <center>
                                <h2><i class="bi bi-box-arrow-in-right"></i></h2>
                                <p>Absen Pulang</p>
                            </center>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-2">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">
                                        Penghasilan Hari Ini
                                    </p>
                                    <?php
                                    $sales_id = $_SESSION['admin']['id'];

                                    $getPenjualanHari = $con->query("SELECT SUM(sub_total) as hari FROM transaksi_produk JOIN transaksi ON transaksi_produk.no_nota=transaksi.no_nota WHERE transaksi.sales_id='$sales_id' AND DATE_FORMAT(transaksi.tgl, '%Y-%m-%d') = '" . date('Y-m-d') . "'")->fetch_assoc();

                                    $getPenjualanBulan = $con->query("SELECT SUM(sub_total) as bulan FROM transaksi_produk JOIN transaksi ON transaksi_produk.no_nota=transaksi.no_nota WHERE transaksi.sales_id='$sales_id' AND DATE_FORMAT(transaksi.tgl, '%Y-%m') = '" . date('Y-m') . "'")->fetch_assoc();

                                    $getPenjualanTahun = $con->query("SELECT SUM(sub_total) as tahun FROM transaksi_produk JOIN transaksi ON transaksi_produk.no_nota=transaksi.no_nota WHERE transaksi.sales_id='$sales_id' AND DATE_FORMAT(transaksi.tgl, '%Y') = '" . date('Y') . "'")->fetch_assoc();
                                    ?>
                                    <h5 class="font-weight-bolder">Rp <?= number_format($getPenjualanHari['hari'], 0, 0, '.') ?></h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div
                                    class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i
                                        class="bi bi-cash text-lg opacity-10"
                                        aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-2">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">
                                        Penghasilan Bulan Ini
                                    </p>
                                    <h5 class="font-weight-bolder">Rp <?= number_format($getPenjualanBulan['bulan'], 0, 0, '.') ?></h5>
                                    <!-- <p class="mb-0">
                  <span class="text-success text-sm font-weight-bolder">+55%</span> ads
                </p> -->
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div
                                    class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i
                                        class="bi bi-cash text-lg opacity-10"
                                        aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 mb-xl-0 mb-2">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">
                                        Penghasilan Tahun Ini
                                    </p>
                                    <h5 class="font-weight-bolder">Rp <?= number_format($getPenjualanTahun['tahun'], 0, 0, '.') ?></h5>
                                    <!-- <p class="mb-0">
                  <span class="text-success text-sm font-weight-bolder">+55%</span> ads
                </p> -->
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div
                                    class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i
                                        class="bi bi-cash text-lg opacity-10"
                                        aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function rekapPenghasilan(key) {
                document.location.href = 'index.php?hal=rekap-penjualan-pusat&src&key=' + key;
            }
        </script>
        <div class="card shadow-sm p-2 mt-2">
            <canvas id="myChart"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <?php
        $sales_id = $_SESSION['admin']['id'];

        $getPenjualanPerBulan = $con->query("SELECT DATE_FORMAT(transaksi.tgl, '%M %Y') as bulan, DATE_FORMAT(transaksi.tgl, '%Y-%m') as bln, SUM(sub_total) as total FROM transaksi_produk JOIN transaksi ON transaksi_produk.no_nota=transaksi.no_nota WHERE transaksi.sales_id='$sales_id' GROUP BY DATE_FORMAT(transaksi.tgl, '%F %Y') ORDER BY DATE_FORMAT(transaksi.tgl, '%Y-%m-%d') ASC");
        ?>
        <script>
            const ctz = document.getElementById('myChart');

            new Chart(ctz, {
                type: 'line',
                data: {
                    labels: [
                        <?php
                        foreach ($getPenjualanPerBulan as $penjualan) {
                            echo "'" . $penjualan['bulan'] . "',";
                        }
                        ?>
                    ],
                    datasets: [{
                        label: '# of Votes',
                        data: [
                            <?php
                            foreach ($getPenjualanPerBulan as $penjualan) {
                                echo $penjualan['total'] . ",";
                            }
                            ?>
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

        <div class="card shadow-sm p-2 mt-2">
            <h5>Rekap Omset Per Bulan</h5>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Omset</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($getPenjualanPerBulan as $penjualanBulan) { ?>
                            <tr>
                                <td><?= $penjualanBulan['bulan'] ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm">
                                        Rp <?= number_format($penjualanBulan['total'], 0, 0, '.') ?>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif ?>
    <?php if ($_SESSION['admin']['role'] === 'ceo' || $_SESSION['admin']['role'] === 'akuntansi'): ?>
        <div class="">
            <div class="row">
                <div class="col-xl-4 col-sm-6 mb-xl-0 mb-2">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">
                                            Penghasilan Hari Ini
                                        </p>
                                        <?php

                                        $getPenjualanHari = $con->query("SELECT SUM(sub_total) as hari FROM transaksi_produk JOIN transaksi ON transaksi_produk.no_nota=transaksi.no_nota WHERE DATE_FORMAT(transaksi.tgl, '%Y-%m-%d') = '" . date('Y-m-d') . "'")->fetch_assoc();

                                        $getPenjualanBulan = $con->query("SELECT SUM(sub_total) as bulan FROM transaksi_produk JOIN transaksi ON transaksi_produk.no_nota=transaksi.no_nota WHERE DATE_FORMAT(transaksi.tgl, '%Y-%m') = '" . date('Y-m') . "'")->fetch_assoc();

                                        $getPenjualanTahun = $con->query("SELECT SUM(sub_total) as tahun FROM transaksi_produk JOIN transaksi ON transaksi_produk.no_nota=transaksi.no_nota WHERE DATE_FORMAT(transaksi.tgl, '%Y') = '" . date('Y') . "'")->fetch_assoc();
                                        ?>
                                        <h5 class="font-weight-bolder">Rp <?= number_format($getPenjualanHari['hari'], 0, 0, '.') ?></h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                        <i
                                            class="bi bi-cash text-lg opacity-10"
                                            aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 mb-xl-0 mb-2">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">
                                            Penghasilan Bulan Ini
                                        </p>
                                        <h5 class="font-weight-bolder">Rp <?= number_format($getPenjualanBulan['bulan'], 0, 0, '.') ?></h5>
                                        <!-- <p class="mb-0">
                  <span class="text-success text-sm font-weight-bolder">+55%</span> ads
                </p> -->
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                        <i
                                            class="bi bi-cash text-lg opacity-10"
                                            aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 mb-xl-0 mb-2">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">
                                            Penghasilan Tahun Ini
                                        </p>
                                        <h5 class="font-weight-bolder">Rp <?= number_format($getPenjualanTahun['tahun'], 0, 0, '.') ?></h5>
                                        <!-- <p class="mb-0">
                  <span class="text-success text-sm font-weight-bolder">+55%</span> ads
                </p> -->
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                        <i
                                            class="bi bi-cash text-lg opacity-10"
                                            aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function rekapPenghasilan(key) {
                    document.location.href = 'index.php?hal=rekap-penjualan-pusat&src&key=' + key;
                }
            </script>
            <div class="card shadow-sm p-2 mt-2">
                <canvas id="myChart"></canvas>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <?php
            $getPenjualanPerBulan = $con->query("SELECT DATE_FORMAT(transaksi.tgl, '%M %Y') as bulan, DATE_FORMAT(transaksi.tgl, '%Y-%m') as bln, SUM(sub_total) as total FROM transaksi_produk JOIN transaksi ON transaksi_produk.no_nota=transaksi.no_nota GROUP BY DATE_FORMAT(transaksi.tgl, '%F %Y') ORDER BY DATE_FORMAT(transaksi.tgl, '%Y-%m-%d') ASC");
            ?>
            <script>
                const ctx = document.getElementById('myChart');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: [
                            <?php
                            foreach ($getPenjualanPerBulan as $penjualan) {
                                echo "'" . $penjualan['bulan'] . "',";
                            }
                            ?>
                        ],
                        datasets: [{
                            label: '# of Votes',
                            data: [
                                <?php
                                foreach ($getPenjualanPerBulan as $penjualan) {
                                    echo $penjualan['total'] . ",";
                                }
                                ?>
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>

            <div class="card shadow-sm p-2 mt-2">
                <h5>Rekap Omset Per Bulan</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Omset</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($getPenjualanPerBulan as $penjualanBulan) { ?>
                                <tr>
                                    <td><?= $penjualanBulan['bulan'] ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm">
                                            Rp <?= number_format($penjualanBulan['total'], 0, 0, '.') ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card shadow p-2 mt-2">
                <h5>Pembelian Obat</h5>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $getData = $con->query("SELECT DATE_FORMAT(tgl, '%M %Y') as bulan, DATE_FORMAT(tgl, '%Y-%m') as bln, SUM(total) as total FROM pembelian GROUP BY DATE_FORMAT(tgl, '%F %Y') ORDER BY DATE_FORMAT(tgl, '%Y-%m-%d') DESC");

                            foreach ($getData as $data) {
                            ?>
                                <tr>
                                    <td><?= $data['bulan'] ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm">
                                            Rp <?= number_format($data['total'], 0, 0, '.') ?>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif ?>

    <?php if ($_SESSION['admin']['role'] === 'purchasing' || $_SESSION['admin']['role'] === 'gudang'): ?>
        <div class="card shadow p-2 mt-2">
            <h5>Pembelian Obat</h5>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $getData = $con->query("SELECT DATE_FORMAT(tgl, '%M %Y') as bulan, DATE_FORMAT(tgl, '%Y-%m') as bln, SUM(total) as total FROM pembelian GROUP BY DATE_FORMAT(tgl, '%F %Y') ORDER BY DATE_FORMAT(tgl, '%Y-%m-%d') DESC");

                        foreach ($getData as $data) {
                        ?>
                            <tr>
                                <td><?= $data['bulan'] ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm">
                                        Rp <?= number_format($data['total'], 0, 0, '.') ?>
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card shadow-sm p-2 mt-2">
            <canvas id="myChart"></canvas>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <?php
        $getPenjualanPerBulan = $con->query("SELECT DATE_FORMAT(tgl, '%M %Y') as bulan, DATE_FORMAT(tgl, '%Y-%m') as bln, SUM(total) as total FROM pembelian GROUP BY DATE_FORMAT(tgl, '%F %Y') ORDER BY DATE_FORMAT(tgl, '%Y-%m-%d') ASC");
        ?>
        <script>
            const cty = document.getElementById('myChart');

            new Chart(cty, {
                type: 'line',
                data: {
                    labels: [
                        <?php
                        foreach ($getPenjualanPerBulan as $penjualan) {
                            echo "'" . $penjualan['bulan'] . "',";
                        }
                        ?>
                    ],
                    datasets: [{
                        label: '# of Votes',
                        data: [
                            <?php
                            foreach ($getPenjualanPerBulan as $penjualan) {
                                echo $penjualan['total'] . ",";
                            }
                            ?>
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    <?php endif ?>