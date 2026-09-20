# CareerConnect - Deployment Guide (XAMPP / Production Hosting)

This guide details steps for deploying CareerConnect on local XAMPP environments or shared PHP/MySQL cloud hosting (e.g., InfinityFree, 000webhost, cPanel).

---

## 1. Local Deployment on XAMPP

1. **Copy Source Files**:
   Place the project folder into your XAMPP `htdocs` directory (e.g. `C:\xampp\htdocs\careerconnect` or root directory).

2. **Start Apache & MySQL**:
   Open XAMPP Control Panel and start both **Apache** and **MySQL** services.

3. **Import Database Schema & Seed Data**:
   - Open phpMyAdmin in your browser (`http://localhost/phpmyadmin`).
   - Create a database named `careerconnect`.
   - Click **Import** and select `database/schema.sql`.
   - Click **Import** and select `database/seed.sql`.

4. **Database Configuration Check**:
   Inspect `config/database.php`. The connection automatically tests default MySQL ports (3306 and 3307).

5. **Test Platform**:
   Navigate to `http://localhost/careerconnect/hello.php` to verify PHP & MySQL connection.

---

## 2. Production Deployment (cPanel / InfinityFree)

1. **Database Setup**:
   - Create a MySQL database and user on your hosting account.
   - Import `database/schema.sql` and `database/seed.sql`.

2. **Update Configuration**:
   - Copy `config/database.example.php` to `config/database.php`.
   - Configure `DB_HOST`, `DB_USER`, `DB_PASS`, and `DB_NAME` with production credentials.

3. **File Permissions**:
   Ensure `uploads/profiles` and `uploads/resumes` folders have write permissions (`755` or `777`).
