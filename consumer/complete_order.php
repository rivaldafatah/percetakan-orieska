<?php
session_start();
include '../includes/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Mengambil order_id dari parameter URL
$order_id = $_GET['order_id'];

// Pastikan order_id valid dan milik pengguna yang sedang login
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Jika pesanan tidak ditemukan atau tidak milik pengguna
    header('Location: order_history.php');
    exit();
}

// Update status pesanan menjadi 'completed'
$stmt = $conn->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();

header('Location: order_history.php');
exit();
?>
