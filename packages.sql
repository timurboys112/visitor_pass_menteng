CREATE TABLE packages (
    package_id INT AUTO_INCREMENT PRIMARY KEY, -- ID unik untuk setiap paket
    resident_name VARCHAR(100) NOT NULL,       -- Nama penghuni penerima paket
    package_description TEXT NOT NULL,         -- Deskripsi paket
    received_by VARCHAR(50) NOT NULL,          -- Username resepsionis yang menerima paket
    received_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Waktu penerimaan paket
);