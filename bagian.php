<?php
// Handle hapus
if (isset($_GET['hapus'])) {
    $idHapus = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM tbl_bagian WHERE kdbag = '$idHapus'");
    echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        Data berhasil dihapus!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=bagian.php">';
}

// Default mode
$MODE = 'tambah';
$editData = [];

// Handle edit: ambil data jika ada parameter edit
if (isset($_GET['edit'])) {
    $idEdit = $_GET['edit'];
    $resultEdit = mysqli_query($conn, "SELECT * FROM tbl_bagian WHERE kdbag = '$idEdit'");
    if ($resultEdit && mysqli_num_rows($resultEdit) > 0) {
        $editData = mysqli_fetch_assoc($resultEdit);
        $MODE = 'edit';
    }
}

// Proses insert/update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kdbag = $_POST['kdbag'];
    $nmbag = $_POST['nmbag'];

    // Cek mode berdasarkan parameter edit
    if (isset($_GET['edit'])) {
        $query = "UPDATE tbl_bagian SET nmbag = '$nmbag' WHERE kdbag = '$kdbag'";
    } else {
        $lastKodeQuery = mysqli_query($conn, "SELECT MAX(kdbag) as maxKode FROM tbl_bagian");
        $nextKode = "01"; // default jika tidak ada data
        if ($lastKodeQuery && $row = mysqli_fetch_assoc($lastKodeQuery)) {
            $maxKode = (int)$row['maxKode'];
            $nextKode = str_pad($maxKode + 1, 2, '0', STR_PAD_LEFT);
        }
        $query = "INSERT INTO tbl_bagian (kdbag, nmbag) VALUES ('$nextKode', '$nmbag')";
    }

    if (mysqli_query($conn, $query)) {
        echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        Data berhasil disimpan!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
        echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=bagian.php">';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        Gagal menyimpan data!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
        echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=bagian.php">';
    }
}

// Ambil data dari database untuk ditampilkan di tabel
$result = mysqli_query($conn, "SELECT * FROM tbl_bagian");
?>

<h2>Manajemen Bagian</h2>
<form method="POST">
    <div class="mb-3 mt-3">
        <label for="kdbag" class="form-label">Kode Bagian</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
            <input type="text" class="form-control" id="kdbag" name="kdbag"
                value="<?= isset($editData['kdbag']) ? htmlspecialchars($editData['kdbag']) : '' ?>"
                readonly>
        </div>
    </div>
    <div class="mb-3">
        <label for="nmbag" class="form-label">bagian Bagian</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-comment-dots"></i></span>
            <input type="text" class="form-control" id="nmbag" name="nmbag"
                placeholder="Masukkan bagian Bagian" required
                value="<?= isset($editData['nmbag']) ? htmlspecialchars($editData['nmbag']) : '' ?>">
        </div>
    </div>
    <div class="text-center">
        <button type="submit" class="btn <?= isset($editData['kdbag']) ? 'btn-warning' : 'btn-primary' ?>">
            <i class="fas <?= isset($editData['kdbag']) ? 'fa-edit' : 'fa-save' ?>"></i>
            <?= isset($editData['kdbag']) ? 'Ubah' : 'Simpan' ?>
        </button>
        <a href="admin.php?page=utama&panggil=bagian.php" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Batal
        </a>
    </div>
</form>

<hr>

<h3>Daftar Bagian</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>ID</th>
            <th>Bagian</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $id = htmlspecialchars($row['kdbag']);
            echo "<tr>
                        <td>{$no}</td>
                        <td>{$id}</td>
                        <td>" . htmlspecialchars($row['nmbag']) . "</td>
                        <td>
                            <a href='admin.php?page=utama&panggil=bagian.php&edit={$id}' class='btn btn-warning btn-sm'>
                                <i class='fas fa-edit'></i> Ubah
                            </a>
                            <a href='admin.php?page=utama&panggil=bagian.php&hapus={$id}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">
                                <i class='fas fa-trash'></i> Hapus
                            </a>
                        </td>
                    </tr>";
            $no++;
        }
        ?>
    </tbody>
</table>