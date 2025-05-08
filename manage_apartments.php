<?php
// filepath: c:\xampp\htdocs\visitor_pass_menteng\manage_apartments.php
session_start();

// Cek apakah user sudah login dan memiliki role Admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
    header('Location: index.html');
    exit();
}

// Menghubungkan ke database
require_once 'db_connection.php';

// Proses tambah apartemen
if (isset($_POST['add_apartment'])) {
    $apartment_number = $_POST['apartment_number'];
    $floor = $_POST['floor'];
    $owner_username = $_POST['owner_username'];

    $sql = "INSERT INTO apartments (apartment_number, floor, owner_username) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $apartment_number, $floor, $owner_username);

    if ($stmt->execute()) {
        $success_message = "Apartemen berhasil ditambahkan.";
    } else {
        $error_message = "Gagal menambahkan apartemen: " . $conn->error;
    }
}

// Proses hapus apartemen
if (isset($_GET['delete_apartment'])) {
    $apartment_id = $_GET['delete_apartment'];

    $sql = "DELETE FROM apartments WHERE apartment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $apartment_id);

    if ($stmt->execute()) {
        $success_message = "Apartemen berhasil dihapus.";
    } else {
        $error_message = "Gagal menghapus apartemen: " . $conn->error;
    }
}

// Ambil data apartemen
$sql = "SELECT * FROM apartments";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Apartemen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <!-- Tambahkan logo -->
        <div class="text-center mb-4">
            <img src="img/logo aem.jpeg" alt="Logo Apartemen" width="100">
        </div>

        <h1 class="mb-3">Kelola Apartemen</h1>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Form Tambah Apartemen -->
        <form method="post" class="mb-4">
            <h3>Tambah Apartemen</h3>
            <div class="mb-3">
                <label for="apartment_number" class="form-label">Nomor Apartemen</label>
                <input type="text" class="form-control" id="apartment_number" name="apartment_number" required>
            </div>
            <div class="mb-3">
                <label for="floor" class="form-label">Lantai</label>
                <input type="number" class="form-control" id="floor" name="floor" required>
            </div>
            <div class="mb-3">
                <label for="owner_username" class="form-label">Username Pemilik</label>
                <input type="text" class="form-control" id="owner_username" name="owner_username" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_apartment">Tambah Apartemen</button>
        </form>

        <!-- Tabel Data Apartemen -->
        <h3>Daftar Apartemen</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Apartemen</th>
                    <th>Lantai</th>
                    <th>Pemilik</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['apartment_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['floor']); ?></td>
                            <td><?php echo htmlspecialchars($row['owner_username']); ?></td>
                            <td>
                                <a href="?delete_apartment=<?php echo $row['apartment_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus apartemen ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada apartemen.</td>
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