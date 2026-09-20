<?php
/**
 * CareerConnect - Automated Database Installer & Importer
 * Automatically creates the database and imports schema.sql and seed.sql
 */

define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'careerconnect');

// Ports to test (3307 for XAMPP default, 3306 for standard MySQL)
$ports = [3307, 3306];
$conn = null;
$connected_port = null;

echo "==================================================\n";
echo "CareerConnect - Automatic Database Setup & Importer\n";
echo "==================================================\n\n";

foreach ($ports as $port) {
    try {
        $test_conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, '', $port);
        if (!$test_conn->connect_error) {
            $conn = $test_conn;
            $connected_port = $port;
            echo "[+] Successfully connected to MySQL server on port {$port}.\n";
            break;
        }
    } catch (\Throwable $e) {
        // Continue
    }
}

if (!$conn) {
    die("[-] ERROR: Could not connect to MySQL server on ports 3306 or 3307. Please make sure MySQL service is running in XAMPP.\n");
}

// 1. Create Database if not exists
echo "[+] Creating database `" . DB_NAME . "`...\n";
if ($conn->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
    echo "[+] Database `" . DB_NAME . "` created or verified successfully.\n";
} else {
    die("[-] ERROR creating database: " . $conn->error . "\n");
}

$conn->select_db(DB_NAME);

// Function to execute SQL file with multi_query
function execute_sql_file($mysqli, $filePath)
{
    if (!file_exists($filePath)) {
        echo "[-] SQL file not found: {$filePath}\n";
        return false;
    }

    $sql = file_get_contents($filePath);
    if ($mysqli->multi_query($sql)) {
        do {
            if ($result = $mysqli->store_result()) {
                $result->free();
            }
        } while ($mysqli->more_results() && $mysqli->next_result());
    }

    if ($mysqli->error) {
        echo "[-] SQL Execution Error: " . $mysqli->error . "\n";
        return false;
    }
    return true;
}

// 2. Import Schema
$schema_file = __DIR__ . '/database/schema.sql';
echo "[+] Importing Database Schema from database/schema.sql...\n";
if (execute_sql_file($conn, $schema_file)) {
    echo "[+] Schema imported successfully!\n";
} else {
    echo "[-] Schema import failed.\n";
}

// 3. Import Seed Data
$seed_file = __DIR__ . '/database/seed.sql';
echo "[+] Importing Seed Data from database/seed.sql...\n";
if (execute_sql_file($conn, $seed_file)) {
    echo "[+] Seed data imported successfully!\n";
} else {
    echo "[-] Seed data import failed.\n";
}

echo "\n==================================================\n";
echo "Setup Complete! You can now visit index.php or hello.php.\n";
echo "==================================================\n";
