<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaintId = intval($_POST['complaint_id'] ?? 0);
    $fineId = intval($_POST['fine_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    // Mark complaint as resolved
    $conn->query("UPDATE complaints SET status = 'resolved' WHERE id = $complaintId");

    if ($action === 'delete' && $fineId > 0) {
        // First remove foreign key reference
        $conn->query("UPDATE complaints SET fine_id = NULL WHERE id = $complaintId");
        // Now delete fine
        $conn->query("DELETE FROM fines WHERE id = $fineId");
    }

    header("Location: ../admin_dashboard.php#user_complaint");
    exit();
} else {
    echo "Invalid request.";
}
?>
