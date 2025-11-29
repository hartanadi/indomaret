<?php
include '../config/config.php';
include '../includes/header.php';

session_start();

// Query untuk statistik
$total_transaksi = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi"))['total'];
$total_pendapatan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL(SUM(total), 0) as total FROM transaksi WHERE status = 'Sudah Bayar' OR status = 'lunas' OR status = 'Lunas'"))['total'];
$total_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM produk"))['total'];
$total_kasir = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM kasir"))['total'];
$transaksi_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE DATE(tanggal) = CURDATE()"))['total'];
$pendapatan_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT IFNULL(SUM(total), 0) as total FROM transaksi WHERE DATE(tanggal) = CURDATE() AND (status = 'Sudah Bayar' OR status = 'lunas' OR status = 'Lunas')"))['total'];

?>

<!-- Link CSS untuk dark mode -->
<link rel="stylesheet" href="/indomaret/assets/css/dark-mode.css">

<div class="container fade-in">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <h1 class="welcome-title">🎯 Dashboard Indomaret</h1>
        <p class="welcome-subtitle">Selamat datang, Admin! 👋</p>
    </div>

    <!-- Statistik Cards -->
    <div class="dashboard-grid">
        <!-- Total Transaksi -->
        <div class="stat-card">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value"><?= number_format($total_transaksi, 0, ',', '.') ?></div>
            <div class="stat-icon">📊</div>
        </div>

        <!-- Total Pendapatan -->
        <div class="stat-card">
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></div>
            <div class="stat-icon">💰</div>
        </div>

        <!-- Transaksi Hari Ini -->
        <div class="stat-card">
            <div class="stat-label">Transaksi Hari Ini</div>
            <div class="stat-value"><?= number_format($transaksi_hari_ini, 0, ',', '.') ?></div>
            <div class="stat-icon">📅</div>
        </div>

        <!-- Pendapatan Hari Ini -->
        <div class="stat-card">
            <div class="stat-label">Pendapatan Hari Ini</div>
            <div class="stat-value">Rp <?= number_format($pendapatan_hari_ini, 0, ',', '.') ?></div>
            <div class="stat-icon">💵</div>
        </div>

        <!-- Total Produk -->
        <div class="stat-card">
            <div class="stat-label">Total Produk</div>
            <div class="stat-value"><?= number_format($total_produk, 0, ',', '.') ?></div>
            <div class="stat-icon">🛍️</div>
        </div>

        <!-- Total Kasir -->
        <div class="stat-card">
            <div class="stat-label">Total Kasir</div>
            <div class="stat-value"><?= number_format($total_kasir, 0, ',', '.') ?></div>
            <div class="stat-icon">👥</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
        <h2 style="margin-bottom: 1rem; color: var(--text-primary);">⚡ Quick Actions</h2>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="transactions/add.php" class="btn btn-primary">➕ Tambah Transaksi</a>
            <a href="transactions/list.php" class="btn btn-success">📋 Lihat Transaksi</a>
            <a href="products/list.php" class="btn btn-primary">🛍️ Kelola Produk</a>
            <a href="cashiers/list.php" class="btn btn-primary">👥 Kelola Kasir</a>
        </div>
    </div>
</div>