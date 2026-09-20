-- ============================================================
-- CareerConnect - Database Seed Data
-- Demo Credentials:
-- Admin:      admin / admin@careerconnect.com / Password123!
-- Recruiter:  techcorp / techcorp@careerconnect.com / Password123!
-- Job Seeker: john.doe / john.doe@gmail.com / Password123!
-- ============================================================

USE careerconnect;

-- Clear existing data
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE notifications;
TRUNCATE TABLE otp_verifications;
TRUNCATE TABLE saved_jobs;
TRUNCATE TABLE applications;
TRUNCATE TABLE jobs;
TRUNCATE TABLE categories;
TRUNCATE TABLE user_profiles;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------
-- 1. USERS
-- Password hash for 'Password123!': $2y$12$PZW51kFsvIWgeQChGHvGKeUfhdtYseN4JHK7qFN3JBesDL3EAP8eu
-- ------------------------------------------------------------
INSERT INTO users (id, role_id, username, email, password_hash, full_name, phone, status, is_verified) VALUES
(1, 1, 'admin', 'admin@careerconnect.com', '$2y$12$PZW51kFsvIWgeQChGHvGKeUfhdtYseN4JHK7qFN3JBesDL3EAP8eu', 'Platform Administrator', '+91 9876543210', 'active', 1),
(2, 2, 'techcorp', 'techcorp@careerconnect.com', '$2y$12$PZW51kFsvIWgeQChGHvGKeUfhdtYseN4JHK7qFN3JBesDL3EAP8eu', 'TechCorp Solutions HR', '+91 9876543211', 'active', 1),
(3, 2, 'innovate', 'innovate@careerconnect.com', '$2y$12$PZW51kFsvIWgeQChGHvGKeUfhdtYseN4JHK7qFN3JBesDL3EAP8eu', 'Innovate Labs Talent', '+91 9876543212', 'active', 1),
(4, 3, 'john.doe', 'john.doe@gmail.com', '$2y$12$PZW51kFsvIWgeQChGHvGKeUfhdtYseN4JHK7qFN3JBesDL3EAP8eu', 'John Doe', '+91 9876543213', 'active', 1),
(5, 3, 'jane.smith', 'jane.smith@gmail.com', '$2y$12$PZW51kFsvIWgeQChGHvGKeUfhdtYseN4JHK7qFN3JBesDL3EAP8eu', 'Jane Smith', '+91 9876543214', 'active', 1),
(6, 3, 'alex.wong', 'alex.wong@gmail.com', '$2y$12$PZW51kFsvIWgeQChGHvGKeUfhdtYseN4JHK7qFN3JBesDL3EAP8eu', 'Alex Wong', '+91 9876543215', 'active', 1);

-- ------------------------------------------------------------
-- 2. USER PROFILES
-- ------------------------------------------------------------
INSERT INTO user_profiles (user_id, title, bio, skills, experience, education, location, company_name, website, avatar, resume) VALUES
(1, 'Lead System Admin', 'Managing CareerConnect platform operations & security.', 'System Administration, PHP, Security, MySQL', '10+ years in DevOps & IT Admin', 'M.Tech Computer Science - IIT Bombay', 'Bengaluru, Karnataka', 'CareerConnect Inc.', 'https://careerconnect.org', 'default-avatar.png', NULL),
(2, 'Senior Talent Acquisition Specialist', 'Hiring top tech engineering talent worldwide at TechCorp Solutions.', 'Talent Sourcing, Technical Recruiting, HR Strategy', '7 years in IT Recruiting', 'MBA HR - IIM Bangalore', 'Bengaluru, Karnataka', 'TechCorp Solutions', 'https://techcorp.example.com', 'default-avatar.png', NULL),
(3, 'Head of Engineering & Hiring', 'Building cutting-edge AI and cloud applications.', 'AI Systems, Python, Cloud Architecture', '8 years Tech Lead', 'B.Tech Software Engineering - NIT Trichy', 'Hyderabad, Telangana', 'Innovate Labs', 'https://innovatelabs.example.com', 'default-avatar.png', NULL),
(4, 'Full Stack Web Developer', 'Passionate computer science graduate proficient in PHP, MySQL, JavaScript, and Bootstrap.', 'PHP, MySQL, JavaScript, HTML5, CSS3, Bootstrap, Git', 'Frontend Developer Intern - Acme Web', 'B.Tech Computer Science - VTU (2024)', 'Pune, Maharashtra', NULL, 'https://johndoe.dev', 'default-avatar.png', 'sample-resume.pdf'),
(5, 'UI/UX Designer & Frontend Developer', 'Creating beautiful visual designs and interactive web applications.', 'UI/UX Design, Figma, JavaScript, CSS Grid, React, Bootstrap', 'UI Design Intern - Design Studio', 'B.Des Digital Design - NID', 'Mumbai, Maharashtra', NULL, 'https://janesmith.design', 'default-avatar.png', 'sample-resume.pdf'),
(6, 'Data Analyst & ML Aspirant', 'Enthusiastic about data visualization, Python data science, and MySQL database management.', 'Python, SQL, MySQL, Pandas, Chart.js, Tableau, Excel', 'Data Analyst Trainee - Analytics Pro', 'B.S. Data Science - Delhi University', 'Delhi NCR', NULL, 'https://alexwong.data', 'default-avatar.png', 'sample-resume.pdf');

-- ------------------------------------------------------------
-- 3. CATEGORIES
-- ------------------------------------------------------------
INSERT INTO categories (id, name, slug, icon, description) VALUES
(1, 'Software Development', 'software-development', 'bi-code-slash', 'Web engineering, backend development, and full-stack software development roles.'),
(2, 'Data Science & AI', 'data-science-ai', 'bi-cpu', 'Machine learning, big data analytics, data engineering, and AI models.'),
(3, 'UI/UX & Product Design', 'ui-ux-design', 'bi-palette', 'User experience design, graphic design, interactive wireframing, and Figma design.'),
(4, 'Digital Marketing', 'digital-marketing', 'bi-megaphone', 'SEO, social media strategy, content marketing, and growth hacking.'),
(5, 'Product Management', 'product-management', 'bi-diagram-3', 'Agile product owners, product roadmap strategies, and project management.'),
(6, 'DevOps & Cloud', 'devops-cloud', 'bi-cloud-check', 'AWS, Azure, Docker, Kubernetes, CI/CD pipelines, and cloud administration.'),
(7, 'Cybersecurity', 'cybersecurity', 'bi-shield-lock', 'Network security, ethical hacking, threat intelligence, and compliance.'),
(8, 'Mobile App Development', 'mobile-app-development', 'bi-phone', 'iOS Swift development, Android Kotlin, React Native, and Flutter app building.');

-- ------------------------------------------------------------
-- 4. JOBS
-- ------------------------------------------------------------
INSERT INTO jobs (id, recruiter_id, category_id, title, company_name, company_logo, location, job_type, experience_level, salary_min, salary_max, salary_range, description, requirements, skills_required, status, views_count) VALUES
(1, 2, 1, 'Full Stack Web Developer (PHP/MySQL)', 'TechCorp Solutions', 'techcorp-logo.png', 'Bengaluru (Remote)', 'Full-time', 'Mid', 600000.00, 800000.00, '₹6.0 - ₹8.0 LPA', 
'We are seeking a talented Full Stack Web Developer to join our core SaaS product team. You will build high-performance web interfaces using PHP 8, MySQL, JavaScript, and Bootstrap 5.', 
'• 2+ years experience with PHP & MySQL\n• Proficiency in JavaScript ES6, HTML5, CSS3, and Bootstrap\n• Experience with RESTful APIs and AJAX\n• Knowledge of database normalization and SQL optimization', 
'PHP, MySQL, JavaScript, Bootstrap 5, AJAX, HTML5, CSS3', 'active', 142),

(2, 2, 1, 'Web Development Intern', 'TechCorp Solutions', 'techcorp-logo.png', 'Remote', 'Internship', 'Entry', 250000.00, 350000.00, '₹2.5 - ₹3.5 LPA', 
'Join our 3-month summer internship program! Great opportunity for computer science students to gain real-world web development experience working directly with senior developers.', 
'• Pursuing B.Tech/B.E. in Computer Science or related field\n• Solid understanding of HTML, CSS, JavaScript, and basic PHP\n• Eager to learn modern web architecture and database design', 
'HTML5, CSS3, JavaScript, PHP, MySQL, Git', 'active', 289),

(3, 3, 2, 'Junior Data Analyst', 'Innovate Labs', 'innovate-logo.png', 'Hyderabad', 'Full-time', 'Entry', 450000.00, 650000.00, '₹4.5 - ₹6.5 LPA', 
'Innovate Labs is looking for a detail-oriented Junior Data Analyst to query large datasets, build dynamic dashboards, and provide actionable business analytics.', 
'• Strong proficiency in SQL & MySQL queries\n• Experience with Python (Pandas/NumPy) or Chart.js\n• B.S./B.Tech in Data Analytics, Statistics, or Math', 
'SQL, MySQL, Python, Chart.js, Data Visualization, Excel', 'active', 195),

(4, 3, 3, 'UI/UX Product Designer', 'Innovate Labs', 'innovate-logo.png', 'Hyderabad (Hybrid)', 'Full-time', 'Mid', 800000.00, 1100000.00, '₹8.0 - ₹11.0 LPA', 
'Design interactive, user-centric web applications and mobile prototypes. You will collaborate closely with product managers and frontend engineers.', 
'• 3+ years product design experience\n• Mastery of Figma, Adobe CC, and responsive grid layouts\n• Portfolio demonstrating clean visual design and user flow wireframes', 
'Figma, UI/UX, Wireframing, Bootstrap Grid, Prototyping', 'active', 160),

(5, 2, 6, 'DevOps & Cloud Engineer', 'TechCorp Solutions', 'techcorp-logo.png', 'Pune', 'Full-time', 'Senior', 1200000.00, 1500000.00, '₹12.0 - ₹15.0 LPA', 
'Architect and maintain resilient cloud infrastructure on AWS. Implement automated CI/CD pipelines, Docker containers, and security monitoring.', 
'• 4+ years cloud devops experience\n• Experience with Docker, Kubernetes, AWS, and Linux administration\n• Shell scripting and automation', 
'AWS, Docker, Kubernetes, Linux, CI/CD, Terraform', 'active', 98),

(6, 3, 8, 'Mobile App Developer Intern (Flutter)', 'Innovate Labs', 'innovate-logo.png', 'Remote', 'Internship', 'Entry', 200000.00, 300000.00, '₹2.0 - ₹3.0 LPA', 
'Build cross-platform mobile apps for Android and iOS using Flutter and Dart. Work directly on consumer-facing mobile features.', 
'• Hands-on experience building mobile apps with Flutter / Dart\n• Knowledge of REST API integration and local storage', 
'Flutter, Dart, Mobile Development, REST API, Git', 'active', 210);

-- ------------------------------------------------------------
-- 5. APPLICATIONS
-- ------------------------------------------------------------
INSERT INTO applications (id, job_id, user_id, cover_letter, resume_file, status, applied_at) VALUES
(1, 1, 4, 'Dear Hiring Manager at TechCorp, I am excited to apply for the Full Stack Web Developer position. I have strong expertise in PHP 8, MySQL database design, JavaScript, and Bootstrap 5.', 'sample-resume.pdf', 'Shortlisted', NOW() - INTERVAL 5 DAY),
(2, 2, 4, 'Hello! I am a passionate CS graduate interested in the Web Development Internship. I am ready to contribute to TechCorp while expanding my full-stack skills.', 'sample-resume.pdf', 'Under Review', NOW() - INTERVAL 3 DAY),
(3, 3, 6, 'I am excited to submit my application for the Junior Data Analyst role. I specialize in SQL data extraction and dynamic chart visualization.', 'sample-resume.pdf', 'Applied', NOW() - INTERVAL 2 DAY),
(4, 4, 5, 'With a background in UI/UX and frontend technologies, I believe I am a great fit for the UI/UX Product Designer role at Innovate Labs.', 'sample-resume.pdf', 'Selected', NOW() - INTERVAL 7 DAY),
(5, 6, 4, 'I am applying for the Flutter Internship to expand my cross-platform mobile development skills.', 'sample-resume.pdf', 'Applied', NOW() - INTERVAL 1 DAY);

-- ------------------------------------------------------------
-- 6. SAVED JOBS
-- ------------------------------------------------------------
INSERT INTO saved_jobs (id, user_id, job_id, saved_at) VALUES
(1, 4, 1, NOW() - INTERVAL 6 DAY),
(2, 4, 3, NOW() - INTERVAL 4 DAY),
(3, 5, 4, NOW() - INTERVAL 8 DAY),
(4, 6, 3, NOW() - INTERVAL 2 DAY);

-- ------------------------------------------------------------
-- 7. NOTIFICATIONS
-- ------------------------------------------------------------
INSERT INTO notifications (id, user_id, title, message, type, is_read, created_at) VALUES
(1, 4, 'Application Shortlisted!', 'Congratulations! TechCorp Solutions has shortlisted your application for Full Stack Web Developer (PHP/MySQL).', 'success', 0, NOW() - INTERVAL 5 DAY),
(2, 5, 'Application Selected!', 'Awesome news! Innovate Labs has selected your application for UI/UX Product Designer.', 'success', 0, NOW() - INTERVAL 7 DAY),
(3, 4, 'New Job Match', 'TechCorp Solutions posted a new job: Web Development Intern.', 'info', 1, NOW() - INTERVAL 3 DAY);
