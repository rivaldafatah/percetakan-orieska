<?php
session_start();
include '../includes/db.php';

$order_id = $_GET['order_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reason = $_POST['reason'];
    $proof_image = $_FILES['proof_image']['name'];

    // Unggah bukti gambar ke folder uploads/returns
    $target_dir = "../uploads/returns/";
    $target_file = $target_dir . basename($proof_image);
    move_uploaded_file($_FILES['proof_image']['tmp_name'], $target_file);

    // Buat return_code dengan format RET-00001
    $stmt = $conn->prepare("SELECT MAX(id) AS max_id FROM returns");
    if (!$stmt) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));  // Menampilkan error jika terjadi kesalahan
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $max_id = $row['max_id'];
    $new_id = $max_id + 1;
    $return_code = "RTR-" . str_pad($new_id, 5, "0", STR_PAD_LEFT);

    // Simpan data retur ke database termasuk return_code
    $stmt = $conn->prepare("INSERT INTO returns (order_id, user_id, reason, proof_image, status, retur_code) VALUES (?, ?, ?, ?, 'pending', ?)");
    if (!$stmt) {
        die("Error preparing insert statement: " . htmlspecialchars($conn->error));  // Menampilkan error jika terjadi kesalahan
    }
    $stmt->bind_param("iisss", $order_id, $_SESSION['user_id'], $reason, $proof_image, $return_code);
    $stmt->execute();

    // Update status pesanan menjadi 'return_pending'
    $stmt = $conn->prepare("UPDATE orders SET status = 'return_pending' WHERE id = ?");
    if (!$stmt) {
        die("Error preparing update statement: " . htmlspecialchars($conn->error));  // Menampilkan error jika terjadi kesalahan
    }
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    header('Location: order_history.php');
    exit();
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Retur - Percetakan Orieska</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Percetakan Orieska</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="../index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../layanan.php">Layanan Vendor</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="katalogDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Katalog
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="katalogDropdown">
                            <li><a class="dropdown-item" href="catalog.php">Semua Produk</a></li>
                            <li><a class="dropdown-item" href="banner.php">Banner</a></li>
                            <li><a class="dropdown-item" href="stiker.php">Stiker</a></li>
                            <li><a class="dropdown-item" href="dus_kemasan.php">Dus Kemasan</a></li>
                            <li><a class="dropdown-item" href="undangan.php">Undangan</a></li> 
                            <li><a class="dropdown-item" href="kartu_nama.php">Kartu Nama</a></li>
                            <li><a class="dropdown-item" href="buku.php">Buku</a></li>
                            <li><a class="dropdown-item" href="brosur.php">Brosur</a></li>
                            <li><a class="dropdown-item" href="map.php">Map</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../about.php">Tentang</a>
                    </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php"><i class="bi bi-cart"></i> Keranjang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="order_history.php"><i class="material-icons"></i> Riwayat</a>
                    </li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">Permintaan Retur</h2>
        <form method="post" action="return_request.php?order_id=<?= $order_id ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="reason" class="form-label">Alasan Pengembalian:</label>
                <textarea id="reason" name="reason" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="proof_image" class="form-label">Unggah Bukti Gambar:</label>
                <input type="file" id="proof_image" name="proof_image" class="form-control" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajukan Retur</button>
            <a href="order_history.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
