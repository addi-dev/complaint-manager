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

-- ------------------------------------------------------------
-- categories_reclamation
-- ------------------------------------------------------------
CREATE TABLE categories_reclamation (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  libelle     VARCHAR(100) NOT NULL UNIQUE,
  description TEXT
);
 
-- ------------------------------------------------------------
-- priorites
-- ------------------------------------------------------------
CREATE TABLE priorites (
  id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  libelle VARCHAR(50)  NOT NULL UNIQUE,  -- ex: Faible, Normale, Haute, Critique
  niveau  TINYINT      NOT NULL UNIQUE   -- 1 (bas) → 4 (critique)
);
 
INSERT INTO priorites (libelle, niveau) VALUES
  ('Faible',    1),
  ('Normale',   2),
  ('Haute',     3),
  ('Critique',  4);
 
 -- ------------------------------------------------------------
-- statuts
-- ------------------------------------------------------------
CREATE TABLE statuts (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  libelle     VARCHAR(100) NOT NULL UNIQUE,
  code        VARCHAR(50)  NOT NULL UNIQUE,
  description TEXT
);
 
INSERT INTO statuts (libelle, code, description) VALUES
  ('Nouvelle',                  'NOUVELLE',              'Réclamation soumise par le client'),
  ('En attente d''affectation', 'ATTENTE_AFFECTATION',   'Enregistrée, non assignée'),
  ('Affectée',                  'AFFECTEE',              'Confiée à un agent'),
  ('En cours de traitement',    'EN_COURS',              'Analyse en cours'),
  ('En attente d''informations','ATTENTE_INFO',          'Le client doit fournir des précisions'),
  ('Résolue',                   'RESOLUE',               'Solution proposée'),
  ('Clôturée',                  'CLOTUREE',              'Dossier finalisé et archivé'),
  ('Rejetée',                   'REJETEE',               'Réclamation non recevable');
 
-- ------------------------------------------------------------
-- reclamations  (entité centrale)
-- ------------------------------------------------------------
CREATE TABLE reclamations (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  numero_unique  VARCHAR(20)   NOT NULL UNIQUE,  -- ex: REC-20240001
  client_id      INT UNSIGNED  NOT NULL,
  categorie_id   INT UNSIGNED  NOT NULL,
  priorite_id    INT UNSIGNED  NOT NULL,
  statut_id      INT UNSIGNED  NOT NULL,
  agent_id       INT UNSIGNED  NULL,             -- agent actuellement assigné
  objet          VARCHAR(255)  NOT NULL,
  description    TEXT          NOT NULL,
  created_at     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  closed_at      TIMESTAMP     NULL,
  CONSTRAINT fk_rec_client    FOREIGN KEY (client_id)    REFERENCES clients(id),
  CONSTRAINT fk_rec_categorie FOREIGN KEY (categorie_id) REFERENCES categories_reclamation(id),
  CONSTRAINT fk_rec_priorite  FOREIGN KEY (priorite_id)  REFERENCES priorites(id),
  CONSTRAINT fk_rec_statut    FOREIGN KEY (statut_id)    REFERENCES statuts(id),
  CONSTRAINT fk_rec_agent     FOREIGN KEY (agent_id)     REFERENCES utilisateurs(id)
);
 