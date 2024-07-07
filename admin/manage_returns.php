<?php
session_start();
include '../includes/db.php';

// // Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Mengambil daftar pengembalian dari database
$stmt = $conn->prepare("SELECT returns.*, orders.user_id, products.name as product_name FROM returns
                        JOIN orders ON returns.order_id = orders.id
                        JOIN products ON returns.product_id = products.id");
$stmt->execute();
$result = $stmt->get_result();
$returns = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pengelolaan Pengembalian - Admin - Percetakan Orieska</title>
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

tr:nth-child(even){background-color: #f2f2f2}

th {
  background-color: #778899;
  color: white;
}
</style>
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
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
                            <li><a class="dropdown-item" href="request_stock.php">Permintaan Bahan Baku</a></li>
                            <li><a class="dropdown-item" href="manage_requests.php">Cetak</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pengeluaran
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="input_expense.php">Tambah Pengeluaran</a></li>
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
                    <li class="nav-item">
                        <a class="nav-link" href="company_register.php">Daftar Akun Perusahaan</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Kelola Akun Konsumen
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="manage_accounts.php">Akun Perorangan</a></li>
                            <li><a class="dropdown-item" href="manage_company_accounts.php">Akun Perusahaan</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_users.php">Kelola Semua Akun</a>
                    </li>
                </ul>
            </div>
        </div>
    <div class="content">
    <h2>Pengelolaan Pengembalian</h2>
    <table>
        <tr>
            <th>ID Pengembalian</th>
            <th>ID Pesanan</th>
            <th>ID Pengguna</th>
            <th>Produk</th>
            <th>Alasan</th>
            <th>Status</th>
            <th>Tanggal Dibuat</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($returns as $return): ?>
        <tr>
            <td><?= $return['id'] ?></td>
            <td><?= $return['order_id'] ?></td>
            <td><?= $return['user_id'] ?></td>
            <td><?= $return['product_name'] ?></td>
            <td><?= $return['reason'] ?></td>
            <td><?= $return['status'] ?></td>
            <td><?= $return['created_at'] ?></td>
            <td>
                <?php if ($return['status'] === 'pending'): ?>
                    <a href="process_return.php?id=<?= $return['id'] ?>">Proses</a>
                <?php else: ?>
                    Diproses
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    </div>
</body>
</html>
