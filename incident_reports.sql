CREATE TABLE incident_reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    security_username VARCHAR(50) NOT NULL,
    incident_title VARCHAR(255) NOT NULL,
    incident_description TEXT NOT NULL,
    report_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (security_username) REFERENCES users(username)
        ON DELETE CASCADE ON UPDATE CASCADE
);