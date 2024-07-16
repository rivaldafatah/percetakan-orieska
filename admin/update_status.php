<?php
session_start();
include '../includes/db.php';
include '../send_email.php'; // Pastikan path ini benar

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Mengambil ID pesanan dan status baru dari URL
$order_id = $_GET['id'];
$new_status = $_GET['status'];

// Mengambil email konsumen berdasarkan ID pesanan
$stmt = $conn->prepare("SELECT users.email, orders.* FROM orders JOIN users ON orders.user_id = users.id WHERE orders.id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Pesanan tidak ditemukan.");
}

// Menentukan URL untuk halaman input
if ($new_status === 'production') {
    header("Location: input_materials.php?id=$order_id");
    exit();
} elseif ($new_status === 'shipped') {
    header("Location: input_resi.php?id=$order_id");
    exit();
}

// Memperbarui status pesanan
$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $new_status, $order_id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Mengirim notifikasi email
    $email = $order['email'];
    $subject = "Pembaruan Status Pesanan Anda";
    
    // Menambahkan informasi estimasi berdasarkan status baru
    switch ($new_status) {
        case 'production':
            $body = "Status pesanan Anda dengan ID $order_id telah diperbarui menjadi 'Dalam Produksi'. Estimasi waktu pengerjaan produksi adalah 3-5 hari kerja.";
            break;
        case 'shipped':
            $body = "Status pesanan Anda dengan ID $order_id telah diperbarui menjadi 'Dikirim'. Estimasi waktu pengiriman adalah 2-4 hari kerja.";
            break;
        case 'completed':
            $body = "Status pesanan Anda dengan ID $order_id telah diperbarui menjadi 'Selesai'. Terima kasih telah berbelanja di Percetakan Orieska.";
            break;
        default:
            $body = "Status pesanan Anda dengan ID $order_id telah diperbarui menjadi '$new_status'.";
            break;
    }

    $email_sent = sendEmailNotification($email, $subject, $body);
    if ($email_sent === true) {
        echo "Status pesanan diperbarui dan email notifikasi telah dikirim.";
    } else {
        echo "Status pesanan diperbarui tetapi email notifikasi gagal dikirim. Error: $email_sent";
    }
} else {
    echo "Gagal memperbarui status pesanan.";
}
?>
