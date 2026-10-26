<?php
/**
 * view.php - Pusat Dokumen & Standar Operasional Akademik
 * Tampilan web normal dengan celah Local File Inclusion (LFI).
 */
require_once __DIR__ . '/header.php';

// Parameter nama dokumen yang ingin dibuka
$page = $_GET['page'] ?? 'panduan_layanan.txt';

$file_content = '';
$lfi_error = '';

// =====================================================================
// [VULNERABILITY] LOCAL FILE INCLUSION (LFI)
// Celah tetap aktif: memuat berkas langsung tanpa sanitasi path / whitelist
// =====================================================================
if (!empty($page)) {
    ob_start();
    $included = @include($page);
    $raw_output = ob_get_clean();

    if ($included === false && empty($raw_output)) {
        $lfi_error = "Dokumen '" . htmlspecialchars($page) . "' tidak dapat ditemukan atau belum diarsipkan pada repositori server.";
    } else {
        $file_content = $raw_output;
    }
}
?>

<div class="container">

    <div class="card">
        <div class="card-header">
            <div>
                <h2 class="card-title" style="font-size: 1.3rem;">
                    Pusat Dokumen & Panduan Layanan Terpadu
                </h2>
                <p style="color: var(--text-muted); font-size: 0.85rem;">
                    Repositori berkas digital, panduan operasional, dan peraturan akademik.
                </p>
            </div>
            <span class="badge badge-slate">ARSIP RESMI</span>
        </div>

        <!-- Menu Pilihan Dokumen Normal -->
        <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; align-items: center;">
            <span style="font-size: 0.88rem; font-weight: 600; color: #475569;">Pilihan Dokumen:</span>
            <a href="view.php?page=panduan_layanan.txt" class="btn btn-outline" style="font-size: 0.82rem; padding: 6px 12px;">
                📄 Panduan Layanan
            </a>
            <a href="view.php?page=tentang_kami.txt" class="btn btn-outline" style="font-size: 0.82rem; padding: 6px 12px;">
                🏛️ Profil Jurusan
            </a>
            <a href="view.php?page=standar_laboratorium.txt" class="btn btn-outline" style="font-size: 0.82rem; padding: 6px 12px;">
                🔬 Tata Tertib Lab
            </a>
        </div>

        <!-- Form Pemanggilan Dokumen Manual -->
        <form action="view.php" method="GET" style="display: flex; gap: 10px; margin-bottom: 24px; flex-wrap: wrap;">
            <input type="text" name="page" class="form-control" style="flex: 1; min-width: 250px;" 
                   placeholder="Ketik nama berkas panduan..." 
                   value="<?php echo htmlspecialchars($page, ENT_QUOTES); ?>">
            <button type="submit" class="btn btn-primary">
                Buka Berkas
            </button>
        </form>

        <?php if (!empty($lfi_error)): ?>
            <div class="alert alert-danger">
                <?= $lfi_error; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($file_content)): ?>
            <div style="border: 1px solid var(--border); border-radius: 8px; overflow: hidden;">
                <div style="background: #f8fafc; padding: 12px 18px; border-bottom: 1px solid var(--border); font-size: 0.85rem; font-weight: 600; color: #475569;">
                    Menampilkan Berkas: <code><?= htmlspecialchars($page); ?></code>
                </div>
                <div style="padding: 24px; background: #ffffff; color: #1e293b; white-space: pre-wrap; font-family: var(--font-sans); line-height: 1.7; font-size: 0.95rem;">
<?= htmlspecialchars($file_content); ?>
                </div>
            </div>
        <?php endif; ?>

    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
