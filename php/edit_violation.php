<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id']);
  $name = trim($_POST['name']);
  $amount = floatval($_POST['amount']);

  if ($name && $amount >= 0) {
    $stmt = $conn->prepare("UPDATE violations SET name = ?, amount = ? WHERE id = ?");
    $stmt->bind_param("sdi", $name, $amount, $id);
    $stmt->execute();
  }

  header("Location: ../admin_dashboard.php#violations");
  exit();
} else {
  $id = intval($_GET['id']);
  $stmt = $conn->prepare("SELECT * FROM violations WHERE id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();
  $violation = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Violation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2>Edit Violation</h2>
    <form action="edit_violation.php" method="POST" class="row g-3">
      <input type="hidden" name="id" value="<?php echo $violation['id']; ?>">
      <div class="col-md-6">
        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($violation['name']); ?>" required>
      </div>
      <div class="col-md-4">
        <input type="number" step="0.01" name="amount" class="form-control" value="<?php echo htmlspecialchars($violation['amount']); ?>" required>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Update</button>
      </div>
    </form>
  </div>
</body>
</html>
