<?php
session_start();

// Koneksi ke database
include './includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form login
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa username dan password
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Regenerasi session ID
        session_regenerate_id(true);

        // Redirect berdasarkan role
        switch ($user['role']) {
            case 'admin':
                header("Location: admin/dashboard.php");
                break;
            case 'bagian_keuangan':
                header("Location: bagian_keuangan/dashboard.php");
                break;
            case 'bagian_produksi':
                header("Location: bagian_produksi/dashboard.php");
                break;
            case 'bagian_pengiriman':
                header("Location: bagian_pengiriman/dashboard.php");
                break;
            case 'individual':
                header("Location: consumer/catalog.php");
                break;
            case 'company':
                header("Location: consumer/company_catalog.php");
                break;
            default:
                header("Location: login.php");
        }
        exit;
    } else {
        // Jika login gagal
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Bagian Pengiriman</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body, html {
      height: 100%;
      background-color: #343a40;
    }
    .form-container {
      background: #818FB4;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }
  </style>
</head>
<body>
    <div class="d-flex align-items-center justify-content-center" style="height: 100%;">
    <div class="col-md-4">
      <div class="form-container">
        <h2 class="mb-4 text-center">Login Bagian Pengiriman</h2>
        <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
        <form method="post" action="login.php">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
