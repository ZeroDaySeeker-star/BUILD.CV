-- BUILD.CV Database Schema - Multi-Profile Architecture SaaS
-- Created: 2026-02-26

CREATE DATABASE IF NOT EXISTS buildcv CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE buildcv;

-- ============================================================================
-- USERS & ACCOUNTS
-- ============================================================================

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    reset_token VARCHAR(100) NULL,
    reset_expires DATETIME NULL,
    email_verified TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_username (username)
);

-- ============================================================================
-- PLANS & SUBSCRIPTIONS
-- ============================================================================

CREATE TABLE IF NOT EXISTS plans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    display_name VARCHAR(100) NOT NULL,
    description TEXT,
    cv_limit INT DEFAULT 1,
    portfolio_limit INT DEFAULT 1,
    is_unlimited TINYINT(1) DEFAULT 0,
    price_monthly DECIMAL(10, 2),
    price_yearly DECIMAL(10, 2),
    position INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uk_name (name)
);

CREATE TABLE IF NOT EXISTS subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    plan_id INT NOT NULL,
    status ENUM('active', 'paused', 'cancelled', 'expired') DEFAULT 'active',
    start_date DATETIME NOT NULL,
    end_date DATETIME NULL,
    auto_renew TINYINT(1) DEFAULT 1,
    payment_ref VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (plan_id) REFERENCES plans(id),
    INDEX idx_user_status (user_id, status),
    INDEX idx_end_date (end_date)
);

-- ============================================================================
-- PROFILES (ONE PER USER - 1:1 RELATIONSHIP)
-- ============================================================================

CREATE TABLE IF NOT EXISTS premium_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    plan_id INT NOT NULL,
    duration_months INT DEFAULT 1,
    used_count INT DEFAULT 0,
    max_uses INT DEFAULT 1,
    status ENUM('active', 'used', 'expired', 'revoked') DEFAULT 'active',
    expires_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (plan_id) REFERENCES plans(id)
);

CREATE TABLE IF NOT EXISTS code_usages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code_id INT NOT NULL,
    user_id INT NOT NULL,
    ip_address VARCHAR(45),
    used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (code_id) REFERENCES premium_codes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS code_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45) NOT NULL,
    user_id INT NULL,
    attempt_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================================

CREATE TABLE IF NOT EXISTS profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    full_name VARCHAR(100),
    title VARCHAR(100),
    email VARCHAR(255),
    phone VARCHAR(30),
    location VARCHAR(100),
    website VARCHAR(255),
    linkedin VARCHAR(255),
    github VARCHAR(255),
    summary TEXT,
    profile_photo VARCHAR(255),
    is_public TINYINT(1) DEFAULT 0,
    public_url VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY uk_public_url (public_url),
    INDEX idx_user_id (user_id)
);



-- ============================================================================
-- TEMPLATES
-- ============================================================================

CREATE TABLE IF NOT EXISTS templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    template_key VARCHAR(50) UNIQUE NOT NULL,
    template_name VARCHAR(100) NOT NULL,
    template_type ENUM('cv', 'portfolio') NOT NULL,
    plan_required VARCHAR(50) DEFAULT 'free',
    preview_image VARCHAR(255),
    description TEXT,
    is_active TINYINT(1) DEFAULT 1,
    position INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type_plan (template_type, plan_required)
);

-- ============================================================================
-- ANALYTICS & TRACKING
-- ============================================================================

CREATE TABLE IF NOT EXISTS profile_visits (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    visit_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    referrer VARCHAR(500),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id_date (user_id, visit_date)
);

-- ============================================================================
-- AUDIT LOG
-- ============================================================================

CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT,
    details JSON,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_action (user_id, action),
    INDEX idx_created (created_at)
);

-- ============================================================================
-- INSERT DEFAULT PLANS
-- ============================================================================

INSERT IGNORE INTO plans (name, display_name, description, cv_limit, portfolio_limit, is_unlimited, price_monthly, price_yearly, position) VALUES
('free', 'Gratuit', 'Découvrez BUILD.CV gratuitement', 1, 1, 0, 0.00, 0.00, 1),
('standard', 'Standard', 'Accès intermédiaire pour se démarquer', 3, 1, 0, 3000.00, 30000.00, 2),
('premium', 'Premium', 'Illimité avec toutes les options premium', 999, 999, 1, 5000.00, 50000.00, 3);

-- ============================================================================
-- INSERT DEFAULT TEMPLATES
-- ============================================================================

INSERT IGNORE INTO templates (template_key, template_name, template_type, plan_required, description, position) VALUES
-- CV Templates
('minimal', 'Minimal', 'cv', 'free', 'Clean and simple one-column CV layout', 1),
('professional', 'Professional', 'cv', 'free', 'Classic two-column professional CV', 2),
('modern', 'Modern', 'cv', 'free', 'Contemporary layout with modern colors', 3),
('compact', 'Compact', 'cv', 'free', 'Dense layout perfect for experienced professionals', 4),
('creative', 'Creative', 'cv', 'premium', 'Bold accent colors with modern layout', 5),
('startup', 'Startup', 'cv', 'standard', 'Style Canva moderne avec barre latérale colorée', 6),
('timeline', 'Timeline', 'cv', 'standard', 'Moderne avec connecteur vertical pour vos expériences', 7),
('neoretro', 'Neo-Retro', 'cv', 'standard', 'Style audacieux avec bordures épaisses et tons pastels', 8),
('executive', 'Executive', 'cv', 'standard', 'Sérieux et prestigieux, idéal pour les cadres et leaders', 9),
('elegant', 'Elegant', 'cv', 'standard', 'Style éditorial magazine avec typographies de luxe', 10),
-- Portfolio Templates
('portfolio_minimal', 'Minimal', 'portfolio', 'free', 'Clean white minimalist portfolio', 1),
('portfolio_developer', 'Developer', 'portfolio', 'free', 'Dark theme with code-style accents', 2),
('portfolio_dark', 'Dark Lux', 'portfolio', 'standard', 'Élégance nocturne et contrastes forts', 3),
('portfolio_gallery', 'Galerie', 'portfolio', 'standard', 'Focus sur les images et les projets', 4),
('agency', 'Agency', 'portfolio', 'standard', 'Audacieux et visuel avec grille impactante', 5),
('architect', 'Architect', 'portfolio', 'standard', 'Minimaliste et monochrome structural', 6),
('cyber', 'Cyber', 'portfolio', 'standard', 'Néon rétro-futuriste et glitchy hack', 7),
('journal', 'Journal', 'portfolio', 'standard', 'Chaleureux avec esthétique papier', 8),
('corporate', 'Corporate', 'portfolio', 'standard', 'Sérieux et structuré pour la confiance', 9),
('glass', 'Glass', 'portfolio', 'standard', 'Transparence et dégradés vibrants', 10),
('portfolio_designer', 'Designer', 'portfolio', 'premium', 'Light, image-forward, grid layout', 5);

-- Table des lettres de motivation
CREATE TABLE IF NOT EXISTS cover_letters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    job_title VARCHAR(255),
    company VARCHAR(255),
    content TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
