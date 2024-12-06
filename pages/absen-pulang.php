<?php include "koneksi.php"; ?>
<?php
date_default_timezone_set('Asia/Jakarta');

$nama_lengkap = $_SESSION['admin']['nama_lengkap'];

$ambil2 = $con->query("SELECT nama_lengkap, jam_masuk, jam_pulang FROM sales WHERE nama_lengkap='$nama_lengkap';");
$pecah = $ambil2->fetch_assoc();

?>
<div class="page-header">
    <div class="container">
        <div class="card" style="max-width: 360px;">
            <div class="card-header pb-0 text-start">
                <p class="mb-0">Absensi Datang</p>
                <h4 class="font-weight-bolder" style="color: #0F5220;"><?= $appname ?></h4>
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="" class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control form-control-lg" placeholder="nama_lengkap" aria-label="nama_lengkap" value="<?= $pecah['nama_lengkap'] ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Jam Kerja/Shift</label>
                        <input type="text" name="jam_kerja" class="form-control form-control-lg" placeholder="jam_kerja" aria-label="jam_kerja" value="<?= $pecah['jam_masuk'] ?>-<?= $pecah['jam_pulang'] ?>" readonly>
                    </div>
                    <div class="form-group" style="margin-top:20px">
                        <label for="" class="form-label">Foto Absen</label>
                        <input type="file" name="foto_absen" id="foto_absen" class="form-control" accept="image/*" capture="user">
                    </div>
                    <input type="hidden" name="tanggal_absen_plg" class="form-control" value="<?php echo date("Y-m-d"); ?>">
                    <input type="hidden" name="jam_pulang" class="form-control" value="<?php echo date("H:i:s"); ?>">

                    <div class="text-center">
                        <button type="submit" name="save" class="btn btn-lg btn-lg w-100 mt-4 mb-0 text-white" style="background-color: #0F5220;">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php

if (isset($_POST['save'])) {

    $tanggal_absen_plg = htmlspecialchars($_POST["tanggal_absen_plg"]);
    $nama_lengkap = htmlspecialchars($_POST["nama_lengkap"]);
    $folder = "assets/foto/absensi";

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $foto = $_FILES['foto_absen']['name'];
    $allowed_extensions = ["jpg", "jpeg", "png", "gif", "webp", "heic"];
    $file_extension = strtolower(pathinfo($foto, PATHINFO_EXTENSION));

    // Check if file extension is valid
    if (!in_array($file_extension, $allowed_extensions)) {
        echo "<script>alert('Hanya file JPG, PNG, GIF, WebP, atau HEIC yang diizinkan!');</script>";
        exit;
    }

    // Check for file size (max 5MB)
    if ($_FILES['foto_absen']['size'] > 5000000) { // 5MB limit
        echo "<script>alert('File size is too large!');</script>";
        exit;
    }

    if (!empty($foto)) {
        $lokasi = $_FILES['foto_absen']['tmp_name'];
        move_uploaded_file($lokasi, $folder . '/' . $foto);
    }

    $fotoupload = "assets/foto/absensi/$foto";

    if (file_exists($fotoupload)) {

        // Get the current time and calculate the difference
        $jam_plg = date('H:i:s');
        list($h, $m, $s) = explode(":", $jam_plg);
        $dtAwal = $h . $m . $s;

        $jam_kerja = $_POST["jam_kerja"];
        list($h, $m, $s) = explode(":", $jam_kerja);
        $dtAkhir = $h . $m . $s;

        // Calculate the time difference in seconds
        $jmlcepat = (int)($dtAkhir) - (int)($dtAwal);

        // Determine if the user is leaving early or on time
        $status_plg = ($jmlcepat > 0) ? "Pulang Cepat" : "Tepat Waktu";

        // Check if attendance for today already exists
        $query = mysqli_query($con, "SELECT * FROM absensi_pulang JOIN sales ON absensi_pulang.nama_lengkap = sales.nama_lengkap WHERE absensi_pulang.nama_lengkap='$nama_lengkap' AND DATE(tanggal_absen_plg) = CURDATE();");

        if ($query->num_rows > 0) {
            echo "
                <script>
                alert('Anda telah melakukan absen hari ini');
                document.location.href='index.php?halaman=absen-pulangplg';
                </script>
            ";
        } else {
            // Insert the attendance data into the database
            $con->query("INSERT INTO absensi_pulang
                (tanggal_absen_plg, foto_absen_plg, jam_kerja, jam_pulang, pulang_cepat, status_plg, nama_lengkap)
                VALUES ('$tanggal_absen_plg', '$foto', '$jam_kerja', '$jam_plg', '$jmlcepat', '$status_plg', '$nama_lengkap')
            ");

            if (mysqli_affected_rows($con) > 0) {
                echo "
                    <script>
                    document.location.href='index.php?halaman=absensi';
                    </script>
                ";
            } else {
                echo "
                    <script>
                    alert('GAGAL!');
                    document.location.href='index.php?halaman=absensi';
                    </script>
                ";
            }
        }
    }
};
?>
