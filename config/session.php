<?php
/**
 * CareerConnect - Session Initialization & Security Configuration
 */

if (session_status() === PHP_SESSION_NONE) {
    // Configure secure session cookie settings
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.gc_maxlifetime', 86400);

    session_start();
}

/**
 * Regenerate session ID safely upon login/privilege changes
 */
function regenerate_session() {
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_regenerate_id(true);
    }
}
