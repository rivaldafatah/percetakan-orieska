<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $deskripsi = $_POST['deskripsi'];

    $stmt = $conn->prepare("INSERT INTO pertanyaan (nama, email, deskripsi) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nama, $email, $deskripsi);
    $stmt->execute();

    // Set success message in session
    $_SESSION['success'] = "Pertanyaan Anda telah dikirim.";
    header('Location: index.php'); // Redirect to prevent form resubmission
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tentang Kami - Percetakan Orieska</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- AOS CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    body {
        overflow-x: hidden;
    }
    .about-section {
        padding: 60px 0;
    }
  </style>
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
                        <a class="nav-link" aria-current="page" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="layanan.php">Layanan Vendor</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="katalogDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Katalog
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="katalogDropdown">
                            <li><a class="dropdown-item" href="consumer/catalog.php">Semua Produk</a></li>
                            <li><a class="dropdown-item" href="consumer/banner.php">Banner</a></li>
                            <li><a class="dropdown-item" href="consumer/stiker.php">Stiker</a></li>
                            <li><a class="dropdown-item" href="consumer/dus_kemasan.php">Dus Kemasan</a></li>
                            <li><a class="dropdown-item" href="consumer/undangan.php">Undangan</a></li> 
                            <li><a class="dropdown-item" href="consumer/kartu_nama.php">Kartu Nama</a></li>
                            <li><a class="dropdown-item" href="consumer/buku.php">Buku</a></li>
                            <li><a class="dropdown-item" href="consumer/brosur.php">Brosur</a></li>
                            <li><a class="dropdown-item" href="consumer/map.php">Map</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">Tentang</a>
                    </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="consumer/cart.php"><i class="bi bi-cart"></i> Keranjang</a>
                    </li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?= htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="consumer/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="consumer/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="consumer/register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

        <!-- About Section 1 -->
        <section class="about-section bg-light">
            <div class="container">
                <div class="row">
                    <!-- Teks di Kiri, Gambar di Kanan -->
                    <div class="col-md-6" data-aos="fade-right">
                        <h2 class="mb-4">Tentang Percetakan Orieska</h2>
                        <p style="font-size: 1.2rem;">
                            Percetakan Orieska adalah perusahaan yang bergerak di bidang percetakan dan layanan desain grafis. Kami telah beroperasi selama lebih dari 10 tahun, memberikan solusi percetakan berkualitas tinggi untuk berbagai kebutuhan, mulai dari banner, buku, plakat, hingga stiker dan kartu nama.
                        </p>
                        <p style="font-size: 1.2rem;">
                            Kami berkomitmen untuk memberikan layanan terbaik kepada pelanggan kami, dengan fokus pada kualitas, ketepatan waktu, dan harga yang kompetitif. Dengan dukungan tim profesional dan teknologi mutakhir, kami siap menjadi mitra terbaik untuk memenuhi semua kebutuhan percetakan Anda.
                        </p>
                    </div>
                    <div class="col-md-6" data-aos="fade-left">
                        <img src="gambar/Kartu-Nama.png" class="img-fluid rounded" alt="Tentang Kami">
                    </div>
                </div>
            </div>
        </section>

    <!-- About Section 2 -->
    <section class="about-section bg-light">
        <div class="container">
            <div class="row">
                <!-- Gambar di Kiri, Teks di Kanan -->
                <div class="col-md-6" data-aos="fade-right">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a7/Large_format_digital_printer.jpg/1200px-Large_format_digital_printer.jpg" class="img-fluid rounded" alt="Tentang Kami">
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <h2 class="mb-4">Layanan Kami</h2>
                    <p style="font-size: 1.2rem;">
                        Selain percetakan, kami juga menyediakan layanan desain grafis profesional yang meliputi pembuatan logo, brosur, katalog, dan materi promosi lainnya. Dengan tenaga ahli berpengalaman dan teknologi terkini, kami mampu menghadirkan desain yang kreatif dan inovatif sesuai dengan kebutuhan bisnis Anda.
                    </p>
                    <p style="font-size: 1.2rem;">
                        Kami juga menjalin kemitraan dengan berbagai vendor untuk memastikan bahwa setiap produk yang kami hasilkan memiliki kualitas terbaik. Layanan kami mencakup seluruh proses, mulai dari desain, cetak, hingga finishing dan pengiriman.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4" data-aos="fade-up">
        <div class="container">
            <div class="row">
                <!-- Informasi Percetakan Orieska -->
                <div class="col-md-4" data-aos="fade-right">
                    <h5>Tentang Kami</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">Tentang Kami</a></li>
                        <li><a href="#" class="text-white">Mitra</a></li>
                        <li><a href="#" class="text-white">Portfolio</a></li>
                    </ul>
                </div>
                <!-- Informasi Kontak -->
                <div class="col-md-4" data-aos="fade-up">
                    <h5>Informasi Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-whatsapp"></i> WhatsApp: +62 812-3456-7890</li>
                        <li><i class="bi bi-envelope"></i> Email: info@orieska.com</li>
                        <li><i class="bi bi-geo-alt"></i> Alamat: Jl. Cilisung, Kp Coblong Rt 03 / Rw 14, Kabupaten Bandung</li>
                    </ul>
                </div>
                <!-- Bank Pembayaran -->
                <div class="col-md-4" data-aos="fade-left">
                    <h5>Bank Pembayaran</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-bank"></i> BCA: 123-456-7890</li>
                        <li><i class="bi bi-bank"></i> BRI: 098-765-4321</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <!-- AOS JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
      AOS.init();
    </script>
</body>
</html>
