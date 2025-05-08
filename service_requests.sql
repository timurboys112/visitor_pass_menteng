CREATE TABLE service_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    resident_username VARCHAR(50) NOT NULL,
    service_type VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending', 'Diproses', 'Selesai') DEFAULT 'Pending'
);