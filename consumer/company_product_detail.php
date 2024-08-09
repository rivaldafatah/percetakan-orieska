<?php
session_start();
include '../includes/db.php';

// Mengambil ID produk dari URL
$product_id = $_GET['id'];

// Mengambil data produk dari database berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND company = 1");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Produk tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = $_POST['quantity'];
    $note = $_POST['note'];
    $design_file = $_FILES['design_file'];

    // Validasi jumlah pesanan sesuai dengan minimum order
    if ($quantity < $product['min_order']) {
        $_SESSION['error'] = "Jumlah pesanan minimal adalah " . $product['min_order'];
        header("Location: company_product_detail.php?id=$product_id");
        exit();
    }

    // Validasi file desain
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'cdr', 'psd', 'rar'];
    $file_extension = strtolower(pathinfo($design_file['name'], PATHINFO_EXTENSION));
    $max_file_size = 20 * 1024 * 1024; // 20MB

    if (!in_array($file_extension, $allowed_extensions)) {
        $_SESSION['error'] = "Tipe file tidak didukung. Silakan unggah file dengan ekstensi: " . implode(", ", $allowed_extensions);
        header("Location: company_product_detail.php?id=$product_id");
        exit();
    }

    if ($design_file['size'] > $max_file_size) {
        $_SESSION['error'] = "Ukuran file maksimal adalah 20MB.";
        header("Location: company_product_detail.php?id=$product_id");
        exit();
    }

    // Unggah file desain ke folder uploads/designs
    $target_dir = "../uploads/designs/";
    $target_file = $target_dir . basename($design_file['name']);
    if (!move_uploaded_file($design_file['tmp_name'], $target_file)) {
        $_SESSION['error'] = "Terjadi kesalahan saat mengunggah file.";
        header("Location: company_product_detail.php?id=$product_id");
        exit();
    }

    // Tambahkan produk ke keranjang belanja
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $item = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity,
        'design_file' => basename($design_file['name']),
        'note' => $note
    ];

    $_SESSION['cart'][] = $item;

    header('Location: company_cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Produk</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- AOS CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product {
            max-width: 900px;
            margin: auto;
            padding: 15px;
        }
        .product img {
            max-width: 100%;
            height: auto;
        }
        .product h3 {
            margin: 10px 0;
        }
        .product p {
            margin: 5px 0;
        }
        .product .price {
            font-size: 18px;
            color: #f60;
        }
        .product .description {
            margin: 20px 0;
        }
    </style>
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
                        <a class="nav-link" aria-current="page" href="../index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../layanan.php">Layanan Vendor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="company_catalog.php">Katalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../about.php">Tentang</a>
                    </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="company_cart.php"><i class="bi bi-cart"></i> Keranjang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="company_order_history.php"><i class="material-icons"></i> Riwayat</a>
                    </li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="company_logout.php">Logout</a></li>
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
                            <a class="nav-link" href="company_logout.php">Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">Detail Produk</h2>
        <div class="row justify-content-center">
            <div class="card product-card">
                <div class="row g-0">
                    <div class="col-md-6">
                        <img src="../uploads/products/<?= $product['image'] ?>" class="img-fluid rounded-start" alt="<?= $product['name'] ?>">
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <h3 class="card-title"><?= $product['name'] ?></h3>
                            <p class="price card-text">Rp <?= number_format($product['price'], 2, ',', '.') ?></p>
                            <p class="description card-text"><?= $product['description'] ?></p>
                            <p class="card-text"><strong>Estimasi Pengerjaan: </strong><?= htmlspecialchars($product['estimasi_pengerjaan']) ?></p>
                            <form method="post" action="company_product_detail.php?id=<?= $product['id'] ?>" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Jumlah (minimum order <?= $product['min_order'] ?>):</label>
                                    <input type="number" id="quantity" name="quantity" class="form-control" value="<?= $product['min_order'] ?>" min="<?= $product['min_order'] ?>" required>
                                 </div>
                                <div class="mb-3">
                                    <label for="note" class="form-label">Catatan:</label>
                                    <textarea id="note" name="note" class="form-control"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="design_file" class="form-label">Unggah File Desain Max 20 MB (JPG, JPEG, PNG, PDF, CDR, PSD, RAR):</label>
                                    <input type="file" id="design_file" name="design_file" class="form-control" accept=".jpg, .jpeg, .png, .pdf, .cdr, .psd, .rar" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Tambahkan ke Keranjang</button>
                            </form>
                            <a href="company_catalog.php" class="btn btn-secondary mt-3">Kembali ke Katalog</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<!-- Custom JavaScript to handle quantity input -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var quantityInput = document.getElementById('quantity');
    var minOrder = <?= $product['min_order'] ?>;

    quantityInput.addEventListener('input', function() {
        if (quantityInput.value < minOrder) {
            quantityInput.value = minOrder;
        }
    });
});
</body>
</html>
