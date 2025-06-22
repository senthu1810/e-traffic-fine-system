<?php
// Extend session lifetime (same as dashboard)
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600);

session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Use prepared statements to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['admin'] = $username;
        $_SESSION['last_activity'] = time();
        header("Location: ../admin_dashboard.php");
        exit();
    } else {
        // Show alert and reload the login page
        echo "<script>
                alert('Invalid credentials.');
                window.location.href = '../admin_login.html';
              </script>";
        exit();
    }
}
?>
