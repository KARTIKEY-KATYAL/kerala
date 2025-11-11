<?php
session_start();

// -----------------------------
// Timeout Configurations
// -----------------------------
$idle_timeout = 300; // 5 minutes idle timeout
$absolute_timeout = 1800; // 30 minutes absolute timeout

// -----------------------------
// Check Idle Timeout
// -----------------------------
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $idle_timeout) {
    session_unset();
    session_destroy();
    header("Location: AdminLogin.html?error=idle_timeout");
    exit();
}
$_SESSION['last_activity'] = time();

// -----------------------------
// Check Absolute Timeout
// -----------------------------
if (isset($_SESSION['created']) && (time() - $_SESSION['created']) > $absolute_timeout) {
    session_unset();
    session_destroy();
    header("Location: AdminLogin.html?error=absolute_timeout");
    exit();
}

// If session just created, mark creation time
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
}

// -----------------------------
// Auth Check
// -----------------------------
if (isset($_SESSION['user']) && isset($_SESSION['token'])) {
    require('Connection.php');
    $user = $_SESSION['user'];
    $token = $_SESSION['token'];
    $query = "SELECT * FROM login WHERE username='$user' AND token='$token'";
    $result = mysqli_query($con, $query);
    $numrows = mysqli_num_rows($result);
    
    if ($numrows == 0) {
        session_unset();
        session_destroy();
        header("Location: AdminLogin.html?error=invalid_session");
        exit();
    } else {
        // Update last login time
        $currentLoginTime = date("Y-m-d H:i:s");
        $queryUpdate = "UPDATE login SET lastlogin='$currentLoginTime' WHERE username='$user'";
        mysqli_query($con, $queryUpdate);
    }
} else {
    header("Location: AdminLogin.html?error=no_session");
    exit();
}
?>
