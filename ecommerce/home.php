<section class="hero">
    <div id="carouselExampleIndicators" class="carousel slide">
      <div class="carousel-indicators">
        <button
          type="button"
          data-bs-target="#carouselExampleIndicators"
          data-bs-slide-to="0"
          class="active"
          aria-current="true"
          aria-label="Slide 1"
        ></button>
        <button
          type="button"
          data-bs-target="#carouselExampleIndicators"
          data-bs-slide-to="1"
          aria-label="Slide 2"
        ></button>
        <button
          type="button"
          data-bs-target="#carouselExampleIndicators"
          data-bs-slide-to="2"
          aria-label="Slide 3"
        ></button>
      </div>
      <div class="carousel-inner">
        <div
          class="carousel-item bg-secondary ratio ratio-21x9 active"
          style="border-radius: 15px"
        >
          <img
            src="https://images.unsplash.com/photo-1603555501671-8f96b3fce8b5?q=80&w=2071&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
            class="d-block w-100"
            alt="car1"
            style="border-radius: 13px"
          />
        </div>
        <div
          class="carousel-item bg-secondary ratio ratio-21x9"
          style="border-radius: 15px"
        >
          <img
            src="https://images.unsplash.com/photo-1533079299928-78eb7250f457?q=80&w=1934&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
            class="d-block w-100"
            alt="car2"
            style="border-radius: 13px"
          />
        </div>
        <div
          class="carousel-item bg-secondary ratio ratio-21x9"
          style="border-radius: 15px"
        >
          <img
            src="https://images.unsplash.com/photo-1613892571289-39d5c649887a?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
            class="d-block w-100"
            alt="car3"
            style="border-radius: 13px"
          />
        </div>
      </div>
      <button
        class="carousel-control-prev"
        type="button"
        data-bs-target="#carouselExampleIndicators"
        data-bs-slide="prev"
      >
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button
        class="carousel-control-next"
        type="button"
        data-bs-target="#carouselExampleIndicators"
        data-bs-slide="next"
      >
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
</section>
<br><hr><br>
<section class="konsultasi">
    <h2>Temukan Obat Terbaik untuk Kesehatan Anda!</h2>
    <span class="mb-1 mt-0 text-gray opacity-50">Solusi Terpercaya untuk Semua Kebutuhan Obat Anda, Hanya di Perdagangan Besar Farmasi Husada Mulia Indonesia</span>
    <p class="text-capitalize mb-1">Beli obat dengan kualitas terbaik dan harga bersaing di <b class="text-success">Perdagangan Besar Farmasi Husada Mulia Indonesia</b>. Temukan produk obat, suplemen, dan perawatan kesehatan yang tepat untuk Anda.</p>
    <center>
        <a href="?halaman=shop" class="btn btn-success text-light shadow mt-3" style="border-radius: 15px; width: 200px; height: 40px;">Beli Sekarang</a>
    </center>
</section>
<br><hr><br>
<section class="ca-1">
    <h3 class="mb-4">Produk Terbaru</h3>
    <div class="row">
        <div class="owl-carousel owl-theme">
            <?php
                $getProd1 = $con->query("SELECT obat.id, obat.nama_obat, obat.foto, stok.stok, pembelian.harga AS harga_terbaru FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT nama_obat, harga FROM pembelian WHERE tgl = (SELECT MAX(tgl) FROM pembelian AS p WHERE p.nama_obat = pembelian.nama_obat)) pembelian ON obat.nama_obat = pembelian.nama_obat ORDER BY obat.created_at DESC;");
                foreach($getProd1 as $prod1){
            ?>
                    <div class="card h-100 shadow-sm">
                        <img src="../assets/foto/obat/<?= $prod1['foto']?>" class="card-img-top" style="" alt="...">
                        <div class="card-body">
                            <h6 class="card-title mb-1"><?= $prod1['nama_obat']?></h6>
                            <p class="card-text" style="margin-top: -8px;">
                              <h3 class="mt-1">Rp. <?= number_format($prod1['harga_terbaru'],0,'','.')?></h3>
                              <h6 class="mt-1 text-muted">Stok <?= $prod1['stok']?></h6>
                                <a href="index.php?halaman=detail_produk&id_produk=<?= $prod1['id']?>" class="stretched-link"></a>
                            </p>
                        </div>
                    </div>
            <?php }?>
        </div>
    </div>
</section>
<br><hr><br>
<section class="ca-1">
    <h3 class="mb-4">Produk Terbaru</h3>
    <div class="row">
        <div class="owl-carousel owl-theme">
            <?php
                $getProd1 = $con->query("SELECT obat.id, obat.nama_obat, obat.foto, stok.stok, pembelian.harga AS harga_terbaru FROM obat LEFT JOIN stok ON obat.nama_obat = stok.nama_obat LEFT JOIN (SELECT nama_obat, harga FROM pembelian WHERE tgl = (SELECT MAX(tgl) FROM pembelian AS p WHERE p.nama_obat = pembelian.nama_obat)) pembelian ON obat.nama_obat = pembelian.nama_obat ORDER BY obat.created_at DESC;");
                foreach($getProd1 as $prod1){
            ?>
                    <div class="card h-100 shadow-sm">
                        <img src="../assets/foto/obat/<?= $prod1['foto']?>" class="card-img-top" style="" alt="...">
                        <div class="card-body">
                            <h6 class="card-title mb-1"><?= $prod1['nama_obat']?></h6>
                            <p class="card-text" style="margin-top: -8px;">
                              <h3 class="mt-1">Rp. <?= number_format($prod1['harga_terbaru'],0,'','.')?></h3>
                              <h6 class="mt-1 text-muted">Stok <?= $prod1['stok']?></h6>
                                <a href="index.php?halaman=detail_produk&id_produk=<?= $prod1['id']?>" class="stretched-link"></a>
                            </p>
                        </div>
                    </div>
            <?php }?>
        </div>
    </div>
</section>
<br><hr><br>
<div class="card shadow p-3 text-light" style="background: #0F5220; background: linear-gradient(90deg, rgba(12,223,103,1) 0%, rgba(15, 82, 32) 100%);">
    <center>
    <b>Sistem Informasi Manajemen Perdagangan Besar Farmasi Husada Mulia Indonesia</b>
    </center>
    <br><br>
    <div class="row">
        <div class="col-md-4">
            <center class="mb-3">
            <h1><b>300+</b></h1>
            <b>Produk Terjual</b>
            </center>
        </div>
        <div class="col-md-4">
            <center class="mb-3">
            <h1><b>150+</b></h1>
            <b>Obat</b>
            </center>

        </div>
        <div class="col-md-4">
            <center class="mb-3">
            <h1><b>100+</b></h1>
            <b>Pembeli</b>
            </center>
        </div>
    </div>
</div>
<br><hr><br><br>