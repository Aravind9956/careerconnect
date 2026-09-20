-- ============================================================
-- CareerConnect - Database Schema Migration SQL
-- Migration script to update existing database structure cleanly
-- ============================================================

USE careerconnect;

-- 1. Update jobs table to support numeric INR salary min/max
SET @exist_salary_min = (
    SELECT COUNT(*) FROM information_schema.columns 
    WHERE table_schema = DATABASE() AND table_name = 'jobs' AND column_name = 'salary_min'
);

SET @sql = IF(@exist_salary_min = 0, 
    'ALTER TABLE jobs ADD COLUMN salary_min DECIMAL(12,2) DEFAULT NULL AFTER experience_level, ADD COLUMN salary_max DECIMAL(12,2) DEFAULT NULL AFTER salary_min;', 
    'SELECT "salary_min already exists";'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 2. Update otp_verifications table structure
SET @exist_otp_hash = (
    SELECT COUNT(*) FROM information_schema.columns 
    WHERE table_schema = DATABASE() AND table_name = 'otp_verifications' AND column_name = 'otp_hash'
);

SET @sql_otp = IF(@exist_otp_hash = 0, 
    'ALTER TABLE otp_verifications 
     ADD COLUMN user_id INT DEFAULT NULL AFTER id,
     ADD COLUMN otp_hash VARCHAR(255) NOT NULL AFTER email,
     ADD COLUMN purpose ENUM("registration", "password_reset") DEFAULT "registration" AFTER otp_hash,
     ADD COLUMN attempts INT DEFAULT 0 AFTER expires_at,
     ADD COLUMN verified_at DATETIME DEFAULT NULL AFTER attempts,
     MODIFY COLUMN otp_code VARCHAR(255) NULL;', 
    'SELECT "otp_hash already exists";'
);
PREPARE stmt_otp FROM @sql_otp;
EXECUTE stmt_otp;
DEALLOCATE PREPARE stmt_otp;

-- 3. Populate numeric salary values for seeded/existing jobs (in INR)
UPDATE jobs SET salary_min = 600000, salary_max = 800000 WHERE id = 1 AND (salary_min IS NULL OR salary_min = 0);
UPDATE jobs SET salary_min = 250000, salary_max = 350000 WHERE id = 2 AND (salary_min IS NULL OR salary_min = 0);
UPDATE jobs SET salary_min = 450000, salary_max = 650000 WHERE id = 3 AND (salary_min IS NULL OR salary_min = 0);
UPDATE jobs SET salary_min = 800000, salary_max = 1100000 WHERE id = 4 AND (salary_min IS NULL OR salary_min = 0);
UPDATE jobs SET salary_min = 1200000, salary_max = 1500000 WHERE id = 5 AND (salary_min IS NULL OR salary_min = 0);
UPDATE jobs SET salary_min = 200000, salary_max = 300000 WHERE id = 6 AND (salary_min IS NULL OR salary_min = 0);

-- Update string salary_range to Indian LPA format
UPDATE jobs SET salary_range = '₹6.0 - ₹8.0 LPA' WHERE id = 1;
UPDATE jobs SET salary_range = '₹2.5 - ₹3.5 LPA' WHERE id = 2;
UPDATE jobs SET salary_range = '₹4.5 - ₹6.5 LPA' WHERE id = 3;
UPDATE jobs SET salary_range = '₹8.0 - ₹11.0 LPA' WHERE id = 4;
UPDATE jobs SET salary_range = '₹12.0 - ₹15.0 LPA' WHERE id = 5;
UPDATE jobs SET salary_range = '₹2.0 - ₹3.0 LPA' WHERE id = 6;
