CREATE TABLE apartments (
    apartment_id INT AUTO_INCREMENT PRIMARY KEY, -- ID unik untuk setiap apartemen
    owner_username VARCHAR(50) NOT NULL,         -- Username pemilik apartemen
    apartment_number VARCHAR(20) NOT NULL,       -- Nomor apartemen
    floor INT NOT NULL,                          -- Lantai apartemen
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Waktu pembuatan data
);

-- Contoh data awal
INSERT INTO apartments (owner_username, apartment_number, floor) VALUES
('user1', 'A101', 1),
('user2', 'B202', 2),
('user3', 'C303', 3);