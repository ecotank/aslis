<?php
// Konfigurasi database
$host = "localhost";
$db   = "aslis";
$user = "root";
$pass = "";

// Koneksi database
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi ambil data dengan CURL
function getNewsData($url)
{
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'User-Agent: Aslis/1.0'  // Ganti MyAppName/1.0 sesuai nama aplikasi kamu
        ],
    ]);
    $response = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);

    if ($error) {
        return ["error" => $error];
    }
    return $response;
}


// Jika request untuk ambil berita (cek parameter misal 'action=news')
if (isset($_GET['action']) && $_GET['action'] === 'news') {
    header('Content-Type: application/json');

    $apiKey = "871020c9ab2e49cab5a239bf5ba3e09a";
    $endpoint = "https://newsapi.org/v2/everything";

    $query = "cybersecurity"; // atau kata kunci lain yang kamu mau

    $url = "$endpoint?q=$query&apiKey=$apiKey";

    $response = getNewsData($url);
    file_put_contents('debug_news.json', $response);
    if (is_array($response) && isset($response['error'])) {
        http_response_code(500);
        echo json_encode(["error" => "Gagal mengambil data berita: " . $response['error']]);
        exit;
    }

    $data = json_decode($response, true);
    if (!isset($data['status']) || $data['status'] !== 'ok') {
        http_response_code(500);
        echo json_encode(["error" => "NewsAPI Error: " . ($data['message'] ?? 'Unknown error')]);
        exit;
    }

    echo json_encode($data);
    exit;
}

// Fungsi upload file
function uploadFile($file)
{
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

    $filename = time() . "_" . basename($file["name"]);
    $targetFilePath = $targetDir . $filename;

    // Batasi tipe file
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi', 'pdf'];
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    if (!in_array($fileType, $allowedTypes)) {
        return false;
    }

    if (move_uploaded_file($file["tmp_name"], $targetFilePath)) {
        return $targetFilePath;
    }
    return false;
}

// Proses submit laporan hanya jika method POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data POST dan validasi sederhana
    $nama = $_POST['nama'] ?? null;
    $email = $_POST['email'] ?? null;
    $nomor_telepon = $_POST['nomor_telepon'] ?? null;
    $alamat = $_POST['alamat'] ?? null;
    $jenis_kejahatan = $_POST['jenis_kejahatan'] ?? null;
    $deskripsi = $_POST['deskripsi'] ?? null;
    $tanggal_kejadian = $_POST['tanggal_kejadian'] ?? null;

    if (!$nama || !$jenis_kejahatan) {
        die("Nama pelapor dan jenis kejahatan wajib diisi.");
    }

    // Simpan data pelapor
    $stmt = $conn->prepare("INSERT INTO Pelapor (nama, email, nomor_telepon, alamat) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $email, $nomor_telepon, $alamat);
    $stmt->execute();
    $pelapor_id = $stmt->insert_id;
    $stmt->close();

    // Simpan data laporan
    $stmt = $conn->prepare("INSERT INTO Laporan (pelapor_id, jenis_kejahatan, deskripsi, tanggal_kejadian, status_laporan, prioritas) VALUES (?, ?, ?, ?, 'baru', 'sedang')");
    $stmt->bind_param("isss", $pelapor_id, $jenis_kejahatan, $deskripsi, $tanggal_kejadian);
    $stmt->execute();
    $laporan_id = $stmt->insert_id;
    $stmt->close();

    // Simpan file bukti (bisa multiple)
    if (!empty($_FILES['bukti']['name'][0])) {
        foreach ($_FILES['bukti']['name'] as $key => $val) {
            $file = [
                'name' => $_FILES['bukti']['name'][$key],
                'type' => $_FILES['bukti']['type'][$key],
                'tmp_name' => $_FILES['bukti']['tmp_name'][$key],
                'error' => $_FILES['bukti']['error'][$key],
                'size' => $_FILES['bukti']['size'][$key]
            ];
            $uploadPath = uploadFile($file);
            if ($uploadPath !== false) {
                $stmt = $conn->prepare("INSERT INTO Attachments (laporan_id, nama_file, path_file, tipe_file, ukuran_file) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("isssi", $laporan_id, $file['name'], $uploadPath, $file['type'], $file['size']);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    echo "Laporan berhasil dikirim. Terima kasih!";
    exit;
}

// Kalau bukan POST dan bukan request news, bisa beri pesan atau redirect
http_response_code(400);
echo "Request tidak dikenali.";
exit;
