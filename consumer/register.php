<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../includes/db.php';

$success = "";

function sendVerificationEmail($email, $verificationCode) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Set your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'dalexganteng@gmail.com'; // SMTP username
        $mail->Password = 'ayeh afnp pkeb kpoe'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('your-email@gmail.com', 'Percetakan Orieska');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Verifikasi Email Perusahaan';
        $mail->Body    = "Klik link di bawah ini untuk memverifikasi email perusahaan Anda:<br><a href='http://localhost/percetakan-orieska/consumer/verify.php?code=$verificationCode'>Verifikasi Email</a>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $verificationCode = md5(uniqid(rand(), true));

    // Insert new user to get the ID
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, role, verification_code) VALUES (?, ?, ?, 'individual', ?)");
    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ssss", $username, $password, $email, $verificationCode);
    $stmt->execute();

    // Get the last inserted ID
    $user_id = $stmt->insert_id;

    // Generate user code using the last inserted ID
    $user_code = 'USR-' . str_pad($user_id, 5, '0', STR_PAD_LEFT);

    // Update the user with the generated user code
    $stmt = $conn->prepare("UPDATE users SET user_code = ? WHERE id = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("si", $user_code, $user_id);
    $stmt->execute();

    if (sendVerificationEmail($email, $verificationCode)) {
        $success = "Akun berhasil didaftarkan. Silakan cek email untuk verifikasi.";
    } else {
        $error = "Gagal mengirim email verifikasi.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Percetakan Orieska</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            background-color: #343a40;
        }
        .bg-image {
            background-image: url('gambar/pexels-jplenio-1103970.jpg'); /* Ganti dengan jalur gambar lokal Anda */
            background-size: cover;
            background-position: center;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>  
</head>
<body>
    <div class="bg-image">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="form-container">
                        <h2 class="mb-4 text-center">Daftar</h2>
                        <form method="post" action="register.php">
                            <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
                            <?php if ($success) { echo "<script>showAlert('$success');</script>"; } ?>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" placeholder="Masukan Username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Masukan Email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Masukan Password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Daftar</button>
                        </form>
                        <div class="mt-3 text-center">
                            <p>Sudah punya akun? <a href="login.php">Klik login disini</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
