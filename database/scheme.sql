-- ============================================================
-- Schema: Application de gestion des réclamations clients
-- ============================================================
 
CREATE DATABASE IF NOT EXISTS reclamations
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
 
USE reclamations;
 
-- ------------------------------------------------------------
-- roles
-- ------------------------------------------------------------
CREATE TABLE roles (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom         VARCHAR(50)  NOT NULL UNIQUE,
  description TEXT
);
 
INSERT INTO roles (nom, description) VALUES
  ('admin',       'Administrateur système'),
  ('superviseur', 'Responsable du service'),
  ('agent',       'Agent de traitement'),
  ('client',      'Client déclarant');

-- ------------------------------------------------------------
-- utilisateurs  (agents, superviseurs, admins)
-- ------------------------------------------------------------
CREATE TABLE utilisateurs (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role_id        INT UNSIGNED NOT NULL,
  nom            VARCHAR(100) NOT NULL,
  prenom         VARCHAR(100) NOT NULL,
  email          VARCHAR(150) NOT NULL UNIQUE,
  mot_de_passe   VARCHAR(255) NOT NULL,        -- hashed (bcrypt)
  actif          BOOLEAN      NOT NULL DEFAULT TRUE,
  created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_util_role FOREIGN KEY (role_id) REFERENCES roles(id)
);
 
-- ------------------------------------------------------------
-- clients
-- ------------------------------------------------------------
CREATE TABLE clients (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom         VARCHAR(100) NOT NULL,
  prenom      VARCHAR(100) NOT NULL,
  email       VARCHAR(150) NOT NULL UNIQUE,
  telephone   VARCHAR(20),
  adresse     TEXT,
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);