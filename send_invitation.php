<?php
// filepath: c:\xampp\htdocs\visitor_pass_menteng\send_invitation.php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit();
}

$username = $_SESSION['username'];

// Menghubungkan ke database
require_once 'db_connection.php';

// Proses pengiriman undangan
if (isset($_POST['send_invitation'])) {
    $guest_name = $_POST['guest_name'];
    $visit_date = $_POST['visit_date'];
    $visit_time = $_POST['visit_time'];
    $message = $_POST['message'];

    $sql = "INSERT INTO invitations (host_username, guest_name, visit_date, visit_time, message) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $guest_name, $visit_date, $visit_time, $message);

    if ($stmt->execute()) {
        $success_message = "Undangan berhasil dikirim!";
    } else {
        $error_message = "Gagal mengirim undangan: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Undangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h1 class="mb-3">Kirim Undangan</h1>
        <p class="lead">Selamat datang, <?php echo htmlspecialchars($username); ?>!</p>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="guest_name" class="form-label">Nama Tamu</label>
                <input type="text" class="form-control" id="guest_name" name="guest_name" required>
            </div>
            <div class="mb-3">
                <label for="visit_date" class="form-label">Tanggal Kunjungan</label>
                <input type="date" class="form-control" id="visit_date" name="visit_date" required>
            </div>
            <div class="mb-3">
                <label for="visit_time" class="form-label">Waktu Kunjungan</label>
                <input type="time" class="form-control" id="visit_time" name="visit_time" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Pesan</label>
                <textarea class="form-control" id="message" name="message" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="send_invitation">Kirim Undangan</button>
        </form>

        <!-- Tombol Kembali ke Dashboard -->
        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>