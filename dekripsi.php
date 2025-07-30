<?php
header('Content-Type: application/json');  

ini_set('display_errors', 1);  
error_reporting(E_ALL);  

$host = 'localhost';  
$dbname = 'aslis'; 
$username = 'root';  
$password = '';  

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Koneksi ke database gagal: " . $e->getMessage()]);
    exit();
}

$hasil_folder = realpath(dirname(__FILE__) . '/../hasil/');
if (!is_dir($hasil_folder)) {
    if (!mkdir('../hasil', 0777, true)) {
        echo json_encode(["success" => false, "message" => "Gagal membuat folder 'hasil'."]);
        exit();
    }
    $hasil_folder = realpath(dirname(__FILE__) . '/../hasil/');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file_to_decrypt']) && $_FILES['file_to_decrypt']['error'] == 0) {
        $file = $_FILES['file_to_decrypt']['tmp_name'];
        $fileName = basename($_FILES['file_to_decrypt']['name']);
        $key = $_POST['decryption_key']; 
        $outputFile = 'decrypted_' . $fileName;

        $encryptedDataWithIv = file_get_contents($file);
        $decodedData = base64_decode($encryptedDataWithIv);
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($decodedData, 0, $ivLength);
        $encryptedData = substr($decodedData, $ivLength);

        $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);
        file_put_contents($hasil_folder . '/' . $outputFile, $decryptedData);

        // Menyimpan riwayat ke database
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $stmt = $pdo->prepare("INSERT INTO file_history (action_type, input_file_name, output_file_name, user_ip, encryption_key) 
                               VALUES ('dekripsi', ?, ?, ?, ?)");
        $stmt->execute([$fileName, $outputFile, $user_ip, $key]);

        echo json_encode([
            "success" => true,
            "file_url" => "../hasil/" . $outputFile
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal upload file"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}
?>
