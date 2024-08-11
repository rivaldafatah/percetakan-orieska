<?php
include '../includes/db.php';

if (isset($_GET['code'])) {
    $verificationCode = $_GET['code'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE verification_code = ?");
    $stmt->bind_param("s", $verificationCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt = $conn->prepare("UPDATE users SET email_verified = TRUE WHERE verification_code = ?");
        $stmt->bind_param("s", $verificationCode);
        $stmt->execute();

        echo "Email berhasil diverifikasi. Silakan login.";
    } else {
        echo "Kode verifikasi tidak valid.";
    }
}
?>
