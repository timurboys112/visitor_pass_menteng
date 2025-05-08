<?php
// filepath: c:\xampp\htdocs\visitor_pass_menteng\access_control.php
session_start();

// Cek apakah user sudah login dan memiliki role Security
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Security') {
    header('Location: index.html');
    exit();
}

$username = $_SESSION['username'];

// Menghubungkan ke database
require_once 'db_connection.php';

// Proses verifikasi QR Code
if (isset($_POST['verify_qr'])) {
    $qr_code = $_POST['qr_code'];

    // Cek apakah QR code valid
    $sql = "SELECT guest_name, host_username, visit_date, visit_time 
            FROM invitations 
            WHERE qr_code = ? AND visit_date = CURDATE()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $qr_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $success_message = "Akses valid untuk tamu: " . htmlspecialchars($row['guest_name']) . 
                           ". Diundang oleh: " . htmlspecialchars($row['host_username']) . 
                           " pada " . date('d-m-Y H:i', strtotime($row['visit_time'])) . ".";
    } else {
        $error_message = "QR Code tidak valid atau tidak berlaku untuk hari ini.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Kontrol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h1 class="mb-3">Akses Kontrol (QR Scan)</h1>
        <p class="lead">Gunakan formulir di bawah ini untuk memverifikasi QR Code tamu.</p>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Form Verifikasi QR Code -->
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="qr_code" class="form-label">QR Code</label>
                <input type="text" class="form-control" id="qr_code" name="qr_code" placeholder="Masukkan QR Code" required>
            </div>
            <button type="submit" class="btn btn-primary" name="verify_qr">Verifikasi</button>
        </form>

        <!-- Tombol Kembali ke Dashboard -->
        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>