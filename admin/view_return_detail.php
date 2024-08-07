<?php
session_start();
include '../includes/db.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$order_id = $_GET['order_id'];

// Mengambil detail pengiriman pengembalian dari tabel return_shipments dan status dari tabel orders
$stmt = $conn->prepare("SELECT rs.*, o.status AS order_status FROM return_shipments rs JOIN orders o ON rs.order_id = o.id WHERE rs.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$return_shipment = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = 'returned';

    // Memperbarui status pengembalian menjadi 'returned' di tabel returns
    $stmt = $conn->prepare("UPDATE returns SET status = ? WHERE order_id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("si", $status, $order_id);
    if (!$stmt->execute()) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();

    // Memperbarui status pengembalian di tabel orders
    $stmt = $conn->prepare("UPDATE orders SET status = 'returned' WHERE id = ?");
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("i", $order_id);
    if (!$stmt->execute()) {
        die('Execute failed: ' . htmlspecialchars($stmt->error));
    }
    $stmt->close();

    header('Location: manage_returns.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengembalian - Admin - Percetakan Orieska</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Percetakan Orieska</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Layanan Vendor</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="katalogDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Katalog
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="katalogDropdown">
                            <li><a class="dropdown-item" href="#">Banner</a></li>
                            <li><a class="dropdown-item" href="#">Buku</a></li>
                            <li><a class="dropdown-item" href="#">Plakat</a></li>
                            <li><a class="dropdown-item" href="#">Stiker</a></li>
                            <li><a class="dropdown-item" href="#">Kartu Nama</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Tentang</a>
                    </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php"><i class="bi bi-cart"></i> Keranjang</a>
                    </li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">Detail Pengembalian</h2>
        <?php if ($return_shipment): ?>
            <table class="table table-bordered">
                <tr>
                    <th>ID Pengembalian</th>
                    <td><?= htmlspecialchars($return_shipment['id']); ?></td>
                </tr>
                <tr>
                    <th>Order ID</th>
                    <td><?= htmlspecialchars($return_shipment['order_id']); ?></td>
                </tr>
                <tr>
                    <th>Nama Pengirim</th>
                    <td><?= htmlspecialchars($return_shipment['sender_name']); ?></td>
                </tr>
                <tr>
                    <th>Alamat Pengirim</th>
                    <td><?= htmlspecialchars($return_shipment['sender_address']); ?></td>
                </tr>
                <tr>
                    <th>Ekspedisi</th>
                    <td><?= htmlspecialchars($return_shipment['expedition']); ?></td>
                </tr>
                <tr>
                    <th>Status Pengembalian</th>
                    <td><?= htmlspecialchars($return_shipment['order_status']); ?></td>
                </tr>
            </table>
            <?php if ($return_shipment['order_status'] === 'being_returned'): ?>
                <form method="post" action="view_return_detail.php?order_id=<?= $order_id; ?>">
                    <input type="hidden" name="return_id" value="<?= $return_shipment['id']; ?>">
                    <button type="submit" class="btn btn-success">Tandai Sebagai Diterima</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-center">Tidak ada detail pengembalian yang ditemukan.</p>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS dan dependensi -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
