<?php
/**
 * CareerConnect - Canonical database connection
 */

require_once __DIR__ . '/constants.php';

if (!defined('DB_HOST')) {
    define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
}
if (!defined('DB_USER')) {
    define('DB_USER', getenv('DB_USER') ?: 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', getenv('DB_NAME') ?: 'careerconnect');
}
if (!defined('DB_PORT')) {
    define('DB_PORT', getenv('DB_PORT') ? (int) getenv('DB_PORT') : 3306);
}

function get_db_connection() {
    static $conn = null;

    if ($conn instanceof mysqli && $conn->ping()) {
        return $conn;
    }

    mysqli_report(MYSQLI_REPORT_OFF);
    $portsToTry = array_values(array_unique([DB_PORT, 3306, 3307, 3308]));

    foreach ($portsToTry as $port) {
        try {
            $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, $port);
            if (!$conn->connect_error) {
                $conn->set_charset('utf8mb4');
                return $conn;
            }
        } catch (Throwable $e) {
            // try next port
        }
    }

    foreach ($portsToTry as $port) {
        try {
            $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, '', $port);
            if (!$conn->connect_error) {
                $conn->set_charset('utf8mb4');
                @$conn->select_db(DB_NAME);
                return $conn;
            }
        } catch (Throwable $e) {
            // try next port
        }
    }

    error_log('Database connection failed for host: ' . DB_HOST . ' and database: ' . DB_NAME);
    return null;
}

function ensure_database_schema() {
    global $conn;

    if (!$conn) {
        return false;
    }

    $requiredTables = ['roles', 'users', 'user_profiles', 'categories', 'jobs', 'otp_verifications'];
    foreach ($requiredTables as $table) {
        $tableCheck = $conn->query("SHOW TABLES LIKE '" . str_replace("'", "''", $table) . "'");
        if (!$tableCheck || $tableCheck->num_rows === 0) {
            $schemaSql = file_get_contents(__DIR__ . '/../database/schema.sql');
            if ($schemaSql === false) {
                error_log('Unable to read database/schema.sql for automatic schema initialization.');
                return false;
            }

            if (!$conn->multi_query($schemaSql)) {
                error_log('Database schema initialization failed: ' . $conn->error);
                return false;
            }

            do {
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->more_results() && $conn->next_result());

            return true;
        }
    }

    $requiredColumns = [
        'otp_verifications' => ['id', 'user_id', 'email', 'otp_hash', 'purpose', 'is_used', 'expires_at', 'attempts', 'verified_at', 'created_at'],
        'users' => ['id', 'role_id', 'username', 'email', 'password_hash', 'full_name', 'phone', 'status', 'is_verified', 'created_at', 'updated_at'],
    ];

    foreach ($requiredColumns as $table => $columns) {
        $existing = $conn->query("SHOW COLUMNS FROM `{$table}`");
        if (!$existing) {
            error_log('Unable to inspect columns for table: ' . $table);
            continue;
        }

        $columnNames = [];
        while ($row = $existing->fetch_assoc()) {
            $columnNames[] = $row['Field'];
        }

        foreach ($columns as $column) {
            if (!in_array($column, $columnNames, true)) {
                $sql = '';
                if ($table === 'otp_verifications' && $column === 'user_id') {
                    $sql = 'ALTER TABLE `otp_verifications` ADD COLUMN `user_id` INT NULL AFTER `id`';
                } elseif ($table === 'otp_verifications' && $column === 'is_used') {
                    $sql = 'ALTER TABLE `otp_verifications` ADD COLUMN `is_used` TINYINT(1) NOT NULL DEFAULT 0 AFTER `purpose`';
                } elseif ($table === 'otp_verifications' && $column === 'verified_at') {
                    $sql = 'ALTER TABLE `otp_verifications` ADD COLUMN `verified_at` DATETIME NULL AFTER `attempts`';
                } elseif ($table === 'users' && $column === 'updated_at') {
                    $sql = 'ALTER TABLE `users` ADD COLUMN `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`';
                }

                if ($sql !== '' && !$conn->query($sql)) {
                    error_log('Database migration failed for ' . $table . '.' . $column . ': ' . $conn->error);
                }
            }
        }
    }

    return true;
}

$conn = get_db_connection();
ensure_database_schema();
