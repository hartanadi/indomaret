<?php
session_start();
// Memulai sesi PHP agar dapat menyimpan data sementara (seperti id_transaksi)

define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
// Mendefinisikan konstanta ROOTPATH sebagai path absolut ke folder proyek
// Contoh: /var/www/html/indomaret_RPL4

include ROOTPATH . "/config/config.php";
// Mengimpor file konfigurasi database (berisi koneksi $conn)

include ROOTPATH . "/includes/header.php";
// Menyertakan header HTML (biasanya berisi navbar atau CSS Bootstrap)


// Mengecek apakah tombol "selanjutnya" diklik (form dikirim)
if (@$_POST['selanjutnya']) {

    // Ambil data transaksi terakhir berdasarkan ID terbesar (transaksi terbaru)
    @$kode_terakhir = mysqli_fetch_assoc(mysqli_query($conn, "SELECT kode FROM transaksi ORDER BY id DESC LIMIT 1"));

    // Ambil ID dan kode transaksi terakhir
    $query = mysqli_query($conn, "SELECT id, kode FROM transaksi ORDER BY id DESC LIMIT 1");
    $data = mysqli_fetch_assoc($query);

    // Jika sudah ada transaksi sebelumnya
    if ($data) {
        // Ambil kode transaksi terakhir, contoh: TRX0005
        $kode_terakhir = $data['kode'];  

        // Ambil 4 digit terakhir dari kode (contoh: ambil 0005 → jadi 5)
        $urutan = (int) substr($kode_terakhir, 3, 4); 

        // Tambahkan 1 agar menjadi kode transaksi berikutnya
        $urutan++;

        // Bentuk kembali kode transaksi baru dengan awalan "TRX" dan 4 digit angka
        $kode_transaksi = "TRX" . str_pad($urutan, 4, "0", STR_PAD_LEFT); // hasil: TRX0006

        // Hitung ID baru (jika tidak auto-increment)
        $id_terakhir = $data['id'];
        $id = $id_terakhir + 1;

    } else {
        // Jika belum ada data transaksi sama sekali
        $kode_transaksi = "TRX0001";
        $id = 1;
    }


    // Ambil nama kasir yang dipilih dari form
    $nama_kasir = $_POST['nama_kasir'];

    // Cari ID kasir berdasarkan nama yang diinput
    $kasir = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM kasir WHERE nama='$nama_kasir'"));
    $id_kasir = $kasir['id'];

    // Atur zona waktu ke WITA (Makassar)
    date_default_timezone_set('Asia/Makassar');

    // Ambil waktu saat ini untuk disimpan sebagai tanggal transaksi
    $tanggal = date("Y-m-d H:i:s");

    // Set total transaksi awal menjadi 0 (karena detail belum ditambahkan)
    $total = 0;

    // Simpan transaksi baru ke tabel `transaksi`
    $query = mysqli_query($conn, "INSERT INTO transaksi (id, tanggal, kode, id_kasir, total) 
                                  VALUES ('$id', '$tanggal', '$kode_transaksi', '$id_kasir', '$total')");

    // Simpan id_transaksi ke session agar bisa digunakan di halaman detail
    $_SESSION['id_transaksi'] = $id;

    // Jika proses simpan gagal
    if (!$query) {
        echo "<p>Gagal menyimpan transaksi: " . mysqli_error($conn) . "</p>";
    } else {
        // Jika berhasil, pindah ke halaman detail_transaksi.php
        header('Location: transaction_details.php');
        exit;
    }
}
?>

<!-- Link CSS untuk dark mode -->
<link rel="stylesheet" href="/indomaret/assets/css/dark-mode.css">

<div class="container fade-in">
    <!-- Header halaman -->
    <div class="page-header">
        <h1 class="page-title">➕ Tambah Transaksi</h1>
        <a href="list.php" class="btn btn-primary">← Kembali</a>
    </div>

    <!-- Form Card -->
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="" method="POST">
            <div class="form-group">
                <label for="nama_kasir" style="display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-weight: 600;">Pilih Kasir:</label>
                <input type="text" class="form-control" name="nama_kasir" placeholder="Ketik nama kasir" list="kasirList" required>

                <!-- Datalist berisi daftar nama kasir dari database -->
                <datalist id="kasirList">
                    <?php
                    $qKasir = mysqli_query($conn, "SELECT nama FROM kasir");
                    while($k = mysqli_fetch_assoc($qKasir)) {
                        echo "<option value='{$k['nama']}'></option>";
                    }
                    ?>
                </datalist>
            </div>

            <!-- Tombol kirim -->
            <div style="margin-top: 1.5rem;">
                <input type="submit" name="selanjutnya" class="btn btn-primary" value="Selanjutnya →" style="width: 100%;">
            </div>
        </form>
    </div>
</div>


<?php include ROOTPATH . "/includes/footer.php"; ?>
