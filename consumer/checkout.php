<?php
session_start();
include '../includes/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $address = $_POST['address'];
    $shipping_method = $_POST['shipping_method'];
    $payment_method = $_POST['payment_method'];

    // Menyimpan data pesanan ke database
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $stmt = $conn->prepare("INSERT INTO orders (user_id, address, shipping_method, payment_method, total, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("isssd", $user_id, $address, $shipping_method, $payment_method, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Menyimpan detail pesanan ke database
    foreach ($cart as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, design_file) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Error preparing statement for order items: " . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("iiids", $order_id, $item['id'], $item['quantity'], $item['price'], $item['design_file']);
        $stmt->execute();
    }

    // Mengosongkan keranjang belanja
    unset($_SESSION['cart']);

    header('Location: order_success.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Percetakan Orieska</title>
</head>
<body>
    <h2>Checkout</h2>
    <form method="post" action="checkout.php">
        <label>Alamat Pengiriman:</label>
        <textarea name="address" required></textarea>
        <label>Metode Pengiriman:</label>
        <select name="shipping_method">
            <option value="ambil">Ambil di Tempat</option>
            <option value="kirim">Dikirim ke Alamat</option>
        </select>
        <label>Metode Pembayaran:</label>
        <select name="payment_method">
            <option value="transfer">Transfer Bank</option>
        </select>
        <button type="submit">Proses Checkout</button>
    </form>
    <a href="cart.php">Kembali ke Keranjang</a>
</body>
</html>
