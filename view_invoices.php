<?php
// filepath: c:\xampp\htdocs\visitor_pass_menteng\view_invoices.php
session_start();

// Cek apakah user sudah login dan memiliki role Resident
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Resident') {
    header('Location: index.html');
    exit();
}

$username = $_SESSION['username'];

// Menghubungkan ke database
require_once 'db_connection.php';

// Ambil data tagihan penghuni
$sql = "SELECT invoice_number, amount, due_date, status 
        FROM invoices 
        WHERE resident_username = ? 
        ORDER BY due_date DESC";
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
    <title>Lihat Tagihan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h1 class="mb-3">Lihat Tagihan</h1>
        <p class="lead">Berikut adalah daftar tagihan Anda.</p>

        <!-- Tabel Tagihan -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Tagihan</th>
                    <th>Jumlah</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['invoice_number']); ?></td>
                            <td>Rp <?php echo number_format($row['amount'], 0, ',', '.'); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row['due_date'])); ?></td>
                            <td>
                                <?php echo $row['status'] === 'Paid' 
                                    ? '<span class="badge bg-success">Lunas</span>' 
                                    : '<span class="badge bg-danger">Belum Lunas</span>'; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada tagihan.</td>
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