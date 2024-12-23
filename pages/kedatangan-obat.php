<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php
if (isset($_POST['save'])) {
    $id_pembelian = $_POST['id'];
    $tgl_terima = $_POST['tgl_terima'];
    $nama_obat = $_POST['nama_obat'];
    $jumlah_terima = $_POST['jumlah_terima'];
    $id_tempat = $_POST['id_tempat'];

    $query_insert = "INSERT INTO terima (id_pembelian, tgl_terima, nama_obat, jumlah_terima, id_tempat) 
                     VALUES ('$id_pembelian', '$tgl_terima', '$nama_obat', '$jumlah_terima', '$id_tempat')";

    $query_update = "UPDATE pembelian SET status='Sudah Datang' WHERE id_pembelian = '$id_pembelian'";


    if ($con->query($query_insert) && $con->query($query_update)) {
        $stok = $con->query("SELECT * FROM stok WHERE nama_obat = '$nama_obat'");

        if ($stok->num_rows > 0) {
            $con->query("UPDATE stok 
                     SET stok = stok + $jumlah_terima 
                     WHERE nama_obat = '$nama_obat'");
        } else {
            $con->query("INSERT INTO stok (nama_obat, stok) 
                     VALUES ('$nama_obat', '$jumlah_terima')");
        }

        echo "<script>alert('Data dan stok berhasil diperbarui'); document.location.href='index.php?hal=kedatangan-obat';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data: " . $con->error . "');</script>";
    }
}
?>

<style>
    th,
    td {
        text-align: center;
        vertical-align: middle;
        padding: 8px;
    }
</style>

<?php
$ambil = $con->query("SELECT pembelian.id_pembelian, pembelian.tgl, pembelian.nama_obat, pembelian.jumlah, 
pembelian.namasuplier, terima.tgl_terima, terima.jumlah_terima
FROM pembelian
LEFT JOIN terima ON pembelian.id_pembelian = terima.id_pembelian
WHERE pembelian.id_pembelian
ORDER BY pembelian.id_pembelian, terima.tgl_terima");


$data = [];
while ($row = $ambil->fetch_assoc()) {
    $id_pembelian = $row['id_pembelian'];
    if (!isset($data[$id_pembelian])) {
        $data[$id_pembelian] = [
            'tgl' => $row['tgl'],
            'nama_obat' => $row['nama_obat'],
            'jumlah' => $row['jumlah'],
            'namasuplier' => $row['namasuplier'],
            'terima' => [],
        ];
    }
    $data[$id_pembelian]['terima'][] = [
        'tgl_terima' => $row['tgl_terima'],
        'jumlah_terima' => $row['jumlah_terima'],
    ];
}
?>

<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Daftar Pembelian</h6>
            </div>
            <div class="card shadow p-2">
                <div class="table-responsive">
                    <table id="myTable" class="display table">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">Tanggal Order</th>
                                <th rowspan="2">Nama Obat</th>
                                <th rowspan="2">Jumlah Order</th>
                                <th rowspan="2">Nama Suplier</th>
                                <th colspan="2" style="text-align: center;">Terima</th>
                                <th rowspan="2">Sisa yang harus diterima</th>
                                <th rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                <th>Tanggal Terima</th>
                                <th>Jumlah Terima</th>
                            </tr>
                        </thead>
                        <!-- <tbody>
                            <?php
                            $no = 1;
                            foreach ($data as $id_pembelian => $item) {
                                $sisa = $item['jumlah'];
                                foreach ($item['terima'] as $terima) {
                                    $sisa -= $terima['jumlah_terima'];
                                }
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($item['tgl']); ?></td>
                                    <td><?php echo htmlspecialchars($item['nama_obat']); ?></td>
                                    <td><?php echo htmlspecialchars($item['jumlah']); ?></td>
                                    <td><?php echo htmlspecialchars($item['namasuplier']); ?></td>
                                    <td>
                                        <?php
                                        foreach ($item['terima'] as $terima) {
                                            echo htmlspecialchars($terima['tgl_terima']) . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($item['terima'] as $terima) {
                                            echo htmlspecialchars($terima['jumlah_terima']) . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($sisa); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-add" data-toggle="modal" data-target="#terima"
                                            data-id="<?php echo htmlspecialchars($id_pembelian); ?>"
                                            data-nama_obat="<?php echo htmlspecialchars($item['nama_obat']); ?>"
                                            data-tgl="<?php echo htmlspecialchars($item['tgl']); ?>"
                                            data-jumlah="<?php echo htmlspecialchars($item['jumlah']); ?>">
                                            Terima
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody> -->
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($data as $id_pembelian => $item) {
                                $sisa = $item['jumlah'];
                                // Menampilkan setiap baris untuk setiap entri 'terima'
                                foreach ($item['terima'] as $terima) {
                                    $sisa -= $terima['jumlah_terima'];
                            ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($item['tgl']); ?></td>
                                        <td><?php echo htmlspecialchars($item['nama_obat']); ?></td>
                                        <td><?php echo htmlspecialchars($item['jumlah']); ?></td>
                                        <td><?php echo htmlspecialchars($item['namasuplier']); ?></td>
                                        <td><?php echo htmlspecialchars($terima['tgl_terima']); ?></td>
                                        <td><?php echo htmlspecialchars($terima['jumlah_terima']); ?></td>
                                        <td><?php echo htmlspecialchars($sisa); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-add" data-toggle="modal" data-target="#terima"
                                                data-id="<?php echo htmlspecialchars($id_pembelian); ?>"
                                                data-nama_obat="<?php echo htmlspecialchars($item['nama_obat']); ?>"
                                                data-tgl="<?php echo htmlspecialchars($item['tgl']); ?>"
                                                data-jumlah="<?php echo htmlspecialchars($item['jumlah']); ?>">
                                                Terima
                                            </button>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>

                    </table>
                </div>
            </div>

            <div class="modal fade" id="terima" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Terima Obat</h5>
                        </div>
                        <form action="" method="post" id="terima_form">
                            <div class="modal-body">
                                <input type="hidden" class="form-control" name="id" id="id_pembelian" readonly>
                                <div>
                                    <label for="tgl" class="form-label">Tanggal Order</label>
                                    <input type="date" class="form-control" name="tgl" id="tgl" readonly>
                                </div>
                                <div>
                                    <label for="nama_obat" class="form-label">Nama Obat</label>
                                    <input type="text" class="form-control" name="nama_obat" id="nama_obat" readonly>
                                </div>
                                <div>
                                    <label for="jumlah" class="form-label">Jumlah Order</label>
                                    <input type="text" class="form-control" name="jumlah" id="jumlah" readonly>
                                </div>
                                <div>
                                    <label for="tgl_terima" class="form-label">Tanggal Terima</label>
                                    <input type="date" class="form-control" name="tgl_terima" id="tgl_terima" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div>
                                    <label for="jumlah_terima" class="form-label">Jumlah Terima</label>
                                    <input type="text" class="form-control" name="jumlah_terima" id="jumlah_terima" required>
                                </div>
                                <div>
                                    <label for="tempat_penyimpanan" class="form-label">Tempat Penyimpanan</label>
                                    <select class="form-control" name="id_tempat" id="id_tempat" required>
                                        <option value="" disabled selected>Pilih Tempat Penyimpanan</option>
                                        <?php
                                        $tempat = $con->query("SELECT id_tempat, nama_tempat FROM tempat_penyimpanan");
                                        while ($row = $tempat->fetch_assoc()) {
                                            echo "<option value='" . htmlspecialchars($row['id_tempat']) . "'>" . htmlspecialchars($row['nama_tempat']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div>
                                    <label for="penerima" class="form-label">Penerima</label>
                                    <input type="text" class="form-control" name="penerima" id="penerima" value="<?= $_SESSION['admin']['nama_lengkap'] ?>" readonly>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="save">Simpan Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                $(document).ready(function() {
                    $('#myTable').DataTable();
                    $('.btn-add').on('click', function() {
                        $('#id_pembelian').val($(this).data('id'));
                        $('#tgl').val($(this).data('tgl'));
                        $('#nama_obat').val($(this).data('nama_obat'));
                        $('#jumlah').val($(this).data('jumlah'));
                        $('#tgl_terima').val('<?php echo date('Y-m-d'); ?>');
                    });
                });
            </script>
        </div>
    </div>
</div>