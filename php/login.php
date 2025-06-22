<?php
// Start fresh session
session_start();
session_unset();
session_destroy();
session_start(); // Restart fresh

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    $table = $role === 'police' ? 'police_officers' : 'users';

    $sql = "SELECT * FROM $table WHERE email='$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $role;
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['last_activity'] = time();
    
            if ($role === 'police') {
                header("Location: ../police_dashboard.php");
            } else {
                header("Location: ../user_dashboard.php");
            }
            exit();
        } else {
            header("Location: ../index.html?error=Incorrect+password");
            exit();
        }
    } else {
        header("Location: ../index.html?error=Account+not+found");
        exit();
    }
}
