# CareerConnect - Security Audit & OWASP Mitigations

## 1. SQL Injection Prevention
- **Implementation**: 100% of queries handling user inputs utilize MySQLi prepared statements (`$conn->prepare()`, `bind_param()`, `execute()`).
- **Verification**: Zero raw SQL concatenation exists across authentication, job search, profile management, and dashboard handlers.

---

## 2. Password Hashing
- **Implementation**: All user passwords are encrypted using `password_hash($password, PASSWORD_BCRYPT)` and validated via `password_verify()`.
- **Enforcement**: Plaintext passwords are NEVER stored in database tables or logs.

---

## 3. Cross-Site Request Forgery (CSRF)
- **Implementation**: `includes/csrf.php` generates cryptographically secure 256-bit random tokens stored in `$_SESSION['csrf_token']`.
- **Validation**: Every POST form contains a hidden `csrf_token` input validated with `hash_equals()`.

---

## 4. Cross-Site Scripting (XSS)
- **Implementation**: `escape()` helper function (`htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`) wraps all user-generated content prior to HTML rendering.

---

## 5. File Upload Hardening
- **Validation**: Strict whitelist enforcement (`ALLOWED_AVATAR_EXT`, `ALLOWED_RESUME_EXT`).
- **File Size**: Hard limit of 5 MB (`MAX_FILE_SIZE`).
- **Filename Sanitization**: Uploaded files are renamed using safe hashes (`resume_USERID_TIMESTAMP.pdf`) to prevent path traversal and script execution.
