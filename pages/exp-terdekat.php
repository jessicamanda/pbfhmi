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
                                <th>Tanggal Beli</th>
                                <th>Nama Obat</th>
                                <th>Tanggal Expired</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ambil = $con->query("SELECT pembelian.*, obat.nama_obat
                            FROM pembelian JOIN obat ON pembelian.nama_obat = obat.nama_obat
                            WHERE pembelian.id_pembelian IN (SELECT MAX(id_pembelian) FROM pembelian
                            JOIN obat ON pembelian.nama_obat = obat.nama_obat GROUP BY obat.nama_obat)
                            ORDER BY ABS(DATEDIFF(pembelian.tgl_exp, CURDATE())) ASC");
                            $no = 1; 
                            while ($pecah = $ambil->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $pecah["tgl"]; ?></td>
                                    <td><?php echo $pecah["nama_obat"]; ?></td>
                                    <td><?php echo $pecah["tgl_exp"]; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable({
                        "order": [[3, "asc"]]
                    });
                });
            </script>
        </div>
    </div>
</div>