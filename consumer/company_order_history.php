<?php
session_start();
include '../includes/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Mengambil data pesanan dari database dan mengurutkan berdasarkan order_id dalam urutan menurun
$stmt = $conn->prepare("SELECT orders.id AS order_id, orders.total, orders.status, 
                        orders.tracking_number, order_items.product_id, order_items.quantity, 
                        order_items.note, products.name AS product_name, orders.payment_proof
                        FROM orders 
                        JOIN order_items ON orders.id = order_items.order_id
                        JOIN products ON order_items.product_id = products.id
                        WHERE orders.user_id = ?
                        ORDER BY orders.id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = [];

while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];
    $payment_proof = '';

    // Menangani upload gambar bukti pembayaran jika ada
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

        // Memperbarui data pesanan dengan bukti pembayaran
        $stmt = $conn->prepare("UPDATE orders SET payment_proof = ? WHERE id = ?");
        if ($stmt === false) {
            die("Error preparing statement: " . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("si", $payment_proof, $order_id);
        $stmt->execute();
        
        header('Location: company_order_history.php');
        exit();
    } else {
        die("Error: No payment proof uploaded.");
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan Perusahaan - Percetakan Orieska</title>
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
        <h2 class="text-center">Riwayat Pesanan Perusahaan</h2>
        <?php if (empty($orders)): ?>
            <p class="text-center">Tidak ada riwayat pesanan.</p>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Nama Produk</th>
                        <th>Quantity</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Nomor Resi</th>
                        <th>Catatan</th>
                        <th>Bukti Pembayaran</th>
                        <th>Upload Bukti Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['order_id']); ?></td>
                            <td><?= htmlspecialchars($order['product_name']); ?></td>
                            <td><?= htmlspecialchars($order['quantity']); ?></td>
                            <td>Rp <?= number_format($order['total'], 2, ',', '.'); ?></td>
                            <td><?= htmlspecialchars($order['status']); ?></td>
                            <td><?= htmlspecialchars($order['tracking_number']); ?></td>
                            <td><?= htmlspecialchars($order['note']); ?></td>
                            <td>
                                <?php if ($order['payment_proof']): ?>
                                    <a href="../uploads/payment_proofs/<?= htmlspecialchars($order['payment_proof']); ?>" target="_blank">Lihat Bukti Pembayaran</a>
                                <?php else: ?>
                                    Belum ada bukti
                                <?php endif; ?>
                            </td>
                            <td>
                                <form method="post" action="company_order_history.php" enctype="multipart/form-data">
                                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['order_id']); ?>">
                                    <div class="mb-3">
                                        <input type="file" name="payment_proof" class="form-control" accept="image/jpeg,image/png" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Unggah</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>