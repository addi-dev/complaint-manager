-- ============================================================
-- Seed: données de test — reclamations
-- ============================================================
 
USE reclamations;
 
-- ------------------------------------------------------------
-- utilisateurs  (mot de passe = "password123" bcrypt hash)
-- ------------------------------------------------------------
INSERT INTO utilisateurs (role_id, nom, prenom, email, mot_de_passe, actif) VALUES
  (1, 'Benali',    'Karim',    'karim.benali@company.ma',    '$2b$12$KIXbq1234fakeHashAdminAAAAAAAAAAAAAAAAAAAAAAAAAAAA', TRUE),
  (2, 'Tazi',      'Fatima',   'fatima.tazi@company.ma',     '$2b$12$KIXbq1234fakeHashSupervAAAAAAAAAAAAAAAAAAAAAAAAAAAA', TRUE),
  (3, 'Moussaoui', 'Youssef',  'youssef.moussaoui@company.ma','$2b$12$KIXbq1234fakeHashAgent1AAAAAAAAAAAAAAAAAAAAAAAAAAAA', TRUE),
  (3, 'El Idrissi','Sara',     'sara.elidrissi@company.ma',  '$2b$12$KIXbq1234fakeHashAgent2AAAAAAAAAAAAAAAAAAAAAAAAAAAA', TRUE),
  (3, 'Cherkaoui', 'Omar',     'omar.cherkaoui@company.ma',  '$2b$12$KIXbq1234fakeHashAgent3AAAAAAAAAAAAAAAAAAAAAAAAAAAA', TRUE),
  (3, 'Amrani',    'Nadia',    'nadia.amrani@company.ma',    '$2b$12$KIXbq1234fakeHashAgent4AAAAAAAAAAAAAAAAAAAAAAAAAAAA', FALSE); -- compte désactivé
 
-- ------------------------------------------------------------
-- clients
-- ------------------------------------------------------------
INSERT INTO utilisateurs (role_id, nom, prenom, email, mot_de_passe, actif)
VALUES
(1, 'El Amrani', 'Youssef', 'youssef.elamrani1@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(2, 'Benali', 'Salma', 'salma.benali2@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(3, 'Alaoui', 'Hamza', 'hamza.alaoui3@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(1, 'Tazi', 'Imane', 'imane.tazi4@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(2, 'Fassi', 'Omar', 'omar.fassi5@example.com', '$2y$10$abcdefghijklmnopqrstuv', FALSE),
(3, 'Idrissi', 'Sara', 'sara.idrissi6@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(1, 'Bennani', 'Karim', 'karim.bennani7@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(2, 'Cherkaoui', 'Nadia', 'nadia.cherkaoui8@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(3, 'Lahlou', 'Mehdi', 'mehdi.lahlou9@example.com', '$2y$10$abcdefghijklmnopqrstuv', FALSE),
(1, 'Kettani', 'Aya', 'aya.kettani10@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),

(2, 'Zniber', 'Rachid', 'rachid.zniber11@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(3, 'Skalli', 'Meryem', 'meryem.skalli12@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(1, 'Berrada', 'Anas', 'anas.berrada13@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(2, 'Filali', 'Lina', 'lina.filali14@example.com', '$2y$10$abcdefghijklmnopqrstuv', FALSE),
(3, 'Mansouri', 'Zakaria', 'zakaria.mansouri15@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(1, 'Naciri', 'Hajar', 'hajar.naciri16@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(2, 'Boukili', 'Yassine', 'yassine.boukili17@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(3, 'Ouazzani', 'Chaimae', 'chaimae.ouazzani18@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(1, 'Rami', 'Soufiane', 'soufiane.rami19@example.com', '$2y$10$abcdefghijklmnopqrstuv', FALSE),
(2, 'Tahiri', 'Asmae', 'asmae.tahiri20@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),

(3, 'Boussaid', 'Walid', 'walid.boussaid21@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(1, 'Sefrioui', 'Khadija', 'khadija.sefrioui22@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(2, 'Ait Said', 'Nabil', 'nabil.aitsaid23@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(3, 'Mikou', 'Samira', 'samira.mikou24@example.com', '$2y$10$abcdefghijklmnopqrstuv', FALSE),
(1, 'Dahbi', 'Reda', 'reda.dahbi25@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(2, 'Jabri', 'Ilham', 'ilham.jabri26@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(3, 'Bakkali', 'Adil', 'adil.bakkali27@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(1, 'Amine', 'Siham', 'siham.amine28@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE),
(2, 'Khattabi', 'Tarik', 'tarik.khattabi29@example.com', '$2y$10$abcdefghijklmnopqrstuv', FALSE),
(3, 'Mezzour', 'Houda', 'houda.mezzour30@example.com', '$2y$10$abcdefghijklmnopqrstuv', TRUE);