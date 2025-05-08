<?php
// filepath: c:\xampp\htdocs\visitor_pass_menteng\view_visits.php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit();
}

$username = $_SESSION['username'];

// Menghubungkan ke database
require_once 'db_connection.php';

// Ambil data riwayat kunjungan tamu milik user
$sql = "SELECT guest_name, check_in_time, check_out_time 
        FROM guests 
        WHERE host_username = ? 
        ORDER BY check_in_time DESC";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Kunjungan Tamu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h1 class="mb-3">Riwayat Kunjungan Tamu</h1>
        <p class="lead">Berikut adalah daftar kunjungan tamu Anda.</p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Tamu</th>
                    <th>Waktu Check-In</th>
                    <th>Waktu Check-Out</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['guest_name']); ?></td>
                            <td><?php echo date('d-m-Y H:i', strtotime($row['check_in_time'])); ?></td>
                            <td>
                                <?php echo $row['check_out_time'] 
                                    ? date('d-m-Y H:i', strtotime($row['check_out_time'])) 
                                    : 'Belum Check-Out'; ?>
                            </td>
                            <td>
                                <?php echo $row['check_out_time'] ? 'Selesai' : 'Masih di Lokasi'; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada riwayat kunjungan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
    </div>
</div>
</body>
</html>