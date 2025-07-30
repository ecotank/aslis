
<?php
// Simulasi data laporan untuk ditampilkan (bisa diganti dengan koneksi database)
$laporan = [
    ["id" => 1, "judul" => "Phishing Email", "status" => "Diproses", "feedback" => "Segera kami tindaklanjuti."],
    ["id" => 2, "judul" => "Website Palsu", "status" => "Selesai", "feedback" => "Sudah kami laporkan ke pihak berwenang."],
];

// Simulasi pengiriman feedback
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["laporan_id"];
    $feedback = $_POST["feedback"];
    // Di sini harusnya simpan feedback ke database berdasarkan ID
    echo "<script>alert('Feedback untuk laporan #$id berhasil dikirim!');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Feedback Administrator</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container py-5">
    <h1 class="mb-4">Kelola Feedback Laporan</h1>

    <?php foreach ($laporan as $lapor): ?>
        <div class="card mb-4">
            <div class="card-header">
                <strong>Laporan #<?= $lapor['id'] ?>:</strong> <?= $lapor['judul'] ?>
            </div>
            <div class="card-body">
                <p>Status: <span class="badge bg-info text-dark"><?= $lapor['status'] ?></span></p>
                <form method="POST" class="mb-2">
                    <input type="hidden" name="laporan_id" value="<?= $lapor['id'] ?>">
                    <div class="mb-3">
                        <label for="feedback_<?= $lapor['id'] ?>" class="form-label">Feedback:</label>
                        <textarea name="feedback" id="feedback_<?= $lapor['id'] ?>" class="form-control" rows="3"><?= $lapor['feedback'] ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Feedback</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</body>
</html>
