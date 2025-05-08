<?php
// filepath: c:\xampp\htdocs\visitor_pass_menteng\blacklist_guests.php
session_start();

// Cek apakah user sudah login dan memiliki role Security
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'Security') {
    header('Location: index.html');
    exit();
}

$username = $_SESSION['username'];

// Menghubungkan ke database
require_once 'db_connection.php';

// Proses tambah tamu ke blacklist
if (isset($_POST['add_blacklist'])) {
    $guest_name = $_POST['guest_name'];
    $reason = $_POST['reason'];

    $sql = "INSERT INTO blacklist (guest_name, reason, added_by, added_at) 
            VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $guest_name, $reason, $username);

    if ($stmt->execute()) {
        $success_message = "Tamu berhasil ditambahkan ke blacklist.";
    } else {
        $error_message = "Gagal menambahkan tamu ke blacklist: " . $conn->error;
    }
}

// Ambil data blacklist
$sql_blacklist = "SELECT guest_name, reason, added_by, added_at 
                  FROM blacklist 
                  ORDER BY added_at DESC";
$result_blacklist = $conn->query($sql_blacklist);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blacklist Tamu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow p-4">
        <h1 class="mb-3">Blacklist Tamu</h1>
        <p class="lead">Gunakan formulir di bawah ini untuk menambahkan tamu ke daftar blacklist.</p>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <!-- Form Tambah Blacklist -->
        <form method="post" class="mb-4">
            <div class="mb-3">
                <label for="guest_name" class="form-label">Nama Tamu</label>
                <input type="text" class="form-control" id="guest_name" name="guest_name" required>
            </div>
            <div class="mb-3">
                <label for="reason" class="form-label">Alasan</label>
                <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="add_blacklist">Tambah ke Blacklist</button>
        </form>

        <!-- Tabel Blacklist -->
        <h3>Daftar Blacklist</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Tamu</th>
                    <th>Alasan</th>
                    <th>Ditambahkan Oleh</th>
                    <th>Tanggal Ditambahkan</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_blacklist->num_rows > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = $result_blacklist->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['guest_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['reason']); ?></td>
                            <td><?php echo htmlspecialchars($row['added_by']); ?></td>
                            <td><?php echo date('d-m-Y H:i', strtotime($row['added_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada tamu yang diblacklist.</td>
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