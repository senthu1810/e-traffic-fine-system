<?php
include 'config.php';
$cid = intval($_POST['complaint_id']);
$fid = intval($_POST['fine_id']);
$action = $_POST['action'];

if ($action === 'delete' && $fid > 0) {
  $conn->query("DELETE FROM fines WHERE id = $fid");
}
$conn->query("UPDATE complaints SET status = 'Resolved' WHERE id = $cid");
echo "Complaint marked as resolved.";
