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
INSERT INTO clients (nom, prenom, email, telephone, adresse) VALUES
  ('Hajji',      'Mohamed',  'mohamed.hajji@gmail.com',    '0661234567', '12 Rue Ibn Batouta, Casablanca'),
  ('Ouali',      'Amina',    'amina.ouali@hotmail.com',    '0672345678', '34 Avenue Hassan II, Rabat'),
  ('Berrada',    'Khalid',   'khalid.berrada@gmail.com',   '0683456789', '5 Rue de Fès, Marrakech'),
  ('Lahrichi',   'Houda',    'houda.lahrichi@yahoo.fr',    '0694567890', '78 Boulevard Mohammed V, Tanger'),
  ('Ennaji',     'Rachid',   'rachid.ennaji@gmail.com',    '0651234567', '22 Rue Al Massira, Agadir'),
  ('Ziani',      'Latifa',   'latifa.ziani@outlook.com',   '0662345678', '9 Rue Oqba, Fès'),
  ('Tahiri',     'Saad',     'saad.tahiri@gmail.com',      '0673456789', '15 Avenue de la Résistance, Meknès'),
  ('Benkiran',   'Souad',    'souad.benkiran@gmail.com',   '0684567890', '3 Rue des Orangers, Oujda'),
  ('El Fassi',   'Hassan',   'hassan.elfassi@hotmail.com', '0695678901', '101 Boulevard Zerktouni, Casablanca'),
  ('Mouhib',     'Rim',      'rim.mouhib@gmail.com',       '0656789012', '47 Rue Tariq Ibn Ziad, Rabat');
 