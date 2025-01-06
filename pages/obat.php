<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>

<?php
$mode = "add";
$edit_id = "";
$nama_obat = "";
$mq = "";
$margin = "";
$deskripsi = "";
$foto = "";

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $query = $con->query("SELECT * FROM obat WHERE id = '$edit_id'");
    if ($query && $data = $query->fetch_assoc()) {
        $mode = "edit";
        $nama_obat = $data['nama_obat'];
        $mq = $data['mq'];
        $margin = $data['margin'];
        $deskripsi = $data['deskripsi'];
        $foto = $data['foto'];
    } else {
        echo "<script>alert('Data tidak ditemukan!'); document.location.href='index.php?hal=obat';</script>";
    }
}

if (isset($_POST['save'])) {
    $nama_obat = htmlspecialchars($_POST['nama_obat']);
    $mq = htmlspecialchars($_POST['mq']);
    $margin = htmlspecialchars($_POST['margin']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $mode = $_POST['mode'];


    $folder = "assets/foto/obat";

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $foto = $_FILES['foto']['name'];
    $lokasi = $_FILES['foto']['tmp_name'];
    $allowed_extensions = ["jpg", "jpeg", "png", "gif", "webp", "heic"];
    $file_extension = strtolower(pathinfo($foto, PATHINFO_EXTENSION));

    if (!in_array($file_extension, $allowed_extensions)) {
        echo "<script>alert('Hanya file JPG atau PNG yang diizinkan!');</script>";
        exit;
    }
    if (!empty($lokasi)) {
        $query = $con->query("SELECT foto FROM obat WHERE id='$edit_id'");
        $data = $query->fetch_assoc();

        if (!empty($data['foto']) && file_exists($folder . '/' . $data['foto'])) {
            unlink($folder . '/' . $data['foto']);
        }
        move_uploaded_file($lokasi, $folder . '/' . $foto);
    }

    if ($mode == "edit" && empty($foto)) {
        $foto = $_POST['existing_foto'];
    }

    if ($mode == "add") {
        $con->query("INSERT INTO obat (nama_obat, mq, margin,foto, deskripsi) VALUES ('$nama_obat', '$mq', '$margin', '$foto', '$deskripsi')");
        echo "<script>alert('Data berhasil ditambahkan'); document.location.href='index.php?hal=obat';</script>";
    } elseif ($mode == "edit") {
        $edit_id = $_POST['edit_id'];
        $con->query("UPDATE obat SET nama_obat='$nama_obat', mq='$mq', margin='$margin', foto='$foto', deskripsi='$deskripsi' WHERE id='$edit_id'");
        echo "<script>alert('Data berhasil diupdate'); document.location.href='index.php?hal=obat';</script>";
    }
}
if (isset($_GET['delete'])) {
    $id = htmlspecialchars($_GET['delete']);
    $query = $con->query("DELETE FROM obat WHERE id='$id'");

    if ($query) {
        echo "<script>alert('Data berhasil dihapus'); document.location.href='index.php?hal=obat';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . $con->error . "'); document.location.href='index.php?hal=obat';</script>";
    }
}
?>


<div class="container-fluid">
    <div class="card mb-4">
        <div class="card shadow p-2 mt-2">
            <form action="" method="post" id="tambah_edit_form" enctype="multipart/form-data">
                <input type="hidden" name="mode" value="<?php echo $mode; ?>">
                <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                <div class="modal-body">
                    <div class="">
                        <label for="nama_obat" class="form-label">Nama Obat</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="nama_obat" id="nama_obat" value="<?= htmlspecialchars($nama_obat); ?>" required>
                        </div>
                    </div>
                    <div class="">
                        <label for="mq" class="form-label">Minimum Quantity</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="mq" id="mq" value="<?= htmlspecialchars($mq); ?>" required>
                        </div>
                    </div>
                    <div class="">
                        <label for="margin" class="form-label">Margin</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="margin" id="margin"value="<?= isset($margin) && $margin !== '' ? htmlspecialchars($margin) : '100'; ?>"
                            required>
                        </div>
                    </div>
                    <div class="">
                        <label for="deskripsi" class="form-label">deskripsi</label>
                        <div class="input-group">
                            <textarea class="form-control" rows="4" name="deskripsi" id="deskripsi"></textarea>
                        </div>
                    </div>
                    <div class="">
                        <label for="margin" class="form-label">Foto</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="foto" id="foto">
                            <input type="hidden" name="existing_foto" value="<?php echo $foto; ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3" name="save">
                        <?php echo ($mode == "edit") ? "Update Data" : "Simpan Data"; ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLE -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Daftar Obat</h6>
                </div>
                <div class="card shadow p-2">
                    <div class="table-responsive">
                        <table id="myTable" class="display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Nama Obat</th>
                                    <th>Minimum Quantity</th>
                                    <th>Margin</th>
                                    <th>Dekripsi</th>
                                    <th>Foto</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ambil = $con->query("SELECT * FROM obat");
                                $no = 1;
                                while ($pecah = $ambil->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $pecah["id"]; ?></td>
                                        <td><?php echo $pecah["nama_obat"]; ?></td>
                                        <td><?php echo $pecah["mq"]; ?></td>
                                        <td><?php echo $pecah["margin"]; ?></td>
                                        <td><?php echo $pecah["deskripsi"]; ?></td>
                                        <td>
                                            <?php if (!empty($pecah["foto"])) : ?>
                                                <img src="assets/foto/obat/<?= htmlspecialchars($pecah["foto"]); ?>" alt="Foto" width="50">
                                            <?php else : ?>
                                                Tidak ada foto
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="index.php?hal=obat&edit=<?php echo $pecah['id']; ?>" class="btn btn-primary btn-edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button class="btn btn-danger" name="delete">
                                                <a href="index.php?hal=obat&delete=<?php echo $pecah['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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