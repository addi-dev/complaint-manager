-- Tous les donnees sone FAKE et Generer par AI; mot de pass pour tout est 'password'

INSERT IGNORE INTO roles (id, nom) VALUES
(1, 'admin'),
(2, 'superviseur'),
(3, 'agent'),
(4, 'client');

INSERT IGNORE INTO priorites (id, libelle, niveau) VALUES
(1, 'Faible',   1),
(2, 'Normale',  2),
(3, 'Haute',    3),
(4, 'Critique', 4);

INSERT IGNORE INTO statuts (id, code, libelle) VALUES
(1, 'NOUVELLE',            'Nouvelle'),
(2, 'AFFECTEE',            'Affectée'),
(3, 'EN_COURS',            'En cours'),
(4, 'ATTENTE_INFO',        'En attente d\'informations'),
(5, 'RESOLUE',             'Résolue'),
(6, 'CLOTUREE',            'Clôturée'),
(7, 'REJETEE',             'Rejetée'),
(8, 'ATTENTE_AFFECTATION', 'En attente d\'affectation');

INSERT IGNORE INTO categories_reclamation (id, libelle) VALUES
(1,  'Facturation'),
(2,  'Livraison'),
(3,  'Qualité produit'),
(4,  'Service après-vente'),
(5,  'Remboursement'),
(6,  'Délai de livraison'),
(7,  'Produit endommagé'),
(8,  'Erreur de commande'),
(9,  'Compte client'),
(10, 'Paiement en ligne'),
(11, 'Application mobile'),
(12, 'Site web'),
(13, 'Résiliation'),
(14, 'Transport / Transporteur'),
(15, 'Garantie produit');

INSERT INTO utilisateurs (id, nom, prenom, email, mot_de_passe, numero_cin, date_naissance, role_id, actif) VALUES
(1, 'Alami',    'Karim',   'admin@test.com',       '$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'A100001', '1985-03-15', 1, 1),
(2, 'Bennani',  'Salma',   'admin2@test.com',      '$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'A100002', '1988-07-22', 1, 1),
(3, 'Chraibi',  'Omar',    'sup1@test.com',        '$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'A100003', '1990-01-10', 2, 1),
(4, 'Douiri',   'Nadia',   'sup2@test.com',        '$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'A100004', '1992-05-18', 2, 1),
(5, 'El Fassi', 'Youssef', 'agent1@test.com',      '$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'A100005', '1993-09-25', 3, 1),
(6, 'Filali',   'Amina',   'agent2@test.com',      '$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'A100006', '1995-11-03', 3, 1),
(7, 'Guessous', 'Mehdi',   'agent3@test.com',      '$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'A100007', '1994-04-14', 3, 1),
(8, 'Hajji',    'Fatima',  'agent.inactif@test.com','$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'A100008', '1991-08-30', 3, 0);

INSERT INTO clients (id, nom, prenom, email, mot_de_passe, numero_cin, date_naissance, telephone, adresse) VALUES
(1, 'Idrissi',   'Hassan',  'client1@test.com', '$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'B200001', '1987-06-12', '0661234567', '12 Rue Mohammed V, Casablanca'),
(2, 'Jabri',     'Zineb',   'client2@test.com', '$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'B200002', '1992-02-28', '0662345678', '45 Avenue Hassan II, Rabat'),
(3, 'Karimi',    'Rachid',  'client3@test.com', '$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'B200003', '1995-10-07', '0663456789', '8 Rue Ibn Sina, Fès'),
(4, 'Lahlou',    'Samira',  'client4@test.com', '$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'B200004', '1989-12-19', '0664567890', '23 Boulevard Zerktouni, Marrakech'),
(5, 'Mansouri',  'Khalid',  'client5@test.com', '$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'B200005', '1983-04-03', '0665678901', '67 Rue Allal Ben Abdellah, Tanger'),
(6, 'Naciri',    'Houda',   'client6@test.com', '$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'B200006', '1997-08-15', '0666789012', '34 Avenue des FAR, Agadir'),
(7, 'Ouhammou',  'Driss',   'client7@test.com', '$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'B200007', '1990-03-22', '0667890123', '15 Rue Patrice Lumumba, Oujda'),
(8, 'Qacemi',    'Latifa',  'client.deleted@test.com', '$2y$10$niNvwZVXEDJLAf4suoBdneEvPmK1XPvLYWvZH/.7Av13OaM9kiGdW', 'B200008', '1986-11-09', '0668901234', '5 Rue de Fès, Meknès');

UPDATE clients SET deleted_at = NOW() WHERE id = 8;

INSERT INTO reclamations (id, numero_unique, client_id, categorie_id, priorite_id, statut_id, agent_id, objet, description, created_at) VALUES
(1,  'REC-2026000001', 1, 1,  2, 1, NULL, 'Facture incorrecte',              'Le montant facturé ne correspond pas à ma commande. J\'ai été prélevé deux fois pour le même article.',                         '2026-05-01 09:00:00'),
(2,  'REC-2026000002', 2, 2,  3, 8, NULL, 'Colis non livré',                 'Ma commande est marquée comme livrée mais je n\'ai rien reçu. Le livreur n\'a pas sonné.',                                      '2026-05-02 10:30:00'),
(3,  'REC-2026000003', 3, 3,  4, 2, 5,   'Produit défectueux à la réception','J\'ai reçu un produit cassé dans son emballage. La vitre est fissurée et le boîtier est abîmé.',                               '2026-05-03 11:15:00'),
(4,  'REC-2026000004', 4, 4,  3, 3, 6,   'SAV ne répond pas',               'Cela fait 3 semaines que j\'essaie de contacter le service après-vente. Aucune réponse par téléphone ni par email.',             '2026-05-05 14:00:00'),
(5,  'REC-2026000005', 5, 5,  2, 4, 5,   'Remboursement non reçu',          'J\'ai retourné un article il y a 6 semaines. Le remboursement n\'a toujours pas été effectué sur mon compte.',                   '2026-05-07 08:45:00'),
(6,  'REC-2026000006', 1, 6,  1, 5, 6,   'Délai de livraison dépassé',      'La livraison était prévue sous 48h. Nous sommes au 10ème jour et toujours rien. Aucune mise à jour du tracking.',               '2026-05-08 16:20:00'),
(7,  'REC-2026000007', 2, 7,  3, 6, NULL, 'Produit endommagé',              'Le produit est arrivé avec l\'emballage intact mais l\'intérieur était complètement endommagé. Photos disponibles.',              '2026-05-10 09:30:00'),
(8,  'REC-2026000008', 3, 8,  2, 7, 7,   'Mauvaise commande reçue',         'J\'ai commandé un article en taille L et j\'ai reçu un XS. La référence sur le bon de livraison est incorrecte.',              '2026-05-12 11:00:00'),
(9,  'REC-2026000009', 4, 9,  4, 1, NULL, 'Problème de connexion au compte', 'Je n\'arrive plus à me connecter à mon espace client. Le mot de passe oublié ne fonctionne pas.',                              '2026-05-14 13:45:00'),
(10, 'REC-2026000010', 5, 10, 3, 2, 5,   'Paiement refusé sans raison',     'Ma carte bancaire est valide et j\'ai les fonds nécessaires mais le paiement est systématiquement refusé sur votre site.',       '2026-05-15 10:00:00'),
(11, 'REC-2026000011', 6, 11, 2, 8, NULL, 'Application mobile crash',       'L\'application crash dès que j\'essaie d\'accéder à mon historique de commandes. Version iOS 17.4.',                            '2026-05-16 15:30:00'),
(12, 'REC-2026000012', 7, 12, 1, 1, NULL, 'Site web inaccessible',          'Le site web est inaccessible depuis 2 jours. J\'obtiens une erreur 503 à chaque tentative de connexion.',                       '2026-05-17 08:00:00'),
(13, 'REC-2026000013', 1, 13, 4, 3, 6,   'Résiliation non traitée',         'J\'ai demandé la résiliation de mon abonnement il y a 2 mois. Je continue d\'être prélevé chaque mois.',                       '2026-05-18 14:15:00'),
(14, 'REC-2026000014', 2, 14, 3, 5, 7,   'Transporteur irrespectueux',      'Le livreur a jeté mon colis depuis son camion. Il a été rude et irrespectueux quand je lui ai fait la remarque.',               '2026-05-19 11:30:00'),
(15, 'REC-2026000015', 3, 15, 2, 6, 5,   'Garantie refusée abusivement',    'Mon produit est sous garantie (acheté il y a 8 mois) mais le SAV refuse la prise en charge sans justification valable.',         '2026-05-20 09:45:00'),
(16, 'REC-2026000016', 4, 1,  1, 2, 5,   'Double facturation',              'J\'ai été facturé deux fois pour la même commande le même jour. Deux prélèvements identiques sur mon relevé bancaire.',          '2026-05-21 10:20:00'),
(17, 'REC-2026000017', 5, 2,  4, 3, 6,   'Retard livraison express',        'J\'ai payé la livraison express pour recevoir mon colis le lendemain. Il est arrivé 5 jours plus tard.',                        '2026-05-22 14:00:00'),
(18, 'REC-2026000018', 6, 3,  2, 4, 7,   'Article manquant dans le colis',  'J\'ai commandé 3 articles, le colis ne contient que 2. Le bon de livraison indique pourtant 3 articles expédiés.',             '2026-05-23 09:00:00'),
(19, 'REC-2026000019', 7, 4,  3, 5, NULL, 'Photo produit trompeuse',        'Le produit reçu ne ressemble pas du tout aux photos sur le site. Les couleurs et les dimensions sont complètement différentes.',  '2026-05-24 11:15:00'),
(20, 'REC-2026000020', 1, 5,  4, 6, 5,   'Remboursement partiel incorrect', 'Le remboursement reçu est de 45€ alors que j\'avais payé 89€. Aucune explication fournie sur la différence.',                  '2026-05-25 16:00:00');

UPDATE reclamations SET closed_at = '2026-05-28 10:00:00' WHERE statut_id = 6;
UPDATE reclamations SET closed_at = '2026-05-27 14:00:00' WHERE statut_id = 7;

INSERT INTO affectations (reclamation_id, utilisateur_id, affecte_par, note, created_at) VALUES
(3,  5, 1, 'Produit défectueux — priorité haute, traiter rapidement.',        '2026-05-03 12:00:00'),
(4,  6, 3, 'SAV — suivre de près, client très mécontent.',                    '2026-05-05 15:00:00'),
(5,  5, 1, 'Remboursement urgent — 6 semaines de retard.',                    '2026-05-07 09:30:00'),
(6,  6, 3, 'Délai dépassé — contacter le transporteur immédiatement.',        '2026-05-08 17:00:00'),
(8,  7, 1, 'Erreur de commande — vérifier avec l\'entrepôt.',                 '2026-05-12 12:00:00'),
(10, 5, 3, 'Problème paiement — vérifier avec l\'équipe technique.',          '2026-05-15 11:00:00'),
(13, 6, 1, 'Résiliation — vérifier le contrat et traiter immédiatement.',     '2026-05-18 15:00:00'),
(14, 7, 3, 'Transporteur — recueillir les preuves et contacter le partenaire.','2026-05-19 12:30:00'),
(15, 5, 1, 'Garantie — vérifier la date d\'achat et les conditions.',         '2026-05-20 10:30:00'),
(16, 5, 3, 'Double facturation — remonter à la comptabilité.',                '2026-05-21 11:00:00'),
(17, 6, 1, 'Retard express — vérifier avec le transporteur et rembourser les frais.', '2026-05-22 15:00:00'),
(18, 7, 3, 'Article manquant — vérifier le stock et réexpédier.',             '2026-05-23 10:00:00'),
(20, 5, 1, 'Remboursement partiel — vérifier le calcul et corriger.',         '2026-05-25 17:00:00');

INSERT INTO commentaires (reclamation_id, auteur_id, client_id, contenu, interne, created_at) VALUES
(3, 5,    NULL, 'Bonjour, j\'ai bien pris en charge votre réclamation. Pouvez-vous nous envoyer des photos du produit endommagé ?',  FALSE, '2026-05-04 09:00:00'),
(3, NULL, 3,    'Bonjour, voici les photos en pièce jointe. Le produit est clairement cassé à l\'intérieur.',                        FALSE, '2026-05-04 11:30:00'),
(3, 5,    NULL, 'Merci pour les photos. Nous allons procéder au remplacement. Vous recevrez le nouveau produit sous 3-5 jours.',     FALSE, '2026-05-05 10:00:00'),
(3, 6,    NULL, 'Note interne : Contacter l\'entrepôt pour déclencher le remplacement. Ref commande : CMD-2026-8874.',               TRUE,  '2026-05-05 10:05:00'),
(4, 6,    NULL, 'Bonjour, nous avons bien reçu votre réclamation concernant notre SAV. Nous nous en excusons sincèrement.',         FALSE, '2026-05-06 09:00:00'),
(4, NULL, 4,    'Merci, j\'espère une résolution rapide. Cela dure depuis trop longtemps.',                                         FALSE, '2026-05-06 10:00:00'),
(4, 6,    NULL, 'Note interne : Remonter au responsable SAV. Historique d\'appels à vérifier.',                                     TRUE,  '2026-05-06 10:05:00'),
(5, 5,    NULL, 'Bonjour, nous avons localisé votre retour. Le remboursement sera effectué sous 5 jours ouvrés.',                   FALSE, '2026-05-08 09:00:00'),
(5, NULL, 5,    'Merci pour l\'information. J\'espère que cette fois c\'est la bonne.',                                             FALSE, '2026-05-08 10:30:00'),
(8, 7,    NULL, 'Bonjour, nous confirmons l\'erreur de notre côté. Un nouvel article en taille L vous sera expédié aujourd\'hui.',  FALSE, '2026-05-13 09:00:00'),
(8, NULL, 3,    'Parfait, merci pour la réactivité !',                                                                              FALSE, '2026-05-13 10:00:00'),
(10, 5,   NULL, 'Bonjour, nous avons transmis votre cas à l\'équipe technique. Une réponse vous sera donnée sous 24h.',            FALSE, '2026-05-16 09:00:00'),
(10, 5,   NULL, 'Note interne : Ticket ouvert chez le PSP. Ref : TKT-20260516-001.',                                               TRUE,  '2026-05-16 09:05:00'),
(15, 5,   NULL, 'Bonjour, après vérification votre produit est bien sous garantie. Nous avons accepté la prise en charge.',        FALSE, '2026-05-21 09:00:00'),
(15, NULL, 3,   'Merci beaucoup ! Je suis soulagé.',                                                                               FALSE, '2026-05-21 10:00:00'),
(15, 5,   NULL, 'Votre réclamation est maintenant clôturée. N\'hésitez pas à nous contacter si nécessaire.',                       FALSE, '2026-05-22 09:00:00');

INSERT INTO historique_actions (reclamation_id, utilisateur_id, ancien_statut_id, nouveau_statut_id, action, details, created_at) VALUES
(3,  1, 1, 2, 'AFFECTATION',     'Réclamation affectée à El Fassi Youssef.',           '2026-05-03 12:00:00'),
(3,  5, 2, 3, 'CHANGEMENT_STATUT','Prise en charge — en attente des photos client.',   '2026-05-04 09:00:00'),
(4,  3, 1, 8, 'AFFECTATION',     'Réclamation affectée à Filali Amina.',               '2026-05-05 15:00:00'),
(4,  6, 8, 2, 'CHANGEMENT_STATUT','Affectation confirmée.',                            '2026-05-06 09:00:00'),
(4,  6, 2, 3, 'CHANGEMENT_STATUT','Enquête SAV lancée.',                               '2026-05-06 10:00:00'),
(5,  1, 1, 2, 'AFFECTATION',     'Réclamation affectée à El Fassi Youssef.',           '2026-05-07 09:30:00'),
(5,  5, 2, 4, 'CHANGEMENT_STATUT','En attente de confirmation du virement bancaire.',  '2026-05-08 09:00:00'),
(6,  3, 1, 2, 'AFFECTATION',     'Réclamation affectée à Filali Amina.',               '2026-05-08 17:00:00'),
(6,  6, 2, 5, 'CHANGEMENT_STATUT','Transporteur contacté — livraison replanifiée.',    '2026-05-10 09:00:00'),
(6,  6, 5, 6, 'CHANGEMENT_STATUT','Client livré et satisfait. Clôture du dossier.',    '2026-05-12 10:00:00'),
(8,  1, 1, 2, 'AFFECTATION',     'Réclamation affectée à Guessous Mehdi.',             '2026-05-12 12:00:00'),
(8,  7, 2, 3, 'CHANGEMENT_STATUT','Vérification entrepôt en cours.',                  '2026-05-13 09:00:00'),
(8,  7, 3, 5, 'CHANGEMENT_STATUT','Nouvel article expédié. Numéro de suivi communiqué.','2026-05-14 09:00:00'),
(8,  7, 5, 7, 'CHANGEMENT_STATUT','Réclamation rejetée après analyse — doublon.',      '2026-05-15 09:00:00'),
(10, 3, 1, 2, 'AFFECTATION',     'Réclamation affectée à El Fassi Youssef.',           '2026-05-15 11:00:00'),
(10, 5, 2, 3, 'CHANGEMENT_STATUT','Ticket PSP ouvert.',                               '2026-05-16 09:00:00'),
(15, 1, 1, 2, 'AFFECTATION',     'Réclamation affectée à El Fassi Youssef.',           '2026-05-20 10:30:00'),
(15, 5, 2, 3, 'CHANGEMENT_STATUT','Vérification garantie en cours.',                  '2026-05-21 09:00:00'),
(15, 5, 3, 5, 'CHANGEMENT_STATUT','Garantie acceptée — prise en charge validée.',      '2026-05-22 09:00:00'),
(15, 5, 5, 6, 'CHANGEMENT_STATUT','Dossier clôturé après résolution.',                '2026-05-23 09:00:00');

INSERT INTO notifications (utilisateur_id, client_id, reclamation_id, type, message, lu, created_at) VALUES
(5, NULL, 3,  'AFFECTATION', 'Une nouvelle réclamation vous a été affectée.',                          FALSE, '2026-05-03 12:00:00'),
(6, NULL, 4,  'AFFECTATION', 'Une nouvelle réclamation vous a été affectée.',                          FALSE, '2026-05-05 15:00:00'),
(5, NULL, 5,  'AFFECTATION', 'Une nouvelle réclamation vous a été affectée.',                          TRUE,  '2026-05-07 09:30:00'),
(6, NULL, 6,  'AFFECTATION', 'Une nouvelle réclamation vous a été affectée.',                          TRUE,  '2026-05-08 17:00:00'),
(7, NULL, 8,  'AFFECTATION', 'Une nouvelle réclamation vous a été affectée.',                          TRUE,  '2026-05-12 12:00:00'),
(5, NULL, 10, 'AFFECTATION', 'Une nouvelle réclamation vous a été affectée.',                          FALSE, '2026-05-15 11:00:00'),
(5, NULL, 3,  'STATUT',      'Le statut de la réclamation #3 a été mis à jour : En cours',             TRUE,  '2026-05-04 09:00:00'),
(6, NULL, 6,  'STATUT',      'Le statut de la réclamation #6 a été mis à jour : Résolue',              TRUE,  '2026-05-10 09:00:00'),
(5, NULL, 15, 'AFFECTATION', 'Une nouvelle réclamation vous a été affectée.',                          FALSE, '2026-05-20 10:30:00'),
(5, NULL, 16, 'AFFECTATION', 'Une nouvelle réclamation vous a été affectée.',                          FALSE, '2026-05-21 11:00:00'),
(6, NULL, 17, 'AFFECTATION', 'Une nouvelle réclamation vous a été affectée.',                          FALSE, '2026-05-22 15:00:00'),
(7, NULL, 18, 'AFFECTATION', 'Une nouvelle réclamation vous a été affectée.',                          FALSE, '2026-05-23 10:00:00'),
(5, NULL, 20, 'AFFECTATION', 'Une nouvelle réclamation vous a été affectée.',                          FALSE, '2026-05-25 17:00:00');

INSERT INTO notifications (utilisateur_id, client_id, reclamation_id, type, message, lu, created_at) VALUES
(NULL, 1, 1,  'INFO',   'Votre réclamation REC-2026000001 a bien été enregistrée.',                    FALSE, '2026-05-01 09:00:00'),
(NULL, 2, 2,  'INFO',   'Votre réclamation REC-2026000002 a bien été enregistrée.',                    FALSE, '2026-05-02 10:30:00'),
(NULL, 3, 3,  'INFO',   'Votre réclamation REC-2026000003 a bien été enregistrée.',                    FALSE, '2026-05-03 11:15:00'),
(NULL, 3, 3,  'STATUT', 'Le statut de votre réclamation a été mis à jour : En cours',                  FALSE, '2026-05-04 09:00:00'),
(NULL, 4, 4,  'INFO',   'Votre réclamation REC-2026000004 a bien été enregistrée.',                    FALSE, '2026-05-05 14:00:00'),
(NULL, 5, 5,  'INFO',   'Votre réclamation REC-2026000005 a bien été enregistrée.',                    TRUE,  '2026-05-07 08:45:00'),
(NULL, 1, 6,  'INFO',   'Votre réclamation REC-2026000006 a bien été enregistrée.',                    TRUE,  '2026-05-08 16:20:00'),
(NULL, 1, 6,  'STATUT', 'Le statut de votre réclamation a été mis à jour : Clôturée',                  TRUE,  '2026-05-12 10:00:00'),
(NULL, 3, 8,  'STATUT', 'Le statut de votre réclamation a été mis à jour : Rejetée',                   FALSE, '2026-05-15 09:00:00'),
(NULL, 3, 15, 'STATUT', 'Le statut de votre réclamation a été mis à jour : Clôturée',                  FALSE, '2026-05-23 09:00:00');