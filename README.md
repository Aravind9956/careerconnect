# CareerConnect — Job & Internship Portal

> **"Discover Opportunities. Build Your Career."**  
> *ApexPlanet 60-Day Full Stack Web Development Internship Capstone Project (Tasks 1 through 5)*

---

## 🌟 Overview
**CareerConnect** is an enterprise-grade, responsive full-stack job and internship platform built using pure **PHP 8+**, **MySQL**, **JavaScript (ES6+)**, **Bootstrap 5**, and **Chart.js**. The platform seamlessly connects three distinct user roles:

1. **Job Seekers / Students**: Search and filter opportunities in real-time, submit job applications with resume uploads, track application status updates, and bookmark jobs.
2. **Recruiters / Employers**: Post jobs, edit listings, manage active hiring campaigns, review candidate applications, inspect resumes, and manage applicant pipeline statuses.
3. **Administrators**: Moderation control, user management CRUD, job category management, and real-time Chart.js platform analytics.

---

## 🛠️ Tech Stack & Architecture

- **Frontend**: HTML5, CSS3 (SaaS Design System with `:root` CSS Variables), ES6+ JavaScript, Bootstrap 5.3, Bootstrap Icons, Chart.js CDN, Google Fonts (Outfit & Inter).
- **Backend**: PHP 8+, MySQL (`mysqli` with Prepared Statements), PHP Sessions, Anti-CSRF Protection, AJAX / Fetch API.
- **Tools & Server**: XAMPP / Apache, phpMyAdmin, Git, GitHub.

---

## 🚀 Quick Setup Instructions (XAMPP)

1. **Clone or Copy Project**:
   Place the project files into your XAMPP web root (e.g. `C:\xampp\htdocs\careerconnect` or directory `f:\task3`).

2. **Start Servers**:
   Launch XAMPP Control Panel and start **Apache** and **MySQL**.

3. **Import Database Schema & Seed Data**:
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Create a database named `careerconnect`.
   - Import `database/schema.sql`.
   - Import `database/seed.sql`.

4. **Environment Check**:
   Open `http://localhost/careerconnect/hello.php` in your browser to verify PHP and MySQL database connectivity.

5. **Demo Account Credentials**:
   - **Job Seeker**: `john.doe@gmail.com` / `Password123!`
   - **Recruiter**: `techcorp@careerconnect.com` / `Password123!`
   - **Admin**: `admin@careerconnect.com` / `Password123!`

---

## 🔒 Security Highlights

- **SQL Injection Prevention**: 100% of database queries handling user inputs use MySQLi prepared statements (`mysqli_prepare`).
- **Encrypted Password Storage**: Passwords are encrypted using `password_hash($password, PASSWORD_BCRYPT)` and validated via `password_verify()`.
- **Anti-CSRF Protection**: All POST operations require a cryptographically safe token generated per session.
- **XSS Escaping**: User inputs rendered in templates are sanitized with `escape()` (`htmlspecialchars`).
- **File Upload Security**: File extension whitelisting, MIME check, 5MB size cap, and safe unique filename generation (`resume_USERID_TIMESTAMP.pdf`).

---

## 📜 Prepared Git Commit Plan (10+ Meaningful Commits)

```bash
git init
git add .
git commit -m "feat: create project foundation and file architecture"
git commit -m "feat: build responsive landing page with 11 dynamic sections"
git commit -m "feat: implement SaaS design system and CSS custom properties"
git commit -m "feat: design 3NF database schema and seed data DDL"
git commit -m "feat: implement password hashing and session authentication"
git commit -m "feat: build registration and real-time AJAX email validation"
git commit -m "feat: add email OTP verification workflow"
git commit -m "feat: add job seeker candidate dashboard and profile management"
git commit -m "feat: build recruiter portal with job CRUD and applicant pipeline"
git commit -m "feat: implement debounced AJAX live job search and pagination"
git commit -m "feat: build admin dashboard and Chart.js analytics engine"
git commit -m "feat: add custom error pages (404, 403, 500)"
git commit -m "docs: add full project documentation (SRS, ER, API, Security, Deployment)"
```

---

## 📽️ 12-Minute Demo Presentation Guide

1. **Minutes 0–2 (Task 1 Setup & Landing Page)**: Show `hello.php` environment check, then walk through `index.php` hero search, categories, featured jobs, how it works, and footer.
2. **Minutes 2–4 (Task 2 & 5 Registration & OTP)**: Demonstrate `register.php`, real-time AJAX email check (`check-email.php`), password match validation, 6-digit OTP code entry on `verify-otp.php`.
3. **Minutes 4–6 (Task 3 & 4 Candidate Workflow)**: Sign in as candidate (`john.doe@gmail.com`), browse `jobs.php` with AJAX filters, open `job-details.php`, submit job application with resume, view `user/dashboard.php` and `user/applications.php`.
4. **Minutes 6–9 (Task 3 & 4 Recruiter Workflow)**: Sign in as recruiter (`techcorp@careerconnect.com`), open `recruiter/dashboard.php`, post new job on `recruiter/create-job.php`, open `recruiter/applications.php`, review candidate resume, and change application status to `Shortlisted`/`Selected`.
5. **Minutes 9–11 (Task 4 & 5 Admin & Analytics)**: Sign in as admin (`admin@careerconnect.com`), show `admin/users.php` user management, open `admin/analytics.php` and showcase Chart.js live graphs.
6. **Minutes 11–12 (Conclusion & Docs)**: Show `docs/` folder, `database/schema.sql`, and conclude presentation.

---

## ✅ ApexPlanet Internship Tasks Verification Checklist

### Task 1 — Foundation & Environment Setup
- [x] HTML5 semantic layout (`header`, `nav`, `main`, `section`, `article`, `footer`).
- [x] CSS3 custom properties system, flexbox, grid, soft shadows, transitions.
- [x] JS ES6 DOM handling and form validation.
- [x] PHP syntax, control structures, and MySQLi database connection.
- [x] `hello.php` environment verification script.
- [x] Professional landing page with 11 required sections.
- [x] 10+ prepared Git commit history plan.

### Task 2 — Interactive UI & Frontend Development
- [x] Bootstrap 5 grid, cards, modals, buttons, responsive mobile-first design.
- [x] Responsive Login & Registration UI.
- [x] Real-time client validation (required fields, email format, password match, visibility toggle).
- [x] Real-time AJAX email uniqueness check (`ajax/check-email.php`).
- [x] Reusable navbar, footer, form components, alerts, and toasts.

### Task 3 — Backend Development & Database Integration
- [x] 3NF normalized MySQL database (`roles`, `users`, `user_profiles`, `categories`, `jobs`, `applications`, `saved_jobs`, `otp_verifications`, `notifications`).
- [x] Role-Based Access Control (ADMIN, RECRUITER, USER).
- [x] Secure authentication with `password_hash` and `password_verify`.
- [x] 100% prepared statements (`mysqli_prepare`).
- [x] Profile management with photo and resume upload validation.

### Task 4 — Real-World Full Stack Project
- [x] Job Seeker module (search, filter, save jobs, apply with resume, status tracking).
- [x] Recruiter module (job posting CRUD, applicant review, status pipeline).
- [x] Admin module (user management CRUD, job moderation CRUD, categories CRUD).
- [x] Live AJAX search and pagination.
- [x] Responsive dashboards for all 3 roles.

### Task 5 — Capstone Project & Deployment
- [x] Email OTP verification on registration & forgot password reset workflow.
- [x] Interactive Chart.js analytics powered by live MySQL data.
- [x] Complete documentation in `docs/` (`REQUIREMENTS.md`, `ER-DIAGRAM.md`, `WIREFRAMES.md`, `API-DOCUMENTATION.md`, `SECURITY.md`, `DEPLOYMENT.md`, `TESTING.md`).
- [x] Deployment template (`config/database.example.php`).
- [x] Custom error pages (`404.php`, `403.php`, `500.php`).
