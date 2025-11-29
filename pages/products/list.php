<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$result = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
?>

<!-- Link CSS untuk dark mode -->
<link rel="stylesheet" href="/indomaret/assets/css/dark-mode.css">

<div class="container fade-in">
    <!-- Header halaman -->
    <div class="page-header">
        <h1 class="page-title">🛍️ Daftar Produk</h1>
        <a href="add.php" class="btn btn-primary">
            ➕ Tambah Produk
        </a>
    </div>

    <!-- Tabel utama untuk menampilkan daftar produk -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Voucher ID</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if(mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)){ 
                        $id_produk = $row['id'];
                    ?>
                    <tr>
                        <td><?= $no++?></td>
                        <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                        <?php
                        // ambil diskon dari voucher
                        $voucher_id = $row['id_voucher'];
                        $diskon = mysqli_query($conn, "SELECT diskon, maks_diskon FROM voucher WHERE id = '$voucher_id'");
                        if(mysqli_num_rows($diskon) > 0){
                            $diskon = mysqli_fetch_assoc($diskon);
                            $harga_diskon = $row['harga_satuan'] - ($row['harga_satuan'] * $diskon['diskon'] / 100);
                            
                            // cek batas maksimal diskon
                            if($diskon['maks_diskon'] > 0 && ($row['harga_satuan'] * $diskon['diskon'] / 100) > $diskon['maks_diskon']){
                                $harga_diskon = $row['harga_satuan'] - $diskon['maks_diskon'];
                            }             
                        ?>
                        <td>
                            <del style="color: var(--danger); opacity: 0.7;">Rp <?= number_format($row['harga_satuan'], 0, ',', '.') ?></del><br>
                            <strong style="color: var(--success);">Rp <?= number_format($harga_diskon, 0, ',', '.') ?></strong>
                        </td>
                        <?php
                        }else{
                        ?>
                        <td><strong>Rp <?= number_format($row['harga_satuan'], 0, ',', '.') ?></strong></td>
                        <?php
                        }
                        ?>
                        <td><?= $row['id_voucher'] ? htmlspecialchars($row['id_voucher']) : '<span style="color: var(--text-muted);">-</span>' ?></td>
                        <td>
                            <?php
                            $stok = (int)$row['stok'];
                            if($stok > 10) {
                                echo '<span style="color: var(--success); font-weight: bold;">' . number_format($stok, 0, ',', '.') . '</span>';
                            } elseif($stok > 0) {
                                echo '<span style="color: var(--warning); font-weight: bold;">' . number_format($stok, 0, ',', '.') . '</span>';
                            } else {
                                echo '<span style="color: var(--danger); font-weight: bold;">Habis</span>';
                            }
                            ?>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn-action btn-view">Edit</a>
                                <?php
                                $cek = mysqli_query($conn, "SELECT id_produk FROM detail_transaksi WHERE id_produk = '$id_produk'");
                                if(mysqli_num_rows($cek) > 0){
                                ?>
                                    <button type="button" class="btn-action btn-delete" disabled title="Produk ini tidak bisa dihapus karena sudah dipakai di transaksi">Hapus</button>
                                <?php } else { ?>
                                    <form action="/indomaret/process/products_process.php" method="post"
                                        onsubmit="return confirm('Yakin ingin menghapus produk ini?')" style="display: inline;">
                                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <button type="submit" class="btn-action btn-delete">Hapus</button>
                                    </form>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                    <?php 
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="6" class="empty-state">
                            <p>📭 Belum ada data produk</p>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>
