<?php
session_start();
include '../includes/db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$order_id = $_GET['order_id'];

// Mengambil detail pengiriman pengembalian dari tabel return_shipments dan status dari tabel orders
$stmt = $conn->prepare("SELECT rs.*, o.status AS order_status FROM return_shipments rs JOIN orders o ON rs.order_id = o.id WHERE rs.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$return_shipment = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = 'returned';

    // Memperbarui status pengembalian menjadi 'returned' di tabel returns
    $stmt = $conn->prepare("UPDATE returns SET status = ? WHERE order_id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("si", $status, $order_id);
    if (!$stmt->execute()) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();

    // Memperbarui status pengembalian di tabel orders
    $stmt = $conn->prepare("UPDATE orders SET status = 'returned' WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $order_id);
    if (!$stmt->execute()) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();

    header('Location: manage_returns.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
    <title>Detail Pengembalian - Admin - Percetakan Orieska</title>
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

        tr:nth-child(even) {background-color: #f2f2f2}

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
                            <li><a class="dropdown-item" href="add_inventory.php">Tambah Bahan Baku</a></li>
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
        <h2 class="text-center">Detail Pengembalian</h2>
        <?php if ($return_shipment): ?>
            <table class="table table-bordered">
                <tr>
                    <th>ID Pengembalian</th>
                    <td><?= htmlspecialchars($return_shipment['id']); ?></td>
                </tr>
                <tr>
                    <th>Order ID</th>
                    <td><?= htmlspecialchars($return_shipment['order_id']); ?></td>
                </tr>
                <tr>
                    <th>Nama Pengirim</th>
                    <td><?= htmlspecialchars($return_shipment['sender_name']); ?></td>
                </tr>
                <tr>
                    <th>Alamat Pengirim</th>
                    <td><?= htmlspecialchars($return_shipment['sender_address']); ?></td>
                </tr>
                <tr>
                    <th>Ekspedisi</th>
                    <td><?= htmlspecialchars($return_shipment['expedition']); ?></td>
                </tr>
                <tr>
                    <th>Status Pengembalian</th>
                    <td><?= htmlspecialchars($return_shipment['order_status']); ?></td>
                </tr>
            </table>
            <?php if ($return_shipment['order_status'] === 'being_returned'): ?>
                <form method="post" action="view_return_detail.php?order_id=<?= $order_id; ?>">
                    <input type="hidden" name="return_id" value="<?= $return_shipment['id']; ?>">
                    <button type="submit" class="btn btn-success">Tandai Sebagai Diterima</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-center">Tidak ada detail pengembalian yang ditemukan.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS dan dependensi -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
