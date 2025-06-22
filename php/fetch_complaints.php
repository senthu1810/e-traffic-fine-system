<?php
include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

$result = $conn->query("
  SELECT c.*, f.reason, f.amount, v.vehicle_no
  FROM complaints c
  JOIN fines f ON c.fine_id = f.id
  JOIN vehicles v ON f.vehicle_id = v.id
  WHERE c.user_id = $user_id
  ORDER BY c.created_at DESC
");

$output = '';

if ($result->num_rows === 0) {
  $output = "<p>No complaints submitted yet.</p>";
} else {
  while ($row = $result->fetch_assoc()) {
    $statusClass = $row['status'] === 'Resolved' ? 'success' : 'warning';
    $output .= "<div class='border rounded p-3 mb-3'>
      <p>
        <strong>Vehicle:</strong> " . htmlspecialchars($row['vehicle_no']) . "<br>
        <strong>Reason:</strong> " . htmlspecialchars($row['reason']) . " | Rs." . number_format($row['amount'], 2) . "<br>
        <strong>Complaint:</strong> " . htmlspecialchars($row['complaint_text']) . "<br>
        <strong>Status:</strong> <span class='badge bg-{$statusClass}'>{$row['status']}</span><br>";

    if (!empty($row['evidence_file'])) {
      $file = 'uploads/' . htmlspecialchars($row['evidence_file']);
      $output .= "<a href='$file' target='_blank' class='btn btn-sm btn-info mt-2'>View Evidence</a>";
    }

    $output .= "</p></div>";
  }
}

echo $output;
?>
