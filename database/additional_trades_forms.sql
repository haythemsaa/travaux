-- =====================================================
-- ADDITIONAL CUSTOM FORMS FOR REMAINING TRADES
-- Comprehensive questionnaires for all missing trades
-- =====================================================

-- Excavation / Terrassement (ID 2)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(2, 'work_type', 'select', 'Type de terrassement', 'Excavation type', 'Tipo de excavación', 'Erdarbeitstyp', 'Tipo di scavo',
'{"options": ["Fondations", "Piscine", "Tranchées réseaux", "Nivellement terrain", "Assainissement", "Autre"]}',
'{"required": true}', 1, true, null, 'Sélectionnez le type de travaux'),
(2, 'terrain_area', 'number', 'Surface du terrain (m²)', 'Land area (sqm)', 'Área del terreno (m²)', 'Grundstücksfläche (m²)', 'Superficie terreno (m²)',
NULL, '{"required": true, "min": 10}', 2, true, '500', null),
(2, 'excavation_depth', 'select', 'Profondeur estimée', 'Estimated depth', 'Profundidad estimada', 'Geschätzte Tiefe', 'Profondità stimata',
'{"options": ["< 1m", "1-2m", "2-3m", "> 3m"]}',
'{"required": true}', 3, true, null, null),
(2, 'soil_type', 'select', 'Type de sol', 'Soil type', 'Tipo de suelo', 'Bodentyp', 'Tipo di terreno',
'{"options": ["Terre végétale", "Argile", "Sable", "Roche", "Ne sait pas"]}',
'{"required": false}', 4, false, null, null),
(2, 'access_difficulty', 'radio', 'Accès difficile?', 'Difficult access?', '¿Acceso difícil?', 'Schwieriger Zugang?', 'Accesso difficile?',
'{"options": ["Oui", "Non"]}',
'{"required": false}', 5, false, null, 'Passage étroit, pente, etc.');

-- Foundation / Fondations (ID 3)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(3, 'foundation_type', 'select', 'Type de fondation', 'Foundation type', 'Tipo de cimientos', 'Fundamenttyp', 'Tipo di fondamenta',
'{"options": ["Semelles filantes", "Semelles isolées", "Radier", "Pieux", "Micropieux", "Ne sait pas"]}',
'{"required": true}', 1, true, null, null),
(3, 'building_type', 'select', 'Type de construction', 'Building type', 'Tipo de edificio', 'Gebäudetyp', 'Tipo di edificio',
'{"options": ["Maison individuelle", "Extension", "Garage", "Véranda", "Piscine", "Mur de soutènement", "Autre"]}',
'{"required": true}', 2, true, null, null),
(3, 'num_floors', 'select', 'Nombre d\'étages', 'Number of floors', 'Número de pisos', 'Anzahl Etagen', 'Numero piani',
'{"options": ["RDC uniquement", "RDC + 1 étage", "RDC + 2 étages", "Plus"]}',
'{"required": true}', 3, true, null, null),
(3, 'soil_study', 'radio', 'Étude de sol réalisée?', 'Soil study done?', '¿Estudio de suelo?', 'Bodengutachten?', 'Studio del terreno?',
'{"options": ["Oui", "Non", "En cours"]}',
'{"required": true}', 4, true, null, null);

-- Concrete / Béton (ID 4)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(4, 'work_type', 'checkbox', 'Type de travaux béton', 'Concrete work type', 'Tipo de trabajo', 'Art der Betonarbeiten', 'Tipo di lavoro',
'{"options": ["Dalle", "Chape", "Escalier", "Terrasse", "Allée", "Piscine", "Mur", "Piliers", "Autre"]}',
'{"required": true}', 1, true, null, 'Sélectionnez tous les travaux'),
(4, 'surface_area', 'number', 'Surface (m²)', 'Surface area (sqm)', 'Superficie (m²)', 'Fläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 1}', 2, true, '50', null),
(4, 'thickness', 'select', 'Épaisseur souhaitée', 'Desired thickness', 'Espesor deseado', 'Gewünschte Dicke', 'Spessore desiderato',
'{"options": ["10 cm", "12 cm", "15 cm", "20 cm", "> 20 cm", "Ne sait pas"]}',
'{"required": false}', 3, false, null, null),
(4, 'finish_type', 'select', 'Type de finition', 'Finish type', 'Tipo de acabado', 'Oberflächentyp', 'Tipo di finitura',
'{"options": ["Brut", "Lissé", "Bouchardé", "Ciré", "Poli", "Imprimé/Estampé"]}',
'{"required": false}', 4, false, null, null),
(4, 'reinforcement', 'radio', 'Ferraillage nécessaire?', 'Reinforcement needed?', '¿Refuerzo necesario?', 'Bewehrung erforderlich?', 'Rinforzo necessario?',
'{"options": ["Oui", "Non", "À définir"]}',
'{"required": false}', 5, false, null, null);

-- Masonry / Maçonnerie générale (ID 5)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(5, 'work_type', 'checkbox', 'Type de travaux', 'Type of work', 'Tipo de trabajo', 'Art der Arbeit', 'Tipo di lavoro',
'{"options": ["Mur porteur", "Mur de clôture", "Mur de soutènement", "Cloisons", "Ouverture mur", "Réparation fissures", "Rejointoiement", "Autre"]}',
'{"required": true}', 1, true, null, null),
(5, 'wall_length', 'number', 'Longueur totale (m)', 'Total length (m)', 'Longitud total (m)', 'Gesamtlänge (m)', 'Lunghezza totale (m)',
NULL, '{"required": false, "min": 1}', 2, false, '10', 'Si applicable'),
(5, 'wall_height', 'number', 'Hauteur (m)', 'Height (m)', 'Altura (m)', 'Höhe (m)', 'Altezza (m)',
NULL, '{"required": false, "min": 0.5}', 3, false, '2.5', 'Si applicable'),
(5, 'material', 'select', 'Matériau', 'Material', 'Material', 'Material', 'Materiale',
'{"options": ["Parpaing", "Brique", "Pierre", "Béton cellulaire", "Autre"]}',
'{"required": false}', 4, false, null, null);

-- Gutters / Gouttières (ID 7)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(7, 'work_type', 'select', 'Type d\'intervention', 'Type of work', 'Tipo de intervención', 'Art der Arbeit', 'Tipo di intervento',
'{"options": ["Installation complète", "Remplacement", "Réparation", "Nettoyage", "Débouchage"]}',
'{"required": true}', 1, true, null, null),
(7, 'total_length', 'number', 'Longueur totale (m)', 'Total length (m)', 'Longitud total (m)', 'Gesamtlänge (m)', 'Lunghezza totale (m)',
NULL, '{"required": true, "min": 1}', 2, true, '20', null),
(7, 'material', 'select', 'Matériau', 'Material', 'Material', 'Material', 'Materiale',
'{"options": ["PVC", "Zinc", "Aluminium", "Cuivre", "Acier galvanisé"]}',
'{"required": false}', 3, false, null, null),
(7, 'num_downspouts', 'number', 'Nombre de descentes', 'Number of downspouts', 'Número de bajantes', 'Anzahl Fallrohre', 'Numero pluviali',
NULL, '{"required": false, "min": 1}', 4, false, '3', null),
(7, 'rainwater_collection', 'radio', 'Récupération eau de pluie?', 'Rainwater collection?', '¿Recogida de agua?', 'Regenwassersammlung?', 'Raccolta acqua piovana?',
'{"options": ["Oui", "Non"]}',
'{"required": false}', 5, false, null, null);

-- Facade Cleaning / Ravalement (ID 8)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(8, 'building_type', 'select', 'Type de bâtiment', 'Building type', 'Tipo de edificio', 'Gebäudetyp', 'Tipo di edificio',
'{"options": ["Maison individuelle", "Immeuble", "Commerce", "Bureau"]}',
'{"required": true}', 1, true, null, null),
(8, 'facade_surface', 'number', 'Surface de façade (m²)', 'Facade surface (sqm)', 'Superficie fachada (m²)', 'Fassadenfläche (m²)', 'Superficie facciata (m²)',
NULL, '{"required": true, "min": 10}', 2, true, '100', null),
(8, 'num_floors', 'select', 'Nombre d\'étages', 'Number of floors', 'Número de pisos', 'Anzahl Etagen', 'Numero piani',
'{"options": ["1", "2", "3", "4", "5+", "Ne sait pas"]}',
'{"required": true}', 3, true, null, null),
(8, 'facade_material', 'select', 'Matériau de la façade', 'Facade material', 'Material fachada', 'Fassadenmaterial', 'Materiale facciata',
'{"options": ["Crépi/Enduit", "Pierre", "Brique", "Béton", "Mixte", "Autre"]}',
'{"required": false}', 4, false, null, null),
(8, 'work_needed', 'checkbox', 'Travaux nécessaires', 'Work needed', 'Trabajos necesarios', 'Erforderliche Arbeiten', 'Lavori necessari',
'{"options": ["Nettoyage", "Réparation fissures", "Peinture", "Enduit", "Hydrofuge", "Isolation extérieure (ITE)"]}',
'{"required": true}', 5, true, null, null),
(8, 'scaffolding', 'radio', 'Échafaudage nécessaire?', 'Scaffolding needed?', '¿Andamio necesario?', 'Gerüst erforderlich?', 'Ponteggio necessario?',
'{"options": ["Oui", "Non", "Ne sait pas"]}',
'{"required": false}', 6, false, null, null);

-- Demolition / Démolition (ID 9)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(9, 'demolition_type', 'select', 'Type de démolition', 'Demolition type', 'Tipo de demolición', 'Abbruchtyp', 'Tipo di demolizione',
'{"options": ["Totale (bâtiment complet)", "Partielle (pièce/étage)", "Intérieure uniquement", "Mur porteur", "Cloison", "Autre"]}',
'{"required": true}', 1, true, null, null),
(9, 'building_type', 'select', 'Type de construction', 'Building type', 'Tipo de edificio', 'Gebäudetyp', 'Tipo di edificio',
'{"options": ["Maison", "Garage", "Dépendance", "Mur", "Immeuble", "Autre"]}',
'{"required": true}', 2, true, null, null),
(9, 'surface_area', 'number', 'Surface à démolir (m²)', 'Surface to demolish (sqm)', 'Superficie a demoler (m²)', 'Abrissfläche (m²)', 'Superficie da demolire (m²)',
NULL, '{"required": true, "min": 1}', 3, true, '50', null),
(9, 'material', 'select', 'Matériau principal', 'Main material', 'Material principal', 'Hauptmaterial', 'Materiale principale',
'{"options": ["Brique", "Parpaing", "Béton", "Pierre", "Bois", "Mixte"]}',
'{"required": false}', 4, false, null, null),
(9, 'waste_removal', 'radio', 'Évacuation des gravats?', 'Waste removal?', '¿Retirada de escombros?', 'Entsorgung?', 'Rimozione macerie?',
'{"options": ["Oui", "Non"]}',
'{"required": true}', 5, true, null, null),
(9, 'asbestos_concern', 'radio', 'Risque amiante?', 'Asbestos concern?', '¿Riesgo de amianto?', 'Asbestrisiko?', 'Rischio amianto?',
'{"options": ["Oui", "Non", "Ne sait pas"]}',
'{"required": true}', 6, true, null, 'Bâtiments avant 1997'),
(9, 'permit', 'radio', 'Permis de démolir obtenu?', 'Demolition permit obtained?', '¿Permiso obtenido?', 'Abbruchgenehmigung?', 'Permesso ottenuto?',
'{"options": ["Oui", "Non", "En cours"]}',
'{"required": false}', 7, false, null, null);

-- Home Automation / Domotique (ID 16)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(16, 'system_type', 'checkbox', 'Systèmes souhaités', 'Desired systems', 'Sistemas deseados', 'Gewünschte Systeme', 'Sistemi desiderati',
'{"options": ["Éclairage", "Volets/Stores", "Chauffage", "Climatisation", "Sécurité/Alarme", "Portail/Porte garage", "Multimédia", "Arrosage"]}',
'{"required": true}', 1, true, null, 'Sélectionnez tous les systèmes'),
(16, 'property_type', 'select', 'Type de bien', 'Property type', 'Tipo de propiedad', 'Immobilientyp', 'Tipo di proprietà',
'{"options": ["Appartement", "Maison", "Bureau"]}',
'{"required": true}', 2, true, null, null),
(16, 'num_rooms', 'number', 'Nombre de pièces', 'Number of rooms', 'Número de habitaciones', 'Anzahl Räume', 'Numero stanze',
NULL, '{"required": false, "min": 1}', 3, false, '5', null),
(16, 'control_preference', 'select', 'Mode de contrôle préféré', 'Preferred control', 'Control preferido', 'Bevorzugte Steuerung', 'Controllo preferito',
'{"options": ["Application mobile", "Commandes vocales", "Télécommandes", "Interrupteurs tactiles", "Tous"]}',
'{"required": false}', 4, false, null, null),
(16, 'existing_system', 'radio', 'Système domotique existant?', 'Existing system?', '¿Sistema existente?', 'Bestehendes System?', 'Sistema esistente?',
'{"options": ["Oui - à étendre", "Oui - à remplacer", "Non"]}',
'{"required": true}', 5, true, null, null);

-- Telecommunications / Télécommunications (ID 17)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(17, 'work_type', 'checkbox', 'Type d\'installation', 'Installation type', 'Tipo de instalación', 'Installationstyp', 'Tipo di installazione',
'{"options": ["Fibre optique", "Câblage réseau RJ45", "Antenne TV/TNT", "Parabole satellite", "Interphone", "Vidéophone", "Autre"]}',
'{"required": true}', 1, true, null, null),
(17, 'num_outlets', 'number', 'Nombre de prises réseau', 'Number of network outlets', 'Número de tomas', 'Anzahl Netzwerkdosen', 'Numero prese',
NULL, '{"required": false, "min": 1}', 2, false, '8', 'Pour câblage RJ45'),
(17, 'num_rooms', 'number', 'Nombre de pièces', 'Number of rooms', 'Número de habitaciones', 'Anzahl Räume', 'Numero stanze',
NULL, '{"required": false, "min": 1}', 3, false, '5', null),
(17, 'cable_concealment', 'radio', 'Passage des câbles', 'Cable routing', 'Paso de cables', 'Kabelverlegung', 'Passaggio cavi',
'{"options": ["Encastré (dans les murs)", "Apparent (goulottes)", "Mixte"]}',
'{"required": false}', 4, false, null, null);

-- Ventilation / VMC (ID 22)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(22, 'system_type', 'select', 'Type de VMC', 'VMC type', 'Tipo de VMC', 'VMC-Typ', 'Tipo di VMC',
'{"options": ["VMC simple flux", "VMC double flux", "VMC hygroréglable", "VMI (Insufflation)", "Autre"]}',
'{"required": true}', 1, true, null, null),
(22, 'property_size', 'number', 'Surface du logement (m²)', 'Property size (sqm)', 'Tamaño vivienda (m²)', 'Wohnungsgröße (m²)', 'Dimensioni abitazione (m²)',
NULL, '{"required": true, "min": 20}', 2, true, '100', null),
(22, 'num_rooms', 'number', 'Nombre de pièces', 'Number of rooms', 'Número de habitaciones', 'Anzahl Räume', 'Numero stanze',
NULL, '{"required": true, "min": 1}', 3, true, '4', null),
(22, 'num_bathrooms', 'number', 'Nombre de salles d\'eau', 'Number of bathrooms', 'Número de baños', 'Anzahl Bäder', 'Numero bagni',
NULL, '{"required": true, "min": 1}', 4, true, '2', null),
(22, 'humidity_problem', 'radio', 'Problème d\'humidité?', 'Humidity problem?', '¿Problema de humedad?', 'Feuchtigkeitsproblem?', 'Problema di umidità?',
'{"options": ["Oui", "Non"]}',
'{"required": false}', 5, false, null, null);

-- Water Treatment / Traitement d'eau (ID 23)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(23, 'treatment_type', 'checkbox', 'Type de traitement', 'Treatment type', 'Tipo de tratamiento', 'Behandlungstyp', 'Tipo di trattamento',
'{"options": ["Adoucisseur", "Osmoseur", "Anti-calcaire", "Filtration", "UV (désinfection)", "Autre"]}',
'{"required": true}', 1, true, null, null),
(23, 'water_source', 'select', 'Source d\'eau', 'Water source', 'Fuente de agua', 'Wasserquelle', 'Fonte d\'acqua',
'{"options": ["Eau de ville", "Puits", "Forage", "Source"]}',
'{"required": true}', 2, true, null, null),
(23, 'num_people', 'select', 'Nombre de personnes', 'Number of people', 'Número de personas', 'Personenanzahl', 'Numero persone',
'{"options": ["1-2", "3-4", "5-6", "7+"]}',
'{"required": false}', 3, false, null, null),
(23, 'water_hardness', 'select', 'Dureté de l\'eau', 'Water hardness', 'Dureza del agua', 'Wasserhärte', 'Durezza acqua',
'{"options": ["Très douce (< 8°f)", "Douce (8-15°f)", "Moyennement dure (15-30°f)", "Dure (> 30°f)", "Ne sait pas"]}',
'{"required": false}', 4, false, null, null);

-- Geothermal / Géothermie (ID 24)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(24, 'system_type', 'select', 'Type de système', 'System type', 'Tipo de sistema', 'Systemtyp', 'Tipo di sistema',
'{"options": ["Captage horizontal", "Captage vertical (forage)", "Sur nappe phréatique", "Ne sait pas"]}',
'{"required": true}', 1, true, null, null),
(24, 'property_size', 'number', 'Surface à chauffer (m²)', 'Area to heat (sqm)', 'Área a calentar (m²)', 'Zu heizende Fläche (m²)', 'Area da riscaldare (m²)',
NULL, '{"required": true, "min": 50}', 2, true, '150', null),
(24, 'terrain_size', 'number', 'Surface du terrain (m²)', 'Land area (sqm)', 'Superficie terreno (m²)', 'Grundstücksgröße (m²)', 'Superficie terreno (m²)',
NULL, '{"required": false, "min": 100}', 3, false, '500', 'Pour captage horizontal'),
(24, 'heating_need', 'checkbox', 'Besoins', 'Needs', 'Necesidades', 'Bedürfnisse', 'Bisogni',
'{"options": ["Chauffage", "Eau chaude sanitaire", "Rafraîchissement été"]}',
'{"required": true}', 4, true, null, null),
(24, 'current_heating', 'select', 'Chauffage actuel', 'Current heating', 'Calefacción actual', 'Aktuelle Heizung', 'Riscaldamento attuale',
'{"options": ["Gaz", "Fioul", "Électrique", "Bois", "Autre", "Aucun"]}',
'{"required': false}', 5, false, null, null);

-- Soundproofing / Isolation Phonique (ID 26)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(26, 'noise_source', 'select', 'Source du bruit', 'Noise source', 'Fuente de ruido', 'Lärmquelle', 'Fonte rumore',
'{"options": ["Voisins (appartement)", "Rue/Route", "Aérien (avions)", "Intérieur (pièce à pièce)", "Autre"]}',
'{"required": true}', 1, true, null, null),
(26, 'area_to_treat', 'checkbox', 'Zones à isoler', 'Areas to insulate', 'Áreas a aislar', 'Zu dämmende Bereiche', 'Aree da isolare',
'{"options": ["Murs", "Plafond", "Sol", "Fenêtres", "Portes"]}',
'{"required": true}', 2, true, null, null),
(26, 'surface_area', 'number', 'Surface (m²)', 'Surface (sqm)', 'Superficie (m²)', 'Fläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 1}', 3, true, '20', null),
(26, 'noise_level', 'select', 'Niveau de nuisance', 'Noise level', 'Nivel de molestia', 'Lärmpegel', 'Livello rumore',
'{"options": ["Faible", "Modéré", "Important", "Très important"]}',
'{"required": false}', 4, false, null, null);

-- Doors / Portes (ID 28)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(28, 'door_type', 'select', 'Type de porte', 'Door type', 'Tipo de puerta', 'Türtyp', 'Tipo di porta',
'{"options": ["Porte d\'entrée", "Porte intérieure", "Porte de service", "Porte fenêtre", "Porte coulissante"]}',
'{"required": true}', 1, true, null, null),
(28, 'num_doors', 'number', 'Nombre de portes', 'Number of doors', 'Número de puertas', 'Anzahl Türen', 'Numero porte',
NULL, '{"required": true, "min": 1}', 2, true, '1', null),
(28, 'material', 'select', 'Matériau', 'Material', 'Material', 'Material', 'Materiale',
'{"options": ["Bois massif", "Bois composite", "PVC", "Aluminium", "Acier", "Verre"]}',
'{"required": false}', 3, false, null, null),
(28, 'security_level', 'select', 'Niveau de sécurité', 'Security level', 'Nivel de seguridad', 'Sicherheitsstufe', 'Livello sicurezza',
'{"options": ["Standard", "Renforcée", "Blindée A2P BP1", "Blindée A2P BP2", "Blindée A2P BP3"]}',
'{"required": false}', 4, false, null, 'Pour porte d\'entrée'),
(28, 'insulation_needed', 'radio', 'Isolation thermique/phonique?', 'Insulation needed?', '¿Aislamiento necesario?', 'Dämmung erforderlich?', 'Isolamento necessario?',
'{"options": ["Oui", "Non"]}',
'{"required": false}', 5, false, null, null);

-- Garage Doors / Portes de Garage (ID 29)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(29, 'door_type', 'select', 'Type de porte', 'Door type', 'Tipo de puerta', 'Türtyp', 'Tipo di porta',
'{"options": ["Sectionnelle", "Basculante", "Enroulable", "Battante", "Coulissante"]}',
'{"required": true}', 1, true, null, null),
(29, 'door_width', 'select', 'Largeur', 'Width', 'Anchura', 'Breite', 'Larghezza',
'{"options": ["Simple (< 2.5m)", "Standard (2.5-3m)", "Large (3-4m)", "Double (> 4m)"]}',
'{"required": true}', 2, true, null, null),
(29, 'material', 'select', 'Matériau', 'Material', 'Material', 'Material', 'Materiale',
'{"options": ["Acier", "Aluminium", "Bois", "PVC"]}',
'{"required": false}', 3, false, null, null),
(29, 'motorization', 'radio', 'Motorisation souhaitée?', 'Motorization desired?', '¿Motorización deseada?', 'Motorisierung gewünscht?', 'Motorizzazione?',
'{"options": ["Oui", "Non"]}',
'{"required": true}', 4, true, null, null),
(29, 'insulation', 'radio', 'Porte isolée?', 'Insulated door?', '¿Puerta aislada?', 'Isolierte Tür?', 'Porta isolata?',
'{"options": ["Oui", "Non"]}',
'{"required": false}', 5, false, null, null);

-- Shutters / Volets (ID 30)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(30, 'shutter_type', 'select', 'Type de volets', 'Shutter type', 'Tipo de persianas', 'Rollladentyp', 'Tipo di persiane',
'{"options": ["Roulants", "Battants", "Pliants", "Coulissants"]}',
'{"required": true}', 1, true, null, null),
(30, 'num_windows', 'number', 'Nombre de fenêtres', 'Number of windows', 'Número de ventanas', 'Anzahl Fenster', 'Numero finestre',
NULL, '{"required": true, "min": 1}', 2, true, '5', null),
(30, 'material', 'select', 'Matériau', 'Material', 'Material', 'Material', 'Materiale',
'{"options": ["PVC", "Aluminium", "Bois"]}',
'{"required": false}', 3, false, null, null),
(30, 'motorization', 'radio', 'Motorisation?', 'Motorized?', '¿Motorización?', 'Motorisierung?', 'Motorizzazione?',
'{"options": ["Oui - tous", "Oui - certains", "Non"]}',
'{"required": false}', 4, false, null, null);

-- Tiling / Carrelage (ID 32)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(32, 'tiling_location', 'checkbox', 'Zones à carreler', 'Areas to tile', 'Áreas a alicatar', 'Bereiche zu fliesen', 'Aree da piastrellare',
'{"options": ["Sol", "Murs", "Douche", "Cuisine (crédence)", "Extérieur (terrasse)"]}',
'{"required": true}', 1, true, null, null),
(32, 'total_area', 'number', 'Surface totale (m²)', 'Total area (sqm)', 'Área total (m²)', 'Gesamtfläche (m²)', 'Superficie totale (m²)',
NULL, '{"required": true, "min": 1}', 2, true, '20', null),
(32, 'tile_size', 'select', 'Format de carreaux', 'Tile format', 'Formato baldosas', 'Fliesenformat', 'Formato piastrelle',
'{"options": ["Petit (< 20x20cm)", "Standard (20x20 - 40x40cm)", "Grand (> 40x40cm)", "Rectangulaire", "Mosaïque"]}',
'{"required": false}', 3, false, null, null),
(32, 'tile_type', 'select', 'Type de carrelage', 'Tile type', 'Tipo de baldosa', 'Fliesentyp', 'Tipo di piastrella',
'{"options": ["Grès cérame", "Faïence", "Pierre naturelle", "Terre cuite", "Autre"]}',
'{"required": false}', 4, false, null, null),
(32, 'remove_old', 'radio', 'Dépose ancien carrelage?', 'Remove old tiling?', '¿Quitar baldosas antiguas?', 'Alte Fliesen entfernen?', 'Rimuovere vecchie?',
'{"options": ["Oui", "Non"]}',
'{"required": true}', 5, true, null, null);

-- Parquet (ID 33)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(33, 'parquet_type', 'select', 'Type de parquet', 'Parquet type', 'Tipo de parqué', 'Parketttyp', 'Tipo di parquet',
'{"options": ["Massif", "Contrecollé", "Stratifié"]}',
'{"required": true}', 1, true, null, null),
(33, 'floor_area', 'number', 'Surface (m²)', 'Floor area (sqm)', 'Superficie (m²)', 'Bodenfläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 5}', 2, true, '30', null),
(33, 'wood_species', 'select', 'Essence de bois', 'Wood species', 'Especie de madera', 'Holzart', 'Essenza legno',
'{"options": ["Chêne", "Hêtre", "Érable", "Bambou", "Teck", "Exotique", "Autre"]}',
'{"required": false}', 3, false, null, null),
(33, 'finish', 'select', 'Finition', 'Finish', 'Acabado', 'Finish', 'Finitura',
'{"options": ["Huilé", "Vitrifié", "Ciré", "Brut (à finir)"]}',
'{"required": false}', 4, false, null, null),
(33, 'underlay', 'radio', 'Sous-couche nécessaire?', 'Underlay needed?', '¿Subcapa necesaria?', 'Unterlage erforderlich?', 'Sottofondo necessario?',
'{"options": ["Oui", "Non", "Ne sait pas"]}',
'{"required": false}', 5, false, null, null);

-- Laminate / Stratifié (ID 34)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(34, 'floor_area', 'number', 'Surface (m²)', 'Floor area (sqm)', 'Superficie (m²)', 'Bodenfläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 5}', 1, true, '30', null),
(34, 'quality_class', 'select', 'Classe d\'usage', 'Usage class', 'Clase de uso', 'Nutzungsklasse', 'Classe d\'uso',
'{"options": ["Domestique léger (AC3)", "Domestique intense (AC4)", "Commercial (AC5)"]}',
'{"required": false}', 2, false, null, null),
(34, 'plank_size', 'select', 'Format des lames', 'Plank format', 'Formato tablas', 'Dielenformat', 'Formato listelli',
'{"options": ["Petit (< 1m)", "Standard (1-1.3m)", "Large (> 1.3m)"]}',
'{"required": false}', 3, false, null, null),
(34, 'underlay_included', 'radio', 'Sous-couche intégrée?', 'Integrated underlay?', '¿Subcapa integrada?', 'Integrierte Unterlage?', 'Sottofondo integrato?',
'{"options": ["Oui", "Non", "Ne sait pas"]}',
'{"required": false}', 4, false, null, null);

-- Carpet / Moquette (ID 35)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(35, 'carpet_type', 'select', 'Type de moquette', 'Carpet type', 'Tipo de moqueta', 'Teppichtyp', 'Tipo di moquette',
'{"options": ["Boucle", "Velours", "Aiguilletée", "Shaggy", "Dalles"]}',
'{"required": true}', 1, true, null, null),
(35, 'floor_area', 'number', 'Surface (m²)', 'Floor area (sqm)', 'Superficie (m²)', 'Bodenfläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 5}', 2, true, '20', null),
(35, 'room_type', 'select', 'Type de pièce', 'Room type', 'Tipo de habitación', 'Raumtyp', 'Tipo di stanza',
'{"options": ["Chambre", "Salon", "Bureau", "Escalier", "Couloir"]}',
'{"required": false}', 3, false, null, null),
(35, 'underlay_needed', 'radio', 'Thibaude nécessaire?', 'Underlay needed?', '¿Subcapa necesaria?', 'Unterlage erforderlich?', 'Sottofondo necessario?',
'{"options": ["Oui", "Non"]}',
'{"required": false}', 4, false, null, null);

-- Wallpaper / Papier Peint (ID 37)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(37, 'wallpaper_type', 'select', 'Type de papier peint', 'Wallpaper type', 'Tipo de papel', 'Tapetentyp', 'Tipo di carta',
'{"options": ["Papier traditionnel", "Intissé", "Vinyle", "Textile", "Panoramique"]}',
'{"required": true}', 1, true, null, null),
(37, 'total_area', 'number', 'Surface des murs (m²)', 'Wall area (sqm)', 'Área de paredes (m²)', 'Wandfläche (m²)', 'Superficie pareti (m²)',
NULL, '{"required": true, "min": 5}', 2, true, '30', null),
(37, 'num_rooms', 'number', 'Nombre de pièces', 'Number of rooms', 'Número de habitaciones', 'Anzahl Räume', 'Numero stanze',
NULL, '{"required": false, "min": 1}', 3, false, '1', null),
(37, 'wall_prep', 'radio', 'Préparation des murs nécessaire?', 'Wall prep needed?', '¿Preparación de paredes?', 'Wandvorbereitung?', 'Preparazione pareti?',
'{"options": ["Oui", "Non", "Ne sait pas"]}',
'{"required": false}', 4, false, null, null),
(37, 'remove_old', 'radio', 'Dépose ancien papier?', 'Remove old wallpaper?', '¿Quitar papel antiguo?', 'Alte Tapete entfernen?', 'Rimuovere vecchia?',
'{"options": ["Oui", "Non"]}',
'{"required": true}', 5, true, null, null);

-- Decorative Painting / Peinture Décorative (ID 38)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(38, 'technique', 'select', 'Technique souhaitée', 'Desired technique', 'Técnica deseada', 'Gewünschte Technik', 'Tecnica desiderata',
'{"options": ["Effet béton ciré", "Tadelakt", "Stuc", "Patine", "Fresque/Trompe-l\'oeil", "Dorure", "Autre"]}',
'{"required": true}', 1, true, null, null),
(38, 'surface_area', 'number', 'Surface (m²)', 'Surface (sqm)', 'Superficie (m²)', 'Fläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 1}', 2, true, '15', null),
(38, 'location', 'select', 'Emplacement', 'Location', 'Ubicación', 'Standort', 'Posizione',
'{"options": ["Mur entier", "Pan de mur", "Plafond", "Meuble", "Autre"]}',
'{"required": false}', 3, false, null, null),
(38, 'custom_design', 'radio', 'Création personnalisée?', 'Custom design?', '¿Diseño personalizado?', 'Individuelles Design?', 'Design personalizzato?',
'{"options": ["Oui", "Non"]}',
'{"required": false}', 4, false, null, null);

-- Countertops / Plan de Travail (ID 41)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(41, 'material', 'select', 'Matériau', 'Material', 'Material', 'Material', 'Materiale',
'{"options": ["Stratifié", "Quartz", "Granit", "Marbre", "Bois massif", "Inox", "Céramique", "Béton ciré"]}',
'{"required": true}', 1, true, null, null),
(41, 'linear_length', 'number', 'Longueur linéaire (m)', 'Linear length (m)', 'Longitud lineal (m)', 'Länge linear (m)', 'Lunghezza lineare (m)',
NULL, '{"required": true, "min": 1}', 2, true, '3', null),
(41, 'depth', 'select', 'Profondeur', 'Depth', 'Profundidad', 'Tiefe', 'Profondità',
'{"options": ["60 cm", "65 cm", "70 cm", "80 cm", "Sur mesure"]}',
'{"required": false}', 3, false, null, null),
(41, 'num_cutouts', 'number', 'Nombre de découpes (évier, plaque)', 'Number of cutouts', 'Número de cortes', 'Anzahl Ausschnitte', 'Numero tagli',
NULL, '{"required": false, "min": 0}', 4, false, '2', 'Évier, plaques, etc.'),
(41, 'edge_profile', 'select', 'Profil de chant', 'Edge profile', 'Perfil de canto', 'Kantenprofil', 'Profilo bordo',
'{"options": ["Droit', "Arrondi", "Biseauté", "Ogee", "Autre"]}',
'{"required": false}', 5, false, null, null);

-- Joinery / Menuiserie (ID 42)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(42, 'work_type', 'checkbox', 'Type de travaux', 'Type of work', 'Tipo de trabajo', 'Art der Arbeit', 'Tipo di lavoro',
'{"options": ["Escalier", "Bibliothèque", "Dressing", "Cuisine", "Meuble TV", "Bureau", "Lambris", "Autre"]}',
'{"required": true}', 1, true, null, null),
(42, 'custom_made', 'radio', 'Sur mesure?', 'Custom made?', '¿A medida?', 'Maßanfertigung?', 'Su misura?',
'{"options": ["Oui", "Non"]}',
'{"required": true}', 2, true, null, null),
(42, 'wood_type', 'select', 'Type de bois', 'Wood type', 'Tipo de madera', 'Holzart', 'Tipo di legno',
'{"options": ["Chêne", "Hêtre", "Pin", "Sapin", "Noyer", "Cerisier", "Exotique", "MDF/Panneau"]}',
'{"required": false}', 3, false, null, null),
(42, 'finish', 'select', 'Finition', 'Finish', 'Acabado', 'Finish', 'Finitura',
'{"options": ["Brut", "Huilé", "Vernis", "Lasuré", "Peint", "Ciré"]}',
'{"required": false}', 4, false, null, null);

-- Custom Furniture / Meubles Sur Mesure (ID 43)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(43, 'furniture_type', 'checkbox', 'Type de meuble', 'Furniture type', 'Tipo de mueble', 'Möbeltyp', 'Tipo di mobile',
'{"options": ["Dressing/Placard", "Bibliothèque", "Bureau", "Table", "Lit", "Banc", "Meuble TV", "Autre"]}',
'{"required": true}', 1, true, null, null),
(43, 'dimensions', 'textarea', 'Dimensions souhaitées', 'Desired dimensions', 'Dimensiones deseadas', 'Gewünschte Maße', 'Dimensioni desiderate',
NULL, '{"required": false}', 2, false, 'L x P x H en cm', 'Exemple: 200 x 60 x 180'),
(43, 'material_preference', 'select', 'Matériau préféré', 'Preferred material', 'Material preferido', 'Bevorzugtes Material', 'Materiale preferito',
'{"options": ["Bois massif", "Médium (MDF)", "Contreplaqué", "Métal", "Verre", "Mixte"]}',
'{"required": false}', 3, false, null, null),
(43, 'design_provided', 'radio', 'Plan/Design fourni?', 'Design provided?', '¿Diseño proporcionado?', 'Design bereitgestellt?', 'Design fornito?',
'{"options": ["Oui", "Non - besoin d\'aide"]}',
'{"required": true}', 4, true, null, null);

-- Closets / Placards (ID 44)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(44, 'closet_type', 'select', 'Type de placard', 'Closet type', 'Tipo de armario', 'Schranktyp', 'Tipo di armadio',
'{"options": ["Sous pente", "Encastré", "Sur mesure", "Standard", "Dressing"]}',
'{"required": true}', 1, true, null, null),
(44, 'width', 'number', 'Largeur (cm)', 'Width (cm)', 'Anchura (cm)', 'Breite (cm)', 'Larghezza (cm)',
NULL, '{"required": true, "min": 50}', 2, true, '200', null),
(44, 'depth', 'number', 'Profondeur (cm)', 'Depth (cm)', 'Profundidad (cm)', 'Tiefe (cm)', 'Profondità (cm)',
NULL, '{"required": true, "min": 30}', 3, true, '60', null),
(44, 'door_type', 'select', 'Type de portes', 'Door type', 'Tipo de puertas', 'Türtyp', 'Tipo di porte',
'{"options": ["Coulissantes", "Battantes", "Pliantes"]}',
'{"required": false}', 4, false, null, null),
(44, 'interior_layout', 'checkbox', 'Aménagement intérieur', 'Interior layout', 'Diseño interior', 'Innenausstattung', 'Allestimento interno',
'{"options": ["Étagères", "Penderie", "Tiroirs", "Paniers", "Porte-chaussures", "Autre"]}',
'{"required": false}', 5, false, null, null);

-- Terrace / Terrasse (ID 46)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(46, 'terrace_type', 'select', 'Type de terrasse', 'Terrace type', 'Tipo de terraza', 'Terrassentyp', 'Tipo di terrazza',
'{"options": ["Bois", "Composite", "Carrelage", "Pierre naturelle", "Béton", "Dalle sur plots"]}',
'{"required": true}', 1, true, null, null),
(46, 'surface_area', 'number', 'Surface (m²)', 'Surface (sqm)', 'Superficie (m²)', 'Fläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 5}', 2, true, '25', null),
(46, 'elevation', 'radio', 'Terrasse surélevée?', 'Elevated terrace?', '¿Terraza elevada?', 'Erhöhte Terrasse?', 'Terrazza rialzata?',
'{"options": ["Oui", "Non"]}',
'{"required": false}', 3, false, null, null),
(46, 'canopy', 'checkbox', 'Couverture souhaitée', 'Canopy desired', 'Cubierta deseada', 'Überdachung gewünscht', 'Copertura desiderata',
'{"options": ["Pergola", "Store", "Auvent", "Aucune"]}',
'{"required": false}', 4, false, null, null);

-- Fence / Clôture (ID 48)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(48, 'fence_type', 'select', 'Type de clôture', 'Fence type', 'Tipo de valla', 'Zauntyp', 'Tipo di recinzione',
'{"options": ["Grillage rigide", "Bois", "PVC", "Aluminium", "Composite", "Mur/Muret", "Haie végétale"]}',
'{"required": true}', 1, true, null, null),
(48, 'linear_length', 'number', 'Longueur linéaire (m)', 'Linear length (m)', 'Longitud lineal (m)', 'Länge linear (m)', 'Lunghezza lineare (m)',
NULL, '{"required": true, "min": 1}', 2, true, '30', null),
(48, 'height', 'select', 'Hauteur', 'Height', 'Altura', 'Höhe', 'Altezza',
'{"options": ["< 1m", "1-1.5m", "1.5-2m", "> 2m"]}',
'{"required": true}', 3, true, null, null),
(48, 'gate_needed', 'radio', 'Portail/Portillon nécessaire?', 'Gate needed?', '¿Puerta necesaria?', 'Tor erforderlich?', 'Cancello necessario?',
'{"options": ["Oui - portail véhicule", "Oui - portillon piéton", "Les deux", "Non"]}',
'{"required": false}', 4, false, null, null);

-- Driveway / Allée (ID 49)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(49, 'surface_type', 'select', 'Type de revêtement', 'Surface type', 'Tipo de pavimento', 'Oberflächentyp', 'Tipo di pavimentazione',
'{"options": ["Enrobé (bitume)", "Béton désactivé", "Béton imprimé", "Pavés", "Gravier", "Dalles", "Résine"]}',
'{"required": true}', 1, true, null, null),
(49, 'surface_area', 'number', 'Surface (m²)', 'Surface (sqm)', 'Superficie (m²)', 'Fläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 10}', 2, true, '50', null),
(49, 'traffic_type', 'select', 'Type de circulation', 'Traffic type', 'Tipo de tráfico', 'Verkehrstyp', 'Tipo di traffico',
'{"options": ["Piéton uniquement", "Voitures légères", "Véhicules lourds"]}',
'{"required": false}', 3, false, null, null),
(49, 'slope_issue', 'radio', 'Problème de pente?', 'Slope issue?', '¿Problema de pendiente?', 'Hangproblem?', 'Problema pendenza?',
'{"options": ["Oui", "Non"]}',
'{"required": false}', 4, false, null, null);

-- Metalwork / Métallerie (ID 51)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(51, 'work_type', 'checkbox', 'Type de travaux', 'Type of work', 'Tipo de trabajo', 'Art der Arbeit', 'Tipo di lavoro',
'{"options": ["Portail", "Grille", "Garde-corps", "Escalier métallique", "Pergola", "Verrière", "Marquise", "Autre"]}',
'{"required": true}', 1, true, null, null),
(51, 'material', 'select', 'Matériau', 'Material', 'Material', 'Material', 'Materiale',
'{"options": ["Acier", "Fer forgé", "Aluminium", "Inox"]}',
'{"required": false}', 2, false, null, null),
(51, 'finish', 'select', 'Finition', 'Finish', 'Acabado', 'Finish', 'Finitura',
'{"options": ["Brut", "Galvanisé", "Thermolaqué", "Peint", "Autre"]}',
'{"required": false}', 3, false, null, null),
(51, 'custom_design', 'radio', 'Fabrication sur mesure?', 'Custom design?', '¿Diseño personalizado?', 'Maßanfertigung?', 'Su misura?',
'{"options": ["Oui", "Non"]}',
'{"required": true}', 4, true, null, null);

-- Glass Work / Vitrerie (ID 52)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(52, 'work_type', 'select', 'Type d\'intervention', 'Type of work', 'Tipo de intervención', 'Art der Arbeit', 'Tipo di intervento',
'{"options": ["Remplacement vitre cassée", "Double vitrage", "Verrière", "Miroir", "Crédence cuisine", "Paroi de douche", "Garde-corps verre", "Autre"]}',
'{"required": true}', 1, true, null, null),
(52, 'glass_type', 'select', 'Type de verre', 'Glass type', 'Tipo de vidrio', 'Glastyp', 'Tipo di vetro',
'{"options": ["Simple vitrage", "Double vitrage", "Triple vitrage", "Feuilleté/Sécurité", "Trempé", "Dépoli/Sablé", "Autre"]}',
'{"required": false}', 2, false, null, null),
(52, 'surface_area', 'number', 'Surface (m²)', 'Surface (sqm)', 'Superficie (m²)', 'Fläche (m²)', 'Superficie (m²)',
NULL, '{"required": false, "min": 0.5}', 3, false, '2', null),
(52, 'urgency', 'radio', 'Urgence', 'Urgency', 'Urgencia', 'Dringlichkeit', 'Urgenza',
'{"options": ["Urgent (vitre cassée)", "Normal"]}',
'{"required": true}', 4, true, null, null);

-- Plastering / Plâtrerie (ID 53)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(53, 'work_type', 'checkbox', 'Type de travaux', 'Type of work', 'Tipo de trabajo', 'Art der Arbeit', 'Tipo di lavoro',
'{"options": ["Cloisons", "Plafond", "Doublage murs", "Isolation", "Enduit", "Bandes/Joints", "Réparation fissures"]}',
'{"required": true}', 1, true, null, null),
(53, 'surface_area', 'number', 'Surface (m²)', 'Surface (sqm)', 'Superficie (m²)', 'Fläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 1}', 2, true, '40', null),
(53, 'material', 'select', 'Matériau', 'Material', 'Material', 'Material', 'Materiale',
'{"options": ["Placo (BA13)", "Placo hydrofuge", "Placo phonique", "Placo haute dureté", "Carreau de plâtre"]}',
'{"required": false}', 3, false, null, null),
(53, 'finish_level', 'select', 'Niveau de finition', 'Finish level', 'Nivel de acabado', 'Oberflächengüte', 'Livello finitura',
'{"options": ["Courant", "Soigné", "Parfait (à peindre)"]}',
'{"required": false}', 4, false, null, null);

-- Asbestos Removal / Désamiantage (ID 54)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(54, 'material_type', 'checkbox', 'Matériaux contenant amiante', 'Asbestos-containing materials', 'Materiales con amianto', 'Asbesthaltige Materialien', 'Materiali con amianto',
'{"options": ["Toiture (fibrociment)", "Conduits", "Dalles de sol", "Flocage", "Calorifugeage", "Faux plafond", "Ne sait pas"]}',
'{"required": true}', 1, true, null, 'Sélectionnez tous les matériaux concernés'),
(54, 'building_year', 'select', 'Année de construction', 'Building year', 'Año de construcción', 'Baujahr', 'Anno di costruzione',
'{"options": ["Avant 1980", "1980-1990", "1990-1997", "Après 1997", "Ne sait pas"]}',
'{"required": true}', 2, true, null, null),
(54, 'diagnostic_done', 'radio', 'Diagnostic amiante réalisé?', 'Asbestos survey done?', '¿Diagnóstico realizado?', 'Asbestgutachten erstellt?', 'Diagnosi effettuata?',
'{"options": ["Oui", "Non"]}',
'{"required": true}', 3, true, null, null),
(54, 'surface_area', 'number', 'Surface (m²)', 'Surface (sqm)', 'Superficie (m²)', 'Fläche (m²)', 'Superficie (m²)',
NULL, '{"required": false, "min": 1}', 4, false, '50', 'Si connue'),
(54, 'building_occupied', 'radio', 'Bâtiment occupé?', 'Building occupied?', '¿Edificio ocupado?', 'Gebäude bewohnt?', 'Edificio occupato?',
'{"options": ["Oui", "Non"]}',
'{"required": false}', 5, false, null, null);

-- Pest Control / Dératisation (ID 55)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(55, 'pest_type', 'checkbox', 'Type de nuisible', 'Pest type', 'Tipo de plaga', 'Schädlingsart', 'Tipo di infestante',
'{"options": ["Rats", "Souris", "Cafards/Blattes", "Punaises de lit", "Termites", "Guêpes/Frelons", "Fourmis", "Autre"]}',
'{"required": true}', 1, true, null, null),
(55, 'property_type', 'select', 'Type de bien', 'Property type', 'Tipo de propiedad', 'Immobilientyp', 'Tipo di proprietà',
'{"options": ["Maison individuelle", "Appartement", "Immeuble", "Local commercial", "Bureau"]}',
'{"required": true}', 2, true, null, null),
(55, 'infestation_level', 'select', 'Niveau d\'infestation', 'Infestation level', 'Nivel de infestación', 'Befallsstärke', 'Livello infestazione',
'{"options": ["Léger (quelques individus)", "Modéré", "Important", "Très important"]}',
'{"required": true}', 3, true, null, null),
(55, 'urgency', 'radio', 'Urgence', 'Urgency', 'Urgencia', 'Dringlichkeit', 'Urgenza',
'{"options": ["Urgent (dans 24h)", "Normal (sous 1 semaine)"]}',
'{"required": true}', 4, true, null, null),
(55, 'treatment_preference', 'select', 'Préférence de traitement', 'Treatment preference', 'Preferencia tratamiento', 'Behandlungspräferenz', 'Preferenza trattamento',
'{"options": ["Chimique", "Bio/Écologique", "Pas de préférence"]}',
'{"required": false}', 5, false, null, null);

-- Elevator / Ascenseur (ID 56)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(56, 'work_type', 'select', 'Type d\'intervention', 'Type of work', 'Tipo de intervención', 'Art der Arbeit', 'Tipo di intervento',
'{"options": ["Installation neuve", "Remplacement", "Modernisation", "Maintenance", "Réparation"]}',
'{"required": true}', 1, true, null, null),
(56, 'elevator_type', 'select', 'Type d\'ascenseur', 'Elevator type', 'Tipo de ascensor', 'Aufzugstyp', 'Tipo di ascensore',
'{"options": ["Hydraulique", "Électrique à câbles", "Électrique sans local", "PMR (handicapés)"]}',
'{"required": false}', 2, false, null, null),
(56, 'num_floors', 'select', 'Nombre d\'étages', 'Number of floors', 'Número de pisos', 'Anzahl Etagen', 'Numero piani',
'{"options": ["2", "3", "4", "5", "6+"]}',
'{"required": true}', 3, true, null, null),
(56, 'capacity', 'select', 'Capacité', 'Capacity', 'Capacidad', 'Kapazität', 'Capacità',
'{"options": ["2-3 personnes", "4-6 personnes", "8-10 personnes", "> 10 personnes"]}',
'{"required": false}', 4, false, null, null);

-- Fire Protection / Protection Incendie (ID 57)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(57, 'system_type', 'checkbox', 'Systèmes souhaités', 'Desired systems', 'Sistemas deseados', 'Gewünschte Systeme', 'Sistemi desiderati',
'{"options": ["Détecteurs de fumée", "Alarme incendie', "Extincteurs", "Sprinklers", "RIA (Robinets d\'incendie)", "Désenfumage", "Porte coupe-feu"]}',
'{"required": true}', 1, true, null, null),
(57, 'building_type', 'select', 'Type de bâtiment', 'Building type', 'Tipo de edificio', 'Gebäudetyp', 'Tipo di edificio',
'{"options": ["Habitation individuelle", "Immeuble d\'habitation", "ERP (Établissement recevant du public)", "Bureau", "Industriel"]}',
'{"required": true}', 2, true, null, null),
(57, 'surface_area', 'number', 'Surface (m²)', 'Surface (sqm)', 'Superficie (m²)', 'Fläche (m²)', 'Superficie (m²)',
NULL, '{"required": false, "min": 10}', 3, false, '200', null),
(57, 'compliance_check', 'radio', 'Vérification conformité?', 'Compliance check?', '¿Verificación conformidad?', 'Konformitätsprüfung?', 'Verifica conformità?',
'{"options": ["Oui", "Non"]}',
'{"required": false}', 4, false, null, null);

-- Security System / Système de Sécurité (ID 58)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(58, 'system_type', 'checkbox', 'Systèmes souhaités', 'Desired systems', 'Sistemas deseados', 'Gewünschte Systeme', 'Sistemi desiderati',
'{"options": ["Alarme intrusion", "Vidéosurveillance", "Contrôle d\'accès", "Interphone/Vidéophone", "Détecteur périmétrique", "Télésurveillance"]}',
'{"required": true}', 1, true, null, null),
(58, 'property_type', 'select', 'Type de bien', 'Property type', 'Tipo de propiedad', 'Immobilientyp', 'Tipo di proprietà',
'{"options": ["Maison", "Appartement", "Commerce", "Bureau", "Entrepôt"]}',
'{"required": true}', 2, true, null, null),
(58, 'num_cameras', 'number', 'Nombre de caméras', 'Number of cameras', 'Número de cámaras', 'Anzahl Kameras', 'Numero telecamere',
NULL, '{"required": false, "min": 1}', 3, false, '4', 'Si vidéosurveillance'),
(58, 'num_sensors', 'number', 'Nombre de détecteurs', 'Number of sensors', 'Número de detectores', 'Anzahl Sensoren', 'Numero sensori',
NULL, '{"required": false, "min": 1}', 4, false, '8', 'Si alarme intrusion'),
(58, 'monitoring', 'radio', 'Télésurveillance souhaitée?', 'Monitoring desired?', '¿Monitoreo deseado?', 'Überwachung gewünscht?', 'Monitoraggio?',
'{"options": ["Oui", "Non"]}',
'{"required": false}', 5, false, null, null);

-- Post-Construction Cleaning / Nettoyage Après Travaux (ID 60)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(60, 'property_type', 'select', 'Type de bien', 'Property type', 'Tipo de propiedad', 'Immobilientyp', 'Tipo di proprietà',
'{"options": ["Appartement", "Maison", "Bureau", "Commerce", "Chantier"]}',
'{"required": true}', 1, true, null, null),
(60, 'surface_area', 'number', 'Surface (m²)', 'Surface (sqm)', 'Superficie (m²)', 'Fläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 10}', 2, true, '100', null),
(60, 'work_type', 'checkbox', 'Type de travaux effectués', 'Work completed', 'Trabajos realizados', 'Durchgeführte Arbeiten', 'Lavori effettuati',
'{"options": ["Peinture", "Carrelage", "Plâtrerie", "Menuiserie", "Démolition", "Rénovation complète", "Autre"]}',
'{"required": false}', 3, false, null, 'Pour adapter le nettoyage'),
(60, 'cleaning_level', 'select', 'Niveau de nettoyage', 'Cleaning level', 'Nivel de limpieza', 'Reinigungsstufe', 'Livello pulizia',
'{"options": ["Basique (balayage, poussière)', "Standard (nettoyage complet)", "Approfondi (avec vitres)"]}',
'{"required": true}', 4, true, null, null),
(60, 'waste_removal', 'radio', 'Évacuation gravats/déchets?', 'Waste removal?', '¿Retirada escombros?', 'Entsorgung?', 'Rimozione macerie?',
'{"options": ["Oui", "Non - déjà fait"]}',
'{"required": true}', 5, true, null, null);
