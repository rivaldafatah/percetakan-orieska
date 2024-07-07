<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Mengambil data pesanan konsumen
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Status Pesanan - Percetakan Orieska</title>
</head>
<body>
    <h2>Status Pesanan</h2>
    <h3><a href="logout.php">Logout</h3>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Total</th>
            <th>Status</th>
            <th>Detail</th>
            <th>Faktur</th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= $order['total'] ?></td>
                <td><?= $order['status'] ?></td>
                <td><a href="order_detail.php?order_id=<?= $order['id'] ?>">Lihat Detail</a></td>
                <td><a href="invoice.php?order_id=<?= $order['id'] ?>" target="_blank">Lihat Faktur</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>