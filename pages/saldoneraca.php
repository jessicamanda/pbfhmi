<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<?php
    $tanggal = '0000-00-00';

    if (isset($_POST['filter'])) {
        $tanggal = $_POST['date'];
    }


?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h4>Saldo Neraca</h4>
                    <form action="" method="POST">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-5">
                                <label for="start_date" class="form-label">Sampai dengan Tanggal</label>
                                <input type="date" id="start_date" name="date" class="form-control" value="<?= isset($_GET['date']) ? $_GET['date'] : ''; ?>" required>
                            </div>

                            <div class="col-md-2 d-flex justify-content-end align-items-end">
                                <button style="margin-top: 2.8rem;" type="submit" class="btn btn-primary btn-sm" name="filter">Tampilkan</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card shadow p-3">
                    <h6 style="margin-left: 13px;"> Saldo Neraca Sampai dengan Tanggal: <?= $tanggal ?></h6>
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
                                    if (isset($_POST['filter'])) {
                                        $tanggal = $_POST['date'];
                                        
                                        $query = "SELECT namaakun, sum(debet) as rpd, sum(kredit) as rpk, nomor 
                                                  FROM transaksibaru 
                                                  JOIN akun ON transaksibaru.namaakun=akun.akun 
                                                  WHERE tgl <= '$tanggal'
                                                  AND nomor < 400
                                                  GROUP BY namaakun 
                                                  ORDER BY nomor ASC";
                                        
                                        $result = $conakuntansi->query($query);

                                        $total2 = 0;
                                        
                                        while ($pecah = $result->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><a href="index.php?hal=transaksineraca&akun=<?= $pecah['namaakun'] ?>"><?= $pecah['namaakun'] ?></a></td>
                                        <td><?= $pecah['nomor'] ?></td>
                                        <td><?= number_format($pecah['rpd'], 0, ',', '.') ?></td>
                                        <td><?= number_format($pecah['rpk'], 0, ',', '.') ?></td>
                                        <td><?= number_format($total = $pecah['rpd'] - $pecah['rpk'], 0, ',', '.') ?></td>
                                    </tr>                                 
                                    <?php $total = $pecah['rpd']-$pecah['rpk'] ?>
                                    <?php $total2 += $total ?>
                                <?php } ?>
                                <tr>
                                    <td colspan="4"><strong>Total</strong></td>
                                    <td><?= number_format($total2, 0, ',', '.') ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
