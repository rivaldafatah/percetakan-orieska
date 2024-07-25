<?php
session_start();
include '../includes/db.php';

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header('Location: login.php');
//     exit();
// }

$request_id = $_GET['request_id'];

$stmt = $conn->prepare("SELECT * FROM requests WHERE id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();

if (!$request) {
    die('Permintaan tidak ditemukan.');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cetak Permintaan Stok - Percetakan Orieska</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .request-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            color: #555;
        }
        .request-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }
        .request-box table td {
            padding: 5px;
            vertical-align: top;
        }
        .request-box table tr td:nth-child(2) {
            text-align: right;
        }
        .request-box table tr.top table td {
            padding-bottom: 20px;
        }
        .request-box table tr.information table td {
            padding-bottom: 40px;
        }
        .request-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        .request-box table tr.details td {
            padding-bottom: 20px;
        }
        .request-box table tr.item td {
            border-bottom: 1px solid #eee;
        }
        .request-box table tr.item.last td {
            border-bottom: none;
        }
        .request-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="request-box">
        <table>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <h2>Percetakan Orieska</h2>
                            </td>
                            <td>
                                Permintaan Stok #: <?= $request['id'] ?><br>
                                Tanggal: <?= $request['request_date'] ?>
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
                            <td>
                                Supplier<br>
                                [Nama Supplier]<br>
                                [Alamat Supplier]
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>Nama Bahan</td>
                <td>Jumlah</td>
            </tr>
            <tr class="details">
                <td><?= $request['material_name'] ?></td>
                <td><?= $request['quantity'] ?> <?= $request['unit'] ?></td>
            </tr>
        </table>
    </div>
    <script>
        window.print();
    </script>
</body>
</html>
