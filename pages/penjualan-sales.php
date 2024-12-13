<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>

<?php
if (isset($_POST['save'])) {
    $tgl = htmlspecialchars($_POST['tgl']);
    $no_nota = htmlspecialchars($_POST['no_nota']);
    $nama_pelanggan = htmlspecialchars($_POST['nama_pelanggan']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $instansi = htmlspecialchars($_POST['instansi']);
    $status = 'Diproses';
    $total = array_sum(array_column($_SESSION['keranjang'], 'sub_total'));
    $nohp = htmlspecialchars($_POST['nohp']);

    foreach ($_SESSION['keranjang'] as $item) {
        $nama_obat = $item['nama_obat'];
        $jumlah = $item['jumlah'];

        $stok_query = $con->query("SELECT stok FROM stok WHERE nama_obat = '$nama_obat'");
        $stok_data = $stok_query->fetch_assoc();
        
        if ($stok_data && $stok_data['stok'] < $jumlah) {
            echo "<script>alert('Stok tidak mencukupi untuk $nama_obat!'); window.location.href='index.php?hal=penjualan-sales';</script>";
            exit;
        }
    }

    $con->query("INSERT INTO transaksi (tgl, no_nota, nama_pelanggan, instansi, nohp, alamat, total, status) VALUES ('$tgl', '$no_nota', '$nama_pelanggan', '$instansi', '$nohp', '$alamat', '$total', '$status')");

    foreach ($_SESSION['keranjang'] as $item) {
        $nama_obat = $item['nama_obat'];
        $sub_total = $item['sub_total'];
        $jumlah = $item['jumlah'];
        $con->query("INSERT INTO transaksi_produk (no_nota, nama_obat, jumlah, sub_total) VALUES ('$no_nota', '$nama_obat','$jumlah', '$sub_total')");

        $con->query("UPDATE stok SET stok = stok - $jumlah WHERE nama_obat = '$nama_obat'");
    }

    unset($_SESSION['keranjang']);

    echo "<script>alert('Transaksi berhasil disimpan'); document.location.href='index.php?hal=data-penjualan-sales';</script>";
}


if (isset($_POST['keranjang'])) {
    $nama_obat = $_POST['nama_obat'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];
    $sub_total = $_POST['sub_total'];

    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    $_SESSION['keranjang'][] = compact('nama_obat', 'harga', 'jumlah', 'sub_total');
}

if (isset($_POST['reset_keranjang'])) {
    unset($_SESSION['keranjang']);
    echo "<script>alert('Keranjang telah direset'); document.location.href='index.php?hal=penjualan-sales';</script>";
}

$total = 0;
if (!empty($_SESSION['keranjang'])) {
    $total = array_sum(array_column($_SESSION['keranjang'], 'sub_total'));
}

if (!isset($_SESSION['keranjang']) || !is_array($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}


?>

<div class="container-fluid">
    <form action="" method="post">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card shadow p-2 mt-2">
                        <div class="">
                            <label for="tgl" class="form-label">Tanggal Penjualan</label>
                            <input type="text" class="form-control" name="tgl" value="<?= htmlspecialchars(date('Y-m-d')); ?>" readonly>
                        </div>
                        <div class="">
                            <label for="no_nota" class="form-label">Nomor Nota</label>
                            <input type="number" class="form-control" name="no_nota" value="<?= htmlspecialchars(date('Ymdhis')); ?>" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card shadow p-2 mt-2">
                        <div class="mb-3">
                            <label for="nama_obat" class="form-label">Nama Obat</label>
                            <select name="nama_obat" class="form-control" id="nama_obat" required>
                                <option value="" disabled selected>Pilih Nama Obat</option>
                                <?php
                                $ambil = $con->query("SELECT * FROM pembelian JOIN stok on pembelian.nama_obat=stok.nama_obat");
                                while ($pecah = $ambil->fetch_assoc()) {
                                ?>
                                    <option value="<?php echo $pecah['nama_obat']; ?>" data-stok="<?php echo $pecah['stok']; ?>" data-harga="<?php echo $pecah['harga']; ?>"><?php echo $pecah['nama_obat']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" name="harga" class="form-control" id="harga" readonly>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="jumlah" class="form-label">Jumlah</label>
                                    <input type="number" name="jumlah" class="form-control" id="jumlah" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="stok" class="form-label">Stok</label>
                                    <input type="number" name="stok" class="form-control" id="stok" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="sub_total" class="form-label">Sub Harga</label>
                            <input type="text" name="sub_total" class="form-control" id="sub_total" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary" name="keranjang">Tambah Ke Keranjang</button>
    </form>
    <form action="" method="post">
        <button type="submit" class="btn btn-danger" name="reset_keranjang">Reset Keranjang</button>
    </form>

    <table id="cart_table" class="display mt-4 mb-4">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['keranjang'] as $index => $item): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $item['nama_obat'] ?></td>
                    <td><?= $item['jumlah'] ?></td>
                    <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($item['sub_total'], 0, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4">Total</td>
                <td><?= $total ?></td>
            </tr>
        </tfoot>
    </table>
</div>
</div>
</div>
</div>

<form action="" method="post">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card shadow p-2 mt-2">
                    <input type="text" hidden class="form-control" name="tgl" value="<?= htmlspecialchars(date('Y-m-d')); ?>">
                    <input type="number" hidden class="form-control" name="no_nota" value="<?= htmlspecialchars(date('Ymdhis')); ?>">
                    <div class="">
                        <label for="" class="form-label">Nama Pelanggan</label>
                        <div class="input-group">
                            <input type="text" name="nama_pelanggan" class="form-control" id="nama_pelanggan">
                        </div>
                    </div>
                    <div class="">
                        <label for="" class="form-label">Instansi</label>
                        <div class="input-group">
                            <input type="text" name="instansi" class="form-control" id="instansi">
                        </div>
                    </div>
                    <div class="">
                        <label for="" class="form-label">No HP Pelanggan</label>
                        <div class="input-group">
                            <input type="text" name="nohp" class="form-control" id="nohp">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="" class="form-label">Alamat Pelanggan</label>
                        <div class="input-group">
                            <textarea class="form-control" rows="4" name="alamat" id="alamat"></textarea>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-4" name="save">Simpan Transaksi</button>
            </div>
        </div>
    </div>
</form>


</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
    document.getElementById('nama_obat').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        document.getElementById('harga').value = selectedOption.getAttribute('data-harga');
        document.getElementById('stok').value = selectedOption.getAttribute('data-stok');
    });

    $(document).ready(function() {
        $('#cart_table').DataTable();
    });

    document.getElementById('jumlah').addEventListener('input', function() {
        const harga = parseFloat(document.getElementById('harga').value) || 0;
        const jumlah = parseInt(this.value) || 0;
        document.getElementById('sub_total').value = harga * jumlah;
    });
</script>