<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<?php
$tanggal_awal = '0000-00-00';
$tanggal_akhir = '0000-00-00';
$page = 1;
$total_pages = 0;

if (isset($_POST['filter'])) {
    echo "
        <script>
            document.location.href = 'index.php?hal=rekapbukubesar&filter&start_date=$_POST[start_date]&end_date=$_POST[end_date]';
        </script>
    ";
}

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h4>Rekap Buku Besar</h4>
                    <form action="" method="POST">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-5">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>" required>
                            </div>

                            <div class="col-md-5">
                                <label for="end_date" class="form-label">Sampai dengan</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" required>
                            </div>

                            <div class="col-md-2 d-flex justify-content-end align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm" name="filter" style="margin-top: 2.8rem;">Tampilkan</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card shadow p-3">
                    <h6 style="margin-left: 13px;">Rekap Buku Besar Dari Tanggal <?= $tanggal_awal ?> Sampai dengan <?= $tanggal_akhir ?></h6>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Akun</th>
                                    <th>Nomor</th>
                                    <th>Debet</th>
                                    <th>Kredit</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($_GET['filter'])) {
                                    $tanggal_awal = $_GET['start_date'];
                                    $tanggal_akhir = $_GET['end_date'];

                                    $query = "SELECT namaakun, SUM(debet) AS rpd, SUM(kredit) AS rpk, nomor
                                            FROM transaksibaru 
                                            JOIN akun ON transaksibaru.namaakun = akun.akun
                                            WHERE tgl BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
                                            GROUP BY namaakun 
                                            ORDER BY nomor ASC";

                                    $urlPage = "index.php?hal=rekapbukubesar&filter&start_date=$tanggal_awal&end_date=$tanggal_akhir";

                                    // Pagination parameters
                                    $limit = 45; // Number of entries per page
                                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                    $start = ($page - 1) * $limit;

                                    // Get total number of records for the query
                                    $result = $conakuntansi->query($query);
                                    $total_records = $result->num_rows;

                                    // Calculate total pages
                                    $total_pages = ceil($total_records / $limit);

                                    // Fetch limited data
                                    $queryWithLimit = $query . " LIMIT $start, $limit";
                                    $result = $conakuntansi->query($queryWithLimit);

                                    $totalQuery = "SELECT SUM(debet) AS total_debet, SUM(kredit) AS total_kredit FROM transaksibaru WHERE tgl BETWEEN '$tanggal_awal' AND '$tanggal_akhir'";
                                    $totalResult = $conakuntansi->query($totalQuery)->fetch_assoc();
                                    $totalAkumulasi = $totalResult['total_debet'] - $totalResult['total_kredit'];

                                    while ($pecah = $result->fetch_assoc()) {
                                ?>
                                        <tr>
                                            <td><?= $pecah['namaakun'] ?></td>
                                            <td><?= $pecah['nomor'] ?></td>
                                            <td><?= number_format($pecah['rpd'], 0, ',', '.') ?></td>
                                            <td><?= number_format($pecah['rpk'], 0, ',', '.') ?></td>
                                            <td><?= number_format($total = $pecah['rpd'] - $pecah['rpk'], 0, ',', '.') ?></td>
                                        </tr>
                                    <?php } ?>
                                    <tr>
                                        <td colspan="4"><strong>Total</strong></td>
                                        <td><?= number_format($totalAkumulasi, 0, ',', '.') ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <center class="mt-2">
                        <?php
                        // Display pagination
                        echo '<nav>';
                        echo '<ul class="pagination justify-content-center">';

                        // Back button
                        if ($page > 1) {
                            echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page - 1) . '">Back</a></li>';
                        }

                        // Determine start and end pages for pagination
                        $start_page = max(1, $page - 2);
                        $end_page = min($total_pages, $page + 2);

                        if ($start_page > 1) {
                            echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=1">1</a></li>';
                            if ($start_page > 2) {
                                echo '<li class="page-item"><span class="page-link">...</span></li>';
                            }
                        }

                        // Show page numbers
                        for ($i = $start_page; $i <= $end_page; $i++) {
                            if ($i == $page) {
                                echo '<li class="page-item"><span class="page-link active" style="color: blue;">' . $i . '</span></li>';
                            } else {
                                echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $i . '">' . $i . '</a></li>';
                            }
                        }

                        if ($end_page < $total_pages) {
                            if ($end_page < $total_pages - 1) {
                                echo '<li class="page-item"><span class="page-link">...</span></li>';
                            }
                            echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . $total_pages . '">' . $total_pages . '</a></li>';
                        }

                        // Next button
                        if ($page < $total_pages) {
                            echo '<li class="page-item"><a class="page-link" href="' . $urlPage . '&page=' . ($page + 1) . '">Next</a></li>';
                        }

                        echo '</ul>';
                        echo '</nav>';
                        ?>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
