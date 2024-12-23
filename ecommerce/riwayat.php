<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<?php include "../koneksi.php"; ?>

<?php
$nama_lengkap = $_SESSION['admin']['nama_lengkap'];
$nama_lengkap = mysqli_real_escape_string($con, $nama_lengkap);

$transaksi_produk = $con->query("SELECT transaksi.id_penjualan, transaksi.no_nota,transaksi.instansi, transaksi.status, transaksi.tgl, 
    transaksi.nama_pelanggan, transaksi.alamat, transaksi.nohp, transaksi.total,transaksi_produk.nama_obat,
    transaksi_produk.jumlah,transaksi_produk.sub_total FROM transaksi LEFT JOIN transaksi_produk ON transaksi.no_nota = transaksi_produk.no_nota
    WHERE transaksi.nama_pelanggan = '$nama_lengkap'");

$transaksi_pembayaran = $con->query("SELECT transaksi.id_penjualan, transaksi_pembayaran.tgl_bayar, transaksi_pembayaran.nominal, 
        transaksi_pembayaran.foto FROM transaksi LEFT JOIN transaksi_pembayaran ON transaksi.id_penjualan = transaksi_pembayaran.id_penjualan
    WHERE transaksi.nama_pelanggan = '$nama_lengkap'");


$data_transaksi = [];
while ($row = $transaksi_produk->fetch_assoc()) {
    $id_penjualan = $row['id_penjualan'];
    if (!isset($data_transaksi[$id_penjualan])) {
        $data_transaksi[$id_penjualan] = [
            'id_penjualan' => $row['id_penjualan'],
            'no_nota' => $row['no_nota'],
            'instansi' => $row['instansi'],
            'status' => $row['status'],
            'tgl' => $row['tgl'],
            'nama_pelanggan' => $row['nama_pelanggan'],
            'alamat' => $row['alamat'],
            'nohp' => $row['nohp'],
            'total' => $row['total'],
            'produk' => [],
            'pembayaran' => []
        ];
    }
    $data_transaksi[$id_penjualan]['produk'][] = [
        'nama_obat' => $row['nama_obat'],
        'jumlah' => $row['jumlah'],
        'sub_total' => $row['sub_total']
    ];
}

while ($row = $transaksi_pembayaran->fetch_assoc()) {
    $id_penjualan = $row['id_penjualan'];
    if (isset($data_transaksi[$id_penjualan])) {
        $data_transaksi[$id_penjualan]['pembayaran'][] = [
            'tgl_bayar' => $row['tgl_bayar'],
            'nominal' => $row['nominal'],
            'foto' => $row['foto']
        ];
    }
}



?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Riwayat Pembelian <?php echo $nama_lengkap ?></h6>
            </div>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table id="myTable" class="table display">
                        <thead>
                            <tr>
                                <th rowspan="2">Tanggal</th>
                                <th rowspan="2">No Nota</th>
                                <th rowspan="2">Instansi</th>
                                <th rowspan="2">Alamat</th>
                                <th colspan="3" style="text-align: center;">Produk</th>
                                <th rowspan="2">Total</th>
                                <th rowspan="2">Sisa Yang Harus Dibayar</th>
                                <th rowspan="2">Status</th>
                                <th colspan="3" style="text-align: center;">Pembayaran</th>
                                <th rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                <th>Nama Obat</th>
                                <th>Jumlah</th>
                                <th>Sub Total</th>
                                <th>Tgl Bayar</th>
                                <th>Nominal Bayar</th>
                                <th>Foto Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data_transaksi as $transaksi):
                                $sisa = $transaksi['total'];
                                if (!empty($transaksi['pembayaran'])) {
                                    foreach ($transaksi['pembayaran'] as $terima) {
                                        $sisa -= $terima['nominal'];
                                    }
                                }

                                if ($sisa == 0) {
                                    $status = 'Lunas';
                                } else {
                                    $status = 'Belum Lunas';
                                } ?>
                                <tr>
                                    <td><?= $transaksi['tgl'] ?></td>
                                    <td><?= $transaksi['no_nota'] ?></td>
                                    <td><?= $transaksi['instansi'] ?></td>
                                    <td><?= $transaksi['alamat'] ?></td>
                                    <td>
                                        <?php foreach ($transaksi['produk'] as $produk): ?>
                                            <?= $produk['nama_obat'] ?><br>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($transaksi['produk'] as $produk): ?>
                                            <?= $produk['jumlah'] ?><br>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($transaksi['produk'] as $produk): ?>
                                            Rp <?= number_format($produk['sub_total'], 0, '', '.') ?><br>
                                        <?php endforeach; ?>
                                    </td>
                                    <td><b>Rp <?= number_format($transaksi['total'], 0, '', '.') ?></b></td>
                                    <td style="color: red;"><b>Rp <?= number_format($sisa, 0, '', '.') ?></b></td>
                                    <td><?= $transaksi['status'] ?></td>
                                    <td>
                                        <?php foreach ($transaksi['pembayaran'] as $pembayaran): ?>
                                            <?= $pembayaran['tgl_bayar'] ?><br>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?php foreach ($transaksi['pembayaran'] as $pembayaran): ?>
                                            Rp <?= number_format($pembayaran['nominal'], 0, '', '.') ?><br>
                                        <?php endforeach; ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($transaksi['pembayaran'] as $pembayaran) {
                                            if (!empty($pembayaran['foto'])) {
                                                $fotoPath = 'assets/foto/tagihan/' . htmlspecialchars($pembayaran['foto']);
                                                echo '<a href="' . $fotoPath . '" target="_blank">Lihat Foto</a><br>';
                                            }
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-add"
                                            data-bs-toggle="modal"
                                            data-bs-target="#terima"
                                            data-id="<?= $transaksi['id_penjualan'] ?>"
                                            data-tgl="<?= $transaksi['tgl'] ?>"
                                            data-sisa="<?= $sisa ?>">
                                            Bayar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>
            <div class="modal fade" id="terima" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Bayar</h5>
                        </div>
                        <form action="" method="post" id="terima_form" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" class="form-control" name="id" id="id_penjualan" readonly>
                                <div>
                                    <label for="tgl" class="form-label">Tanggal Order</label>
                                    <input type="text" class="form-control" name="tgl" id="tgl" readonly>
                                </div>
                                <div>
                                    <label for="tgl_bayar" class="form-label">Tanggal Bayar</label>
                                    <input type="date" class="form-control" name="tgl_bayar" id="tgl_bayar" required>
                                </div>
                                <div>
                                    <label for="sisa" class="form-label">Yang harus dibayar</label>
                                    <input type="text" class="form-control" name="sisa" id="sisa" readonly>
                                </div>

                                <div>
                                    <label for="nominal" class="form-label">Nominal</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp.</span>
                                        <input type="text" class="form-control" oninput="formatNumber(this)" name="nominal" id="nominal" required>
                                    </div>
                                </div>
                                <div class="">
                                    <label for="margin" class="form-label">Foto Bukti</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" name="foto" id="foto">
                                        <input type="hidden" name="existing_foto" value="<?php echo $foto; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="save">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php
            if (isset($_POST['save'])) {
                $nominal = str_replace('.', '', $_POST['nominal']);

                $nominal = (int)$nominal;
                $id_penjualan = $_POST['id'];
                $tgl_bayar = $_POST['tgl_bayar'];

                $folder = "assets/foto/tagihan";
                if (!is_dir($folder)) {
                    mkdir($folder, 0755, true);
                }

                $foto = $_FILES['foto']['name'];
                $allowed_extensions = ["jpg", "jpeg", "png", "gif", "webp", "heic"];
                $file_extension = strtolower(pathinfo($foto, PATHINFO_EXTENSION));

                $lokasi = $_FILES['foto']['tmp_name'];
                if (!empty($foto)) {
                    move_uploaded_file($lokasi, $folder . '/' . $foto);
                }

                if (empty($id_penjualan)) {
                    echo "<script>alert('ID Penjualan tidak ditemukan.');</script>";
                } else {
                    $query_insert = "INSERT INTO transaksi_pembayaran (id_penjualan, tgl_bayar, nominal, foto) 
                                     VALUES ('$id_penjualan', '$tgl_bayar', '$nominal', '$foto')";
                }

                if ($con->query($query_insert)) {
                    echo "<script>alert('Data berhasil ditambahkan'); document.location.href='index.php?halaman=riwayat';</script>";
                } else {
                    echo "<script>alert('Gagal menyimpan data: " . $con->error . "');</script>";
                }
            }
            ?>
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable({
                        "paging": true,
                        "searching": true,
                        "info": true
                    });
                });
            </script>
            <script>
                $.fn.dataTable.ext.errMode = 'none';

                $(document).ready(function() {
                    $('.btn-add').on('click', function() {
                        $('#id_penjualan').val($(this).data('id'));
                        $('#tgl').val($(this).data('tgl'));
                        $('#tgl_bayar').val('<?php echo date('Y-m-d'); ?>');
                        $('#sisa').val('Rp ' + new Intl.NumberFormat('id-ID').format($(this).data('sisa')));
                    });
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