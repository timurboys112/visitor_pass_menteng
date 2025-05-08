<?php
// filepath: c:\xampp\htdocs\visitor_pass_menteng\check_out.php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit();
}

$username = $_SESSION['username'];

// Menghubungkan ke database
require_once 'db_connection.php';

// Proses check-out tamu
if (isset($_POST['check_out'])) {
    $qr_code = $_POST['qr_code'];

    // Cari tamu berdasarkan QR code
    $sql = "SELECT * FROM guests WHERE qr_code = ? AND check_out_time IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $qr_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $guest = $result->fetch_assoc();

    if ($guest) {
        // Update waktu check-out
        $sql_update = "UPDATE guests SET check_out_time = NOW() WHERE qr_code = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("s", $qr_code);

        if ($stmt_update->execute()) {
            $success_message = "Check-out berhasil untuk tamu: " . htmlspecialchars($guest['guest_name']);
        } else {
            $error_message = "Gagal memproses check-out: " . $conn->error;
        }
    } else {
        $error_message = "QR code tidak valid atau tamu sudah check-out.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proses Check-Out Tamu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <!-- Tambahkan logo -->
        <div class="text-center mb-4">
            <img src="img/logo aem.jpeg" alt="Logo Apartemen" width="100">
        </div>
        <h1 class="mb-3">Proses Check-Out Tamu</h1>
        <p class="lead">Silakan masukkan atau pindai QR code untuk memproses check-out tamu.</p>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="qr_code" class="form-label">QR Code</label>
                <input type="text" class="form-control" id="qr_code" name="qr_code" placeholder="Masukkan atau pindai QR code" required>
            </div>

        <!-- Video untuk feed kamera -->
        <div class="mb-3">
                <label class="form-label">Pindai QR Code</label>
                <video id="preview" class="w-100 border" style="height: 300px;"></video>
            </div>

            <button type="submit" class="btn btn-primary" name="check_out">Proses Check-Out</button>
            </form>

        <!-- Tombol Kembali ke Dashboard -->
        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</div>

<script>
    let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
    scanner.addListener('scan', function (content) {
        // Masukkan hasil scan ke input QR Code
        document.getElementById('qr_code').value = content;
        alert('QR Code berhasil dipindai: ' + content);
    });

    // Aktifkan kamera
    Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]); // Gunakan kamera pertama
        } else {
            alert('Kamera tidak ditemukan!');
        }
    }).catch(function (e) {
        console.error(e);
    });
</script>
</body>
</html>