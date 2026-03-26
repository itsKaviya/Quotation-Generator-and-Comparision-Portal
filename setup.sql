CREATE DATABASE IF NOT EXISTS quotation_portal;
USE quotation_portal;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS vendors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    rating FLOAT
);

CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    description TEXT
);

CREATE TABLE IF NOT EXISTS quotations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    vendor_id INT,
    service_id INT,
    price DECIMAL(10,2),
    delivery_days INT,
    FOREIGN KEY (vendor_id) REFERENCES vendors(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
);

CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    service_id INT,
    title VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
);

-- Insert Sample Services
INSERT IGNORE INTO services (id, name, description) VALUES 
(1, 'Web Development', 'Custom website design and development'),
(2, 'Mobile App Development', 'Android and iOS app creation'),
(3, 'UI/UX Design', 'Modern user interface and experience design');

-- Insert Sample Vendors
INSERT IGNORE INTO vendors (id, name, rating) VALUES 
(1, 'TechNova Solutions', 4.5),
(2, 'CloudScale IT', 4.2),
(3, 'DevFlow Agency', 4.8),
(4, 'PixelPerfect Studios', 4.0);

-- Insert Sample Quotations
INSERT IGNORE INTO quotations (vendor_id, service_id, price, delivery_days) VALUES 
(1, 1, 1500.00, 15),
(2, 1, 1200.00, 20),
(3, 1, 1800.00, 10),
(4, 1, 1000.00, 25),
(1, 2, 3000.00, 45),
(2, 2, 2500.00, 60),
(3, 2, 3500.00, 30);
