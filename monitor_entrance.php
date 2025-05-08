<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.html');
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
if (!in_array($role, ['Admin', 'Security', 'Receptionist'])) {
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitor Pintu Masuk - Apartemen Eksekutif Menteng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> <!-- jQuery for AJAX -->
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
        <!-- Tambahkan logo -->
        <div class="text-center mb-4">
            <img src="img/logo aem.jpeg" alt="Logo Apartemen" width="100">
        </div>
        <h1 class="mb-3">Monitor Pintu Masuk</h1>
        <p class="lead">Selamat datang, <?php echo htmlspecialchars($username); ?> (<?php echo htmlspecialchars($role); ?>)</p>

        <!-- Bagian Info Refresh -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div id="last_refresh" class="text-muted">Terakhir diperbarui: -</div>
            <button class="btn btn-outline-primary btn-sm" onclick="refreshNow()">🔄 Refresh Sekarang</button>
        </div>

        <h3 class="mt-3 text-success">🔹 Tamu Saat Ini di Lokasi</h3>
        <div id="tamu_saat_ini"></div>

        <h3 class="mt-5 text-primary">🕒 Riwayat Aktivitas Pintu Masuk (20 Terakhir)</h3>
        <div id="riwayat_aktivitas"></div>

        <a href="dashboard.php" class="btn btn-secondary mt-4">⬅ Kembali ke Dashboard</a>
    </div>
</div>

<script>
// Fungsi format waktu (HH:MM:SS)
function formatTime(date) {
    return date.toLocaleTimeString('id-ID');
}

// Fungsi untuk update indikator waktu refresh
function updateLastRefresh() {
    const now = new Date();
    $("#last_refresh").text("Terakhir diperbarui: " + formatTime(now));
}

// Fungsi load data
function loadMonitorData() {
    $("#tamu_saat_ini").load("monitor_data_tamu.php?type=in_location");
    $("#riwayat_aktivitas").load("monitor_data_tamu.php?type=history", function() {
        updateLastRefresh();
    });
}

// Fungsi refresh manual
function refreshNow() {
    loadMonitorData();
}

// Panggil pertama kali
loadMonitorData();

// Auto-refresh tiap 30 detik
setInterval(loadMonitorData, 30000);
</script>

</body>
</html>