<?php
/**
 * logout.php - Menghapus sesi login dan kembali ke login.php
 */
require_once __DIR__ . '/config.php';

// Hapus semua data sesi
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

header("Location: login.php");
exit;
?>
