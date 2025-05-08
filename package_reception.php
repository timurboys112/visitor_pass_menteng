<?php
// filepath: c:\xampp\htdocs\visitor_pass_menteng\package_reception.php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit();
}

$username = $_SESSION['username'];

// Menghubungkan ke database
require_once 'db_connection.php';

// Proses penerimaan paket
if (isset($_POST['receive_package'])) {
    $resident_name = $_POST['resident_name'];
    $package_description = $_POST['package_description'];

    $sql = "INSERT INTO packages (resident_name, package_description, received_by, received_at) 
            VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $resident_name, $package_description, $username);

    if ($stmt->execute()) {
        $success_message = "Paket berhasil diterima untuk penghuni: $resident_name.";
    } else {
        $error_message = "Gagal mencatat penerimaan paket: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penerimaan Paket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <!-- Tambahkan logo -->
        <div class="text-center mb-4">
            <img src="img/logo aem.jpeg" alt="Logo Apartemen" width="100">
        </div>
        <h1 class="mb-3">Penerimaan Paket</h1>
        <p class="lead">Silakan catat penerimaan paket untuk penghuni apartemen.</p>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label for="resident_name" class="form-label">Nama Penghuni</label>
                <input type="text" class="form-control" id="resident_name" name="resident_name" placeholder="Masukkan nama penghuni" required>
            </div>
            <div class="mb-3">
                <label for="package_description" class="form-label">Deskripsi Paket</label>
                <textarea class="form-control" id="package_description" name="package_description" rows="3" placeholder="Masukkan deskripsi paket" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="receive_package">Catat Penerimaan Paket</button>
        </form>

        <!-- Tombol Kembali ke Dashboard -->
        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>