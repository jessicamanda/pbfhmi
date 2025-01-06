<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<?php

$pembelian = $con->query("SELECT * FROM pembelian");

$data = [];
while ($row = $pembelian->fetch_assoc()) {
    $no_nota = $row['no_nota'];
    if (!isset($data[$no_nota])) {
        $data[$no_nota] = [
            'id_pembelian' => $row['id_pembelian'],
            'tgl' => $row['tgl'],
            'no_nota' => $row['no_nota'],
            'tipe' => $row['tipe'],
            'jatuh_tempo' => $row['jatuh_tempo'],
            'no_batch' => $row['no_batch'],
            'status' => $row['status'],
            'produk' => [],
        ];
    }
    $data[$no_nota]['produk'][] = [
        'nama_obat' => $row['nama_obat'],
        'namasuplier' => $row['namasuplier'],
        'nohp' => $row['nohp'],
        'harga' => $row['harga'],
        'jumlah' => $row['jumlah'],
        'ppn' => $row['ppn'],
        'total' => $row['total'],
        'harga_jual' => $row['harga_jual'],
        'tgl_exp' => $row['tgl_exp'],

    ];
}

?>

<style>
    th,
    td {
        text-align: center;
        vertical-align: middle;
        padding: 8px;
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Daftar Pembelian</h6>
            </div>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table id="myTable" class="display">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Tanggal</th>
                                <th rowspan="2">No Nota</th>
                                <th colspan="9" style="text-align: center;">Produk</th>
                                <th rowspan="2">Total</th>
                                <th rowspan="2">Jatuh Tempo</th>
                                <th rowspan="2">Status</th>
                                <th rowspan="2">Print PO</th>
                            </tr>
                            <tr>
                                <th>Nama Obat</th>
                                <th>Nama Suplier</th>
                                <th>No HP</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>PPN</th>
                                <th>Sub Total</th>
                                <th>Harga Jual</th>
                                <th>Tanggal Expired</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $nomer = 1;
                            $total=0;
                            foreach ($data as $pecah) {
                                foreach ($pecah['produk'] as $produk) {
                                    $total += $produk['total'];

                                }
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $pecah["tgl"]; ?></td>
                                    <td><?php echo $pecah["no_nota"]; ?></td>
                                    <td>
                                        <?php
                                        foreach ($pecah['produk'] as $produk) {
                                            echo htmlspecialchars($produk['nama_obat']) . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($pecah['produk'] as $produk) {
                                            echo htmlspecialchars($produk['namasuplier']) . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($pecah['produk'] as $produk) {
                                            echo htmlspecialchars($produk['nohp']) . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($pecah['produk'] as $produk) {
                                            echo htmlspecialchars($produk['jumlah']) . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($pecah['produk'] as $produk) {
                                            echo htmlspecialchars($produk['harga']) . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($pecah['produk'] as $produk) {
                                            echo htmlspecialchars($produk['ppn']) . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($pecah['produk'] as $produk) {
                                            echo 'Rp' . number_format($produk['total'], 0, ',', '.')
                                                . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($pecah['produk'] as $produk) {
                                            echo 'Rp' . number_format($produk['harga_jual'], 0, ',', '.')
                                                . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($pecah['produk'] as $produk) {
                                            echo htmlspecialchars($produk['tgl_exp']) . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td><b>Rp <?= number_format($total, 0, '', '.') ?></b></td>
                                    <td><b><?= $pecah["jatuh_tempo"] ?></b></td>
                                    <td><?php echo $pecah["status"]; ?></td>
                                    <td> <a href="pages/print-po.php?print&no_nota=<?php echo $pecah['no_nota']; ?>" target="_blank" class="btn btn-success" style="background-color: #3b7c47;">
                                            <i class="bi bi-printer-fill mr-2"></i> </a></td>
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

                function formatNumber(input) {
                    let value = input.value.replace(/\D/g, '');
                    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                    input.value = value;
                }
            </script>
        </div>
    </div>
</div>