-- ============================================================
-- CareerConnect - Database Schema DDL
-- Platform: PHP 8+ / MySQL 8+ (compatible with MariaDB / XAMPP)
-- Database Normalization: 3NF Compliant
-- ============================================================

CREATE DATABASE IF NOT EXISTS careerconnect
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE careerconnect;

-- Disable Foreign Key checks temporarily for clean setup
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS otp_verifications;
DROP TABLE IF EXISTS saved_jobs;
DROP TABLE IF EXISTS applications;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS user_profiles;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;

SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------
-- 1. ROLES TABLE
-- Lookup table for RBAC (Role-Based Access Control)
-- ------------------------------------------------------------
CREATE TABLE roles (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(30) NOT NULL UNIQUE,
  description VARCHAR(255) DEFAULT NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert core platform roles
INSERT INTO roles (id, name, description) VALUES
(1, 'ADMIN', 'System Administrator with full platform access'),
(2, 'RECRUITER', 'Employer / Recruiter who posts jobs and manages applicants'),
(3, 'USER', 'Job Seeker / Student who applies for jobs and internships');

-- ------------------------------------------------------------
-- 2. USERS TABLE
-- Authentication and base account data
-- ------------------------------------------------------------
CREATE TABLE users (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  role_id          INT NOT NULL DEFAULT 3,
  username         VARCHAR(50) NOT NULL UNIQUE,
  email            VARCHAR(100) NOT NULL UNIQUE,
  password_hash    VARCHAR(255) NOT NULL,
  full_name        VARCHAR(100) NOT NULL,
  phone            VARCHAR(20) DEFAULT NULL,
  status           ENUM('active', 'pending', 'suspended') DEFAULT 'active',
  is_verified      TINYINT(1) DEFAULT 0,
  created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  INDEX idx_email (email),
  INDEX idx_username (username),
  INDEX idx_role (role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 3. USER PROFILES TABLE
-- Extended details for Job Seekers / Recruiters
-- ------------------------------------------------------------
CREATE TABLE user_profiles (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  user_id          INT NOT NULL UNIQUE,
  title            VARCHAR(100) DEFAULT NULL,
  bio              TEXT DEFAULT NULL,
  skills           TEXT DEFAULT NULL,
  experience       TEXT DEFAULT NULL,
  education        TEXT DEFAULT NULL,
  location         VARCHAR(100) DEFAULT NULL,
  company_name     VARCHAR(100) DEFAULT NULL,
  website          VARCHAR(255) DEFAULT NULL,
  avatar           VARCHAR(255) DEFAULT 'default-avatar.png',
  resume           VARCHAR(255) DEFAULT NULL,
  created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 4. CATEGORIES TABLE
-- Job classification categories
-- ------------------------------------------------------------
CREATE TABLE categories (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100) NOT NULL UNIQUE,
  slug        VARCHAR(100) NOT NULL UNIQUE,
  icon        VARCHAR(50) DEFAULT 'bi-briefcase',
  description TEXT DEFAULT NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 5. JOBS TABLE
-- Job and Internship Postings
-- ------------------------------------------------------------
CREATE TABLE jobs (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  recruiter_id     INT NOT NULL,
  category_id      INT NOT NULL,
  title            VARCHAR(150) NOT NULL,
  company_name     VARCHAR(100) NOT NULL,
  company_logo     VARCHAR(255) DEFAULT NULL,
  location         VARCHAR(100) NOT NULL,
  job_type         ENUM('Full-time', 'Part-time', 'Contract', 'Remote', 'Internship') NOT NULL DEFAULT 'Full-time',
  experience_level ENUM('Entry', 'Mid', 'Senior', 'Lead') NOT NULL DEFAULT 'Entry',
  salary_min       DECIMAL(12,2) DEFAULT NULL,
  salary_max       DECIMAL(12,2) DEFAULT NULL,
  salary_range     VARCHAR(100) DEFAULT 'Negotiable',
  description      LONGTEXT NOT NULL,
  requirements     LONGTEXT DEFAULT NULL,
  skills_required  TEXT DEFAULT NULL,
  status           ENUM('active', 'closed') DEFAULT 'active',
  views_count      INT DEFAULT 0,
  created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (recruiter_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE CASCADE,
  INDEX idx_title (title),
  INDEX idx_location (location),
  INDEX idx_job_type (job_type),
  INDEX idx_status (status),
  INDEX idx_category (category_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 6. APPLICATIONS TABLE
-- Job Seeker Job Applications
-- ------------------------------------------------------------
CREATE TABLE applications (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  job_id       INT NOT NULL,
  user_id      INT NOT NULL,
  cover_letter TEXT DEFAULT NULL,
  resume_file  VARCHAR(255) DEFAULT NULL,
  status       ENUM('Applied', 'Under Review', 'Shortlisted', 'Rejected', 'Selected') NOT NULL DEFAULT 'Applied',
  applied_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY unique_user_job (job_id, user_id),
  FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 7. SAVED JOBS TABLE
-- Bookmarked Jobs by Seekers
-- ------------------------------------------------------------
CREATE TABLE saved_jobs (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT NOT NULL,
  job_id     INT NOT NULL,
  saved_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY unique_user_saved_job (user_id, job_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 8. OTP VERIFICATIONS TABLE
-- Email OTP verification for Registration & Password Reset
-- ------------------------------------------------------------
CREATE TABLE otp_verifications (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  user_id     INT DEFAULT NULL,
  email       VARCHAR(100) NOT NULL,
  otp_hash    VARCHAR(255) NOT NULL,
  purpose     ENUM('registration', 'password_reset') NOT NULL DEFAULT 'registration',
  is_used     TINYINT(1) DEFAULT 0,
  expires_at  DATETIME NOT NULL,
  attempts    INT DEFAULT 0,
  verified_at DATETIME DEFAULT NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_purpose (purpose),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
-- 9. NOTIFICATIONS TABLE
-- User notification system
-- ------------------------------------------------------------
CREATE TABLE notifications (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT NOT NULL,
  title      VARCHAR(150) NOT NULL,
  message    TEXT NOT NULL,
  type       VARCHAR(50) DEFAULT 'info',
  is_read    TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX idx_user_read (user_id, is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
