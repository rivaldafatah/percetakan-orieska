<?php
session_start();
include '../includes/db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pemilik') {
    header('Location: login.php');
    exit();
}

// Mengambil daftar bahan baku dari database
$stmt = $conn->prepare("SELECT * FROM inventory");
$stmt->execute();
$result = $stmt->get_result();
$inventories = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $estimasi_pengerjaan = $_POST['estimasi_pengerjaan'];
    $min_order = $_POST['min_order'];
    $image = $_FILES['image']['name'];

    $target_dir = "../uploads/products/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, estimasi_pengerjaan, min_order, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsds", $name, $description, $price, $estimasi_pengerjaan, $min_order, $image);
    $stmt->execute();
    
    $product_id = $stmt->insert_id;

    if (isset($_POST['materials'])) {
        $materials = $_POST['materials'];
        foreach ($materials as $material_id) {
            $stmt = $conn->prepare("INSERT INTO product_materials (product_id, material_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $product_id, $material_id);
            $stmt->execute();
        }
    }

    header('Location: manage_products.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk - Admin - Percetakan Orieska</title>
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
        <h2>Tambah Produk Baru</h2>
        <form method="post" action="add_product.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nama Produk:</label>
                <input type="text" class="form-control" name="name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi:</label>
                <textarea class="form-control" rows="3" name="description" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga:</label>
                <input type="number" class="form-control" step="0.01" name="price" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Estimasi Pengerjaan:</label>
                <input type="text" class="form-control" name="estimasi_pengerjaan" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Minimum Order:</label>
                <input type="number" class="form-control" name="min_order" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar:</label>
                <input type="file" class="form-control" name="image" required>
            </div>
            <div id="materials-container">
                <div class="mb-3">
                    <label for="material-0" class="form-label">Bahan Baku:</label>
                    <select id="material-0" name="materials[]" class="form-select">
                        <?php foreach ($inventories as $item): ?>
                            <option value="<?= $item['id'] ?>"><?= $item['name'] ?> (Stok: <?= $item['quantity'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="button" class="btn btn-secondary" onclick="addMaterial()">Tambah Bahan Lain</button>
            <button type="submit" class="btn btn-primary">Tambah Produk</button>
        </form>
    </div>
</div>

<script>
    let materialIndex = 1;

    function addMaterial() {
        const container = document.getElementById('materials-container');
        const newMaterial = document.createElement('div');
        newMaterial.classList.add('mb-3');
        newMaterial.innerHTML = `
            <label for="material-${materialIndex}" class="form-label">Bahan Baku:</label>
            <select id="material-${materialIndex}" name="materials[]" class="form-select">
                <?php foreach ($inventories as $item): ?>
                    <option value="<?= $item['id'] ?>"><?= $item['name'] ?> (Stok: <?= $item['quantity'] ?>)</option>
                <?php endforeach; ?>
            </select>
        `;
        container.appendChild(newMaterial);
        materialIndex++;
    }
</script>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
