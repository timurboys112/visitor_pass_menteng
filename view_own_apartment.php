<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit();
}

$username = $_SESSION['username'];

// Menghubungkan ke database
require_once 'db_connection.php';

// Jika ada POST (form tambah apartemen)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $apartment_number = $_POST['apartment_number'] ?? '';
    $floor = $_POST['floor'] ?? '';

    if (!empty($apartment_number) && !empty($floor)) {
        $sql_insert = "INSERT INTO apartments (owner_username, apartment_number, floor) VALUES (?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssi", $username, $apartment_number, $floor);
        $stmt_insert->execute();

        // Setelah berhasil tambah, redirect ulang halaman biar fresh
        header("Location: view_own_apartment.php");
        exit();
    }
}

// Ambil informasi apartemen milik pengguna
$sql = "SELECT * FROM apartments WHERE owner_username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$apartment = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Apartemen Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h1 class="mb-3">Informasi Apartemen Saya</h1>
        <p class="lead">Selamat datang, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>

        <?php if ($apartment): ?>
            <table class="table table-striped">
                <tr>
                    <th>Nomor Apartemen</th>
                    <td><?php echo htmlspecialchars($apartment['apartment_number']); ?></td>
                </tr>
                <tr>
                    <th>Lantai</th>
                    <td><?php echo htmlspecialchars($apartment['floor']); ?></td>
                </tr>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">Anda belum memiliki apartemen yang terdaftar.</div>
            <!-- Form Tambah Apartemen -->
            <form method="post" class="mt-3">
                <div class="mb-3">
                    <label for="apartment_number" class="form-label">Nomor Apartemen</label>
                    <input type="text" class="form-control" id="apartment_number" name="apartment_number" required>
                </div>
                <div class="mb-3">
                    <label for="floor" class="form-label">Lantai</label>
                    <input type="number" class="form-control" id="floor" name="floor" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Apartemen</button>
            </form>
        <?php endif; ?>

        <!-- Tombol Kembali -->
        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>