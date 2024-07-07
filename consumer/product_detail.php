<?php
session_start();
include '../includes/db.php';

// Mengambil ID produk dari URL
$product_id = $_GET['id'];

// Mengambil data produk dari database berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Produk tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = $_POST['quantity'];
    $design_file = $_FILES['design_file']['name'];

    // Unggah file desain ke folder uploads/designs
    $target_dir = "../uploads/designs/";
    $target_file = $target_dir . basename($design_file);
    move_uploaded_file($_FILES['design_file']['tmp_name'], $target_file);

    // Tambahkan produk ke keranjang belanja
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $item = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $quantity,
        'design_file' => $design_file
    ];

    $_SESSION['cart'][] = $item;

    header('Location: cart.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Produk - Percetakan Orieska</title>
    <style>
        .product {
            max-width: 600px;
            margin: auto;
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
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
    <h2>Detail Produk</h2>
    <div class="product">
        <img src="../uploads/products/<?= $product['image'] ?>" alt="<?= $product['name'] ?>">
        <h3><?= $product['name'] ?></h3>
        <p class="price">Rp <?= number_format($product['price'], 2, ',', '.') ?></p>
        <p class="description"><?= $product['description'] ?></p>
        <form method="post" action="product_detail.php?id=<?= $product['id'] ?>" enctype="multipart/form-data">
            <label>Jumlah:</label>
            <input type="number" name="quantity" value="1" min="1" required>
            <label>Unggah File Desain (PDF):</label>
            <input type="file" name="design_file" accept="application/pdf" required>
            <button type="submit">Tambahkan ke Keranjang</button>
        </form>
        <a href="catalog.php">Kembali ke Katalog</a>
    </div>
</body>
</html>
