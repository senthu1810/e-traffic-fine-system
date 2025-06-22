<?php
session_start();

// Unset all session variables
$_SESSION = array();

// Optionally regenerate session ID
session_regenerate_id(true);

// Set logout message flag in cookie (short lifespan)
setcookie("logout_success", "1", time() + 5, "/");

// Destroy the session
session_destroy();

// Prevent back button access
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

// Redirect to login/home page
header("Location: ../index.html");
exit();
