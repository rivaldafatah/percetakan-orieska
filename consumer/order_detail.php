<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$order_id = $_GET['order_id'];

$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

$stmt = $conn->prepare("SELECT order_items.*, products.name FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order_items = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Pesanan - Percetakan Orieska</title>
</head>
<body>
    <h2>Detail Pesanan</h2>
    <p>Order ID: <?= $order['id'] ?></p>
    <p>Total: <?= $order['total'] ?></p>
    <p>Status: <?= $order['status'] ?></p>
    <h3>Item Pesanan</h3>
    <ul>
        <?php foreach ($order_items as $item): ?>
            <li>
                Produk: <?= $item['name'] ?> - Jumlah: <?= $item['quantity'] ?> - Harga: <?= $item['price'] ?> - Desain: <?= $item['design_file'] ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
