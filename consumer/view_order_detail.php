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

// Mengambil data pesanan dari database
$stmt = $conn->prepare("SELECT orders.*, users.username, users.email FROM orders 
                        JOIN users ON orders.user_id = users.id 
                        WHERE orders.id = ? AND orders.user_id = ?");
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Pesanan tidak ditemukan.");
}

// Mengambil detail pesanan dari database dengan nama produk
$stmt = $conn->prepare("SELECT order_items.*, products.name AS product_name FROM order_items 
                        JOIN products ON order_items.product_id = products.id 
                        WHERE order_items.order_id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order_items = $result->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['design_file'])) {
    $design_file = $_FILES['design_file'];

    // Validasi file desain
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'cdr', 'psd', 'rar'];
    $file_extension = strtolower(pathinfo($design_file['name'], PATHINFO_EXTENSION));
    $max_file_size = 20 * 1024 * 1024; // 20MB

    if (!in_array($file_extension, $allowed_extensions)) {
        $_SESSION['error'] = "Tipe file tidak didukung. Silakan unggah file dengan ekstensi: " . implode(", ", $allowed_extensions);
        header("Location: view_order_detail.php?id=$order_id");
        exit();
    }

    if ($design_file['size'] > $max_file_size) {
        $_SESSION['error'] = "Ukuran file maksimal adalah 20MB.";
        header("Location: view_order_detail.php?id=$order_id");
        exit();
    }

    // Unggah file desain ke folder uploads/designs
    $target_dir = "../uploads/designs/";
    $target_file = $target_dir . basename($design_file['name']);
    if (!move_uploaded_file($design_file['tmp_name'], $target_file)) {
        $_SESSION['error'] = "Terjadi kesalahan saat mengunggah file.";
        header("Location: view_order_detail.php?id=$order_id");
        exit();
    }

    // Memperbarui data pesanan dengan file desain baru
    $stmt = $conn->prepare("UPDATE order_items SET design_file = ? WHERE order_id = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("si", $design_file['name'], $order_id);
    $stmt->execute();

    $_SESSION['success'] = "Desain baru berhasil diunggah.";
    header("Location: view_order_detail.php?id=$order_id");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status_action'])) {
    $status_action = $_POST['status_action'];
    if ($status_action == 'approve') {
        // Update status to proofing approved
        $stmt = $conn->prepare("UPDATE orders SET status = 'proofing approved' WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();

        $_SESSION['success'] = "Desain proofing telah disetujui.";
        header("Location: view_order_detail.php?id=$order_id");
        exit();
    } elseif ($status_action == 'request_changes') {
        // Redirect to note input page for requesting changes
        header("Location: request_changes.php?id=$order_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Percetakan Orieska</title>
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
                        <a class="nav-link" aria-current="page" href="../index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../layanan.php">Layanan Vendor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="catalog.php">Katalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../about.php">Tentang</a>
                    </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php"><i class="bi bi-cart"></i> Keranjang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="order_history.php"><i class="material-icons"></i> Riwayat</a>
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
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center">Detail Pesanan</h2>
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                            <?php unset($_SESSION['success']); ?>
                        <?php endif; ?>
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                        <table class="table table-bordered">
                            <tr>
                                <th>ID Pesanan:</th>
                                <td><?= htmlspecialchars($order['id']) ?></td>
                            </tr>
                            <tr>
                                <th>Pengguna:</th>
                                <td><?= htmlspecialchars($order['username']) ?></td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td><?= htmlspecialchars($order['email']) ?></td>
                            </tr>
                            <tr>
                                <th>Alamat:</th>
                                <td><?= htmlspecialchars($order['address']) ?></td>
                            </tr>
                            <tr>
                                <th>Metode Pengiriman:</th>
                                <td><?= htmlspecialchars($order['shipping_method']) ?></td>
                            </tr>
                            <tr>
                                <th>Metode Pembayaran:</th>
                                <td><?= htmlspecialchars($order['payment_method']) ?></td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td>Rp <?= number_format($order['total'], 2, ',', '.') ?></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td><?= htmlspecialchars($order['status']) ?></td>
                            </tr>
                            <tr>
                                <th>Resi:</th>
                                <td><?= htmlspecialchars($order['tracking_number']); ?></td>
                            </tr>
                        </table>

                        <h3>Detail Produk</h3>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>File Desain</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                                        <td>Rp <?= number_format($item['price'], 2, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                                        <td>Rp <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?></td>
                                        <td>
                                            <?php if ($item['design_file']): ?>
                                                <a class="btn btn-primary btn-sm" href="../uploads/designs/<?= htmlspecialchars($item['design_file']) ?>" download>Unduh Desain</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <?php if ($order['status'] === 'proofing' && $order['proofing_file']): ?>
                            <h3>Desain Proofing</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>File Desain Proof</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td><a class="btn btn-primary btn-sm" href="../uploads/proofing/<?= htmlspecialchars($order['proofing_file']) ?>" download>Unduh Desain Proofing</a></td>
                                    <td>
                                        <form method="post" action="view_order_detail.php?id=<?= $order['id'] ?>">
                                            <input type="hidden" name="status_action" value="approve">
                                            <button type="submit" class="btn btn-success btn-sm">Terima Desain</button>
                                        </form>
                                        <form method="post" action="view_order_detail.php?id=<?= $order['id'] ?>">
                                            <input type="hidden" name="status_action" value="request_changes">
                                            <button type="submit" class="btn btn-danger btn-sm">Ajukan Perubahan</button>
                                        </form>
                                    </td>
                                </tbody>
                            </table>
                        <?php endif; ?>

                        <?php if ($order['payment_proof']): ?>
                            <a class="btn btn-warning" href="../uploads/payment_proofs/<?= htmlspecialchars($order['payment_proof']); ?>" target="_blank">Lihat Bukti Pembayaran</a>
                        <?php else: ?>
                            <p>Belum ada bukti pembayaran.</p>
                        <?php endif; ?>
                        <a class="btn btn-success" href="print_invoice.php?order_id=<?= $order['id'] ?>" target="_blank">Cetak Invoice</a>
                        <a class="btn btn-secondary" href="order_history.php">Kembali</a><br><br>

                        <?php if ($order['status'] === 'rejected'): ?>
                            <form method="post" action="view_order_detail.php?id=<?= $order['id'] ?>" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label class="form-label"><strong>Catatan Penolakan:</strong></label>
                                    <label class="form-label"><?= htmlspecialchars($order['rejection_notes']) ?></label><br>
                                    <label for="design_file" class="form-label">Unggah File Desain Baru (JPG, JPEG, PNG, PDF, CDR, PSD, RAR):</label>
                                    <input type="file" id="design_file" name="design_file" class="form-control" accept=".jpg, .jpeg, .png, .pdf, .cdr, .psd, .rar" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Unggah Desain Baru</button>
                            </form>
                        <?php endif; ?>
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
