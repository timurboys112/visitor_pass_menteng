<?php
// login.php
session_start();

// Dummy data login
$users = [
    'Admin' => ['admin' => 'admin123'],
    'Receptionist' => ['reception' => 'reception123'],
    'Security' => ['security' => 'security123'],
    'Resident' => ['resident' => 'resident123'],
    'Guest' => ['guest' => 'guest123'],
];

// Ambil data dari form
$role = $_POST['role'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Cek login
if (isset($users[$role][$username]) && $users[$role][$username] === $password) {
    // Simpan data ke session
    $_SESSION['role'] = $role;
    $_SESSION['username'] = $username;

    // Redirect ke dashboard
    header('Location: dashboard.php');
    exit();
} else {
    // Kalau gagal, simpan pesan error di session dan kembali ke login
    $_SESSION['error'] = 'Username atau password salah!';
    header('Location: index.html');
    exit();
}
?>