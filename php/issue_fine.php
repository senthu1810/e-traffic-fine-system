<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $officer_id = $_POST['officer_id'];
    $violation_id = $_POST['violation_id'];

    if (!$user_id || !$vehicle_id || !$officer_id || !$violation_id) {
        header("Location: ../police_dashboard.php?fine_issued=0");
        exit();
    }

    // Fetch violation details
    $stmt = $conn->prepare("SELECT violation_name, amount FROM violations WHERE id = ?");
    $stmt->bind_param("i", $violation_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        header("Location: ../police_dashboard.php?fine_issued=0");
        exit();
    }

    $violation = $result->fetch_assoc();
    $reason = $violation['violation_name'];
    $amount = $violation['amount'];

    // Insert fine record
    $stmt = $conn->prepare("INSERT INTO fines (user_id, vehicle_id, officer_id, violation_id, reason, amount, date_issued) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiiisd", $user_id, $vehicle_id, $officer_id, $violation_id, $reason, $amount);

    if ($stmt->execute()) {
        header("Location: ../police_dashboard.php?fine_issued=1");
        exit();
    } else {
        header("Location: ../police_dashboard.php?fine_issued=0");
        exit();
    }

    $stmt->close();
} else {
    header("Location: ../police_dashboard.php");
    exit();
}
?>
