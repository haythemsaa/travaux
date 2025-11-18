-- =====================================================
-- CUSTOM FORM FIELDS FOR EACH TRADE
-- Specific questionnaires for each profession
-- =====================================================

-- General Contracting / Maçonnerie
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(1, 'project_type', 'select', 'Type de projet', 'Project type', 'Tipo de proyecto', 'Projekttyp', 'Tipo di progetto',
'{"options": ["Construction neuve", "Extension", "Rénovation", "Réparation"]}',
'{"required": true}', 1, true),
(1, 'building_floors', 'number', 'Nombre d\'étages', 'Number of floors', 'Número de pisos', 'Anzahl der Etagen', 'Numero di piani',
NULL, '{"required": true, "min": 1, "max": 10}', 2, true),
(1, 'surface_area', 'number', 'Surface (m²)', 'Surface area (sqm)', 'Superficie (m²)', 'Fläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 1}', 3, true),
(1, 'has_permit', 'radio', 'Permis de construire obtenu?', 'Building permit obtained?', '¿Permiso obtenido?', 'Baugenehmigung?', 'Permesso ottenuto?',
'{"options": ["Oui", "Non", "En cours"]}', '{"required": true}', 4, true);

-- Roofing / Couverture
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(6, 'roof_type', 'select', 'Type de toiture', 'Roof type', 'Tipo de techo', 'Dachart', 'Tipo di tetto',
'{"options": ["Toiture en pente", "Toiture plate", "Toiture arrondie", "Autre"]}',
'{"required": true}', 1, true),
(6, 'roof_surface', 'number', 'Surface de toiture (m²)', 'Roof surface (sqm)', 'Superficie del techo (m²)', 'Dachfläche (m²)', 'Superficie tetto (m²)',
NULL, '{"required": true, "min": 10}', 2, true),
(6, 'material_preference', 'select', 'Matériau souhaité', 'Preferred material', 'Material preferido', 'Bevorzugtes Material', 'Materiale preferito',
'{"options": ["Tuiles terre cuite", "Tuiles béton", "Ardoise", "Zinc", "Tôle", "Shingle", "Autre"]}',
'{"required": false}', 3, false),
(6, 'insulation_needed', 'checkbox', 'Isolation nécessaire', 'Insulation needed', 'Aislamiento necesario', 'Dämmung erforderlich', 'Isolamento necessario',
NULL, '{"required": false}', 4, false),
(6, 'has_leak', 'radio', 'Fuite existante?', 'Existing leak?', '¿Filtración existente?', 'Bestehende Undichtigkeit?', 'Perdita esistente?',
'{"options": ["Oui", "Non"]}', '{"required": true}', 5, true);

-- Electrical / Électricité
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(15, 'work_type', 'select', 'Type de travaux', 'Type of work', 'Tipo de trabajo', 'Art der Arbeit', 'Tipo di lavoro',
'{"options": ["Installation complète", "Mise aux normes", "Réparation", "Ajout de prises/interrupteurs", "Tableau électrique"]}',
'{"required": true}', 1, true),
(15, 'property_size', 'number', 'Surface du bien (m²)', 'Property size (sqm)', 'Tamaño (m²)', 'Grundstücksgröße (m²)', 'Dimensioni (m²)',
NULL, '{"required": true, "min": 10}', 2, true),
(15, 'num_rooms', 'number', 'Nombre de pièces', 'Number of rooms', 'Número de habitaciones', 'Anzahl der Räume', 'Numero di stanze',
NULL, '{"required": true, "min": 1}', 3, true),
(15, 'panel_upgrade', 'checkbox', 'Mise à niveau du tableau', 'Panel upgrade', 'Actualización del panel', 'Panel-Upgrade', 'Aggiornamento quadro',
NULL, '{"required": false}', 4, false),
(15, 'power_capacity', 'select', 'Puissance souhaitée', 'Desired capacity', 'Capacidad deseada', 'Gewünschte Kapazität', 'Capacità desiderata',
'{"options": ["6 kVA", "9 kVA", "12 kVA", "15 kVA", "18 kVA ou plus"]}',
'{"required": false}', 5, false);

-- Plumbing / Plomberie
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(19, 'work_type', 'select', 'Type d\'intervention', 'Type of work', 'Tipo de intervención', 'Art der Arbeit', 'Tipo di intervento',
'{"options": ["Installation complète", "Réparation", "Remplacement", "Urgence/Fuite", "Débouchage"]}',
'{"required": true}', 1, true),
(19, 'num_bathrooms', 'number', 'Nombre de salles de bain', 'Number of bathrooms', 'Número de baños', 'Anzahl Badezimmer', 'Numero bagni',
NULL, '{"required": false, "min": 0}', 2, false),
(19, 'num_toilets', 'number', 'Nombre de WC', 'Number of toilets', 'Número de WC', 'Anzahl WC', 'Numero WC',
NULL, '{"required": false, "min": 0}', 3, false),
(19, 'has_leak', 'radio', 'Fuite d\'eau?', 'Water leak?', '¿Fuga de agua?', 'Wasserleck?', 'Perdita d\'acqua?',
'{"options": ["Oui - Urgent", "Oui - Non urgent", "Non"]}',
'{"required": true}', 4, true);

-- Heating / Chauffage
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(20, 'heating_type', 'select', 'Type de chauffage', 'Heating type', 'Tipo de calefacción', 'Heizungsart', 'Tipo di riscaldamento',
'{"options": ["Chaudière gaz", "Chaudière fioul", "Pompe à chaleur", "Radiateurs électriques", "Chauffage au sol", "Autre"]}',
'{"required": true}', 1, true),
(20, 'property_area', 'number', 'Surface à chauffer (m²)', 'Area to heat (sqm)', 'Área a calentar (m²)', 'Zu heizende Fläche (m²)', 'Area da riscaldare (m²)',
NULL, '{"required": true, "min": 10}', 2, true),
(20, 'current_system_age', 'number', 'Âge du système actuel (ans)', 'Current system age (years)', 'Edad del sistema (años)', 'Alter des Systems (Jahre)', 'Età del sistema (anni)',
NULL, '{"required": false, "min": 0}', 3, false),
(20, 'eco_friendly', 'checkbox', 'Intéressé par solution écologique', 'Interested in eco-friendly', 'Interesado en ecológico', 'Umweltfreundlich', 'Interessato a ecologico',
NULL, '{"required": false}', 4, false);

-- HVAC / Climatisation
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(21, 'system_type', 'select', 'Type de système', 'System type', 'Tipo de sistema', 'Systemtyp', 'Tipo di sistema',
'{"options": ["Split", "Multi-split", "Gainable", "Cassette", "Réversible"]}',
'{"required": true}', 1, true),
(21, 'num_rooms', 'number', 'Nombre de pièces à climatiser', 'Rooms to cool', 'Habitaciones a climatizar', 'Zu kühlende Räume', 'Stanze da climatizzare',
NULL, '{"required": true, "min": 1}', 2, true),
(21, 'total_area', 'number', 'Surface totale (m²)', 'Total area (sqm)', 'Área total (m²)', 'Gesamtfläche (m²)', 'Superficie totale (m²)',
NULL, '{"required": true, "min": 10}', 3, true),
(21, 'heating_function', 'checkbox', 'Fonction chauffage souhaitée', 'Heating function desired', 'Función de calefacción', 'Heizfunktion', 'Funzione riscaldamento',
NULL, '{"required": false}', 4, false);

-- Insulation / Isolation
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(25, 'insulation_area', 'select', 'Zone à isoler', 'Area to insulate', 'Área a aislar', 'Zu dämmende Zone', 'Area da isolare',
'{"options": ["Combles perdus", "Combles aménagés", "Murs intérieurs", "Murs extérieurs", "Plancher", "Toiture", "Sous-sol"]}',
'{"required": true}', 1, true),
(25, 'surface_area', 'number', 'Surface (m²)', 'Surface (sqm)', 'Superficie (m²)', 'Fläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 1}', 2, true),
(25, 'current_insulation', 'radio', 'Isolation existante?', 'Existing insulation?', '¿Aislamiento existente?', 'Vorhandene Dämmung?', 'Isolamento esistente?',
'{"options": ["Oui", "Non", "Ne sait pas"]}',
'{"required": true}', 3, true),
(25, 'energy_goals', 'checkbox', 'Objectif économies d\'énergie', 'Energy savings goal', 'Objetivo ahorro energético', 'Energiesparziel', 'Obiettivo risparmio energetico',
NULL, '{"required": false}', 4, false);

-- Windows / Fenêtres
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(27, 'num_windows', 'number', 'Nombre de fenêtres', 'Number of windows', 'Número de ventanas', 'Anzahl Fenster', 'Numero finestre',
NULL, '{"required": true, "min": 1}', 1, true),
(27, 'window_type', 'select', 'Type de fenêtre', 'Window type', 'Tipo de ventana', 'Fenstertyp', 'Tipo di finestra',
'{"options": ["PVC", "Aluminium", "Bois", "Mixte bois/alu"]}',
'{"required": true}', 2, true),
(27, 'glazing', 'select', 'Type de vitrage', 'Glazing type', 'Tipo de acristalamiento', 'Verglasungstyp', 'Tipo di vetro',
'{"options": ["Simple vitrage", "Double vitrage", "Triple vitrage", "Vitrage phonique"]}',
'{"required": true}', 3, true),
(27, 'opening_type', 'select', 'Type d\'ouverture', 'Opening type', 'Tipo de apertura', 'Öffnungsart', 'Tipo di apertura',
'{"options": ["Oscillo-battant", "Coulissant", "À la française", "Fixe", "Basculant"]}',
'{"required": false}', 4, false);

-- Kitchen / Cuisine
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(39, 'kitchen_size', 'number', 'Surface de la cuisine (m²)', 'Kitchen size (sqm)', 'Tamaño cocina (m²)', 'Küchengröße (m²)', 'Dimensioni cucina (m²)',
NULL, '{"required": true, "min": 4}', 1, true),
(39, 'layout_type', 'select', 'Type d\'agencement', 'Layout type', 'Tipo de distribución', 'Layout-Typ', 'Tipo di layout',
'{"options": ["Linéaire", "En L", "En U", "Avec îlot", "Parallèle"]}',
'{"required": true}', 2, true),
(39, 'cabinet_material', 'select', 'Matériau meubles', 'Cabinet material', 'Material muebles', 'Schrankstoff', 'Materiale mobili',
'{"options": ["Mélaminé", "Stratifié", "Bois massif", "Laqué", "Autre"]}',
'{"required": false}', 3, false),
(39, 'countertop_material', 'select', 'Matériau plan de travail', 'Countertop material', 'Material encimera', 'Arbeitsplattenmaterial', 'Materiale piano',
'{"options": ["Stratifié", "Quartz", "Granit", "Marbre", "Bois", "Inox", "Céramique"]}',
'{"required": false}', 4, false),
(39, 'appliances_included', 'checkbox', 'Électroménager inclus', 'Appliances included', 'Electrodomésticos incluidos', 'Geräte enthalten', 'Elettrodomestici inclusi',
NULL, '{"required": false}', 5, false);

-- Bathroom / Salle de bain
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(40, 'bathroom_size', 'number', 'Surface (m²)', 'Size (sqm)', 'Tamaño (m²)', 'Größe (m²)', 'Dimensioni (m²)',
NULL, '{"required": true, "min": 2}', 1, true),
(40, 'bath_type', 'select', 'Type de baignoire/douche', 'Bath/shower type', 'Tipo de bañera/ducha', 'Bad-/Duschtyp', 'Tipo vasca/doccia',
'{"options": ["Douche à l\'italienne", "Cabine de douche", "Baignoire", "Baignoire + douche", "Aucun"]}',
'{"required": true}', 2, true),
(40, 'num_sinks', 'select', 'Nombre de vasques', 'Number of sinks', 'Número de lavabos', 'Anzahl Waschbecken', 'Numero lavabi',
'{"options": ["1", "2", "3+"]}',
'{"required": true}', 3, true),
(40, 'toilet_included', 'checkbox', 'WC inclus', 'Toilet included', 'WC incluido', 'WC enthalten', 'WC incluso',
NULL, '{"required": false}', 4, false),
(40, 'tile_walls', 'checkbox', 'Carrelage mural', 'Wall tiling', 'Azulejos paredes', 'Wandfliesen', 'Piastrelle pareti',
NULL, '{"required": false}', 5, false),
(40, 'tile_floor', 'checkbox', 'Carrelage sol', 'Floor tiling', 'Azulejos suelo', 'Bodenfliesen', 'Piastrelle pavimento',
NULL, '{"required": false}', 6, false);

-- Painting / Peinture
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(36, 'total_area', 'number', 'Surface totale (m²)', 'Total area (sqm)', 'Área total (m²)', 'Gesamtfläche (m²)', 'Superficie totale (m²)',
NULL, '{"required": true, "min": 5}', 1, true),
(36, 'num_rooms', 'number', 'Nombre de pièces', 'Number of rooms', 'Número de habitaciones', 'Anzahl Räume', 'Numero stanze',
NULL, '{"required": true, "min": 1}', 2, true),
(36, 'paint_type', 'select', 'Type de peinture', 'Paint type', 'Tipo de pintura', 'Farbtyp', 'Tipo di vernice',
'{"options": ["Acrylique", "Glycéro", "Écologique", "Effet décoratif"]}',
'{"required": false}', 3, false),
(36, 'ceiling_included', 'checkbox', 'Plafond inclus', 'Ceiling included', 'Techo incluido', 'Decke enthalten', 'Soffitto incluso',
NULL, '{"required": false}', 4, false),
(36, 'woodwork', 'checkbox', 'Boiseries à peindre', 'Woodwork to paint', 'Carpintería a pintar', 'Holzarbeiten', 'Falegnameria da verniciare',
NULL, '{"required": false}', 5, false);

-- Flooring / Sols
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(31, 'floor_area', 'number', 'Surface (m²)', 'Floor area (sqm)', 'Superficie (m²)', 'Bodenfläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 5}', 1, true),
(31, 'floor_type', 'select', 'Type de revêtement', 'Flooring type', 'Tipo de suelo', 'Bodenbelagstyp', 'Tipo di pavimento',
'{"options": ["Carrelage", "Parquet massif", "Parquet flottant", "Stratifié", "Vinyle/PVC", "Moquette", "Béton ciré"]}',
'{"required": true}', 2, true),
(31, 'room_type', 'select', 'Type de pièce', 'Room type', 'Tipo de habitación', 'Raumtyp', 'Tipo di stanza',
'{"options": ["Salon", "Chambre", "Cuisine", "Salle de bain", "Couloir", "Bureau", "Autre"]}',
'{"required": false}', 3, false),
(31, 'underlay_needed', 'checkbox', 'Sous-couche nécessaire', 'Underlay needed', 'Subcapa necesaria', 'Unterlage erforderlich', 'Sottofondo necessario',
NULL, '{"required": false}', 4, false),
(31, 'remove_old_floor', 'checkbox', 'Retirer ancien revêtement', 'Remove old flooring', 'Quitar suelo antiguo', 'Alten Boden entfernen', 'Rimuovere pavimento vecchio',
NULL, '{"required": false}', 5, false);

-- Landscaping / Aménagement paysager
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(45, 'garden_size', 'number', 'Surface du jardin (m²)', 'Garden size (sqm)', 'Tamaño jardín (m²)', 'Gartengröße (m²)', 'Dimensioni giardino (m²)',
NULL, '{"required": true, "min": 10}', 1, true),
(45, 'work_type', 'checkbox', 'Type de travaux', 'Type of work', 'Tipo de trabajo', 'Art der Arbeit', 'Tipo di lavoro',
'{"options": ["Pelouse", "Plantation", "Clôture", "Terrasse", "Éclairage", "Arrosage automatique", "Allée"]}',
'{"required": true}', 2, true),
(45, 'maintenance', 'radio', 'Entretien régulier souhaité?', 'Regular maintenance desired?', '¿Mantenimiento regular?', 'Regelmäßige Wartung?', 'Manutenzione regolare?',
'{"options": ["Oui", "Non"]}',
'{"required": false}', 3, false);

-- Pool / Piscine
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(47, 'pool_type', 'select', 'Type de piscine', 'Pool type', 'Tipo de piscina', 'Pooltyp', 'Tipo di piscina',
'{"options": ["Enterrée béton", "Coque polyester", "Hors-sol", "Semi-enterrée", "Piscine naturelle"]}',
'{"required": true}', 1, true),
(47, 'pool_size', 'select', 'Dimensions', 'Size', 'Dimensiones', 'Größe', 'Dimensioni',
'{"options": ["< 20 m²", "20-40 m²", "40-60 m²", "> 60 m²"]}',
'{"required": true}', 2, true),
(47, 'pool_depth', 'select', 'Profondeur', 'Depth', 'Profundidad', 'Tiefe', 'Profondità',
'{"options": ["< 1.2m", "1.2-1.5m", "> 1.5m"]}',
'{"required": false}', 3, false),
(47, 'heating', 'checkbox', 'Chauffage piscine', 'Pool heating', 'Calefacción', 'Heizung', 'Riscaldamento',
NULL, '{"required": false}', 4, false),
(47, 'cover', 'checkbox', 'Couverture/Volet', 'Cover/Shutter', 'Cubierta', 'Abdeckung', 'Copertura',
NULL, '{"required": false}', 5, false);

-- Solar Panels / Panneaux solaires
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(18, 'roof_type', 'select', 'Type de toiture', 'Roof type', 'Tipo de tejado', 'Dachart', 'Tipo di tetto',
'{"options": ["Tuiles", "Ardoise", "Tôle", "Toiture plate", "Autre"]}',
'{"required": true}', 1, true),
(18, 'roof_orientation', 'select', 'Orientation', 'Orientation', 'Orientación', 'Ausrichtung', 'Orientamento',
'{"options": ["Sud", "Sud-Est", "Sud-Ouest", "Est", "Ouest", "Autre"]}',
'{"required": true}', 2, true),
(18, 'roof_slope', 'select', 'Inclinaison', 'Slope', 'Inclinación', 'Neigung', 'Inclinazione',
'{"options": ["0-15°", "15-30°", "30-45°", "> 45°"]}',
'{"required": false}', 3, false),
(18, 'installation_size', 'select', 'Puissance souhaitée', 'Desired capacity', 'Potencia deseada', 'Gewünschte Leistung', 'Potenza desiderata',
'{"options": ["3 kWc", "6 kWc", "9 kWc", "> 9 kWc"]}',
'{"required": true}', 4, true),
(18, 'battery_storage', 'checkbox', 'Stockage par batterie', 'Battery storage', 'Almacenamiento', 'Batteriespeicher', 'Accumulo batteria',
NULL, '{"required": false}', 5, false);

-- Locksmith / Serrurerie
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(50, 'work_type', 'select', 'Type d\'intervention', 'Type of work', 'Tipo de intervención', 'Art der Arbeit', 'Tipo di intervento',
'{"options": ["Porte claquée", "Clé cassée", "Changement serrure", "Blindage porte", "Ouverture coffre", "Installation serrure"]}',
'{"required": true}', 1, true),
(50, 'urgency', 'radio', 'Urgence', 'Urgency', 'Urgencia', 'Dringlichkeit', 'Urgenza',
'{"options": ["Urgent (enfermé dehors)", "Normal"]}',
'{"required": true}', 2, true),
(50, 'num_locks', 'number', 'Nombre de serrures', 'Number of locks', 'Número de cerraduras', 'Anzahl Schlösser', 'Numero serrature',
NULL, '{"required": false, "min": 1}', 3, false),
(50, 'security_level', 'select', 'Niveau de sécurité', 'Security level', 'Nivel de seguridad', 'Sicherheitsstufe', 'Livello sicurezza',
'{"options": ["Standard", "Renforcée", "Haute sécurité", "Certifiée A2P"]}',
'{"required": false}', 4, false);

-- Moving / Déménagement
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required) VALUES
(59, 'property_size', 'select', 'Type de logement', 'Property type', 'Tipo de vivienda', 'Wohnungstyp', 'Tipo di alloggio',
'{"options": ["Studio", "T2/F2", "T3/F3", "T4/F4", "T5+/F5+", "Maison"]}',
'{"required": true}', 1, true),
(59, 'volume', 'number', 'Volume estimé (m³)', 'Estimated volume (m³)', 'Volumen estimado (m³)', 'Geschätztes Volumen (m³)', 'Volume stimato (m³)',
NULL, '{"required": false, "min": 1}', 2, false),
(59, 'distance', 'number', 'Distance (km)', 'Distance (km)', 'Distancia (km)', 'Entfernung (km)', 'Distanza (km)',
NULL, '{"required": true, "min": 1}', 3, true),
(59, 'floor_origin', 'select', 'Étage départ', 'Origin floor', 'Piso origen', 'Ausgangsgeschoss', 'Piano partenza',
'{"options": ["RDC", "1er étage", "2ème étage", "3ème étage", "4+ étage"]}',
'{"required": true}', 4, true),
(59, 'floor_destination', 'select', 'Étage arrivée', 'Destination floor', 'Piso destino', 'Zielgeschoss', 'Piano arrivo',
'{"options": ["RDC", "1er étage", "2ème étage", "3ème étage", "4+ étage"]}',
'{"required": true}', 5, true),
(59, 'elevator', 'checkbox', 'Ascenseur disponible', 'Elevator available', 'Ascensor disponible', 'Aufzug verfügbar', 'Ascensore disponibile',
NULL, '{"required": false}', 6, false),
(59, 'packing', 'checkbox', 'Emballage souhaité', 'Packing desired', 'Embalaje deseado', 'Verpackung gewünscht', 'Imballaggio desiderato',
NULL, '{"required": false}', 7, false);
