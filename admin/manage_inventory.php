<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: login.php');
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard Admin - Percetakan Orieska</title>
<style>
body {
  margin: 0;
}

ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
  width: 20%;
  background-color: #808080;
  position: fixed;
  height: 100%;
  overflow: auto;
}

li a {
  display: block;
  color: white;
  padding: 8px 16px;
  text-decoration: none;
}

li a.active {
  background-color: #20B2AA;
  color: white;
}

li a:hover:not(.active) {
  background-color: #555;
  color: white;
}
</style>
</head>
<body>
<ul>
  <li><a class="active" href="#home">Admin Orieska</a></li>
  <li><a href="dashboard.php">Dashboard</a></li>
  <li><a href="manage_products.php">Produk</a></li>
  <li><a href="manage_inventory.php">stok bahan</a></li>
  <li><a href="request_stock.php">Permintaan Bahan Baku</a></li>
  <li><a href="manage_requests.php">Cetak Permintaan Bahan Baku</a></li>
  <li><a href="input_expense.php">Tambah Pengeluaran</a></li>
  <li><a href="manage_expenses.php">Laporan Pengeluaran</a></li>
  <li><a href="manage_orders.php">Pesanan Konsumen Perorangan</a></li>
  <li><a href="manage_company_orders.php">Pesanan Konsumen Perusahaan</a></li>
  <li><a href="manage_returns.php">Kelola Pengembalian</a></li>
  <li><a href="company_register.php">Daftarkan Akun Perusahaan</a></li>
  <li><a href="manage_company_accounts.php">Kelola Akun Perusahaan</a></li>
  <li><a href="manage_accounts.php">Kelola Akun Perorangan</a></li>
  <li><a href="manage_users.php">Kelola Semua Akun</a></li>
</ul>

<div style="margin-left:25%;padding:1px 16px;height:1000px;">
  <h2>Pengelolaan Stok</h2>

</div>

</body>
</html>