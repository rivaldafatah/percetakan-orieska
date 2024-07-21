<?php
session_start();
include '../includes/db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Mengambil ID retur dan tindakan (accept/reject) dari URL
$return_id = $_GET['id'];
$action = $_GET['action'];

if ($action === 'accept') {
    // Update status menjadi accepted
    $stmt = $conn->prepare("UPDATE returns SET status = 'accepted' WHERE id = ?");
    $stmt->bind_param("i", $return_id);
    $stmt->execute();

    // Update status pesanan terkait menjadi return_accepted
    $stmt = $conn->prepare("UPDATE orders SET status = 'return_accepted' WHERE id = (SELECT order_id FROM returns WHERE id = ?)");
    $stmt->bind_param("i", $return_id);
    $stmt->execute();
} elseif ($action === 'reject') {
    // Update status menjadi rejected
    $stmt = $conn->prepare("UPDATE returns SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param("i", $return_id);
    $stmt->execute();

    // Update status pesanan terkait menjadi return_rejected
    $stmt = $conn->prepare("UPDATE orders SET status = 'return_rejected' WHERE id = (SELECT order_id FROM returns WHERE id = ?)");
    $stmt->bind_param("i", $return_id);
    $stmt->execute();
}

header('Location: manage_returns.php');
exit();
?>
