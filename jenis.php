<?php
// Handle hapus
if (isset($_GET['hapus'])) {
    $idHapus = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM tbl_jenis WHERE idjenis = '$idHapus'");
    echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        Data berhasil dihapus!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=jenis.php">';
}

// Handle edit
$editData = null;
if (isset($_GET['edit'])) {
    $idEdit = $_GET['edit'];
    $editResult = mysqli_query($conn, "SELECT * FROM tbl_jenis WHERE idjenis = '$idEdit'");
    $editData = mysqli_fetch_assoc($editResult);
}

// Proses insert/update data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idJenis = $_POST['idJenis'];
    $nmJenis = $_POST['nmJenis'];

    if (empty($idJenis)) {
        // Insert data baru
        $query = "INSERT INTO tbl_jenis (nmjenis) VALUES ('$nmJenis')";
    } else {
        // Update data yang sudah ada
        $query = "UPDATE tbl_jenis SET nmjenis = '$nmJenis' WHERE idjenis = '$idJenis'";
    }

    if (mysqli_query($conn, $query)) {
        echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        Data berhasil disimpan!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
        echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=jenis.php">';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        Gagal menyimpan data!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
        echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=jenis.php">';
    }
}


// Ambil data dari database untuk ditampilkan di tabel
$result = mysqli_query($conn, "SELECT * FROM tbl_jenis");

?>

<h2>Manajemen Jenis Keluhan</h2>
<form method="POST">
    <div class="mb-3 mt-3">
        <label for="idJenis" class="form-label">ID Jenis Keluhan</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
            <input type="text" class="form-control" id="idJenis" name="idJenis" readonly
                value="<?= isset($editData['idjenis']) ? $editData['idjenis'] : '' ?>">
        </div>
    </div>
    <div class="mb-3">
        <label for="nmJenis" class="form-label">Jenis Keluhan</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-comment-dots"></i></span>
            <input type="text" class="form-control" id="nmJenis" name="nmJenis"
                placeholder="Masukkan jenis keluhan" required
                value="<?= isset($editData['nmjenis']) ? $editData['nmjenis'] : '' ?>">
        </div>
    </div>
    <div class="text-center">
        <button type="submit" class="btn <?= isset($editData) ? 'btn-warning' : 'btn-primary' ?>">
            <i class="fas <?= isset($editData) ? 'fa-edit' : 'fa-save' ?>"></i>
            <?= isset($editData) ? 'Ubah' : 'Simpan' ?>
        </button>
        <a href="admin.php?page=utama&panggil=jenis.php" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Batal
        </a>
    </div>
</form>

<hr>

<h3>Daftar Jenis Keluhan</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>ID</th>
            <th>Jenis Keluhan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $id = htmlspecialchars($row['idjenis']);
            echo "<tr>
                        <td>{$no}</td>
                        <td>{$id}</td>
                        <td>" . htmlspecialchars($row['nmjenis']) . "</td>
                        <td>
                            <a href='admin.php?page=utama&panggil=jenis.php&edit={$id}' class='btn btn-warning btn-sm'>
                                <i class='fas fa-edit'></i> Ubah
                            </a>
                            <a href='admin.php?page=utama&panggil=jenis.php&hapus={$id}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">
                                <i class='fas fa-trash'></i> Hapus
                            </a>
                        </td>
                    </tr>";
            $no++;
        }
        ?>
    </tbody>
</table>