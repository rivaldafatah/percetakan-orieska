<?php
session_start();
include '../includes/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Mengambil ID pesanan dari URL
$order_id = $_GET['order_id'];

// Mengambil data pesanan dari database
$stmt = $conn->prepare("SELECT orders.*, users.username, users.email FROM orders 
                        JOIN users ON orders.user_id = users.id 
                        WHERE orders.id = ?");
$stmt->bind_param("i", $order_id);
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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Pesanan - Percetakan Orieska</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }
        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }
        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }
        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }
        .invoice-box table tr.item.last td {
            border-bottom: none;
        }
        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
        .badge {
            font-size: 14px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <h2>Percetakan Orieska</h2>
                            </td>
                            <td>
                                Faktur #: <?= htmlspecialchars($order['id']) ?><br>
                                Dibuat: <?= date('d-m-Y', strtotime($order['created_at'])) ?><br>
                                Status: 
                                <?php if ($order['status'] === 'pending'): ?>
                                    <span class="badge bg-warning">Pending</span>
                                <?php elseif ($order['status'] === 'approved'): ?>
                                    <span class="badge bg-secondary">Order Disetujui</span>
                                <?php elseif ($order['status'] === 'proofing'): ?>
                                    <span class="badge bg-dark">Desain Di Proofing</span>
                                <?php elseif ($order['status'] === 'production'): ?>
                                    <span class="badge bg-primary">Dalam Produksi</span>
                                <?php elseif ($order['status'] === 'shipped'): ?>
                                    <span class="badge bg-info">Dikirim</span>
                                <?php elseif ($order['status'] === 'completed'): ?>
                                    <span class="badge bg-success">Selesai</span>
                                <?php elseif ($order['status'] === 'return_pending'): ?>
                                    <span class="badge bg-warning text-dark">Retur Pending</span>
                                <?php elseif ($order['status'] === 'return_rejected'): ?>
                                    <span class="badge bg-danger">Retur Ditolak</span>
                                <?php elseif ($order['status'] === 'return_approved'): ?>
                                    <span class="badge bg-primary">Retur Diterima</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                Percetakan Orieska<br>
                                Jalan Cilisung Kp. Coblong Rt 3/ Rw 14 <br> 
                                Ds. Sukamenak, Kec. Margahayu, <br>
                                Kab. Bandung
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                Alamat Konsumen:<br>
                                <?= htmlspecialchars($order['address']) ?><br>
                                <?= htmlspecialchars($order['shipping_method']) ?>
                            </td>
                            <td>
                                Pengguna:<br>
                                <?= htmlspecialchars($order['username']) ?><br>
                                <?= htmlspecialchars($order['email']) ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>Metode Pembayaran</td>
                <td>Jumlah</td>
            </tr>
            <tr class="details">
                <td><?= htmlspecialchars($order['payment_method']) ?></td>
                <td>Rp <?= number_format($order['total'], 2, ',', '.') ?></td>
            </tr>
            <tr class="heading">
                <td>Nama Produk</td>
                <td>Harga</td>
            </tr>
            <?php foreach ($order_items as $item): ?>
            <tr class="item">
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td>Rp <?= number_format($item['price'], 2, ',', '.') ?> x <?= htmlspecialchars($item['quantity']) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td></td>
                <td>Total: Rp <?= number_format($order['total'], 2, ',', '.') ?></td>
            </tr>
        </table>
        <?php if ($order['payment_proof']): ?>
            <p><strong>Bukti Pembayaran:</strong> <a class="btn btn-warning btn-sm" href="../uploads/payment_proofs/<?= htmlspecialchars($order['payment_proof']); ?>" target="_blank">Lihat Bukti Pembayaran</a></p>
        <?php else: ?>
            <p><strong>Bukti Pembayaran:</strong> Belum ada bukti pembayaran.</p>
        <?php endif; ?>
        <p><a class="btn btn-secondary" href="company_order_history.php">Kembali</a></p>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script>
        window.print();
    </script>
</body>
</html>
