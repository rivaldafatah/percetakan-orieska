<?php
session_start();
include '../includes/db.php';

// Pastikan pengguna sudah login dan merupakan konsumen perusahaan
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $note = $_POST['note'];
    $design_file = null;

    // Mengelola upload file desain
    if (!empty($_FILES['design_file']['name'])) {
        $design_file = $_FILES['design_file']['name'];
        $target_dir = "../uploads/designs/";
        $target_file = $target_dir . basename($design_file);
        move_uploaded_file($_FILES['design_file']['tmp_name'], $target_file);
    }

    // Mengambil informasi produk dari database
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if ($product) {
        $cart_item = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'note' => $note,
            'design_file' => $design_file
        ];

        // Menambahkan produk ke keranjang
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $_SESSION['cart'][] = $cart_item;

        header('Location: company_cart.php');
        exit();
    } else {
        // Produk tidak ditemukan
        header('Location: company_catalog.php');
        exit();
    }
} else {
    // Metode permintaan tidak valid
    header('Location: company_catalog.php');
    exit();
}
?>
