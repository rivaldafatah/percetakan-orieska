<?php
session_start();
include '../includes/db.php';

// Pastikan pengguna sudah login dan merupakan konsumen perusahaan
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $payment_proof = $_FILES['payment_proof']['name'];
    $target_dir = "../uploads/payment_proofs/";
    $target_file = $target_dir . basename($payment_proof);
    move_uploaded_file($_FILES['payment_proof']['tmp_name'], $target_file);

    $stmt = $conn->prepare("UPDATE orders SET payment_proof = ? WHERE id = ?");
    $stmt->bind_param("si", $payment_proof, $order_id);
    $stmt->execute();

    header('Location: company_order_history.php');
    exit();
}
?>
