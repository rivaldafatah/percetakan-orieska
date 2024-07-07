<?php
session_start();
include '../includes/db.php';

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

// Mengambil data pesanan yang statusnya pending
$stmt = $conn->prepare("SELECT orders.*, users.username FROM orders JOIN users ON orders.user_id = users.id WHERE orders.status = 'pending'");
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $action = $_POST['action'];

    if ($action === 'produce') {
        // Mengubah status pesanan menjadi produksi
        $stmt = $conn->prepare("UPDATE orders SET status = 'in_production' WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Prosedur Produksi - Admin - Percetakan Orieska</title>
</head>
<body>
    <h2>Prosedur Produksi</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Username</th>
            <th>Total</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= $order['username'] ?></td>
                <td><?= $order['total'] ?></td>
                <td><?= $order['status'] ?></td>
                <td>
                    <form method="post" action="manage_productions.php">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <button type="submit" name="action" value="produce">Mulai Produksi</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
