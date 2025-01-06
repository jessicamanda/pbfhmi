<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Daftar Exp Terdekat</h6>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ambil = $con->query("SELECT transaksi.*, transaksi_produk.nama_obat, obat.nama_obat
                            FROM transaksi_produk
                            JOIN obat ON transaksi_produk.nama_obat = obat.nama_obat
                            JOIN transaksi ON transaksi_produk.no_nota = transaksi.no_nota
                            GROUP BY obat.nama_obat ORDER BY transaksi.tgl ASC;");
                            $no = 1;
                            while ($pecah = $ambil->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $pecah["id_penjualan"]; ?></td>
                                    <td><?php echo $pecah["tgl"]; ?></td>
                                    <td><?php echo $pecah["nama_obat"]; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable({
                        "order": [[2, "asc"]]
                    });
                });
            </script>
        </div>
    </div>
</div>