<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';  // Pastikan path ini sesuai dengan struktur project Anda
include '../includes/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Mengambil order_id dari parameter URL
$order_id = $_GET['order_id'];

// Pastikan order_id valid dan milik pengguna yang sedang login
$stmt = $conn->prepare("SELECT orders.*, users.email FROM orders INNER JOIN users ON orders.user_id = users.id WHERE orders.id = ? AND orders.user_id = ?");
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Jika pesanan tidak ditemukan atau tidak milik pengguna
    header('Location: order_history.php');
    exit();
}

$order = $result->fetch_assoc();
$email = $order['email']; // Mengambil email pengguna

// Fungsi untuk mengirim email notifikasi
function sendStatusUpdateEmail($email, $order_code) {
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
        $mail->Body    = "Pesanan Anda dengan kode: <strong>$order_code</strong> telah selesai. <br>Terima kasih telah berbelanja di Percetakan Orieska.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}

// Update status pesanan menjadi 'completed'
$stmt = $conn->prepare("UPDATE orders SET status = 'completed' WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();

// Kirim email notifikasi ke pengguna
sendStatusUpdateEmail($email, $order['order_code']);

header('Location: order_history.php');
exit();
?>
