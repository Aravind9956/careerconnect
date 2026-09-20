# CareerConnect - Test Execution & Verification Matrix

## 1. Test Verification Summary

| Test ID | Module / Feature | Verification Steps | Result |
| :--- | :--- | :--- | :--- |
| **TC-01** | Environment | Load `hello.php` to verify PHP 8+ runtime and MySQL connectivity. | **PASSED** |
| **TC-02** | Registration | Register new Job Seeker account; verify BCRYPT hash in DB and OTP generation. | **PASSED** |
| **TC-03** | Email OTP | Enter 6-digit OTP code in `verify-otp.php`; verify user `is_verified=1`. | **PASSED** |
| **TC-04** | Real-Time AJAX Email | Type existing vs new email in registration form; verify instant AJAX badge. | **PASSED** |
| **TC-05** | Authentication | Sign in with valid vs invalid credentials; test session regeneration. | **PASSED** |
| **TC-06** | Role Access Control | Attempt accessing `/admin/dashboard.php` as Job Seeker; verify 403 redirect. | **PASSED** |
| **TC-07** | Job Posting CRUD | Recruiter creates, edits, toggles status, and deletes job listing. | **PASSED** |
| **TC-08** | Job Application | Candidate submits application with cover letter & resume upload; verify applicant pipeline. | **PASSED** |
| **TC-09** | Recruiter Pipeline | Recruiter updates applicant status (Applied -> Shortlisted -> Selected). | **PASSED** |
| **TC-10** | Live AJAX Search | Type keywords on `jobs.php`; verify debounced results without page reload. | **PASSED** |
| **TC-11** | Chart.js Analytics | Admin visits `admin/analytics.php`; verify live Chart.js graphs render. | **PASSED** |
| **TC-12** | Password Reset | Perform Forgot Password OTP reset flow; sign in with new password. | **PASSED** |
