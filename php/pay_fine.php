<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['fine_id'])) {
    $_SESSION['msg'] = "Invalid request.";
    header("Location: ../user_dashboard.php");
    exit();
}

$fine_id = intval($_POST['fine_id']);

// Check if fine exists and is still pending
$stmt = $conn->prepare("SELECT status FROM fines WHERE id = ?");
$stmt->bind_param("i", $fine_id);
$stmt->execute();
$stmt->bind_result($status);

if (!$stmt->fetch()) {
    $stmt->close();
    $_SESSION['msg'] = "Fine not found.";
    header("Location: ../user_dashboard.php");
    exit();
}
$stmt->close();

if ($status === 'paid') {
    $_SESSION['msg'] = "This fine is already paid.";
    header("Location: ../user_dashboard.php");
    exit();
}

// Begin transaction
$conn->begin_transaction();

try {
    // Update fine status
    $stmt = $conn->prepare("UPDATE fines SET status = 'paid' WHERE id = ?");
    $stmt->bind_param("i", $fine_id);
    $stmt->execute();
    $stmt->close();

    // Insert payment record
    $stmt = $conn->prepare("INSERT INTO payments (fine_id, date_paid) VALUES (?, NOW())");
    $stmt->bind_param("i", $fine_id);
    $stmt->execute();
    $stmt->close();

    // Commit transaction
    $conn->commit();

    $_SESSION['msg'] = "Fine paid successfully!";
    header("Location: ../user_dashboard.php");
    exit();
} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['msg'] = "An error occurred while processing payment.";
    header("Location: ../user_dashboard.php");
    exit();
}
?>
