<?php include "koneksi.php"; ?>
<?php
date_default_timezone_set('Asia/Jakarta');

$username = $_SESSION['admin']['username'];

$ambil2 = $con->query("SELECT nama_lengkap,jam_masuk, jam_pulang FROM sales WHERE username='$username';");
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
            <input type="text" name="nama_lengkap" class="form-control form-control-lg" placeholder="nama_lengkap" aria-label="nama_lengkap" value="<?= $pecah['nama_lengkap'] ?>">
          </div>
          <div class="mb-3">
            <label for="" class="form-label">Jam Kerja/Shift</label>
            <input type="text" name="jam_kerja" class="form-control form-control-lg" placeholder="jam_kerja" aria-label="jam_kerja" value="<?= $pecah['jam_masuk'] ?>-<?= $pecah['jam_pulang'] ?>">
          </div>
          <div class="form-group" style="margin-top:20px">
            <label for="" class="form-label">Foto Absen</label>
            <input type="file" name="foto_absen" id="foto_absen" class="form-control" accept="image/*"
              capture="user">
          </div>

          <input type="hidden" name="latitude" id="latitude">
          <input type="hidden" name="longitude" id="longitude">
          <input type="hidden" type="date" name="tanggal_absen" class="form-control" value="<?php echo date("Y-m-d"); ?>" placeholder="">
          <input type="hidden" type="date" name="jam_masuk" class="form-control" value="<?php echo date("H:i:s"); ?>" placeholder="">

          <div class="text-center">
            <button type="submit" id="submit-button" name="save" class="btn btn-lg btn-lg w-100 mt-4 mb-0 text-white" style="background-color: #0F5220;">Submit</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

<?php
if (isset($_POST['save'])) {

  $tanggal_absen = htmlspecialchars($_POST["tanggal_absen"]);
  $nama_karyawan = htmlspecialchars($_POST["nama_lengkap"]);
  $latitude = htmlspecialchars($_POST["latitude"]);
  $longitude = htmlspecialchars($_POST["longitude"]);

  $folder = "assets/foto/absensi";

  if (!is_dir($folder)) {
    mkdir($folder, 0755, true);
  }

  $foto = $_FILES['foto_absen']['name'];
  $allowed_extensions = ["jpg", "jpeg", "png", "gif", "webp", "heic"];
  $file_extension = strtolower(pathinfo($foto, PATHINFO_EXTENSION));

  if (!in_array($file_extension, $allowed_extensions)) {
    echo "<script>alert('Hanya file JPG atau PNG yang diizinkan!');</script>";
    exit;
  }
  if (!is_dir($folder)) {
    mkdir($folder, 0755, true);
  }

  $foto = $_FILES['foto_absen']['name'];
  $lokasi = $_FILES['foto_absen']['tmp_name'];
  if (!empty($foto)) {
    $foto_kompres = compressPhoto($lokasi, $folder, $foto);
    if (!$foto_kompres) {
      echo "<script>alert('Format file tidak didukung')</script>";
      exit;
    }
    $foto = $foto_kompres;
  }

  $jam_pulang = $con->query("SELECT * FROM sales")->fetch_assoc();
  $jam_masuk = date('H:i:s');
  list($h, $m, $s) = explode(":", $jam_masuk);
  $dtAwal = $h . $m . $s;

  $jam_kerja = ($jam_pulang["jam_masuk"]);
  list($h, $m, $s) = explode(":", $jam_kerja);
  $dtAkhir = $h . $m . $s;

  $jmltelat = (int)($dtAkhir) - (int)($dtAwal);

  if ($jmltelat >= 0) {
    $status_masuk = "Tepat Waktu";
  } else {
    $status_masuk = "Telat";
  };

  $query = mysqli_query($con, "SELECT * FROM absensi JOIN sales WHERE  username='$username' AND DATE(tanggal_absen) = CURDATE();");

  if ($query->num_rows > 0) {
    echo "
   <script>
   alert('Anda telah melakukan absen hari ini');
   document.location.href='index.php?hal=absen-datang';
   </script>

   ";
  } else {

    $jam_pulang = $con->query("SELECT * FROM sales WHERE jam_kerja= '$_POST[jam_kerja]'")->fetch_assoc();

    $con->query("INSERT INTO absensi
       (tanggal_absen, foto_absen,jam_kerja, jam_masuk, telat_masuk, status_masuk, nama_karyawan, jam_pulang, latitude, longitude)
       VALUES ('$tanggal_absen', '$foto', '$jam_pulang[jam_masuk]', '$jam_masuk', '$jmltelat','$status_masuk','$nama_karyawan', '$jam_pulang[jam_pulang]', '$latitude', '$longitude')");

    if (mysqli_affected_rows($con) > 0) {
      echo "
      <script>
      document.location.href='index.php?hal=riwayat-absen-datang';
      </script>
      ";
    } else {
      echo "
   <script>
   alert('GAGAL!');
   document.location.href='index.php?hal=absen-datang';
   </script>

   ";
    }
  }
};


function compressPhoto($file, $target_dir, $filename, $quality = 75)
{
  $imageFileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
  $target_file = $target_dir . '/' . $filename;

  if (class_exists('Imagick')) {
    $imagick = new Imagick($file);

    $new_width = $imagick->getImageWidth() * 0.6;
    $new_height = $imagick->getImageWidth() * 0.6;

    $imagick->resizeImage($new_width, $new_height, Imagick::FILTER_LANCZOS, 1);

    switch ($imageFileType) {
      case 'jpg':
      case 'jpeg':
        $imagick->setImageFormat('jpeg');
        break;
      case 'png':
        $imagick->setImageFormat('png');
        break;
      case 'gif':
        $imagick->setImageFormat('gif');
        break;
      case 'webp':
        $imagick->setImageFormat('webp');
        break;
      case 'heic':
        $imagick->setImageFormat('jpeg');
        $filename = pathinfo($filename, PATHINFO_FILENAME) . '.jpg';
        $target_file = $target_dir . '/' . $filename;
        break;
    }

    $imagick->setImageCompressionQuality($quality);
    $imagick->writeImage($target_file);
    $imagick->clear();
    $imagick->destroy();
  } else {
    list($width, $height) = getimagesize($file);
    $new_width = $width * 0.6;
    $new_height = $height * 0.6;

    $image_p = imagecreatetruecolor($new_width, $new_height);
    switch ($imageFileType) {
      case 'jpg':
      case 'jpeg':
        $image = imagecreatefromjpeg($file);
        break;
      case 'png':
        $image = imagecreatefrompng($file);
        break;
      case 'gif':
        $image = imagecreatefromgif($file);
        break;
      case 'webp':
        $image = imagecreatefromwebp($file);
        break;
      default:
        return false;
    }

    imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

    switch ($imageFileType) {
      case 'jpg':
      case 'jpeg':
        imagejpeg($image_p, $target_file, $quality);
        break;
      case 'png':
        imagepng($image_p, $target_file, $quality / 10);
        break;
      case 'gif':
        imagegif($image_p, $target_file);
        break;
      case 'webp':
        imagewebp($image_p, $target_file, $quality);
        break;
    }

    imagedestroy($image);
    imagedestroy($image_p);;
  }

  return $filename;
}
?>



<script>
  document.addEventListener("DOMContentLoaded", function() {
    const submitButton = document.getElementById("submit-button");
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        document.getElementById("latitude").value = position.coords.latitude;
        document.getElementById("longitude").value = position.coords.longitude;
        submitButton.disabled = false;
      }, function(error) {
        alert("Geolocation error: " + error.message);
      });
    } else {
      alert("Geolocation is not supported by this browser.");
    }
  });
</script>