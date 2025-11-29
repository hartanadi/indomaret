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
        <h1 class="page-title">➕ Tambah Produk</h1>
        <a href="list.php" class="btn btn-primary">← Kembali</a>
    </div>

    <!-- Form Card -->
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="/indomaret/process/products_process.php" method="POST">
            <input type="hidden" name="action" value="add" />
            
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-weight: 600;">Nama Produk:</label>
                <input type="text" class="form-control" name="nama" required />
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-weight: 600;">Voucher:</label>
                <input type="text" class="form-control" list="voucher_list" name="id_voucher" placeholder="Pilih voucher (opsional)" />
                <datalist id="voucher_list">
                    <?php
                    $query = mysqli_query($conn, "SELECT * FROM voucher");
                    while ($voucher = mysqli_fetch_assoc($query)) {
                    ?>
                        <option value="<?= $voucher['id'] ?>">
                            <?= $voucher['nama'] ?> - <?= $voucher['diskon'] ?>%
                        </option>
                    <?php
                    }
                    ?>
                </datalist>
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-weight: 600;">Harga:</label>
                <input type="number" class="form-control" name="harga_satuan" min="0" required />
            </div>

            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-weight: 600;">Stok:</label>
                <input type="number" class="form-control" name="stok" min="0" required />
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan</button>
            </div>
        </form>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>
