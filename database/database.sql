CREATE DATABASE IF NOT EXISTS horizons_lointains
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE horizons_lointains;

-- ===============================================================
-- GROUPE 1 : Tables de référence
-- ===============================================================

CREATE TABLE role (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR (50) NOT NULL
);

CREATE TABLE destination (
    destination_id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    image VARCHAR (255)
);

CREATE TABLE type_hebergement (
    hebergement_id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR (100) NOT NULL
);

CREATE TABLE equipement (
    equipement_id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR (100) NOT NULL
);

-- ================================================================
-- Groupe 2 : Table Principale
-- ================================================================

CREATE TABLE sejour (
    sejour_id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(150) NOT NULL,
    description TEXT,
    image VARCHAR (255),
    prix_personne DECIMAL (10,2) NOT NULL,
    duree_nuits INT NOT NULL,
    superficie_m2 INT,
    prix_comprend TEXT,
    prix_comprend_pas TEXT,
    actif BOOLEAN DEFAULT TRUE,
    destination_id INT NOT NULL,
    hebergement_id INT NOT NULL, 
    FOREIGN KEY (destination_id) REFERENCES destination(destination_id),
    FOREIGN KEY (hebergement_id) REFERENCES type_hebergement(hebergement_id)
);

-- =====================================================================
-- Groupe 3 : Tables de liaison ( many to many)
-- =====================================================================

CREATE TABLE sejour_equipement (
    sejour_id INT NOT NULL,
    equipement_id INT NOT NULL,
    PRIMARY KEY (sejour_id, equipement_id),
    FOREIGN KEY (sejour_id) REFERENCES sejour(sejour_id),
    FOREIGN KEY (equipement_id) REFERENCES equipement(equipement_id)
);

-- =====================================================================
-- Groupe 4 : Table métier
-- =====================================================================

CREATE TABLE utilisateur (
    utilisateur_id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR (100) NOT NULL,
    prenom VARCHAR (100) NOT NULL,
    email VARCHAR (150) NOT NULL,
    mot_de_passe VARCHAR (255) NOT NULL,
    role_id INT NOT NULL DEFAULT 3,
    token_reset VARCHAR (255),
    token_expiration DATETIME,
    FOREIGN KEY (role_id) REFERENCES role(role_id)
);

CREATE TABLE reservation (
    reservation_id INT PRIMARY KEY AUTO_INCREMENT,
    numero_reservation VARCHAR (50) NOT NULL UNIQUE,
    utilisateur_id INT NULL,
    email VARCHAR(150) NOT  NULL
    sejour_id INT NOT NULL,
    date_depart DATE NOT NULL,
    date_retour DATE NOT NULL,
    nb_personnes INT NOT NULL, 
    prix_total DECIMAL (10,2) NOT NULL,
    statut ENUM ('en_attente', 'confirmee', 'annulee', 'terminee') DEFAULT 'en_attente',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP, 
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id),
    FOREIGN KEY (sejour_id) REFERENCES sejour(sejour_id)
);

-- =====================================================================
-- Données de test
-- =====================================================================

INSERT INTO role (libelle) VALUES
('admin'),
('employe'),
('client');

-- ======================================================================
-- Destinations 
-- ======================================================================

INSERT INTO destination (nom, description, image) VALUES
('Asie', "Découvrez les merveilles de l'Asie, entre temples ancestraux, plages paradisiaques et cultures fascinantes.", 'asie.jpg'),
('Caraïbes', 'Evasion garantie aux Caraïbes, entre eaux turquoise, sable blanc et saveurs créoles.' , 'caraibes.jpg');

-- =======================================================================
-- Les types d'hébergement
-- =======================================================================

INSERT INTO type_hebergement (libelle) VALUES
('Hôtel'),
('Pavillion'),
("Maison sur l\eau"),
('Villa');

-- ========================================================================
-- Les équipements
-- ========================================================================

INSERT INTO equipement(libelle) VALUES
('Piscine'),
('Spa'),
('Restaurant'),
('Bar'),
('Bar à la plage'),
('Sport'),
('Internet'),
('Service en chambre 24/24');

-- =========================================================================
-- Les séjours (4 séjours, 2par destination)
-- ==========================================================================

INSERT INTO sejour (titre, description, image, prix_personne, duree_nuits, superficie_m2, prix_comprend, prix_comprend_pas, actif, destination_id, hebergement_id) VALUES
('Évasion à Bali', 'Partez à la découverte de Bali, île des dieux, entre rizières en terrasses, temples sacrés et plages de rêve.', 'bali.jpg', 1200.00, 7, 45, 'Vol aller-retour, hébergement, petit-déjeuner', 'Dîners, activités optionnelles, boissons', TRUE, 1, 1),
('Circuit Tokyo & Kyoto', 'Plongez au cœur du Japon moderne et traditionnel, entre gratte-ciels de Tokyo et temples de Kyoto.', 'japon.jpg', 1800.00, 10, 35, 'Vol aller-retour, hébergement, petits-déjeuners, guide francophone', 'Déjeuners, dîners, pourboires', TRUE, 1, 1),
('Martinique Soleil', 'Une semaine au paradis en Martinique, entre plages de sable blanc, forêt tropicale et gastronomie créole.', 'martinique.jpg', 950.00, 7, 40, 'Vol aller-retour, hébergement, petit-déjeuner', 'Repas, activités, transport sur place', TRUE, 2, 2),
('Barbade Luxe', "Séjour haut de gamme à la Barbade, dans une villa privée face à l'océan Atlantique.", 'barbade.jpg', 2500.00, 10, 80, 'Vol aller-retour, villa privée, piscine, petit-déjeuner et dîner', 'Déjeuners, activités nautiques', TRUE, 2, 4);

-- ==========================================================================
-- Les équipements de séjours (liaison)
-- ==========================================================================

INSERT INTO sejour_equipement ( sejour_id, equipement_id) VALUES
-- Évasion à Bali
(1, 1), -- Piscine
(1, 2), -- Spa
(1, 3), -- Restaurant
(1, 7), -- Internet
-- Circuit Tokyo & Kyoto
(2, 3), -- Restaurant
(2, 7), -- Internet
-- Martinique Soleil
(3, 1), -- Piscine
(3, 4), -- Bar
(3, 5), -- Bar à la plage
(3, 6), -- Sport
-- Barbade Luxe
(4, 1), -- Piscine
(4, 2), -- Spa
(4, 3), -- Restaurant
(4, 4), -- Bar
(4, 5), -- Bar à la plage
(4, 8); -- Service en chambre 24h/24

-- ==========================================================================
-- Utilisateur de test
-- ==========================================================================

INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role_id) VALUES
('Admin', 'Horizons', 'admin@horizons-lointains.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
('Dupont', 'Marie', 'employe@horizons-lointains.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2),
('Martin', 'Sophie', 'client@horizons-lointains.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3);