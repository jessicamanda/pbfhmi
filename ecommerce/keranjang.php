<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
<!-- Tambahkan jQuery di sini -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Tambahkan Bootstrap JS di sini -->
<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->


<!-- Container for the Cart and Checkout button -->
<div class="container mt-0">
    <div class="row">
        <div class="col-12 text-center">
            <h2 style="color: green; font-weight: bold; font-size: 40px">Keranjang</h2>
        </div>
        <!-- <div class="col-12 p-2 d-flex justify-content-end">
            <button class="btn btn-success" type="button">
                <i class="bi bi-cart"></i>
                Checkout
            </button>
        </div> -->
    </div>
</div>


<div class="card border border-success">

    <div class="container ">
        <div class="row d-flex flex-sm-row">

            <div class="col-sm-8 p-4 d-flex flex-column gap-2">
                <?php
                $grandTotal = 0;
                if (isset($_SESSION['admin'])) {
                    $id_pelanggan = $_SESSION['admin']['id'];

                    $products = $con->query("SELECT obat.*, pembelian.*, stok.*, keranjang.* FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT p1.* FROM pembelian p1 WHERE p1.tgl = (SELECT MAX(p2.tgl) FROM pembelian p2 WHERE p2.nama_obat = p1.nama_obat)) pembelian ON obat.nama_obat = pembelian.nama_obat LEFT JOIN keranjang ON obat.id = keranjang.id_obat WHERE keranjang.user_id = '$id_pelanggan'");

                    // print_r($products);

                } else {
                    echo "
                        <script>
                            alert('Lakukan Login Terlebih Dahulu');
                            document.location.href='login-customer.php';
                        </script>
                    ";
                }
                ?>

                <?php foreach ($products as $index => $product): ?>
                    <div class="card border border-success p-2" >
                        <div class="product">
                            <div class="product-content" style="  display: flex;">
                                <a href="../assets/foto/obat/<?= $product['foto'] ?>" target="_blank">
                                    <img class="product-image"
                                        style=" max-width: 100px;max-height: 100px; margin-bottom: 10px;border-radius: 10px;margin-right: 10px;"
                                        src="../assets/foto/obat/<?= $product['foto'] ?>"
                                        alt="<?php echo $product['nama_obat']; ?>">
                                </a>
                                <div class="row">
                                    <h6 class="mb-1"><?php echo $product['nama_obat']; ?></h6>
                                    <p class="card-text mb-0 mt-0">Rp.
                                        <?= number_format($product['harga'], 0, ',', '.') ?>,00 
                                    </p>
                                    <p class="mb-0"><?php echo $product['jumlah']; ?> Pcs</p>
                                    <h6 id="price"><b>Sub : Rp
                                            <?php echo number_format($sub = $product['harga'] * $product['jumlah'] , 0, ',', '.') ?></b>
                                    </h6>
                                    <?php $grandTotal += $sub; ?>
                                    <h6 id="price" style="display:none"><?php echo $product['harga']; ?></h6>

                                </div>
                            </div>
                            <div class="quantity d-flex">
                                <div class="card-jumlah d-flex gap-2 mb-2 mr-2">
                                    <!-- 
                                    <button class="btn btn-outline-success" onclick="decrement(<?php echo $index; ?>)">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <button class="btn btn-outline-success" onclick="increment(<?php echo $index; ?>)">
                                        <i class="bi bi-plus"></i>
                                    </button> -->
                                    <!-- <button style="" class="btn btn-outline-danger"
                                        onclick="removeProduct(<?php echo $index; ?>)">
                                        <i class="fas fa-trash"></i>

                                    </button> -->
                                    <!-- <input type="number" type="number" readonly
                                        style="width:80px" class="border border-success btn-sm" value="<?php echo $product['jumlah']; ?>"> -->
                                    <span class="btn btn-outline-success" data-bs-toggle="modal"
                                        data-bs-target="#update<?= $product['id'] ?>">
                                        <i class="bi bi-pencil-square"></i>
                                    </span>

                                    <a href="index.php?halaman=keranjang&id=<?php echo $product['id'] ?>"
                                        onclick="return(confirm('Yakin menghapus keranjang yang dipilih?'))">
                                        <span class="btn btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </span>
                                    </a>
                                    <?php
                                    if (isset($_GET['id'])) {
                                        $con->query("DELETE FROM keranjang where id=$_GET[id]");
                                        echo "
                                        <script>
                                        alert('Berhasil menghapus keranjang');
                                        document.location.href='index.php?halaman=keranjang';
                                        </script>
                                        ";
                                    }



                                    ?>


                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="update<?= $product['id'] ?>" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Update</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form method="post">
                                    <div class="modal-body">
                                        <label for="">Jumlah</label>
                                        <input type="number" name="jumlah" value="<?= $product['jumlah'] ?>"
                                            class="form-control border border-success">
                                        <input type="hidden" name="id" value="<?= $product['id'] ?>"
                                            class="form-control border border-success">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-success" name="update_jumlah">Save</button>
                                    </div>
                                </form>
                                <?php
                                if (isset($_POST['update_jumlah'])) {
                                    $id_user = $_SESSION['admin']['id'];
                                    $jumlah = htmlspecialchars($_POST['jumlah']);
                                    $id = htmlspecialchars($_POST['id']);
                                    $con->query("UPDATE keranjang SET jumlah = $jumlah WHERE id = $id AND user_id = $id_user");

                                    echo "
                                        <script>
                                            alert('Berhasil mengupdate jumlah');
                                            document.location.href='index.php?halaman=keranjang';
                                        </script>
                                    ";
                                }
                                ?>

                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
                <div class="border border-success p-2   " style="border-radius: 10px;">
                    <h3>Total : Rp <?= number_format($grandTotal, 0, '', '.') ?>,00</h3>
                </div>
            </div>


            <div class="col-sm-4 p-4 d-flex flex-column gap-2">
                <div class="card border border-success p-4">
                    <div class="total d-flex gap-3 flex-column">
                        <h4>
                            <label for="" class="form-label">Silahkan Isi Data : </label>
                        </h4>
                        <form method="post">
                            <div class="mb-2">
                                <label for="" class="form-label">Nama Lengkap Tujuan :</label>
                                <input type="text" class="form-control border border-success" name="nama_lengkap"
                                    placeholder="Nama Lengkap" value="<?= $_SESSION['admin']['nama_lengkap'] ?>">
                            </div>
                            <div class="mb-2">
                                <label for="" class="form-label">Alamat Lengkap Tujuan <i class="bi bi-pencil-square" data-bs-toggle="modal" data-bs-target="#dataDiri"></i> :</label>
                                <textarea type="text" style="min-height: 150px;" name="alamat_lengkap" class="form-control border border-success" id="alamat"
                                    placeholder="Alamat Lengkap" value="">Provinsi <?= $_SESSION['admin']['provinsi'] ?>, Kabupaten/Kota <?= $_SESSION['admin']['kota'] ?>, Kecamatan <?= $_SESSION['admin']['kecamatan'] ?>, Desa/Kelurahan <?= $_SESSION['admin']['kelurahan'] ?>, <?= $_SESSION['admin']['alamat'] ?>, <?= $_SESSION['admin']['kode_pos'] ?>
                                </textarea>
                            </div>
                            <div>
                                <label for="" class="form-label">Nomor HP Tujuan :</label>
                                <input type="text" class="form-control border border-success" name="no_telp"
                                    placeholder="Nomor HP" value="<?= $_SESSION['admin']['nohp'] ?>">
                            </div>
                            <?php if ($_SESSION['admin']['alamat'] == '') { ?>
                                <button type="button" class="btn btn-success w-100 mt-2" data-bs-toggle="modal" data-bs-target="#dataDiri">Lengkapi Data Diri</button>
                            <?php } else { ?>
                                <button class="btn btn-success w-100 mt-2" name="co">Checkout</button>
                            <?php } ?>
                        </form>
                        <!-- Modal Data Diri -->
                        <div class="modal fade" id="dataDiri" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Lengkapi Alamat</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="post">
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <p class="mb-0" align="left" for="inputState" required class="form-label">Provinsi</p>
                                                    <select id="provinsi" required class="form-select">
                                                        <option hidden Selected>Pilih</option>
                                                    </select>
                                                    <input type="text" hidden id="provins" name="provinsi">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <p class="mb-0" align="left" for="inputState" required class="form-label">Kota/Kabupaten</p>
                                                    <select id="kota" required class="form-select">
                                                        <option hidden Selected>Pilih</option>
                                                    </select>
                                                    <input type="text" hidden id="kot" name="kota">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <p class="mb-0" align="left" for="inputState" required class="form-label">Kecamatan</p>
                                                    <select id="kecamatan" required class="form-select">
                                                        <option hidden Selected>Pilih</option>
                                                    </select>
                                                    <input type="text" hidden id="kecamata" name="kecamatan">
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <p class="mb-0" align="left" for="inputState" required class="form-label">Desa/Kelurahan</p>
                                                    <select id="kelurahan" required class="form-select">
                                                        <option hidden Selected>Pilih</option>
                                                    </select>
                                                    <input type="text" hidden id="keluraha" name="kelurahan">
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <p class="mb-0" align="left" for="inputCity" required class="form-label">Kode Pos</p>
                                                    <input type="text" name="kode_pos" id="kode_pos" required class="form-control" placeholder="Masukkan kode pos">
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <p class="mb-0" align="left" for="inputCity" required class="form-label">Alamat Rumah (Dusun, RT, RW)</p>
                                                    <textarea type="text" name="alamat" required class="form-control" style="height: 100px;" id="alamatAsal" value="" placeholder="Masukkan alamat"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success" name="savedatadiri">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
                        <?php
                        if (isset($_POST['co'])) {
                            $getCart = $con->query("SELECT obat.id AS id_obat, obat.nama_obat, obat.foto, obat.created_at AS obat_created_at, pembelian.id_pembelian, pembelian.tgl AS tanggal_pembelian, pembelian.harga AS harga_terbaru, pembelian.status, stok.stok, stok.id AS stok_id, keranjang.user_id, keranjang.jumlah, keranjang.sub_harga FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT p1.* FROM pembelian p1 WHERE p1.tgl = (SELECT MAX(p2.tgl) FROM pembelian p2 WHERE p2.nama_obat = p1.nama_obat)) pembelian ON obat.nama_obat = pembelian.nama_obat LEFT JOIN keranjang ON obat.id = keranjang.id_obat WHERE keranjang.user_id = $id_pelanggan ");
                            $code_nota = date('YmdHis') . $_SESSION['admin']['id'];
                            $user_id = $_SESSION['admin']['id'];
                            $instansi = $_SESSION['admin']['instansi'];

                            $alamat_lengkap = $_POST['alamat_lengkap'];
                                preg_match(
                                    "/Provinsi (.*?), Kabupaten\/Kota (.*?), Kecamatan (.*?), Desa\/Kelurahan (.*?), (.*?), (\d+)/", $alamat_lengkap, $matches
                                );
                            $provinsi = isset($matches[1]) ? $matches[1] : '';
                            $kota = isset($matches[2]) ? $matches[2] : '';
                            $kecamatan = isset($matches[3]) ? $matches[3] : '';
                            $kelurahan = isset($matches[4]) ? $matches[4] : '';
                            $alamat = isset($matches[5]) ? $matches[5] : '';
                            $kode_pos = isset($matches[6]) ? $matches[6] : '';

                            $nama_lengkap = htmlspecialchars($_POST['nama_lengkap']);
                            $no_telp = htmlspecialchars($_POST['no_telp']);

                            $con->query("INSERT INTO transaksi (tgl, nama_pelanggan, user_id, instansi, provinsi, kota, kecamatan, kelurahan, kode_pos, alamat, no_nota, status, nohp, total) VALUES (now(), '$nama_lengkap', '$user_id', '$instansi', '$provinsi', '$kota', '$kecamatan', '$kelurahan', '$kode_pos', '$alamat', '$code_nota', 'Diproses', '$no_telp', '$grandTotal')");

                            foreach ($getCart as $data) {
                                $produk = $data['nama_obat'];
                                $harga = $data['harga_terbaru'];
                                $jumlah = $data['jumlah'];
                                $sub_harga = $data['harga_terbaru'] * $data['jumlah'];

                                $con->query("INSERT INTO transaksi_produk (no_nota, nama_obat, jumlah, sub_total) VALUES ('$code_nota', '$produk', '$jumlah', '$sub_harga')");
                            }


                            $con->query("DELETE FROM keranjang WHERE user_id = '$user_id'");
                            echo "
                                    <script>
                                        alert('Berhasil Check Out, Lakukan Pembayaran Dengan Transfer Ke Nomor Rekening 330101007238502 BRI, dan Upload Bukti Pembayaran Anda');
                                        document.location.href='index.php?halaman=shop';
                                    </script>
                                ";
                        }

                        if (isset($_POST['savedatadiri'])) {
                            $provinsi = htmlspecialchars($_POST["provinsi"]);
                            $kota = htmlspecialchars($_POST["kota"]);
                            $kelurahan = htmlspecialchars($_POST["kelurahan"]);
                            $kecamatan = htmlspecialchars($_POST["kecamatan"]);
                            $kode_pos = htmlspecialchars($_POST["kode_pos"]);
                            $alamat = htmlspecialchars($_POST["alamat"]);

                            $con->query("UPDATE transaksi SET provinsi='$provinsi', kota='$kota', kecamatan='$kecamatan', kelurahan='$kelurahan', kode_pos='$kode_pos', alamat='$alamat' WHERE user_id = '" . $_SESSION['admin']['id'] . "'");
                            $getNewPasien = $con->query("SELECT * FROM user LEFT JOIN transaksi ON transaksi.user_id = user.id WHERE username = '" . $_SESSION['admin']['username'] . "' AND provinsi != '' LIMIT 1 ")->fetch_assoc();
                            $_SESSION['admin'] = '';

                            $_SESSION['admin'] = $getNewPasien;

                            echo "
                                    <script>
                                        alert('Berhasil Mengisi Alamat');
                                        document.location.href='index.php?halaman=keranjang';
                                    </script>
                                ";
                        }

                        ?>
                        <!-- <h4>Total Belanja</h4>
                        <p>Jumlah Barang : <span id="total-quantity">0</span> Item</p>
                        <p>Total Biaya : Rp. <span id="total-cost">0</span>,00</p> -->

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var quantities = document.querySelectorAll('.quantity-input');
        var prices = document.querySelectorAll('#price');
        var totalElement = document.getElementById('total');

        function updateTotals() {
            var total = 0;
            for (var i = 0; i < quantities.length; i++) {
                var quantity = parseInt(quantities[i].value);
                var price = parseInt(prices[i].textContent);
                total += quantity * price;
            }
            totalElement.textContent = 'Total: ' + total;
        }

        quantities.forEach(function(input) {
            input.addEventListener('change', function() {
                updateTotals();
            });
        });

        updateTotal();
    });

    function increment(index) {
        var input = document.getElementById('quantity-' + index);
        var newValue = parseInt(input.value) + 1;
        input.value = newValue;
        updateTotal();
    }

    function decrement(index) {
        var input = document.getElementById('quantity-' + index);
        var newValue = parseInt(input.value) - 1;
        if (newValue < 1) newValue = 1;
        input.value = newValue;
        updateTotal();
    }

    function removeProduct(index) {
        var product = document.getElementById('product-' + index);
        product.remove();
        updateTotal();
    }

    function updateTotal() {
        var totalQuantity = 0;
        var totalCost = 0;
        var products = document.querySelectorAll('.product');
        products.forEach(function(product) {

            var quantityInput = product.querySelector('.quantity input');
            var quantity = parseInt(quantityInput.value);

            totalQuantity += quantity;
            // console.log('ada'+totalQuantity);

            var priceElement = product.querySelector('#price');
            var price = parseInt(priceElement.textContent);
            totalCost += quantity * price;
        });
        document.getElementById('total-quantity').textContent = totalQuantity;
        document.getElementById('total-cost').textContent = totalCost.toLocaleString('id-ID');

    }
</script>