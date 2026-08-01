<?php
require_once('admin/inc/db_config.php');
require_once('admin/inc/essentials.php');

// Prevent caching
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Clear all session variables
$_SESSION = array();

// Delete the session cookie if it exists
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Clear browser storage and redirect
echo "<script>
    // Clear all browser storage
    sessionStorage.clear();
    localStorage.clear();
    
    // Clear any cached data
    if ('caches' in window) {
        caches.keys().then(function(names) {
            names.forEach(function(name) {
                caches.delete(name);
            });
        });
    }
    
    // Force page reload and redirect
    window.location.replace('index.php');
</script>";
exit;
?>