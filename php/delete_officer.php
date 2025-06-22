<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: ../admin_login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['officer_id'])) {
    $officer_id = (int)$_POST['officer_id'];

    // Delete police officer
    $stmt = $conn->prepare("DELETE FROM police_officers WHERE id = ?");
    $stmt->bind_param("i", $officer_id);
    $stmt->execute();
    $stmt->close();

    // fines.officer_id will become NULL due to ON DELETE SET NULL

    header("Location: ../admin_dashboard.php#police");
    exit();
}
?>
