<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Daftar Obat Belum Datang</h6>
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
                                <th>Harga</th>
                                <th>PPN</th>
                                <th>Total</th>
                                <th>Tipe</th>
                                <th>Jatuh Tempo</th>
                                <th>Tanggal Expired</th>
                                <th>No Batch</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ambil = $con->query("SELECT * FROM pembelian WHERE status='Belum Datang'");
                            $no = 1;
                            while ($pecah = $ambil->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $pecah["id_pembelian"]; ?></td>
                                    <td><?php echo $pecah["tgl"]; ?></td>
                                    <td><?php echo $pecah["nama_obat"]; ?></td>
                                    <td><?php echo $pecah["namasuplier"]; ?></td>
                                    <td><?php echo $pecah["nohp"]; ?></td>
                                    <td><?php echo $pecah["harga"]; ?></td>
                                    <td><?php echo $pecah["ppn"]; ?></td>
                                    <td><?php echo $pecah["total"]; ?></td>
                                    <td><?php echo $pecah["tipe"]; ?></td>
                                    <td><?php echo $pecah["jatuh_tempo"]; ?></td>
                                    <td><?php echo $pecah["tgl_exp"]; ?></td>
                                    <td><?php echo $pecah["no_batch"]; ?></td>
                                    <td><?php echo $pecah["status"]; ?></td>
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