CREATE DATABASE IF NOT EXISTS assurance_db;
USE assurance_db;

-- Table des agents
CREATE TABLE agents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL
);

-- Table des clients
CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    telephone VARCHAR(20),
    adresse TEXT
);

-- Table des contrats
-- CREATE TABLE contrats (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     client_id INT NOT NULL,
--     type VARCHAR(50),
--     montant DECIMAL(10,2),
--     date_debut DATE,
--     date_fin DATE,
--     renouvellement_auto BOOLEAN DEFAULT FALSE,
--     FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
-- );
CREATE TABLE contrats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    agent_id INT NOT NULL,
    type VARCHAR(50) NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    renouvellement_auto BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE CASCADE
);

-- Table des paiements
CREATE TABLE paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contrat_id INT NOT NULL,
    date_paiement DATE,
    montant DECIMAL(10,2),
    statut ENUM('effectue', 'en attente') DEFAULT 'en attente',
    FOREIGN KEY (contrat_id) REFERENCES contrats(id) ON DELETE CASCADE
);
