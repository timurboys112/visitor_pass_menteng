DROP TABLE IF EXISTS invitations;

CREATE TABLE invitations (
    invitation_id INT AUTO_INCREMENT PRIMARY KEY,  -- ID unik untuk setiap undangan
    host_username VARCHAR(50) NOT NULL,            -- Username pengirim undangan
    guest_username VARCHAR(50) NOT NULL,           -- Username tamu (buat relasi dengan user tamu yang login)
    guest_name VARCHAR(100) NOT NULL,              -- Nama tamu (nama lengkapnya)
    visit_date DATE NOT NULL,                      -- Tanggal kunjungan
    visit_time TIME NOT NULL,                      -- Waktu kunjungan
    message TEXT,                                   -- Pesan opsional
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Waktu pembuatan data
    qr_code VARCHAR(255) UNIQUE                     -- QR Code (unik)
);