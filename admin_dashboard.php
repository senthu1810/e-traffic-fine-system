<?php
session_start();
include 'php/config.php';

// Redirect if admin not logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.html");
    exit();
}

// Prevent back navigation after logout
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard-E-Traffic Fine System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/admin_dashboard.css">
  <style>
    .sidebar{ width:250px; position:fixed; top:0; bottom:0; background:#0d6efd; color:#fff; padding:20px;}
    .sidebar a{ color:#fff; display:block; padding:10px 0; text-decoration:none;}
    .content{ margin-left:260px; padding:20px; }
    .modal-content{ border-radius:10px; border:2px solid #198754; }
    .modal-header{ background:#198754; color:#fff; }
  </style>
</head>
<body>

<div class="d-flex">
  <!-- Sidebar -->
  <div class="sidebar">
    <h4 class="text-center">E-Challan Admin</h4>
    <hr class="bg-light">
    <a href="#users">👥 Users</a>
    <a href="#police">👮 Police Officers</a>
    <a href="#pendingFines">🚨 Pending Fines</a>
    <a href="#payments">💰 Payments Received</a>
    <a href="#assign">🚗 Assign Vehicle</a>
    <a href="#vehicles">📋 All Vehicles</a>
    <a href="#violations">🚫 Manage Violations</a>
    <a href="#user_complaint">📢 User Complaints</a>
    <hr class="bg-light">
    <a href="php/logout_admin.php" class="btn btn-danger w-100 mt-3">Logout</a>
  </div>

  <!-- Main Content -->
  <div class="content">

    <!-- Users -->
    <div class="card mb-4" id="users">
      <div class="card-header bg-info text-white">👥 Users</div>
      <div class="card-body">
       
      <!-- Search box -->
        <div class="input-group mb-3">
         <input type="text" id="userSearch" class="form-control" placeholder="Enter NIC NO" aria-label="Search Users">
         <button class="btn btn-outline-primary" type="button" onclick="searchAndHighlight('userSearch', 'user-row', '', ['div'])">Search</button>
      </div>

        <?php
        $result = $conn->query("SELECT * FROM users");
        while ($row = $result->fetch_assoc()) {
          echo "
          <div class='user-row d-flex justify-content-between align-items-center mb-3' id='user-{$row['id']}'>
            <div>
              <strong>{$row['first_name']} {$row['last_name']}</strong> | Email: {$row['email']} | NIC: {$row['nic']}
            </div>
            <form method='POST' action='php/delete_user.php' onsubmit=\"return confirm('Are you sure you want to delete this user?')\">
              <input type='hidden' name='user_id' value='{$row['id']}'>
              <button type='submit' class='btn btn-sm btn-danger'>Remove</button>
            </form>
          </div>";
        }
        ?>
      </div>
    </div>

    <!-- Police Officers -->
    <div class="card mb-4" id="police">
      <div class="card-header bg-success text-white">👮 Police Officers</div>
      <div class="card-body">
       
      <!-- Search box -->
      <div class="input-group mb-3">
      <input type="text" id="officerSearch" class="form-control" placeholder="Enter Job ID" aria-label="Search Officers">
      <button class="btn btn-outline-success" type="button" onclick="searchAndHighlight('officerSearch', 'officer-row', '', ['div'])">Search</button>
     </div>

        <?php
        $result = $conn->query("SELECT * FROM police_officers");
        while ($row = $result->fetch_assoc()) {
          echo "
          <div class='officer-row d-flex justify-content-between align-items-center mb-3' id='officer-{$row['id']}'>
            <div>
              <strong>{$row['first_name']} {$row['last_name']}</strong> | Email: {$row['email']} | Job ID: {$row['job_id']}
            </div>
            <form method='POST' action='php/delete_officer.php' onsubmit=\"return confirm('Are you sure you want to delete this officer?')\">
              <input type='hidden' name='officer_id' value='{$row['id']}'>
              <button type='submit' class='btn btn-sm btn-danger'>Remove</button>
            </form>
          </div>";
        }
        ?>
      </div>
    </div>

<!-- Pending Fines -->
<div class="card mb-4" id="pendingFines">
  <div class="card-header bg-danger text-white">🚨 Pending Fines</div>
  <div class="card-body">

    <!-- Search box -->
    <div class="input-group mb-3">
      <input type="text" id="pendingFineSearch" class="form-control" placeholder="Enter Vehicle No" aria-label="Search Pending Fines">
      <button class="btn btn-outline-danger" type="button" onclick="searchAndHighlight('pendingFineSearch', 'pending-fine-row', '', ['p'])">Search</button>
    </div>

    <?php
    $result = $conn->query("
      SELECT 
        f.id AS fine_id,
        f.reason,
        f.amount,
        f.date_issued,
        f.status,
        v.vehicle_no,
        u.first_name AS user_fname,
        u.last_name AS user_lname,
        po.first_name AS officer_fname,
        po.last_name AS officer_lname
      FROM fines f
      JOIN vehicles v ON f.vehicle_id = v.id
      JOIN users u ON f.user_id = u.id
      JOIN police_officers po ON f.officer_id = po.id
      WHERE f.status = 'pending'
    ");

    while ($row = $result->fetch_assoc()) {
      echo "<div class='pending-fine-row d-flex justify-content-between align-items-center mb-3' id='pending-fine-{$row['fine_id']}'>
        <div>
          <p class='mb-0'>
            <strong>Vehicle:</strong> " . htmlspecialchars($row['vehicle_no']) . " |
            <strong>Reason:</strong> " . htmlspecialchars($row['reason']) . " |
            <strong>Amount:</strong> Rs." . number_format($row['amount'], 2) . " |
            <strong>Date:</strong> " . htmlspecialchars($row['date_issued']) . "<br>
            <strong>Issued To:</strong> " . htmlspecialchars($row['user_fname']) . " " . htmlspecialchars($row['user_lname']) . " |
            <strong>Issued By:</strong> Officer " . htmlspecialchars($row['officer_fname']) . " " . htmlspecialchars($row['officer_lname']) . "
          </p>
        </div>
        <form method='POST' action='php/delete_fine.php' onsubmit=\"return confirm('Are you sure you want to delete this fine?')\">
          <input type='hidden' name='fine_id' value='{$row['fine_id']}'>
          <button type='submit' class='btn btn-sm btn-danger'>Remove</button>
        </form>
      </div>";
    }
    ?>
  </div>
</div>


    <!-- Payments -->
    <div class="card mb-4" id="payments">
      <div class="card-header bg-warning text-dark">💰 Payments Received</div>
      <div class="card-body">
       
      <!-- Search box -->
      <div class="input-group mb-3">
      <input type="text" id="paymentSearch" class="form-control" placeholder="Enter Vehicle No" aria-label="Search Payments">
      <button class="btn btn-outline-warning" type="button" onclick="searchAndHighlight('paymentSearch', 'payment-row', '', ['p'])">Search</button>
     </div>

        <?php
        $result = $conn->query("
          SELECT 
            v.vehicle_no,
            f.reason, 
            f.amount, 
            p.date_paid,
            u.first_name AS user_fname,
            u.last_name AS user_lname,
            po.first_name AS officer_fname,
            po.last_name AS officer_lname,
            f.id as fine_id
          FROM payments p
          JOIN fines f ON p.fine_id = f.id
          JOIN vehicles v ON f.vehicle_id = v.id
          JOIN users u ON f.user_id = u.id
          JOIN police_officers po ON f.officer_id = po.id
        ");
        while ($row = $result->fetch_assoc()) {
          echo "<div class='payment-row mb-3' id='payment-{$row['fine_id']}'>
            <p>
              <strong>Vehicle:</strong> " . htmlspecialchars($row['vehicle_no']) . " | 
              <strong>Reason:</strong> " . htmlspecialchars($row['reason']) . " | 
              <strong>Amount:</strong> Rs." . number_format($row['amount'], 2) . " | 
              <strong>Date:</strong> " . htmlspecialchars($row['date_paid']) . "<br>
              <strong>Issued To:</strong> " . htmlspecialchars($row['user_fname']) . " " . htmlspecialchars($row['user_lname']) . " | 
              <strong>Issued By:</strong> Officer " . htmlspecialchars($row['officer_fname']) . " " . htmlspecialchars($row['officer_lname']) . "
            </p><hr>
          </div>";
        }
        ?>
      </div>
    </div>

    <!-- Assign Vehicle -->
    <div class="card mb-4" id="assign">
  <div class="card-header bg-primary text-white">🚗 Assign Vehicle to User</div>
  <div class="card-body">
    <form action="php/update_vehicle.php" method="POST" class="row g-3" onsubmit="return validateAssignForm();">
      
      <!-- NIC Search Box -->
      <div class="col-md-6 position-relative">
        <input type="text" id="nicSearch" placeholder="Enter NIC No" class="form-control" autocomplete="off" required>
        <div id="nicSuggestions" class="list-group position-absolute w-100 z-3" style="max-height: 200px; overflow-y: auto;"></div>
        <input type="hidden" name="user_id" id="userIdHidden">
      </div>

      <!-- Vehicle Number Field -->
      <div class="col-md-4">
  <input type="text" name="vehicle_no" placeholder="e.g., NP BOD8225"
    class="form-control"
    pattern="^[A-Z]{2} [A-Z0-9]{4,10}$"
    title="Format must be like 'NP BOD8225' (2 uppercase letters, space, then 4–10 uppercase letters/numbers)"
    required>
</div>


      <!-- Submit Button -->
      <div class="col-md-2">
        <button type="submit" class="btn btn-success w-100">Assign</button>
      </div>
    </form>
  </div>
</div>


    <!-- Vehicles -->
    <div class="card mb-5" id="vehicles">
      <div class="card-header bg-dark text-white">📋 All Vehicles</div>
      <div class="card-body">
        
      <!-- Search box -->
      <div class="input-group mb-3">
      <input type="text" id="vehicleSearch" class="form-control" placeholder="Enter Vehicle No" aria-label="Search Vehicles">
     <button class="btn btn-outline-dark" type="button" onclick="searchAndHighlight('vehicleSearch', 'vehicle-row', '', ['div'])">Search</button>
    </div>

        <?php
        $vehicles = $conn->query("
          SELECT v.id, v.vehicle_no, u.first_name, u.last_name 
          FROM vehicles v 
          JOIN users u ON v.user_id = u.id
        ");
        while ($v = $vehicles->fetch_assoc()) {
          echo "
          <div class='vehicle-row d-flex justify-content-between align-items-center mb-3' id='vehicle-{$v['id']}'>
            <div>
              <strong>{$v['vehicle_no']}</strong> - Owner: {$v['first_name']} {$v['last_name']}
            </div>
            <form method='POST' action='php/delete_vehicle.php' onsubmit=\"return confirm('Are you sure you want to delete this vehicle?')\">
              <input type='hidden' name='vehicle_id' value='{$v['id']}'>
              <button type='submit' class='btn btn-sm btn-danger'>Remove</button>
            </form>
          </div>";
        }
        ?>
      </div>
    </div>

 
<!-- Manage Violations -->
<div class="card mb-5" id="violations">
  <div class="card-header bg-danger text-white">🚫 Manage Violations</div>
  <div class="card-body">

    <!-- Add New Violation Form -->
    <form action="php/add_violation.php" method="POST" class="row g-3 mb-4">
      <div class="col-md-6">
        <input type="text" name="violation_name" class="form-control" placeholder="Violation Name" required>
      </div>
      <div class="col-md-4">
        <input type="number" name="violation_amount" class="form-control" placeholder="Amount" step="0.01" required>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-danger w-100">Add</button>
      </div>
    </form>

    <!-- Existing Violations Table -->
    <table class="table table-bordered">
      <thead class="table-danger">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Amount</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $violations = $conn->query("SELECT * FROM violations ORDER BY id ASC");
        while ($v = $violations->fetch_assoc()) {
          echo "
          <tr>
            <td>{$v['id']}</td>
            <td>{$v['violation_name']}</td>
            <td>Rs. " . number_format($v['amount'], 2) . "</td>
            <td>
              <form method='POST' action='php/delete_violation.php' onsubmit=\"return confirm('Delete this violation?')\" style='display:inline-block;'>
                <input type='hidden' name='id' value='{$v['id']}'>
                <button type='submit' class='btn btn-sm btn-danger'>Delete</button>
              </form>
            </td>
          </tr>";
        }
        ?>
      </tbody>
    </table>

  </div>
</div>

<!-- User Complaints -->
<h5 class="mt-4">📢 User Complaints</h5>
<div class="list-group" id="user_complaint">
  <?php
  $res = $conn->query("
    SELECT c.*, u.first_name, u.last_name, f.id AS fine_id, f.reason AS fine_reason
    FROM complaints c
    JOIN users u ON c.user_id = u.id
    LEFT JOIN fines f ON f.id = c.fine_id
    ORDER BY c.date_submitted DESC
  ");
  while($r = $res->fetch_assoc()) {
    $complaintId = intval($r['id']);
    $fineId = intval($r['fine_id']);
    $isResolved = ($r['status'] === 'resolved');
    
    echo "<div class='mb-3 border p-3'>
      <p><strong>User:</strong> ".htmlspecialchars($r['first_name']." ".$r['last_name'])."<br>
         <strong>Complaint:</strong> ".htmlspecialchars($r['complaint_text'])."<br>
         <strong>Reason for Fine:</strong> ".htmlspecialchars(trim($r['fine_reason']) ?: 'N/A')."<br>
         <strong>Date Submitted:</strong> ".htmlspecialchars($r['date_submitted'])."<br>
         <strong>Status:</strong> ".($isResolved ? "<span class='text-success'>Resolved</span>" : "<span class='text-danger'>Pending</span>")."</p>";
    
    if (!empty($r['evidence_file'])) {
      echo "<a href='uploads/".htmlspecialchars($r['evidence_file'])."' target='_blank' class='btn btn-sm btn-info'>View Evidence</a> ";
    }

    if (!$isResolved) {
      echo "<button class='btn btn-sm btn-success mt-2' data-bs-toggle='modal' data-bs-target='#resolveModal'
              onclick='openResolveModal($complaintId, $fineId)'>Mark as Resolved</button>";
    }

    echo "</div>";
  }
  ?>
</div>

<!-- Resolve Complaint Modal -->
<div class="modal fade" id="resolveModal" tabindex="-1" aria-labelledby="resolveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="php/resolve_complaint.php">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="resolveModalLabel">Resolve Complaint</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="complaint_id" id="modalComplaintId">
          <input type="hidden" name="fine_id" id="modalFineId">
          <p>What action would you like to take on this complaint?</p>
        </div>
        <div class="modal-footer">
          <button type="submit" name="action" value="delete" class="btn btn-danger">Delete Fine</button>
          <button type="submit" name="action" value="keep" class="btn btn-primary">Keep Fine</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openResolveModal(complaintId, fineId) {
  document.getElementById('modalComplaintId').value = complaintId;
  document.getElementById('modalFineId').value = fineId;
}
</script>


<script>
  window.allUsers = <?php
    $result = $conn->query("SELECT id, first_name, last_name, nic FROM users");
    $usersArray = [];
    while ($row = $result->fetch_assoc()) {
      $usersArray[] = $row;
    }
    echo json_encode($usersArray);
  ?>;
</script>

<script>
  window.onpageshow = function(event) {
    if (event.persisted) {
      window.location.reload();
    }
  };
</script>



<script src="js/admin_search.js"></script>


</body>
</html>
