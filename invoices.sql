CREATE TABLE invoices (
    invoice_id INT AUTO_INCREMENT PRIMARY KEY, -- ID unik untuk setiap tagihan
    invoice_number VARCHAR(50) NOT NULL UNIQUE, -- Nomor tagihan unik
    resident_username VARCHAR(50) NOT NULL, -- Username penghuni yang menerima tagihan
    amount DECIMAL(10, 2) NOT NULL, -- Jumlah tagihan
    due_date DATE NOT NULL, -- Tanggal jatuh tempo tagihan
    status ENUM('Paid', 'Unpaid') DEFAULT 'Unpaid', -- Status pembayaran
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Waktu pembuatan tagihan
    FOREIGN KEY (resident_username) REFERENCES users(username) -- Relasi ke tabel users
        ON DELETE CASCADE ON UPDATE CASCADE
);