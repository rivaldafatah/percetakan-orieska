<?php
session_start();
include '../includes/db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'bagian_praproduksi') {
    header('Location: login.php');
    exit();
}

$order_id = $_GET['id'];
$new_status = $_GET['status'];

// Mengambil data pesanan
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

// Menangani pembaruan status
if ($new_status == 'approved') {
    // Perbarui Status ke setujui pesanan
    $stmt = $conn->prepare("UPDATE orders SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    header("Location: manage_orders.php");
    exit();
} elseif ($new_status == 'proofing') {
    // Perbarui Status ke setujui pesanan
    $stmt = $conn->prepare("UPDATE orders SET status = 'proofing' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    header("Location: manage_orders.php");
    exit();
} elseif ($new_status == 'production') {
    // Arahkan ke halaman input bahan
    header("Location: input_materials.php?id=$order_id");
    exit();
} elseif ($new_status == 'shipped') {
    // Arahkan ke halaman input resi
    header("Location: input_resi.php?id=$order_id");
    exit();
} elseif ($new_status == 'completed') {
    // Perbarui status menjadi selesai
    $stmt = $conn->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    header("Location: manage_orders.php");
    exit();
}
?>
