<?php
// Mendefinisikan konstanta ROOTPATH yang menunjuk ke folder utama proyek
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

// Mengimpor file konfigurasi database (berisi koneksi ke MySQL)
include ROOTPATH . "/config/config.php";

// Mengimpor file header (biasanya berisi HTML awal atau menu navigasi)
include ROOTPATH . "/includes/header.php";
?>

<!-- Link CSS untuk dark mode -->
<link rel="stylesheet" href="/indomaret/assets/css/dark-mode.css">

<div class="container fade-in">
    <!-- Header halaman -->
    <div class="page-header">
        <h1 class="page-title">📋 Daftar Transaksi</h1>
        <a href="add.php" class="btn btn-primary">
            ➕ Tambah Transaksi
        </a>
    </div>

    <!-- Tabel utama untuk menampilkan daftar transaksi -->
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Kode Transaksi</th>
                    <th>Kasir</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                <?php
                // Inisialisasi nomor urut
                $no = 1;

                // Menjalankan query untuk mengambil semua transaksi beserta data kasir terkait
                $query = mysqli_query($conn, "SELECT *, transaksi.id AS id_transaksi FROM transaksi JOIN kasir ON transaksi.id_kasir = kasir.id ORDER BY transaksi.id DESC");

                // Melakukan perulangan untuk menampilkan setiap baris data transaksi
                if(mysqli_num_rows($query) > 0) {
                    while($transaksi = mysqli_fetch_assoc($query)){
                    ?>
                    <tr>
                        <!-- Menampilkan nomor urut -->
                        <td><?= $no++ ?></td>

                        <!-- Menampilkan tanggal transaksi -->
                        <td><?= date('d/m/Y H:i', strtotime($transaksi['tanggal'])) ?></td>

                        <!-- Menampilkan kode transaksi -->
                        <td><strong><?=$transaksi['kode']?></strong></td>

                        <!-- Menampilkan nama kasir -->
                        <td><?=$transaksi['nama']?></td>

                        <!-- Menampilkan total harga transaksi -->
                        <td><strong>Rp <?= number_format($transaksi['total'], 0, ',', '.') ?></strong></td>

                        <!-- Menampilkan status pembayaran -->
                        <td>
                            <?php
                            // Mengecek status transaksi
                            $status = $transaksi['status'] ?? '';
                            if($status == 'Sudah Bayar' || $status == 'lunas' || $status == 'Lunas'){
                                echo '<span class="status-badge status-paid">Sudah Bayar</span>';
                            } else {
                                echo '<span class="status-badge status-unpaid">Belum Bayar</span>';
                            }
                            ?>
                        </td>

                        <!-- Kolom Aksi -->
                        <td>
                            <div class="action-buttons">
                                <!-- Tombol untuk melihat detail transaksi -->
                                <a href="transaction_details.php?id=<?= $transaksi['id_transaksi'] ?>" class="btn-action btn-view">Lihat Detail</a>

                                <!-- Tombol untuk menghapus transaksi -->
                                <?php
                                // Mengecek apakah transaksi ini memiliki detail produk (relasi ke detail_transaksi)
                                $id_transaksi = $transaksi['id_transaksi'];
                                $cek = mysqli_query($conn, "SELECT id_transaksi FROM detail_transaksi WHERE id_transaksi = '$id_transaksi'");

                                // Jika transaksi sudah punya detail produk, maka tidak boleh dihapus
                                if(mysqli_num_rows($cek) > 0){
                                ?>
                                <!-- Tombol delete dinonaktifkan jika ada detail transaksi -->
                                <button type="button" class="btn-action btn-delete" disabled>Hapus</button>
                                <?php
                                }else{
                                ?>
                                <!-- Jika tidak ada detail transaksi, maka bisa dihapus -->
                                <form action="/indomaret/process/transactions_process.php" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')" style="display: inline;">
                                    <!-- Menentukan aksi delete untuk file process -->
                                    <input type="hidden" name="action" value="delete">
                                    <!-- Mengirim ID transaksi yang ingin dihapus -->
                                    <input type="hidden" name="id_transaksi" value="<?=$transaksi['id_transaksi']?>">
                                    <!-- Tombol submit untuk menghapus -->
                                    <button type="submit" class="btn-action btn-delete">Hapus</button>
                                </form>
                                <?php
                                }
                                ?>
                            </div>
                        </td>
                    </tr>
                    <?php
                    } // Akhir dari perulangan while
                } else {
                    ?>
                    <tr>
                        <td colspan="7" class="empty-state">
                            <p>📭 Belum ada transaksi</p>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Mengimpor file footer (biasanya berisi penutup HTML)
include ROOTPATH . "/includes/footer.php";
?>


<!-- 
        Bagian Kode                                                             Fungsi
define('ROOTPATH', ...)                                         Menentukan lokasi folder utama proyek untuk memudahkan include.
include config.php                                              Menghubungkan file ke database MySQL.
include header.php                                              Menampilkan tampilan awal HTML dan navigasi.
<a href="add.php">Add transaksi</a>                             Tombol untuk menambah transaksi baru.
mysqli_query($conn, "SELECT * FROM transaksi JOIN kasir...")    Mengambil semua data transaksi dan nama kasir.
while($transaksi = mysqli_fetch_assoc(...))                     Menampilkan setiap transaksi dalam tabel.
Lihat Detail                                                    Mengarah ke halaman detail transaksi berdasarkan id.
Logika if(mysqli_num_rows($cek) > 0)                            Mengecek apakah transaksi punya detail produk (agar tidak bisa dihapus).
Form process/transactions_process.php                            Mengirim permintaan hapus data ke file pemrosesan.
include footer.php                                              Menutup halaman dengan tampilan footer. 
-->
