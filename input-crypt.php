<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pengenkripsi & Pendekripsi File</title>
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="../assets/js/color-modes.js"></script>

    <!-- Pastikan Bootstrap JS dan Popper JS dimuat -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <style>
        body {
            background-color: #000000;
            color: #ffffff;
            font-family: "Geist", sans-serif;
            font-size: 12px;
        }

        .container {
            background-color: #000000;
            height: 96vh;
            /* Membuat container memiliki tinggi penuh layar */
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* Menyusun elemen di tengah secara vertikal */
            align-items: center;
            /* Menyusun elemen di tengah secara horizontal */
        }


        .form-control,
        select,
        textarea {
            color: #ffffff;
            background-color: #1a1a1a;
            border: 1px solid #444;
        }

        .form-control::placeholder,
        select {
            color: #cccccc;
        }

        .form-control:focus {
            background-color: #1a1a1a;
            color: #ffffff;
            border-color: #888;
            box-shadow: none;
        }

        .btn-secondary {
            background-color: #444;
            border-color: #666;
            color: #fff;
        }

        .btn-secondary:hover {
            background-color: #666;
            border-color: #888;
        }

        footer {
            color: #888;
        }

        /* Styling modal */
        .modal-content {
            background-color: #1a1a1a;
            border-radius: 15px;
            border: 2px solid #444;
            animation: fadeIn 0.5s ease-in-out;
        }

        .modal-header {
            border-bottom: none;
            padding: 15px 30px;
        }

        .modal-title {
            font-size: 1.5rem;
            color: #fff;
        }

        .modal-body {
            font-size: 1.1rem;
            color: #ccc;
            text-align: center;
            padding: 20px;
        }

        .modal-footer {
            justify-content: center;
            padding: 10px;
        }

        .modal-footer .btn {
            width: 150px;
            background-color: #0056b3;
            color: #fff;
            font-weight: bold;
        }

        .modal-footer .btn:hover {
            background-color: #004085;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }

        /* Custom button styles */
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>

<body>
    <div class="container w-50 mt-4" id="file-encryption-decryption">
        <h2 class="text-center mb-4">Pengenkripsi & Pendekripsi File</h2>

        <!-- Form Enkripsi File -->
        <form action="../backend/enkripsi.php" method="POST" enctype="multipart/form-data" id="form-enkripsi">
            <div class="row g-2">
                <div class="mb-3 col-12">
                    <label for="file_to_encrypt" class="form-label">Pilih File untuk Enkripsi</label>
                    <input name="file_to_encrypt" type="file" class="form-control" id="file_to_encrypt" required />
                </div>
                <div class="mb-3 col-12">
                    <label for="encryption_key" class="form-label">Masukkan Kunci Enkripsi</label>
                    <input name="encryption_key" type="password" class="form-control" id="encryption_key" placeholder="Kunci Enkripsi" required />
                </div>
                <button type="submit" class="btn btn-secondary w-100">Enkripsi File</button>
            </div>
        </form>

        <hr class="my-4" />

        <!-- Form Dekripsi File -->
        <form action="../backend/dekripsi.php" method="POST" enctype="multipart/form-data" id="form-dekripsi">
            <div class="row g-2">
                <div class="mb-3 col-12">
                    <label for="file_to_decrypt" class="form-label">Pilih File untuk Dekripsi</label>
                    <input name="file_to_decrypt" type="file" class="form-control" id="file_to_decrypt" required />
                </div>
                <div class="mb-3 col-12">
                    <label for="decryption_key" class="form-label">Masukkan Kunci Dekripsi</label>
                    <input name="decryption_key" type="password" class="form-control" id="decryption_key" placeholder="Kunci Dekripsi" required />
                </div>
                <button type="submit" class="btn btn-secondary w-100">Dekripsi File</button>
            </div>
        </form>

        <footer class="pt-3 mt-4 border-top">
            &copy; Aslis
        </footer>
    </div>

    <!-- Modal untuk Enkripsi/Dekripsi -->
    <div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultModalLabel">Proses Berhasil!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    File berhasil diproses.<br>Anda dapat mendownload file tersebut di bawah ini:
                    <br><br>
                    <a id="downloadLink" href="#" class="btn btn-primary" download>Download File</a>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Penanganan form enkripsi menggunakan AJAX
        document.getElementById('form-enkripsi').onsubmit = async function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });
            var result = await response.json();
            if (result.success) {
                // Menampilkan modal dengan link download setelah berhasil enkripsi
                document.getElementById('downloadLink').setAttribute('href', result.file_url);
                var myModal = new bootstrap.Modal(document.getElementById('resultModal'));
                myModal.show();
            } else {
                alert(result.message || "Terjadi kesalahan saat enkripsi.");
            }
        };

        // Penanganan form dekripsi menggunakan AJAX
        document.getElementById('form-dekripsi').onsubmit = async function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });
            var result = await response.json();
            if (result.success) {
                // Menampilkan modal dengan link download setelah berhasil dekripsi
                document.getElementById('downloadLink').setAttribute('href', result.file_url);
                var myModal = new bootstrap.Modal(document.getElementById('resultModal'));
                myModal.show();
            } else {
                alert(result.message || "Terjadi kesalahan saat dekripsi.");
            }
        };
    </script>
</body>

</html>