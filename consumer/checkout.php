<?php
session_start();
include '../includes/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'];
    $shipping_method = $_POST['shipping_method'];
    $payment_method = $_POST['payment_method'];
    $payment_proof = '';

    // Menangani upload gambar bukti pembayaran
    if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/payment_proofs/';
        $payment_proof = basename($_FILES['payment_proof']['name']);
        $target_file = $upload_dir . $payment_proof;

        // Memastikan direktori upload ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Memindahkan file yang diupload ke direktori target
        if (!move_uploaded_file($_FILES['payment_proof']['tmp_name'], $target_file)) {
            die("Error uploading payment proof.");
        }
    } else {
        die("Error: No payment proof uploaded.");
    }

    // Menyimpan data pesanan ke database
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $stmt = $conn->prepare("INSERT INTO orders (user_id, address, shipping_method, payment_method, total, status, payment_proof) VALUES (?, ?, ?, ?, ?, 'pending', ?)");
    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("isssds", $user_id, $address, $shipping_method, $payment_method, $total, $payment_proof);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Menyimpan detail pesanan ke database
    foreach ($cart as $item) {
        $design_file = $item['design_file']; // Assuming `design_file` is part of the cart item
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, design_file) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Error preparing statement for order items: " . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("iiids", $order_id, $item['id'], $item['quantity'], $item['price'], $design_file);
        $stmt->execute();
    }

    // Mengosongkan keranjang belanja
    unset($_SESSION['cart']);

    header('Location: order_success.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .bank-logo {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }
        .payment-option {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
    </style>
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
                    <li class="nav-item">
                        <a class="nav-link" href="catalog.php">Katalog</a>
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
        <h2 class="text-center">Checkout</h2>
        <div class="card">
            <div class="card-body">
                <form method="post" action="checkout.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat Pengiriman:</label>
                        <textarea id="address" name="address" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="shipping_method" class="form-label">Metode Pengiriman:</label>
                        <select id="shipping_method" name="shipping_method" class="form-select">
                            <option value="ambil">Ambil di Tempat</option>
                            <option value="kirim">Dikirim ke Alamat</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_method" class="form-label">Metode Pembayaran:</label>
                        <select id="payment_method" name="payment_method" class="form-select">
                            <option value="bri">Transfer Bank BRI (9083242834)</option>
                            <option value="bca">Transfer Bank BCA (98324893248324)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_proof" class="form-label">Unggah Bukti Pembayaran (jpg, jpeg, png):</label>
                        <input type="file" id="payment_proof" name="payment_proof" class="form-control" accept="image/jpeg,image/png" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Proses Checkout</button>
                </form>
                <a href="cart.php" class="btn btn-secondary mt-3">Kembali ke Keranjang</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
