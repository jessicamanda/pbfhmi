<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
<?php $nama_lengkap = $_SESSION['admin']['nama_lengkap'];
?>
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Daftar Riwayat Absensi Pulang</h6>
            </div>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table id="myTable" class="display">
                        <thead>
                            <tr>
                                <th>No</th>
                                <?php if ($_SESSION['admin']['role'] === 'ceo'): ?>
                                    <th>Nama Karyawan</th>
                                <?php endif; ?>
                                <th>Tanggal Absen</th>
                                <th>Jam Kerja</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                                <th>Foto Absen</th>
                                <th>Maps</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $nama_lengkap = mysqli_real_escape_string($con, $nama_lengkap);
                            $ambil = $con->query("SELECT * FROM absensi_pulang WHERE nama_lengkap = '$nama_lengkap'");
                            if ($_SESSION['admin']['role'] === 'ceo'):
                                $ambil = $con->query("SELECT * FROM absensi_pulang");
                            endif;
                            $no = 1;
                            while ($pecah = $ambil->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <?php if ($_SESSION['admin']['role'] === 'ceo'): ?>
                                        <td><?php echo $pecah["nama_lengkap"]; ?></td>
                                    <?php endif;
                                    ?>
                                    <td><?php echo $pecah["tanggal_absen_plg"]; ?></td>
                                    <td><?php echo $pecah["jam_kerja"]; ?></td>
                                    <td><?php echo $pecah["jam_pulang"]; ?></td>
                                    <td><?php echo $pecah["status_plg"]; ?></td>
                                    <td>
                                        <img src="assets/foto/absensi/<?php echo $pecah["foto_absen_plg"]; ?>" alt="Foto Absen" width="100" />
                                    </td>
                                    <td>
                                    <a href="https://www.google.com/maps/place/<?php echo $pecah["latitude"]; ?>,<?php echo $pecah["longitude"]; ?>" target="_blank">Lihat Maps</a>
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