-- 1️⃣ Buat database (kalau belum ada)
CREATE DATABASE IF NOT EXISTS visitor_pass_menteng;
USE visitor_pass_menteng;

-- 2️⃣ Buat tabel `users`
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Receptionist', 'Security', 'Resident', 'Guest') NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 3️⃣ Buat tabel `guests`
CREATE TABLE IF NOT EXISTS guests (
    guest_id INT AUTO_INCREMENT PRIMARY KEY,
    guest_name VARCHAR(100) NOT NULL,
    qr_code VARCHAR(255) UNIQUE NOT NULL,
    check_in_time DATETIME NOT NULL,
    check_out_time DATETIME DEFAULT NULL,
    host_username VARCHAR(50) NOT NULL
);

-- 4️⃣ Insert dummy data ke `users` (password = "password123")
INSERT INTO users (username, password, role) VALUES
('admin1', '$2y$10$Cqx4Pt3uR80MSLKhOqOnxuAYLV7ZV2La4kR.T.RfVBrTHi5g8h3Qy', 'Admin'),
('resep1', '$2y$10$Cqx4Pt3uR80MSLKhOqOnxuAYLV7ZV2La4kR.T.RfVBrTHi5g8h3Qy', 'Receptionist'),
('security1', '$2y$10$Cqx4Pt3uR80MSLKhOqOnxuAYLV7ZV2La4kR.T.RfVBrTHi5g8h3Qy', 'Security'),
('resident1', '$2y$10$Cqx4Pt3uR80MSLKhOqOnxuAYLV7ZV2La4kR.T.RfVBrTHi5g8h3Qy', 'Resident'),
('guest1', '$2y$10$Cqx4Pt3uR80MSLKhOqOnxuAYLV7ZV2La4kR.T.RfVBrTHi5g8h3Qy', 'Guest');

-- 5️⃣ Insert dummy data ke `guests`
INSERT INTO guests (guest_name, qr_code, check_in_time, check_out_time, host_username) VALUES
('Tamu A', 'QRCODE001', '2025-05-07 08:00:00', '2025-05-07 10:00:00', 'resident1'),
('Tamu B', 'QRCODE002', '2025-05-06 09:30:00', NULL, 'resident1'),
('Tamu C', 'QRCODE003', '2025-05-05 13:00:00', '2025-05-05 15:30:00', 'resident1'),
('Tamu D', 'QRCODE004', '2025-05-07 09:00:00', NULL, 'resident1');