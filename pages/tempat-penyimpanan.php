<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>

<?php
$mode = "add";
$edit_id = "";
$nama_tempat = "";
$deskripsi = "";

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $query = $con->query("SELECT * FROM tempat_penyimpanan WHERE id_tempat= '$edit_id'");
    if ($query && $data = $query->fetch_assoc()) {
        $mode = "edit";
        $nama_tempat = $data['nama_tempat'];
        $deskripsi = $data['deskripsi'];
    } else {
        echo "<script>alert('Data tidak ditemukan!'); document.location.href='index.php?hal=tempat-penyimpanan';</script>";
    }
}

if (isset($_POST['save'])) {
    $nama_tempat = htmlspecialchars($_POST['nama_tempat']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $mode = $_POST['mode'];

    if ($mode == "add") {
        $con->query("INSERT INTO tempat_penyimpanan (nama_tempat, deskripsi) VALUES ('$nama_tempat', '$deskripsi')");
        echo "<script>alert('Data berhasil ditambahkan'); document.location.href='index.php?hal=tempat-penyimpanan';</script>";
    } elseif ($mode == "edit") {
        $edit_id = $_POST['edit_id'];
        $con->query("UPDATE tempat_penyimpanan SET nama_tempat='$nama_tempat', deskripsi='$deskripsi' WHERE id_tempat='$edit_id'");
        echo "<script>alert('Data berhasil diupdate'); document.location.href='index.php?hal=tempat-penyimpanan';</script>";
    }
}
if (isset($_GET['delete'])) {
    $id = htmlspecialchars($_GET['delete']);
    $query = $con->query("DELETE FROM tempat_penyimpanan WHERE id_tempat='$id'");

    if ($query) {
        echo "<script>alert('Data berhasil dihapus'); document.location.href='index.php?hal=tempat-penyimpanan';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . $con->error . "'); document.location.href='index.php?hal=tempat-penyimpanan';</script>";
    }
}
?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card shadow p-2 mt-2">
                    <form action="" method="post" id="tambah_edit_form">
                        <input type="hidden" name="mode" value="<?php echo $mode; ?>">
                        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                        <div class="modal-body">
                            <div class="">
                                <label for="nama_tempat" class="form-label">Nama Tempat Penyimpanan</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="nama_tempat" id="nama_tempat" value="<?= htmlspecialchars($nama_tempat); ?>" required>
                                </div>
                            </div>
                            <div class="">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="deskripsi" id="deskripsi" value="<?= htmlspecialchars($deskripsi); ?>" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3" name="save">
                                <?php echo ($mode == "edit") ? "Update Data" : "Simpan Data"; ?>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- TABLE -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Daftar Tempat Penyimpanan</h6>
                </div>
                <div class="card shadow p-2">
                    <div class="table-responsive">
                        <table id="myTable" class="display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Nama Tempat</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ambil = $con->query("SELECT * FROM tempat_penyimpanan");
                                $no = 1;
                                while ($pecah = $ambil->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $pecah["id_tempat"]; ?></td>
                                        <td><?php echo $pecah["nama_tempat"]; ?></td>
                                        <td><?php echo $pecah["deskripsi"]; ?></td>
                                        <td>
                                            <a href="index.php?hal=tempat-penyimpanan&edit=<?php echo $pecah['id_tempat']; ?>" class="btn btn-primary btn-edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button class="btn btn-danger" name="delete">
                                                <a href="index.php?hal=tempat-penyimpanan&delete=<?php echo $pecah['id_tempat']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
</div>