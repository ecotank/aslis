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

// Fungsi upload file
function uploadFile($file) {
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

// Ambil data POST
$nama = $_POST['nama'];
$email = $_POST['email'] ?? null;
$nomor_telepon = $_POST['nomor_telepon'] ?? null;
$alamat = $_POST['alamat'] ?? null;  // Ada di tabel Pelapor, jangan lupa di form ditambah
$jenis_kejahatan = $_POST['jenis_kejahatan'];
$deskripsi = $_POST['deskripsi'];
$tanggal_kejadian = $_POST['tanggal_kejadian'] ?? null;

// Simpan data pelapor
$stmt = $conn->prepare("INSERT INTO Pelapor (nama, email, nomor_telepon, alamat) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nama, $email, $nomor_telepon, $alamat);
$stmt->execute();
$pelapor_id = $stmt->insert_id;
$stmt->close();

// Simpan data laporan, dengan status_laporan default 'baru' dan prioritas default 'sedang'
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

$conn->close();

echo "Laporan berhasil dikirim. Terima kasih!";
?>
