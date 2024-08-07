<?php
session_start();
include '../includes/db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pemilik') {
    header('Location: login.php');
    exit();
}

// Mengambil ID pesanan dari URL
$order_id = $_GET['id'];

// Mengambil data pesanan dari database
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

// Mengambil detail pesanan dari database dengan nama produk
$stmt = $conn->prepare("SELECT order_items.*, products.name AS product_name FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_items.order_id = ?");
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
<html>
<head>
    <title>Detail Pesanan - Admin - Percetakan Orieska</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        .main-content {
            display: flex;
            height: 100%;
        }
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .sidebar {
            width: 250px;
            position: sticky;
            top: 0;
            background: #343a40;
            color: #fff;
            height: 100vh;
            overflow-y: auto;
            padding: 0;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .sidebar .nav-link.active {
            background: #495057;
        }
        .sidebar .nav-link:hover {
            background: #495057;
        }
        .sidebar .dropdown-menu {
            background: #343a40;
            border: none;
        }
        .sidebar .dropdown-item {
            color: #fff;
        }
        .sidebar .dropdown-item:hover {
            background: #495057;
        }
        .content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            height: calc(100vh - 56px); /* Adjust the height to account for the navbar */
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            text-align: left;
            padding: 8px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        th {
            background-color: #778899;
            color: white;
        }
        .img-fluid {
            max-width: 100%;
            height: 50%;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Pemilik Dashboard</a>
            <div class="d-flex">
                <div class="navbar-text text-white me-3">
                    Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                </div>
                <a class="btn btn-outline-light" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>
    <div class="main-content">
        <div class="sidebar">
            <div class="p-3">
                <h4>Menu</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Produk
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="manage_products.php">List Produk</a></li>
                            <li><a class="dropdown-item" href="add_product.php">Tambah Produk</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Manajemen Stok
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="manage_inventory.php">Stok Bahan</a></li>
                            <li><a class="dropdown-item" href="manage_requests.php">Cetak</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pengeluaran
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="manage_expenses.php">Laporan Pengeluaran</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pesanan Konsumen
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="manage_orders.php">Konsumen Perorangan</a></li>
                            <li><a class="dropdown-item" href="manage_company_orders.php">Konsumen Perusahaan</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_returns.php">Pengembalian</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="content">
            <h2>Detail Pesanan</h2>
            <table class="table table-bordered">
                <tr>
                    <th>ID Pesanan</th>
                    <td><?= htmlspecialchars($order['id']) ?></td>
                </tr>
                <tr>
                    <th>Pengguna</th>
                    <td><?= htmlspecialchars($order['user_id']) ?></td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td><?= htmlspecialchars($order['address']) ?></td>
                </tr>
                <tr>
                    <th>Metode Pembayaran</th>
                    <td><?= htmlspecialchars($order['payment_method']) ?></td>
                </tr>
                <tr>
                    <th>Total</th>
                    <td>Rp <?= number_format($order['total'], 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><?= htmlspecialchars($order['status']) ?></td>
                </tr>
                <tr>
                    <th>Resi</th>
                    <td><?= htmlspecialchars($order['tracking_number']) ?></td>
                </tr>
                <tr>
                    <th>Catatan</th>
                    <td><?= htmlspecialchars($notes) ?></td>
                </tr>
            </table>
            <h3>Detail Produk</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Id Produk</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>File Desain</th>
                        <th>Bukti Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?= $item['product_id'] ?></td>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td>Rp <?= number_format($item['price'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td>Rp <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></td>
                        <td>
                            <?php if ($item['design_file']): ?>
                                <a class="btn btn-success" href="../uploads/designs/<?= htmlspecialchars($item['design_file']) ?>" download>Unduh Desain</a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($order['payment_proof'])): ?>
                            <a class="btn btn-warning" href="../uploads/payment_proofs/<?= htmlspecialchars($order['payment_proof']) ?>"  target="_blank">Lihat Gambar</a>
                            <?php else: ?>
                            <p>Tidak ada bukti pembayaran yang diunggah.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
            <a class="btn btn-primary" href="manage_company_orders.php">Kembali ke Pengelolaan Pesanan</a>
        </div>
    </div>
</body>
</html>
