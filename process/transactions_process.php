<?php
// Menentukan lokasi folder utama proyek agar mudah memanggil file lain
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

// Menyertakan file konfigurasi database untuk koneksi ke MySQL
include ROOTPATH . "/config/config.php";

// Mengecek apakah data dikirim melalui metode POST (form submit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'];
    
    if ($action == 'delete') {
        $id_transaksi = $_POST['id_transaksi'];
        // Hapus data transaksi dari tabel
        $query = "DELETE FROM transaksi WHERE id = $id_transaksi";
        mysqli_query($conn, $query);
        // Redirect kembali ke halaman list transaksi
        header("Location: ../pages/transactions/list.php");
        exit;
    }
    
    $id_transaksi = $_POST['id_transaksi'];

    // Hitung total awal transaksi
    $total = mysqli_fetch_assoc(mysqli_query($conn, 
        "SELECT IFNULL(SUM(sub_total),0) AS total FROM detail_transaksi WHERE id_transaksi = $id_transaksi"
    ))['total'];

    if ($action == 'add') {
        $produk = $_POST['produk'];

        // Ambil data produk dari tabel
        $tabel_produk = mysqli_fetch_assoc(mysqli_query($conn, 
            "SELECT produk.id, harga_satuan, voucher.diskon 
             FROM produk 
             LEFT JOIN voucher ON produk.id_voucher = voucher.id 
             WHERE produk.nama = '$produk'"
        ));

        if (!$tabel_produk) {
            die("<script>alert('❌ Produk tidak ditemukan!'); history.back();</script>");
        }

        $id_produk = $tabel_produk['id'];
        $quantity = (int) $_POST['qty'];
        $harga_satuan = $tabel_produk['harga_satuan'];
        $discount = $tabel_produk['diskon'] ?? 0;

        // Hitung harga dan subtotal
        $harga_setelah_diskon = $harga_satuan - ($harga_satuan * $discount / 100);
        $subtotal = $harga_setelah_diskon * $quantity;

        // Cek apakah produk sudah ada dalam transaksi ini
        $cek = mysqli_query($conn, 
            "SELECT * FROM detail_transaksi WHERE id_transaksi = $id_transaksi AND id_produk = $id_produk"
        );

        if (mysqli_num_rows($cek) > 0) {
            // Jika sudah ada, update qty dan subtotal
            mysqli_query($conn, 
                "UPDATE detail_transaksi 
                 SET kuantitas = kuantitas + $quantity, 
                     sub_total = sub_total + $subtotal 
                 WHERE id_transaksi = $id_transaksi AND id_produk = $id_produk"
            );
        } else {
            // Jika belum ada, insert data baru
            mysqli_query($conn, 
                "INSERT INTO detail_transaksi (id_transaksi, id_produk, kuantitas, sub_total, harga_satuan, diskon)
                 VALUES ($id_transaksi, $id_produk, $quantity, $subtotal, $harga_satuan, $discount)"
            );
        }

        // Update total transaksi
        mysqli_query($conn, 
            "UPDATE transaksi 
             SET total = (SELECT SUM(sub_total) FROM detail_transaksi WHERE id_transaksi = $id_transaksi) 
             WHERE id = $id_transaksi"
        );

        // Kurangi stok produk
        mysqli_query($conn, 
            "UPDATE produk SET stok = stok - $quantity WHERE id = $id_produk"
        );

    } elseif ($action == 'bayar') {
        $jumlah_bayar = (float) $_POST['jumlah_bayar'];
        
        // Ambil total transaksi
        $data_transaksi = mysqli_fetch_assoc(mysqli_query($conn, 
            "SELECT total FROM transaksi WHERE id = $id_transaksi"
        ));
        $total_transaksi = (float) $data_transaksi['total'];
        
        // Validasi jumlah pembayaran
        if ($jumlah_bayar < $total_transaksi) {
            // Jika uang kurang, redirect dengan pesan error
            $kurang = $total_transaksi - $jumlah_bayar;
            header("Location: ../pages/transactions/transaction_details.php?id=" . $id_transaksi . "&error=kurang&kurang=" . $kurang);
            exit;
        }
        
        // Hitung kembalian
        $kembalian = $jumlah_bayar - $total_transaksi;
        
        // Update transaksi
        mysqli_query($conn, 
            "UPDATE transaksi 
             SET bayar = $jumlah_bayar, 
                 kembalian = $kembalian, 
                 status = 'Sudah Bayar' 
             WHERE id = $id_transaksi"
        );
        
        // Redirect dengan pesan sukses dan kembalian
        if ($kembalian > 0) {
            header("Location: ../pages/transactions/transaction_details.php?id=" . $id_transaksi . "&success=1&kembalian=" . $kembalian);
        } else {
            header("Location: ../pages/transactions/transaction_details.php?id=" . $id_transaksi . "&success=1&kembalian=0");
        }
        exit;
    }

    // Redirect kembali ke halaman detail transaksi (untuk action 'add')
    header("Location: ../pages/transactions/transaction_details.php?id=" . $id_transaksi);
    exit;
}
?>
