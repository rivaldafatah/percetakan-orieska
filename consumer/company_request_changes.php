<?php
session_start();
include '../includes/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Mengambil ID pesanan dari URL dan memastikannya ada
if (!isset($_GET['id'])) {
    die("ID pesanan tidak ditemukan.");
}

$order_id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_notes'])) {
    $change_notes = $_POST['change_notes'];

    // Memperbarui status dan catatan perubahan di database
    $stmt = $conn->prepare("UPDATE orders SET status = 'proofing pending', rejection_notes = ? WHERE id = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("si", $change_notes, $order_id);
    $stmt->execute();

    $_SESSION['success'] = "Permintaan perubahan telah diajukan.";
    header("Location: view_order_detail.php?id=$order_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Perubahan - Percetakan Orieska</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center">Ajukan Perubahan Desain</h2>
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                        <form method="post" action="request_changes.php?id=<?= $order_id ?>">
                            <div class="mb-3">
                                <label for="change_notes" class="form-label">Catatan Perubahan:</label>
                                <textarea id="change_notes" name="change_notes" class="form-control" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Kirim Permintaan Perubahan</button>
                        </form>
                        <a class="btn btn-secondary mt-3" href="view_company_order_detail.php?id=<?= $order_id ?>">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
