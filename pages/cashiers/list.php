<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$result = mysqli_query($conn, "SELECT * FROM kasir ORDER BY id DESC");
?>

<!-- Link CSS untuk dark mode -->
<link rel="stylesheet" href="/indomaret/assets/css/dark-mode.css">

<div class="container fade-in">
    <!-- Header halaman -->
    <div class="page-header">
        <h1 class="page-title">👥 Daftar Kasir</h1>
        <a href="add.php" class="btn btn-primary">
            ➕ Tambah Kasir
        </a>
    </div>

    <!-- Tabel utama untuk menampilkan daftar kasir -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kasir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if(mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)){ 
                        $id_kasir = $row['id'];
                        // cek apakah kasir dipakai di tabel transaksi
                        $cek = mysqli_query($conn, "SELECT id_kasir FROM transaksi WHERE id_kasir = '$id_kasir'");
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= htmlspecialchars($row['nama']) ?></strong></td>
                        <td>
                            <div class="action-buttons">
                                <a href="edit.php?id=<?= $row['id'] ?>" class="btn-action btn-view">Edit</a>
                                <?php if (mysqli_num_rows($cek) > 0) { ?>
                                    <button type="button" class="btn-action btn-delete" disabled title="Kasir ini tidak bisa dihapus karena sudah dipakai di transaksi">Hapus</button>
                                <?php } else { ?>
                                    <form action="/indomaret/process/cashiers_process.php" method="post"
                                        onsubmit="return confirm('Yakin ingin menghapus kasir ini?')" style="display: inline;">
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
                        <td colspan="3" class="empty-state">
                            <p>📭 Belum ada data kasir</p>
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
