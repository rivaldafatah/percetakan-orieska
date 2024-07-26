<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'bagian_praproduksi') {
    header('Location: login.php');
    exit();
}

$stmt = $conn->prepare("SELECT COUNT(*) AS total_orders FROM orders");
$stmt->execute();
$result = $stmt->get_result();
$total_orders = $result->fetch_assoc()['total_orders'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total_productions FROM orders WHERE status = 'production'");
$stmt->execute();
$result = $stmt->get_result();
$total_productions = $result->fetch_assoc()['total_productions'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total_returns FROM returns WHERE status = 'pending'");
$stmt->execute();
$result = $stmt->get_result();
$total_returns = $result->fetch_assoc()['total_returns'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total_admin FROM users WHERE role = 'admin'");
$stmt->execute();
$result = $stmt->get_result();
$total_admin = $result->fetch_assoc()['total_admin'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total_company FROM users WHERE role = 'company'");
$stmt->execute();
$result = $stmt->get_result();
$total_company = $result->fetch_assoc()['total_company'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total_individual FROM users WHERE role = 'individual'");
$stmt->execute();
$result = $stmt->get_result();
$total_individual = $result->fetch_assoc()['total_individual'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total_products FROM products");
$stmt->execute();
$result = $stmt->get_result();
$total_products = $result->fetch_assoc()['total_products'];
?>

<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <title>Admin Orieska</title>
  <style>
    body {
      display: flex;
      min-height: 100vh;
      flex-direction: column;
    }
    .main-content {
      flex: 1;
      display: flex;
    }
    .navbar {
      position: sticky;
      top: 0;
      z-index: 1000; /* Pastikan navbar berada di atas elemen lain saat sticky */
    }
    .sidebar {
      width: 250px;
      background: #343a40;
      color: #fff;
      position: sticky;
      top: 56px; /* Tinggi navbar untuk menghindari tumpang tindih */
      height: calc(100vh - 56px);
      overflow-y: auto;
    }
    .sidebar .nav-link {
      color: #fff;
    }
    .sidebar .nav-link.active {
      background: #495057;
    }
    .sidebar .nav-link:hover {
      background: #495057;
    }
    .sidebar .dropdown-menu {
      background: #343a40;
      border: none;
    }
    .sidebar .dropdown-item {
      color: #fff;
    }
    .sidebar .dropdown-item:hover {
      background: #495057;
    }
    .content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            height: calc(100vh - 56px); /* Adjust the height to account for the navbar */
        }
    .card {
      min-height: 200px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #fff;
    }
    .card-header {
      font-size: 1.25rem;
      font-weight: 500;
    }
    .card-title {
      font-size: 2.5rem;
      font-weight: 700;
    }
    .card-text {
      font-size: 1rem;
      font-weight: 400;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Bagian Pra Produksi Dashboard</a>
            <div class="d-flex">
                <div class="navbar-text text-white me-3">
                    Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                </div>
                <a class="btn btn-outline-light" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>
    <div class="main-content">
        <div class="sidebar">
            <div class="p-3">
                <h4>Menu</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pesanan Konsumen
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="manage_orders.php">Konsumen Perorangan</a></li>
                            <li><a class="dropdown-item" href="manage_company_orders.php">Konsumen Perusahaan</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    <div class="content p-4">
      <h1>Hai <?= htmlspecialchars($_SESSION['username']); ?>, Selamat Datang di Bagian Pra Produksi Dashboard</h1>
      <p>Kelola Percetakan Disini Yaaaa</p>
      <div class="row">
      <div class="col-md-4">
          <div class="card bg-info mb-3">
            <div class="card-header">Total Admin</div>
            <div class="card-body">
              <h5 class="card-title"><?= $total_admin ?></h5>
              <p class="card-text">Jumlah admin yang terdaftar</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-success mb-3">
            <div class="card-header">Total Konsumen Perusahaan</div>
            <div class="card-body">
              <h5 class="card-title"><?= $total_company ?></h5>
              <p class="card-text">Jumlah Konsumen Perusahaan yang terdaftar</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-warning mb-3">
            <div class="card-header">Total Konsumen Perorangan</div>
            <div class="card-body">
              <h5 class="card-title"><?= $total_individual ?></h5>
              <p class="card-text">Jumlah Konsumen Perorangan yang terdaftar</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-dark mb-3">
            <div class="card-header">Total Produk</div>
            <div class="card-body">
              <h5 class="card-title"><?= $total_products ?></h5>
              <p class="card-text">Jumlah Produk yang ditambahkan</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-primary mb-3">
            <div class="card-header">Total Pesanan</div>
            <div class="card-body">
              <h5 class="card-title"><?= $total_orders ?></h5>
              <p class="card-text">Jumlah pesanan yang telah diterima.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-secondary mb-3">
            <div class="card-header">Total Produksi</div>
            <div class="card-body">
              <h5 class="card-title"><?= $total_productions ?></h5>
              <p class="card-text">Jumlah pesanan yang sedang dalam produksi.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card bg-danger mb-3">
            <div class="card-header">Total Pengembalian</div>
            <div class="card-body">
              <h5 class="card-title"><?= $total_returns ?></h5>
              <p class="card-text">Jumlah pengembalian yang belum diproses.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

</body>
</html>
