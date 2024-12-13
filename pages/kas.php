<script src="https://code.jquery.com/jquery-3.7.1.js"></script>


<?php
if (isset($_POST['save'])) {
    $tgl = htmlspecialchars($_POST['tgl']);
    $debet = htmlspecialchars($_POST['debet']);
    $rpdebet = htmlspecialchars($_POST['rpdebet']);
    $kredit = htmlspecialchars($_POST['kredit']);
    $rpkredit = htmlspecialchars($_POST['rpkredit']);
    $ket1 = htmlspecialchars($_POST['ket1']);
    $ket2 = htmlspecialchars($_POST['ket2']);

    $idtxdata = $conakuntansi->query("SELECT max(idtx) as mak from transaksibaru");
    $idtx1 = $idtxdata->fetch_assoc();
    $idtx = $idtx1['mak'] + 1;

    $folder = "assets/foto/akuntansi";

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $foto = $_FILES['foto']['name'];
    $lokasi = $_FILES['foto']['tmp_name'];
    if (!empty($foto)) {
        move_uploaded_file($lokasi, $folder . $foto);
    }

    $query_debet = $conakuntansi->query("INSERT INTO transaksibaru (tgl, namaakun, debet, ket1, ket2, foto, idtx) VALUES ('$tgl', '$debet', '$rpdebet', '$ket1', '$ket2', '$foto', '$idtx')");
    $query_kredit = $conakuntansi->query("INSERT INTO transaksibaru (tgl, namaakun, kredit, ket1, ket2, foto, idtx) VALUES ('$tgl', '$kredit', '$rpkredit', '$ket1', '$ket2', '$foto', '$idtx')");
    if ($query_debet && $query_kredit) {
        echo "<script>alert('Data berhasil disimpan')</script>";
        echo "<script>location='index.php?hal=kas'</script>";
    } else {
        echo "<script>alert('Data gagal disimpan')</script>";
        echo "<script>location='index.php?hal=kas'</script>";
    }
}

?>



<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>KAS</h4>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Transaksi</button>
                </div>
                <div class="card shadow p-3">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nomor</th>
                                    <th>Tanggal</th>
                                    <th>Nama Akun</th>
                                    <th>Debet</th>
                                    <th>Kredit</th>
                                    <th>Keterangan 1</th>
                                    <th>Foto</th>
                                    <th>Flow</th>
                                    <th>Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $query = "SELECT * FROM transaksibaru where namaakun='kas' ORDER BY idtx ASC";
                                    $flow2 = 0;
                                    $urlPage = "index.php?hal=kas";

                                    // Parameters for pagination
                                    $limit = 30; // Number of entries to show in a page 
                                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                                    $start = ($page - 1) * $limit;

                                    // Get the total number of records
                                    $result = $conakuntansi->query($query);
                                    $total_records = $result->num_rows;

                                    // Calculate total pages
                                    $total_pages = ceil($total_records / $limit);

                                    $cekPage = '';
                                    if(isset($_GET['page'])){
                                        $cekPage = $_GET['page'];
                                    }else{
                                        $cekPage = '1';
                                    }
                                    // End Pagination
                                    
                                    $getData = $conakuntansi->query($query." LIMIT $start, $limit;");
                                    $getTotal = $conakuntansi->query("SELECT SUM(debet - kredit) AS total_saldo FROM transaksibaru WHERE namaakun = 'kas'")->fetch_assoc()['total_saldo'];
                                    foreach($getData as $data) {
                                ?>
                                    <tr>
                                        <td><?= $data['idtx']; ?></td>
                                        <td><?= $data['tgl']; ?></td>
                                        <td><?= $data['namaakun']; ?></td>
                                        <td><?= number_format($data['debet'], 0, ',', '.'); ?></td>
                                        <td><?= number_format($data['kredit'], 0, ',', '.'); ?></td>
                                        <td><?= $data['ket1']; ?></td>
                                        <td>
                                            <a href="assets/foto/akuntansi/<?= $data['foto']; ?>" target="_blank">
                                                <img src="assets/foto/akuntansi/<?= $data['foto']; ?>" width="100">
                                            </a>
                                        </td>
                                        <td>
                                            <?= number_format($flow = $data['debet'] - $data['kredit'])?>
                                        </td>
                                        <td>
                                            <?php $flow2 += $flow?>
                                            <?= number_format($flow2)?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td><strong>Total: </strong> </td>
                                    <td colspan="7"></td>
                                    <td>
                                       <?= number_format($getTotal)?>                                    
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <center class="mt-2">
                        <?php
                            // Display pagination
                                echo '<nav>';
                                echo '<ul class="pagination justify-content-center">';
                                
                                // Back button
                                if ($page > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="'.$urlPage.'&page=' . ($page - 1) . '">Back</a></li>';
                                }
                                
                                // Determine the start and end page
                                $start_page = max(1, $page - 2);
                                $end_page = min($total_pages, $page + 2);
                                
                                if ($start_page > 1) {
                                    echo '<li class="page-item"><a class="page-link" href="'.$urlPage.'&page=1">1</a></li>';
                                    if ($start_page > 2) {
                                        echo '<li class="page-item"><span class="page-link">...</span></li>';
                                    }
                                }
                                
                                for ($i = $start_page; $i <= $end_page; $i++) {
                                    if ($i == $page) {
                                        echo '<li class="page-item"><span class="page-link active " style="color: blue;">' . $i . '</span></li>';
                                    } else {
                                        echo '<li class="page-item" style="color: white;"><a class="page-link" href="'.$urlPage.'&page=' . $i . '">' . $i . '</a></li>';
                                    }
                                }
                                
                                if ($end_page < $total_pages) {
                                    if ($end_page < $total_pages - 1) {
                                        echo '<li class="page-item"><span class="page-link">...</span></li>';
                                    }
                                    echo '<li class="page-item"><a class="page-link" href="'.$urlPage.'&page=' . $total_pages . '">' . $total_pages . '</a></li>';
                                }
                                
                                // Next button
                                if ($page < $total_pages) {
                                    echo '<li class="page-item"><a class="page-link" href="'.$urlPage.'&page=' . ($page + 1) . '">Next</a></li>';
                                }
                                
                                echo '</ul>';
                                echo '</nav>';
                            // End Display
                        ?>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Untuk Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Transaksi Akuntansi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tgl" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tgl" name="tgl" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">DEBET</label>
                        <select name="debet" class="form-control">
                            <option value="" hidden>Pilih Akun</option>
                            <?php 
                                $debet = $conakuntansi->query("SELECT * FROM akun ORDER BY nomor asc");
                                while($row = $debet->fetch_assoc()){
                            ?>
                            <option value="<?php echo $row['akun'];?>"><?php echo $row['akun'];?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="debit" class="form-label">Rp Debet</label>
                        <input type="text" class="form-control" id="debit" name="rpdebet" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">KREDIT</label>
                        <select name="kredit" class="form-control">
                            <option value="" hidden>Pilih Akun</option>
                            <?php 
                                $debet = $conakuntansi->query("SELECT * FROM akun ORDER BY nomor asc");
                                while($row = $debet->fetch_assoc()){
                            ?>
                            <option value="<?php echo $row['akun'];?>"><?php echo $row['akun'];?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kredit" class="form-label">Rp Kredit</label>
                        <input type="text" class="form-control" id="kredit" name="rpkredit" required>
                    </div>
                    <div class="mb-3">
                        <label for="ket1" class="form-label">Keterangan 1</label>
                        <input type="text" class="form-control" id="ket1" name="ket1" required>
                    </div>
                    <div class="mb-3">
                        <label for="ket2" class="form-label">Keterangan 2</label>
                        <input type="text" class="form-control" id="ket2" name="ket2" required>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="foto" name="foto" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="save">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
	$('#debit').keyup(function(){
        $("#kredit").val($(this).val());
    });    
</script>