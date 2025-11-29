<?php
// Menentukan lokasi folder utama proyek di server
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

// Menyertakan file konfigurasi database
include ROOTPATH . "/config/config.php";

// Mengecek apakah form dikirim dengan metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Mengambil data dari form
    $action      = $_POST['action'];           // Jenis aksi (add, edit, delete)
    $name        = $_POST['nama'];             // Nama produk
    $unit_price  = $_POST['harga_satuan'];     // Harga satuan produk
    $stock       = $_POST['stok'];             // Jumlah stok produk

    // Mengecek apakah kolom id_voucher diisi atau tidak
    if (empty($_POST['id_voucher'])) {
        // Jika kosong, set nilai voucher menjadi NULL agar tidak error di SQL
        $id_voucher = "NULL";
    } else {
        // Jika diisi, tambahkan tanda kutip karena kolom id_voucher bertipe CHAR(6)
        $id_voucher = "'" . mysqli_real_escape_string($conn, $_POST['id_voucher']) . "'";
    }

    // Proses sesuai jenis aksi
    if ($action == 'add') {
        // Menyimpan data baru ke tabel produk
        $query = "
            INSERT INTO produk (id_voucher, nama, harga_satuan, stok)
            VALUES ($id_voucher, '$name', '$unit_price', '$stock')
        ";
        mysqli_query($conn, $query);

    } elseif ($action == 'edit') {
        // Mengambil ID produk yang akan diedit
        $id = $_POST['id'];
        // Update data produk sesuai ID
        $query = "
            UPDATE produk 
            SET nama='$name', 
                id_voucher=$id_voucher, 
                harga_satuan='$unit_price', 
                stok='$stock' 
            WHERE id=$id
        ";
        mysqli_query($conn, $query);

    } elseif ($action == 'delete') {
        // Mengambil ID produk yang akan dihapus
        $id = $_POST['id'];
        // Hapus data produk dari tabel
        $query = "DELETE FROM produk WHERE id=$id";
        mysqli_query($conn, $query);
    }

    // Setelah proses selesai, arahkan kembali ke halaman daftar produk
    header("Location: ../pages/products/list.php");
    exit;
}
?>

<!-- 
🧠 Penjelasan Singkat Fungsi File:

File ini berfungsi sebagai *file proses produk* untuk menangani:
    • Tambah produk baru (add)
    • Edit produk (edit)
    • Hapus produk (delete)

✅ Penyesuaian dengan database `indomaret_rpl4`:
    - `id_voucher` bertipe CHAR(6) dan boleh NULL → ditangani dengan tanda kutip.
    - Query INSERT ditulis eksplisit (tanpa NULL di kolom id) karena kolom `id` auto_increment.
    - Menggunakan mysqli_real_escape_string() agar aman dari input berbahaya.

Setelah aksi berhasil dijalankan, halaman akan diarahkan ke:
📍 ../pages/products/list.php agar perubahan langsung terlihat.
-->
