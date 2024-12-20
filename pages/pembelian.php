<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>

<?php
$mode = "add";
$edit_id = "";
$tgl = "";
$nama_obat = "";
$namasuplier = "";
$nohp = "";
$harga = "";
$jumlah = "";
$ppn = "";
$total = "";
$harga_jual = "";
$tgl_exp = "";
$no_batch = "";

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $ambil = $con->query("SELECT * FROM pembelian JOIN obat ON obat.nama_obat = pembelian.nama_obat WHERE pembelian.id_pembelian = '$edit_id';
");
    if ($ambil && $pecah = $ambil->fetch_assoc()) {
        $mode = "edit";
        $tgl = $pecah['tgl'];
        $nama_obat = $pecah['nama_obat'];
        $namasuplier = $pecah['namasuplier'];
        $nohp = $pecah['nohp'];
        $harga = $pecah['harga'];
        $jumlah = $pecah['jumlah'];
        $ppn = $pecah['ppn'];
        $total = $pecah['total'];
        $harga_jual = $pecah['harga_jual'];
        $jatuh_tempo = $pecah['jatuh_tempo'];
        $tgl_exp = $pecah['tgl_exp'];
        $no_batch = $pecah['no_batch'];
    } else {
        echo "<script>alert('Data tidak ditemukan!'); document.location.href='index.php?hal=pembelian';</script>";
    }
}

if (isset($_POST['save'])) {
    $harga = str_replace('.', '', $_POST['harga']);
    $total = str_replace('.', '', $_POST['total']);
    $harga_jual = str_replace('.', '', $_POST['harga_jual']);

    $harga = (int)$harga;
    $total = (int)$total;
    $harga_jual = (int)$harga_jual;

    $tgl = htmlspecialchars($_POST['tgl']);
    $nama_obat = htmlspecialchars($_POST['nama_obat']);
    $suplier = htmlspecialchars($_POST['suplier']);
    $namasuplier = htmlspecialchars($_POST['namasuplier']);
    $nohp = htmlspecialchars($_POST['nohp']);
    $nomorhp = htmlspecialchars($_POST['nomorhp']);
    $jumlah = htmlspecialchars($_POST['jumlah']);
    $ppn = htmlspecialchars($_POST['ppn']);
    $jatuh_tempo = htmlspecialchars($_POST['jatuh_tempo']);
    $tgl_exp = htmlspecialchars($_POST['tgl_exp']);
    $no_batch = htmlspecialchars($_POST['no_batch']);
    $tipe = 'Non Pajak';
    $status = 'Belum Datang';
    $mode = $_POST['mode'];
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');

    $final_suplier = !empty($suplier) ? $suplier : $namasuplier;
    $final_nohp = !empty($nohp) ? $nohp : $nomorhp;

    if ($mode == "add") {
        $query = "INSERT INTO pembelian 
                    (tgl, nama_obat, namasuplier, nohp, harga, ppn, total, tgl_exp, no_batch, tipe, harga_jual,status, jumlah, jatuh_tempo, created_at, updated_at) 
                  VALUES 
                    ('$tgl', '$nama_obat', '$final_suplier', '$final_nohp', '$harga', '$ppn', '$total', '$tgl_exp', '$no_batch', '$tipe', '$status','$status', '$jumlah', '$jatuh_tempo', '$created_at', '$updated_at')";
        if ($con->query($query)) {
            echo "<script>alert('Data berhasil ditambahkan'); document.location.href='index.php?hal=pembelian';</script>";
        } else {
            echo "<script>alert('Gagal menambahkan data: " . $con->error . "'); document.location.href='index.php?hal=pembelian';</script>";
        }
    } elseif ($mode == "edit") {
        $edit_id = htmlspecialchars($_POST['edit_id']);
        $query = "UPDATE pembelian 
                  SET tgl='$tgl', nama_obat='$nama_obat', jumlah='$jumlah', namasuplier='$final_suplier', 
                      nohp='$nohp', harga='$harga', ppn='$ppn', total='$total', jatuh_tempo='$jatuh_tempo', 
                      tgl_exp='$tgl_exp', no_batch='$no_batch', updated_at='$updated_at', harga_jual='$harga_jual'
                  WHERE id_pembelian='$edit_id'";
        if ($con->query($query)) {
            echo "<script>alert('Data berhasil diupdate'); document.location.href='index.php?hal=pembelian';</script>";
        } else {
            echo "<script>alert('Gagal mengupdate data: " . $con->error . "'); document.location.href='index.php?hal=pembelian';</script>";
        }
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
                        <input type="hidden" name="mode" value="<?php echo $mode; ?>">
                        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
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
                                    <select name="nama_obat" class="form-control" required>
                                        <option value="" disabled selected>Pilih Nama Obat</option>
                                        <?php
                                        $ambil = $con->query("SELECT * FROM obat");
                                        while ($pecah = $ambil->fetch_assoc()) {
                                            $selected = ($pecah['nama_obat'] == $nama_obat) ? 'selected' : '';
                                            echo '<option value="' . $pecah['nama_obat'] . '" ' . $selected . '>' . $pecah['nama_obat'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="">
                            <label for="" class="form-label">PPN per gram atau buah (dalam %)</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="ppn" id="ppn"
                                    value="<?= isset($ppn) && $ppn !== '' ? htmlspecialchars($ppn) : '12'; ?>"
                                    required>
                            </div>
                        </div>
                        <div class="">
                            <label for="" class="form-label">Jumlah</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="jumlah" id="jumlah" oninput="calculateTotal()" value="<?= htmlspecialchars($jumlah); ?>" required>
                            </div>
                        </div>
                        <div class="">
                            <label for="" class="form-label">Harga per gram atau buah</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="harga" id="harga" oninput="formatNumber(this); calculateTotal()" value="<?= htmlspecialchars($harga); ?>" required>
                            </div>
                        </div>
                        <div class="">
                            <label for="total" class="form-label">Total Rupiah</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control" name="total" id="total" value="<?= htmlspecialchars($total); ?>" readonly>
                            </div>
                        </div>
                        <div class="">
                            <label for="harga_jual" class="form-label">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" class="form-control" name="harga_jual" id="harga_jual" value="<?= htmlspecialchars($harga_jual); ?>" readonly>
                            </div>
                        </div>
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
                                        <input type="text" name="nohp" class="form-control" id="nohp" placeholder="No HP suplier" value="<?= htmlspecialchars($nohp); ?>"  readonly>
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
                            <label for="" class="form-label">Jatuh Tempo</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="jatuh_tempo" id="jatuh_tempo" value="<?= htmlspecialchars($tgl_exp); ?>" required>
                            </div>
                        </div>
                        <div class="">
                            <label for="" class="form-label">Tanggal Expired</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="tgl_exp" id="tgl_exp" value="<?= htmlspecialchars($tgl_exp); ?>" required>
                            </div>
                        </div>
                        <div class="">
                            <label for="" class="form-label">No Batch</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="no_batch" id="no_batch" value="<?= htmlspecialchars($no_batch); ?>" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3" name="save">
                            <?php echo ($mode == "edit") ? "Update Data" : "Simpan Data"; ?>
                        </button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- TABLE -->
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
                                    <th>No</th>
                                    <th>ID Pembelian</th>
                                    <th>Tanggal</th>
                                    <th>Nama Obat</th>
                                    <th>Nama Suplier</th>
                                    <th>No HP</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>PPN</th>
                                    <th>Total</th>
                                    <th>Harga Jual</th>
                                    <th>Tipe</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Tanggal Expired</th>
                                    <th>No Batch</th>
                                    <th>Status</th>
                                    <?php if ($_SESSION['admin']['role'] === 'ceo'): ?>
                                        <th>Aksi</th>
                                    <?php endif ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ambil = $con->query("SELECT * FROM pembelian");
                                $no = 1;
                                while ($pecah = $ambil->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $pecah["id_pembelian"]; ?></td>
                                        <td><?php echo $pecah["tgl"]; ?></td>
                                        <td><?php echo $pecah["nama_obat"]; ?></td>
                                        <td><?php echo $pecah["namasuplier"]; ?></td>
                                        <td><?php echo $pecah["nohp"]; ?></td>
                                        <td><?php echo $pecah["jumlah"]; ?></td>
                                        <td> Rp. <?= number_format($pecah['harga'], 0, ',', '.') ?></td>
                                        <td><?php echo $pecah["ppn"]; ?></td>
                                        <td> Rp. <?= number_format($pecah['total'], 0, ',', '.') ?></td>
                                        <td> Rp. <?= number_format($pecah['harga_jual'], 0, ',', '.') ?></td>
                                        <td><?php echo $pecah["tipe"]; ?></td>
                                        <td><?php echo $pecah["jatuh_tempo"]; ?></td>
                                        <td><?php echo $pecah["tgl_exp"]; ?></td>
                                        <td><?php echo $pecah["no_batch"]; ?></td>
                                        <td><?php echo $pecah["status"]; ?></td>
                                        <?php if ($_SESSION['admin']['role'] === 'ceo'): ?>
                                            <td>
                                                <a href="index.php?hal=pembelian&edit=<?php echo $pecah['id_pembelian']; ?>" class="btn btn-primary btn-edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button class="btn btn-danger" name="delete">
                                                    <a href="index.php?hal=pembelian&delete=<?php echo $pecah['id_pembelian']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                        <i class="bi bi-trash text-white"></i>
                                                    </a>
                                                </button>
                                            </td>
                                        <?php endif ?>

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
</div>

<script>
    function calculateTotal() {
        let harga = document.getElementById('harga').value.replace(/\./g, '');

        var jumlah = parseInt(document.getElementById("jumlah").value);
        harga = parseInt(harga);
        var ppn = parseInt(document.getElementById("ppn").value);

        if (!harga || isNaN(harga) || !jumlah || isNaN(jumlah) || !ppn || isNaN(ppn)) {
            document.getElementById("total").value = '';
            return;
        }

        var sub = jumlah * harga;
        var pajaksub = harga * ppn / 100;
        var pajak = jumlah * pajaksub;
        var total = sub + pajak;
        var harga_jual = total / jumlah;

        document.getElementById("total").value = formatNumber(total);
        document.getElementById("harga_jual").value = formatNumber(harga_jual);
    }

    function formatNumber(number) {
        if (isNaN(number)) return '';
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    document.getElementById('suplier').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var nohp = selectedOption.getAttribute('data-nohp');
        document.getElementById('nohp').value = nohp;
    });

    document.getElementById('harga').addEventListener('input', function() {
        let value = this.value.replace(/[^\d]/g, '');
        this.value = formatNumber(value);
    });

    window.onload = function() {
        let total = document.getElementById('total').value;
        total = total.replace(/\./g, '');
        total = parseInt(total);
        document.getElementById('total').value = formatNumber(total);
    }
</script>