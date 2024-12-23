<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<?php
if (isset($_GET['acc'])) {
    $id = $_GET['acc'];
    $con->query("UPDATE transaksi SET status='ACC' WHERE id_penjualan='$id'");
    echo "<script>alert('Pesanan akan diproses'); document.location.href='index.php?hal=data-penjualan-sales';</script>";
}

$id = $_SESSION['admin']['id'];
$id = mysqli_real_escape_string($con, $id);


$transaksi_produk = $con->query("SELECT transaksi.id_penjualan, transaksi.no_nota,transaksi.instansi, transaksi.status, transaksi.tgl, 
    transaksi.nama_pelanggan, transaksi.alamat, transaksi.provinsi, transaksi.kota, transaksi.kelurahan, transaksi.kecamatan, transaksi.kode_pos, transaksi.nohp, transaksi.total,transaksi_produk.nama_obat,
    transaksi_produk.jumlah,transaksi_produk.sub_total FROM transaksi LEFT JOIN transaksi_produk ON transaksi.no_nota = transaksi_produk.no_nota
    WHERE transaksi.sales_id = '$id'");

$transaksi_pembayaran = $con->query("SELECT transaksi.id_penjualan, transaksi_pembayaran.tgl_bayar, transaksi_pembayaran.nominal, 
        transaksi_pembayaran.foto FROM transaksi LEFT JOIN transaksi_pembayaran ON transaksi.id_penjualan = transaksi_pembayaran.id_penjualan
    WHERE transaksi.sales_id = '$id'");

$data = [];
while ($row = $transaksi_produk->fetch_assoc()) {
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
            'provinsi' => $row['provinsi'],
            'kota' => $row['kota'],
            'kecamatan' => $row['kecamatan'],
            'kelurahan' => $row['kelurahan'],
            'kode_pos' => $row['kode_pos'],
            'produk' => [],
            'pembayaran' => [],
        ];
    }
    $data[$id_penjualan]['produk'][] = [
        'nama_obat' => $row['nama_obat'],
        'jumlah' => $row['jumlah'],
        'sub_total' => $row['sub_total']
    ];
}
while ($row = $transaksi_pembayaran->fetch_assoc()) {
    $id_penjualan = $row['id_penjualan'];
    if (isset($data[$id_penjualan])) {  // Fix here: use $data instead of $data_transaksi
        $data[$id_penjualan]['pembayaran'][] = [
            'tgl_bayar' => $row['tgl_bayar'],
            'nominal' => $row['nominal'],
            'foto' => $row['foto']
        ];
    }
}


// Handle payment submission
if (isset($_POST['save'])) {
    $id_penjualan = $_POST['id'];
    $tgl_bayar = $_POST['tgl_bayar'];
    $nominal = str_replace('.', '', $_POST['nominal']);

    $nominal = (int)$nominal;

    $folder = "assets/foto/tagihan";
    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $foto = $_FILES['foto']['name'];
    $allowed_extensions = ["jpg", "jpeg", "png", "gif", "webp", "heic"];
    $file_extension = strtolower(pathinfo($foto, PATHINFO_EXTENSION));

    // if (!in_array($file_extension, $allowed_extensions)) {
    //     echo "<script>alert('Hanya file JPG atau PNG yang diizinkan!');</script>";
    //     exit;
    // }

    $lokasi = $_FILES['foto']['tmp_name'];
    if (!empty($foto)) {
        move_uploaded_file($lokasi, $folder . '/' . $foto);
    }

    $query_insert = "INSERT INTO transaksi_pembayaran (id_penjualan, tgl_bayar, nominal, foto) 
                        VALUES ('$id_penjualan', '$tgl_bayar', '$nominal', '$foto')";

    if ($con->query($query_insert)) {
        echo "<script>alert('Data berhasil ditambahkan'); document.location.href='index.php?hal=data-penjualan-sales';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data: " . $con->error . "');</script>";
    }
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
                                <th rowspan="2">Sisa yang harus dibayar</th>
                                <?php if ($_SESSION['admin']['role'] === 'ceo'): ?>
                                    <th rowspan="2">ACC</th>
                                <?php endif ?>
                                <?php if ($_SESSION['admin']['role'] === 'sales'): ?>
                                    <th colspan="4" style="text-align: center;">Penerimaan</th>
                                <?php endif ?>
                            </tr>
                            <tr>
                                <th>Nama Obat</th>
                                <th>Jumlah</th>
                                <th>Sub Total</th>
                                <?php if ($_SESSION['admin']['role'] === 'sales'): ?>
                                    <th>Tanggal Terima</th>
                                    <th>Nominal Terbayar</th>
                                    <th>Foto Bukti</th>
                                    <th>Aksi</th>
                                <?php endif ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $nomer = 1;
                            foreach ($data as $pecah){
                                $sisa = $pecah['total'];
                                if (!empty($pecah['pembayaran'])) {
                                    foreach ($pecah['pembayaran'] as $terima) {
                                        $sisa -= $terima['nominal'];
                                    }
                                }

                                if ($sisa == 0) {
                                    $status = 'Lunas';
                                } else {
                                    $status = 'Belum Lunas';
                                } 
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $pecah["tgl"]; ?></td>
                                    <td><?php echo $pecah["no_nota"]; ?></td>
                                    <td><?php echo $pecah["nama_pelanggan"]; ?></td>
                                    <td><?php echo $pecah['instansi'] ?></td>
                                    <td><?php echo $pecah["nohp"]; ?></td>
                                    <td>
                                        <?php
                                        echo (isset($pecah["provinsi"]) ? $pecah["provinsi"] : '') . ', ' .
                                            (isset($pecah["kota"]) ? $pecah["kota"] : '') . ', ' .
                                            (isset($pecah["kecamatan"]) ? $pecah["kecamatan"] : '') . ', ' .
                                            (isset($pecah["kelurahan"]) ? $pecah["kelurahan"] : '') . ', ' .
                                            (isset($pecah["kode_pos"]) ? $pecah["kode_pos"] : '') . ', ' .
                                            (isset($pecah["alamat"]) ? $pecah["alamat"] : '');
                                        ?>
                                    </td>

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
                                            echo htmlspecialchars($produk['jumlah']) . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($pecah['produk'] as $produk) {
                                            echo 'Rp' . number_format($produk['sub_total'], 0, ',', '.')
                                                . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td><b>Rp <?= number_format($pecah["total"], 0, '', '.') ?></b></td>
                                    <td><?php echo $pecah["status"]; ?></td>
                                    <td style="color: red;"><b>Rp <?= number_format($sisa, 0, ',', '.') ?></b></td>
                                    <?php if ($_SESSION['admin']['role'] === 'ceo' && $pecah["status"] === 'Diproses'): ?>
                                        <td>
                                            <a href="index.php?hal=data-penjualan-sales&acc=<?php echo $id_penjualan; ?>" class="btn btn-success">
                                                ACC
                                            </a>
                                        </td>
                                    <?php endif ?>
                                    <?php if ($_SESSION['admin']['role'] === 'ceo' && $pecah["status"] === 'ACC'): ?>
                                        <td>
                                            <h6 style="color: red;">SUDAH ACC</h6>
                                        </td>
                                    <?php endif ?>

                                    <?php if ($_SESSION['admin']['role'] === 'sales'): ?>
                                        <td>
                                            <?php
                                            foreach ($pecah['pembayaran'] as $pembayaran) {
                                                echo htmlspecialchars($pembayaran['tgl_bayar']) . '<br>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            foreach ($pecah['pembayaran'] as $pembayaran) {
                                                echo "Rp " . htmlspecialchars(number_format($pembayaran['nominal'], 0, ',', '.')) . '<br>';
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                            foreach ($pecah['pembayaran'] as $pembayaran) {
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
                                                data-id="<?php echo htmlspecialchars($id_penjualan); ?>"
                                                data-tgl="<?php echo htmlspecialchars($pecah['tgl']); ?>"
                                                data-sisa="<?php echo htmlspecialchars($sisa); ?>">
                                                Bayar
                                            </button>
                                        </td>
                                    <?php endif ?>
                                </tr>
                            <?php } ?>
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
                                    <input type="date" class="form-control" name="tgl" id="tgl" readonly>
                                </div>
                                <div>
                                    <label for="tgl_bayar" class="form-label">Tanggal Bayar</label>
                                    <input type="date" class="form-control" name="tgl_bayar" id="tgl_bayar" required>
                                </div>
                                <div>
                                    <label for="total" class="form-label">Yang harus dibayar</label>
                                    <input type="text" class="form-control" name="total" id="total" readonly>
                                </div>

                                <div>
                                    <label for="nominal" class="form-label">Nominal</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp.</span>
                                        <input type="text" class="form-control" name="nominal" id="nominal" oninput="formatNumber(this)" required>
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

            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable();

                    $('.btn-add').on('click', function() {
                        $('#id_penjualan').val($(this).data('id'));
                        $('#tgl').val($(this).data('tgl'));
                        $('#tgl_bayar').val('<?php echo date('Y-m-d'); ?>');
                        $('#total').val('Rp ' + new Intl.NumberFormat('id-ID').format($(this).data('sisa')));
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