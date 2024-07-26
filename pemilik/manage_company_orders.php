<?php
session_start();
include '../includes/db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pemilik') {
    header('Location: login.php');
    exit();
}

// Mengambil daftar pesanan dari konsumen perorangan dari database dan mengurutkannya berdasarkan id pesanan dalam urutan menurun
$stmt = $conn->prepare("SELECT orders.*, users.username FROM orders JOIN users ON orders.user_id = users.id WHERE users.role = 'company' ORDER BY orders.id DESC");
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pengelolaan Pesanan - Admin - Percetakan Orieska</title>
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
        <h2>Pengelolaan Pesanan Konsumen Perorangan</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Pengguna</th>
                <th>Metode Pembayaran</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $order['username'] ?></td>
                    <td><?= $order['payment_method'] ?></td>
                    <td>Rp <?= number_format($order['total'], 2, ',', '.') ?></td>
                    <td><?= $order['status'] ?></td>
                    <td>
                        <a class="btn btn-primary btn-sm" href="view_company_order.php?id=<?= $order['id'] ?>" role="button">Lihat Detail</a>
                        <a class="btn btn-secondary btn-sm" href="update_status.php?id=<?= $order['id'] ?>&status=approved" role="button">Update Status Setujui</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
</body>
</html>
