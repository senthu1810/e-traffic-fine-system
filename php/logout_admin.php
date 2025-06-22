<?php
session_start();
session_unset();
session_destroy();

// Disable caching to prevent back-button access
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Location: ../admin_login.html");
exit();
?>  
