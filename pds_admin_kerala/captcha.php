<?php
session_start();

// Generate CAPTCHA on server side
function generateCaptchaString($length = 5) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $captcha = '';
    for ($i = 0; $i < $length; $i++) {
        $captcha .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $captcha;
}

// Generate new CAPTCHA and store in session
$captcha_text = generateCaptchaString();
$_SESSION['captcha'] = $captcha_text;

// Return CAPTCHA to display
echo $captcha_text;
?>
