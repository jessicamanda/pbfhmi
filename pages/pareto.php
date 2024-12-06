<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>

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
                                <th>Harga</th>
                                <th>PPN</th>
                                <th>Total</th>
                                <th>Tipe</th>
                                <th>Jatuh Tempo</th>
                                <th>Tanggal Expired</th>
                                <th>No Batch</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ambil = $con->query("SELECT nama_obat, COUNT(nama_obat) AS total_pembelian, id_pembelian, tgl, namasuplier, nohp, harga, ppn, total, tipe, jatuh_tempo, tgl_exp, no_batch, status FROM pembelian GROUP BY nama_obat ORDER BY total_pembelian DESC");
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