<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>

<?php
if (isset($_GET['acc'])) {
    $id = $_GET['acc'];
    $con->query("UPDATE transaksi SET status='ACC' WHERE id_penjualan='$id'");
    echo "<script>alert('Pesanan akan diproses'); document.location.href='index.php?hal=data-penjualan-sales';</script>";
}

$ambil = $con->query("SELECT 
    transaksi.id_penjualan, transaksi.no_nota,transaksi.instansi, transaksi.status, transaksi.tgl, transaksi.nama_pelanggan, transaksi.alamat, transaksi.nohp, 
    transaksi.total, transaksi_produk.nama_obat, transaksi_produk.jumlah, transaksi_produk.sub_total FROM transaksi
    LEFT JOIN transaksi_produk ON transaksi.no_nota = transaksi_produk.no_nota
    ");

$data = [];
while ($row = $ambil->fetch_assoc()) {
    $id_penjualan = $row['id_penjualan'];
    if (!isset($data[$id_penjualan])) {
        $data[$id_penjualan] = [
            'tgl' => $row['tgl'],
            'no_nota' => $row['no_nota'],
            'nama_pelanggan' => $row['nama_pelanggan'],
            'nohp' => $row['nohp'],
            'alamat' => $row['alamat'],
            'instansi' => $row['instansi'],
            'status' => $row['status'],
            'total' => $row['total'],
            'transaksi_produk' => [],
        ];
    }
    $data[$id_penjualan]['transaksi_produk'][] = [
        'nama_obat' => $row['nama_obat'],
        'jumlah' => $row['jumlah'],
        'sub_total' => $row['sub_total'],
    ];
}

?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Daftar Penjualan</h6>
            </div>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table id="myTable" class="display">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Tanggal</th>
                                <th rowspan="2">No Nota</th>
                                <th rowspan="2">Nama Pembeli</th>
                                <th rowspan="2">Instansi</th>
                                <th rowspan="2">No HP</th>
                                <th rowspan="2">Alamat</th>
                                <th colspan="3" style="text-align: center;">Produk</th>
                                <th rowspan="2">Total</th>
                                <th rowspan="2">Status</th>
                                <?php if ($_SESSION['admin']['role'] === 'ceo'): ?>
                                <th rowspan="2">ACC</th>
                                <?php endif?>

                            </tr>
                            <tr>
                                <th>Nama Obat</th>
                                <th>Jumlah</th>
                                <th>Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $nomer = 1;
                            foreach ($data as $id_penjualan => $pecah) {
                            ?>
                                <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $pecah["tgl"]; ?></td>
                                    <td><?php echo $pecah["no_nota"]; ?></td>
                                    <td><?php echo $pecah["nama_pelanggan"]; ?></td>
                                    <td><?php echo $pecah['instansi'] ?></td>
                                    <td><?php echo $pecah["nohp"]; ?></td>
                                    <td><?php echo $pecah["alamat"]; ?></td>

                                    <td>
                                        <?php
                                        foreach ($pecah['transaksi_produk'] as $transaksi_produk) {
                                            echo htmlspecialchars($transaksi_produk['nama_obat']) . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($pecah['transaksi_produk'] as $transaksi_produk) {
                                            echo htmlspecialchars($transaksi_produk['jumlah']) . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($pecah['transaksi_produk'] as $transaksi_produk) {
                                            echo $transaksi_produk['sub_total'] . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td><b>Rp <?= number_format($pecah["total"], 0, '', '.') ?></b></td>
                                    <td><?php echo $pecah["status"]; ?></td>
                                    <?php if ($_SESSION['admin']['role'] === 'ceo'): ?>

                                    <td>
    <a href="index.php?hal=data-penjualan-sales&acc=<?php echo $id_penjualan; ?>" class="btn btn-success">
        ACC
    </a>
</td>
<?php endif?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable();
                });
            </script>
        </div>
    </div>
</div>