-- 1️⃣ Buat database (kalau belum ada)
CREATE DATABASE IF NOT EXISTS visitor_pass_menteng;
USE visitor_pass_menteng;

-- 2️⃣ Buat tabel `guests`
CREATE TABLE IF NOT EXISTS guests (
    guest_id INT AUTO_INCREMENT PRIMARY KEY,
    guest_name VARCHAR(100) NOT NULL,
    qr_code VARCHAR(255) UNIQUE NOT NULL,
    check_in_time DATETIME NOT NULL,
    check_out_time DATETIME DEFAULT NULL,
    host_username VARCHAR(50) NOT NULL,
    visitor_type VARCHAR(50) NOT NULL -- Jenis tamu: Guest atau Resident
);

-- 3️⃣ Insert dummy data
INSERT INTO guests (guest_name, qr_code, check_in_time, check_out_time, host_username, visitor_type)
VALUES
('Tamu A', 'QRCODE001', '2025-05-07 08:00:00', '2025-05-07 10:00:00', 'user1', 'Guest'),
('Tamu B', 'QRCODE002', '2025-05-06 09:30:00', NULL, 'user1', 'Resident'),
('Tamu C', 'QRCODE003', '2025-05-05 13:00:00', '2025-05-05 15:30:00', 'user2', 'Guest'),
('Tamu D', 'QRCODE004', '2025-05-07 09:00:00', NULL, 'user2', 'Resident');