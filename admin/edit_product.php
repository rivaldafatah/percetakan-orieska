<?php
session_start();
include '../includes/db.php';

// // Pastikan hanya admin yang dapat mengakses halaman ini
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// Mengambil data produk berdasarkan ID yang diberikan
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
}

// Proses pengeditan produk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];

    if ($image) {
        $target_dir = "../uploads/products/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    } else {
        $image = $product['image'];
    }

    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ? WHERE id = ?");
    $stmt->bind_param("ssdsi", $name, $description, $price, $image, $id);
    $stmt->execute();

    header('Location: manage_products.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk - Admin - Percetakan Orieska</title>
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
        <h2>Edit Produk</h2>
        <div class="container">
        <form method="post" action="edit_product.php" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="hidden" class="form-control" name="id" value="<?= $product['id'] ?>">
                <label class="form-label">Nama Produk:</label>
                <input type="text" class="form-control" name="name" value="<?= $product['name'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi:</label>
                <textarea name="description" class="form-control" required><?= $product['description'] ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga:</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar:</label>
                <input type="file" name="image" class="form-control">
            <?php if ($product['image']): ?>
                <img src="../uploads/products/<?= $product['image'] ?>" alt="<?= $product['name'] ?>" width="100">
            <?php endif; ?>
            </div>
            <button type="submit">Update Produk</button>
    </form>
    </div>
</body>
</html>
