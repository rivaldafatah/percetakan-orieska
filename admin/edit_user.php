<?php
session_start();
include '../includes/db.php';

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $role, $id);
    $stmt->execute();

    header('Location: manage_users.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pengguna - Admin - Percetakan Orieska</title>
</head>
<body>
    <h2>Edit Pengguna</h2>
    <form method="post" action="edit_user.php">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">
        <label>Username:</label>
        <input type="text" name="username" value="<?= $user['username'] ?>" required>
        <label>Email:</label>
        <input type="email" name="email" value="<?= $user['email'] ?>" required>
        <label>Role:</label>
        <select name="role">
            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
            <option value="individual" <?= $user['role'] == 'individual' ? 'selected' : '' ?>>Individual</option>
            <option value="company" <?= $user['role'] == 'company' ? 'selected' : '' ?>>Company</option>
        </select>
        <button type="submit">Update Pengguna</button>
    </form>
</body>
</html>
