<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../includes/db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'pemilik') {
    header('Location: login.php');
    exit();
}

$order_id = $_GET['id'];
$new_status = $_GET['status'];

// Mengambil data pesanan beserta email pengguna
$stmt = $conn->prepare("SELECT orders.*, users.email FROM orders INNER JOIN users ON orders.user_id = users.id WHERE orders.id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$email = $order['email']; // Mengambil email dari hasil join

function sendStatusUpdateEmail($email, $status) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  
        $mail->SMTPAuth = true;
        $mail->Username = 'dalexganteng@gmail.com'; 
        $mail->Password = 'ayeh afnp pkeb kpoe'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('your-email@gmail.com', 'Percetakan Orieska');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Order Status Updated';
        $mail->Body    = "Your order status has been updated to: $status";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Menangani pembaruan status
if ($new_status == 'approved') {
    $stmt = $conn->prepare("UPDATE orders SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    sendStatusUpdateEmail($email, 'approved');
    header("Location: manage_orders.php");
    exit();
} elseif ($new_status == 'proofing') {
    header("Location: upload_proofing_design.php?id=$order_id");
    sendStatusUpdateEmail($email, 'proofing');
    exit();
} elseif ($new_status == 'production') {
    header("Location: input_materials.php?id=$order_id");
    sendStatusUpdateEmail($email, 'production');
    exit();
} elseif ($new_status == 'shipped') {
    header("Location: input_resi.php?id=$order_id");
    sendStatusUpdateEmail($email, 'shipped');
    exit();
    
} elseif ($new_status == 'rejected') {
    header("Location: reject_order.php?id=$order_id");
    sendStatusUpdateEmail($email, 'rejected');
    exit();

} elseif ($new_status == 'completed') {
    $stmt = $conn->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    sendStatusUpdateEmail($email, 'completed');
    header("Location: manage_orders.php");
    exit();
}
?>
