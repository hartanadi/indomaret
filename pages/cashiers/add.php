<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";
?>

<!-- Link CSS untuk dark mode -->
<link rel="stylesheet" href="/indomaret/assets/css/dark-mode.css">

<div class="container fade-in">
    <!-- Header halaman -->
    <div class="page-header">
        <h1 class="page-title">➕ Tambah Kasir</h1>
        <a href="list.php" class="btn btn-primary">← Kembali</a>
    </div>

    <!-- Form Card -->
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="/indomaret/process/cashiers_process.php" method="post">
            <input type="hidden" name="action" value="add" />
            
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-weight: 600;">ID:</label>
                <input type="number" class="form-control" name="id" required />
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-weight: 600;">Nama Kasir:</label>
                <input type="text" class="form-control" name="name" required />
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-weight: 600;">Status:</label>
                <select name="status" class="form-control" required>
                    <option value="">Pilih Status</option>
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?php include ROOTPATH . "/includes/footer.php"; ?>