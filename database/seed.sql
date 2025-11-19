-- =====================================================
-- TRAVAUX PRO - DONNÉES DE DÉMONSTRATION
-- Script pour avoir une application immédiatement fonctionnelle
-- =====================================================

-- Utilisateurs de démonstration (mot de passe: password123 pour tous)
-- Hash bcrypt de "password123": $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

-- Client de démonstration
INSERT INTO users (email, password, role, first_name, last_name, phone, address, city, postal_code, country, language, is_verified, created_at) VALUES
('client@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', 'Jean', 'Dupont', '+33612345678', '15 Rue de la République', 'Paris', '75001', 'FR', 'fr', true, NOW()),
('marie.martin@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', 'Marie', 'Martin', '+33687654321', '23 Avenue des Champs', 'Lyon', '69001', 'FR', 'fr', true, NOW()),
('paul.durand@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'client', 'Paul', 'Durand', '+33654321987', '7 Boulevard Haussmann', 'Marseille', '13001', 'FR', 'fr', true, NOW());

-- Artisans de démonstration
INSERT INTO users (email, password, role, first_name, last_name, phone, address, city, postal_code, country, language, company_name, siret, is_verified, created_at) VALUES
('artisan@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artisan', 'Pierre', 'Bernard', '+33698765432', '45 Rue du Commerce', 'Paris', '75015', 'FR', 'fr', 'Électricité Bernard', '12345678900012', true, NOW()),
('plombier@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artisan', 'Jacques', 'Leroy', '+33676543210', '12 Avenue Victor Hugo', 'Lyon', '69002', 'FR', 'fr', 'Plomberie Leroy', '98765432100023', true, NOW()),
('peintre@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artisan', 'Sophie', 'Dubois', '+33687123456', '34 Rue Nationale', 'Toulouse', '31000', 'FR', 'fr', 'Peinture Dubois', '11122233344455', true, NOW()),
('menuisier@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artisan', 'Marc', 'Petit', '+33654987321', '8 Place de la Mairie', 'Nice', '06000', 'FR', 'fr', 'Menuiserie Petit', '55566677788899', true, NOW()),
('carreleur@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'artisan', 'Luc', 'Moreau', '+33698741236', '56 Rue de Rivoli', 'Bordeaux', '33000', 'FR', 'fr', 'Carrelage Moreau', '99988877766655', true, NOW());

-- Admin de démonstration
INSERT INTO users (email, password, role, first_name, last_name, phone, is_verified, created_at) VALUES
('admin@demo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Admin', 'System', '+33600000000', true, NOW());

-- Profils artisans détaillés
INSERT INTO artisan_profiles (user_id, trade_category_id, experience_years, description, service_radius, hourly_rate, availability, verified, rating, reviews_count) VALUES
(4, 15, 10, 'Électricien professionnel avec 10 ans d\'expérience. Spécialisé en rénovation et mise aux normes. Interventions rapides et soignées.', 50, 45.00, 'available', true, 4.8, 24),
(5, 19, 15, 'Plombier chauffagiste expert. Installation, dépannage et rénovation. Service d\'urgence 24/7. Garantie décennale.', 30, 50.00, 'available', true, 4.9, 31),
(6, 36, 8, 'Peintre en bâtiment tous travaux. Intérieur et extérieur. Finitions soignées. Devis gratuit et rapide.', 40, 35.00, 'available', true, 4.7, 18),
(7, 42, 12, 'Menuisier ébéniste. Fabrication sur mesure, pose de parquet, escaliers. Travail artisanal de qualité.', 60, 55.00, 'available', true, 4.9, 27),
(8, 32, 6, 'Carreleur professionnel. Pose de carrelage sol et mur. Salles de bain, cuisines, terrasses. Garantie 10 ans.', 35, 40.00, 'available', true, 4.6, 15);

-- Certifications artisans
INSERT INTO artisan_certifications (user_id, badge_id, issued_at) VALUES
(4, 1, DATE_SUB(NOW(), INTERVAL 2 YEAR)),
(4, 2, DATE_SUB(NOW(), INTERVAL 1 YEAR)),
(5, 1, DATE_SUB(NOW(), INTERVAL 3 YEAR)),
(5, 2, DATE_SUB(NOW(), INTERVAL 6 MONTH)),
(6, 1, DATE_SUB(NOW(), INTERVAL 1 YEAR)),
(7, 1, DATE_SUB(NOW(), INTERVAL 4 YEAR)),
(7, 4, DATE_SUB(NOW(), INTERVAL 2 YEAR)),
(8, 1, DATE_SUB(NOW(), INTERVAL 1 YEAR));

-- Projets de démonstration
INSERT INTO projects (user_id, trade_category_id, title, description, budget, urgency, status, address, city, postal_code, latitude, longitude, created_at) VALUES
(1, 15, 'Rénovation électrique appartement', 'Rénovation complète de l\'installation électrique d\'un appartement de 70m². Mise aux normes obligatoire. Remplacement du tableau électrique et ajout de prises.', 3500.00, 'medium', 'open', '15 Rue de la République', 'Paris', '75001', 48.8566, 2.3522, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1, 19, 'Fuite d\'eau salle de bain', 'Fuite importante au niveau du lavabo. Besoin d\'une intervention rapide. Possibilité de remplacement complet de la robinetterie.', 500.00, 'urgent', 'open', '15 Rue de la République', 'Paris', '75001', 48.8566, 2.3522, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 36, 'Peinture maison 100m²', 'Peinture intérieure complète d\'une maison de 100m². 5 pièces + couloir. Murs et plafonds. Possibilité de fournir la peinture.', 4000.00, 'low', 'open', '23 Avenue des Champs', 'Lyon', '69001', 45.7640, 4.8357, DATE_SUB(NOW(), INTERVAL 10 DAY)),
(2, 32, 'Carrelage salle de bain 15m²', 'Pose de carrelage dans une salle de bain de 15m² (sol + murs). Fourniture et pose. Style moderne.', 2500.00, 'medium', 'in_progress', '23 Avenue des Champs', 'Lyon', '69001', 45.7640, 4.8357, DATE_SUB(NOW(), INTERVAL 15 DAY)),
(3, 42, 'Pose parquet chambre 20m²', 'Pose de parquet flottant dans une chambre de 20m². Préparation du sol incluse. Parquet déjà acheté.', 800.00, 'low', 'open', '7 Boulevard Haussmann', 'Marseille', '13001', 43.2965, 5.3698, DATE_SUB(NOW(), INTERVAL 7 DAY)),
(1, 6, 'Réfection toiture 80m²', 'Réfection complète de toiture en tuiles. Surface de 80m². Nettoyage, traitement hydrofuge et remplacement des tuiles abîmées.', 8000.00, 'high', 'open', '15 Rue de la République', 'Paris', '75001', 48.8566, 2.3522, DATE_SUB(NOW(), INTERVAL 3 DAY));

-- Données personnalisées des projets
INSERT INTO project_custom_fields (project_id, field_id, value) VALUES
-- Projet 1: Électricité
(1, 1, 'Installation complète'),
(1, 2, '70'),
(1, 3, '3'),
-- Projet 2: Plomberie
(2, 19, 'Urgence/Fuite'),
(2, 22, 'Oui - Urgent'),
-- Projet 3: Peinture
(3, 147, '100'),
(3, 148, '5'),
(3, 151, 'Acrylique');

-- Devis de démonstration
INSERT INTO quotes (project_id, user_id, amount, duration, description, status, valid_until, created_at) VALUES
(1, 4, 3200.00, '5 jours', 'Rénovation électrique complète:\n- Remplacement tableau électrique Legrand\n- 15 prises supplémentaires\n- Mise aux normes NF C 15-100\n- Certificat Consuel inclus\n- Garantie 2 ans', 'pending', DATE_ADD(NOW(), INTERVAL 30 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),
(1, 4, 3800.00, '7 jours', 'Devis détaillé électricité:\n- Tableau électrique haut de gamme\n- 20 prises avec USB\n- Éclairage LED inclus\n- Domotique basique\n- Garantie 3 ans', 'pending', DATE_ADD(NOW(), INTERVAL 25 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(2, 5, 450.00, '1 jour', 'Intervention urgente plomberie:\n- Réparation fuite lavabo\n- Remplacement joints\n- Vérification générale\n- Intervention sous 24h', 'accepted', DATE_ADD(NOW(), INTERVAL 15 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 6, 3800.00, '8 jours', 'Peinture complète maison:\n- Préparation murs et plafonds\n- 2 couches peinture acrylique\n- Finition soignée\n- Peinture écologique\n- Nettoyage inclus', 'pending', DATE_ADD(NOW(), INTERVAL 20 DAY), DATE_SUB(NOW(), INTERVAL 8 DAY)),
(4, 8, 2300.00, '4 jours', 'Carrelage salle de bain:\n- Fourniture carrelage 60x60cm\n- Pose sol et murs\n- Joints époxy\n- Évacuation anciens carreaux\n- Garantie 10 ans', 'accepted', DATE_ADD(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 12 DAY)),
(5, 7, 750.00, '2 jours', 'Pose parquet flottant:\n- Préparation sol (ragréage)\n- Sous-couche acoustique\n- Pose parquet\n- Plinthes incluses', 'pending', DATE_ADD(NOW(), INTERVAL 18 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY));

-- Messages de démonstration
INSERT INTO messages (sender_id, recipient_id, message, created_at, is_read) VALUES
(1, 4, 'Bonjour, je suis intéressé par votre devis pour la rénovation électrique. Pouvez-vous me préciser le délai d\'intervention ?', DATE_SUB(NOW(), INTERVAL 3 DAY), true),
(4, 1, 'Bonjour Monsieur Dupont, je peux intervenir dès la semaine prochaine. Je suis disponible du lundi au vendredi. Quel jour vous conviendrait le mieux ?', DATE_SUB(NOW(), INTERVAL 3 DAY), true),
(1, 4, 'Parfait ! Mardi prochain serait idéal. À quelle heure pourriez-vous passer ?', DATE_SUB(NOW(), INTERVAL 2 DAY), true),
(4, 1, 'Je peux venir mardi matin vers 9h pour faire un état des lieux précis et confirmer le devis. Ça vous convient ?', DATE_SUB(NOW(), INTERVAL 2 DAY), true),
(1, 4, 'Très bien, à mardi 9h alors. Merci !', DATE_SUB(NOW(), INTERVAL 2 DAY), true),
(2, 5, 'Bonjour, j\'ai une fuite urgente. Pouvez-vous intervenir aujourd\'hui ?', DATE_SUB(NOW(), INTERVAL 1 DAY), true),
(5, 2, 'Bonjour Madame Martin, oui je peux passer dans l\'après-midi. Vers 14h ça vous irait ?', DATE_SUB(NOW(), INTERVAL 1 DAY), true),
(2, 5, 'Parfait, merci beaucoup !', DATE_SUB(NOW(), INTERVAL 1 DAY), true);

-- Avis de démonstration
INSERT INTO reviews (project_id, reviewer_id, reviewed_user_id, rating, comment, created_at) VALUES
(4, 2, 8, 5, 'Excellent travail ! Le carreleur est très professionnel, ponctuel et soigneux. Le résultat est parfait. Je recommande vivement.', DATE_SUB(NOW(), INTERVAL 5 DAY)),
(2, 1, 5, 5, 'Intervention rapide et efficace. Le plombier a résolu le problème rapidement et a fait une vérification complète. Prix correct. Très satisfait.', DATE_SUB(NOW(), INTERVAL 3 DAY));

-- Notifications de démonstration
INSERT INTO notifications (user_id, type, title, message, data, created_at) VALUES
(1, 'quote_received', 'Nouveau devis reçu', 'Pierre Bernard vous a envoyé un devis pour votre projet "Rénovation électrique appartement"', '{"project_id": 1, "quote_id": 1, "artisan_name": "Pierre Bernard"}', DATE_SUB(NOW(), INTERVAL 3 DAY)),
(1, 'quote_received', 'Nouveau devis reçu', 'Pierre Bernard vous a envoyé un second devis pour votre projet "Rénovation électrique appartement"', '{"project_id": 1, "quote_id": 2, "artisan_name": "Pierre Bernard"}', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(5, 'quote_accepted', 'Devis accepté', 'Marie Martin a accepté votre devis pour le projet "Fuite d\'eau salle de bain"', '{"project_id": 2, "quote_id": 3, "client_name": "Marie Martin"}', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(8, 'quote_accepted', 'Devis accepté', 'Marie Martin a accepté votre devis pour le projet "Carrelage salle de bain 15m²"', '{"project_id": 4, "quote_id": 5, "client_name": "Marie Martin"}', DATE_SUB(NOW(), INTERVAL 12 DAY)),
(1, 'message_received', 'Nouveau message', 'Pierre Bernard vous a envoyé un message', '{"sender_id": 4, "sender_name": "Pierre Bernard"}', DATE_SUB(NOW(), INTERVAL 2 DAY));

-- Favoris de démonstration
INSERT INTO favorites (user_id, artisan_id, created_at) VALUES
(1, 4, DATE_SUB(NOW(), INTERVAL 10 DAY)),
(1, 5, DATE_SUB(NOW(), INTERVAL 8 DAY)),
(2, 6, DATE_SUB(NOW(), INTERVAL 15 DAY)),
(2, 8, DATE_SUB(NOW(), INTERVAL 12 DAY)),
(3, 7, DATE_SUB(NOW(), INTERVAL 20 DAY));

-- Analytics de démonstration
INSERT INTO analytics (user_id, event_type, event_data, created_at) VALUES
(1, 'project_created', '{"project_id": 1, "category": "Électricité"}', DATE_SUB(NOW(), INTERVAL 5 DAY)),
(1, 'project_created', '{"project_id": 2, "category": "Plomberie"}', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(4, 'quote_sent', '{"project_id": 1, "amount": 3200}', DATE_SUB(NOW(), INTERVAL 3 DAY)),
(5, 'quote_sent', '{"project_id": 2, "amount": 450}', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(1, 'quote_accepted', '{"quote_id": 3, "amount": 450}', DATE_SUB(NOW(), INTERVAL 1 DAY));

-- Statistiques de prix par catégorie (pour analytics)
INSERT INTO price_statistics (trade_category_id, region, min_price, avg_price, max_price, currency, unit, sample_size, last_updated) VALUES
(15, 'Île-de-France', 2000.00, 3500.00, 6000.00, 'EUR', 'projet', 45, NOW()),
(15, 'Provence-Alpes-Côte d\'Azur', 1800.00, 3200.00, 5500.00, 'EUR', 'projet', 32, NOW()),
(19, 'Île-de-France', 300.00, 500.00, 1200.00, 'EUR', 'intervention', 78, NOW()),
(19, 'Auvergne-Rhône-Alpes', 250.00, 450.00, 1000.00, 'EUR', 'intervention', 56, NOW()),
(36, 'Île-de-France', 25.00, 35.00, 50.00, 'EUR', 'm²', 124, NOW()),
(36, 'Occitanie', 20.00, 30.00, 45.00, 'EUR', 'm²', 89, NOW()),
(32, 'Île-de-France', 40.00, 60.00, 90.00, 'EUR', 'm²', 67, NOW()),
(32, 'Auvergne-Rhône-Alpes', 35.00, 55.00, 80.00, 'EUR', 'm²', 54, NOW()),
(42, 'Île-de-France', 45.00, 65.00, 100.00, 'EUR', 'm²', 42, NOW()),
(6, 'Île-de-France', 80.00, 120.00, 180.00, 'EUR', 'm²', 38, NOW());

-- Messages de bienvenue
INSERT INTO notifications (user_id, type, title, message, is_read, created_at) VALUES
(1, 'welcome', 'Bienvenue sur Travaux Pro !', 'Nous sommes ravis de vous accueillir. N\'hésitez pas à créer votre premier projet et recevoir des devis d\'artisans qualifiés.', false, NOW()),
(2, 'welcome', 'Bienvenue sur Travaux Pro !', 'Nous sommes ravis de vous accueillir. N\'hésitez pas à créer votre premier projet et recevoir des devis d\'artisans qualifiés.', false, NOW()),
(3, 'welcome', 'Bienvenue sur Travaux Pro !', 'Nous sommes ravis de vous accueillir. N\'hésitez pas à créer votre premier projet et recevoir des devis d\'artisans qualifiés.', false, NOW()),
(4, 'welcome', 'Bienvenue sur Travaux Pro !', 'Votre compte artisan est activé ! Vous pouvez maintenant parcourir les projets disponibles et envoyer vos devis.', false, NOW()),
(5, 'welcome', 'Bienvenue sur Travaux Pro !', 'Votre compte artisan est activé ! Vous pouvez maintenant parcourir les projets disponibles et envoyer vos devis.', false, NOW()),
(6, 'welcome', 'Bienvenue sur Travaux Pro !', 'Votre compte artisan est activé ! Vous pouvez maintenant parcourir les projets disponibles et envoyer vos devis.', false, NOW()),
(7, 'welcome', 'Bienvenue sur Travaux Pro !', 'Votre compte artisan est activé ! Vous pouvez maintenant parcourir les projets disponibles et envoyer vos devis.', false, NOW()),
(8, 'welcome', 'Bienvenue sur Travaux Pro !', 'Votre compte artisan est activé ! Vous pouvez maintenant parcourir les projets disponibles et envoyer vos devis.', false, NOW());

-- =====================================================
-- RÉSUMÉ DES DONNÉES IMPORTÉES
-- =====================================================
-- ✓ 7 utilisateurs (3 clients + 4 artisans + 1 admin)
-- ✓ 5 profils artisans détaillés
-- ✓ 8 certifications artisans
-- ✓ 6 projets de démonstration
-- ✓ 6 devis
-- ✓ 8 messages
-- ✓ 2 avis
-- ✓ 10 notifications
-- ✓ 5 favoris
-- ✓ 5 analytics
-- ✓ 10 statistiques de prix
--
-- COMPTES DE DÉMONSTRATION :
-- • Client: client@demo.com / password123
-- • Artisan: artisan@demo.com / password123
-- • Admin: admin@demo.com / password123
-- =====================================================
