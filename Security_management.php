<?php
// filepath: c:\xampp\htdocs\visitor_pass_menteng\Security_management.php
session_start();

// Cek apakah user sudah login dan memiliki role Admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin') {
    header('Location: index.html');
    exit();
}

// Menghubungkan ke database
require_once 'db_connection.php';

// Proses tambah satpam
if (isset($_POST['add_security'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password
    $role = 'Security'; // Role tetap sebagai Security

    $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $password, $role);

    if ($stmt->execute()) {
        $success_message = "Satpam berhasil ditambahkan.";
    } else {
        $error_message = "Gagal menambahkan satpam: " . $conn->error;
    }
}

// Proses hapus satpam
if (isset($_GET['delete_security'])) {
    $user_id = $_GET['delete_security'];

    $sql = "DELETE FROM users WHERE user_id = ? AND role = 'Security'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $success_message = "Satpam berhasil dihapus.";
    } else {
        $error_message = "Gagal menghapus satpam: " . $conn->error;
    }
}

// Ambil data satpam
$sql = "SELECT * FROM users WHERE role = 'Security'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Satpam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <!-- Tambahkan logo -->
        <div class="text-center mb-4">
            <img src="img/logo aem.jpeg" alt="Logo Apartemen" width="100">
        </div>
        <h1 class="mb-3">Manajemen Satpam</h1>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Form Tambah Satpam -->
        <form method="post" class="mb-4">
            <h3>Tambah Satpam</h3>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="add_security">Tambah Satpam</button>
        </form>

        <!-- Tabel Data Satpam -->
        <h3>Daftar Satpam</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td>
                                <a href="?delete_security=<?php echo $row['user_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus satpam ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada satpam.</td>
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