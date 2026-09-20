# CareerConnect - System Requirements Specification (SRS)

## 1. Project Overview
**CareerConnect** ("Discover Opportunities. Build Your Career.") is an enterprise-grade full-stack web application designed for the ApexPlanet 60-Day Web Development Internship Capstone. It bridges three core user roles: **Job Seekers / Students**, **Recruiters / Employers**, and **System Administrators**.

---

## 2. Functional Requirements

### 2.1 Public & Guest Users
- Browse high-converting landing page with 11 distinct dynamic sections.
- Search and filter active job postings by keyword, location, category, job type, and experience level.
- View detailed job specifications, company profile, and requirements.
- Register account with role selection (Job Seeker / Recruiter).
- Real-time AJAX verification of email and username uniqueness.
- Email OTP identity verification during registration and password reset workflows.

### 2.2 Job Seekers / Candidates
- Dedicated Candidate Dashboard displaying application status counts (Applied, Under Review, Shortlisted, Selected).
- Application tracker with real-time recruiter status updates.
- Profile management: update bio, title, skills, work experience, education, location, and upload resume PDF (up to 5MB).
- One-click job application with custom cover note and resume upload.
- Bookmark and save jobs for later review.

### 2.3 Recruiters / Employers
- Dedicated Recruiter Dashboard with metrics (Active Jobs, Total Applicants, Shortlisted Candidates).
- Full Job Posting CRUD (Create, Read, Edit, Toggle Active/Closed Status, Delete with modal confirmation).
- Applicant Management Pipeline: review candidate profiles, inspect uploaded resumes, and update status in real-time.

### 2.4 System Administrators
- Full Admin Control Dashboard with system-wide analytics.
- User Management CRUD: view all users, modify roles, toggle active/suspended account status, delete users.
- Global Job Moderation CRUD: review, edit, or delete any job posting.
- Job Categories CRUD: add new job categories with custom icons and slugs.
- Interactive Chart.js Analytics: user registration growth, application status breakdown, jobs per category.

---

## 3. Non-Functional Requirements

### 3.1 Security & Data Integrity
- Passwords MUST be encrypted using `password_hash()` (BCRYPT) and verified via `password_verify()`.
- ALL SQL queries handling user input MUST use MySQLi prepared statements (`mysqli_prepare`) to prevent SQL Injection.
- Comprehensive Anti-CSRF token verification on all POST operations.
- Output escaping (`htmlspecialchars`) across all UI templates to prevent Cross-Site Scripting (XSS).
- Strict session management (`session_regenerate_id()`) to prevent Session Fixation attacks.
- Strict MIME type and file extension validation for image and resume uploads.

### 3.2 UI/UX & Responsive Aesthetics
- Custom SaaS design system built on Bootstrap 5 grid and CSS variables.
- Responsive design tailored across Mobile (320px–414px), Tablet (768px–820px), and Desktop (1024px–1920px).
- Smooth hover animations, glassmorphism badges, soft shadows, and clean modern typography.
