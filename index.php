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
  <title>Percetakan Orieska</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- AOS CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    body {
        overflow-x: hidden; /* Tambahkan ini untuk menghilangkan overflow horizontal */
    }
    /* Perkecil ukuran carousel */
    #carouselSection .carousel-inner img {
        height: 600px; /* Ubah sesuai kebutuhan */
        object-fit: cover;
    }
    /* Animasi interaktif untuk section 2 dan 3 */
    .interactive:hover {
        transform: scale(1.05);
        transition: transform 0.3s;
    }
    .interactive {
        transition: transform 0.3s;
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
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Section 1: Carousel -->
    <section id="carouselSection" class="my-4" data-aos="fade-up">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="gambar/BANNER-1.png" class="d-block w-100" alt="Slide 1">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Pelayanan</h5>
                        <p>Menawarkan Pelayanan Terbaik Untuk Anda.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="gambar/BANNER-2.jpg" class="d-block w-100" alt="Slide 2">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Kualitas</h5>
                        <p>Memastikan Detail Dari Desain Anda Tercetak Dengan Sempurna.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="gambar/BANNER-3.jpg" class="d-block w-100" alt="Slide 3">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Kapabilitas Besar</h5>
                        <p>Menghasilkan Cetakan Dengan Warna Yang Hidup Dan Detail Yang Tajam.</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Section 2: Kenapa Harus Percetakan Orieska -->
    <section id="kenapaOrieska" class="py-4 bg-light" data-aos="fade-right">
        <div class="container">
            <h2 class="text-center mb-4">Kenapa Harus Percetakan Orieska?</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-center interactive" data-aos="fade-up">
                        <div class="card-body">
                            <h5 class="card-title">Kualitas Terbaik</h5>
                            <p class="card-text">Kami menggunakan bahan dan teknologi terbaik untuk menghasilkan cetakan berkualitas tinggi.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center interactive" data-aos="fade-up" data-aos-delay="100">
                        <div class="card-body">
                            <h5 class="card-title">Harga Terjangkau</h5>
                            <p class="card-text">Kami menawarkan harga yang kompetitif tanpa mengorbankan kualitas.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center interactive" data-aos="fade-up" data-aos-delay="200">
                        <div class="card-body">
                            <h5 class="card-title">Pelayanan Cepat</h5>
                            <p class="card-text">Tim kami siap melayani Anda dengan cepat dan profesional.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 3: Informasi Layanan Vendor -->
    <section id="layananVendor" class="py-4" data-aos="fade-left">
        <div class="container">
            <h2 class="text-center mb-4">Informasi Layanan Vendor</h2>
            <div class="row">
                <div class="col-md-12">
                    <p class="text-center">Percetakan Orieska menyediakan berbagai layanan cetak yang dapat disesuaikan dengan kebutuhan Anda, seperti cetak banner, buku, plakat, stiker, dan kartu nama. Kami berkomitmen untuk memberikan hasil cetak terbaik guna memenuhi kebutuhan Anda. Dengan teknologi canggih dan tim profesional, kami siap membantu Anda mendapatkan hasil cetak berkualitas tinggi.</p>
                    <div class="text-center">
                        <a href="#" class="btn btn-primary interactive" data-aos="fade-up">Layanan Vendor</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 4: Galeri Foto -->
    <section id="galeriFoto" class="py-4" data-aos="zoom-in">
        <div class="container">
            <h2 class="text-center mb-4">Galeri Foto</h2>
            <div class="row row-cols-1 row-cols-md-3 g-3">
                <div class="col">
                    <div class="card h-100" data-aos="fade-up">
                        <img src="gambar/gambar1.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Produksi Map</h5>
                            <p class="card-text">Ini adalah produksi map percetakan.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100" data-aos="fade-up" data-aos-delay="100">
                        <img src="gambar/gambar2.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Produksi Amplop</h5>
                            <p class="card-text">Ini adalah produksi amplop percetakan</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100" data-aos="fade-up" data-aos-delay="200">
                        <img src="gambar/gambar3.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Produksi Formulir</h5>
                            <p class="card-text">Ini adalah Produksi Formulir Percetakan.</p>
                        </div>
                    </div>
                </div>
                <!-- Tambahkan lebih banyak kolom card sesuai kebutuhan -->
            </div>
        </div>
    </section>

    <!-- Section 5: Artikel atau Blog Terbaru -->
    <section id="artikelTerbaru" class="py-4 bg-light" data-aos="fade-up">
        <div class="container">
            <h2 class="text-center mb-4">Artikel atau Blog Terbaru</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card h-100" data-aos="fade-up">
                        <img src="gambar/gambar1.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Artikel Produksi Map</h5>
                            <p class="card-text">Deskripsi singkat tentang artikel atau cuplikan.</p>
                            <a href="#" class="btn btn-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100" data-aos="fade-up" data-aos-delay="100">
                        <img src="gambar/gambar2.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Artikel Produksi Amplop</h5>
                            <p class="card-text">Deskripsi singkat tentang artikel atau cuplikan.</p>
                            <a href="#" class="btn btn-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100" data-aos="fade-up" data-aos-delay="200">
                        <img src="gambar/gambar3.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Artikel Produksi Formulir</h5>
                            <p class="card-text">Deskripsi singkat tentang artikel atau cuplikan.</p>
                            <a href="#" class="btn btn-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <!-- Tambahkan lebih banyak kolom card sesuai kebutuhan -->
            </div>
        </div>
    </section>

    <!-- Section 6: Formulir Pertanyaan -->
    <section id="formPertanyaan" class="py-4 bg-light" data-aos="fade-up">
        <div class="container">
            <h2 class="text-center mb-4">Ajukan Pertanyaan</h2>
            <?php
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success" role="alert">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']); // Menghapus pesan setelah ditampilkan
            }
            ?>
            <form action="index.php" method="post">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi Pertanyaan</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
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
