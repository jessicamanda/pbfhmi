<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php
$sales_id = $_SESSION['admin']['id'];

if (isset($_POST['save'])) {
    $tgl = htmlspecialchars($_POST['tgl']);
    $no_nota = htmlspecialchars(date('Ymdhis'));
    $nama_pelanggan = htmlspecialchars($_POST['nama_pelanggan']);
    $sales_id = $sales_id;
    $provinsi = htmlspecialchars($_POST['provinsi']);
    $kota = htmlspecialchars($_POST['kota']);
    $kecamatan = htmlspecialchars($_POST['kecamatan']);
    $kelurahan = htmlspecialchars($_POST['kelurahan']);
    $kode_pos = htmlspecialchars($_POST['kode_pos']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $sipa = htmlspecialchars($_POST['sipa']);
    $jatuh_tempo = htmlspecialchars($_POST['jatuh_tempo']);
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

    $getLastUser = $con->query("SELECT * FROM user ORDER BY id DESC LIMIT 1")->fetch_assoc();

    $user_id = $getLastUser['id'] + 1;

    $con->query("INSERT INTO transaksi (tgl, no_nota, nama_pelanggan, user_id, instansi, nohp, alamat, total, status,provinsi, kota, kecamatan, kelurahan,kode_pos, sales_id, jatuh_tempo) VALUES ('$tgl', '$no_nota', '$nama_pelanggan', '$user_id', '$instansi', '$nohp', '$alamat', '$total', '$status', '$provinsi', '$kota', '$kecamatan', '$kelurahan','$kode_pos', '$sales_id', '$jatuh_tempo')");
    $con->query("INSERT INTO user (id, username, nama_lengkap, password, role, nohp, sipa) VALUES ('$user_id', '$nama_pelanggan', '$nama_pelanggan', '$nohp', 'pelanggan', '$nohp', '$sipa')");

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
    $harga = str_replace('.', '', $_POST['harga']);
    $sub_total = str_replace('.', '', $_POST['sub_total']);

    $harga = (int)$harga;
    $sub_total = (int)$sub_total;

    $nama_obat = $_POST['nama_obat'];
    $jumlah = $_POST['jumlah'];

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
                        <!-- <div class="">
                            <label for="no_nota" class="form-label">Nomor Nota</label>
                            <input type="number" class="form-control" name="no_nota" value="<?= htmlspecialchars(date('Ymdhis')); ?>" required>
                        </div> -->
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
                                   
                                        $ambil = $con->query("SELECT obat.id AS id_obat, obat.nama_obat, obat.foto, obat.created_at AS obat_created_at, pembelian.id_pembelian, pembelian.created_at AS tanggal_pembelian, pembelian.harga_jual AS harga_terbaru, pembelian.jumlah, pembelian.created_at, pembelian.no_batch, pembelian.status, stok.stok, stok.id AS stok_id FROM obat 
                                        LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT p1.* FROM pembelian p1 WHERE p1.created_at = (SELECT MAX(p2.created_at) FROM pembelian p2 WHERE p2.nama_obat = p1.nama_obat AND p2.status = 'Sudah Datang')) pembelian ON obat.nama_obat = pembelian.nama_obat 
                                        ORDER BY  obat.created_at DESC;");

                                    while ($pecah = $ambil->fetch_assoc()) {
                                    ?>
                                        <option value="<?php echo $pecah['nama_obat']; ?>" data-stok="<?php echo $pecah['stok']; ?>" data-harga="<?php echo $pecah['harga_terbaru']; ?>"><?php echo $pecah['nama_obat']; ?></option>
                                    <?php } ?>

                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga" class="form-control" id="harga" readonly>
                            </div>
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
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" name="sub_total" class="form-control" id="sub_total" readonly>
                            </div>
                        </div>
                        <button type="submit" oninput="calculateTotal()" class="btn btn-primary" name="keranjang">Tambah Ke Keranjang</button>
                    </div>
                </div>
            </div>
        </div>
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
                <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>

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
                            <label for="" class="form-label">Tenggat SIPA (Surat Ijin Praktek Apoteker)</label>
                            <div class="input-group">
                                <input type="date" name="sipa" class="form-control" id="sipa">
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
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="basic-url" class="form-label">Provinsi</label>
                                <select id="provinsi" required class="form-select">
                                    <option hidden selected>Pilih Provinsi</option>
                                </select>
                                <input type="text" hidden id="provins" name="provinsi">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="basic-url" class="form-label">Kota</label>
                                <select id="kota" required class="form-select">
                                    <option hidden Selected>Pilih</option>
                                </select>
                                <input type="text" hidden id="kot" name="kota">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="basic-url" class="form-label">Kecamatan</label>
                                <select id="kecamatan" required class="form-select">
                                    <option hidden Selected>Pilih</option>
                                </select>
                                <input type="text" hidden id="kecamata" name="kecamatan">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="basic-url" class="form-label">Desa/Kelurahan</label>
                                <select id="kelurahan" required class="form-select">
                                    <option hidden Selected>Pilih</option>
                                </select>
                                <input type="text" hidden id="keluraha" name="kelurahan">
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="form-group">
                                    <label>Kode POS</label>
                                    <input type="text" class="form-control" name="kode_pos" id="kode_pos" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="" class="form-label">Alamat Pelanggan</label>
                            <div class="input-group">
                                <textarea type="text" style="min-height: 150px;" name="alamat" class="form-control border border-success" id="alamat"
                                    placeholder="Alamat Lengkap" value=""></textarea>
                            </div>
                        </div>
                        <div class="">
                            <label for="" class="form-label">Jatuh Tempo Pembayaran</label>
                            <div class="input-group">
                                <input type="date" name="jatuh_tempo" class="form-control" id="jatuh_tempo">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4" name="save">Simpan Transaksi</button>
                </div>
            </div>
        </div>
    </form>

</div>

<script>
    var selectProvinsi = document.getElementById("provinsi");
    // Lakukan permintaan HTTP untuk mendapatkan data propinsi dari API
    fetch("https://kodepos-2d475.firebaseio.com/list_propinsi.json?print=pretty")
        .then(response => response.json())
        .then(data => {
            // Data propinsi telah diterima, lakukan iterasi untuk membuat elemen option
            for (var propinsiCode in data) {
                if (data.hasOwnProperty(propinsiCode)) {
                    var propinsiName = data[propinsiCode];

                    // Membuat elemen option
                    var optionElement = document.createElement("option");
                    optionElement.value = propinsiCode; // Nilai option sesuai dengan kode propinsi
                    optionElement.text = propinsiName; // Teks yang akan ditampilkan pada option

                    // Menambahkan elemen option ke dalam elemen select
                    selectProvinsi.appendChild(optionElement);
                }
            }
        })
        .catch(error => {
            console.error("Error fetching propinsi data:", error);
        });
    // Mendapatkan referensi ke elemen select
    var selectProvinsi = document.getElementById("provinsi");
    var selectKota = document.getElementById("kota");
    var selectKecamatan = document.getElementById("kecamatan");
    var selectKelurahan = document.getElementById("kelurahan");
    var inputProvinsi = document.getElementById("provins");
    var inputKota = document.getElementById("kot");
    var inputKecamatan = document.getElementById("kecamata");
    var inputKelurahan = document.getElementById("keluraha");
    var inputKodePos = document.getElementById("kode_pos");

    // Function untuk membuat elemen option
    function createOption(value, text) {
        var optionElement = document.createElement("option");
        optionElement.value = value;
        optionElement.text = text;
        return optionElement;
    }

    // Function untuk mengambil data kota dari API berdasarkan provinsi yang dipilih
    function updateKotaList(provinsiCode) {
        // Lakukan permintaan HTTP untuk mendapatkan data kota dari API
        fetch(`https://kodepos-2d475.firebaseio.com/list_kotakab/${provinsiCode}.json?print=pretty`)
            .then(response => response.json())
            .then(data => {
                // Hapus opsi yang ada sebelumnya
                selectKota.innerHTML = "";

                // Tambahkan opsi baru berdasarkan data kota yang diterima
                for (var kotaCode in data) {
                    if (data.hasOwnProperty(kotaCode)) {
                        var kotaName = data[kotaCode];
                        selectKota.appendChild(createOption(kotaCode, kotaName));
                    }
                }

                // Panggil fungsi untuk memperbarui kecamatan
                updateKecamatanList();
            })
            .catch(error => {
                console.error("Error fetching kota data:", error);
            });
    }

    // Function untuk mengambil data kecamatan dan kelurahan dari API berdasarkan kota yang dipilih
    // Function untuk mengambil data kecamatan dari API berdasarkan kota yang dipilih
    function updateKecamatanList() {
        // Mendapatkan nilai kota yang dipilih
        var selectedKota = selectKota.value;

        // Lakukan permintaan HTTP untuk mendapatkan data kecamatan dan kelurahan dari API
        fetch(`https://kodepos-2d475.firebaseio.com/kota_kab/${selectedKota}.json?print=pretty`)
            .then(response => response.json())
            .then(data => {
                // Hapus opsi yang ada sebelumnya
                selectKecamatan.innerHTML = "";
                selectKelurahan.innerHTML = "";

                // Buat objek untuk menyimpan kecamatan yang unik
                var kecamatanSet = new Set();

                // Tambahkan kecamatan ke objek set
                data.forEach(entry => {
                    kecamatanSet.add(entry.kecamatan);
                });

                // Tambahkan opsi baru ke dalam elemen select untuk kecamatan
                kecamatanSet.forEach(kecamatan => {
                    selectKecamatan.appendChild(createOption(kecamatan, kecamatan));
                });
            })
            .catch(error => {
                console.error("Error fetching kecamatan data:", error);
            });
    }
    // Function untuk mengambil data kelurahan dari API berdasarkan kecamatan yang dipilih
    function updateKelurahanList() {
        // Mendapatkan nilai kecamatan yang dipilih
        var selectedKecamatan = selectKecamatan.value;

        // Mendapatkan nilai kota yang dipilih
        var selectedKota = selectKota.value;

        // Lakukan permintaan HTTP untuk mendapatkan data kelurahan dari API
        fetch(`https://kodepos-2d475.firebaseio.com/kota_kab/${selectedKota}.json?print=pretty`)
            .then(response => response.json())
            .then(data => {
                // Hapus opsi yang ada sebelumnya
                selectKelurahan.innerHTML = "";

                // Filter data berdasarkan kecamatan yang dipilih
                var filteredData = data.filter(entry => entry.kecamatan === selectedKecamatan);

                // Tambahkan opsi baru ke dalam elemen select untuk kelurahan
                filteredData.forEach(entry => {
                    var option = createOption(entry.kelurahan, entry.kelurahan);
                    selectKelurahan.appendChild(option);
                });
            })
            .catch(error => {
                console.error("Error fetching kelurahan data:", error);
            });
    }

    // Menambahkan event listener untuk elemen provinsi
    selectProvinsi.addEventListener("change", function() {
        // Mendapatkan nilai provinsi yang dipilih
        var selectedProvinsi = selectProvinsi.value;
        var selectedProvinsii = selectProvinsi.options[selectProvinsi.selectedIndex].text;
        inputProvinsi.value = selectedProvinsii;
        // Memanggil fungsi untuk memperbarui daftar kota berdasarkan provinsi yang dipilih
        updateKotaList(selectedProvinsi);
    });

    // Menambahkan event listener untuk elemen kota
    selectKota.addEventListener("change", function() {
        // Memanggil fungsi untuk memperbarui daftar kecamatan dan kelurahan berdasarkan kota yang dipilih
        var selectedKotaa = selectKota.options[selectKota.selectedIndex].text;
        inputKota.value = selectedKotaa;
        updateKecamatanList();
    });
    // Menambahkan event listener untuk elemen kecamatan
    selectKecamatan.addEventListener("change", function() {
        // Memanggil fungsi untuk memperbarui daftar kelurahan berdasarkan kecamatan yang dipilih
        var selectedKecamatann = selectKecamatan.options[selectKecamatan.selectedIndex].text;
        inputKecamatan.value = selectedKecamatann;
        updateKelurahanList();
    });
    selectKelurahan.addEventListener("change", function() {
        // Mendapatkan nilai kelurahan yang dipilih
        var selectedKelurahan = selectKelurahan.value;

        // Mendapatkan nilai kota yang dipilih
        var selectedKota = selectKota.value;
        var selectedKelurahann = selectKelurahan.options[selectKelurahan.selectedIndex].text;
        inputKelurahan.value = selectedKelurahann;
        // Lakukan permintaan HTTP untuk mendapatkan kode pos berdasarkan kelurahan yang dipilih
        fetch(`https://kodepos-2d475.firebaseio.com/kota_kab/${selectedKota}.json?print=pretty`)
            .then(response => response.json())
            .then(data => {
                // Temukan data yang sesuai dengan kelurahan yang dipilih
                var kodePosData = data.find(entry => entry.kelurahan === selectedKelurahan);

                // Tampilkan kode pos di elemen input kode_pos
                if (kodePosData) {
                    inputKodePos.value = kodePosData.kodepos;
                } else {
                    console.error("Kode pos not found for selected kelurahan.");
                }
            })
            .catch(error => {
                console.error("Error fetching kode pos data:", error);
            });
    });
</script>

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

<script>
    function formatNumber(number) {
        if (isNaN(number)) return '';
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function getCleanValue(value) {
        return parseInt(value.replace(/\./g, '')) || 0;
    }

    function calculateSubTotal() {
        const harga = getCleanValue(document.getElementById('harga').value || '0');
        const jumlah = parseInt(document.getElementById('jumlah').value || '0');

        if (!harga || !jumlah) {
            document.getElementById('sub_total').value = '';
            return;
        }

        const subTotal = harga * jumlah;
        document.getElementById('sub_total').value = formatNumber(subTotal);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('harga').addEventListener('input', function() {
            let value = this.value.replace(/[^\d]/g, '');
            this.value = formatNumber(value);
        });

        document.getElementById('jumlah').addEventListener('input', calculateSubTotal);
    });
</script>