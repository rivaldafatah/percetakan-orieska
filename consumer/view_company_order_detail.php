<?php
session_start();
include '../includes/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Mengambil ID pesanan dari URL
$order_id = $_GET['order_id'];

// Mengambil data pesanan dari database
$stmt = $conn->prepare("SELECT orders.*, users.username, users.email FROM orders 
                        JOIN users ON orders.user_id = users.id 
                        WHERE orders.id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Pesanan tidak ditemukan.");
}

// Mengambil detail pesanan dari database dengan nama produk
$stmt = $conn->prepare("SELECT order_items.*, products.name AS product_name FROM order_items 
                        JOIN products ON order_items.product_id = products.id 
                        WHERE order_items.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order_items = $result->fetch_all(MYSQLI_ASSOC);

// Mengambil catatan pesanan dari detail pesanan (jika ada)
$notes = [];
foreach ($order_items as $item) {
    if (!empty($item['note'])) {
        $notes[] = $item['note'];
    }
}
$notes = implode(", ", $notes);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan Perusahaan - Percetakan Orieska</title>
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
                        <a class="nav-link" href="company_cart.php"><i class="bi bi-cart"></i> Keranjang</a>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center">Detail Pesanan Perusahaan</h2>
                        <table class="table table-bordered">
                            <tr>
                                <th>ID Pesanan:</th>
                                <td><?= htmlspecialchars($order['id']) ?></td>
                            </tr>
                            <tr>
                                <th>Pengguna:</th>
                                <td><?= htmlspecialchars($order['username']) ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?= htmlspecialchars($order['email']) ?></td>
                            </tr>
                            <tr>
                                <th>Catatan:</th>
                                <td><?= htmlspecialchars($notes) ?></td>
                            </tr>
                            <tr>
                                <th>Metode Pembayaran:</th>
                                <td><?= htmlspecialchars($order['payment_method']) ?></td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td>Rp <?= number_format($order['total'], 2, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td><?= htmlspecialchars($order['status']) ?></td>
                            </tr>
                            <tr>
                                <th>Resi:</th>
                                <td><?= htmlspecialchars($order['tracking_number']); ?></td>
                            </tr>
                        </table>

                        <h3>Detail Produk</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>File Desain</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                                        <td>Rp <?= number_format($item['price'], 2, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                                        <td>Rp <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></td>
                                        <td>
                                            <?php if ($item['design_file']): ?>
                                                <a class="btn btn-primary btn-sm" href="../uploads/designs/<?= htmlspecialchars($item['design_file']) ?>" download>Unduh Desain</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <?php if ($order['payment_proof']): ?>
                            <a class="btn btn-warning" href="../uploads/payment_proofs/<?= htmlspecialchars($order['payment_proof']); ?>" target="_blank">Lihat Bukti Pembayaran</a>
                        <?php else: ?>
                            <p>Belum ada bukti pembayaran.</p>
                        <?php endif; ?>

                        <a class="btn btn-secondary" href="company_order_history.php">Kembali</a>
                        <a class="btn btn-success" href="company_print_invoice.php?order_id=<?= $order['id'] ?>" target="_blank">Cetak Invoice</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
