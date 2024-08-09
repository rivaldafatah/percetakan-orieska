<?php
session_start();
include '../includes/db.php';

$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Periksa apakah username atau email sudah ada di database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $error = "Username atau email sudah digunakan.";
    } else {
        // Masukkan data pengguna baru ke database
        $stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, 'individual')");
        if ($stmt === false) {
            die("Error preparing statement: " . htmlspecialchars($conn->error));
        }
        $stmt->bind_param("sss", $username, $password, $email);
        $stmt->execute();
        $success = "Akun berhasil didaftarkan. Silakan login.";
        // header('Location: login.php');
        // exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <script>
        // Tampilkan pesan alert jika ada pesan sukses
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