<?php
session_start();
include 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: ../admin_login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vehicle_id'])) {
    $vehicle_id = (int)$_POST['vehicle_id'];

    // Delete vehicle
    $stmt = $conn->prepare("DELETE FROM vehicles WHERE id = ?");
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $stmt->close();

    // fines.vehicle_id will become NULL due to ON DELETE SET NULL

    header("Location: ../admin_dashboard.php#vehicles");
    exit();
}
?>
