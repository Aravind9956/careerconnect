<?php
/**
 * CareerConnect - Central app configuration
 */

$httpProtocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$basePath = rtrim(str_replace('/config', '', $scriptDir), '/');

define('BASE_URL', $httpProtocol . $host . ($basePath ?: ''));
define('APP_BASE_PATH', $basePath ?: '');
define('APP_STORAGE_PATH', ROOT_PATH . '/storage');
