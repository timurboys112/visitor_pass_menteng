<?php
// filepath: c:\xampp\htdocs\visitor_pass_menteng\incident_reports.php
session_start();

// Cek apakah user sudah login dan memiliki role Security
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Security') {
    header('Location: index.html');
    exit();
}

$username = $_SESSION['username'];

// Menghubungkan ke database
require_once 'db_connection.php';

// Proses tambah laporan insiden
if (isset($_POST['submit_report'])) {
    $incident_title = $_POST['incident_title'];
    $incident_description = $_POST['incident_description'];

    $sql = "INSERT INTO incident_reports (security_username, incident_title, incident_description, report_date) 
            VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $incident_title, $incident_description);

    if ($stmt->execute()) {
        $success_message = "Laporan insiden berhasil ditambahkan.";
    } else {
        $error_message = "Gagal menambahkan laporan insiden: " . $conn->error;
    }
}

// Ambil data laporan insiden
$sql_reports = "SELECT incident_title, incident_description, report_date, security_username 
                FROM incident_reports 
                ORDER BY report_date DESC";
$result_reports = $conn->query($sql_reports);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Insiden</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h1 class="mb-3">Laporan Insiden</h1>
        <p class="lead">Gunakan formulir di bawah ini untuk mencatat laporan insiden.</p>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Form Tambah Laporan Insiden -->
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="incident_title" class="form-label">Judul Insiden</label>
                <input type="text" class="form-control" id="incident_title" name="incident_title" required>
            </div>
            <div class="mb-3">
                <label for="incident_description" class="form-label">Deskripsi Insiden</label>
                <textarea class="form-control" id="incident_description" name="incident_description" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="submit_report">Kirim Laporan</button>
        </form>

        <!-- Tabel Laporan Insiden -->
        <h3>Riwayat Laporan Insiden</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul Insiden</th>
                    <th>Deskripsi</th>
                    <th>Tanggal Laporan</th>
                    <th>Satpam</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_reports->num_rows > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = $result_reports->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['incident_title']); ?></td>
                            <td><?php echo htmlspecialchars($row['incident_description']); ?></td>
                            <td><?php echo date('d-m-Y H:i', strtotime($row['report_date'])); ?></td>
                            <td><?php echo htmlspecialchars($row['security_username']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada laporan insiden.</td>
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