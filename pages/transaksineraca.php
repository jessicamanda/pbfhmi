<link rel="stylesheet" href="https://cdn.datatables.net/2.1.2/css/dataTables.dataTables.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.0/css/buttons.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.1.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.0/js/dataTables.buttons.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.0/js/buttons.html5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.1.0/js/buttons.print.js"></script>

<?php

$akun = $_GET['akun'];
$getData = $conakuntansi->query("SELECT * FROM transaksibaru WHERE namaakun = '$akun' ORDER BY idtx desc");

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
            foto='$foto' 
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



if (isset($_POST['filter'])) {
    $tanggal_mulai = $_POST['start_date'];
    $tanggal_akhir = $_POST['end_date'];
    $getData = $conakuntansi->query("SELECT * FROM transaksibaru WHERE tgl BETWEEN '$tanggal_mulai' AND '$tanggal_akhir' AND namaakun = '$akun' ORDER BY idtx desc");
}
?>



<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body">
                    <h4>Saldo Neraca</h4>
                    <div class="mt-3">
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah Transaksi</button>
                    </div>
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
                    <div class="table-responsive">
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
                                            <a href="assets/foto/akuntansi/<?= $pecah['foto']; ?>" target="_blank">
                                                <img src="assets/foto/akuntansi/<?= $pecah['foto']; ?>" width="100">
                                            </a>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0)"
                                                class="btn btn-primary"
                                                onclick="editTransaction('<?php echo $pecah['id']; ?>', '<?php echo $pecah['tgl']; ?>', '<?php echo $pecah['namaakun']; ?>', '<?php echo $pecah['debet']; ?>', '<?php echo $pecah['kredit']; ?>', '<?php echo $pecah['ket1']; ?>', '<?php echo $pecah['ket2']; ?>', '<?php echo $pecah['foto']?>')">
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
</div>

<!-- Modal Tambah -->
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
                            while ($row = $debet->fetch_assoc()) {
                            ?>
                                <option value="<?php echo $row['akun']; ?>"><?php echo $row['akun']; ?></option>
                            <?php } ?>
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
                            while ($row = $debet->fetch_assoc()) {
                            ?>
                                <option value="<?php echo $row['akun']; ?>"><?php echo $row['akun']; ?></option>
                            <?php } ?>
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

<!-- Modal Update -->
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
                        <input type="date" class="form-control" id="editTgl" name="tgl" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editDebet" class="form-label">DEBET</label>
                        <input type="text" class="form-control" id="editDebet" name="debet" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editRpDebet" class="form-label">Rp Debet</label>
                        <input type="text" class="form-control" id="editRpDebet" name="rpdebet" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editKredit" class="form-label">KREDIT</label>
                        <input type="text" class="form-control" id="editKredit" name="kredit" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editRpKredit" class="form-label">Rp Kredit</label>
                        <input type="text" class="form-control" id="editRpKredit" name="rpkredit" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editKet1" class="form-label">Keterangan 1</label>
                        <input type="text" class="form-control" id="editKet1" name="ket1" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editKet2" class="form-label">Keterangan 2</label>
                        <input type="text" class="form-control" id="editKet2" name="ket2" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editFoto" class="form-label">Foto Lama</label>
                        <div>
                            <img src="" id="editFotoLama" width="100">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editFoto" class="form-label">Foto Baru</label>
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
    function editTransaction(id, tgl, namaakun, debet, kredit, ket1, ket2, foto) {
        $('#editId').val(id);
        $('#editTgl').val(tgl);
        $('#editDebet').val(namaakun);
        $('#editRpDebet').val(debet);
        $('#editKredit').val(namaakun);
        $('#editRpKredit').val(kredit);
        $('#editKet1').val(ket1);
        $('#editKet2').val(ket2);
        $('#editFotoLama').attr('src', 'assets/foto/akuntansi/' + foto);
        $('#editFotoLama').show();
        $('#editFoto').val('');
        $('#editModal').modal('show');
    }

    $('#debit').keyup(function() {
        $("#kredit").val($(this).val());
    });

    $(document).ready(function() {
        $('#example').DataTable({
            layout: {
                topStart: {
                    buttons: [{
                        extend: 'excel',
                        text: 'Export ke Excel',
                        exportOptions: {
                            modifier: {
                                page: 'current'
                            }
                        }
                    }]
                }
            }
        });
    });
</script>