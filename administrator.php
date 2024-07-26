<!DOCTYPE html>
<html lang="id">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Portal Administrator - Percetakan Orieska</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    body {
      background-color: #f8f9fa;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: 'Arial', sans-serif;
    }
    .container {
      text-align: center;
      max-width: 600px;
      padding: 30px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .btn {
      margin: 10px 0;
      width: 100%;
      font-size: 16px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1 class="mb-4">Administrator Percetakan Orieska</h1>
    <p class="mb-4">Selamat datang di portal administrator Percetakan Orieska. Silakan pilih login sesuai dengan peran Anda:</p>
    <div class="d-grid gap-2">
      <button class="btn btn-primary" onclick="location.href='admin/login.php'">Login sebagai Admin</button>
      <button class="btn btn-secondary" onclick="location.href='pemilik/login.php'">Login sebagai Pemilik</button>
      <button class="btn btn-dark" onclick="location.href='bagian_praproduksi/login.php'">Login sebagai Bagian Pra Produksi</button>
      <button class="btn btn-success" onclick="location.href='bagian_keuangan/login.php'">Login sebagai Bagian Keuangan</button>
      <button class="btn btn-warning" onclick="location.href='bagian_produksi/login.php'">Login sebagai Bagian Produksi</button>
      <button class="btn btn-info" onclick="location.href='bagian_pengiriman/login.php'">Login sebagai Bagian Pengiriman</button>
    </div>
  </div>
  
  <!-- Bootstrap JS and dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
