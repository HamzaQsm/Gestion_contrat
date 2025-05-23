-- Create email history table
CREATE TABLE IF NOT EXISTS email_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contrat_id INT,
    client_email VARCHAR(255),
    subject VARCHAR(255),
    message TEXT,
    status ENUM('pending', 'sent', 'failed') DEFAULT 'pending',
    error_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (contrat_id) REFERENCES contrats(id)
);
