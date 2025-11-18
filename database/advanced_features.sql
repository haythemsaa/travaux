-- Add badges and certifications tables
CREATE TABLE IF NOT EXISTS badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    type ENUM('verification', 'certification', 'achievement', 'premium') NOT NULL,
    criteria TEXT,
    color VARCHAR(20),
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS artisan_badges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    artisan_id INT NOT NULL,
    badge_id INT NOT NULL,
    verified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NULL,
    verification_document VARCHAR(255),
    verified_by INT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (artisan_id) REFERENCES artisan_profiles(id) ON DELETE CASCADE,
    FOREIGN KEY (badge_id) REFERENCES badges(id) ON DELETE CASCADE,
    UNIQUE KEY unique_artisan_badge (artisan_id, badge_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add availability calendar
CREATE TABLE IF NOT EXISTS artisan_availability (
    id INT AUTO_INCREMENT PRIMARY KEY,
    artisan_id INT NOT NULL,
    date DATE NOT NULL,
    is_available BOOLEAN DEFAULT TRUE,
    start_time TIME DEFAULT '08:00:00',
    end_time TIME DEFAULT '18:00:00',
    notes TEXT,
    FOREIGN KEY (artisan_id) REFERENCES artisan_profiles(id) ON DELETE CASCADE,
    UNIQUE KEY unique_artisan_date (artisan_id, date),
    INDEX idx_date (date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add quote templates
CREATE TABLE IF NOT EXISTS quote_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    artisan_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    items JSON NOT NULL,
    terms_conditions TEXT,
    validity_days INT DEFAULT 30,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (artisan_id) REFERENCES artisan_profiles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add quote items for detailed quotes
CREATE TABLE IF NOT EXISTS quote_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quote_id INT NOT NULL,
    description VARCHAR(255) NOT NULL,
    quantity DECIMAL(10,2) NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    display_order INT DEFAULT 0,
    FOREIGN KEY (quote_id) REFERENCES quotes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add invoices
CREATE TABLE IF NOT EXISTS invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quote_id INT NOT NULL,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    issue_date DATE NOT NULL,
    due_date DATE NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    tax_rate DECIMAL(5,2) DEFAULT 20.00,
    tax_amount DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('draft', 'sent', 'paid', 'overdue', 'cancelled') DEFAULT 'draft',
    payment_method VARCHAR(50),
    payment_date DATE NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (quote_id) REFERENCES quotes(id) ON DELETE CASCADE,
    INDEX idx_invoice_number (invoice_number),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add guarantees/warranties
CREATE TABLE IF NOT EXISTS guarantees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    artisan_id INT NOT NULL,
    guarantee_type ENUM('workmanship', 'materials', 'decennial', 'other') NOT NULL,
    duration_months INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    description TEXT,
    terms TEXT,
    insurance_policy VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (artisan_id) REFERENCES artisan_profiles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add analytics/statistics tracking
CREATE TABLE IF NOT EXISTS analytics_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    event_type VARCHAR(50) NOT NULL,
    event_category VARCHAR(50) NOT NULL,
    event_data JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_event_type (event_type),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add price statistics by region
CREATE TABLE IF NOT EXISTS price_statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    region VARCHAR(100) NOT NULL,
    city VARCHAR(100),
    avg_price_min DECIMAL(10,2),
    avg_price_max DECIMAL(10,2),
    median_price DECIMAL(10,2),
    sample_count INT DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
    UNIQUE KEY unique_category_region (category_id, region, city),
    INDEX idx_region (region)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add lead scoring
ALTER TABLE projects ADD COLUMN lead_score INT DEFAULT 50 AFTER status;
ALTER TABLE projects ADD COLUMN lead_quality ENUM('cold', 'warm', 'hot', 'qualified') DEFAULT 'warm' AFTER lead_score;

-- Add geolocation fields
ALTER TABLE projects ADD COLUMN latitude DECIMAL(10, 8) NULL AFTER postal_code;
ALTER TABLE projects ADD COLUMN longitude DECIMAL(11, 8) NULL AFTER latitude;

ALTER TABLE artisan_profiles ADD COLUMN latitude DECIMAL(10, 8) NULL AFTER postal_code;
ALTER TABLE artisan_profiles ADD COLUMN longitude DECIMAL(11, 8) NULL AFTER latitude;
ALTER TABLE artisan_profiles ADD COLUMN service_radius_km INT DEFAULT 50 AFTER service_area;

-- Add premium features for artisans
ALTER TABLE artisan_profiles ADD COLUMN subscription_tier ENUM('free', 'basic', 'premium', 'enterprise') DEFAULT 'free' AFTER is_verified;
ALTER TABLE artisan_profiles ADD COLUMN subscription_expires_at TIMESTAMP NULL AFTER subscription_tier;
ALTER TABLE artisan_profiles ADD COLUMN monthly_leads_limit INT DEFAULT 10 AFTER subscription_expires_at;
ALTER TABLE artisan_profiles ADD COLUMN leads_used_this_month INT DEFAULT 0 AFTER monthly_leads_limit;

-- Add response time tracking
ALTER TABLE quotes ADD COLUMN response_time_hours DECIMAL(10,2) NULL AFTER created_at;

-- Add project value estimation
ALTER TABLE projects ADD COLUMN estimated_value DECIMAL(10,2) NULL AFTER budget_max;

-- Insert default badges
INSERT INTO badges (name, slug, description, icon, type, color, display_order) VALUES
('SIRET Vérifié', 'siret-verified', 'Numéro SIRET vérifié par Travaux Pro', 'fa-check-circle', 'verification', '#10b981', 1),
('Assurance Décennale', 'insurance-decennial', 'Assurance décennale valide et vérifiée', 'fa-shield-alt', 'verification', '#3b82f6', 2),
('Certification RGE', 'rge-certified', 'Reconnu Garant de l\'Environnement', 'fa-leaf', 'certification', '#059669', 3),
('Artisan Premium', 'premium-artisan', 'Membre Premium avec avantages exclusifs', 'fa-crown', 'premium', '#f59e0b', 4),
('Top Rated', 'top-rated', 'Note moyenne supérieure à 4.5/5', 'fa-star', 'achievement', '#fbbf24', 5),
('Réponse Rapide', 'quick-response', 'Répond en moyenne en moins de 2h', 'fa-bolt', 'achievement', '#8b5cf6', 6),
('Expert Vérifié', 'expert-verified', 'Plus de 10 ans d\'expérience vérifiée', 'fa-award', 'achievement', '#ef4444', 7),
('Eco-Responsable', 'eco-friendly', 'Utilise des matériaux écologiques', 'fa-recycle', 'certification', '#10b981', 8);
