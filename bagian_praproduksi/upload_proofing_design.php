<?php
session_start();
include '../includes/db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'bagian_praproduksi') {
    header('Location: login.php');
    exit();
}

// Mengambil ID pesanan dari URL dan memastikannya ada
if (!isset($_GET['id'])) {
    die("ID pesanan tidak ditemukan.");
}

$order_id = $_GET['id'];

// Mengambil data pesanan dari database
$stmt = $conn->prepare("SELECT orders.*, users.username, users.email, orders.rejection_notes FROM orders 
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['proofing_file'])) {
    $proofing_file = $_FILES['proofing_file'];

    // Validasi file proofing
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'cdr', 'psd', 'rar'];
    $file_extension = strtolower(pathinfo($proofing_file['name'], PATHINFO_EXTENSION));
    $max_file_size = 20 * 1024 * 1024; // 20MB

    if (!in_array($file_extension, $allowed_extensions)) {
        $_SESSION['error'] = "Tipe file tidak didukung. Silakan unggah file dengan ekstensi: " . implode(", ", $allowed_extensions);
        header("Location: upload_proofing_design.php?id=$order_id");
        exit();
    }

    if ($proofing_file['size'] > $max_file_size) {
        $_SESSION['error'] = "Ukuran file maksimal adalah 20MB.";
        header("Location: upload_proofing_design.php?id=$order_id");
        exit();
    }

    // Unggah file proofing ke folder uploads/proofing
    $target_dir = "../uploads/proofing/";
    $target_file = $target_dir . basename($proofing_file['name']);
    if (!move_uploaded_file($proofing_file['tmp_name'], $target_file)) {
        $_SESSION['error'] = "Terjadi kesalahan saat mengunggah file.";
        header("Location: upload_proofing_design.php?id=$order_id");
        exit();
    }

    // Memperbarui data pesanan dengan file proofing baru
    $stmt = $conn->prepare("UPDATE orders SET proofing_file = ?, status = 'proofing' WHERE id = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("si", $proofing_file['name'], $order_id);
    $stmt->execute();

    $_SESSION['success'] = "Desain proofing berhasil diunggah.";
    header("Location: manage_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
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
            <h2>Detail Pesanan</h2>
            <table class="table table-bordered">
                <tr>
                    <th>ID Pesanan</th>
                    <td><?= htmlspecialchars($order['id']) ?></td>
                </tr>
                <tr>
                    <th>Pengguna</th>
                    <td><?= htmlspecialchars($order['username']) ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= htmlspecialchars($order['email']) ?></td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td><?= htmlspecialchars($order['address']) ?></td>
                </tr>
                <tr>
                    <th>Metode Pengiriman</th>
                    <td><?= htmlspecialchars($order['shipping_method']) ?></td>
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
                    <th>Catatan Penolakan</th>
                    <td><?= htmlspecialchars($order['rejection_notes']) ?></td>
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
                                <a class="btn btn-primary" href="../uploads/designs/<?= htmlspecialchars($item['design_file']) ?>" download>Unduh Desain</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <form method="post" action="upload_proofing_design.php?id=<?= $order['id'] ?>" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="proofing_file" class="form-label">Unggah File Desain Proofing Baru (JPG, JPEG, PNG, PDF, CDR, PSD, RAR):</label>
                    <input type="file" id="proofing_file" name="proofing_file" class="form-control" accept=".jpg, .jpeg, .png, .pdf, .cdr, .psd, .rar" required>
                </div>
                <button type="submit" class="btn btn-primary">Unggah Desain Proofing Baru</button>
            </form>

            <br>
            <a class="btn btn-secondary" href="manage_orders.php">Kembali ke Pengelolaan Pesanan</a>
        </div>
    </div>
</body>
</html>
