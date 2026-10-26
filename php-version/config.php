<?php
/**
 * ====================================================================
 * LABORATORIUM PENETRATION TESTING - TEKNIK INFORMATIKA
 * File: config.php - Konfigurasi Koneksi Database MySQL & Header Khusus
 * ====================================================================
 * Siap di-upload ke shared hosting gratis seperti InfinityFree (htdocs).
 */

// Mulai session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Custom HTTP Header untuk validasi layanan / banner grabbing (cURL / Nmap)
header("X-Lab-Version: VulnLab-PHP/1.2");
header("X-Target-Server: NPT-Lab-Teknik-Informatika");

// 2. Kredensial Database MySQL
// PANDUAN INFINITYFREE:
// - $db_host: sql309.infinityfree.com
// - $db_user: if0_43275167
// - $db_pass: dvF8Yzj0QkT5jp
// - $db_name: if0_43275167_vulnlab
$db_host = "sql309.infinityfree.com";        // Ubah sesuai host MySQL hosting Anda
$db_user = "if0_43275167";             // Ubah sesuai username MySQL hosting Anda
$db_pass = "dvF8Yzj0QkT5jp";                 // Ubah sesuai password MySQL hosting Anda
$db_name = "if0_43275167_vulnlab";       // Ubah sesuai nama database MySQL hosting Anda

// Flag koneksi untuk penanganan error anggun (graceful handling)
$conn = null;
$db_connected = false;
$db_error_message = "";

// Coba lakukan koneksi menggunakan mysqli
try {
    // Matikan warning standar PHP agar tidak merusak tampilan jika belum disetting
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    if ($conn) {
        $db_connected = true;
        // Set charset UTF-8
        mysqli_set_charset($conn, "utf8mb4");
    } else {
        $db_connected = false;
        $db_error_message = mysqli_connect_error();
    }
} catch (Exception $e) {
    $db_connected = false;
    $db_error_message = $e->getMessage();
}
?>
