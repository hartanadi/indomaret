<?php
session_start(); // Memulai sesi PHP agar dapat menyimpan data sementara (seperti id_transaksi)

// Mendefinisikan konstanta ROOTPATH yang menunjuk ke direktori utama project
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

// Mengimpor file konfigurasi database
include ROOTPATH . "/config/config.php";

// Mengimpor file header (biasanya berisi tampilan awal HTML)
include ROOTPATH . "/includes/header.php";
?>

<!-- Link CSS untuk dark mode -->
<link rel="stylesheet" href="/indomaret/assets/css/dark-mode.css">

<?php
// -----------------------------------------------------------
// QUERY 1: Mengambil data transaksi utama (header tabel)
// -----------------------------------------------------------
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = $_SESSION['id_transaksi'];
}

// Menjalankan query untuk mengambil data transaksi beserta nama kasir
$header_query = mysqli_query($conn, "
    SELECT transaksi.*, kasir.nama AS cashiername  
    FROM transaksi  
    JOIN kasir ON kasir.id = transaksi.id_kasir  
    WHERE transaksi.id = " . $id
);

// Mengambil satu baris hasil query dalam bentuk array asosiatif
$detail = mysqli_fetch_assoc($header_query);

// -----------------------------------------------------------
// QUERY 2: Mengambil data detail produk yang dibeli
// -----------------------------------------------------------
$query = mysqli_query($conn, "
    SELECT produk.harga_satuan AS harga_produk, detail_transaksi.*, 
           produk.nama AS productname, id_voucher  
    FROM detail_transaksi  
    JOIN produk ON produk.id = detail_transaksi.id_produk 
    JOIN transaksi ON transaksi.id = detail_transaksi.id_transaksi 
    LEFT JOIN voucher ON produk.id_voucher = voucher.id 
    WHERE detail_transaksi.id_transaksi = " . $id
);
?>

<div class="container fade-in">
    <!-- Header halaman -->
    <div class="page-header">
        <h1 class="page-title">📄 Detail Transaksi</h1>
        <a href="list.php" class="btn btn-primary">← Kembali</a>
    </div>

    <?php
    // Menampilkan notifikasi jika ada
    if (isset($_GET['error']) && $_GET['error'] == 'kurang') {
        $kurang = isset($_GET['kurang']) ? number_format((float)$_GET['kurang'], 0, ',', '.') : 0;
        echo '<div style="background-color: rgba(239, 68, 68, 0.2); color: var(--danger); padding: 15px; margin-bottom: 1.5rem; border: 1px solid var(--danger); border-radius: 8px;">';
        echo '<strong>⚠️ Uang Kurang!</strong><br>';
        echo 'Jumlah pembayaran kurang sebesar: <strong>Rp ' . $kurang . '</strong>';
        echo '</div>';
    }
    
    if (isset($_GET['success']) && $_GET['success'] == '1') {
        $kembalian = isset($_GET['kembalian']) ? (float)$_GET['kembalian'] : 0;
        echo '<div style="background-color: rgba(16, 185, 129, 0.2); color: var(--success); padding: 15px; margin-bottom: 1.5rem; border: 1px solid var(--success); border-radius: 8px;">';
        echo '<strong>✅ Pembayaran Berhasil!</strong><br>';
        if ($kembalian > 0) {
            echo 'Kembalian: <strong>Rp ' . number_format($kembalian, 0, ',', '.') . '</strong>';
        } else {
            echo 'Pembayaran pas, tidak ada kembalian.';
        }
        echo '</div>';
    }
    
    $status = mysqli_fetch_assoc(mysqli_query($conn, "SELECT status FROM transaksi WHERE id = " . $id))['status'];

    if (isset($_POST['bayar'])) {
    ?>
        <div class="card" style="max-width: 500px; margin: 0 auto 1.5rem;">
            <p style="font-size: 1.1rem; margin-bottom: 1rem;"><strong>Total yang harus dibayar: Rp <?= number_format($detail['total'], 0, ',', '.') ?></strong></p>
            <form action="/indomaret/process/transactions_process.php" method="POST">
                <input type="hidden" name="id_transaksi" value="<?= $id ?>" />
                <input type="hidden" name="action" value="bayar" />
                <div class="form-group">
                    <input type="number" class="form-control" placeholder="Jumlah bayar..." name="jumlah_bayar" min="0" step="100" required />
                </div>
                <input type="submit" value="Bayar" name="proses_bayar" class="btn btn-success" style="width: 100%;" />
            </form>
        </div>
    <?php
    } elseif ($status == 'lunas' || $status == 'Sudah Bayar' || $status == 'Lunas') {
    ?>
        <div class="card" style="max-width: 500px; margin: 0 auto 1.5rem; text-align: center;">
            <h3 style="color: var(--success);">✅ Transaksi sudah lunas.</h3>
        </div>
    <?php
    } else {
    ?>
        <div class="card" style="max-width: 600px; margin: 0 auto 1.5rem;">
            <h3 style="margin-bottom: 1rem; color: var(--text-primary);">➕ Tambah Produk</h3>
            <form action="/indomaret/process/transactions_process.php" method="post">
                <input type="hidden" name="id_transaksi" value="<?= $id ?>" />
                <input type="hidden" name="action" value="add" />

                <datalist id="produk_list">
                    <?php
                    $query_produk = mysqli_query($conn, "SELECT * FROM produk");
                    while ($produk = mysqli_fetch_assoc($query_produk)) {
                    ?>
                        <option value="<?= $produk['nama'] ?>"></option>
                    <?php
                    }
                    ?>
                </datalist>

                <div style="display: flex; gap: 0.5rem; margin-bottom: 1rem;">
                    <input type="text" class="form-control" name="produk" list="produk_list" placeholder="Cari produk..." style="flex: 2;" />
                    <input type="number" class="form-control" name="qty" placeholder="Qty" min="1" required style="flex: 1;" />
                </div>
                <input type="submit" name="submit" value="Tambah" class="btn btn-primary" style="width: 100%;" />
            </form>

            <br>
            <form action="" method="POST">
                <input type="submit" name="bayar" value="💳 Bayar" class="btn btn-success" style="width: 100%;" />
            </form>
        </div>
    <?php
    }
    ?>

    <!-- Tabel untuk menampilkan informasi transaksi -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th colspan="3" style="text-align: right;">Kode / Kasir / ID</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?= date('d/m/Y H:i', strtotime($detail['tanggal'])) ?>
                    </td>
                    <td colspan="3" style="text-align: right;">
                        <strong><?= $detail['kode'] ?></strong> / <?= $detail['cashiername'] ?> / <?= $detail['id_kasir'] ?>
                    </td>
                </tr>
                <tr>
                    <th>Produk</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Harga</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>

        <?php
        // Melakukan perulangan untuk setiap produk yang ada di transaksi
        while ($detail_produk = mysqli_fetch_assoc($query)) {
        ?>
            <tr>
                <!-- Nama produk -->
                <td><?= $detail_produk['productname'] ?></td>

                <!-- Jumlah produk yang dibeli -->
                <td align="center"><?= $detail_produk['kuantitas'] ?></td>

                <?php
                // Mengambil ID voucher dari produk (jika ada)
                $voucher_id = $detail_produk['id_voucher'];

                // Mengecek apakah produk ini memiliki voucher diskon
                $diskon = mysqli_query($conn, "SELECT diskon, maks_diskon FROM voucher WHERE id = '$voucher_id'");

                if (mysqli_num_rows($diskon) > 0) {
                    $diskon = mysqli_fetch_assoc($diskon);

                    // Menghitung harga setelah diskon berdasarkan persentase
                    $harga_diskon = $detail_produk['harga_satuan'] - ($detail_produk['harga_satuan'] * $diskon['diskon'] / 100);

                    // Mengecek apakah diskon melebihi batas maksimum (max_discount)
                    if ($diskon['maks_diskon'] > 0 && ($detail_produk['harga_satuan'] * $diskon['diskon'] / 100) > $diskon['maks_diskon']) {
                        $harga_diskon = $detail_produk['harga_satuan'] - $diskon['maks_diskon'];
                    }
                ?>
                    <!-- Menampilkan harga asli (dicoret merah) dan harga setelah diskon -->
                    <td style="text-align: right;">
                        <del style="color: var(--danger); opacity: 0.7;">Rp <?= number_format($detail_produk['harga_produk'], 0, ',', '.') ?></del><br>
                        <strong style="color: var(--success);">Rp <?= number_format($detail_produk['harga_satuan'], 0, ',', '.') ?></strong>
                    </td>
                <?php
                } else {
                ?>
                    <!-- Jika tidak ada voucher, tampilkan harga normal -->
                    <td style="text-align: right;"><strong>Rp <?= number_format($detail_produk['harga_satuan'], 0, ',', '.') ?></strong></td>
                <?php
                }
                ?>

                <!-- Menampilkan subtotal (harga x qty) -->
                <td style="text-align: right;"><strong>Rp <?= number_format($detail_produk['sub_total'], 0, ',', '.') ?></strong></td>
            </tr>
        <?php
        } // Akhir dari perulangan produk
        ?>

        <!-- Baris total akhir transaksi -->
        <tr style="background: var(--bg-tertiary);">
            <td colspan="3" style="text-align: right;"><strong style="font-size: 1.1rem;">Total</strong></td>
            <td style="text-align: right;">
                <strong style="font-size: 1.2rem; color: var(--accent-primary);">Rp <?= number_format($detail['total'], 0, ',', '.') ?></strong>
            </td>
        </tr>
            </tbody>
        </table>
    </div>
</div>

<?php
// Menyertakan file footer (biasanya berisi penutup HTML)
include ROOTPATH . "/includes/footer.php";
?>

<!-- 
Bagian Kode                                   Fungsi Utama
-----------------------------------------------------------
define('ROOTPATH', ...)                      Menentukan direktori utama proyek
include config.php                           Menghubungkan ke database
include header.php/footer.php                Menyusun tampilan halaman
Query transaksi                              Mengambil data utama transaksi
Query detail_transaksi                       Mengambil detail barang di transaksi
Perulangan while                             Menampilkan setiap produk dalam transaksi
Logika voucher                               Menghitung harga diskon (dengan batas maksimum)
number_format()                              Memformat angka agar mudah dibaca (misal: 10.000)
-->
