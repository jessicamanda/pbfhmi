<?php

$id = htmlspecialchars($_GET['id_obat']);
if (isset($_SESSION['admin']['nama_lengkap'])) {
  $products = $con->query("SELECT obat.id AS id_obat, obat.nama_obat, obat.margin, obat.deskripsi, obat.foto, pembelian.harga_jual AS harga_terbaru, stok.stok FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT p1.* FROM pembelian p1 WHERE p1.created_at = (SELECT MAX(p2.created_at) FROM pembelian p2 WHERE p2.nama_obat = p1.nama_obat AND p2.status = 'Sudah Datang')) pembelian ON obat.nama_obat = pembelian.nama_obat WHERE obat.id  = '" . htmlspecialchars($_GET['id_obat']) . "'");
  $product = $products->fetch_assoc();
} else {
  echo "
          <script>
              alert('Lakukan Login Terlebih Dahulu Sebelum Melakukan Pembelian');
              document.location.href='login-customer.php';
          </script>
      ";
}

?>
<div class="container">
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <img src="../assets/foto/obat/<?= $product['foto'] ?>" class="card-img-top" alt="https://picsum.photos/200">

      </div>
    </div>
    <div class="col-md-6">
      <div class="card border border-success border-1">
        <div class="card-body">
          <h4 class="card-title"><strong>Detail Produk</strong></h4>
          <p class="card-text"><strong>Nama produk:</strong><br> <?= $product['nama_obat'] ?></p>
          <p class="card-text"><strong>Deskripsi:</strong><br> <?= $product['deskripsi'] ?></p>
          <p class="card-text"><strong>Harga:</strong><br> Rp. <?= number_format($product['harga_terbaru'], 0, ',', '.') ?>,00</p>
          <p class="card-text"><strong>Stok:</strong> <?= $product['stok'] ?></p>
          <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#addToCartModal<?= $product['id_obat'] ?>">+ Keranjang</button>
        </div>

      </div>
    </div>
  </div>
</div>
<?php
?>


<div class="modal fade" id="addToCartModal<?= $product['id_obat'] ?>" tabindex="-1" aria-labelledby="addToCartLabel<?= $product['id_obat'] ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addToCartLabel<?= $product['id_obat'] ?>">Tambah ke Keranjang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="index.php?halaman=shop&add=<?= $product['id_obat'] ?>">
        <div class="modal-body">
          <p>Produk: <strong><?= $product['nama_obat'] ?></strong></p>
          <p>Harga: <strong>Rp <?= number_format($product['harga_terbaru'], 0, '', '.') ?></strong></p>
          <div class="mb-3">
            <label for="quantity<?= $product['id_obat'] ?>" class="form-label">Jumlah</label>
            <input type="number" class="form-control" name="jumlah" id="quantity<?= $product['id_obat'] ?>" value="1" min="1" max=<?= $product['stok'] ?> required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Tambah ke Keranjang</button>
        </div>
      </form>
    </div>
  </div>
</div>