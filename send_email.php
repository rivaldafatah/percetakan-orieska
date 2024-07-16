<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

function sendEmailNotification($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        // Konfigurasi server
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'percetakanorieska@gmail.com'; // Ganti dengan email Anda
        $mail->Password   = 'orieska_printing1970'; // Ganti dengan app password Anda jika menggunakan 2FA
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Aktifkan debugging
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = 'html';

        // Penerima email
        $mail->setFrom('percetakanorieska@gmail.com', 'Percetakan Orieska');
        $mail->addAddress($to);

        // Konten email
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
