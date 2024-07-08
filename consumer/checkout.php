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

    // Menyimpan data pesanan ke database
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $stmt = $conn->prepare("INSERT INTO orders (user_id, address, shipping_method, payment_method, total, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("isssd", $user_id, $address, $shipping_method, $payment_method, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Menyimpan detail pesanan ke database
    foreach ($cart as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, design_file) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Error preparing statement for order items: " . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("iiids", $order_id, $item['id'], $item['quantity'], $item['price'], $item['design_file']);
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
  <!-- Bootstrap Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- AOS CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
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
                        <a class="nav-link" aria-current="page" href="#">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Layanan Vendor</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="katalogDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Katalog
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="katalogDropdown">
                            <li><a class="dropdown-item" href="#">Banner</a></li>
                            <li><a class="dropdown-item" href="#">Buku</a></li>
                            <li><a class="dropdown-item" href="#">Plakat</a></li>
                            <li><a class="dropdown-item" href="#">Stiker</a></li>
                            <li><a class="dropdown-item" href="#">Kartu Nama</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Tentang</a>
                    </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php"><i class="bi bi-cart"></i> Keranjang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
            <h2 class="text-center">Checkout</h2>
            <div class="card">
                <div class="card-body">
                    <form method="post" action="checkout.php">
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
