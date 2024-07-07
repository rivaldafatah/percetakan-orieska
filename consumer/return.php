<?php
session_start();
include '../includes/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'];
$product_id = $_GET['product_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reason = $_POST['reason'];

    $stmt = $conn->prepare("INSERT INTO returns (order_id, product_id, reason) VALUES (?, ?, ?)");
    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("iis", $order_id, $product_id, $reason);
    $stmt->execute();

    header('Location: order_status.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajukan Pengembalian - Percetakan Orieska</title>
</head>
<body>
    <h2>Ajukan Pengembalian</h2>
    <form method="post" action="return.php?order_id=<?= $order_id ?>&product_id=<?= $product_id ?>">
        <label>Alasan Pengembalian:</label>
        <textarea name="reason" required></textarea>
        <button type="submit">Ajukan Pengembalian</button>
    </form>
    <a href="order_status.php">Kembali ke Status Pesanan</a>
</body>
</html>
