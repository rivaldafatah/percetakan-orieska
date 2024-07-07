<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header('Location: company_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $total = 0;
    $order_items = json_decode($_POST['order_items'], true);
    
    foreach ($order_items as $item) {
        $total += $item['quantity'] * getProductPrice($item['product_id']);
    }

    $stmt = $conn->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("id", $user_id, $total);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    foreach ($order_items as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, design_file, note) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiidss", $order_id, $item['product_id'], $item['quantity'], getProductPrice($item['product_id']), $item['design_file'], $item['note']);
        $stmt->execute();
    }

    $success = "Pesanan berhasil dibuat.";
}

function getProductPrice($product_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    return $product['price'];
}

// Mengambil data pesanan perusahaan
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pesanan Perusahaan - Percetakan Orieska</title>
</head>
<body>
    <h2>Pesanan Perusahaan</h2>
    <?php if (isset($success)) { echo "<p>$success</p>"; } ?>
    <form method="post" action="company_orders.php">
        <h3>Buat Pesanan Baru</h3>
        <label>Daftar Pesanan (JSON):</label>
        <textarea name="order_items" required></textarea>
        <button type="submit">Buat Pesanan</button>
    </form>
    <h3>Status Pesanan</h3>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Total</th>
            <th>Status</th>
            <th>Detail</th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= $order['total'] ?></td>
                <td><?= $order['status'] ?></td>
                <td><a href="order_detail.php?order_id=<?= $order['id'] ?>">Lihat Detail</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>