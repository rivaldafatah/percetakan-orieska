<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Insert user baru tanpa kode
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("Error preparing statement (INSERT): " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ssss", $username, $password, $email, $role);
    $stmt->execute();
    $user_id = $stmt->insert_id;

    // Generate kode user dengan format USR-00001
    $user_code = 'USR-' . str_pad($user_id, 5, '0', STR_PAD_LEFT);

    // Update user dengan kode user
    $stmt = $conn->prepare("UPDATE users SET user_code = ? WHERE id = ?");
    if ($stmt === false) {
        die("Error preparing statement (UPDATE): " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("si", $user_code, $user_id);
    $stmt->execute();

    header('Location: manage_users.php');
    exit();
}
?>