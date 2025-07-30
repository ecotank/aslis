<?php
// Koneksi ke database langsung di dalam halaman
$host = 'localhost'; // atau IP server MySQL
$username = 'root'; // nama pengguna MySQL
$password = ''; // kata sandi MySQL
$dbname = 'aslis'; // nama database

// Membuat koneksi
$koneksi = new mysqli($host, $username, $password, $dbname);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Query untuk mengambil data laporan
$query = "
    SELECT laporan.laporan_id, pelapor.nama, laporan.deskripsi, laporan.tanggal_laporan
    FROM laporan
    JOIN pelapor ON laporan.pelapor_id = pelapor.pelapor_id
    ORDER BY laporan.tanggal_laporan DESC
";
$result = $koneksi->query($query);

if (!$result) {
    die("Query gagal: " . $koneksi->error);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Hasil Laporan - Keamanan Siber</title>
  <meta name="description" content="Menampilkan hasil laporan dari pelapor kejahatan siber." />
  <meta name="author" content="Aslis" />
  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="../assets/js/color-modes.js"></script>
  <style>
    .section-header {
      border-bottom: 2px solid #343a40;
      padding-bottom: 10px;
    }

    .card-custom {
      border: 2px solid #007bff;
      border-radius: 15px;
      margin-bottom: 20px;
    }

    .card-custom .card-body {
      padding: 20px;
    }

    .card-custom .card-title {
      font-size: 1.25rem;
      color: #343a40;
      font-weight: bold;
    }

    .feedback-text {
      font-size: 1rem;
      color: #495057;
      margin-top: 10px;
    }
  </style>
</head>

<body>

  <main>
    <div class="container py-4">
      <header class="pb-3 border-bottom">
        <a href="/" class="d-flex align-items-center text-body-emphasis text-decoration-none">
          <img src="assets/img/A.png" alt="Logo" width="28" height="32" class="mx-2">
          <span class="fs-4 ps-2 text-dark">Hasil Laporan Keamanan Siber</span>
        </a>
      </header>

      <nav class="navbar navbar-expand-lg bg-body-tertiary rounded" aria-label="Navbar Example">
        <div class="container-fluid">
          <form role="search" class="d-flex ms-auto">
            <input class="form-control" type="search" placeholder="Cari Laporan" aria-label="Search">
          </form>
        </div>
      </nav>

      <section>
        <h2 class="section-header">Daftar Laporan Kejahatan Siber</h2>

        <?php
        // Periksa apakah ada laporan dalam database
        if ($result->num_rows > 0) {
            // Loop melalui semua laporan dan tampilkan
            while ($laporan = $result->fetch_assoc()) {
                echo "
                <div class='card card-custom'>
                    <div class='card-body'>
                        <h5 class='card-title'>" . htmlspecialchars($laporan['nama']) . "</h5>
                        <p class='feedback-text'><strong>Deskripsi:</strong> " . htmlspecialchars($laporan['deskripsi']) . "</p>
                        <p class='feedback-text'><strong>Tanggal Laporan:</strong> " . $laporan['tanggal_laporan'] . "</p>
                    </div>
                </div>
                ";
            }
        } else {
            echo "<p>Tidak ada laporan yang tersedia.</p>";
        }
        ?>

      </section>

    </div>
  </main>

  <footer class="pt-3 mt-4 text-body-secondary border-top">
    &copy; Aslis
  </footer>

  <script src="assets/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

<?php
// Menutup koneksi
$koneksi->close();
?>
