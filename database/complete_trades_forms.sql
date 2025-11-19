-- =====================================================
-- COMPLETE TRADES WITH DETAILED CUSTOM FORMS
-- Additional trades and comprehensive questionnaires
-- =====================================================

-- Painting / Peinture (Category 3)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(3, 'paint_type', 'select', 'Type de peinture', 'Paint type', 'Tipo de pintura', 'Farbtyp', 'Tipo di pittura',
'{"options": ["Intérieure", "Extérieure", "Les deux"]}',
'{"required": true}', 1, true, null, 'Sélectionnez le type de peinture nécessaire'),
(3, 'surface_area', 'number', 'Surface totale à peindre (m²)', 'Total surface (sqm)', 'Superficie total (m²)', 'Gesamtfläche (m²)', 'Superficie totale (m²)',
NULL, '{"required": true, "min": 1}', 2, true, '100', 'Indiquez la surface approximative'),
(3, 'num_rooms', 'number', 'Nombre de pièces', 'Number of rooms', 'Número de habitaciones', 'Anzahl der Räume', 'Numero di stanze',
NULL, '{"required": false, "min": 1}', 3, false, '3', null),
(3, 'ceiling_height', 'select', 'Hauteur sous plafond', 'Ceiling height', 'Altura del techo', 'Deckenhöhe', 'Altezza soffitto',
'{"options": ["Standard (< 2.5m)", "Haute (2.5-3.5m)", "Très haute (> 3.5m)"]}',
'{"required": false}', 4, false, null, null),
(3, 'finish_type', 'select', 'Type de finition', 'Finish type', 'Tipo de acabado', 'Oberflächentyp', 'Tipo di finitura',
'{"options": ["Mate", "Satinée", "Brillante", "Velours"]}',
'{"required": false}', 5, false, null, null),
(3, 'prep_work', 'checkbox', 'Travaux de préparation', 'Prep work needed', 'Preparación necesaria', 'Vorarbeiten', 'Preparazione necessaria',
'{"options": ["Rebouchage", "Ponçage", "Lessivage", "Dépose ancien revêtement"]}',
'{"required": false}', 6, false, null, 'Cochez tous les travaux nécessaires'),
(3, 'color_advice', 'radio', 'Conseil couleur souhaité?', 'Color advice needed?', '¿Asesoramiento de color?', 'Farbberatung?', 'Consulenza colore?',
'{"options": ["Oui", "Non"]}',
'{"required": false}', 7, false, null, null);

-- Carpentry / Menuiserie (Category 8)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(8, 'work_type', 'checkbox', 'Type de travaux', 'Type of work', 'Tipo de trabajo', 'Art der Arbeit', 'Tipo di lavoro',
'{"options": ["Portes", "Fenêtres", "Escalier", "Placard", "Parquet", "Terrasse bois", "Pergola", "Autre"]}',
'{"required": true}', 1, true, null, 'Sélectionnez tous les travaux concernés'),
(8, 'wood_preference', 'select', 'Type de bois préféré', 'Wood preference', 'Preferencia de madera', 'Holzpräferenz', 'Preferenza legno',
'{"options": ["Chêne", "Pin", "Sapin", "Hêtre", "Exotique (Teck, Iroko...)", "MDF", "Pas de préférence"]}',
'{"required": false}', 2, false, null, null),
(8, 'num_doors', 'number', 'Nombre de portes', 'Number of doors', 'Número de puertas', 'Anzahl Türen', 'Numero porte',
NULL, '{"required": false, "min": 0}', 3, false, '0', 'Si concerné'),
(8, 'num_windows', 'number', 'Nombre de fenêtres', 'Number of windows', 'Número de ventanas', 'Anzahl Fenster', 'Numero finestre',
NULL, '{"required": false, "min": 0}', 4, false, '0', 'Si concerné'),
(8, 'custom_design', 'radio', 'Fabrication sur mesure?', 'Custom design?', '¿Diseño personalizado?', 'Maßanfertigung?', 'Su misura?',
'{"options": ["Oui", "Non", "À discuter"]}',
'{"required": true}', 5, true, null, null),
(8, 'finish_treatment', 'select', 'Traitement/Finition', 'Finish/Treatment', 'Tratamiento/Acabado', 'Behandlung/Finish', 'Trattamento/Finitura',
'{"options": ["Lasure", "Peinture", "Vernis", "Huile", "Naturel", "Autre"]}',
'{"required": false}', 6, false, null, null);

-- Locksmith / Serrurerie (Category 7)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(7, 'service_type', 'select', 'Type de service', 'Service type', 'Tipo de servicio', 'Servicetyp', 'Tipo di servizio',
'{"options": ["Urgence (porte claquée/clé cassée)", "Installation serrure", "Remplacement serrure", "Blindage porte", "Portail/Grille", "Autre"]}',
'{"required": true}', 1, true, null, null),
(7, 'is_emergency', 'radio', 'Intervention urgente?', 'Emergency?', '¿Emergencia?', 'Notfall?', 'Emergenza?',
'{"options": ["Oui - Je suis bloqué dehors", "Non - Peut attendre"]}',
'{"required": true}', 2, true, null, null),
(7, 'lock_type', 'select', 'Type de serrure', 'Lock type', 'Tipo de cerradura', 'Schlosstyp', 'Tipo di serratura',
'{"options": ["Serrure simple", "Serrure multipoints (3 points)", "Serrure multipoints (5 points ou +)", "Serrure connectée", "Ne sais pas"]}',
'{"required": false}', 3, false, null, null),
(7, 'security_level', 'select', 'Niveau de sécurité souhaité', 'Security level', 'Nivel de seguridad', 'Sicherheitsstufe', 'Livello di sicurezza',
'{"options": ["Standard", "Renforcé", "Haute sécurité (A2P)"]}',
'{"required": false}', 4, false, null, 'Pour installation/remplacement'),
(7, 'num_doors', 'number', 'Nombre de portes', 'Number of doors', 'Número de puertas', 'Anzahl Türen', 'Numero porte',
NULL, '{"required": false, "min": 1}', 5, false, '1', null);

-- Flooring / Revêtement de sol (Category 9)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(9, 'flooring_type', 'select', 'Type de revêtement', 'Flooring type', 'Tipo de suelo', 'Bodenbelagstyp', 'Tipo di pavimento',
'{"options": ["Parquet massif", "Parquet stratifié", "Carrelage", "Pierre naturelle", "Vinyle/Lino", "Moquette", "Béton ciré", "Résine", "Autre"]}',
'{"required": true}', 1, true, null, null),
(9, 'surface_area', 'number', 'Surface totale (m²)', 'Total surface (sqm)', 'Superficie total (m²)', 'Gesamtfläche (m²)', 'Superficie totale (m²)',
NULL, '{"required": true, "min": 1}', 2, true, '50', null),
(9, 'num_rooms', 'number', 'Nombre de pièces', 'Number of rooms', 'Número de habitaciones', 'Anzahl Räume', 'Numero stanze',
NULL, '{"required": false, "min": 1}', 3, false, '1', null),
(9, 'remove_old', 'radio', 'Dépose ancien revêtement?', 'Remove old flooring?', '¿Quitar suelo viejo?', 'Alten Boden entfernen?', 'Rimuovere vecchio?',
'{"options": ["Oui", "Non", "Déjà fait"]}',
'{"required": true}', 4, true, null, null),
(9, 'floor_preparation', 'checkbox', 'Préparation sol nécessaire', 'Floor prep needed', 'Preparación necesaria', 'Bodenvorbereitung', 'Preparazione necessaria',
'{"options": ["Ragréage", "Isolation phonique", "Isolation thermique", "Traitement humidité"]}',
'{"required": false}', 5, false, null, 'Cochez si nécessaire'),
(9, 'underfloor_heating', 'radio', 'Chauffage au sol?', 'Underfloor heating?', '¿Calefacción por suelo?', 'Fußbodenheizung?', 'Riscaldamento a pavimento?',
'{"options": ["Oui - Existant", "Oui - À installer", "Non"]}',
'{"required": false}', 6, false, null, null);

-- Tiling / Carrelage (Category 10)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(10, 'installation_area', 'checkbox', 'Pièces concernées', 'Rooms', 'Habitaciones', 'Räume', 'Stanze',
'{"options": ["Salle de bain", "Cuisine", "WC", "Séjour", "Chambre", "Terrasse", "Autre"]}',
'{"required": true}', 1, true, null, 'Sélectionnez toutes les pièces'),
(10, 'surface_area', 'number', 'Surface totale (m²)', 'Total surface (sqm)', 'Superficie total (m²)', 'Gesamtfläche (m²)', 'Superficie totale (m²)',
NULL, '{"required": true, "min": 1}', 2, true, '20', null),
(10, 'tile_type', 'select', 'Type de carrelage', 'Tile type', 'Tipo de baldosa', 'Fliesentyp', 'Tipo di piastrella',
'{"options": ["Céramique", "Grès cérame", "Faïence", "Pierre naturelle", "Mosaïque", "Grand format (60x60+)", "Ne sais pas"]}',
'{"required": false}', 3, false, null, null),
(10, 'wall_tiling', 'radio', 'Carrelage mural?', 'Wall tiling?', '¿Azulejo de pared?', 'Wandfliesen?', 'Piastrelle a parete?',
'{"options": ["Oui", "Non", "Sol uniquement"]}',
'{"required": true}', 4, true, null, null),
(10, 'remove_old', 'radio', 'Dépose ancien carrelage?', 'Remove old tiles?', '¿Quitar azulejos viejos?', 'Alte Fliesen entfernen?', 'Rimuovere vecchie?',
'{"options": ["Oui", "Non"]}',
'{"required": true}', 5, true, null, null),
(10, 'special_pattern', 'radio', 'Pose spéciale?', 'Special pattern?', '¿Patrón especial?', 'Spezielles Muster?', 'Motivo speciale?',
'{"options": ["Pose droite simple", "Pose en diagonale", "Pose en chevron", "Motif personnalisé"]}',
'{"required": false}', 6, false, null, null);

-- Landscaping / Aménagement paysager (Category 26)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(26, 'work_type', 'checkbox', 'Travaux souhaités', 'Desired work', 'Trabajos deseados', 'Gewünschte Arbeiten', 'Lavori desiderati',
'{"options": ["Création pelouse", "Plantation arbres/arbustes", "Terrasse", "Clôture", "Arrosage automatique", "Éclairage extérieur", "Bassin/Fontaine", "Allée", "Enrochement"]}',
'{"required": true}', 1, true, null, 'Sélectionnez tous les travaux'),
(26, 'garden_size', 'number', 'Surface jardin (m²)', 'Garden size (sqm)', 'Tamaño jardín (m²)', 'Gartengröße (m²)', 'Dimensione giardino (m²)',
NULL, '{"required": true, "min": 10}', 2, true, '200', null),
(26, 'terrain_type', 'select', 'Type de terrain', 'Terrain type', 'Tipo de terreno', 'Geländetyp', 'Tipo di terreno',
'{"options": ["Plat", "En pente douce", "En pente forte", "Vallonné"]}',
'{"required": false}', 3, false, null, null),
(26, 'garden_style', 'select', 'Style souhaité', 'Desired style', 'Estilo deseado', 'Gewünschter Stil', 'Stile desiderato',
'{"options": ["Moderne/Contemporain", "Japonais/Zen", "Méditerranéen", "Anglais", "Français", "Naturel/Sauvage", "Minimaliste"]}',
'{"required": false}', 4, false, null, null),
(26, 'maintenance_level', 'select', 'Niveau d\'entretien souhaité', 'Maintenance level', 'Nivel de mantenimiento', 'Wartungsniveau', 'Livello manutenzione',
'{"options": ["Faible entretien", "Entretien modéré", "Entretien régulier"]}',
'{"required": false}', 5, false, null, null),
(26, 'has_plans', 'radio', 'Plans déjà réalisés?', 'Plans ready?', '¿Planos listos?', 'Pläne fertig?', 'Piani pronti?',
'{"options": ["Oui", "Non - besoin paysagiste"]}',
'{"required": false}', 6, false, null, null);

-- Pool / Piscine (Category 36)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(36, 'pool_type', 'select', 'Type de piscine', 'Pool type', 'Tipo de piscina', 'Pooltyp', 'Tipo di piscina',
'{"options": ["Piscine enterrée", "Piscine semi-enterrée", "Piscine hors-sol", "Piscine naturelle", "Bassin de nage"]}',
'{"required": true}', 1, true, null, null),
(36, 'pool_size', 'select', 'Dimensions souhaitées', 'Desired size', 'Tamaño deseado', 'Gewünschte Größe', 'Dimensioni desiderate',
'{"options": ["Petite (< 20m²)", "Moyenne (20-40m²)", "Grande (40-80m²)", "Très grande (> 80m²)"]}',
'{"required": true}', 2, true, null, null),
(36, 'pool_material', 'select', 'Matériau', 'Material', 'Material', 'Material', 'Materiale',
'{"options": ["Béton", "Coque polyester", "Liner", "Carrelage/Mosaïque", "Membrane armée"]}',
'{"required": false}', 3, false, null, null),
(36, 'pool_equipment', 'checkbox', 'Équipements souhaités', 'Desired equipment', 'Equipamiento deseado', 'Gewünschte Ausstattung', 'Attrezzature desiderate',
'{"options": ["Chauffage", "Couverture automatique", "Pompe à chaleur", "Robot nettoyage", "Éclairage LED", "Nage à contre-courant", "Traitement automatique"]}',
'{"required": false}', 4, false, null, 'Sélectionnez les équipements'),
(36, 'pool_shape', 'select', 'Forme', 'Shape', 'Forma', 'Form', 'Forma',
'{"options": ["Rectangulaire", "Forme libre", "Haricot", "Ronde/Ovale", "En L", "Couloir de nage"]}',
'{"required": false}', 5, false, null, null),
(36, 'construction_deadline', 'select', 'Délai souhaité', 'Desired timeline', 'Plazo deseado', 'Gewünschte Frist', 'Scadenza desiderata',
'{"options": ["Urgent (< 3 mois)", "Printemps prochain", "Été prochain", "Pas pressé"]}',
'{"required": false}', 6, false, null, null);

-- Moving / Déménagement (Category 41)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(41, 'move_type', 'select', 'Type de déménagement', 'Move type', 'Tipo de mudanza', 'Umzugstyp', 'Tipo di trasloco',
'{"options": ["Appartement vers Appartement", "Appartement vers Maison", "Maison vers Appartement", "Maison vers Maison", "Bureau/Entreprise"]}',
'{"required": true}', 1, true, null, null),
(41, 'property_size', 'select', 'Taille du logement', 'Property size', 'Tamaño vivienda', 'Wohnungsgröße', 'Dimensione abitazione',
'{"options": ["Studio", "T1/T2", "T3", "T4", "T5+", "Maison < 100m²", "Maison 100-150m²", "Maison > 150m²"]}',
'{"required": true}', 2, true, null, null),
(41, 'volume_estimate', 'number', 'Volume estimé (m³)', 'Estimated volume (m³)', 'Volumen estimado (m³)', 'Geschätztes Volumen (m³)', 'Volume stimato (m³)',
NULL, '{"required": false, "min": 1}', 3, false, '30', 'Si connu'),
(41, 'distance', 'number', 'Distance (km)', 'Distance (km)', 'Distancia (km)', 'Entfernung (km)', 'Distanza (km)',
NULL, '{"required": true, "min": 1}', 4, true, '50', null),
(41, 'floor_origin', 'select', 'Étage de départ', 'Origin floor', 'Piso origen', 'Startgeschoss', 'Piano di partenza',
'{"options": ["RDC", "1er", "2ème", "3ème", "4ème ou +"]}',
'{"required": true}', 5, true, null, null),
(41, 'elevator_origin', 'radio', 'Ascenseur départ?', 'Elevator at origin?', '¿Ascensor origen?', 'Aufzug Abfahrt?', 'Ascensore partenza?',
'{"options": ["Oui", "Non"]}',
'{"required": true}', 6, true, null, null),
(41, 'floor_destination', 'select', 'Étage d\'arrivée', 'Destination floor', 'Piso destino', 'Zielgeschoss', 'Piano di arrivo',
'{"options": ["RDC", "1er", "2ème", "3ème", "4ème ou +"]}',
'{"required": true}', 7, true, null, null),
(41, 'elevator_destination', 'radio', 'Ascenseur arrivée?', 'Elevator at destination?', '¿Ascensor destino?', 'Aufzug Ankunft?', 'Ascensore arrivo?',
'{"options": ["Oui", "Non"]}',
'{"required": true}', 8, true, null, null),
(41, 'services_needed', 'checkbox', 'Services additionnels', 'Additional services', 'Servicios adicionales', 'Zusätzliche Dienstleistungen', 'Servizi aggiuntivi',
'{"options": ["Emballage/Déballage", "Fourniture cartons", "Démontage/Remontage meubles", "Nettoyage ancien logement", "Garde-meubles", "Piano/Objets lourds"]}',
'{"required": false}', 9, false, null, null),
(41, 'move_date', 'date', 'Date souhaitée', 'Desired date', 'Fecha deseada', 'Gewünschtes Datum', 'Data desiderata',
NULL, '{"required": false}', 10, false, null, null);

-- Solar Panels / Panneaux solaires (Category 40)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(40, 'installation_type', 'select', 'Type d\'installation', 'Installation type', 'Tipo de instalación', 'Installationstyp', 'Tipo di installazione',
'{"options": ["Photovoltaïque (électricité)", "Thermique (eau chaude)", "Hybride (électricité + eau chaude)"]}',
'{"required": true}', 1, true, null, null),
(40, 'roof_surface', 'number', 'Surface toit disponible (m²)', 'Available roof (sqm)', 'Superficie techo (m²)', 'Verfügbare Dachfläche (m²)', 'Superficie tetto (m²)',
NULL, '{"required": true, "min": 10}', 2, true, '50', null),
(40, 'roof_orientation', 'select', 'Orientation du toit', 'Roof orientation', 'Orientación techo', 'Dachausrichtung', 'Orientamento tetto',
'{"options": ["Sud", "Sud-Est", "Sud-Ouest", "Est", "Ouest", "Mixte", "Ne sais pas"]}',
'{"required": true}', 3, true, null, 'Meilleure exposition = Sud'),
(40, 'roof_slope', 'select', 'Inclinaison du toit', 'Roof slope', 'Inclinación techo', 'Dachneigung', 'Inclinazione tetto',
'{"options": ["Plat (0-15°)", "Faible (15-30°)", "Moyenne (30-45°)", "Forte (> 45°)"]}',
'{"required": false}', 4, false, null, 'Idéal: 30-35°'),
(40, 'annual_consumption', 'number', 'Consommation annuelle (kWh)', 'Annual consumption (kWh)', 'Consumo anual (kWh)', 'Jahresverbrauch (kWh)', 'Consumo annuo (kWh)',
NULL, '{"required": false, "min": 100}', 5, false, '5000', 'Voir facture électricité'),
(40, 'power_objective', 'select', 'Objectif de puissance', 'Power objective', 'Objetivo potencia', 'Leistungsziel', 'Obiettivo potenza',
'{"options": ["3 kWc", "6 kWc", "9 kWc", "12 kWc ou +", "Ne sais pas"]}',
'{"required": false}', 6, false, null, null),
(40, 'battery_storage', 'radio', 'Batterie de stockage?', 'Battery storage?', '¿Batería de almacenamiento?', 'Batteriespeicher?', 'Batteria di accumulo?',
'{"options": ["Oui", "Non", "À étudier"]}',
'{"required": false}', 7, false, null, 'Pour autoconsommation maximale'),
(40, 'usage_type', 'select', 'Usage prévu', 'Intended use', 'Uso previsto', 'Vorgesehene Nutzung', 'Uso previsto',
'{"options": ["Autoconsommation", "Revente totale", "Autoconsommation + Revente surplus"]}',
'{"required": false}', 8, false, null, null);

-- Demolition / Démolition (Category 42)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(42, 'demolition_type', 'select', 'Type de démolition', 'Demolition type', 'Tipo de demolición', 'Abrisstyp', 'Tipo di demolizione',
'{"options": ["Totale", "Partielle", "Intérieure uniquement", "Abattage mur porteur", "Déconstruction sélective"]}',
'{"required": true}', 1, true, null, null),
(42, 'building_type', 'select', 'Type de bâtiment', 'Building type', 'Tipo de edificio', 'Gebäudetyp', 'Tipo di edificio',
'{"options": ["Maison individuelle", "Immeuble", "Garage/Annexe", "Mur/Clôture", "Autre construction"]}',
'{"required": true}', 2, true, null, null),
(42, 'surface_area', 'number', 'Surface (m²)', 'Surface (sqm)', 'Superficie (m²)', 'Fläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 1}', 3, true, '100', null),
(42, 'num_floors', 'number', 'Nombre d\'étages', 'Number of floors', 'Número de pisos', 'Anzahl Etagen', 'Numero piani',
NULL, '{"required": false, "min": 1}', 4, false, '1', null),
(42, 'hazardous_materials', 'checkbox', 'Matériaux dangereux', 'Hazardous materials', 'Materiales peligrosos', 'Gefahrstoffe', 'Materiali pericolosi',
'{"options": ["Amiante", "Plomb", "Termites", "Aucun", "Ne sais pas"]}',
'{"required": true}', 5, true, null, 'Diagnostic obligatoire'),
(42, 'waste_removal', 'radio', 'Évacuation gravats?', 'Waste removal?', '¿Evacuación escombros?', 'Schuttentsorgung?', 'Rimozione macerie?',
'{"options": ["Oui - Inclus", "Non - Je gère", "À discuter"]}',
'{"required": true}', 6, true, null, null),
(42, 'access_difficulty', 'select', 'Difficulté d\'accès', 'Access difficulty', 'Dificultad acceso', 'Zugangsschwierigkeit', 'Difficoltà accesso',
'{"options": ["Facile - Direct", "Moyen - Passage étroit", "Difficile - Grue nécessaire"]}',
'{"required": false}', 7, false, null, null);

-- Alarm / Alarme et sécurité (Category 29)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(29, 'security_type', 'checkbox', 'Type de système', 'System type', 'Tipo de sistema', 'Systemtyp', 'Tipo di sistema',
'{"options": ["Alarme intrusion", "Vidéosurveillance", "Interphone/Visiophone", "Contrôle d\'accès", "Détection incendie"]}',
'{"required": true}', 1, true, null, 'Sélectionnez tous les systèmes'),
(29, 'property_type', 'select', 'Type de bien', 'Property type', 'Tipo de propiedad', 'Immobilientyp', 'Tipo di proprietà',
'{"options": ["Maison", "Appartement", "Bureau", "Commerce", "Entrepôt"]}',
'{"required": true}', 2, true, null, null),
(29, 'property_size', 'number', 'Surface (m²)', 'Surface (sqm)', 'Superficie (m²)', 'Fläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 10}', 3, true, '100', null),
(29, 'num_zones', 'number', 'Nombre de zones à protéger', 'Zones to protect', 'Zonas a proteger', 'Zu schützende Zonen', 'Zone da proteggere',
NULL, '{"required": false, "min": 1}', 4, false, '5', 'Pièces, entrées, extérieur...'),
(29, 'alarm_features', 'checkbox', 'Fonctionnalités souhaitées', 'Desired features', 'Funciones deseadas', 'Gewünschte Funktionen', 'Funzionalità desiderate',
'{"options": ["Application mobile", "Télésurveillance", "Sirène extérieure", "Détecteurs mouvement", "Détecteurs ouverture", "Caméras IP", "Enregistrement vidéo"]}',
'{"required": false}', 5, false, null, null),
(29, 'connection_type', 'select', 'Type de connexion', 'Connection type', 'Tipo de conexión', 'Verbindungstyp', 'Tipo di connessione',
'{"options": ["Filaire", "Sans fil (radio)", "Mixte", "Pas de préférence"]}',
'{"required": false}', 6, false, null, null);

-- Wallpaper / Papier peint (Category 11)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(11, 'work_type', 'select', 'Type de travaux', 'Type of work', 'Tipo de trabajo', 'Art der Arbeit', 'Tipo di lavoro',
'{"options": ["Pose papier peint", "Dépose + Pose", "Dépose uniquement"]}',
'{"required": true}', 1, true, null, null),
(11, 'surface_area', 'number', 'Surface des murs (m²)', 'Wall surface (sqm)', 'Superficie paredes (m²)', 'Wandfläche (m²)', 'Superficie pareti (m²)',
NULL, '{"required": true, "min": 1}', 2, true, '30', null),
(11, 'num_rooms', 'number', 'Nombre de pièces', 'Number of rooms', 'Número de habitaciones', 'Anzahl Räume', 'Numero stanze',
NULL, '{"required": false, "min": 1}', 3, false, '1', null),
(11, 'wallpaper_type', 'select', 'Type de papier peint', 'Wallpaper type', 'Tipo de papel', 'Tapetentyp', 'Tipo di carta da parati',
'{"options": ["Papier traditionnel", "Intissé", "Vinyle", "Textile", "Panoramique", "Déjà acheté", "À acheter ensemble"]}',
'{"required": false}', 4, false, null, null),
(11, 'wall_condition', 'select', 'État des murs', 'Wall condition', 'Estado paredes', 'Wandzustand', 'Condizione pareti',
'{"options": ["Bon état", "Moyen (petits trous)", "Mauvais (réparations nécessaires)"]}',
'{"required": true}', 5, true, null, null),
(11, 'pattern_matching', 'radio', 'Raccord de motif?', 'Pattern matching?', '¿Coincidencia de patrón?', 'Musterjustierung?', 'Corrispondenza motivo?',
'{"options": ["Oui - Motif à raccord", "Non - Uni ou sans raccord"]}',
'{"required': false}', 6, false, null, 'Les motifs à raccord nécessitent plus de temps');

-- Pest Control / Dératisation et désinsectisation (Category 43)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(43, 'pest_type', 'checkbox', 'Type de nuisible', 'Pest type', 'Tipo de plaga', 'Schädlingstyp', 'Tipo di infestante',
'{"options": ["Rats/Souris", "Cafards/Blattes", "Punaises de lit", "Fourmis", "Guêpes/Frelons", "Termites", "Autre"]}',
'{"required": true}', 1, true, null, 'Sélectionnez tous les nuisibles'),
(43, 'infestation_level', 'select', 'Niveau d\'infestation', 'Infestation level', 'Nivel de infestación', 'Befallsgrad', 'Livello di infestazione',
'{"options": ["Léger (quelques individus)", "Modéré (présence régulière)", "Important (infestation)", "Très important (invasion)"]}',
'{"required": true}', 2, true, null, null),
(43, 'property_type', 'select', 'Type de bien', 'Property type', 'Tipo de propiedad', 'Immobilientyp', 'Tipo di proprietà',
'{"options": ["Maison", "Appartement", "Restaurant/Commerce", "Bureau", "Entrepôt", "Jardin/Extérieur"]}',
'{"required": true}', 3, true, null, null),
(43, 'property_size', 'number', 'Surface (m²)', 'Surface (sqm)', 'Superficie (m²)', 'Fläche (m²)', 'Superficie (m²)',
NULL, '{"required": true, "min": 10}', 4, true, '80', null),
(43, 'treatment_preference', 'select', 'Type de traitement', 'Treatment type', 'Tipo de tratamiento', 'Behandlungsart', 'Tipo di trattamento',
'{"options": ["Chimique", "Naturel/Bio", "Mécanique (pièges)', 'Mixte", "Pas de préférence"]}',
'{"required": false}', 5, false, null, null),
(43, 'urgency_level', 'select', 'Urgence', 'Urgency', 'Urgencia', 'Dringlichkeit', 'Urgenza',
'{"options": ["Urgent (24-48h)", "Rapide (< 1 semaine)", "Normal"]}',
'{"required": true}', 6, true, null, null),
(43, 'follow_up', 'radio', 'Suivi régulier souhaité?', 'Regular follow-up?', '¿Seguimiento regular?', 'Regelmäßige Nachverfolgung?', 'Follow-up regolare?',
'{"options": ["Oui - Contrat annuel", "Non - Intervention ponctuelle"]}',
'{"required": false}', 7, false, null, 'Recommandé pour prévention');

-- Asbestos Removal / Désamiantage (Category 44)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(44, 'has_diagnostic', 'radio', 'Diagnostic amiante réalisé?', 'Asbestos diagnostic done?', '¿Diagnóstico realizado?', 'Diagnose durchgeführt?', 'Diagnosi effettuata?',
'{"options": ["Oui", "Non - À réaliser"]}',
'{"required": true}', 1, true, null, 'Obligatoire avant travaux'),
(44, 'asbestos_location', 'checkbox', 'Localisation amiante', 'Asbestos location', 'Ubicación amianto', 'Asbeststandort', 'Ubicazione amianto',
'{"options": ["Toiture", "Faux-plafond", "Canalisation", "Sol", "Cloison", "Isolation", "Autre"]}',
'{"required": true}', 2, true, null, 'Selon diagnostic'),
(44, 'building_type', 'select', 'Type de bâtiment', 'Building type', 'Tipo de edificio', 'Gebäudetyp', 'Tipo di edificio',
'{"options": ["Maison individuelle", "Immeuble", "Local commercial", "Industriel"]}',
'{"required": true}', 3, true, null, null),
(44, 'surface_area', 'number', 'Surface concernée (m²)', 'Affected surface (sqm)', 'Superficie afectada (m²)', 'Betroffene Fläche (m²)', 'Superficie interessata (m²)',
NULL, '{"required": true, "min": 1}', 4, true, '50', null),
(44, 'work_type', 'select', 'Type de travaux', 'Type of work', 'Tipo de trabajo', 'Art der Arbeit', 'Tipo di lavoro',
'{"options": ["Retrait", "Encapsulage", "Confinement"]}',
'{"required": false}', 5, false, null, 'Selon recommandations diagnostiqueur'),
(44, 'building_occupied', 'radio', 'Bâtiment occupé?', 'Building occupied?', '¿Edificio ocupado?', 'Gebäude bewohnt?', 'Edificio occupato?',
'{"options": ["Oui", "Non - Vide"]}',
'{"required": true}', 6, true, null, 'Important pour planning');

-- Facade Cleaning / Ravalement de façade (Category 2)
INSERT INTO custom_form_fields (trade_category_id, field_name, field_type, label_fr, label_en, label_es, label_de, label_it, options, validation_rules, display_order, is_required, placeholder, help_text) VALUES
(2, 'work_type', 'checkbox', 'Travaux à réaliser', 'Work to perform', 'Trabajos a realizar', 'Durchzuführende Arbeiten', 'Lavori da eseguire',
'{"options": ["Nettoyage", "Réparation fissures", "Peinture", "Crépi/Enduit", "Isolation par l\'extérieur", "Joints"]}',
'{"required": true}', 1, true, null, 'Sélectionnez tous les travaux'),
(2, 'facade_surface', 'number', 'Surface de façade (m²)', 'Facade surface (sqm)', 'Superficie fachada (m²)', 'Fassadenfläche (m²)', 'Superficie facciata (m²)',
NULL, '{"required": true, "min": 10}', 2, true, '100', null),
(2, 'building_height', 'select', 'Hauteur du bâtiment', 'Building height', 'Altura edificio', 'Gebäudehöhe', 'Altezza edificio',
'{"options": ["1 étage (RDC + 1)", "2 étages", "3 étages", "4 étages ou +"]}',
'{"required": true}', 3, true, null, 'Impact échafaudage'),
(2, 'facade_material', 'select', 'Matériau façade', 'Facade material', 'Material fachada', 'Fassadenmaterial', 'Materiale facciata',
'{"options": ["Pierre', 'Brique", "Béton", "Crépi/Enduit", "Bois", "Mixte"]}',
'{"required": false}', 4, false, null, null),
(2, 'facade_condition', 'select', 'État de la façade', 'Facade condition', 'Estado fachada', 'Fassadenzustand', 'Condizione facciata',
'{"options": ["Bon - Nettoyage seulement", "Moyen - Réparations légères", "Mauvais - Réparations importantes"]}',
'{"required": true}', 5, true, null, null),
(2, 'scaffolding', 'radio', 'Échafaudage nécessaire?', 'Scaffolding needed?', '¿Andamio necesario?', 'Gerüst erforderlich?', 'Ponteggio necessario?',
'{"options": ["Oui", "Non - Nacelle possible", "À déterminer"]}',
'{"required": false}', 6, false, null, null);

-- Cette base de données contient maintenant des formulaires ULTRA-DÉTAILLÉS pour 20+ métiers
-- Chaque métier a entre 5 et 10 questions personnalisées avec validation, aide contextuelle, etc.
