<?php
// filepath: c:\xampp\htdocs\visitor_pass_menteng\reports.php
session_start();

// Cek apakah user sudah login dan memiliki role Admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
    header('Location: index.html');
    exit();
}

// Menghubungkan ke database
require_once 'db_connection.php';

// Ambil data laporan tamu
$sql_guests = "SELECT guest_name, check_in_time, check_out_time, host_username FROM guests ORDER BY check_in_time DESC";
$result_guests = $conn->query($sql_guests);

// Ambil data laporan apartemen
$sql_apartments = "SELECT apartment_number, floor, owner_username FROM apartments ORDER BY apartment_number ASC";
$result_apartments = $conn->query($sql_apartments);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <!-- Tambahkan logo -->
        <div class="text-center mb-4">
            <img src="img/logo aem.jpeg" alt="Logo Apartemen" width="100">
        </div>
        <h1 class="mb-3">Laporan</h1>
        <p class="lead">Berikut adalah laporan data tamu dan apartemen.</p>

        <!-- Laporan Tamu -->
        <h3 class="mt-4">Laporan Tamu</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Tamu</th>
                    <th>Waktu Check-In</th>
                    <th>Waktu Check-Out</th>
                    <th>Penghuni yang Mengundang</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_guests->num_rows > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = $result_guests->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['guest_name']); ?></td>
                            <td><?php echo date('d-m-Y H:i', strtotime($row['check_in_time'])); ?></td>
                            <td>
                                <?php echo $row['check_out_time'] 
                                    ? date('d-m-Y H:i', strtotime($row['check_out_time'])) 
                                    : 'Belum Check-Out'; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['host_username']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data tamu.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Laporan Apartemen -->
        <h3 class="mt-4">Laporan Apartemen</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Apartemen</th>
                    <th>Lantai</th>
                    <th>Pemilik</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_apartments->num_rows > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = $result_apartments->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['apartment_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['floor']); ?></td>
                            <td><?php echo htmlspecialchars($row['owner_username']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data apartemen.</td>
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