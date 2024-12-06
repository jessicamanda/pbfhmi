<link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.css" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.4/js/dataTables.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php
if (isset($_POST['save'])) {
    $id_pembelian = $_POST['id'];
    $tgl_bayar = $_POST['tgl_bayar'];
    $nama_obat = $_POST['nama_obat'];
    $nominal = $_POST['nominal'];

    $folder = "assets/foto/tagihan";

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $foto = $_FILES['foto']['name'];
    $allowed_extensions = ["jpg", "jpeg", "png", "gif", "webp", "heic"];
    $file_extension = strtolower(pathinfo($foto, PATHINFO_EXTENSION));

    if (!in_array($file_extension, $allowed_extensions)) {
        echo "<script>alert('Hanya file JPG atau PNG yang diizinkan!');</script>";
        exit;
    }
    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $foto = $_FILES['foto']['name'];
    $lokasi = $_FILES['foto']['tmp_name'];
    if (!empty($foto)) {
        move_uploaded_file($lokasi, $folder . '/' . $foto);
    }


    $query_insert = "INSERT INTO pembayaran (id_pembelian, tgl_bayar, nama_obat, nominal,foto) 
                     VALUES ('$id_pembelian', '$tgl_bayar', '$nama_obat', '$nominal','$foto')";

    if ($con->query($query_insert)) {
        echo "<script>alert('Data berhasil ditambahkan'); document.location.href='index.php?hal=tagihan';</script>";
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
$ambil = $con->query("SELECT 
    pembelian.id_pembelian, pembelian.jumlah, pembelian.tgl, pembelian.nama_obat, pembelian.total, pembelian.namasuplier, pembelian.nohp, 
    pembelian.jatuh_tempo, pembayaran.tgl_bayar, pembayaran.foto, pembayaran.nominal,COALESCE(SUM(p.nominal), 0) AS total_bayar FROM pembelian 
    LEFT JOIN pembayaran AS p ON pembelian.id_pembelian = p.id_pembelian
    LEFT JOIN pembayaran ON pembelian.id_pembelian = pembayaran.id_pembelian
    GROUP BY pembelian.id_pembelian, pembelian.tgl, pembelian.nama_obat, 
    pembelian.jumlah, pembelian.total, pembelian.namasuplier, 
    pembelian.nohp, pembelian.jatuh_tempo, pembayaran.tgl_bayar, pembayaran.nominal
    HAVING (pembelian.total - total_bayar) != 0
    ORDER BY pembelian.id_pembelian, pembayaran.tgl_bayar;
    ");

$data = [];
while ($row = $ambil->fetch_assoc()) {
    $id_pembelian = $row['id_pembelian'];
    if (!isset($data[$id_pembelian])) {
        $data[$id_pembelian] = [
            'tgl' => $row['tgl'],
            'nama_obat' => $row['nama_obat'],
            'total' => $row['total'],
            'jumlah' => $row['jumlah'],
            'namasuplier' => $row['namasuplier'],
            'nohp' => $row['nohp'],
            'jatuh_tempo' => $row['jatuh_tempo'],
            'pembayaran' => [],
        ];
    }
    $data[$id_pembelian]['pembayaran'][] = [
        'tgl_bayar' => $row['tgl_bayar'],
        'nominal' => $row['nominal'],
        'foto' => $row['foto'],
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
                                <th rowspan="2">Total Tagihan</th>
                                <th rowspan="2">Nama Suplier</th>
                                <th rowspan="2">Kontak Suplier</th>
                                <th colspan="4" style="text-align: center;">Penerimaan</th>
                                <th rowspan="2">Jatuh Tempo</th>
                                <th rowspan="2">Tanggal Lunas</th>
                                <th rowspan="2">Sisa Tagihan</th>
                                <th rowspan="2">Aksi</th>
                            </tr>
                            <tr>
                                <th>Ke</th>
                                <th>Tanggal Terima</th>
                                <th>Nominal Terbayar</th>
                                <th>Foto Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $nomer = 1;
                            foreach ($data as $id_pembelian => $item) {
                                $tgl_lunas = end($item['pembayaran'])['tgl_bayar'] ?? '-';
                                $sisa = $item['total'];
                                if (!empty($item['pembayaran'])) {
                                    foreach ($item['pembayaran'] as $terima) {
                                        $sisa -= $terima['nominal'];
                                    }
                                }
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($item['tgl']); ?></td>
                                    <td><?php echo htmlspecialchars($item['nama_obat']); ?></td>
                                    <td><?php echo htmlspecialchars($item['jumlah']); ?></td>
                                    <td><?php echo htmlspecialchars($item['total']); ?></td>
                                    <td><?php echo htmlspecialchars($item['namasuplier']); ?></td>
                                    <td><?php echo htmlspecialchars($item['nohp']); ?></td>
                                    <td><?php
                                        foreach ($item['pembayaran'] as $pembayaran) {
                                            echo $nomer++ . '<br>';
                                        }
                                        ?></td>
                                    <td>
                                        <?php
                                        foreach ($item['pembayaran'] as $pembayaran) {
                                            echo htmlspecialchars($pembayaran['tgl_bayar']) . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($item['pembayaran'] as $pembayaran) {
                                            echo htmlspecialchars($pembayaran['nominal']) . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        foreach ($item['pembayaran'] as $pembayaran) {
                                            echo htmlspecialchars($pembayaran['foto']) . '<br>';
                                        }
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($item['jatuh_tempo']); ?></td>
                                    <td><?php echo htmlspecialchars($tgl_lunas); ?></td>
                                    <td><?php echo htmlspecialchars($sisa); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-add" data-toggle="modal" data-target="#terima"
                                            data-id="<?php echo htmlspecialchars($id_pembelian); ?>"
                                            data-nama_obat="<?php echo htmlspecialchars($item['nama_obat']); ?>"
                                            data-tgl="<?php echo htmlspecialchars($item['tgl']); ?>"
                                            data-foto="<?php echo htmlspecialchars($pembayaran['foto']); ?>">
                                            Bayar
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
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
                        <form action="" method="post" id="terima_form" enctype="multipart/form-data">
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
                                <!-- <div>
                                    <label for="jumlah" class="form-label">Jumlah Order</label>
                                    <input type="text" class="form-control" name="jumlah" id="jumlah" readonly>
                                </div>
                                <div>
                                    <label for="" class="form-label">Total Tagihan</label>
                                    <input type="text" class="form-control" name="total" id="total" readonly>
                                </div> -->
                                <div>
                                    <label for="tgl_bayar" class="form-label">Tanggal Bayar</label>
                                    <input type="date" class="form-control" name="tgl_bayar" id="tgl_bayar" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div>
                                    <label for="sisa" class="form-label">Yang harus dibayar</label>
                                    <input type="text" class="form-control" name="sisa" id="sisa" value="<?php echo htmlspecialchars($sisa); ?>" readonly>
                                </div>
                                <div>
                                    <label for="nominal" class="form-label">Nominal</label>
                                    <input type="text" class="form-control" name="nominal" id="nominal" required>
                                </div>
                                <div class="">
                                    <label for="margin" class="form-label">Foto Bukti</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" name="foto" id="foto">
                                        <input type="hidden" name="existing_foto" value="<?php echo $foto; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                        // $('#jumlah').val($(this).data('jumlah'));
                        $('#foto').val($(this).data('foto'));
                        $('#tgl_bayar').val('<?php echo date('Y-m-d'); ?>');
                    });
                });
            </script>
        </div>
    </div>
</div>