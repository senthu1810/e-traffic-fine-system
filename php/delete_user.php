<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: ../admin_login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = (int)$_POST['user_id'];

    // Delete user and vehicles (vehicles have ON DELETE CASCADE)
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // fines.user_id and vehicles linked to user will be handled by ON DELETE SET NULL & CASCADE

    header("Location: ../admin_dashboard.php#users");
    exit();
}
?>
