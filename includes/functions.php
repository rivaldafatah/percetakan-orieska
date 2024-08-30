<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

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
function getProductPrice($product_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    return $product['price'];
}

function checkAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        header('Location: login.php');
        exit();
    }
}

function checkUser() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
}
?>
