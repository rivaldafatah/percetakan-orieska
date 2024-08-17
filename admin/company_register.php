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
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, role, verification_code) VALUES (?, ?, ?, 'company', ?)");
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
        $success = "Akun perusahaan berhasil dibuat. Silakan hubungi pihak perusahaan untuk verifikasi.";
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
    <title>Pendaftaran Akun Perusahaan - Admin - Percetakan Orieska</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        .main-content {
            display: flex;
            height: 100%;
        }
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .sidebar {
            width: 250px;
            position: sticky;
            top: 0;
            background: #343a40;
            color: #fff;
            height: 100vh;
            overflow-y: auto;
            padding: 0;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .sidebar .nav-link.active {
            background: #495057;
        }
        .sidebar .nav-link:hover {
            background: #495057;
        }
        .sidebar .dropdown-menu {
            background: #343a40;
            border: none;
        }
        .sidebar .dropdown-item {
            color: #fff;
        }
        .sidebar .dropdown-item:hover {
            background: #495057;
        }
        .content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            height: calc(100vh - 56px); /* Adjust the height to account for the navbar */
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <div class="d-flex">
                <div class="navbar-text text-white me-3">
                    Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                </div>
                <a class="btn btn-outline-light" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>
    <div class="main-content">
        <div class="sidebar">
            <div class="p-3">
                <h4>Menu</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Produk
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="manage_products.php">List Produk</a></li>
                            <li><a class="dropdown-item" href="add_product.php">Tambah Produk</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Manajemen Stok
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="manage_inventory.php">Stok Bahan</a></li>
                            <li><a class="dropdown-item" href="add_inventory.php">Tambah Bahan Baku</a></li>
                            <li><a class="dropdown-item" href="request_stock.php">Permintaan Bahan Baku</a></li>
                            <li><a class="dropdown-item" href="manage_requests.php">Cetak</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pengeluaran
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="input_expense.php">Tambah Pengeluaran</a></li>
                            <li><a class="dropdown-item" href="manage_expenses.php">Laporan Pengeluaran</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pesanan Konsumen
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="manage_orders.php">Konsumen Perorangan</a></li>
                            <li><a class="dropdown-item" href="manage_company_orders.php">Konsumen Perusahaan</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_returns.php">Pengembalian</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="company_register.php">Daftar Akun Perusahaan</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Kelola Akun Konsumen
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="manage_accounts.php">Akun Perorangan</a></li>
                            <li><a class="dropdown-item" href="manage_company_accounts.php">Akun Perusahaan</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_users.php">Kelola Semua Akun</a>
                    </li>
                </ul>
            </div>
        </div>
    <div class="content">
        <h2>Pendaftaran Akun Perusahaan</h2>
        <?php if (isset($success)) { echo "<p>$success</p>"; } ?>
        <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
        <div class="container">
            <form method="post" action="company_register.php">
                <div class="mb-3">
                    <label class="form-label">Username:</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password:</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary">Daftar</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
