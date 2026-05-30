-- ============================================================
-- SEED DATA — Application de gestion des réclamations clients
-- ============================================================
-- Ordre d'insertion respectant les FK
-- Roles, Priorités et Statuts sont déjà insérés dans le schéma
-- ============================================================

USE reclamations;

-- ============================================================
-- categories_reclamation (30 lignes)
-- ============================================================
INSERT INTO categories_reclamation (libelle, description) VALUES
  ('Facturation',                   'Erreurs ou contestations sur les factures'),
  ('Livraison',                     'Problèmes liés à la livraison de commande'),
  ('Qualité produit',               'Produit défectueux ou non conforme'),
  ('Service après-vente',           'Insatisfaction suite à une intervention SAV'),
  ('Remboursement',                 'Demande de remboursement non traitée'),
  ('Délai de livraison',            'Livraison hors délai annoncé'),
  ('Produit manquant',              'Article absent du colis reçu'),
  ('Produit endommagé',             'Article arrivé abîmé'),
  ('Erreur de commande',            'Mauvais article expédié'),
  ('Annulation commande',           'Commande annulée sans accord client'),
  ('Compte client',                 'Problème d''accès ou données du compte'),
  ('Paiement en ligne',             'Échec ou double débit lors du paiement'),
  ('Offre promotionnelle',          'Promotion non appliquée'),
  ('Communication commerciale',     'Messages non sollicités ou erreurs d''info'),
  ('Service client',                'Mauvaise prise en charge par un agent'),
  ('Garantie produit',              'Mise en jeu de garantie refusée'),
  ('Retour marchandise',            'Retour non accepté ou non remboursé'),
  ('Transport / Transporteur',      'Incident avec le prestataire transport'),
  ('Conformité réglementaire',      'Non-respect d''une obligation légale'),
  ('Confidentialité des données',   'Usage non autorisé des données personnelles'),
  ('Application mobile',            'Dysfonctionnement de l''application'),
  ('Site web',                      'Erreur ou bug sur le site internet'),
  ('Abonnement',                    'Problème lié à un abonnement actif'),
  ('Résiliation',                   'Difficultés lors de la résiliation'),
  ('Devis / Estimation',            'Devis erroné ou non respecté'),
  ('Installation / Mise en service','Problème lors de l''installation du produit'),
  ('Formation / Documentation',     'Manque de documentation ou formation'),
  ('Accessibilité',                 'Difficultés d''accès au service pour PMR'),
  ('Environnement / Durabilité',    'Préoccupations environnementales'),
  ('Autre',                         'Catégorie générique pour cas non listés');

-- ============================================================
-- clients (30 lignes)
-- ============================================================
INSERT INTO clients (nom, prenom, email, telephone, adresse) VALUES
  ('Benali',       'Youssef',    'youssef.benali@email.ma',      '0661234567', '12 Rue Hassan II, Casablanca'),
  ('El Fassi',     'Fatima',     'fatima.elfassi@email.ma',      '0662345678', '34 Avenue Mohammed V, Rabat'),
  ('Chraibi',      'Karim',      'karim.chraibi@email.ma',       '0663456789', '7 Rue Moulay Ismail, Fès'),
  ('Kettani',      'Nadia',      'nadia.kettani@email.ma',       '0664567890', '23 Boulevard Zerktouni, Casablanca'),
  ('Tahiri',       'Omar',       'omar.tahiri@email.ma',         '0665678901', '5 Rue de la Paix, Marrakech'),
  ('Amrani',       'Salma',      'salma.amrani@email.ma',        '0666789012', '89 Avenue Hassan II, Agadir'),
  ('Boussouf',     'Rachid',     'rachid.boussouf@email.ma',     '0667890123', '14 Rue Ibn Battouta, Tanger'),
  ('Lahlou',       'Zineb',      'zineb.lahlou@email.ma',        '0668901234', '56 Rue Oqba, Oujda'),
  ('Guerraoui',    'Mehdi',      'mehdi.guerraoui@email.ma',     '0669012345', '3 Impasse des Oliviers, Meknès'),
  ('Ziani',        'Houda',      'houda.ziani@email.ma',         '0670123456', '22 Rue Al Massira, Tétouan'),
  ('Barakat',      'Amine',      'amine.barakat@email.ma',       '0671234568', '11 Rue Imam Malik, Kénitra'),
  ('Rhoulam',      'Soukaina',   'soukaina.rhoulam@email.ma',    '0672345679', '67 Avenue des FAR, Safi'),
  ('Mernissi',     'Hassan',     'hassan.mernissi@email.ma',     '0673456780', '8 Rue Cadi Ayyad, Béni Mellal'),
  ('Ouazzani',     'Lamia',      'lamia.ouazzani@email.ma',      '0674567891', '45 Boulevard Panorama, Ifrane'),
  ('Belkadi',      'Saad',       'saad.belkadi@email.ma',        '0675678902', '29 Rue du Commerce, El Jadida'),
  ('Hajji',        'Rim',        'rim.hajji@email.ma',           '0676789013', '77 Avenue Bir Anzarane, Laâyoune'),
  ('Filali',       'Khalid',     'khalid.filali@email.ma',       '0677890124', '1 Rue de l''Atlas, Azrou'),
  ('Drissi',       'Meryem',     'meryem.drissi@email.ma',       '0678901235', '33 Rue Tariq Ibn Ziyad, Nador'),
  ('Benhaddou',    'Tarik',      'tarik.benhaddou@email.ma',     '0679012346', '18 Rue Allal Ben Abdellah, Settat'),
  ('Skalli',       'Imane',      'imane.skalli@email.ma',        '0680123457', '60 Avenue Mohammed VI, Taza'),
  ('Alaoui',       'Yassine',    'yassine.alaoui@email.ma',      '0681234569', '9 Rue Bir Inzrane, Guelmim'),
  ('Berrada',      'Samira',     'samira.berrada@email.ma',      '0682345670', '41 Rue Al Hansali, Khénifra'),
  ('Cherkaoui',    'Bilal',      'bilal.cherkaoui@email.ma',     '0683456781', '15 Rue Driss Slaoui, Casablanca'),
  ('Boutaleb',     'Karima',     'karima.boutaleb@email.ma',     '0684567892', '52 Rue des Acacias, Rabat'),
  ('Mouline',      'Issam',      'issam.mouline@email.ma',       '0685678903', '6 Rue El Mansour, Marrakech'),
  ('Hasnaoui',     'Nisrine',    'nisrine.hasnaoui@email.ma',    '0686789014', '38 Avenue Hassan I, Fès'),
  ('El Idrissi',   'Hamza',      'hamza.elidrissi@email.ma',     '0687890125', '24 Boulevard Anfa, Casablanca'),
  ('Tazi',         'Widad',      'widad.tazi@email.ma',          '0688901236', '71 Rue Abdelmoumen, Rabat'),
  ('Sebti',        'Noureddine', 'noureddine.sebti@email.ma',    '0689012347', '13 Rue Al Wahda, Tanger'),
  ('Bennani',      'Ghita',      'ghita.bennani@email.ma',       '0690123458', '47 Rue Ibn Khaldoun, Agadir');

-- ============================================================
-- utilisateurs (30 lignes — agents, superviseurs, admins)
-- ============================================================
-- role_id: 1=admin, 2=superviseur, 3=agent
-- mot_de_passe: bcrypt hash de "Password123!" (factice pour seed)
INSERT INTO utilisateurs (role_id, nom, prenom, email, mot_de_passe, actif) VALUES
  (1, 'Idrissi',    'Amine',      'admin.amine@reclamations.ma',       '$2b$12$KIXXYhashFakeAdmin001', TRUE),
  (1, 'Chafik',     'Sara',       'admin.sara@reclamations.ma',        '$2b$12$KIXXYhashFakeAdmin002', TRUE),
  (2, 'Oukhouya',   'Rachid',     'sup.rachid@reclamations.ma',        '$2b$12$KIXXYhashFakeSup001',   TRUE),
  (2, 'Benmoussa',  'Loubna',     'sup.loubna@reclamations.ma',        '$2b$12$KIXXYhashFakeSup002',   TRUE),
  (2, 'El Khamlichi','Driss',     'sup.driss@reclamations.ma',         '$2b$12$KIXXYhashFakeSup003',   TRUE),
  (3, 'Mansouri',   'Kenza',      'agent.kenza@reclamations.ma',       '$2b$12$KIXXYhashFakeAgt001',   TRUE),
  (3, 'Bensouda',   'Youssef',    'agent.youssef@reclamations.ma',     '$2b$12$KIXXYhashFakeAgt002',   TRUE),
  (3, 'Haddaoui',   'Asmae',      'agent.asmae@reclamations.ma',       '$2b$12$KIXXYhashFakeAgt003',   TRUE),
  (3, 'Laâziri',    'Hamid',      'agent.hamid@reclamations.ma',       '$2b$12$KIXXYhashFakeAgt004',   TRUE),
  (3, 'Chraibi',    'Meriem',     'agent.meriem@reclamations.ma',      '$2b$12$KIXXYhashFakeAgt005',   TRUE),
  (3, 'Bouazza',    'Soufiane',   'agent.soufiane@reclamations.ma',    '$2b$12$KIXXYhashFakeAgt006',   TRUE),
  (3, 'El Ouafi',   'Fatna',      'agent.fatna@reclamations.ma',       '$2b$12$KIXXYhashFakeAgt007',   TRUE),
  (3, 'Tahir',      'Badr',       'agent.badr@reclamations.ma',        '$2b$12$KIXXYhashFakeAgt008',   TRUE),
  (3, 'Serhane',    'Nadia',      'agent.nadia@reclamations.ma',       '$2b$12$KIXXYhashFakeAgt009',   TRUE),
  (3, 'Alami',      'Othmane',    'agent.othmane@reclamations.ma',     '$2b$12$KIXXYhashFakeAgt010',   TRUE),
  (3, 'Bertal',     'Zineb',      'agent.zineb@reclamations.ma',       '$2b$12$KIXXYhashFakeAgt011',   TRUE),
  (3, 'Kettani',    'Ayoub',      'agent.ayoub@reclamations.ma',       '$2b$12$KIXXYhashFakeAgt012',   TRUE),
  (3, 'Ghazali',    'Imane',      'agent.imane@reclamations.ma',       '$2b$12$KIXXYhashFakeAgt013',   FALSE),
  (3, 'Rifai',      'Khalid',     'agent.khalid@reclamations.ma',      '$2b$12$KIXXYhashFakeAgt014',   TRUE),
  (3, 'Ouali',      'Sanae',      'agent.sanae@reclamations.ma',       '$2b$12$KIXXYhashFakeAgt015',   TRUE),
  (3, 'Bakkali',    'Rachid',     'agent.rachid@reclamations.ma',      '$2b$12$KIXXYhashFakeAgt016',   TRUE),
  (3, 'Lamrani',    'Houda',      'agent.houda@reclamations.ma',       '$2b$12$KIXXYhashFakeAgt017',   TRUE),
  (3, 'Squalli',    'Tariq',      'agent.tariq@reclamations.ma',       '$2b$12$KIXXYhashFakeAgt018',   TRUE),
  (3, 'Raji',       'Fatima',     'agent.fatima@reclamations.ma',      '$2b$12$KIXXYhashFakeAgt019',   TRUE),
  (3, 'Zaid',       'Anas',       'agent.anas@reclamations.ma',        '$2b$12$KIXXYhashFakeAgt020',   TRUE),
  (2, 'Farsi',      'Ghita',      'sup.ghita@reclamations.ma',         '$2b$12$KIXXYhashFakeSup004',   TRUE),
  (3, 'Chaara',     'Samir',      'agent.samir@reclamations.ma',       '$2b$12$KIXXYhashFakeAgt021',   TRUE),
  (3, 'Elhabti',    'Wissam',     'agent.wissam@reclamations.ma',      '$2b$12$KIXXYhashFakeAgt022',   TRUE),
  (3, 'Naji',       'Laila',      'agent.laila@reclamations.ma',       '$2b$12$KIXXYhashFakeAgt023',   TRUE),
  (3, 'Belghazi',   'Yassir',     'agent.yassir@reclamations.ma',      '$2b$12$KIXXYhashFakeAgt024',   TRUE);

-- ============================================================
-- reclamations (30 lignes)
-- ============================================================
-- client_id 1-30 | categorie_id 1-30 | priorite_id 1-4
-- statut_id: 1=NOUVELLE 2=ATTENTE_AFF 3=AFFECTEE 4=EN_COURS 5=ATTENTE_INFO 6=RESOLUE 7=CLOTUREE 8=REJETEE
-- agent_id: utilisateurs rôle agent (id 6-30 selon seed ci-dessus)
INSERT INTO reclamations
  (numero_unique, client_id, categorie_id, priorite_id, statut_id, agent_id, objet, description, created_at, closed_at)
VALUES
  ('REC-20240001',  1,  1,  3,  7,  6,  'Facture incorrecte – doublon de prélèvement',
   'J''ai été prélevé deux fois pour la même facture du mois de janvier. Le montant de 850 MAD a été débité deux fois.', '2024-01-05 08:30:00', '2024-01-15 14:00:00'),

  ('REC-20240002',  2,  2,  2,  7,  7,  'Colis non livré après 10 jours',
   'Ma commande #CMD-9921 n''est toujours pas arrivée alors que le délai annoncé était de 3 à 5 jours ouvrés.', '2024-01-07 10:15:00', '2024-01-20 09:00:00'),

  ('REC-20240003',  3,  3,  4,  6,  8,  'Produit défectueux dès la première utilisation',
   'Le mixeur reçu ne fonctionne pas du tout. Aucune réaction lors de la mise sous tension.', '2024-01-10 11:00:00', NULL),

  ('REC-20240004',  4,  5,  3,  4,  9,  'Remboursement non reçu après 3 semaines',
   'Suite au retour de mon article le 20 décembre, je n''ai toujours pas reçu mon remboursement de 1200 MAD.', '2024-01-12 14:30:00', NULL),

  ('REC-20240005',  5,  12, 4,  4, 10,  'Double débit lors du paiement en ligne',
   'Mon compte bancaire a été débité deux fois lors de ma commande. Référence transaction : TXN-44821.', '2024-01-15 09:45:00', NULL),

  ('REC-20240006',  6,  6,  2,  7,  6,  'Livraison hors délai – 15 jours de retard',
   'La livraison promise sous 48 h a pris 15 jours ouvrés. Cela m''a causé un préjudice professionnel.', '2024-01-17 16:00:00', '2024-02-03 11:00:00'),

  ('REC-20240007',  7,  7,  2,  6, 11,  'Article manquant dans le colis',
   'J''ai commandé 3 articles mais n''en ai reçu que 2. Il manque la lampe de bureau référence LB-2024.', '2024-01-20 13:20:00', NULL),

  ('REC-20240008',  8,  8,  3,  5, 12,  'Écran du téléphone fissuré à la livraison',
   'Le smartphone commandé est arrivé avec l''écran fissuré malgré l''emballage intact en apparence.', '2024-01-22 09:10:00', NULL),

  ('REC-20240009',  9,  4,  2,  7,  7,  'Technicien SAV impoli et intervention incomplète',
   'Le technicien envoyé pour réparer ma climatisation est parti sans terminer le travail et a été irrespectueux.', '2024-01-24 15:30:00', '2024-02-10 10:00:00'),

  ('REC-20240010', 10,  9,  3,  4,  8,  'Mauvais produit expédié',
   'J''ai commandé un aspirateur modèle X300 et reçu un modèle X100 de gamme inférieure.', '2024-01-26 11:00:00', NULL),

  ('REC-20240011', 11, 13,  1,  7,  9,  'Code promo non appliqué sur commande',
   'Le code promotionnel WINTER20 n''a pas été déduit de mon panier malgré son application lors du checkout.', '2024-01-28 08:45:00', '2024-02-05 16:00:00'),

  ('REC-20240012', 12, 11,  2,  3, 10,  'Impossible de se connecter à mon compte',
   'Depuis la mise à jour du site, je ne peux plus accéder à mon espace client. Mot de passe refusé systématiquement.', '2024-01-30 10:30:00', NULL),

  ('REC-20240013', 13, 16,  3,  4, 11,  'Garantie refusée sans justification',
   'Mon lave-linge est tombé en panne à 8 mois d''utilisation. Le SAV refuse de prendre en charge sous garantie.', '2024-02-01 14:00:00', NULL),

  ('REC-20240014', 14, 17,  2,  6, 12,  'Retour refusé sous prétexte d''emballage ouvert',
   'Le produit n''a jamais été utilisé mais le retour est refusé car la boîte a été ouverte pour inspection.', '2024-02-03 09:30:00', NULL),

  ('REC-20240015', 15, 18,  4,  4, 13,  'Transporteur a livré chez le mauvais destinataire',
   'Le livreur a déposé mon colis chez un voisin sans me prévenir. Impossible de le récupérer depuis.', '2024-02-05 11:15:00', NULL),

  ('REC-20240016', 16,  1,  2,  7, 14,  'Erreur de montant sur facture d''abonnement',
   'Ma facture mensuelle affiche 349 MAD au lieu des 199 MAD prévus au contrat.', '2024-02-07 08:00:00', '2024-02-20 15:00:00'),

  ('REC-20240017', 17, 23,  3,  8, NULL, 'Résiliation abonnement impossible en ligne',
   'Le bouton de résiliation est grisé et le service client ne répond pas à mes demandes par email.', '2024-02-09 13:40:00', '2024-02-28 12:00:00'),

  ('REC-20240018', 18, 20,  4,  5, 15,  'Données personnelles partagées sans consentement',
   'J''ai reçu des communications commerciales de partenaires sans avoir donné mon accord. RGPD non respecté.', '2024-02-11 10:00:00', NULL),

  ('REC-20240019', 19, 21,  2,  3, 16,  'Application mobile plante au lancement',
   'L''application plante systématiquement dès l''ouverture sur Android 13. Testé sur deux appareils différents.', '2024-02-13 16:20:00', NULL),

  ('REC-20240020', 20, 15,  3,  4, 17,  'Agent de service client agressif au téléphone',
   'Lors de mon appel du 10/02, l''agent m''a raccroché au nez après avoir haussé le ton sans raison.', '2024-02-15 14:10:00', NULL),

  ('REC-20240021', 21,  2,  2,  6, 18,  'Colis perdu lors du transport',
   'Aucune nouvelle de ma commande depuis 20 jours. Le numéro de suivi ne retourne aucun résultat.', '2024-02-17 09:00:00', NULL),

  ('REC-20240022', 22, 10,  3,  7, 19,  'Commande annulée sans accord de ma part',
   'Ma commande a été annulée unilatéralement sans notification. Je l''ai découvert en vérifiant mon espace client.', '2024-02-19 11:30:00', '2024-03-01 10:00:00'),

  ('REC-20240023', 23,  3,  4,  4, 20,  'Batterie du laptop gonfle après 2 mois',
   'La batterie de mon ordinateur portable s''est gonflée, ce qui soulève le clavier. Produit potentiellement dangereux.', '2024-02-21 08:30:00', NULL),

  ('REC-20240024', 24,  6,  1,  7, 21,  'Délai de livraison dépassé de 2 jours',
   'Ma commande est arrivée avec 2 jours de retard par rapport au délai contractuel. Demande de geste commercial.', '2024-02-23 15:00:00', '2024-03-05 09:00:00'),

  ('REC-20240025', 25, 14,  2,  8, NULL, 'Emails publicitaires reçus après désinscription',
   'Malgré ma désinscription effectuée il y a 3 semaines, je continue à recevoir des emails promotionnels.', '2024-02-25 10:45:00', '2024-03-10 14:00:00'),

  ('REC-20240026', 26, 26,  3,  5, 22,  'Produit impossible à installer – notice incompréhensible',
   'La notice d''installation fournie est en anglais uniquement et comporte des erreurs de schéma. Impossible d''installer.', '2024-02-27 13:00:00', NULL),

  ('REC-20240027', 27, 22,  2,  4, 23,  'Retard de traitement réclamation précédente',
   'Ma réclamation REC-20231155 est sans réponse depuis 45 jours. Je relance formellement.', '2024-03-01 09:15:00', NULL),

  ('REC-20240028', 28,  5,  4,  4, 24,  'Remboursement partiel inexpliqué',
   'J''ai reçu un remboursement de 300 MAD alors que le montant total était de 750 MAD. Aucune explication fournie.', '2024-03-03 11:00:00', NULL),

  ('REC-20240029', 29, 25,  3,  3, 25,  'Devis reçu ne correspond pas au tarif convenu',
   'Le devis envoyé affiche 4 500 MAD alors que le commercial nous avait annoncé 3 800 MAD lors de la visite.', '2024-03-05 14:30:00', NULL),

  ('REC-20240030', 30, 19,  2,  1,  NULL, 'Demande d''information sur conformité contrat',
   'Je souhaite vérifier que mon contrat est conforme à la réglementation marocaine en vigueur sur la protection du consommateur.', '2024-03-07 10:00:00', NULL);

-- ============================================================
-- affectations (30 lignes)
-- ============================================================
-- affecte_par: superviseurs (id 3,4,5,26)
INSERT INTO affectations (reclamation_id, utilisateur_id, affecte_par, note, created_at) VALUES
  ( 1,  6, 3, 'Priorité haute – client fidèle depuis 3 ans.', '2024-01-05 09:00:00'),
  ( 2,  7, 4, 'Vérifier avec le transporteur.', '2024-01-07 11:00:00'),
  ( 3,  8, 3, 'Produit critique – escalader si non résolu sous 24 h.', '2024-01-10 11:30:00'),
  ( 4,  9, 5, 'Contacter la comptabilité pour le remboursement.', '2024-01-12 15:00:00'),
  ( 5, 10, 4, 'Cas de double débit – coordination banque requise.', '2024-01-15 10:15:00'),
  ( 6,  6, 3, 'Analyser le bon de livraison et contacter transporteur.', '2024-01-17 16:30:00'),
  ( 7, 11, 5, 'Vérifier stock et expédier l''article manquant.', '2024-01-20 13:45:00'),
  ( 8, 12, 4, 'Photos demandées au client pour dossier assurance.', '2024-01-22 09:30:00'),
  ( 9,  7, 3, 'Convoquer le technicien pour retour d''expérience.', '2024-01-24 16:00:00'),
  (10,  8, 5, 'Organiser échange produit – coordonner logistique.', '2024-01-26 11:30:00'),
  (11,  9, 4, 'Vérifier validité du code promo dans le système.', '2024-01-28 09:00:00'),
  (12, 10, 3, 'Contacter équipe technique pour réinitialisation.', '2024-01-30 11:00:00'),
  (13, 11, 5, 'Récupérer le bon de garantie et rapport technique.', '2024-02-01 14:30:00'),
  (14, 12, 4, 'Vérifier politique de retour et cas applicable.', '2024-02-03 10:00:00'),
  (15, 13, 3, 'Contacter transporteur – ouvrir litige transport.', '2024-02-05 11:45:00'),
  (16, 14, 5, 'Corriger facturation – geste commercial à valider.', '2024-02-07 08:30:00'),
  (18, 15, 4, 'Transmettre au DPO – traitement RGPD obligatoire.', '2024-02-11 10:30:00'),
  (19, 16, 3, 'Transmettre au département IT pour investigation.', '2024-02-13 16:45:00'),
  (20, 17, 5, 'Récupérer enregistrement appel – évaluer agent.', '2024-02-15 14:30:00'),
  (21, 18, 4, 'Ouvrir enquête transporteur – délai 5 jours.', '2024-02-17 09:30:00'),
  (22, 19, 3, 'Vérifier stock au moment de l''annulation.', '2024-02-19 12:00:00'),
  (23, 20, 5, 'URGENT – risque sécurité – escalader direction.', '2024-02-21 09:00:00'),
  (24, 21, 4, 'Appliquer geste commercial standard 48 h dépassé.', '2024-02-23 15:30:00'),
  (26, 22, 3, 'Demander notice en français au fournisseur.', '2024-02-27 13:30:00'),
  (27, 23, 5, 'Retrouver dossier REC-20231155 et relancer agent.', '2024-03-01 09:45:00'),
  (28, 24, 4, 'Vérifier calcul remboursement avec service compta.', '2024-03-03 11:30:00'),
  (29, 25, 3, 'Contacter commercial pour vérification tarif.', '2024-03-05 15:00:00'),
  -- réaffectations (historique)
  ( 3, 20, 3, 'Réaffectation suite à charge de travail de l''agent initial.', '2024-01-11 10:00:00'),
  ( 5, 17, 4, 'Réaffectation – agent spécialisé paiement.', '2024-01-16 09:00:00'),
  (13, 22, 5, 'Réaffectation – agent spécialisé garantie produit.', '2024-02-02 08:00:00');

-- ============================================================
-- commentaires (30 lignes)
-- ============================================================
INSERT INTO commentaires (reclamation_id, auteur_id, client_id, contenu, interne, created_at) VALUES
  ( 1, NULL,  1, 'Bonjour, je confirme bien avoir été prélevé deux fois. Je joins mes relevés bancaires.', FALSE, '2024-01-05 10:00:00'),
  ( 1,  6,  NULL, 'Réclamation reçue. Vérification en cours auprès du service comptabilité.', FALSE, '2024-01-06 09:00:00'),
  ( 1,  6,  NULL, 'Note interne : doublon confirmé côté système. Remboursement à lancer.', TRUE, '2024-01-08 14:00:00'),
  ( 2, NULL,  2, 'Toujours aucune nouvelle de mon colis. Le numéro de suivi ne fonctionne pas.', FALSE, '2024-01-09 11:00:00'),
  ( 2,  7,  NULL, 'Litige ouvert auprès du transporteur. Réponse attendue sous 72 h.', FALSE, '2024-01-10 09:30:00'),
  ( 3, NULL,  3, 'Le mixeur ne démarre pas du tout. J''ai vérifié la prise secteur, tout est normal.', FALSE, '2024-01-10 12:00:00'),
  ( 3,  8,  NULL, 'Note interne : produit rappelé lot B-2024. Vérifier batch client.', TRUE, '2024-01-11 10:00:00'),
  ( 4, NULL,  4, 'Cela fait 3 semaines que j''attends mon remboursement. C''est inacceptable.', FALSE, '2024-01-13 15:00:00'),
  ( 4,  9,  NULL, 'Dossier transmis à la comptabilité. Le remboursement sera effectué sous 5 jours ouvrés.', FALSE, '2024-01-14 10:00:00'),
  ( 5, NULL,  5, 'J''ai contacté ma banque. Ils confirment deux débits distincts le même jour.', FALSE, '2024-01-16 08:00:00'),
  ( 5, 10,  NULL, 'En attente du relevé bancaire complet du client pour traitement.', FALSE, '2024-01-17 09:00:00'),
  ( 6, NULL,  6, 'Le retard m''a coûté un contrat client. Je souhaite une indemnisation.', FALSE, '2024-01-18 14:00:00'),
  ( 7, NULL,  7, 'Je peux envoyer une photo du colis si cela peut aider.', FALSE, '2024-01-21 09:00:00'),
  ( 7, 11,  NULL, 'Stock vérifié. L''article manquant est disponible. Expédition prévue demain.', FALSE, '2024-01-22 10:00:00'),
  ( 8, NULL,  8, 'Je vous envoie les photos de l''écran fissuré par email.', FALSE, '2024-01-23 11:00:00'),
  ( 8, 12,  NULL, 'Photos reçues. Dossier assurance transport ouvert. Décision sous 10 jours.', FALSE, '2024-01-24 09:00:00'),
  ( 9, NULL,  9, 'La climatisation n''est toujours pas réparée. Je veux qu''un autre technicien vienne.', FALSE, '2024-01-25 16:00:00'),
  (10, NULL, 10, 'J''ai besoin du bon produit d''urgence. Puis-je l''avoir en retrait en magasin ?', FALSE, '2024-01-27 12:00:00'),
  (10,  8,  NULL, 'Échange autorisé en magasin. Bon de retrait envoyé par email au client.', FALSE, '2024-01-28 10:00:00'),
  (13, NULL, 13, 'La panne est apparue à 8 mois d''utilisation. La garantie est de 24 mois selon le contrat.', FALSE, '2024-02-02 15:00:00'),
  (13, 11,  NULL, 'Note interne : vérifier la date d''achat et les CGV garantie.', TRUE, '2024-02-03 09:00:00'),
  (15, NULL, 15, 'Mon voisin refuse de me remettre le colis. Que puis-je faire ?', FALSE, '2024-02-06 10:00:00'),
  (15, 13,  NULL, 'Nous avons contacté le transporteur. Une procédure de récupération est lancée.', FALSE, '2024-02-07 11:00:00'),
  (18, NULL, 18, 'Je souhaite un accusé de réception de ma réclamation RGPD dans les meilleurs délais.', FALSE, '2024-02-12 09:00:00'),
  (18, 15,  NULL, 'Dossier transmis au Délégué à la Protection des Données. Traitement sous 30 jours.', FALSE, '2024-02-13 10:00:00'),
  (20, NULL, 20, 'Je voudrais écouter l''enregistrement de l''appel pour preuve.', FALSE, '2024-02-16 08:30:00'),
  (23, NULL, 23, 'La batterie a encore gonflé. J''ai peur d''un incendie. C''est urgent !', FALSE, '2024-02-22 07:45:00'),
  (23, 20,  NULL, 'Note interne : URGENT – retrait produit recommandé. Contacter le client immédiatement.', TRUE, '2024-02-22 08:30:00'),
  (26, NULL, 26, 'La notice en français serait vraiment appréciée. Merci par avance.', FALSE, '2024-02-28 11:00:00'),
  (28, NULL, 28, 'Pouvez-vous m''expliquer le calcul du remboursement partiel reçu ?', FALSE, '2024-03-04 10:30:00');

-- ============================================================
-- pieces_jointes (30 lignes)
-- ============================================================
INSERT INTO pieces_jointes (reclamation_id, nom_fichier, chemin, type_mime, taille) VALUES
  ( 1, 'releve_bancaire_janvier.pdf',    '/uploads/rec-1/releve_bancaire_janvier.pdf',     'application/pdf',  204800),
  ( 1, 'capture_double_debit.png',       '/uploads/rec-1/capture_double_debit.png',         'image/png',         87040),
  ( 2, 'confirmation_commande.pdf',      '/uploads/rec-2/confirmation_commande.pdf',         'application/pdf',  153600),
  ( 3, 'photo_mixeur_defaut.jpg',        '/uploads/rec-3/photo_mixeur_defaut.jpg',           'image/jpeg',        65536),
  ( 3, 'facture_achat_mixeur.pdf',       '/uploads/rec-3/facture_achat_mixeur.pdf',           'application/pdf',  102400),
  ( 4, 'bon_retour_article.pdf',         '/uploads/rec-4/bon_retour_article.pdf',             'application/pdf',  118784),
  ( 5, 'releve_bancaire_double.pdf',     '/uploads/rec-5/releve_bancaire_double.pdf',         'application/pdf',  225280),
  ( 6, 'bon_livraison_signe.pdf',        '/uploads/rec-6/bon_livraison_signe.pdf',             'application/pdf',  143360),
  ( 7, 'photo_colis_rec7.jpg',           '/uploads/rec-7/photo_colis_rec7.jpg',               'image/jpeg',        53248),
  ( 8, 'photo_ecran_fissure1.jpg',       '/uploads/rec-8/photo_ecran_fissure1.jpg',           'image/jpeg',        92160),
  ( 8, 'photo_ecran_fissure2.jpg',       '/uploads/rec-8/photo_ecran_fissure2.jpg',           'image/jpeg',        88064),
  ( 8, 'photo_emballage_intact.jpg',     '/uploads/rec-8/photo_emballage_intact.jpg',         'image/jpeg',        76800),
  ( 9, 'rapport_intervention_sav.pdf',   '/uploads/rec-9/rapport_intervention_sav.pdf',       'application/pdf',  163840),
  (10, 'photo_mauvais_produit.jpg',      '/uploads/rec-10/photo_mauvais_produit.jpg',         'image/jpeg',        71680),
  (10, 'bon_commande_aspirateur.pdf',    '/uploads/rec-10/bon_commande_aspirateur.pdf',       'application/pdf',  112640),
  (12, 'capture_erreur_connexion.png',   '/uploads/rec-12/capture_erreur_connexion.png',     'image/png',         45056),
  (13, 'certificat_garantie.pdf',        '/uploads/rec-13/certificat_garantie.pdf',           'application/pdf',  184320),
  (13, 'facture_achat_lavejinge.pdf',    '/uploads/rec-13/facture_achat_lavelinge.pdf',       'application/pdf',  135168),
  (15, 'capture_suivi_colis.png',        '/uploads/rec-15/capture_suivi_colis.png',           'image/png',         61440),
  (16, 'facture_incorrecte_fev.pdf',     '/uploads/rec-16/facture_incorrecte_fev.pdf',       'application/pdf',  122880),
  (16, 'contrat_abonnement.pdf',         '/uploads/rec-16/contrat_abonnement.pdf',             'application/pdf',  196608),
  (18, 'capture_emails_non_sollicites.png', '/uploads/rec-18/capture_emails.png',             'image/png',         54272),
  (20, 'notes_appel_support.docx',       '/uploads/rec-20/notes_appel_support.docx',
   'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 35840),
  (23, 'photo_batterie_gonflee1.jpg',    '/uploads/rec-23/photo_batterie_gonflee1.jpg',     'image/jpeg',        98304),
  (23, 'photo_batterie_gonflee2.jpg',    '/uploads/rec-23/photo_batterie_gonflee2.jpg',     'image/jpeg',        94208),
  (24, 'bon_livraison_date.pdf',         '/uploads/rec-24/bon_livraison_date.pdf',           'application/pdf',  107520),
  (26, 'notice_produit_originale.pdf',   '/uploads/rec-26/notice_produit_originale.pdf',   'application/pdf',  307200),
  (28, 'confirmation_remboursement.pdf', '/uploads/rec-28/confirmation_remboursement.pdf', 'application/pdf',  131072),
  (29, 'devis_recu.pdf',                 '/uploads/rec-29/devis_recu.pdf',                   'application/pdf',  163840),
  (29, 'email_tarif_commercial.pdf',     '/uploads/rec-29/email_tarif_commercial.pdf',       'application/pdf',  114688);

-- ============================================================
-- historique_actions (30 lignes)
-- ============================================================
INSERT INTO historique_actions
  (reclamation_id, utilisateur_id, ancien_statut_id, nouveau_statut_id, action, details, created_at)
VALUES
  ( 1,  NULL, NULL, 1, 'Création réclamation', 'Soumission initiale par le client via le portail web.', '2024-01-05 08:30:00'),
  ( 1,     3,    1, 2, 'Enregistrement réclamation', 'Réclamation vérifiée et mise en attente d''affectation.', '2024-01-05 09:00:00'),
  ( 1,     3,    2, 3, 'Affectation agent', 'Affectée à l''agent Mansouri Kenza (id 6).', '2024-01-05 09:05:00'),
  ( 1,     6,    3, 4, 'Prise en charge', 'Agent a commencé l''analyse du dossier.', '2024-01-06 09:10:00'),
  ( 1,     6,    4, 6, 'Résolution proposée', 'Remboursement de 850 MAD initié.', '2024-01-10 14:00:00'),
  ( 1,     3,    6, 7, 'Clôture dossier', 'Client confirmé remboursement reçu.', '2024-01-15 14:00:00'),
  ( 2,  NULL, NULL, 1, 'Création réclamation', 'Soumission initiale par le client.', '2024-01-07 10:15:00'),
  ( 2,     4,    1, 3, 'Affectation agent', 'Affectée directement à Bensouda Youssef.', '2024-01-07 11:00:00'),
  ( 2,     7,    3, 4, 'Prise en charge', 'Litige ouvert auprès du transporteur.', '2024-01-08 09:00:00'),
  ( 2,     7,    4, 6, 'Résolution', 'Colis retrouvé et livré. Client informé.', '2024-01-18 15:00:00'),
  ( 2,     4,    6, 7, 'Clôture', 'Dossier archivé après confirmation client.', '2024-01-20 09:00:00'),
  ( 3,  NULL, NULL, 1, 'Création réclamation', 'Réclamation soumise en ligne.', '2024-01-10 11:00:00'),
  ( 3,     3,    1, 3, 'Affectation agent', 'Assignée à Haddaoui Asmae.', '2024-01-10 11:30:00'),
  ( 3,     3,    3, 3, 'Réaffectation', 'Transféré à Raji Fatima suite surcharge.', '2024-01-11 10:00:00'),
  ( 3,    20,    3, 4, 'Prise en charge', 'Analyse lot produit en cours.', '2024-01-11 11:00:00'),
  ( 3,    20,    4, 6, 'Résolution', 'Remplacement produit expédié au client.', '2024-01-20 14:00:00'),
  ( 5,  NULL, NULL, 1, 'Création réclamation', 'Double débit signalé.', '2024-01-15 09:45:00'),
  ( 5,     4,    1, 3, 'Affectation', 'Assignée à Chraibi Meriem.', '2024-01-15 10:15:00'),
  ( 5,     4,    3, 3, 'Réaffectation', 'Transféré à Lamrani Houda spécialisée paiement.', '2024-01-16 09:00:00'),
  ( 5,    17,    3, 5, 'En attente d''info', 'Relevé bancaire complet demandé au client.', '2024-01-17 09:00:00'),
  (17,  NULL, NULL, 1, 'Création réclamation', 'Résiliation impossible signalée.', '2024-02-09 13:40:00'),
  (17,     5,    1, 8, 'Rejet réclamation', 'La demande de résiliation ne respecte pas le délai contractuel de préavis.', '2024-02-28 12:00:00'),
  (23,  NULL, NULL, 1, 'Création réclamation', 'Batterie gonflée signalée – risque sécurité.', '2024-02-21 08:30:00'),
  (23,     5,    1, 3, 'Affectation URGENTE', 'Escaladé à Raji Fatima – agent sécurité produit.', '2024-02-21 09:00:00'),
  (23,    20,    3, 4, 'Prise en charge', 'Contact client établi – retrait produit planifié.', '2024-02-22 08:30:00'),
  (18,  NULL, NULL, 1, 'Création réclamation', 'Violation RGPD signalée.', '2024-02-11 10:00:00'),
  (18,     4,    1, 3, 'Affectation', 'Transmis à l''agent spécialisé conformité.', '2024-02-11 10:30:00'),
  (18,    15,    3, 5, 'Attente d''info', 'Preuves supplémentaires demandées au client.', '2024-02-14 11:00:00'),
  (29,  NULL, NULL, 1, 'Création réclamation', 'Écart de devis signalé.', '2024-03-05 14:30:00'),
  (29,     3,    1, 3, 'Affectation', 'Assignée à Zaid Anas.', '2024-03-05 15:00:00');

-- ============================================================
-- notifications (30 lignes)
-- ============================================================
INSERT INTO notifications (utilisateur_id, reclamation_id, type, message, lu, created_at) VALUES
  ( 6,  1, 'AFFECTATION', 'La réclamation REC-20240001 vous a été affectée.',                             TRUE,  '2024-01-05 09:05:00'),
  ( 7,  2, 'AFFECTATION', 'La réclamation REC-20240002 vous a été affectée.',                             TRUE,  '2024-01-07 11:00:00'),
  ( 8,  3, 'AFFECTATION', 'La réclamation REC-20240003 vous a été affectée.',                             TRUE,  '2024-01-10 11:30:00'),
  ( 9,  4, 'AFFECTATION', 'La réclamation REC-20240004 vous a été affectée.',                             TRUE,  '2024-01-12 15:00:00'),
  (10,  5, 'AFFECTATION', 'La réclamation REC-20240005 vous a été affectée.',                             TRUE,  '2024-01-15 10:15:00'),
  ( 6,  1, 'STATUT',      'Le statut de REC-20240001 est passé à "Résolue".',                             TRUE,  '2024-01-10 14:00:00'),
  ( 7,  2, 'STATUT',      'Le statut de REC-20240002 est passé à "Résolue".',                             TRUE,  '2024-01-18 15:00:00'),
  ( 3,  1, 'RESOLUTION',  'La réclamation REC-20240001 a été résolue et clôturée.',                       TRUE,  '2024-01-15 14:00:00'),
  ( 4,  2, 'RESOLUTION',  'La réclamation REC-20240002 a été résolue et clôturée.',                       TRUE,  '2024-01-20 09:00:00'),
  (11,  7, 'AFFECTATION', 'La réclamation REC-20240007 vous a été affectée.',                             TRUE,  '2024-01-20 13:45:00'),
  (12,  8, 'AFFECTATION', 'La réclamation REC-20240008 vous a été affectée.',                             FALSE, '2024-01-22 09:30:00'),
  (13, 15, 'AFFECTATION', 'La réclamation REC-20240015 vous a été affectée.',                             FALSE, '2024-02-05 11:45:00'),
  (14, 16, 'AFFECTATION', 'La réclamation REC-20240016 vous a été affectée.',                             TRUE,  '2024-02-07 08:30:00'),
  (15, 18, 'AFFECTATION', 'La réclamation REC-20240018 vous a été affectée. Traitement RGPD requis.',     FALSE, '2024-02-11 10:30:00'),
  (16, 19, 'AFFECTATION', 'La réclamation REC-20240019 vous a été affectée.',                             TRUE,  '2024-02-13 16:45:00'),
  (17, 20, 'AFFECTATION', 'La réclamation REC-20240020 vous a été affectée.',                             TRUE,  '2024-02-15 14:30:00'),
  (18, 21, 'AFFECTATION', 'La réclamation REC-20240021 vous a été affectée.',                             FALSE, '2024-02-17 09:30:00'),
  (19, 22, 'AFFECTATION', 'La réclamation REC-20240022 vous a été affectée.',                             TRUE,  '2024-02-19 12:00:00'),
  (20, 23, 'AFFECTATION', 'URGENT – La réclamation REC-20240023 vous a été affectée. Risque sécurité.',   FALSE, '2024-02-21 09:00:00'),
  (21, 24, 'AFFECTATION', 'La réclamation REC-20240024 vous a été affectée.',                             TRUE,  '2024-02-23 15:30:00'),
  (22, 26, 'AFFECTATION', 'La réclamation REC-20240026 vous a été affectée.',                             FALSE, '2024-02-27 13:30:00'),
  (23, 27, 'AFFECTATION', 'La réclamation REC-20240027 vous a été affectée.',                             FALSE, '2024-03-01 09:45:00'),
  (24, 28, 'AFFECTATION', 'La réclamation REC-20240028 vous a été affectée.',                             FALSE, '2024-03-03 11:30:00'),
  (25, 29, 'AFFECTATION', 'La réclamation REC-20240029 vous a été affectée.',                             FALSE, '2024-03-05 15:00:00'),
  (17,  5, 'AFFECTATION', 'La réclamation REC-20240005 vous a été réaffectée.',                           TRUE,  '2024-01-16 09:00:00'),
  (10,  5, 'STATUT',      'Vous avez été désaffecté(e) de la réclamation REC-20240005.',                  TRUE,  '2024-01-16 09:05:00'),
  ( 3,  3, 'INFO',        'La réclamation REC-20240003 a été réaffectée à un autre agent.',               TRUE,  '2024-01-11 10:05:00'),
  ( 5, 17, 'STATUT',      'La réclamation REC-20240017 a été rejetée.',                                   TRUE,  '2024-02-28 12:05:00'),
  ( 3, 11, 'RESOLUTION',  'La réclamation REC-20240011 a été clôturée avec succès.',                      TRUE,  '2024-02-05 16:05:00'),
  ( 4, 22, 'RESOLUTION',  'La réclamation REC-20240022 a été clôturée avec succès.',                      FALSE, '2024-03-01 10:05:00');

-- ============================================================
-- END OF SEED
-- ============================================================