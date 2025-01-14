<section class="hero">
  <div id="carouselExampleIndicators" class="carousel slide">
    <div class="carousel-indicators">
      <button
        type="button"
        data-bs-target="#carouselExampleIndicators"
        data-bs-slide-to="0"
        class="active"
        aria-current="true"
        aria-label="Slide 1"></button>
      <button
        type="button"
        data-bs-target="#carouselExampleIndicators"
        data-bs-slide-to="1"
        aria-label="Slide 2"></button>
      <button
        type="button"
        data-bs-target="#carouselExampleIndicators"
        data-bs-slide-to="2"
        aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
      <div
        class="carousel-item bg-secondary ratio ratio-21x9 active"
        style="border-radius: 15px">
        <img
          src="https://images.unsplash.com/photo-1603555501671-8f96b3fce8b5?q=80&w=2071&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
          class="d-block w-100"
          alt="car1"
          style="border-radius: 13px" />
      </div>
      <div
        class="carousel-item bg-secondary ratio ratio-21x9"
        style="border-radius: 15px">
        <img
          src="https://images.unsplash.com/photo-1533079299928-78eb7250f457?q=80&w=1934&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
          class="d-block w-100"
          alt="car2"
          style="border-radius: 13px" />
      </div>
      <div
        class="carousel-item bg-secondary ratio ratio-21x9"
        style="border-radius: 15px">
        <img
          src="https://images.unsplash.com/photo-1613892571289-39d5c649887a?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
          class="d-block w-100"
          alt="car3"
          style="border-radius: 13px" />
      </div>
    </div>
    <button
      class="carousel-control-prev"
      type="button"
      data-bs-target="#carouselExampleIndicators"
      data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button
      class="carousel-control-next"
      type="button"
      data-bs-target="#carouselExampleIndicators"
      data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</section>
<br>
<hr><br>
<section class="konsultasi">
  <h2>Temukan Obat Terbaik untuk Kesehatan Anda!</h2>
  <span class="mb-1 mt-0 text-gray opacity-50">Solusi Terpercaya untuk Semua Kebutuhan Obat Anda, Hanya di Perdagangan Besar Farmasi Husada Mulia Indonesia</span>
  <p class="text-capitalize mb-1">Beli obat dengan kualitas terbaik dan harga bersaing di <b class="text-success">Perdagangan Besar Farmasi Husada Mulia Indonesia</b>. Temukan produk obat, suplemen, dan perawatan kesehatan yang tepat untuk Anda.</p>
  <center>
    <a href="?halaman=shop" class="btn btn-success text-light shadow mt-3" style="border-radius: 15px; width: 200px; height: 40px;">Beli Sekarang</a>
  </center>
</section>
<br>
<hr><br>
<section class="ca-1">
  <h3 class="mb-4">Produk Terbaru</h3>
  <div class="row">
    <div class="owl-carousel owl-theme">
      <?php
      $getProd1 = $con->query("SELECT obat.id, obat.nama_obat, obat.foto, obat.margin, stok.stok, pembelian.harga_jual AS harga_terbaru FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT nama_obat, harga_jual FROM pembelian WHERE created_at = (SELECT MAX(created_at) FROM pembelian AS p WHERE p.nama_obat = pembelian.nama_obat AND p.status = 'Sudah Datang')) pembelian ON obat.nama_obat = pembelian.nama_obat ORDER BY obat.created_at DESC;");
      foreach ($getProd1 as $prod1) {
      ?>
        <div class="card h-100 shadow-sm">
          <img src="../assets/foto/obat/<?= $prod1['foto'] ?>" class="card-img-top" style="" alt="...">
          <div class="card-body">
            <h6 class="card-title mb-1"><?= $prod1['nama_obat'] ?></h6>
            <p class="card-text" style="margin-top: -8px;">
            <h3 class="mt-1">Rp. <?= number_format($prod1['harga_terbaru'], 0, '', '.') ?></h3>
            <h6 class="mt-1 text-muted">Stok <?= $prod1['stok'] ?></h6>
            <a href="index.php?halaman=detail_produk&id_obat=<?= $prod1['id'] ?>" class="stretched-link"></a>
            </p>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</section>
<br>
<hr><br>
<section class="ca-1">
  <h3 class="mb-4">Produk Terbaru</h3>
  <div class="row">
    <div class="owl-carousel owl-theme">
      <?php
      $getProd1 = $con->query("SELECT obat.id, obat.nama_obat, obat.foto, obat.margin, stok.stok, pembelian.harga_jual AS harga_terbaru FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT nama_obat, harga_jual FROM pembelian WHERE created_at = (SELECT MAX(created_at) FROM pembelian AS p WHERE p.nama_obat = pembelian.nama_obat AND p.status = 'Sudah Datang')) pembelian ON obat.nama_obat = pembelian.nama_obat ORDER BY obat.created_at DESC;");
      foreach ($getProd1 as $prod1) {
      ?>
        <div class="card h-100 shadow-sm">
          <img src="../assets/foto/obat/<?= $prod1['foto'] ?>" class="card-img-top" style="" alt="...">
          <div class="card-body">
            <h6 class="card-title mb-1"><?= $prod1['nama_obat'] ?></h6>
            <p class="card-text" style="margin-top: -8px;">
            <h3 class="mt-1">Rp. <?= number_format($prod1['harga_terbaru'], 0, '', '.') ?></h3>
            <h6 class="mt-1 text-muted">Stok <?= $prod1['stok'] ?></h6>
            <a href="index.php?halaman=detail_produk&id_obat=<?= $prod1['id'] ?>" class="stretched-link"></a>
            </p>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</section>
<br>
<hr><br>
<div style="background-color: #08592F; color: #fff;">
  <footer class="row px-3 py-4" style="width: 100%; font-size: 13px;">
    <!-- Bagian Layanan Pengaduan Konsumen -->
    <div class="col-md-5 mb-3">
      <img src="https://simkhm.id/wonorejo/admin/dist/assets/img/khm.png" class="mb-3" alt="Logo" style="max-width: 120px;" />
      <p><strong>Layanan Pengaduan Konsumen</strong></p>
      <p>Direktorat Jenderal Perlindungan Konsumen dan Tertib Niaga Kementerian Perdagangan RI</p>
      <p>Kontak WhatsApp: +62 853 1111 1010</p>
    </div>

    <!-- Bagian Alamat dan Kontak -->
    <div class="col-md-5 mb-3">
      <p><strong>Hubungi Kami</strong></p>

      <div class="mb-3">
        <h6>Alamat KHM Wonorejo</h6>
        <p class="m-0">Jl. Nasional 25, Krajan, Wonorejo, Kec. Kedungjajang, Kabupaten Lumajang, Jawa Timur 67358</p>
        <a href="https://wa.me/6282233880001" class="text-decoration-none text-white" target="_blank" rel="noopener noreferrer">
          <i class="bi bi-whatsapp"></i> CS +62 822 3388 0001
        </a>
      </div>

      <div class="mb-3">
        <h6>Alamat KHM Klakah</h6>
        <p class="m-0">Jl. Raya Lumajang - Probolinggo, Kec. Klakah, Kabupaten Lumajang, Jawa Timur 67356</p>
        <a href="https://wa.me/6281355550275" class="text-decoration-none text-white" target="_blank" rel="noopener noreferrer">
          <i class="bi bi-whatsapp"></i> CS +62 813 5555 0275
        </a>
      </div>

      <div class="mb-3">
        <h6>Alamat KHM Tunjung</h6>
        <p class="m-0">Jl. Tunjung, Krajan Dua, Tunjung, Kec. Randuagung, Kabupaten Lumajang, Jawa Timur 67354</p>
        <a href="https://wa.me/6281234571010" class="text-decoration-none text-white" target="_blank" rel="noopener noreferrer">
          <i class="bi bi-whatsapp"></i> CS +62 812 3457 1010
        </a>
      </div>
    </div>

    <!-- Bagian Media Sosial -->
    <div class="col-md-2 mb-3">
      <p><strong>Ikuti Kami</strong></p>
      <div class="d-flex gap-3">
        <a href="https://www.instagram.com/husadamuliaofficial/" class="text-decoration-none text-white" target="_blank">
          <i class="bi bi-instagram fs-4"></i>
        </a>
        <a href="https://www.facebook.com/profile.php?id=61553748481575" class="text-decoration-none text-white" target="_blank">
          <i class="bi bi-facebook fs-4"></i>
        </a>
        <a href="https://www.tiktok.com/@husada_mulia" class="text-decoration-none text-white" target="_blank">
          <i class="bi bi-tiktok fs-4"></i>
        </a>
        <a href="https://www.youtube.com/@sahabatmuliaofficial1463" class="text-decoration-none text-white" target="_blank">
          <i class="bi bi-youtube fs-4"></i>
        </a>
      </div>
    </div>
  </footer>
</div>