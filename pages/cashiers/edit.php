<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$cashier = null;

if ($id > 0) {
    $result = mysqli_query($conn, "SELECT * FROM kasir WHERE id = $id");
    if ($result && mysqli_num_rows($result) > 0) {
        $cashier = mysqli_fetch_assoc($result);
    }
}

if (!$cashier) {
    echo "<p>Cashier not found.</p>";
    include ROOTPATH . "/includes/footer.php";
    exit;
}
?>

<!-- Link CSS untuk dark mode -->
<link rel="stylesheet" href="/indomaret/assets/css/dark-mode.css">

<div class="container fade-in">
    <!-- Header halaman -->
    <div class="page-header">
        <h1 class="page-title">✏️ Edit Kasir</h1>
        <a href="list.php" class="btn btn-primary">← Kembali</a>
    </div>

    <!-- Form Card -->
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <form action="/indomaret/process/cashiers_process.php" method="post">
            <input type="hidden" name="action" value="edit" />
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($cashier['id']); ?>" />
            
            <div class="form-group">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-primary); font-weight: 600;">Nama Kasir:</label>
                <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($cashier['nama']); ?>" required />
            </div>

            <div style="margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="width: 100%;">Update</button>
            </div>
        </form>
    </div>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>