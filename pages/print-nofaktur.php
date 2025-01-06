<?php session_start() ?>
<?php include "../koneksi.php"; ?>
<?php if (isset($_GET['print'])) { ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="../assets/img/logo_nino.png" />
        <title>Print No Faktur</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
                background-color: #f4f4f4;
            }

            .container {
                width: 210mm;
                /* A5 height as width for landscape */
                height: 148mm;
                /* A5 width as height for landscape */
                margin: 20mm auto;
                padding: 20px;
                background-color: white;
                border: 1px solid #ccc;
                box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
            }

            h1,
            h2,
            h3 {
                margin: 20px 0;
            }

            p {
                font-size: 12pt;
                line-height: 1.6;
                margin: 10px 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            th {
                text-align: center;
                vertical-align: middle;
            }

            td {
                text-align: start;
            }

            table,
            th,
            td {
                border: 1px solid black;
            }

            .non-border,
            .non-border th,
            .non-border td {
                width: fit-content;
                border: none;
            }

            th,
            td {
                padding: 1px 5px;
            }

            .text-end {
                text-align: right;
            }

            .text-center {
                text-align: center;
            }

            @media print {
                @page {
                    size: A4 landscape;
                    /* Set orientation to landscape */
                }

                body {
                    background-color: white;
                }

                .container {
                    margin: 0;
                    padding: 0;
                    border: none;
                    box-shadow: none;
                    width: 100%;
                    height: auto;
                }

                .footer {
                    page-break-after: always;
                }
            }
        </style>
    </head>

    <body>
        <div class="container">
            <?php
            $no_faktur = $_GET['nofaktur'];
            $transaksi_produk = $con->query("SELECT transaksi.id_penjualan, transaksi.no_nota,transaksi.instansi, transaksi.status, transaksi.tgl, 
transaksi.nama_pelanggan, transaksi.alamat, transaksi.provinsi, transaksi.kota, transaksi.kelurahan, transaksi.kecamatan, transaksi.kode_pos, transaksi.nohp, transaksi.total,transaksi_produk.nama_obat,
transaksi_produk.jumlah,transaksi_produk.sub_total FROM transaksi LEFT JOIN transaksi_produk ON transaksi.no_nota = transaksi_produk.no_nota WHERE transaksi.no_nota='$no_faktur'");

            $transaksi_pembayaran = $con->query("SELECT transaksi.id_penjualan, transaksi_pembayaran.tgl_bayar, transaksi_pembayaran.nominal, 
    transaksi_pembayaran.foto FROM transaksi LEFT JOIN transaksi_pembayaran ON transaksi.id_penjualan = transaksi_pembayaran.id_penjualan WHERE transaksi.no_nota='$no_faktur'");


            $data = [];
            while ($row = $transaksi_produk->fetch_assoc()) {
                $id_penjualan = $row['id_penjualan'];
                if (!isset($data[$id_penjualan])) {
                    $data[$id_penjualan] = [
                        'id_penjualan' => $row['id_penjualan'],
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
            ?>
                                        <?php
                            $no = 1;
                            $nomer = 1;
                            foreach ($data as $pecah) {
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

            <div class="">
                <div class="mb-4">
                    <h3>No Faktur : <?=$pecah['no_nota']?></h3>
                    <table>
                        <thead>
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Tanggal</th>
                                <th rowspan="2">Nama Pembeli</th>
                                <th rowspan="2">Instansi</th>
                                <th rowspan="2">No HP</th>
                                <th rowspan="2">Alamat</th>
                                <th colspan="3" style="text-align: center;">Produk</th>
                                <th rowspan="2">Total</th>
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
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $pecah["tgl"]; ?></td>
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
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    </body>

    </html>
<?php } ?>