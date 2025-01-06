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
    <!-- TABLE -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Daftar Pelanggan</h6>
                </div>
                <div class="card shadow p-2">
                    <div class="table-responsive">
                        <table id="myTable" class="display">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Nama Lengkap</th>
                                    <th>Username</th>
                                    <th>Nomor HP</th>
                                    <th>Alamat</th>
                                    <th>Tenggat SIPA</th>
                                    <th>Status SIPA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ambil = $con->query(" SELECT *, CASE 
                                        WHEN sipa > CURDATE() THEN 'Berlaku'
                                        WHEN sipa < CURDATE() THEN 'Tidak Berlaku' END AS status
                                FROM user WHERE role ='pelanggan'
                                ");
                                $no = 1;
                                while ($pecah = $ambil->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $pecah["id"]; ?></td>
                                        <td><?php echo $pecah["nama_lengkap"]; ?></td>
                                        <td><?php echo $pecah["username"]; ?></td>
                                        <td><?php echo $pecah["nohp"]; ?></td>
                                        <td><?php
                                        echo (isset($pecah["provinsi"]) ? $pecah["provinsi"] : '') . ', ' .
                                            (isset($pecah["kota"]) ? $pecah["kota"] : '') . ', ' .
                                            (isset($pecah["kecamatan"]) ? $pecah["kecamatan"] : '') . ', ' .
                                            (isset($pecah["kelurahan"]) ? $pecah["kelurahan"] : '') . ', ' .
                                            (isset($pecah["kode_pos"]) ? $pecah["kode_pos"] : '') . ', ' .
                                            (isset($pecah["alamat"]) ? $pecah["alamat"] : '');
                                        ?></td>
                                        <td><?php echo $pecah["sipa"]; ?></td>
                                        <td><?php echo $pecah["status"]; ?></td>
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