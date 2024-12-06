<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>

<?php 

$akun = $_GET['akun'];

$getData = $conakuntansi->query("SELECT * FROM transaksibaru WHERE namaakun = '$akun' ORDER BY idtx desc");
?>



<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h4>Saldo Neraca</h4>
                    <form action="" method="POST">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-5">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" id="start_date" name="start_date" class="form-control" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>" required>
                            </div>

                            <div class="col-md-5">
                                <label for="end_date" class="form-label">Sampai dengan</label>
                                <input type="date" id="end_date" name="end_date" class="form-control" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" required>
                            </div>

                            <div class="col-md-2 d-flex justify-content-end align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm" name="filter" style="margin-top: 2.8rem;">Tampilkan</button>
                            </div>
                        </div>
                    </form> 
                </div>
                <div class="card shadow p-2 mt-2">
                        <table class="display" id="example">
                            <thead>
                                <tr>
                                    <th>nomor</th>
                                    <th>tgl</th>
                                    <th>namaakun</th>
                                    <th>debet</th>
                                    <th>kredit</th>
                                    <th>ket1</th>
                                    <th>ket2</th>
                                    <th>foto</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                        
                                        while ($pecah = $getData->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><?php echo $pecah["idtx"]; ?></td>
                                        <td><?php echo $pecah["tgl"]; ?></td>
                                        <td><?php echo $pecah["namaakun"]; ?></td>
                                        <td><?php echo $pecah["debet"]; ?></td>
                                        <td><?php echo $pecah["kredit"]; ?></td>
                                        <td><?php echo $pecah["ket1"]; ?></td>
                                        <td><?php echo $pecah["ket2"]; ?></td>
                                        <td>
                                            <a href="assets/foto/akuntansi/<?= $data['foto']; ?>" target="_blank">
                                                <img src="assets/foto/akuntansi/<?= $data['foto']; ?>" width="100">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="index.php?hal=akun&edit=<?php echo $data['idx']; ?>" class="btn btn-primary btn-sm">
                                                Upload Ulang Foto
                                            </a>
                                        </td>
                                    </tr>                     
                                <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>





<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>