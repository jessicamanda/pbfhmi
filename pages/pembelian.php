<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>

<?php
$session_id = session_id();

if (isset($_POST['save'])) {
    $tgl = htmlspecialchars($_POST['tgl']);
    $jatuh_tempo = htmlspecialchars($_POST['jatuh_tempo']);
    $no_batch = htmlspecialchars($_POST['no_batch']);
    $tipe = 'Non Pajak';
    $status = 'Belum Datang';
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');
    $no_nota = htmlspecialchars(date('Ymdhis'));
    $suplier = htmlspecialchars($_POST['suplier']);
    $namasuplier = htmlspecialchars($_POST['namasuplier']);
    $nohp = htmlspecialchars($_POST['nohp']);
    $nomorhp = htmlspecialchars($_POST['nomorhp']);
    $final_suplier = !empty($suplier) ? $suplier : $namasuplier;
    $final_nohp = !empty($nohp) ? $nohp : $nomorhp;

    $result = $con->query("SELECT * FROM cart WHERE session_id = '$session_id'");
    while ($row = $result->fetch_assoc()) {
        $con->query("INSERT INTO pembelian (no_nota, tgl, nama_obat, namasuplier, nohp, harga, ppn, total, tgl_exp, no_batch, tipe, harga_jual,status, jumlah, jatuh_tempo, created_at, updated_at) 
                     VALUES ('$no_nota', '$tgl','{$row['nama_obat']}', '$final_suplier', '$final_nohp', '{$row['harga']}', '{$row['ppn']}', '{$row['total']}', '{$row['tgl_exp']}', '$no_batch', '$tipe', '{$row['harga_jual']}', '$status', '{$row['jumlah']}', '$jatuh_tempo', '$created_at', '$updated_at')");
    }
    $con->query("DELETE FROM cart WHERE session_id = '$session_id'");
    if ($con) {
        echo "<script>alert('Data berhasil ditambahkan'); document.location.href='index.php?hal=data-purchasing';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data: " . $con->error . "'); document.location.href='index.php?hal=pembelian';</script>";
    }
}


if (isset($_POST['add'])) {
    $harga = str_replace('.', '', $_POST['harga']);
    $total = str_replace('.', '', $_POST['total']);
    $harga_jual = str_replace('.', '', $_POST['harga_jual']);

    $harga = (int)$harga;
    $total = (int)$total;
    $harga_jual = (int)$harga_jual;

    $nama_obat = htmlspecialchars($_POST['nama_obat']);
    $jumlah = htmlspecialchars($_POST['jumlah']);
    $ppn = htmlspecialchars($_POST['ppn']);
    $tgl_exp = htmlspecialchars($_POST['tgl_exp']);
    $created_at = date('Y-m-d H:i:s');

    // if ($mode == "add") {
    $query = "INSERT INTO cart 
                    (session_id, nama_obat, harga, ppn, total, tgl_exp, harga_jual, jumlah, created_at) 
                  VALUES 
                    ('$session_id','$nama_obat','$harga', '$ppn', '$total', '$tgl_exp','$harga_jual','$jumlah', '$created_at')";
    if ($con->query($query)) {
        echo "<script>alert('Data berhasil ditambahkan ke keranjang'); document.location.href='index.php?hal=pembelian';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data: " . $con->error . "'); document.location.href='index.php?hal=pembelian';</script>";
    }
}

if (isset($_GET['deletecart'])) {
    $id = htmlspecialchars($_GET['deletecart']);
    $query = $con->query("DELETE FROM cart WHERE id='$id'");

    if ($query) {
        echo "<script>alert('Data berhasil dihapus'); document.location.href='index.php?hal=pembelian';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . $con->error . "'); document.location.href='index.php?hal=pembelian';</script>";
    }
}

if (isset($_GET['delete'])) {
    $id = htmlspecialchars($_GET['delete']);
    $query = $con->query("DELETE FROM pembelian WHERE id_pembelian='$id'");

    if ($query) {
        echo "<script>alert('Data berhasil dihapus'); document.location.href='index.php?hal=pembelian';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . $con->error . "'); document.location.href='index.php?hal=pembelian';</script>";
    }
}

?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card shadow p-2 mt-2">
                    <form action="" method="post" id="tambah_edit_form">
                        <div class="modal-body">
                            <div class="">
                                <label for="" class="form-label">Tanggal</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="tgl" id="tgl" value="<?= htmlspecialchars(date('Y-m-d')); ?>" required>
                                </div>
                            </div>

                            <div class="">
                                <label for="nama_obat" class="form-label">Obat</label>
                                <div class="input-group">
                                    <select name="nama_obat" class="form-control" id="nama_obat" required>
                                        <option value="" disabled selected>Pilih Nama Obat</option>
                                        <?php
                                        $ambil = $con->query("SELECT * FROM obat");
                                        while ($pecah = $ambil->fetch_assoc()) {
                                            $selected = ($pecah['nama_obat'] == $nama_obat) ? 'selected' : '';
                                            echo '<option value="' . $pecah['nama_obat'] . '" ' . $selected . ' data-margin="' . $pecah['margin'] . '">' . $pecah['nama_obat'] . '</option>';
                                        }
                                        ?>

                                    </select>

                                </div>
                            </div>

                        </div>
                        <div class="">
                            <div class="input-group">
                                <input type="hidden" class="form-control" name="margin" id="margin" oninput="formatNumber(this); calculateTotal()" value="<?= htmlspecialchars($margin); ?>" readonly>
                            </div>
                        </div>
                        <div class="">
                            <label for="" class="form-label">PPN Obat per gram atau buah (dalam %)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="ppn" id="ppn"
                                    value="<?= isset($ppn) && $ppn !== '' ? htmlspecialchars($ppn) : '12'; ?>"
                                    required>
                            </div>
                        </div>
                        <div class="">
                            <label for="" class="form-label">Jumlah</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="jumlah" id="jumlah" oninput="calculateTotal()" required>
                            </div>
                        </div>
                        <div class="">
                            <label for="" class="form-label">Harga per gram atau buah</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="harga" id="harga" oninput="formatNumber(this); calculateTotal()" required>
                            </div>
                        </div>
                        <div class="">
                            <label for="total" class="form-label">Total Rupiah</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control" name="total" id="total" readonly>
                            </div>
                        </div>

                        <div class="">
                            <label for="harga_jual" class="form-label">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control" name="harga_jual" id="harga_jual" readonly>
                            </div>
                        </div>

                        <div class="">
                            <label for="" class="form-label">Tanggal Expired</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="tgl_exp" id="tgl_exp" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3" name="add">Tambah Ke Keranjang
                        </button>
                    </form>
                    <table id="myTable" class="display">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Obat</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>PPN</th>
                                <th>Total</th>
                                <th>Harga Jual</th>
                                <th>Tanggal Expired</th>
                                <th>Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ambil = $con->query("SELECT * FROM cart WHERE session_id = '$session_id'");
                            $no = 1;
                            while ($pecah = $ambil->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $pecah["nama_obat"]; ?></td>
                                    <td><?php echo $pecah["jumlah"]; ?></td>
                                    <td> Rp. <?= number_format($pecah['harga'], 0, ',', '.') ?></td>
                                    <td><?php echo $pecah["ppn"]; ?></td>
                                    <td> Rp. <?= number_format($pecah['total'], 0, ',', '.') ?></td>
                                    <td> Rp. <?= number_format($pecah['harga_jual'], 0, ',', '.') ?></td>
                                    <td><?php echo $pecah["tgl_exp"]; ?></td>
                                    <td>
                                        <button class="btn btn-danger" name="delete">
                                            <a href="index.php?hal=pembelian&deletecart=<?php echo $pecah['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                <i class="bi bi-trash text-white"></i>
                                            </a>
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <form action="" method="post" class="mt-3">
                        <div class="">
                            <label for="" class="form-label">Suplier</label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group">
                                        <select name="suplier" class="form-control" id="suplier">
                                            <option value="">Pilih suplier</option>
                                            <?php
                                            $ambil = $con->query("SELECT * FROM pembelian");
                                            $supliers = [];
                                            while ($pecah = $ambil->fetch_assoc()) {
                                                $suplier_key = $pecah['namasuplier'] . '-' . $pecah['nohp'];
                                                if (!in_array($suplier_key, $supliers)) {
                                                    $supliers[] = $suplier_key;
                                                    $selected = ($pecah['namasuplier'] == $namasuplier) ? 'selected' : '';
                                            ?>
                                                    <option value="<?php echo $pecah['namasuplier']; ?>" data-nohp="<?php echo $pecah['nohp']; ?>" <?php echo $selected; ?>>
                                                        <?php echo $pecah['namasuplier']; ?>
                                                    </option>
                                            <?php }
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="text" name="namasuplier" class="form-control" id="namasuplier" placeholder="Isi nama suplier">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="">
                            <label for="" class="form-label">No HP Supplier</label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="text" name="nohp" class="form-control" id="nohp" placeholder="No HP suplier" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="input-group">
                                        <input type="text" name="nomorhp" class="form-control" id="nomorhp" placeholder="Isi no HP suplier jika belum terisi">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="">
                            <!-- <label for="" class="form-label">Tanggal</label> -->
                            <div class="input-group">
                                <input type="hidden" class="form-control" name="tgl" id="tgl" value="<?= htmlspecialchars(date('Y-m-d')); ?>" required>
                            </div>
                        </div>
                        <div class="">
                            <label for="" class="form-label">Jatuh Tempo</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="jatuh_tempo" id="jatuh_tempo" required>
                            </div>
                        </div>
                        <div class="">
                            <label for="" class="form-label">No Batch</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="no_batch" id="no_batch" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3" name="save">Simpan Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script>
    function formatNumber(number) {
        if (isNaN(number)) return '';
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function getCleanValue(id) {
        const field = document.getElementById(id);
        if (field && field.value) {
            return parseInt(field.value.replace(/\./g, '')) || 0;
        }
        return 0;
    }

    function calculateTotal() {
        let harga = getCleanValue('harga');
        let jumlah = getCleanValue('jumlah');
        let ppn = getCleanValue('ppn');
        let margin = getCleanValue('margin');

        if (!harga || !jumlah || !ppn) {
            document.getElementById("total").value = '';
            return;
        }

        let pajaksub = harga * ppn / 100;
        let pajak = harga + pajaksub;
        let harga_jual = pajak * (margin / 100);
        let total = pajak * jumlah;

        document.getElementById("total").value = formatNumber(total);
        document.getElementById("harga_jual").value = formatNumber(harga_jual);
    }

    window.onload = function() {
        const fieldsToFormat = ['harga', 'harga_jual', 'total'];
        fieldsToFormat.forEach(id => {
            let field = document.getElementById(id);
            if (field && field.value) {
                let value = field.value.replace(/\./g, '');
                value = parseInt(value);
                field.value = formatNumber(value);
            }
        });
    };

    function applyFormattingListeners() {
        ['harga', 'harga_jual', 'total'].forEach(id => {
            const field = document.getElementById(id);
            if (field) {
                field.addEventListener('input', function() {
                    let value = this.value.replace(/[^\d]/g, '');
                    this.value = formatNumber(value);
                });
            }
        });
    }

    function prepareFormSubmission() {
        const fields = ['harga', 'harga_jual', 'total'];
        fields.forEach(id => {
            let field = document.getElementById(id);
            if (field && field.value) {
                field.value = field.value.replace(/\./g, '');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        applyFormattingListeners();

        document.getElementById('nama_obat').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('margin').value = selectedOption.getAttribute('data-margin');
        });

        document.getElementById('suplier').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('nohp').value = selectedOption.getAttribute('data-nohp');
        });

        document.getElementById('form').addEventListener('submit', prepareFormSubmission);
    });
</script>