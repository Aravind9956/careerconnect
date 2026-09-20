<?php
require_once __DIR__ . '/database.php';

if (!isset($conn) || !$conn instanceof mysqli) {
    http_response_code(500);
    die('Database connection failed. Import database/schema.sql and check config/database.php.');
}
