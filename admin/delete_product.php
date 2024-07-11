<?php
session_start();
include '../includes/db.php';

// // Pastikan hanya admin yang dapat mengakses halaman ini
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Mengambil informasi produk untuk menghapus file gambar dari server
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Menghapus produk dari database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Menghapus file gambar dari server
    if ($product['image']) {
        $image_path = "../uploads/products/" . $product['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    header('Location: manage_products.php');
    exit();
}
?>