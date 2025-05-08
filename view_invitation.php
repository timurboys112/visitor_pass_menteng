<?php
// filepath: c:\xampp\htdocs\visitor_pass_menteng\view_invitation.php
session_start();

// Cek apakah user sudah login dan memiliki role Guest
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Guest') {
    header('Location: index.html');
    exit();
}

$username = $_SESSION['username'];

// Menghubungkan ke database
require_once 'db_connection.php';

// Ambil data undangan untuk tamu yang login
$sql = "SELECT host_username, visit_date, visit_time, message 
        FROM invitations 
        WHERE guest_username = ? 
        ORDER BY visit_date DESC, visit_time DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Undangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h1 class="mb-3">Lihat Undangan</h1>
        <p class="lead">Berikut adalah daftar undangan yang Anda terima.</p>

        <!-- Tabel Undangan -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pengirim Undangan</th>
                    <th>Tanggal Kunjungan</th>
                    <th>Waktu Kunjungan</th>
                    <th>Pesan</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['host_username']); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row['visit_date'])); ?></td>
                            <td><?php echo date('H:i', strtotime($row['visit_time'])); ?></td>
                            <td><?php echo htmlspecialchars($row['message']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada undangan yang diterima.</td>
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