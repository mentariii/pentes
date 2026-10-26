<?php
/**
 * login.php - Portal Masuk Staf, Dosen, dan Pengelola Laboratorium
 * Tampilan web normal dengan backend yang sengaja rentan SQL Injection.
 */
require_once __DIR__ . '/header.php';

$error = '';
$sql_error = '';

// Jika sudah login, redirect langsung ke dashboard
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$db_connected) {
        $error = "Gagal terhubung ke database server. Silakan hubungi tim IT atau periksa konfigurasi database.";
    } else {
        // =====================================================================
        // [VULNERABILITY] SQL INJECTION (Authentication Bypass)
        // Celah tetap aktif: konkatenasi string langsung tanpa prepared statement
        // =====================================================================
        $executed_query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

        $result = @mysqli_query($conn, $executed_query);

        if ($result === false) {
            $sql_error = mysqli_error($conn);
            $error = "Terjadi kesalahan pada sistem pemrosesan autentikasi.";
        } else {
            if (mysqli_num_rows($result) > 0) {
                // Berhasil login
                $user = mysqli_fetch_assoc($result);
                $_SESSION['user'] = $user;
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Kombinasi ID Pengguna / NIP dan Kata Sandi tidak sesuai.";
            }
        }
    }
}
?>

<div class="container" style="max-width: 520px;">

    <div class="card" style="box-shadow: var(--shadow-lg); border-top: 4px solid var(--primary); padding: 32px 28px;">
        
        <div style="text-align: center; margin-bottom: 24px;">
            <div class="logo-icon" style="margin: 0 auto 12px auto; width: 48px; height: 48px; font-size: 1.4rem;">TI</div>
            <h2 style="font-size: 1.35rem; font-weight: 700; color: #1e293b;">Portal Masuk Civitas</h2>
            <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 4px;">
                Masuk ke sistem informasi akademik & manajemen laboratorium
            </p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" style="font-size: 0.88rem;">
                <strong>Gagal:</strong> <?= htmlspecialchars($error); ?>
                <?php if (!empty($sql_error)): ?>
                    <div style="margin-top: 6px; font-family: var(--font-mono); font-size: 0.78rem;">
                        <strong>Debug Info:</strong> <?= htmlspecialchars($sql_error); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label class="form-label" for="username">ID Pengguna / Username / NIP:</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan ID pengguna Anda" required autofocus>
            </div>

            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label class="form-label" for="password" style="margin-bottom: 0;">Kata Sandi:</label>
                    <a href="view.php?page=panduan_layanan.txt" style="font-size: 0.78rem; color: var(--primary); text-decoration: none;">Bantuan Akun?</a>
                </div>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan kata sandi">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 6px;">
                Masuk ke Akun
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border); font-size: 0.82rem; color: var(--text-muted);">
            Lupa kata sandi atau kendala akses? Hubungi Unit Layanan Terpadu Fakultas atau baca panduan di <a href="view.php?page=panduan_layanan.txt" style="color: var(--primary);">Pusat Dokumen</a>.
        </div>

    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
