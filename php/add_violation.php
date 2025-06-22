<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['violation_name']);
    $amount = floatval($_POST['violation_amount']);

    if ($name && $amount > 0) {
        $stmt = $conn->prepare("INSERT INTO violations (violation_name, amount) VALUES (?, ?)");
        $stmt->bind_param("sd", $name, $amount);
        $stmt->execute();
    }
}

header("Location: ../admin_dashboard.php#violations");
exit();
