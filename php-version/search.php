<?php
/**
 * search.php - Pencarian Berita & Pengumuman Akademik
 * Tampilan web normal dengan celah Reflected XSS & Union-based SQLi.
 */
require_once __DIR__ . '/header.php';

$query_string = $_GET['q'] ?? '';
$results = [];
$sql_error = '';

if ($query_string !== '') {
    if (!$db_connected) {
        $sql_error = "Database belum terhubung. Konfigurasi file config.php terlebih dahulu.";
    } else {
        // =====================================================================
        // [VULNERABILITY 1] UNION-BASED SQL INJECTION
        // Query menggabungkan input langsung tanpa prepared statement
        // Mengembalikan 5 kolom: (id, title, category, content, author)
        // =====================================================================
        $executed_query = "SELECT id, title, category, content, author FROM posts WHERE title LIKE '%$query_string%' OR content LIKE '%$query_string%'";

        $res = @mysqli_query($conn, $executed_query);

        if ($res === false) {
            $sql_error = mysqli_error($conn);
        } else {
            while ($row = mysqli_fetch_assoc($res)) {
                $results[] = $row;
            }
        }
    }
} else {
    // Tampilkan semua pengumuman jika belum mencari
    if ($db_connected) {
        $res = @mysqli_query($conn, "SELECT id, title, category, content, author FROM posts ORDER BY id DESC");
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $results[] = $row;
            }
        }
    }
}
?>

<div class="container">

    <div class="card">
        <div class="card-header">
            <div>
                <h2 class="card-title" style="font-size: 1.3rem;">
                    Pencarian Berita & Pengumuman Akademik
                </h2>
                <p style="color: var(--text-muted); font-size: 0.85rem;">
                    Cari arsip berita, surat edaran, kegiatan kemahasiswaan, dan agenda akademik.
                </p>
            </div>
        </div>

        <!-- Form Pencarian Normal -->
        <form action="search.php" method="GET" style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px;">
            <input type="text" name="q" class="form-control" style="flex: 1; min-width: 250px;" 
                   placeholder="Ketik kata kunci (contoh: Sidang, Workshop, Beasiswa)..." 
                   value="<?php echo htmlspecialchars($query_string, ENT_QUOTES); ?>">
            <button type="submit" class="btn btn-primary">
                Cari Berita
            </button>
            <?php if ($query_string !== ''): ?>
                <a href="search.php" class="btn btn-outline">Reset</a>
            <?php endif; ?>
        </form>

        <?php if ($query_string !== ''): ?>
            <div style="margin-bottom: 20px; padding: 12px 16px; background: #f8fafc; border-radius: 6px; border: 1px solid var(--border);">
                <!-- ===================================================================== -->
                <!-- [VULNERABILITY 2] REFLECTED CROSS-SITE SCRIPTING (XSS)                -->
                <!-- $query_string dicetak langsung tanpa sanitasi htmlspecialchars()      -->
                <!-- ===================================================================== -->
                <p style="font-size: 0.95rem; color: #334155;">
                    Menampilkan hasil pencarian untuk kata kunci: <strong><?php echo $query_string; ?></strong>
                </p>
            </div>
        <?php endif; ?>

        <!-- Notifikasi Error SQL (Untuk Membantu Eksploitasi SQLi) -->
        <?php if (!empty($sql_error)): ?>
            <div class="alert alert-danger">
                <strong>Terjadi Kesalahan Kueri Database:</strong>
                <div style="font-family: var(--font-mono); font-size: 0.82rem; margin-top: 6px;">
                    <?= htmlspecialchars($sql_error); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Daftar Berita / Hasil Kueri -->
        <?php if (!empty($results)): ?>
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <?php foreach ($results as $item): ?>
                    <article style="border: 1px solid var(--border); border-radius: 8px; padding: 18px; background: #ffffff;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px;">
                            <span class="badge badge-blue"><?= htmlspecialchars($item['category'] ?? 'Umum'); ?></span>
                            <span style="font-size: 0.78rem; color: #94a3b8;">Penulis: <strong><?= htmlspecialchars($item['author'] ?? 'Admin'); ?></strong></span>
                        </div>
                        <h3 style="font-size: 1.15rem; font-weight: 700; color: #1e293b; margin-bottom: 8px;">
                            <?= htmlspecialchars($item['title'] ?? '-'); ?>
                        </h3>
                        <p style="font-size: 0.9rem; color: #475569; line-height: 1.6;">
                            <?= nl2br(htmlspecialchars($item['content'] ?? '-')); ?>
                        </p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php elseif (empty($sql_error)): ?>
            <div class="alert alert-info">
                Tidak ada informasi yang sesuai dengan kata kunci pencarian Anda. Silakan coba kata kunci lain.
            </div>
        <?php endif; ?>

    </div>

</div>

<?php require_once __DIR__ . '/footer.php'; ?>
