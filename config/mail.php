<?php
/**
 * CareerConnect - Email & SMTP Mailer Configuration
 */

define('MAIL_HOST', getenv('MAIL_HOST') ?: 'localhost');
define('MAIL_PORT', (int)(getenv('MAIL_PORT') ?: 587));
define('MAIL_USERNAME', getenv('MAIL_USERNAME') ?: '');
define('MAIL_PASSWORD', getenv('MAIL_PASSWORD') ?: '');
define('MAIL_FROM_ADDRESS', getenv('MAIL_FROM_ADDRESS') ?: 'noreply@careerconnect.com');
define('MAIL_FROM_NAME', getenv('MAIL_FROM_NAME') ?: 'CareerConnect Team');
define('MAIL_ENCRYPTION', getenv('MAIL_ENCRYPTION') ?: 'tls'); // tls, ssl, or none

// Application Environment & Development Fallback Mode
define('APP_ENV', getenv('APP_ENV') ?: 'development');
define('DEV_OTP_MODE', getenv('DEV_OTP_MODE') !== false ? filter_var(getenv('DEV_OTP_MODE'), FILTER_VALIDATE_BOOLEAN) : true);
