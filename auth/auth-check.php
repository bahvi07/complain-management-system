<?php
session_start();

$timeout = 1800; // 30 minutes

if (
    !isset($_SESSION['is_logged_in']) ||
    $_SESSION['is_logged_in'] !== true ||
    (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > $timeout)
) {
    session_unset();
    session_destroy();
    echo "<script>
            alert('Session expired. Please log in again.');
          </script>";
          header('Location:../auth/auth-check.php');
    exit;
}

$_SESSION['last_activity'] = time();
?>
