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
$jenisList = mysqli_query($conn, "SELECT * FROM tbl_jenis");

?>

<h2>Isi Keluhan</h2>
<p>
    silahkan Isi keluhan Bapak/Ibu/Sdr/ <b> <?= $user_nama; ?></b> pada kolom isian yang telah disediakan.
</p> 
<form method="POST">
    <div class="mb-3 mt-3">
        <label for="idjenis" class="form-label">Jenis Keluhan</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
            <select name="idjenis" id="idjenis" class="form-select" required>
                <option value="">-- Pilih Jenis Keluhan --</option>
                <?php while ($jenis = mysqli_fetch_assoc($jenisList)) : ?>
                    <option value="<?= $jenis['idjenis'] ?>"
                        <?= (isset($editData['idjenis']) && $editData['idjenis'] == $jenis['idjenis']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($jenis['nmjenis']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>
    <div class="mb-3">
        <label for="nmbag" class="form-label">Isi Keluhan</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-comment-dots"></i></span>
            <textarea class="form-control" id="nmbag" name="nmbag" rows="4"
                placeholder="Masukkan isi keluhan minimal 20 karakter dan maksimal 500 karakter"
                required></textarea>
        </div>
        <div class="form-text d-flex justify-content-between">
            <span id="charCount">0 / 500</span>
            <span id="charWarning" class="text-danger" style="display: none;">Isi keluhan harus 20–500 karakter.</span>
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

<script>
    const textarea = document.getElementById("nmbag");
    const charCount = document.getElementById("charCount");
    const charWarning = document.getElementById("charWarning");
    const form = document.querySelector("form");

    textarea.addEventListener("input", () => {
        const length = textarea.value.length;
        charCount.textContent = `${length} / 500`;

        // Tampilkan warning jika tidak memenuhi syarat
        if (length < 20 || length > 500) {
            charWarning.style.display = "inline";
            textarea.classList.add("is-invalid");
        } else {
            charWarning.style.display = "none";
            textarea.classList.remove("is-invalid");
        }
    });

    form.addEventListener("submit", function(e) {
        const length = textarea.value.length;
        if (length < 20 || length > 500) {
            e.preventDefault(); // Batalkan submit
            charWarning.style.display = "inline";
            textarea.classList.add("is-invalid");
            textarea.focus();
        }
    });
</script>