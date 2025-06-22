<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fine_id'])) {
    $fine_id = intval($_POST['fine_id']);

    // First delete any payments associated with this fine (if any)
    $conn->query("DELETE FROM payments WHERE fine_id = $fine_id");

    // Then delete the fine itself
    $conn->query("DELETE FROM fines WHERE id = $fine_id");
}

// Redirect back to admin dashboard
header("Location: ../admin_dashboard.php");
exit();
