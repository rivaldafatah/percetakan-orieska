<?php
session_start();
include '../includes/db.php';

// Pastikan hanya admin bagian produksi yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'bagian_produksi') {
    header('Location: login.php');
    exit();
}

$order_id = $_GET['id'];

// Mengambil data produk yang dipesan berdasarkan order_id
$stmt = $conn->prepare("
    SELECT p.product_code, p.name AS product_name, oi.quantity, p.price, (oi.quantity * p.price) AS total_price
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order_products = $result->fetch_all(MYSQLI_ASSOC);

// Mengambil daftar bahan baku yang terkait dengan produk yang dipesan
$stmt = $conn->prepare("
    SELECT pm.product_id, pm.material_id, i.name, i.quantity 
    FROM product_materials pm 
    JOIN inventory i ON pm.material_id = i.id 
    WHERE pm.product_id IN (SELECT product_id FROM order_items WHERE order_id = ?)
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$materials = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $materials = $_POST['materials'];
    $quantities = $_POST['quantities'];

    // Memulai transaksi
    $conn->begin_transaction();

    try {
        // Memperbarui stok bahan baku
        foreach ($materials as $index => $material_id) {
            $quantity = $quantities[$index];

            // Kurangi stok bahan baku
            $stmt = $conn->prepare("UPDATE inventory SET quantity = quantity - ? WHERE id = ?");
            $stmt->bind_param("ii", $quantity, $material_id);
            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }
        }

        // Perbarui status pesanan menjadi produksi
        $stmt = $conn->prepare("UPDATE orders SET status = 'production' WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }

        // Commit transaksi
        $conn->commit();
        header("Location: manage_orders.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $conn->rollback();
        die("Error updating order status: " . htmlspecialchars($e->getMessage()));
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Input Bahan - Admin - Percetakan Orieska</title>
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
    <script>
        function validateQuantities() {
            let valid = true;
            document.querySelectorAll('.material-group').forEach(group => {
                const quantityInput = group.querySelector('input[name="quantities[]"]');
                const maxQuantity = parseInt(group.getAttribute('data-max-quantity'), 10);
                const quantity = parseInt(quantityInput.value, 10);
                if (quantity > maxQuantity) {
                    alert(`Kuantitas Untuk ${group.querySelector('input[name="material_names[]"]').value} Kurang dari jumlah stok : ${maxQuantity}`);
                    valid = false;
                }
            });
            return valid;
        }
    </script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Bagian Produksi Dashboard</a>
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
                            Manajemen Stok
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="manage_inventory.php">Stok Bahan</a></li>
                            <li><a class="dropdown-item" href="add_inventory.php">Tambah Bahan Baku</a></li>
                            <li><a class="dropdown-item" href="manage_requests.php">Cetak</a></li>
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
                </ul>
            </div>
        </div>

        <div class="content">
            <h2>Input Bahan untuk Produksi</h2>

            <!-- Tabel Produk yang Dipesan -->
            <h4>Detail Produk Pesanan</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order_products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['product_code']) ?></td>
                        <td><?= htmlspecialchars($product['product_name']) ?></td>
                        <td><?= htmlspecialchars($product['quantity']) ?></td>
                        <td>Rp <?= number_format($product['price'], 2, ',', '.') ?></td>
                        <td>Rp <?= number_format($product['total_price'], 2, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Form Input Bahan -->
            <form method="post" action="input_materials.php?id=<?= $order_id ?>" onsubmit="return validateQuantities();">
                <div id="materials-container">
                    <?php foreach ($materials as $index => $material): ?>
                    <div class="mb-3 material-group" data-max-quantity="<?= $material['quantity'] ?>">
                        <label for="material-<?= $index ?>" class="form-label">Bahan Baku:</label>
                        <input type="text" id="material-<?= $index ?>" name="material_names[]" class="form-control" value="<?= $material['name'] ?> (Stok: <?= $material['quantity'] ?>)" readonly>
                        <input type="hidden" name="materials[]" value="<?= $material['material_id'] ?>">
                        <label for="quantity-<?= $index ?>" class="form-label">Quantity:</label>
                        <input type="number" id="quantity-<?= $index ?>" name="quantities[]" class="form-control" required>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
