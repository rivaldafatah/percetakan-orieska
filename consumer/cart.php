<?php
session_start();
include '../includes/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Belanja - Percetakan Orieska</title>
</head>
<body>
    <h2>Keranjang Belanja</h2>
    <?php if (empty($cart)): ?>
        <p>Keranjang belanja Anda kosong.</p>
    <?php else: ?>
        <table border="1">
            <tr>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
            <?php foreach ($cart as $item): ?>
                <tr>
                    <td><?= $item['name'] ?></td>
                    <td>Rp <?= number_format($item['price'], 2, ',', '.') ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td>Rp <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3">Total</td>
                <td>Rp <?= number_format($total, 2, ',', '.') ?></td>
            </tr>
        </table>
        <a href="checkout.php">Checkout</a>
    <?php endif; ?>
    <a href="catalog.php">Lanjut Belanja</a>
</body>
</html>
