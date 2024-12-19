<?php
if (isset($_SESSION['admin'])) {
    $id_user = $_SESSION['admin']['id'];
    $products = $con->query("SELECT obat.id AS id_obat, obat.nama_obat, obat.foto, obat.created_at AS obat_created_at, pembelian.id_pembelian, pembelian.tgl AS tanggal_pembelian, pembelian.harga AS harga_terbaru, pembelian.status, stok.stok, stok.id AS stok_id, keranjang.user_id, keranjang.jumlah, keranjang.sub_harga FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT p1.* FROM pembelian p1 WHERE p1.tgl = (SELECT MAX(p2.tgl) FROM pembelian p2 WHERE p2.nama_obat = p1.nama_obat)) pembelian ON obat.nama_obat = pembelian.nama_obat LEFT JOIN keranjang ON obat.id = keranjang.id_obat WHERE keranjang.user_id = $id_user ORDER BY obat.created_at DESC;");
} else {
    $products = $con->query("SELECT obat.id AS id_obat, obat.nama_obat, obat.foto, obat.created_at AS obat_created_at, pembelian.id_pembelian, pembelian.tgl AS tanggal_pembelian, pembelian.harga AS harga_terbaru, pembelian.jumlah, pembelian.tgl_exp, pembelian.no_batch, pembelian.status, stok.stok, stok.id AS stok_id FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT p1.* FROM pembelian p1 WHERE p1.tgl = (SELECT MAX(p2.tgl) FROM pembelian p2 WHERE p2.nama_obat = p1.nama_obat)) pembelian ON obat.nama_obat = pembelian.nama_obat ORDER BY  obat.created_at DESC LIMIT 0;");
}

?>
<div class="container">
    <style>
        .pagination {
            --bs-pagination-active-bg: #198754;
            --bs-pagination-active-border-color: #198754;
        }

        #hide {
            display: block;
        }

        #hide2 {
            display: none;
        }

        @media (min-width: 576px) {
            .filter-product {
                position: -webkit-sticky;
                position: sticky;
                top: 0px;
                display: none;
            }

            #hide {
                position: -webkit-sticky;
                position: sticky;
                top: 0px;
                display: none;
            }

            #hide2 {
                display: block;
            }
        }
    </style>
    <div class="mb-0 d-flex pt-3 align-items-end justify-content-end">
        <div class="row">
            <div class="col-2 mx-3" id="hide">
                <h3>
                    <a class="nav-link" style="float: left;" href="index.php?halaman=keranjang"><b><i class="bi bi-cart4"></i></b></a>
                    <sup style="background-color: green;  font-size:12px; color: white; padding: 2px; border-radius: 5px;"><?= $products->num_rows ?></sup>
                </h3>
            </div>
            <div class="col-2" id="hide">
                <h3>
                    <a class="nav-link" href="index.php?halaman=riwayat"><b><i class="bi bi-clock-history"></i></b> </a>
                </h3>
            </div>
        </div>
    </div>
    <div class="container mx-auto mb-0 d-flex flex-sm-row flex-column align-items-start justify-content-center gap-sm-4">
        <div class="d-flex flex-column col-sm-3 filter-product">
            <form method="POST">
                <h5>Urutkan</h5>
                <div class="input-group">
                    <select class="form-select  mb-2" name="fil" aria-label="Default select example">
                        <option selected value="Rendah">Harga Terendah</option>
                        <option value="Tinggi">Harga Tertinggi</option>
                    </select>
                    <button class="btn h-100 btn-success" name="filter" type="submit"><i class="bi bi-funnel"></i></button>
                </div>
            </form>
        </div>
        <div class="d-flex flex-column col-sm-8 pt-2">
            <div class="row">
                <div class="col-md-10">
                    <form method="POST">
                        <div class="input-group mb-3">
                            <input type="text" name="keyword" class="form-control" placeholder="Cari Produk Kosmetik ...">
                            <button class="btn btn-success" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="col-md-2" id="hide2">
                    <h3>
                        <a class="nav-link me-3" style="float: left;" href="index.php?halaman=keranjang"><b><i class="bi bi-cart4"></i><sup style="background-color: green; color: white; padding: 1px; border-radius: 5px;"><?= $products->num_rows ?></sup></b> </a>
                        <a class="nav-link" href="index.php?halaman=riwayat"><b><i class="bi bi-clock-history"></i></b> </a>
                    </h3>
                </div>
            </div>
            <div class="container">
                <div class="row row-cols-sm-3 row-cols-2">
                    <?php

                    //   Pagination
                    if (isset($_POST['keyword'])) {
                        $result = $con->query("SELECT obat.id AS id_obat, obat.nama_obat, obat.foto, pembelian.harga AS harga_terbaru, stok.stok FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT p1.* FROM pembelian p1 WHERE p1.tgl = (SELECT MAX(p2.tgl) FROM pembelian p2 WHERE p2.nama_obat = p1.nama_obat)) pembelian ON obat.nama_obat = pembelian.nama_obat WHERE obat.nama_obat LIKE '%$_POST[keyword]%' ORDER BY obat.id DESC");
                    } elseif (isset($_POST['filter'])) {
                        if ($_POST['fil'] == 'Tinggi') {
                            $result = $con->query("SELECT obat.id AS id_obat, obat.nama_obat, obat.foto, pembelian.harga AS harga_terbaru, stok.stok FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT p1.* FROM pembelian p1 WHERE p1.tgl = (SELECT MAX(p2.tgl) FROM pembelian p2 WHERE p2.nama_obat = p1.nama_obat)) pembelian ON obat.nama_obat = pembelian.nama_obat ORDER BY harga_terbaru DESC");
                        } elseif ($_POST['fil'] == 'Rendah') {
                            $result = $con->query("SELECT obat.id AS id_obat, obat.nama_obat, obat.foto, pembelian.harga AS harga_terbaru, stok.stok FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT p1.* FROM pembelian p1 WHERE p1.tgl = (SELECT MAX(p2.tgl) FROM pembelian p2 WHERE p2.nama_obat = p1.nama_obat)) pembelian ON obat.nama_obat = pembelian.nama_obat ORDER BY harga_terbaru ASC");
                        }
                    } else {
                        $result = $con->query("SELECT obat.id AS id_obat, obat.nama_obat, obat.foto, pembelian.harga AS harga_terbaru, stok.stok FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT p1.* FROM pembelian p1 WHERE p1.tgl = (SELECT MAX(p2.tgl) FROM pembelian p2 WHERE p2.nama_obat = p1.nama_obat)) pembelian ON obat.nama_obat = pembelian.nama_obat ORDER BY obat.id DESC");
                    }
                    // Parameters for pagination
                    $limit = 10; // Number of entries to show in a page
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $start = ($page - 1) * $limit;

                    // Get the total number of records
                    $tgl_mulaii = date('Y-m-d', strtotime('2024-03-28'));
                    $total_records = $result->num_rows;

                    // Calculate total pages
                    $total_pages = ceil($total_records / $limit);

                    $cekPage = '';
                    if (isset($_GET['page'])) {
                        $cekPage = $_GET['page'];
                    } else {
                        $cekPage = '1';
                    }
                    // End Pagination

                    if (isset($_POST['keyword'])) {
                        $products = $con->query("SELECT obat.id AS id_obat, obat.nama_obat, obat.foto, pembelian.harga AS harga_terbaru, stok.stok FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT p1.* FROM pembelian p1 WHERE p1.tgl = (SELECT MAX(p2.tgl) FROM pembelian p2 WHERE p2.nama_obat = p1.nama_obat)) pembelian ON obat.nama_obat = pembelian.nama_obat WHERE obat.nama_obat LIKE '%$_POST[keyword]%' ORDER BY obat.id DESC LIMIT $start, $limit;");
                    } elseif (isset($_POST['filter'])) {
                        if ($_POST['fil'] == 'Tinggi') {
                            $products = $con->query("SELECT obat.id AS id_obat, obat.nama_obat, obat.foto, pembelian.harga AS harga_terbaru, stok.stok FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT p1.* FROM pembelian p1 WHERE p1.tgl = (SELECT MAX(p2.tgl) FROM pembelian p2 WHERE p2.nama_obat = p1.nama_obat)) pembelian ON obat.nama_obat = pembelian.nama_obat ORDER BY harga_terbaru DESC LIMIT $start, $limit;");
                        } elseif ($_POST['fil'] == 'Rendah') {
                            $products = $con->query("SELECT obat.id AS id_obat, obat.nama_obat, obat.foto, pembelian.harga AS harga_terbaru, stok.stok FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT p1.* FROM pembelian p1 WHERE p1.tgl = (SELECT MAX(p2.tgl) FROM pembelian p2 WHERE p2.nama_obat = p1.nama_obat)) pembelian ON obat.nama_obat = pembelian.nama_obat ORDER BY harga_terbaru ASC LIMIT $start, $limit;");
                        }
                    } else {
                        $products = $con->query("SELECT obat.id AS id_obat, obat.nama_obat, obat.foto, pembelian.harga AS harga_terbaru, stok.stok FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT p1.* FROM pembelian p1 WHERE p1.tgl = (SELECT MAX(p2.tgl) FROM pembelian p2 WHERE p2.nama_obat = p1.nama_obat)) pembelian ON obat.nama_obat = pembelian.nama_obat ORDER BY obat.id DESC LIMIT $start, $limit;");
                    }

                    foreach ($products as $product) {
                    ?>
                        <div class="col p-2">
                            <div class="card w-100 h-100">
                                <a href="index.php?halaman=detail_produk&id_obat=<?= $product['id_obat'] ?>">

                                    <img src="../assets/foto/obat/<?= $product['foto'] ?>" class="card-img-top" alt="<?= $product['foto'] ?>">

                                </a>
                                <div class="card-body p-2">
                                    <h6 class="card-title"><?= $product['nama_obat'] ?></h6>
                                    <h5 class="card-text text-success">Rp <?= number_format($product['harga_terbaru'], 0, '', '.') ?></h5>
                                    <p class="mt-1 text-muted">Stok <?= $product['stok'] ?></p>
                                    <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#addToCartModal<?= $product['id_obat'] ?>">+ Keranjang</button>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="addToCartModal<?= $product['id_obat'] ?>" tabindex="-1" aria-labelledby="addToCartLabel<?= $product['id_obat'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addToCartLabel<?= $product['id_obat'] ?>">Tambah ke Keranjang</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="POST" action="index.php?halaman=shop&add=<?= $product['id_obat'] ?>">
                                        <div class="modal-body">
                                            <p>Produk: <strong><?= $product['nama_obat'] ?></strong></p>
                                            <p>Harga: <strong>Rp <?= number_format($product['harga_terbaru'], 0, '', '.') ?></strong></p>
                                            <div class="mb-3">
                                                <label for="quantity<?= $product['id_obat'] ?>" class="form-label">Jumlah</label>
                                                <input type="number" class="form-control" name="jumlah" id="quantity<?= $product['id_obat'] ?>" value="1" min=1 max=<?= $product['stok'] ?> required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success">Tambah ke Keranjang</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <?php
            if (isset($_GET['add'])) {
                if (isset($_SESSION['admin']['nama_lengkap'])) {
                    $getProd = $con->query("SELECT obat.id AS id_obat, obat.nama_obat, obat.foto, pembelian.harga AS harga_terbaru, stok.stok FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT p1.* FROM pembelian p1 WHERE p1.tgl = (SELECT MAX(p2.tgl) FROM pembelian p2 WHERE p2.nama_obat = p1.nama_obat)) pembelian ON obat.nama_obat = pembelian.nama_obat WHERE obat.id = '" . htmlspecialchars($_GET['add']) . "'")->fetch_assoc();
                    $user_id = $_SESSION['admin']['id'];
                    $nama_lengkap = $_SESSION['admin']['nama_lengkap'];
                    $id_obat = $getProd['id_obat'];
                    $nama_obat = $getProd['nama_obat'];
                    $harga = $getProd['harga_terbaru'];
                    $jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : 1;
                    $sub_harga = 1 * $getProd['harga_terbaru'];

                    $con->query("INSERT INTO keranjang (user_id, nama_lengkap, id_obat, nama_obat, harga, jumlah, sub_harga) VALUES ('$user_id', '$nama_lengkap', '$id_obat', '$nama_obat', '$harga', '$jumlah', '$sub_harga')");

                    echo "
                            <script>
                                alert('Berhasil Menambahkan Produk Ke Keranjang');
                                document.location.href='index.php?halaman=shop';
                            </script>
                        ";
                } else {
                    echo "
                            <script>
                                alert('Lakukan Login Terlebih Dahulu Sebelum Melakukan Pembelian');
                                document.location.href='login-customer.php';
                            </script>
                        ";
                }
            }

            ?>
            <div class="d-flex justify-content-center my-3">
                <?php
                // Display pagination
                echo '<nav>';
                echo '<ul class="pagination justify-content-center">';

                // Back button
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="index.php?halaman=shop&page=' . ($page - 1) . '">Back</a></li>';
                }

                // Determine the start and end page
                $start_page = max(1, $page - 2);
                $end_page = min($total_pages, $page + 2);

                if ($start_page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="index.php?halaman=shop&page=1">1</a></li>';
                    if ($start_page > 2) {
                        echo '<li class="page-item"><span class="page-link">...</span></li>';
                    }
                }

                for ($i = $start_page; $i <= $end_page; $i++) {
                    if ($i == $page) {
                        echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="index.php?halaman=shop&page=' . $i . '">' . $i . '</a></li>';
                    }
                }

                if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) {
                        echo '<li class="page-item"><span class="page-link">...</span></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="index.php?halaman=shop&page=' . $total_pages . '">' . $total_pages . '</a></li>';
                }

                // Next button
                if ($page < $total_pages) {
                    echo '<li class="page-item"><a class="page-link" href="index.php?halaman=shop&page=' . ($page + 1) . '">Next</a></li>';
                }

                echo '</ul>';
                echo '</nav>';
                ?>
            </div>
        </div>
    </div>
</div>