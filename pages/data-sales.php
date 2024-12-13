<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>

<?php
$mode = "add";
$edit_id = "";
$nama_lengkap = "";
$nik = "";
$username = "";
$password = "";
$tanggal_lahir = "";
$jam_masuk = "";
$jam_pulang = "";
$alamat = "";

if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $query = $con->query("SELECT * FROM sales WHERE id = '$edit_id'");
    if ($query && $data = $query->fetch_assoc()) {
        $mode = "edit";
        $nama_lengkap = $data['nama_lengkap'];
        $nik = $data['nik'];
        $username = $data['username'];
        $password = $data['password'];
        $tanggal_lahir = $data['tanggal_lahir'];
        $jam_masuk = $data['jam_masuk'];
        $jam_pulang = $data['jam_pulang'];
        $alamat = $data['alamat'];
    } else {
        echo "<script>alert('Data tidak ditemukan!'); document.location.href='index.php?hal=data-sales';</script>";
    }
}

if (isset($_POST['save'])) {
    $nama_lengkap = htmlspecialchars($_POST['nama_lengkap']);
    $nik = htmlspecialchars($_POST['nik']);
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $tanggal_lahir = htmlspecialchars($_POST['tanggal_lahir']);
    $jam_masuk = htmlspecialchars($_POST['jam_masuk']);
    $jam_pulang = htmlspecialchars($_POST['jam_pulang']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $jam_kerja = $jam_masuk . '-' . $jam_pulang;
    $mode = $_POST['mode'];

    if ($mode == "add") {
        $con->query("INSERT INTO sales (nama_lengkap, nik, username,password,tanggal_lahir, jam_masuk, jam_pulang, alamat, jam_kerja) VALUES ('$nama_lengkap', '$nik', '$username', '$password', '$tanggal_lahir', '$jam_masuk', '$jam_pulang', '$alamat', '$jam_kerja')");
        $con->query("INSERT INTO user (nama_lengkap, username,password, role) VALUES ('$nama_lengkap', '$username', '$password', 'sales')");
        echo "<script>alert('Data berhasil ditambahkan'); document.location.href='index.php?hal=data-sales';</script>";
    } elseif ($mode == "edit") {
        $edit_id = $_POST['edit_id'];
        $con->query("UPDATE sales SET nama_lengkap='$nama_lengkap', nik='$nik', username='$username', password='$password', tanggal_lahir='$tanggal_lahir', jam_masuk='$jam_masuk', jam_pulang='$jam_pulang', alamat='$alamat', jam_kerja='$jam_kerja' WHERE id='$edit_id'");
        echo "<script>alert('Data berhasil diupdate'); document.location.href='index.php?hal=data-sales';</script>";
    }
}
if (isset($_GET['delete'])) {
    $id = htmlspecialchars($_GET['delete']);
    $query = $con->query("DELETE FROM sales WHERE id='$id'");

    if ($query) {
        echo "<script>alert('Data berhasil dihapus'); document.location.href='index.php?hal=data-sales';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . $con->error . "'); document.location.href='index.php?hal=data-sales';</script>";
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
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" value="<?= htmlspecialchars($nama_lengkap); ?>" required>
                                </div>
                            </div>
                            <div class="">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="username" id="username" value="<?= htmlspecialchars($username); ?>" required>
                                </div>
                            </div>
                            <div class="">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="password" id="password" value="<?= htmlspecialchars($password); ?>" required>
                                </div>
                            </div>
                            <div class="">
                                <label for="nik" class="form-label">NIK</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="nik" id="nik" value="<?= htmlspecialchars($nik); ?>" required>
                                </div>
                            </div>
                            <div class="">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" value="<?= htmlspecialchars($tanggal_lahir); ?>" required>
                                </div>
                            </div>
                            <div class="">
                                <label for="jam_masuk" class="form-label">Jam Masuk</label>
                                <div class="input-group">
                                    <input type="time" class="form-control" name="jam_masuk" id="jam_masuk" value="<?= htmlspecialchars($jam_masuk); ?>" required>
                                </div>
                            </div>
                            <div class="">
                                <label for="jam_pulang" class="form-label">Jam Pulang</label>
                                <div class="input-group">
                                    <input type="time" class="form-control" name="jam_pulang" id="jam_pulang" value="<?= htmlspecialchars($jam_pulang); ?>" required>
                                </div>
                            </div>
                            <div class="">
                                <label for="alamat" class="form-label">Alamat</label>
                                <div class="input-group">
                                    <textarea class="form-control" rows="4" name="alamat" id="alamat"required><?= htmlspecialchars($alamat); ?></textarea>
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
                    <h6>Daftar Sales</h6>
                </div>
                <div class="card shadow p-2">
                    <div class="table-responsive">
                        <table id="myTable" class="display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Nama Sales</th>
                                    <th>NIK</th>
                                    <th>Username</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Jam Kerja</th>
                                    <th>Alamat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ambil = $con->query("SELECT * FROM sales");
                                $no = 1;
                                while ($pecah = $ambil->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $pecah["id"]; ?></td>
                                        <td><?php echo $pecah["nama_lengkap"]; ?></td>
                                        <td><?php echo $pecah["nik"]; ?></td>
                                        <td><?php echo $pecah["username"]; ?></td>
                                        <td><?php echo $pecah["tanggal_lahir"]; ?></td>
                                        <td><?php echo $pecah["jam_masuk"]; ?> - <?php echo $pecah["jam_pulang"]; ?></td>
                                        <td><?php echo $pecah["alamat"]; ?></td>
                                        <td>
                                            <a href="index.php?hal=data-sales&edit=<?php echo $pecah['id']; ?>" class="btn btn-primary btn-edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button class="btn btn-danger" name="delete">
                                                <a href="index.php?hal=data-sales&delete=<?php echo $pecah['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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