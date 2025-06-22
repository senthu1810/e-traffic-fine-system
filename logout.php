<?php
session_start();
session_unset();
session_destroy();

// Invalidate the cache
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Redirect to login
header("Location: login.php");
exit();
