<?php session_start() ?>
<?php include "../koneksi.php"; ?>
<?php if (isset($_GET['print'])) { ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="../assets/img/logo_nino.png" />
        <title>Print Purchasing Order</title>
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
            $no_nota = $_GET['no_nota'];
            $pembelian = $con->query("SELECT * FROM pembelian WHERE no_nota='$no_nota'");

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
                        'namasuplier' => $row['namasuplier'],
                        'nohp' => $row['nohp'],    
                        'produk' => [],
                    ];
                }
                $data[$no_nota]['produk'][] = [
                    'nama_obat' => $row['nama_obat'],
                    'harga' => $row['harga'],
                    'jumlah' => $row['jumlah'],
                    'total' => $row['total'],
                ];
            }
            ?>
            <?php
            $no = 1;
            $nomer = 1;
            $total = 0;
            foreach ($data as $pecah) {
                foreach ($pecah['produk'] as $produk) {
                    $total += $produk['total'];
                }
            ?>
                <div class="">
                    <div class="mb-4">
                        <center>
                            <h3 style="margin: 5px 0;"><b><u>PURCHASE ORDER</u></b></h3>
                            <h5 style="margin: 5px 0;">Nomor : <?= $pecah['no_nota'] ?></h5>
                        </center>
                        <span style="margin: 5px 0;">Kepada:</span>
                        <h4 style="margin-top: 5px; margin-bottom: 40px;"><?=$pecah['namasuplier']?></h4>
                        <span>Dengan Hormat,<br>
                            Dengan ini kami mengirimkan daftar pemesanan barang untuk <span><?=$pecah['namasuplier']?></span> sebagai berikut:</span>
                        <table>
                            <thead>
                                <tr>
                                    <th>Nama Obat</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Sub Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
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
                                            echo 'Rp ' . number_format($produk['harga'], 0, ',', '.')
                                                . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($pecah['produk'] as $produk) {
                                            echo '<b>'. 'Rp ' . number_format($produk['total'], 0, ',', '.')
                                                . '<br>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                            <tfoot>
                                <td colspan="3" style="text-align: center;"><b>Total</b></td>
                                    <td><b>Rp <?= number_format($total, 0, '', '.') ?></b></td>
                            </tfoot>
                        </table>

                        <h5 style="margin: 20px 0;"><b>Catatan : </b></h5>
                    </div>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    </body>

    </html>
<?php } ?>