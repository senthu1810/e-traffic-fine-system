<?php
session_start();
include 'php/config.php';

// Role check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'police') {
    header("Location: index.html");
    exit();
}

// Fetch violations for dropdown
$violations_result = $conn->query("SELECT id, violation_name, amount FROM violations ORDER BY violation_name ASC");
$violations = [];
if ($violations_result && $violations_result->num_rows > 0) {
    while ($row = $violations_result->fetch_assoc()) {
        $violations[] = $row;
    }
}
// Fetch vehicle numbers for datalist suggestions
$vehicle_numbers_result = $conn->query("SELECT vehicle_no FROM vehicles ORDER BY vehicle_no ASC");
$vehicle_numbers = [];
if ($vehicle_numbers_result && $vehicle_numbers_result->num_rows > 0) {
    while ($row = $vehicle_numbers_result->fetch_assoc()) {
        $vehicle_numbers[] = $row['vehicle_no'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Police Dashboard-E-Traffic Fine System</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <span class="navbar-brand">🚓 Police Dashboard</span>
      <div class="d-flex">
        <a href="php/logout.php" class="btn btn-danger">Logout</a>
      </div>
    </div>
  </nav>

  <!-- Container -->
  <div class="container mt-5">
    <!-- Search Form -->
    <div class="card shadow-sm">
      <div class="card-header bg-info text-white">
        Search User or Vehicle
      </div>
      <div class="card-body">
        <form method="POST" action="">
          <div class="mb-3">
          <input type="text" name="search" class="form-control" placeholder="Enter the Vehicle No" list="vehicleSuggestions" required />
          <datalist id="vehicleSuggestions">
          <?php foreach ($vehicle_numbers as $v_no): ?>
         <option value="<?= htmlspecialchars($v_no) ?>"></option>
         <?php endforeach; ?>
        </datalist>

          </div>
          <button type="submit" name="find" class="btn btn-primary">Search</button>
        </form>
      </div>
    </div>

    <!-- Result and Fine Form -->
    <?php
    if (isset($_POST['find'])) {
        $search = $conn->real_escape_string($_POST['search']);

        $sql = "SELECT u.id as user_id, u.first_name, u.last_name, v.vehicle_no, v.id as vehicle_id
                FROM users u
                JOIN vehicles v ON u.id = v.user_id
                WHERE u.nic='$search' OR v.vehicle_no='$search'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            <div class="card mt-4 shadow-sm">
              <div class="card-header bg-success text-white">
                User & Vehicle Information
              </div>
              <div class="card-body">
                <p><strong>User:</strong> <?= htmlspecialchars($row['first_name']) ?> <?= htmlspecialchars($row['last_name']) ?></p>
                <p><strong>Vehicle No:</strong> <?= htmlspecialchars($row['vehicle_no']) ?></p>
              </div>
            </div>

            <div class="card mt-3 shadow-sm">
              <div class="card-header bg-warning">
                Issue Fine
              </div>
              <div class="card-body">
                <form action="php/issue_fine.php" method="POST">
                  <input type="hidden" name="user_id" value="<?= htmlspecialchars($row['user_id']) ?>" />
                  <input type="hidden" name="vehicle_id" value="<?= htmlspecialchars($row['vehicle_id']) ?>" />
                  <input type="hidden" name="officer_id" value="<?= htmlspecialchars($_SESSION['user_id']) ?>" />

                  <div class="mb-3">
                    <label for="violation_id" class="form-label">Violation Details</label>
                    <select name="violation_id" id="violation_id" class="form-select" required>
                      <option value="" selected disabled>Select Violation</option>
                      <?php foreach ($violations as $violation): ?>
                        <option value="<?= $violation['id'] ?>" data-amount="<?= $violation['amount'] ?>">
                          <?= htmlspecialchars($violation['violation_name']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label for="amount" class="form-label">Fine Amount (Rs.)</label>
                    <input type="number" name="amount" id="amount" class="form-control" placeholder="Fine amount" readonly required />
                  </div>

                  <button type="submit" class="btn btn-danger">Issue Fine</button>
                </form>
              </div>
            </div>
            <?php
        } else {
            echo "<div class='alert alert-danger mt-4'>No  vehicles found for the given vehicle number.</div>";
        }
    }
    ?>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
  window.onpageshow = function(event) {
    if (event.persisted) {
      window.location.reload();
    }
  };
</script>

  <script>
    document.getElementById('violation_id')?.addEventListener('change', function () {
      const selectedOption = this.options[this.selectedIndex];
      const amount = selectedOption.getAttribute('data-amount');
      document.getElementById('amount').value = amount || '';
    });
  </script>

  <?php if (isset($_GET['fine_issued']) && $_GET['fine_issued'] == 1): ?>
  <script>
    alert("Fine is issued!");
    window.location.href = "police_dashboard.php";
  </script>
  <?php endif; ?>

</body>
</html>
