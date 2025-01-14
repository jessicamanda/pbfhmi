<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>

<?php
$mode = "add";
$edit_id = "";
$nama_dokumen = "";
$dokumen = "";

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $query = $con->query("SELECT * FROM perizinan WHERE id = '$edit_id'");
    if ($query && $data = $query->fetch_assoc()) {
        $mode = "edit";
        $nama_dokumen = $data['nama_dokumen'];
        $dokumen = $data['dokumen'];
    } else {
        echo "<script>alert('Data tidak ditemukan!'); document.location.href='index.php?hal=data-perizinan';</script>";
    }
}

if (isset($_POST['save'])) {
    $nama_dokumen = htmlspecialchars($_POST['nama_dokumen']);
    $mode = $_POST['mode'];

    $folder = "assets/perizinan";

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $dokumen = $_FILES['dokumen']['name'];
    $lokasi = $_FILES['dokumen']['tmp_name'];

    if (!empty($lokasi)) {
        $query = $con->query("SELECT dokumen FROM perizinan WHERE id='$edit_id'");
        $data = $query->fetch_assoc();

        if (!empty($data['dokumen']) && file_exists($folder . '/' . $data['dokumen'])) {
            unlink($folder . '/' . $data['dokumen']);
        }
        move_uploaded_file($lokasi, $folder . '/' . $dokumen);
    }

    if ($mode == "edit" && empty($dokumen)) {
        $dokumen = $_POST['existing_dokumen'];
    }

    if ($mode == "add") {
        $con->query("INSERT INTO perizinan (nama_dokumen, dokumen) VALUES ('$nama_dokumen', '$dokumen')");
        echo "<script>alert('Data berhasil ditambahkan'); document.location.href='index.php?hal=data-perizinan';</script>";
    } elseif ($mode == "edit") {
        $edit_id = $_POST['edit_id'];
        $con->query("UPDATE perizinan SET nama_dokumen='$nama_dokumen', dokumen='$dokumen' WHERE id='$edit_id'");
        echo "<script>alert('Data berhasil diupdate'); document.location.href='index.php?hal=data-perizinan';</script>";
    }
}
if (isset($_GET['delete'])) {
    $id = htmlspecialchars($_GET['delete']);
    $query = $con->query("DELETE FROM perizinan WHERE id='$id'");

    if ($query) {
        echo "<script>alert('Data berhasil dihapus'); document.location.href='index.php?hal=data-perizinan';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . $con->error . "'); document.location.href='index.php?hal=data-perizinan';</script>";
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
                        <label for="nama_dokumen" class="form-label">Nama Dokumen</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="nama_dokumen" id="nama_dokumen" value="<?= htmlspecialchars($nama_dokumen); ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="dokumen" class="form-label">Dokumen</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="dokumen" id="dokumen" onchange="previewFile()">
                            <input type="hidden" name="existing_dokumen" value="<?php echo $dokumen; ?>">
                        </div>
                        <?php if (!empty($dokumen)) : ?>
                            <div id="preview-container" class="mt-3">
                                <?php
                                $filePath = "assets/perizinan/" . htmlspecialchars($dokumen);
                                $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif'])) {
                                    echo '<label class="form-label">Foto Dokumen Sebelumnya</label> <br> <img src="' . $filePath . '" alt="Preview Dokumen" class="img-thumbnail" style="max-width: 200px;">';
                                } elseif (strtolower($fileExtension) === 'pdf') {
                                    echo '<label class="form-label">Preview Dokumen Sebelumnya</label> <br> <iframe src="' . $filePath . '" style="width: 50%; height: 200px;" frameborder="0"></iframe>';
                                } else {
                                    echo '<a href="' . $filePath . '" target="_blank" class="btn btn-danger">Lihat Dokumen Sebelumnya</a>';
                                }
                                ?>
                            </div>
                        <?php endif; ?>
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
                    <h6>Daftar Perizinan</h6>
                </div>
                <div class="card shadow p-2">
                    <div class="table-responsive">
                        <table id="myTable" class="display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Nama DOkumen</th>
                                    <th>Dokumen</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ambil = $con->query("SELECT * FROM perizinan");
                                $no = 1;
                                while ($pecah = $ambil->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $pecah["id"]; ?></td>
                                        <td><?php echo $pecah["nama_dokumen"]; ?></td>
                                        <td>
                                            <?php
                                            if (!empty($pecah['dokumen'])) {
                                                $dokumenPath = 'assets/perizinan/' . htmlspecialchars($pecah['dokumen']);
                                                echo '<a href="' . $dokumenPath . '" target="_blank">Lihat Dokumen</a><br>';
                                            } ?>
                                        </td>
                                        <td>
                                            <a href="index.php?hal=data-perizinan&edit=<?php echo $pecah['id']; ?>" class="btn btn-primary btn-edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button class="btn btn-danger" name="delete">
                                                <a href="index.php?hal=data-perizinan&delete=<?php echo $pecah['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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