-- =====================================================
-- INTERNATIONALIZATION & MULTI-COUNTRY SUPPORT
-- Support for any country with localized configurations
-- =====================================================

-- Countries configuration table
CREATE TABLE IF NOT EXISTS countries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(2) NOT NULL UNIQUE COMMENT 'ISO 3166-1 alpha-2',
    name VARCHAR(100) NOT NULL,
    name_local VARCHAR(100) NOT NULL COMMENT 'Country name in local language',
    language VARCHAR(5) NOT NULL DEFAULT 'en' COMMENT 'Primary language code',
    currency_code VARCHAR(3) NOT NULL DEFAULT 'USD',
    phone_code VARCHAR(10) NOT NULL,
    phone_format VARCHAR(50) COMMENT 'Phone number format pattern',
    postal_code_format VARCHAR(50) COMMENT 'Postal code format pattern',
    postal_code_regex VARCHAR(255) COMMENT 'Regex for postal code validation',
    address_format TEXT COMMENT 'Address format template',
    date_format VARCHAR(20) DEFAULT 'd/m/Y',
    business_id_label VARCHAR(50) COMMENT 'Label for business registration number',
    business_id_format VARCHAR(50) COMMENT 'Format pattern for business ID',
    tax_rate DECIMAL(5,2) DEFAULT 20.00 COMMENT 'Default VAT/Tax rate',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_code (code),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Currencies table
CREATE TABLE IF NOT EXISTS currencies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(3) NOT NULL UNIQUE COMMENT 'ISO 4217 currency code',
    name VARCHAR(50) NOT NULL,
    symbol VARCHAR(10) NOT NULL,
    decimal_places INT DEFAULT 2,
    symbol_position ENUM('before', 'after') DEFAULT 'after',
    thousands_separator VARCHAR(5) DEFAULT ',',
    decimal_separator VARCHAR(5) DEFAULT '.',
    exchange_rate_to_usd DECIMAL(10,4) DEFAULT 1.0000,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Extended trade categories - 50+ trades for comprehensive coverage
CREATE TABLE IF NOT EXISTS trade_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) UNIQUE NOT NULL,
    name_fr VARCHAR(100) NOT NULL,
    name_en VARCHAR(100) NOT NULL,
    name_es VARCHAR(100),
    name_de VARCHAR(100),
    name_it VARCHAR(100),
    description_fr TEXT,
    description_en TEXT,
    description_es TEXT,
    description_de TEXT,
    description_it TEXT,
    icon VARCHAR(50) DEFAULT 'fa-tools',
    parent_category_id INT NULL COMMENT 'For sub-categories',
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_category_id) REFERENCES trade_categories(id) ON DELETE SET NULL,
    INDEX idx_slug (slug),
    INDEX idx_parent (parent_category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Custom form fields for each trade
CREATE TABLE IF NOT EXISTS custom_form_fields (
    id INT AUTO_INCREMENT PRIMARY KEY,
    trade_category_id INT NOT NULL,
    field_name VARCHAR(100) NOT NULL COMMENT 'Unique field identifier',
    field_type ENUM('text', 'number', 'select', 'checkbox', 'radio', 'textarea', 'date', 'file') NOT NULL,
    label_fr VARCHAR(255) NOT NULL,
    label_en VARCHAR(255) NOT NULL,
    label_es VARCHAR(255),
    label_de VARCHAR(255),
    label_it VARCHAR(255),
    placeholder_fr VARCHAR(255),
    placeholder_en VARCHAR(255),
    options JSON COMMENT 'For select/radio/checkbox fields',
    validation_rules JSON COMMENT 'Required, min, max, etc.',
    display_order INT DEFAULT 0,
    is_required BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (trade_category_id) REFERENCES trade_categories(id) ON DELETE CASCADE,
    INDEX idx_trade_category (trade_category_id),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Store custom field values for projects
CREATE TABLE IF NOT EXISTS project_custom_fields (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    field_id INT NOT NULL,
    field_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (field_id) REFERENCES custom_form_fields(id) ON DELETE CASCADE,
    UNIQUE KEY unique_project_field (project_id, field_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Country-specific badges
CREATE TABLE IF NOT EXISTS country_badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    country_code VARCHAR(2) NOT NULL,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    type ENUM('verification', 'certification', 'achievement', 'premium') NOT NULL,
    color VARCHAR(20),
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (country_code) REFERENCES countries(code) ON DELETE CASCADE,
    UNIQUE KEY unique_country_badge (country_code, slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add country field to users and projects
ALTER TABLE users ADD COLUMN country_code VARCHAR(2) DEFAULT 'FR' AFTER phone;
ALTER TABLE projects ADD COLUMN country_code VARCHAR(2) DEFAULT 'FR' AFTER city;
ALTER TABLE artisan_profiles ADD COLUMN country_code VARCHAR(2) DEFAULT 'FR' AFTER city;

-- Add currency to projects and quotes
ALTER TABLE projects ADD COLUMN currency_code VARCHAR(3) DEFAULT 'EUR' AFTER budget_max;
ALTER TABLE quotes ADD COLUMN currency_code VARCHAR(3) DEFAULT 'EUR' AFTER amount;

-- =====================================================
-- INSERT DEFAULT DATA
-- =====================================================

-- Insert major countries
INSERT INTO countries (code, name, name_local, language, currency_code, phone_code, phone_format, postal_code_format, postal_code_regex, business_id_label, business_id_format, tax_rate) VALUES
('FR', 'France', 'France', 'fr', 'EUR', '+33', '0X XX XX XX XX', 'XXXXX', '^[0-9]{5}$', 'SIRET', 'XXX XXX XXX XXXXX', 20.00),
('US', 'United States', 'United States', 'en', 'USD', '+1', '(XXX) XXX-XXXX', 'XXXXX', '^[0-9]{5}$', 'EIN', 'XX-XXXXXXX', 10.00),
('GB', 'United Kingdom', 'United Kingdom', 'en', 'GBP', '+44', '0XXXX XXXXXX', 'XX XX', '^[A-Z]{1,2}[0-9]{1,2}[A-Z]? [0-9][A-Z]{2}$', 'Company Number', 'XXXXXXXX', 20.00),
('ES', 'Spain', 'España', 'es', 'EUR', '+34', 'XXX XX XX XX', 'XXXXX', '^[0-9]{5}$', 'CIF/NIF', 'XXXXXXXXX', 21.00),
('DE', 'Germany', 'Deutschland', 'de', 'EUR', '+49', '0XXX XXXXXXXX', 'XXXXX', '^[0-9]{5}$', 'Steuernummer', 'XXX/XXXX/XXXXX', 19.00),
('IT', 'Italy', 'Italia', 'it', 'EUR', '+39', '0XX XXX XXXX', 'XXXXX', '^[0-9]{5}$', 'Partita IVA', 'XXXXXXXXXXX', 22.00),
('CA', 'Canada', 'Canada', 'en', 'CAD', '+1', '(XXX) XXX-XXXX', 'XXX XXX', '^[A-Z][0-9][A-Z] [0-9][A-Z][0-9]$', 'BN', 'XXXXXXXXX', 13.00),
('AU', 'Australia', 'Australia', 'en', 'AUD', '+61', '0XXX XXX XXX', 'XXXX', '^[0-9]{4}$', 'ABN', 'XX XXX XXX XXX', 10.00),
('PT', 'Portugal', 'Portugal', 'pt', 'EUR', '+351', 'XXX XXX XXX', 'XXXX-XXX', '^[0-9]{4}-[0-9]{3}$', 'NIPC', 'XXXXXXXXX', 23.00),
('NL', 'Netherlands', 'Nederland', 'nl', 'EUR', '+31', '0XX XXX XXXX', 'XXXX XX', '^[0-9]{4}[A-Z]{2}$', 'KVK', 'XXXXXXXX', 21.00),
('BE', 'Belgium', 'België/Belgique', 'fr', 'EUR', '+32', '0XXX XX XX XX', 'XXXX', '^[0-9]{4}$', 'Numéro d\'entreprise', '0XXX.XXX.XXX', 21.00),
('CH', 'Switzerland', 'Schweiz', 'de', 'CHF', '+41', '0XX XXX XX XX', 'XXXX', '^[0-9]{4}$', 'UID', 'CHE-XXX.XXX.XXX', 7.70),
('MX', 'Mexico', 'México', 'es', 'MXN', '+52', 'XX XXXX XXXX', 'XXXXX', '^[0-9]{5}$', 'RFC', 'XXXXXXXXXXXX', 16.00),
('BR', 'Brazil', 'Brasil', 'pt', 'BRL', '+55', '(XX) XXXXX-XXXX', 'XXXXX-XXX', '^[0-9]{5}-[0-9]{3}$', 'CNPJ', 'XX.XXX.XXX/XXXX-XX', 17.00);

-- Insert currencies
INSERT INTO currencies (code, name, symbol, symbol_position, thousands_separator, decimal_separator, exchange_rate_to_usd) VALUES
('EUR', 'Euro', '€', 'after', ' ', ',', 0.92),
('USD', 'US Dollar', '$', 'before', ',', '.', 1.00),
('GBP', 'British Pound', '£', 'before', ',', '.', 0.79),
('CAD', 'Canadian Dollar', 'CA$', 'before', ',', '.', 1.35),
('AUD', 'Australian Dollar', 'AU$', 'before', ',', '.', 1.52),
('CHF', 'Swiss Franc', 'CHF', 'after', '\'', '.', 0.88),
('MXN', 'Mexican Peso', 'MX$', 'before', ',', '.', 17.50),
('BRL', 'Brazilian Real', 'R$', 'before', '.', ',', 4.95);

-- Insert 50+ comprehensive trade categories
INSERT INTO trade_categories (slug, name_fr, name_en, name_es, name_de, name_it, icon, display_order) VALUES
-- Construction & Gros œuvre
('general-contractor', 'Maçonnerie Générale', 'General Contracting', 'Albañilería General', 'Allgemeiner Bauunternehmer', 'Edilizia Generale', 'fa-hard-hat', 1),
('foundation', 'Fondations', 'Foundation Work', 'Cimientos', 'Fundamente', 'Fondazioni', 'fa-layer-group', 2),
('concrete', 'Béton & Ciment', 'Concrete & Cement', 'Hormigón y Cemento', 'Beton & Zement', 'Calcestruzzo', 'fa-cubes', 3),
('masonry', 'Maçonnerie de Pierres', 'Stone Masonry', 'Mampostería', 'Steinmaurerarbeiten', 'Muratura in Pietra', 'fa-chess-rook', 4),
('demolition', 'Démolition', 'Demolition', 'Demolición', 'Abbruch', 'Demolizione', 'fa-hammer', 5),

-- Toiture
('roofing', 'Couverture/Toiture', 'Roofing', 'Techado', 'Dachdeckerarbeiten', 'Copertura Tetti', 'fa-home', 6),
('roofing-tile', 'Toiture Tuiles', 'Tile Roofing', 'Tejas', 'Ziegeldach', 'Tetti in Tegole', 'fa-th-large', 7),
('roofing-slate', 'Toiture Ardoise', 'Slate Roofing', 'Pizarra', 'Schieferdach', 'Tetti in Ardesia', 'fa-clone', 8),
('zinc-roofing', 'Couverture Zinc', 'Zinc Roofing', 'Zinc', 'Zinkdach', 'Copertura in Zinco', 'fa-box', 9),
('flat-roofing', 'Toiture Terrasse', 'Flat Roofing', 'Azotea', 'Flachdach', 'Tetti Piani', 'fa-square', 10),
('gutter', 'Gouttières', 'Gutters', 'Canalones', 'Dachrinnen', 'Grondaie', 'fa-water', 11),

-- Charpente
('carpentry', 'Charpente', 'Carpentry/Framing', 'Carpintería', 'Zimmerei', 'Carpenteria', 'fa-tools', 12),
('timber-frame', 'Charpente Bois', 'Timber Framing', 'Estructura de Madera', 'Holzrahmen', 'Struttura in Legno', 'fa-tree', 13),
('steel-frame', 'Charpente Métallique', 'Steel Framing', 'Estructura Metálica', 'Stahlrahmen', 'Struttura Metallica', 'fa-industry', 14),

-- Électricité
('electrical', 'Électricité Générale', 'Electrical', 'Electricidad', 'Elektroarbeiten', 'Elettricista', 'fa-bolt', 15),
('electrical-installation', 'Installation Électrique', 'Electrical Installation', 'Instalación Eléctrica', 'Elektroinstallation', 'Installazione Elettrica', 'fa-plug', 16),
('smart-home', 'Domotique', 'Smart Home/Home Automation', 'Domótica', 'Smart Home', 'Domotica', 'fa-home', 17),
('solar-panels', 'Panneaux Solaires', 'Solar Panels', 'Paneles Solares', 'Solarpaneele', 'Pannelli Solari', 'fa-solar-panel', 18),

-- Plomberie
('plumbing', 'Plomberie', 'Plumbing', 'Fontanería', 'Sanitärarbeiten', 'Idraulica', 'fa-faucet', 19),
('heating', 'Chauffage', 'Heating', 'Calefacción', 'Heizung', 'Riscaldamento', 'fa-fire', 20),
('hvac', 'Climatisation', 'HVAC/Air Conditioning', 'Aire Acondicionado', 'Klimaanlage', 'Climatizzazione', 'fa-wind', 21),
('water-heater', 'Chauffe-eau', 'Water Heater', 'Calentador de Agua', 'Warmwasserbereiter', 'Scaldabagno', 'fa-temperature-high', 22),
('heat-pump', 'Pompe à Chaleur', 'Heat Pump', 'Bomba de Calor', 'Wärmepumpe', 'Pompa di Calore', 'fa-fan', 23),
('boiler', 'Chaudière', 'Boiler Installation', 'Caldera', 'Kesselinstallation', 'Caldaia', 'fa-burn', 24),

-- Isolation
('insulation', 'Isolation Thermique', 'Insulation', 'Aislamiento', 'Dämmung', 'Isolamento', 'fa-snowflake', 25),
('soundproofing', 'Isolation Phonique', 'Soundproofing', 'Aislamiento Acústico', 'Schalldämmung', 'Isolamento Acustico', 'fa-volume-mute', 26),

-- Fenêtres & Portes
('windows', 'Fenêtres', 'Windows', 'Ventanas', 'Fenster', 'Finestre', 'fa-window-maximize', 27),
('doors', 'Portes', 'Doors', 'Puertas', 'Türen', 'Porte', 'fa-door-open', 28),
('garage-doors', 'Portes de Garage', 'Garage Doors', 'Puertas de Garaje', 'Garagentore', 'Porte Garage', 'fa-warehouse', 29),
('shutters', 'Volets', 'Shutters', 'Persianas', 'Rollläden', 'Persiane', 'fa-bars', 30),

-- Revêtements sols
('flooring', 'Sols & Revêtements', 'Flooring', 'Suelos', 'Bodenbeläge', 'Pavimentazione', 'fa-th', 31),
('tile', 'Carrelage', 'Tiling', 'Azulejos', 'Fliesen', 'Piastrellatura', 'fa-border-all', 32),
('parquet', 'Parquet', 'Parquet/Hardwood', 'Parqué', 'Parkett', 'Parquet', 'fa-grip-lines', 33),
('laminate', 'Stratifié', 'Laminate Flooring', 'Laminado', 'Laminat', 'Laminato', 'fa-layer-group', 34),
('carpet', 'Moquette', 'Carpet Installation', 'Alfombra', 'Teppichboden', 'Moquette', 'fa-clone', 35),

-- Peinture
('painting', 'Peinture', 'Painting', 'Pintura', 'Malerarbeiten', 'Pittura', 'fa-paint-roller', 36),
('wallpaper', 'Papier Peint', 'Wallpapering', 'Papel Pintado', 'Tapezieren', 'Carta da Parati', 'fa-image', 37),
('decorative-painting', 'Peinture Décorative', 'Decorative Painting', 'Pintura Decorativa', 'Dekorationsmalerei', 'Pittura Decorativa', 'fa-palette', 38),

-- Cuisine & Salle de bain
('kitchen', 'Cuisine', 'Kitchen Installation', 'Cocina', 'Kücheninstallation', 'Cucina', 'fa-utensils', 39),
('bathroom', 'Salle de Bain', 'Bathroom', 'Baño', 'Badezimmer', 'Bagno', 'fa-bath', 40),
('countertop', 'Plan de Travail', 'Countertops', 'Encimera', 'Arbeitsplatten', 'Piano di Lavoro', 'fa-minus', 41),

-- Menuiserie
('joinery', 'Menuiserie', 'Joinery/Woodwork', 'Carpintería', 'Schreinerei', 'Falegnameria', 'fa-drafting-compass', 42),
('custom-furniture', 'Meubles Sur Mesure', 'Custom Furniture', 'Muebles a Medida', 'Maßmöbel', 'Mobili su Misura', 'fa-couch', 43),
('closets', 'Placards', 'Closets/Built-ins', 'Armarios', 'Einbauschränke', 'Armadi', 'fa-door-closed', 44),

-- Extérieur
('landscaping', 'Aménagement Paysager', 'Landscaping', 'Jardinería', 'Landschaftsbau', 'Giardinaggio', 'fa-leaf', 45),
('terrace', 'Terrasse', 'Deck/Terrace', 'Terraza', 'Terrasse', 'Terrazza', 'fa-home', 46),
('pool', 'Piscine', 'Swimming Pool', 'Piscina', 'Schwimmbad', 'Piscina', 'fa-swimming-pool', 47),
('fence', 'Clôture', 'Fencing', 'Valla', 'Zaun', 'Recinzione', 'fa-border-style', 48),
('driveway', 'Allée/Entrée', 'Driveway', 'Entrada', 'Auffahrt', 'Vialetto', 'fa-road', 49),

-- Spécialisés
('locksmith', 'Serrurerie', 'Locksmith', 'Cerrajería', 'Schlosserei', 'Fabbro', 'fa-key', 50),
('metalwork', 'Métallerie', 'Metalwork', 'Metalistería', 'Metallarbeiten', 'Metallo', 'fa-wrench', 51),
('glass', 'Vitrerie', 'Glazing/Glass Work', 'Cristalería', 'Glaserei', 'Vetreria', 'fa-square', 52),
('plastering', 'Plâtrerie', 'Plastering/Drywall', 'Yeso', 'Gipsarbeiten', 'Intonacatura', 'fa-paint-roller', 53),
('asbestos-removal', 'Désamiantage', 'Asbestos Removal', 'Eliminación de Amianto', 'Asbestsanierung', 'Rimozione Amianto', 'fa-shield-alt', 54),
('pest-control', 'Traitement Nuisibles', 'Pest Control', 'Control de Plagas', 'Schädlingsbekämpfung', 'Disinfestazione', 'fa-bug', 55),
('elevator', 'Ascenseur', 'Elevator Installation', 'Ascensor', 'Aufzugsinstallation', 'Ascensore', 'fa-arrow-up', 56),
('fire-protection', 'Protection Incendie', 'Fire Protection', 'Protección contra Incendios', 'Brandschutz', 'Protezione Antincendio', 'fa-fire-extinguisher', 57),
('security-system', 'Système de Sécurité', 'Security Systems', 'Sistemas de Seguridad', 'Sicherheitssysteme', 'Sistemi di Sicurezza', 'fa-shield-alt', 58),
('moving', 'Déménagement', 'Moving Services', 'Mudanzas', 'Umzug', 'Trasloco', 'fa-truck-moving', 59),
('cleaning', 'Nettoyage Après Travaux', 'Post-Construction Cleaning', 'Limpieza Post-Obra', 'Endreinigung', 'Pulizia Post-Lavori', 'fa-broom', 60);

-- Insert country-specific badges
INSERT INTO country_badges (country_code, name, slug, description, icon, type, color, display_order) VALUES
-- France
('FR', 'SIRET Vérifié', 'siret-verified', 'Numéro SIRET vérifié', 'fa-check-circle', 'verification', '#10b981', 1),
('FR', 'Assurance Décennale', 'insurance-decennial', 'Assurance décennale valide', 'fa-shield-alt', 'verification', '#3b82f6', 2),
('FR', 'Certification RGE', 'rge-certified', 'Reconnu Garant de l\'Environnement', 'fa-leaf', 'certification', '#059669', 3),
('FR', 'Qualibat', 'qualibat', 'Certification Qualibat', 'fa-certificate', 'certification', '#f59e0b', 4),

-- United States
('US', 'Licensed Contractor', 'us-licensed', 'State Licensed Contractor', 'fa-check-circle', 'verification', '#10b981', 1),
('US', 'Bonded & Insured', 'us-bonded-insured', 'Bonded and Insured Professional', 'fa-shield-alt', 'verification', '#3b82f6', 2),
('US', 'BBB Accredited', 'us-bbb', 'Better Business Bureau Accredited', 'fa-award', 'certification', '#2563eb', 3),
('US', 'EPA Lead-Safe', 'us-epa-lead', 'EPA Lead-Safe Certified', 'fa-leaf', 'certification', '#059669', 4),

-- United Kingdom
('GB', 'FMB Member', 'uk-fmb', 'Federation of Master Builders Member', 'fa-certificate', 'certification', '#2563eb', 1),
('GB', 'Gas Safe Registered', 'uk-gas-safe', 'Gas Safe Registered Engineer', 'fa-fire', 'verification', '#ef4444', 2),
('GB', 'NICEIC Approved', 'uk-niceic', 'NICEIC Approved Contractor', 'fa-bolt', 'certification', '#fbbf24', 3),
('GB', 'TrustMark Registered', 'uk-trustmark', 'TrustMark Government Endorsed', 'fa-check-circle', 'certification', '#10b981', 4),

-- Spain
('ES', 'CIF Verificado', 'es-cif', 'CIF/NIF Verificado', 'fa-check-circle', 'verification', '#10b981', 1),
('ES', 'Seguro RC', 'es-insurance', 'Seguro de Responsabilidad Civil', 'fa-shield-alt', 'verification', '#3b82f6', 2),

-- Germany
('DE', 'Meisterbrief', 'de-master', 'Meisterbrief Certified', 'fa-certificate', 'certification', '#2563eb', 1),
('DE', 'Handwerkskammer', 'de-chamber', 'Chamber of Crafts Member', 'fa-award', 'certification', '#f59e0b', 2),

-- Italy
('IT', 'Partita IVA Verificata', 'it-vat', 'Partita IVA Verificata', 'fa-check-circle', 'verification', '#10b981', 1),
('IT', 'Assicurazione RC', 'it-insurance', 'Assicurazione Responsabilità Civile', 'fa-shield-alt', 'verification', '#3b82f6', 2);
