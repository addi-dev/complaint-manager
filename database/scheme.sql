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

-- ------------------------------------------------------------
-- utilisateurs  (agents, superviseurs, admins)
-- ------------------------------------------------------------
CREATE TABLE utilisateurs (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role_id        INT UNSIGNED NOT NULL,
  nom            VARCHAR(100) NOT NULL,
  prenom         VARCHAR(100) NOT NULL,
  date_naissance DATE NOT NULL,
  numero_cin     VARCHAR(20) NOT NULL,
  email          VARCHAR(150) NOT NULL UNIQUE,
  mot_de_passe   VARCHAR(255) NOT NULL,        -- hashed (bcrypt)
  actif          BOOLEAN      NOT NULL DEFAULT TRUE,
  created_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at     TIMESTAMP NULL DEFAULT NULL,
  CONSTRAINT fk_util_role FOREIGN KEY (role_id) REFERENCES roles(id)
);
 
-- ------------------------------------------------------------
-- clients
-- ------------------------------------------------------------
CREATE TABLE clients (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom            VARCHAR(100) NOT NULL,
  prenom         VARCHAR(100) NOT NULL,
  date_naissance DATE NOT NULL,
  numero_cin     VARCHAR(20) NOT NULL,
  email          VARCHAR(150) NOT NULL UNIQUE,
  mot_de_passe   VARCHAR(255),
  telephone      VARCHAR(20),
  adresse        TEXT,
  created_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at     TIMESTAMP     NULL
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

 -- ------------------------------------------------------------
-- statuts
-- ------------------------------------------------------------
CREATE TABLE statuts (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  libelle     VARCHAR(100) NOT NULL UNIQUE,
  code        VARCHAR(50)  NOT NULL UNIQUE,
  description TEXT
); 
 
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
  deleted_at     TIMESTAMP     NULL,
  CONSTRAINT fk_rec_client    FOREIGN KEY (client_id)    REFERENCES clients(id),
  CONSTRAINT fk_rec_categorie FOREIGN KEY (categorie_id) REFERENCES categories_reclamation(id),
  CONSTRAINT fk_rec_priorite  FOREIGN KEY (priorite_id)  REFERENCES priorites(id),
  CONSTRAINT fk_rec_statut    FOREIGN KEY (statut_id)    REFERENCES statuts(id),
  CONSTRAINT fk_rec_agent     FOREIGN KEY (agent_id)     REFERENCES utilisateurs(id)
);
 
-- ------------------------------------------------------------
-- affectations  (historique d'attribution)
-- ------------------------------------------------------------
CREATE TABLE affectations (
  id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reclamation_id   INT UNSIGNED NOT NULL,
  utilisateur_id   INT UNSIGNED NOT NULL,   -- agent assigné
  affecte_par      INT UNSIGNED NOT NULL,   -- superviseur/admin qui a affecté
  note             TEXT,
  created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_aff_reclamation FOREIGN KEY (reclamation_id) REFERENCES reclamations(id),
  CONSTRAINT fk_aff_utilisateur FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
  CONSTRAINT fk_aff_par         FOREIGN KEY (affecte_par)    REFERENCES utilisateurs(id)
);

-- ------------------------------------------------------------
-- commentaires  (échanges client ↔ agent + notes internes)
-- ------------------------------------------------------------
CREATE TABLE commentaires (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reclamation_id  INT UNSIGNED NOT NULL,
  auteur_id       INT UNSIGNED NULL,       -- NULL = commentaire client non connecté
  client_id       INT UNSIGNED NULL,       -- renseigné si auteur est le client
  contenu         TEXT         NOT NULL,
  interne         BOOLEAN      NOT NULL DEFAULT FALSE,  -- TRUE = note interne agents
  created_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_com_reclamation FOREIGN KEY (reclamation_id) REFERENCES reclamations(id),
  CONSTRAINT fk_com_auteur      FOREIGN KEY (auteur_id)      REFERENCES utilisateurs(id),
  CONSTRAINT fk_com_client      FOREIGN KEY (client_id)      REFERENCES clients(id)
);

-- ------------------------------------------------------------
-- pieces_jointes
-- ------------------------------------------------------------
CREATE TABLE pieces_jointes (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reclamation_id  INT UNSIGNED  NOT NULL,
  nom_fichier     VARCHAR(255)  NOT NULL,
  chemin          VARCHAR(500)  NOT NULL,
  type_mime       VARCHAR(100),
  taille          INT UNSIGNED,          -- taille en octets
  created_at      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_pj_reclamation FOREIGN KEY (reclamation_id) REFERENCES reclamations(id) ON DELETE CASCADE
);

-- ------------------------------------------------------------
-- historique_actions  (journal horodaté de chaque changement)
-- ------------------------------------------------------------
CREATE TABLE historique_actions (
  id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reclamation_id   INT UNSIGNED NOT NULL,
  utilisateur_id   INT UNSIGNED NULL,
  ancien_statut_id INT UNSIGNED NULL,
  nouveau_statut_id INT UNSIGNED NULL,
  action           VARCHAR(255) NOT NULL,  -- description courte de l'action
  details          TEXT,
  created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_hist_reclamation    FOREIGN KEY (reclamation_id)    REFERENCES reclamations(id),
  CONSTRAINT fk_hist_utilisateur    FOREIGN KEY (utilisateur_id)    REFERENCES utilisateurs(id),
  CONSTRAINT fk_hist_ancien_statut  FOREIGN KEY (ancien_statut_id)  REFERENCES statuts(id),
  CONSTRAINT fk_hist_nouveau_statut FOREIGN KEY (nouveau_statut_id) REFERENCES statuts(id)
);

-- ------------------------------------------------------------
-- notifications
-- ------------------------------------------------------------
CREATE TABLE notifications (
  id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  utilisateur_id  INT UNSIGNED NULL,
  client_id       INT UNSIGNED NULL,
  reclamation_id  INT UNSIGNED NULL,
  type            VARCHAR(50)  NOT NULL,
  message         TEXT         NOT NULL,
  lu              BOOLEAN      NOT NULL DEFAULT FALSE,
  created_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_notif_utilisateur  FOREIGN KEY (utilisateur_id)  REFERENCES utilisateurs(id),
  CONSTRAINT fk_notif_client       FOREIGN KEY (client_id)       REFERENCES clients(id),
  CONSTRAINT fk_notif_reclamation  FOREIGN KEY (reclamation_id)  REFERENCES reclamations(id)
);

-- ============================================================
-- Indexes utiles pour les recherches multicritères
-- ============================================================
CREATE INDEX idx_rec_client    ON reclamations(client_id);
CREATE INDEX idx_rec_statut    ON reclamations(statut_id);
CREATE INDEX idx_rec_priorite  ON reclamations(priorite_id);
CREATE INDEX idx_rec_agent     ON reclamations(agent_id);
CREATE INDEX idx_rec_created   ON reclamations(created_at);
CREATE INDEX idx_notif_user_lu ON notifications(utilisateur_id, lu);
CREATE INDEX idx_hist_rec      ON historique_actions(reclamation_id, created_at);
