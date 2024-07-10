<?php
session_start();
include '../includes/db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$order_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tracking_number = $_POST['tracking_number'];

    // Perbarui status pesanan menjadi dikirim
    $stmt = $conn->prepare("UPDATE orders SET status = 'shipped', tracking_number = ? WHERE id = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }

    $stmt->bind_param("si", $tracking_number, $order_id);
    if (!$stmt->execute()) {
        die("Error executing statement: " . htmlspecialchars($stmt->error));
    }

    echo "Order status updated successfully.";
    
    header("Location: manage_orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Input Resi - Admin - Percetakan Orieska</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Input Resi Pengiriman</h2>
        <form method="post" action="input_resi.php?id=<?= $order_id ?>">
            <div class="mb-3">
                <label for="tracking_number" class="form-label">Nomor Resi (Opsional):</label>
                <input type="text" id="tracking_number" name="tracking_number" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
