<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>

<?php 
$mode = 'tambah';
$edit_id = '';

if (isset($_GET['edit'])) {
    $mode = 'edit';
    $edit_id = htmlspecialchars($_GET['edit']);
    $query_edit = mysqli_query($conakuntansi, "SELECT * FROM akun WHERE idx = '$edit_id'");
    $edit_data = mysqli_fetch_assoc($query_edit);
}

if (isset($_POST['save'])) {
    $nomor = htmlspecialchars($_POST['nomor']);
    $akun = htmlspecialchars($_POST['akun']);
    $status = htmlspecialchars($_POST['status']);
    $mode = htmlspecialchars($_POST['mode']);
    $edit_id = htmlspecialchars($_POST['edit_id']);

    if ($mode == 'edit') {
        $query = mysqli_query($conakuntansi, "UPDATE akun SET nomor = '$nomor', Akun = '$akun', status = '$status' WHERE idx = '$edit_id'");
        $message = $query ? 'Data berhasil diperbarui' : 'Data gagal diperbarui';
    } else {
        $query = mysqli_query($conakuntansi, "INSERT INTO akun (nomor, Akun, status) VALUES ('$nomor', '$akun', '$status')");
        $message = $query ? 'Data berhasil disimpan' : 'Data gagal disimpan';
    }

    echo "<script>alert('$message')</script>";
    echo "<script>window.location.href = 'index.php?hal=akun'</script>";
}

if (isset($_GET['delete'])) {
    $delete_id = htmlspecialchars($_GET['delete']);
    $query = mysqli_query($conakuntansi, "DELETE FROM akun WHERE idx = '$delete_id'");
    $message = $query ? 'Data berhasil dihapus' : 'Data gagal dihapus';

    echo "<script>alert('$message')</script>";
    echo "<script>window.location.href = 'index.php?hal=akun'</script>";
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
                                <label for="nomor" class="form-label">Nomor</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="nomor" id="nomor" 
                                        value="<?php echo ($mode == 'edit') ? $edit_data['nomor'] : ''; ?>" required>
                                </div>
                            </div>
                            <div class="">
                                <label for="akun" class="form-label">Akun</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="akun" id="akun" 
                                        value="<?php echo ($mode == 'edit') ? $edit_data['akun'] : ''; ?>" required>
                                </div>
                            </div>
                            <div class="">
                                <label for="status" class="form-label">Status</label>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="" hidden>Status</option>
                                        <option value="1" <?php echo ($mode == 'edit' && $edit_data['status'] == '1') ? 'selected' : ''; ?>>Aktif</option>
                                        <option value="0" <?php echo ($mode == 'edit' && $edit_data['status'] == '0') ? 'selected' : ''; ?>>Non Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2" name="save">
                                <?php echo ($mode == 'edit') ? "Update Akun" : "Tambah Akun "; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Daftar Akun</h4>
                </div>
                <div class="card shadow p-2 mt-2">
                    <table id="example" class="display">
                        <thead>
                            <tr>
                                <th>Nomor</th>
                                <th>Akun</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($conakuntansi, "SELECT * FROM akun");
                            while ($data = mysqli_fetch_array($query)) {
                            ?>
                                <tr>
                                    <td><?php echo $data['nomor']; ?></td>
                                    <td><?php echo $data['akun']; ?></td>
                                    <td><?php echo ($data["status"] == "1") ? "Aktif" : "Non Aktif"; ?></td>
                                    <td>
                                        <a href="index.php?hal=akun&edit=<?php echo $data['idx']; ?>" class="btn btn-primary">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="index.php?hal=akun&delete=<?php echo $data['idx']; ?>" class="btn btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus akun ini?')">
                                            <i class="bi bi-trash text-white"></i>
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
