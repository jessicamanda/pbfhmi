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
        echo "<script>location='index.php?hal=transaksi'</script>";
    } else {
        echo "<script>alert('Data gagal disimpan')</script>";
        echo "<script>location='index.php?hal=transaksi'</script>";
    }
}



if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = $conakuntansi->query("DELETE FROM transaksibaru WHERE idtx='$id'");
    if ($query) {
        echo "<script>alert('Data berhasil dihapus')</script>";
        echo "<script>location='index.php?hal=transaksi'</script>";
    } else {
        echo "<script>alert('Data gagal dihapus')</script>";
        echo "<script>location='index.php?hal=transaksi'</script>";
    }
}

if (isset($_POST['update'])) {
    $id = htmlspecialchars($_POST['id']);
    $tgl = htmlspecialchars($_POST['tgl']);
    $debet = htmlspecialchars($_POST['debet']);
    $rpdebet = htmlspecialchars($_POST['rpdebet']);
    $kredit = htmlspecialchars($_POST['kredit']);
    $rpkredit = htmlspecialchars($_POST['rpkredit']);
    $ket1 = htmlspecialchars($_POST['ket1']);
    $ket2 = htmlspecialchars($_POST['ket2']);

    $folder = "assets/foto/akuntansi/";

    $foto = $_FILES['foto']['name'];
    $lokasi = $_FILES['foto']['tmp_name'];
    if (!empty($foto)) {
        move_uploaded_file($lokasi, $folder . $foto);
        $query_update = $conakuntansi->query("UPDATE transaksibaru SET 
            tgl='$tgl', namaakun='$debet', debet='$rpdebet', kredit='$rpkredit', ket1='$ket1', ket2='$ket2', foto='$foto' 
            WHERE id='$id'");
    } else {
        $query_update = $conakuntansi->query("UPDATE transaksibaru SET 
            tgl='$tgl', namaakun='$debet', debet='$rpdebet', kredit='$rpkredit', ket1='$ket1', ket2='$ket2' 
            WHERE id='$id'");
    }

    if ($query_update) {
        echo "<script>alert('Data berhasil diperbarui')</script>";
        echo "<script>location='index.php?hal=transaksi'</script>";
    } else {
        echo "<script>alert('Data gagal diperbarui')</script>";
        echo "<script>location='index.php?hal=transaksi'</script>";
    }
}

?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h4>Transaksi Akuntansi</h4>
                    <form action="" method="GET" class="d-flex gap-2 mt-4">
                        <input type="hidden" name="hal" value="transaksi">
                        <input type="text" name="search" class="form-control" style="height: calc(1.5em + 0.75rem + 2px);" placeholder="Cari" value="<?= isset($_GET['search']) ? $_GET['search'] : '' ?>">
                        <button type="submit" class="btn btn-secondary btn-sm">Cari</button>
                    </form>
                    <form action="" method="GET">
                        <input type="hidden" name="hal" value="transaksi">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-5">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control" style="height: calc(1.5em + 0.75rem + 2px);" required>
                            </div>

                            <div class="col-md-5">
                                <label for="end_date" class="form-label">Sampai dengan</label>
                                <input type="date" name="end_date" class="form-control" style="height: calc(1.5em + 0.75rem + 2px);" required>
                            </div>

                            <div class="col-md-2 d-flex justify-content-end align-items-end">
                                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                            </div>
                        </div>
                    </form>
                    
                    <div class="mt-3">
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Transaksi</button>
                    </div>
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
                                    <th>Keterangan 2</th>
                                    <th>Foto</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $query = "SELECT * FROM transaksibaru";
                                    $urlPage = "index.php?hal=transaksi";

                                    // Parameters for pagination
                                    $limit = 25; // Number of entries to show in a page 
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

                                    $where_clauses = [];
                                    
                                    if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
                                        $start_date = $_GET['start_date'];
                                        $end_date = $_GET['end_date'];
                                        if (!empty($start_date) && !empty($end_date)) {
                                            $where_clauses[] = "tgl BETWEEN '$start_date' AND '$end_date'";
                                        }
                                    }
                                    
                                    if (isset($_GET['search'])) {
                                        $search = htmlspecialchars($_GET['search']);
                                        $where_clauses[] = "(namaakun LIKE '%$search%' OR ket1 LIKE '%$search%' OR ket2 LIKE '%$search%')";
                                    }
                                    
                                    if (!empty($where_clauses)) {
                                        $query .= " WHERE " . implode(' AND ', $where_clauses);
                                        $query .= " ORDER BY idtx DESC";
                                    }
                                    
                                    // $query = mysqli_query($conakuntansi, $query);
                                    
                                    // while ($data = mysqli_fetch_array($query)) {
                                    
                                    $getData = $conakuntansi->query($query." LIMIT $start, $limit;");
                                    foreach($getData as $data) {
                                ?>
                                    <tr>
                                        <td><?= $data['idtx']; ?></td>
                                        <td><?= $data['tgl']; ?></td>
                                        <td><?= $data['namaakun']; ?></td>
                                        <td><?= number_format($data['debet'], 0, ',', '.'); ?></td>
                                        <td><?= number_format($data['kredit'], 0, ',', '.'); ?></td>
                                        <td><?= $data['ket1']; ?></td>
                                        <td><?= $data['ket2']; ?></td>
                                        <td>
                                            <a href="assets/foto/akuntansi/<?= $data['foto']; ?>" target="_blank">
                                                <img src="assets/foto/akuntansi/<?= $data['foto']; ?>" width="100">
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="javascript:void(0)" 
                                                    class="btn btn-primary" 
                                                    onclick="editTransaction('<?php echo $data['id']; ?>', '<?php echo $data['tgl']; ?>', '<?php echo $data['namaakun']; ?>', '<?php echo $data['debet']; ?>', '<?php echo $data['kredit']; ?>', '<?php echo $data['ket1']; ?>', '<?php echo $data['ket2']; ?>')">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <a href="index.php?hal=transaksi&delete=<?php echo $data['idtx']; ?>" class="btn btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus transaksi ini?')">
                                                    <i class="bi bi-trash text-white"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
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


<!-- Modal Untuk Update -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Transaksi Akuntansi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" id="editId" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editTgl" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="editTgl" name="tgl" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDebet" class="form-label">DEBET</label>
                        <select name="debet" class="form-control" id="editDebet">
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
                        <label for="editRpDebet" class="form-label">Rp Debet</label>
                        <input type="text" class="form-control" id="editRpDebet" name="rpdebet" required>
                    </div>
                    <div class="mb-3">
                        <label for="editKredit" class="form-label">KREDIT</label>
                        <select name="kredit" class="form-control" id="editKredit">
                            <option value="" hidden>Pilih Akun</option>
                            <?php 
                                $kredit = $conakuntansi->query("SELECT * FROM akun ORDER BY nomor asc");
                                while($row = $kredit->fetch_assoc()){
                            ?>
                            <option value="<?php echo $row['akun'];?>"><?php echo $row['akun'];?></option>
                            <?php }?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editRpKredit" class="form-label">Rp Kredit</label>
                        <input type="text" class="form-control" id="editRpKredit" name="rpkredit" required>
                    </div>
                    <div class="mb-3">
                        <label for="editKet1" class="form-label">Keterangan 1</label>
                        <input type="text" class="form-control" id="editKet1" name="ket1" required>
                    </div>
                    <div class="mb-3">
                        <label for="editKet2" class="form-label">Keterangan 2</label>
                        <input type="text" class="form-control" id="editKet2" name="ket2" required>
                    </div>
                    <div class="mb-3">
                        <label for="editFoto" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="editFoto" name="foto">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" name="update">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
	$('#debit').keyup(function(){
        $("#kredit").val($(this).val());
    });

    function editTransaction(id, tgl, namaakun, debet, kredit, ket1, ket2) {
        $('#editId').val(id);
        $('#editTgl').val(tgl);
        $('#editDebet').val(namaakun);
        $('#editRpDebet').val(debet);
        $('#editKredit').val(namaakun);
        $('#editRpKredit').val(kredit);
        $('#editKet1').val(ket1);
        $('#editKet2').val(ket2);
        $('#editModal').modal('show');
    }

</script>
