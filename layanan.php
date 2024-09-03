<?php
session_start();
include 'includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Layanan Vendor - Percetakan Orieska</title>
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
    .interactive:hover {
        transform: scale(1.05);
        transition: transform 0.3s;
    }
    .interactive {
        transition: transform 0.3s;
    }
    .section {
        padding: 60px 0;
    }
    .section h2 {
        margin-bottom: 30px;
    }
    /* Tambahan untuk WhatsApp Floating Button */
    .whatsapp-float {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #25D366;
        color: white;
        border-radius: 50px;
        padding: 10px 15px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
        z-index: 1000;
    }
    .whatsapp-float i {
        font-size: 24px;
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
                            <a class="nav-link" href="consumer/company_login.php">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Layanan Vendor Section -->
    <section class="section bg-light" id="layananVendor">
        <div class="container">
            <h2 class="text-center" data-aos="fade-up">Layanan Vendor Percetakan</h2>
            <div class="row">
                <div class="col-md-4" data-aos="fade-right">
                    <div class="card interactive">
                        <div class="card-body">
                            <h5 class="card-title">Langkah 1: Hubungi Kontak Kami</h5>
                            <p class="card-text">Hubungi kami melalui kontak yang tertera dibawah untuk didaftarkan di sistem kami sebagai konsumen perusahaan.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up">
                    <div class="card interactive">
                        <div class="card-body">
                            <h5 class="card-title">Langkah 2: Tunggu Email Masuk</h5>
                            <p class="card-text">Notifikasi email akan masuk ke akun email konsumen untuk melakukan verifikasi dan login.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-left">
                    <div class="card interactive">
                        <div class="card-body">
                            <h5 class="card-title">Langkah 3: Login Akun Konsumen</h5>
                            <p class="card-text">Login menggunakan username dan password yang sudah didaftarkan yang ada pada email konsumen.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4" data-aos="fade-up">
                <a href="company_login.php" class="btn btn-primary">Login Sebagai Perusahaan</a>
            </div>
        </div>
    </section>

    <!-- Section 2: Informasi Kerja Sama -->
    <section class="section" id="informasiKerjaSama">
        <div class="container">
            <h2 class="text-center" data-aos="fade-up">Kerja Sama Kami</h2>
            <div class="row text-center">
                <div class="col-md-4" data-aos="fade-right">
                    <h3 class="display-4">10+</h3>
                    <p>Perusahaan telah bekerja sama dengan kami</p>
                </div>
                <div class="col-md-4" data-aos="fade-up">
                    <h3 class="display-4">1.500+</h3>
                    <p>Proyek berhasil diselesaikan</p>
                </div>
                <div class="col-md-4" data-aos="fade-left">
                    <h3 class="display-4">20+</h3>
                    <p>Tahun pengalaman dalam industri percetakan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Visi dan Misi -->
    <section class="section bg-light" id="visiMisi">
        <div class="container">
            <h2 class="text-center" data-aos="fade-up">Visi dan Misi Kami</h2>
            <div class="row">
                <div class="col-md-6" data-aos="fade-right">
                    <h3>Visi</h3>
                    <p>Menjadi perusahaan percetakan terdepan yang memberikan solusi kreatif dan inovatif bagi kebutuhan cetak pelanggan kami.</p>
                </div>
                <div class="col-md-6" data-aos="fade-left">
                    <h3>Misi</h3>
                    <ul>
                        <li>Menyediakan layanan percetakan berkualitas tinggi dengan teknologi terkini.</li>
                        <li>Mendukung perkembangan bisnis pelanggan dengan solusi cetak yang efisien dan efektif.</li>
                        <li>Membangun hubungan jangka panjang dengan pelanggan melalui pelayanan yang unggul dan kepercayaan.</li>
                    </ul>
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

    <!-- WhatsApp Floating Button -->
    <div class="whatsapp-float">
        <a href="https://wa.me/6287826936085" target="_blank">
            <i class="bi bi-whatsapp"></i>
        </a>
    </div>

    <!-- Bootstrap JS dan AOS JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init();  // Inisialisasi AOS untuk animasi
    </script>
</body>
</html>
