<?php
require('Connection.php');

// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$ip_address = "";
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip_address = $_SERVER['REMOTE_ADDR'];
}

$idle_timeout = 1800; // 30 minutes idle timeout
$absolute_timeout = 7200; // 2 hours absolute timeout

// Idle Timeout Check
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $idle_timeout) {
    session_unset();
    session_destroy();
    header("Location: Login.html?error=session_timeout");
    exit();
}
$_SESSION['last_activity'] = time();

// Absolute Timeout Check
if (isset($_SESSION['created']) && (time() - $_SESSION['created']) > $absolute_timeout) {
    session_unset();
    session_destroy();
    header("Location: Login.html?error=absolute_timeout");
    exit();
}
if (!isset($_SESSION['created'])) {
    $_SESSION['created'] = time();
}

// User & token verification
if (isset($_SESSION['district_user'])) {
    $user = $_SESSION['district_user'];
    $token = $_SESSION['district_token'];
    $query = "SELECT * FROM login WHERE username='$user' AND token='$token'";
    $result = mysqli_query($con, $query);
    $numrows = mysqli_num_rows($result);
    
    if ($numrows == 0) {
        session_unset();
        session_destroy();
        header("Location: Login.html?error=invalid_session");
        exit();
    } else {
        // Update last login time
        $currentLoginTime = date("Y-m-d H:i:s");
        $queryUpdate = "UPDATE login SET lastlogin='$currentLoginTime' WHERE username='$user'";
        mysqli_query($con, $queryUpdate);
    }
} else {
    header("Location: Login.html?error=no_session");
    exit();
}
?>
