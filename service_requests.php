<?php
// filepath: c:\xampp\htdocs\visitor_pass_menteng\service_requests.php
session_start();

// Cek apakah user sudah login dan memiliki role Resident
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Resident') {
    header('Location: index.html');
    exit();
}

$username = $_SESSION['username'];

// Menghubungkan ke database
require_once 'db_connection.php';

// Proses pengiriman permintaan layanan
if (isset($_POST['submit_request'])) {
    $service_type = $_POST['service_type'];
    $description = $_POST['description'];

    $sql = "INSERT INTO service_requests (resident_username, service_type, description, request_date, status) 
            VALUES (?, ?, ?, NOW(), 'Pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $service_type, $description);

    if ($stmt->execute()) {
        $success_message = "Permintaan layanan berhasil dikirim.";
    } else {
        $error_message = "Gagal mengirim permintaan layanan: " . $conn->error;
    }
}

// Ambil data permintaan layanan penghuni
$sql_requests = "SELECT service_type, description, request_date, status 
                 FROM service_requests 
                 WHERE resident_username = ? 
                 ORDER BY request_date DESC";
$stmt_requests = $conn->prepare($sql_requests);
$stmt_requests->bind_param("s", $username);
$stmt_requests->execute();
$result_requests = $stmt_requests->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Layanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h1 class="mb-3">Permintaan Layanan</h1>
        <p class="lead">Gunakan formulir di bawah ini untuk mengirim permintaan layanan ke manajemen apartemen.</p>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Form Permintaan Layanan -->
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="service_type" class="form-label">Jenis Layanan</label>
                <select class="form-select" id="service_type" name="service_type" required>
                    <option value="Perbaikan">Perbaikan</option>
                    <option value="Kebersihan">Kebersihan</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Jelaskan kebutuhan layanan Anda" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="submit_request">Kirim Permintaan</button>
        </form>

        <!-- Tabel Riwayat Permintaan Layanan -->
        <h3>Riwayat Permintaan Layanan</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jenis Layanan</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Permintaan</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_requests->num_rows > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = $result_requests->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['service_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo date('d-m-Y H:i', strtotime($row['request_date'])); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada permintaan layanan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tombol Kembali ke Dashboard -->
        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>