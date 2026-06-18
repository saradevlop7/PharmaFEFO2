CREATE DATABASE IF NOT EXISTS pharmafefo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pharmafefo;

CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(100) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('PREPARATEUR','PHARMACIEN','ADMIN') NOT NULL DEFAULT 'PREPARATEUR',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS medications (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(255) NOT NULL,
    category   VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS batches (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    medication_id INT NOT NULL,
    lot_number    VARCHAR(100) NOT NULL UNIQUE,
    quantity      INT NOT NULL DEFAULT 0,
    expiry_date   DATE NOT NULL,
    supplier      VARCHAR(150),
    status        ENUM('active','expired','destroyed') NOT NULL DEFAULT 'active',
    created_at    DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (medication_id) REFERENCES medications(id)
);

CREATE TABLE IF NOT EXISTS stock_movements (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    batch_id   INT NOT NULL,
    user_id    INT NOT NULL,
    action     ENUM('ADD','DELIVER','DESTROY') NOT NULL,
    quantity   INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (batch_id) REFERENCES batches(id),
    FOREIGN KEY (user_id)  REFERENCES users(id)
);


INSERT IGNORE INTO users (username, password, role) VALUES
  ('admin',       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ADMIN'),
  ('pharmacien',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'PHARMACIEN'),
  ('preparateur', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'PREPARATEUR');
