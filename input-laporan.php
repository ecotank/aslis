<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pelaporan Kejahatan Siber</title>
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@100..900&display=swap" rel="stylesheet">
    <script src="../assets/js/color-modes.js"></script>

    <style>
        body {
            background-color: #000000;
            color: #ffffff;
            font-family: "Geist", sans-serif;
            font-size: 12px;
        }

        .container {
            background-color: #000000;
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
    </style>
</head>

<body>
    <div class="container w-50 mt-4" id="formulir">
        <form action="../backend/submit_laporan.php" method="POST" enctype="multipart/form-data">
            <h2 class="text-center mb-4">Form Laporan</h2>
            <div class="row g-2">

                <div class="mb-3 col-6">
                    <label for="nama" class="form-label">Nama pelapor</label>
                    <input name="nama" type="text" class="form-control" id="nama" placeholder="Fulan bin Fulana">
                </div>

                <div class="mb-3 col-6">
                    <label for="email" class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" id="email" placeholder="fulana@mail.com">
                </div>

                <div class="mb-3 col-6">
                    <label for="jenisKejahatan" class="form-label">Jenis kejahatan</label>
                    <select name="jenis_kejahatan" class="form-control" id="jenisKejahatan">
                        <option value="" selected>Pilih jenis kejahatan</option>
                        <option value="hacking">Hacking</option>
                        <option value="phising">Phising</option>
                        <option value="malware">Malware</option>
                        <option value="ransomware">Ransomware</option>
                        <option value="identity theft">Identity Theft</option>
                        <option value="dos/ddos">DoS/DDoS</option>
                        <option value="cyberterrorism">Cyberterrorism</option>
                        <option value="data breach">Data Breach</option>
                        <option value="spyware">Spyware</option>
                        <option value="cyberbullying">Cyberbullying</option>
                        <option value="mitm">MITM</option>
                        <option value="sql injection">SQL Injection</option>
                        <option value="xss">XSS</option>
                        <option value="xss">Berita Hoax</option>
                        <option value="xss">Buzzer</option>
                        <option value="xss">Penipuan bentuk aplikasi</option>
                    </select>
                </div>

                <div class="mb-3 col-6">
                    <label for="nomor_telepon" class="form-label">Nomor telepon</label>
                    <input name="nomor_telepon" type="text" class="form-control" id="nomor_telepon"
                        placeholder="081244400123">
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Sumber</label>
                    <textarea name="alamat" class="form-control" id="alamat" rows="2"></textarea>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi kejadian</label>
                    <textarea name="deskripsi" class="form-control" id="deskripsi" rows="3"></textarea>
                </div>

                <div class="mb-3 col-6">
                    <label for="tanggal_kejadian" class="form-label">Tanggal kejadian</label>
                    <input name="tanggal_kejadian" type="date" class="form-control" id="tanggal_kejadian">
                </div>

                <div class="mb-3 col-6">
                    <label for="bukti" class="form-label">Bukti laporan</label>
                    <input name="bukti[]" type="file" class="form-control" id="bukti">
                </div>

                <button type="submit" class="btn btn-secondary" style="background-color: white; color: black">Kirim Laporan</button>

                <footer class="pt-3 mt-4 border-top">
                    &copy; Aslis
                </footer>

            </div>
        </form>
    </div>
</body>

</html>