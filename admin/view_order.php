<?php
session_start();
include '../includes/db.php';

// // Pastikan hanya admin yang dapat mengakses halaman ini
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// Mengambil ID pesanan dari URL
$order_id = $_GET['id'];

// Mengambil data pesanan dari database
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

// Mengambil detail pesanan dari database
$stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order_items = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Pesanan - Admin - Percetakan Orieska</title>
</head>
<body>
    <h2>Detail Pesanan</h2>
    <p>ID Pesanan: <?= $order['id'] ?></p>
    <p>Pengguna: <?= $order['user_id'] ?></p>
    <p>Alamat: <?= $order['address'] ?></p>
    <p>Metode Pengiriman: <?= $order['shipping_method'] ?></p>
    <p>Metode Pembayaran: <?= $order['payment_method'] ?></p>
    <p>Total: Rp <?= number_format($order['total'], 2, ',', '.') ?></p>
    <p>Status: <?= $order['status'] ?></p>
    <h3>Detail Produk</h3>
    <table border="1">
        <tr>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>File Desain</th>
        </tr>
        <?php foreach ($order_items as $item): ?>
        <tr>
            <td><?= $item['product_id'] ?></td>
            <td>Rp <?= number_format($item['price'], 2, ',', '.') ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>Rp <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></td>
            <td>
                <?php if ($item['design_file']): ?>
                    <a href="../uploads/designs/<?= $item['design_file'] ?>" download>Unduh Desain</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="manage_orders.php">Kembali ke Pengelolaan Pesanan</a>
</body>
</html>
