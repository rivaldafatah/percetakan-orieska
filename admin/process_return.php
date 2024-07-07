<?php
session_start();
include '../includes/db.php';

// // Pastikan hanya admin yang dapat mengakses halaman ini
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

$return_id = $_GET['id'];

$stmt = $conn->prepare("UPDATE returns SET status = 'processed' WHERE id = ?");
if ($stmt === false) {
    die("Error preparing statement: " . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $return_id);
$stmt->execute();

header('Location: manage_returns.php');
exit();
?>
