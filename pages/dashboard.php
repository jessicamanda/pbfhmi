<div class="container-fluid">
    <?php if ($_SESSION['admin']['role'] === 'sales'): ?>
        <div class="row ">
            <div class="col-md-3">
                <div class="card shadow p-3 mb-3">
                    <a href="index.php?hal=absen-datang">
                    <div class="position-relative p-3">
                        <center>
                            <h2><i class="bi bi-box-arrow-in-right"></i></h2>
                            <p>Absen Datang</p>
                        </center>
                    </div>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
            <div class="card shadow p-3 mb-3">
                    <a href="index.php?hal=absen-pulang">
                    <div class="position-relative p-3">
                        <center>
                            <h2><i class="bi bi-box-arrow-in-right"></i></h2>
                            <p>Absen Pulang</p>
                        </center>
                    </div>
                    </a>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>