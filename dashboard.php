<?php
// filepath: c:\xampp\htdocs\visitor_pass_menteng\dashboard.php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Apartemen Eksekutif Menteng</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f9f4;
      font-family: Arial, sans-serif;
    }
    .card {
      border: none;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    h1, h3 {
      color: #4a8d72;
    }
    .dashboard-btn {
      background-color: #4a8d72;
      border-color: #4a8d72;
      color: white;
    }
    .dashboard-btn:hover {
      background-color: #3b6f5b;
      border-color: #3b6f5b;
      color: white;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <div class="card shadow p-4 text-center">
    <img src="img/logo aem.jpeg" alt="Logo Apartemen" width="80" class="mb-3">
    <h1 class="mb-3">Selamat Datang, <?php echo htmlspecialchars($username); ?>!</h1>
    <p class="lead">Anda login sebagai <strong><?php echo htmlspecialchars($role); ?></strong>.</p>

    <div class="mt-4 text-start">
      <?php
      if ($role == 'Admin') {
          echo '<h3>Dashboard Admin</h3>';
          echo '
          <div class="d-grid gap-2">
            <a href="manage_users.php" class="btn dashboard-btn">Kelola Pengguna</a>
            <a href="manage_apartments.php" class="btn dashboard-btn">Kelola Apartemen</a>
            <a href="reports.php" class="btn dashboard-btn">Laporan</a>
            <a href="monitor_entrance.php" class="btn dashboard-btn">Pantau Pintu Masuk</a>
            <a href="Receptionist_management.php" class="btn dashboard-btn">Manajemen Resepsionis</a>
            <a href="Security_management.php" class="btn dashboard-btn">Manajemen Satpam</a>
            <a href="visitor_management.php" class="btn dashboard-btn">Manajemen Tamu</a>
          </div>';
      } elseif ($role == 'Receptionist') {
          echo '<h3>Dashboard Receptionist</h3>';
          echo '
          <div class="d-grid gap-2">
            <a href="check_in.php" class="btn dashboard-btn">Proses Check-In Tamu</a>
            <a href="check_out.php" class="btn dashboard-btn">Proses Check-Out Tamu</a>
            <a href="monitor_entrance.php" class="btn dashboard-btn">Pantau Pintu Masuk</a>
            <a href="package_reception.php" class="btn dashboard-btn">Penerimaan Paket</a>
            <a href="visitor_management.php" class="btn dashboard-btn">Manajemen Tamu</a>
            <a href="view_residents.php" class="btn dashboard-btn">Lihat Data Penghuni</a>
          </div>';
      } elseif ($role == 'Security') {
          echo '<h3>Dashboard Security</h3>';
          echo '
          <div class="d-grid gap-2">
            <a href="monitor_entrance.php" class="btn dashboard-btn">Pantau Pintu Masuk</a>
            <a href="incident_reports.php" class="btn dashboard-btn">Laporan Insiden</a>
            <a href="blacklist_guests.php" class="btn dashboard-btn">Blacklist Tamu</a>
            <a href="access_control.php" class="btn dashboard-btn">Akses Kontrol (QR Scan)</a>
          </div>';
      } elseif ($role == 'Resident') {
          echo '<h3>Dashboard Resident</h3>';
          echo '
          <div class="d-grid gap-2">
            <a href="view_own_apartment.php" class="btn dashboard-btn">Lihat Apartemen Saya</a>
            <a href="send_invitation.php" class="btn dashboard-btn">Kirim Undangan Tamu</a>
            <a href="view_visits.php" class="btn dashboard-btn">Riwayat Kunjungan Tamu</a>
            <a href="service_requests.php" class="btn dashboard-btn">Kirim Permintaan Layanan</a>
            <a href="view_invoices.php" class="btn dashboard-btn">Lihat Tagihan</a>
          </div>';
      } elseif ($role == 'Guest') {
          echo '<h3>Dashboard Guest</h3>';
          echo '
          <div class="d-grid gap-2">
            <a href="view_invitation.php" class="btn dashboard-btn">Lihat Undangan</a>
            <a href="check_in_status.php" class="btn dashboard-btn">Status Check-In</a>
          </div>';
      } else {
          echo '<p>Peran tidak dikenali.</p>';
      }
      ?>
    </div>

    <a href="logout.php" class="btn btn-danger mt-4">Logout</a>
  </div>
</div>

</body>
</html>