<?php
// Handle hapus user
if (isset($_GET['hapus'])) {
    $idHapus = intval($_GET['hapus']);
    require_once(ABSPATH . 'wp-admin/includes/user.php'); // penting untuk delete_user
    if (wp_delete_user($idHapus)) {
        echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            User berhasil dihapus!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            Gagal menghapus user!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
    echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=mahasiswa.php">';
}

// Proses insert user
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $peran = "mhs"; // Default peran untuk mahasiswa
    $display_name = sanitize_text_field($_POST['display_name']);

    $user_id = wp_create_user($username, $password, $email);

    if (!is_wp_error($user_id)) {
        $user = new WP_User($user_id);
        $user->set_role('subscriber');
        wp_update_user([
            'ID' => $user_id,
            'display_name' => $display_name
        ]);
        global $wpdb;
        $wpdb->update(
            $wpdb->users,
            ['peran' => $peran],
            ['ID' => $user_id]
        );
        update_user_meta($user_id, 'peran', $peran);
        echo '<div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            Berhasil membuat user baru: ' . esc_html($display_name) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        echo '<meta http-equiv="refresh" content="1;url=admin.php?page=utama&panggil=mahasiswa.php">';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            Gagal membuat user: ' . $user_id->get_error_message() . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
    }
}

// Ambil user WordPress
$users = get_users([
    'orderby' => 'ID',
    'order'   => 'ASC'
]); ?>

<h2>Manajemen Mahasiswa</h2>
<form method="POST" class="mb-4">
    <div class="mb-3">
        <label class="form-label">Nama Lengkap (Display Name)</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user"></i></span>
            <input type="text" name="display_name" class="form-control" placeholder="Nama Lengkap" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Username</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-user-circle"></i></span>
            <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
            <input type="email" name="email" class="form-control" placeholder="Alamat Email" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan
        </button>
        <a href="admin.php?page=utama&panggil=mahasiswa.php" class="btn btn-secondary">
            <i class="fas fa-undo"></i> Batal
        </a>
    </div>
</form>

<hr>

<h3>Daftar Mahasiswa</h3>
<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Display Name</th>
            <th>Email</th>
            <th>Role WP</th>
            <th>Peran (Custom)</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $peran_mapping = [
            'mhs' => 'Mahasiswa',
            'dsn' => 'Dosen',
            'kry' => 'Karyawan'
        ];

        $no = 1;
        foreach ($users as $user):
            $peran_code = $user->peran;
            $peran_label = isset($peran_mapping[$peran_code]) ? $peran_mapping[$peran_code] : '-';
            echo "<tr>
        <td>{$no}</td>
        <td>{$user->user_login}</td>
        <td>{$user->display_name}</td>
        <td>{$user->user_email}</td>
        <td>" . implode(', ', $user->roles) . "</td>
        <td>" . esc_html($peran_label) . "</td>
        <td>
            <a href='admin.php?page=utama&panggil=mahasiswa.php&hapus={$user->ID}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin ingin menghapus user ini?')\">
                <i class='fas fa-trash'></i> Hapus
            </a>
        </td>
    </tr>";
            $no++;
        endforeach;

        ?>
    </tbody>
</table>