<?php
/*
Plugin Name: CRUD Kelompok Ku
Description: Ini adalah plugin CRUD
Author: Gefila
Version: 1.0.0
Author URI: https://github.com/gefila
Plugin URI: https://google.com
*/

// Fungsi yang menampilkan isi halaman menu
?>
<?php
function modulku()
{
    $plugin_url = plugin_dir_url(__FILE__);
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        #wpcontent {
            padding: 0 !important;
        }
    </style>


    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <img src="logo.png" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php?page=utama&panggil=alert.php">Alert</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php?page=utama&panggil=modal.php">Modal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Master</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="admin.php?page=utama&panggil=jenis.php">Manajemen Jenis Keluhan</a></li>
                            <li><a class="dropdown-item" href="admin.php?page=utama&panggil=bagian.php">Bagian</a></li>
                            <li><a class="dropdown-item" href="admin.php?page=utama&panggil=mahasiswa.php">Mahasiswa</a></li>
                            <li><a class="dropdown-item" href="#">A third link</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Dropdown</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Link</a></li>
                            <li><a class="dropdown-item" href="#">Another link</a></li>
                            <li><a class="dropdown-item" href="#">A third link</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid mt-3 px-5">
        <?php
        if (isset($_GET["panggil"])) {
            include($_GET["panggil"]);
        }
        ?>
    </div>
<?php
}
?>

<?php
function tambah_menu_pluginku()
{
    add_menu_page(
        'CRUD Kelompok Ku',        // Judul halaman
        'CRUD SI',        // Nama menu di sidebar
        'read',          // Hak akses
        'utama',         // Slug menu
        'modulku',                 // Fungsi yang dipanggil
        'dashicons-games',        // Icon (WordPress Dashicons)
        80                          // Posisi menu
    );
}


// Hook untuk menambahkan menu admin
add_action('admin_menu', 'tambah_menu_pluginku');
?>