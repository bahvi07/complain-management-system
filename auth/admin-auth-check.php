<?php
session_start();

$timeout = 1800; // 30 minutes

// Validate session & login status
if (
    !isset($_SESSION['admin_id']) ||
    !isset($_SESSION['admin_name']) ||
    !isset($_SESSION['admin_email']) ||
    (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > $timeout)
) {
    session_unset();
    session_destroy();

    echo "<script>
        window.location.href = '../auth/admin-login.php';
    </script>";
    exit;
}

// Refresh last activity time
$_SESSION['last_activity'] = time();
?>
