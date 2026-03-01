-- Migration: Premium Upgrade Features
-- Adds support for Application Tracking, Portfolio Password, and Messages

ALTER TABLE profiles 
ADD COLUMN IF NOT EXISTS portfolio_password VARCHAR(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS custom_domain VARCHAR(255) DEFAULT NULL;

CREATE TABLE IF NOT EXISTS job_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    company VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    status ENUM('Interested', 'Applied', 'Interviewing', 'Offered', 'Rejected', 'Accepted') DEFAULT 'Applied',
    applied_date DATE DEFAULT (CURRENT_DATE),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS portfolio_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    profile_id INT NOT NULL,
    sender_name VARCHAR(100) NOT NULL,
    sender_email VARCHAR(255) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (profile_id) REFERENCES profiles(id) ON DELETE CASCADE
);
