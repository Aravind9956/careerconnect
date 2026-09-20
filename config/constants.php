<?php
/**
 * CareerConnect - Core Configuration Constants
 */

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', str_replace('\\', '/', dirname(__DIR__)));
}

// Application Info
define('APP_NAME', 'CareerConnect');
define('APP_TAGLINE', 'Discover Opportunities. Build Your Career.');
define('APP_VERSION', '1.0.0');

define('APP_STORAGE_PATH', ROOT_PATH . '/storage');

// Base URLs
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$basePath = rtrim(str_replace('/config', '', $scriptDir), '/');
if (!defined('APP_BASE_PATH')) {
    define('APP_BASE_PATH', $basePath ?: '');
}
if (!defined('BASE_URL')) {
    define('BASE_URL', $protocol . $host . (APP_BASE_PATH ?: ''));
}

// Paths
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('PROFILE_UPLOAD_PATH', UPLOAD_PATH . '/profiles');
define('RESUME_UPLOAD_PATH', UPLOAD_PATH . '/resumes');

define('LOG_PATH', ROOT_PATH . '/storage/logs');

// Allowed File Upload Configurations
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5 MB
define('ALLOWED_AVATAR_EXT', ['jpg', 'jpeg', 'png', 'webp']);
define('ALLOWED_RESUME_EXT', ['pdf', 'doc', 'docx']);

// Roles
define('ROLE_ADMIN', 1);
define('ROLE_RECRUITER', 2);
define('ROLE_USER', 3);

// Session Constants
define('SESSION_LIFETIME', 86400); // 24 hours
