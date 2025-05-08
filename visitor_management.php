<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Cek apakah peran yang diizinkan untuk mengakses halaman ini
if ($role != 'Admin' && $role != 'Receptionist') {
    header('Location: dashboard.php'); // Redirect ke dashboard jika bukan admin/receptionist
    exit();
}

// Menghubungkan ke database
require_once 'db_connection.php';

// Fungsi untuk menambahkan tamu (Registrasi)
if (isset($_POST['register_guest'])) {
    $guest_name = $_POST['guest_name'];
    $visitor_type = $_POST['visitor_type']; // Tamu atau Penghuni
    $check_in_time = date('Y-m-d H:i:s');
    
    $sql = "INSERT INTO guests (guest_name, visitor_type, check_in_time) VALUES ('$guest_name', '$visitor_type', '$check_in_time')";
    if (mysqli_query($conn, $sql)) {
        $message = "Tamu berhasil didaftarkan!";
    } else {
        $message = "Gagal mendaftarkan tamu: " . mysqli_error($conn);
    }
}

// Fungsi untuk check-out tamu
if (isset($_POST['check_out'])) {
    $guest_id = $_POST['guest_id'];
    $check_out_time = date('Y-m-d H:i:s');
    
    $sql = "UPDATE guests SET check_out_time = '$check_out_time' WHERE guest_id = '$guest_id'";
    if (mysqli_query($conn, $sql)) {
        $message = "Tamu berhasil check-out!";
    } else {
        $message = "Gagal check-out tamu: " . mysqli_error($conn);
    }
}

// Fungsi untuk melihat riwayat kunjungan
$sql_history = "SELECT * FROM guests ORDER BY check_in_time DESC";
$result_history = mysqli_query($conn, $sql_history);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visitor Management - Apartemen Eksekutif Menteng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <!-- Tambahkan logo -->
        <div class="text-center mb-4">
            <img src="img/logo aem.jpeg" alt="Logo Apartemen" width="100">
        </div>
        <h1 class="mb-3">Manajemen Tamu</h1>
        <p class="lead">Selamat datang, <?php echo htmlspecialchars($username); ?>! Anda login sebagai <strong><?php echo htmlspecialchars($role); ?></strong>.</p>

        <?php
        if (isset($message)) {
            echo '<div class="alert alert-info" role="alert">' . $message . '</div>';
        }
        ?>

        <!-- Form Registrasi Tamu -->
        <h3>Registrasi Tamu</h3>
        <form method="post">
            <div class="mb-3">
                <label for="guest_name" class="form-label">Nama Tamu</label>
                <input type="text" class="form-control" id="guest_name" name="guest_name" required>
            </div>
            <div class="mb-3">
                <label for="visitor_type" class="form-label">Jenis Tamu</label>
                <select class="form-control" id="visitor_type" name="visitor_type" required>
                    <option value="Guest">Tamu</option>
                    <option value="Resident">Penghuni</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="register_guest">Daftar Tamu</button>
        </form>

        <!-- Form Check-Out Tamu -->
        <h3 class="mt-4">Check-Out Tamu</h3>
        <form method="post">
            <div class="mb-3">
                <label for="guest_id" class="form-label">Pilih Tamu untuk Check-Out</label>
                <select class="form-control" id="guest_id" name="guest_id" required>
                    <?php
                    // Menampilkan tamu yang belum check-out
                    $sql = "SELECT * FROM guests WHERE check_out_time IS NULL";
                    $result = mysqli_query($conn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['guest_id'] . "'>" . $row['guest_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-warning" name="check_out">Check-Out</button>
        </form>

        <!-- Riwayat Kunjungan Tamu -->
        <h3 class="mt-4">Riwayat Kunjungan</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Tamu</th>
                    <th>Jenis Tamu</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Durasi Kunjungan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = mysqli_fetch_assoc($result_history)) {
                    $check_in = new DateTime($row['check_in_time']);
                    $check_out = $row['check_out_time'] ? new DateTime($row['check_out_time']) : null;
                    $duration = $check_out ? $check_in->diff($check_out)->format('%h Jam %i Menit') : '-';
                    echo "<tr>
                            <td>" . $no++ . "</td>
                            <td>" . htmlspecialchars($row['guest_name']) . "</td>
                            <td>" . htmlspecialchars($row['visitor_type']) . "</td>
                            <td>" . $check_in->format('d-m-Y H:i') . "</td>
                            <td>" . ($check_out ? $check_out->format('d-m-Y H:i') : 'Tamu Masih Di Lokasi') . "</td>
                            <td>" . $duration . "</td>
                        </tr>";
                }
                ?>
            </tbody>
            </table>
            </div>
            
            <!-- Tombol Hapus Riwayat Kunjungan -->
            <form method="post" class="mt-4">
                <button type="submit" class="btn btn-danger" name="delete_history" onclick="return confirm('Apakah Anda yakin ingin menghapus semua riwayat kunjungan?')">Hapus Semua Riwayat Kunjungan</button>
            </form>
        
            <!-- Tombol Kembali ke Dashboard -->
            <div class="mt-4">
                <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
            </div>
        </div>
        
        </body>
        </html>