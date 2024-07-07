<?php
session_start();

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pesanan Berhasil - Percetakan Orieska</title>
</head>
<body>
    <h2>Pesanan Berhasil</h2>
    <p>Terima kasih, pesanan Anda telah berhasil diproses.</p>
    <a href="catalog.php">Lanjut Belanja</a>
</body>
</html>
