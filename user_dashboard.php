<?php
session_start();

// Prevent browser from caching
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Check if user is logged in and has role 'user'
if (!isset($_SESSION['email']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: index.html");
    exit();
}

include 'php/config.php';

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>User Dashboard - E-Traffic Fine System</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      overflow-x: hidden;
      background-color: #f8f9fa;
    }

    .container {
      margin-top: 40px;
      margin-bottom: 40px;
      background: #fff;
      color: #343a40;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      padding: 30px;
    }

    h2, h3 {
      color: #4b2995;
      font-weight: 700;
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(75, 41, 149, 0.2);
    }

    .btn-pay {
      background: #28a745;
      color: #fff;
      font-weight: 600;
    }
    .btn-pay:hover {
      background: #1e7e34;
      color: #fff;
    }

    .form-control:focus {
      border-color: #764ba2;
      box-shadow: 0 0 5px rgba(118, 75, 162, 0.7);
    }

    ::placeholder {
      color: #a09cbc;
      opacity: 1;
    }

    .nav-link:hover {
      background-color: #495057;
      border-radius: 5px;
    }

    #complaint-history .card {
      margin-bottom: 20px;
    }

    .accordion-button {
      font-weight: 600;
      color: #343a40;
    }

    .accordion-button:not(.collapsed) {
      background-color: #f1f1f1;
      color: #4b2995;
    }
  </style>
</head>

<body>
<div class="d-flex">
  <!-- Sidebar -->
  <div class="bg-dark text-white p-3 vh-100 position-fixed" style="width: 250px;">
    <h4 class="mb-4">E-Challan</h4>
    <ul class="nav flex-column">
      <li class="nav-item mb-2"><a class="nav-link text-white" href="#">Dashboard</a></li>
      <li class="nav-item mb-2"><a class="nav-link text-white" href="#pending">Pending Fines</a></li>
      <li class="nav-item mb-2"><a class="nav-link text-white" href="#paid">Paid Fines</a></li>
      <li class="nav-item mb-2"><a class="nav-link text-white" href="#complaint-history">Complaint History</a></li>
    </ul>
    <div class="mt-auto">
      <button class="btn btn-danger w-100 mt-4" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
    </div>
  </div>

  <!-- Main Content -->
  <div class="container-fluid" style="margin-left: 250px;">
    <div class="py-4">

      <?php
      if (isset($_SESSION['msg'])) {
          $msg = htmlspecialchars($_SESSION['msg']);
          unset($_SESSION['msg']);
          $alertClass = 'alert-info';
          if (stripos($msg, 'success') !== false) $alertClass = 'alert-success';
          elseif (stripos($msg, 'error') !== false || stripos($msg, 'fail') !== false) $alertClass = 'alert-danger';
          elseif (stripos($msg, 'already paid') !== false) $alertClass = 'alert-warning';

          echo "<div class='alert $alertClass alert-dismissible fade show' role='alert' id='session-alert'>
                  $msg
                  <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
                </div>";
      }
      ?>

      <h2 class="mb-4">Welcome, User</h2>

      <!-- Pending Fines -->
      <h3 class="mb-3" id="pending">Pending Fines</h3>
      <div class="row g-3">
        <?php
        $sql = "SELECT f.id, v.vehicle_no, f.reason, f.amount, f.date_issued , f.officer_id FROM fines f 
        JOIN vehicles v ON f.vehicle_id = v.id
        WHERE f.user_id = $user_id AND f.status = 'pending'";
        $result = $conn->query($sql);
        if ($result->num_rows === 0) echo '<p class="text-muted">No pending fines.</p>';
        while ($row = $result->fetch_assoc()) {
          echo "<div class='col-md-6'><div class='card p-3'>
                  <p><strong>Vehicle:</strong> " . htmlspecialchars($row['vehicle_no']) . "</p>
                  <p><strong>Reason:</strong> " . htmlspecialchars($row['reason']) . "</p>
                  <p><strong>Amount:</strong> Rs." . number_format($row['amount'], 2) . "</p>
                  <p><strong>Issued On:</strong> " . date('d M Y', strtotime($row['date_issued'])) . "</p>
                  <form action='php/pay_fine.php' method='POST' class='d-flex justify-content-end gap-2'>
                    <input type='hidden' name='fine_id' value='" . intval($row['id']) . "'>
                    <button type='submit' class='btn btn-pay'>Pay</button>
                    <button type='button' class='btn btn-outline-warning' onclick='openComplaintModal(" . intval($row['id']) . ")'>Complaint</button>
                  </form>
                </div></div>";
        }
        ?>
      </div>

      <!-- Paid Fines -->
      <h3 class="mt-5 mb-3" id="paid">Paid Fines</h3>
      <div class="row g-3">
        <?php
        $sql = "SELECT v.vehicle_no, f.reason, f.amount, f.date_issued FROM fines f 
        JOIN vehicles v ON f.vehicle_id = v.id
        WHERE f.user_id = $user_id AND f.status = 'paid'";
        $result = $conn->query($sql);
        if ($result->num_rows === 0) echo '<p class="text-muted">No paid fines yet.</p>';
        while ($row = $result->fetch_assoc()) {
          echo "<div class='col-md-6'><div class='card p-3 bg-light'>
                  <p><strong>Vehicle:</strong> " . htmlspecialchars($row['vehicle_no']) . "</p>
                  <p><strong>Reason:</strong> " . htmlspecialchars($row['reason']) . "</p>
                  <p><strong>Amount:</strong> Rs." . number_format($row['amount'], 2) . "</p>
                  <p><strong>Issued On:</strong> " . date('d M Y', strtotime($row['date_issued'])) . "</p>
                </div></div>";
        }
        ?>
      </div>

      <!-- Complaint History -->
      <h3 class="mt-5 mb-3" id="complaint-history">📨 Complaint History</h3>
      <div class="card shadow mb-5">
        <div class="card-body">
          <div class="accordion" id="complaintAccordion">
            <?php
            $result = $conn->query("
              SELECT c.*, f.reason, f.amount, v.vehicle_no
              FROM complaints c
              JOIN fines f ON c.fine_id = f.id
              JOIN vehicles v ON f.vehicle_id = v.id
              WHERE c.user_id = $user_id
              ORDER BY c.id DESC
            ");
            if ($result->num_rows === 0) {
              echo "<p class='text-muted'>No complaints submitted yet.</p>";
            } else {
              $count = 0;
              while ($row = $result->fetch_assoc()) {
                $statusClass = $row['status'] === 'Resolved' ? 'success' : 'warning';
                $collapseId = "collapse$count";
                $headingId = "heading$count";
                echo "<div class='accordion-item mb-2'>
                        <h2 class='accordion-header' id='$headingId'>
                          <button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#$collapseId' aria-expanded='false' aria-controls='$collapseId'>
                            Vehicle: " . htmlspecialchars($row['vehicle_no']) . " | Rs. " . number_format($row['amount'], 2) . " | 
                            <span class='badge bg-$statusClass ms-2'>{$row['status']}</span>
                          </button>
                        </h2>
                        <div id='$collapseId' class='accordion-collapse collapse' aria-labelledby='$headingId' data-bs-parent='#complaintAccordion'>
                          <div class='accordion-body'>
                            <p><strong>Reason:</strong> " . htmlspecialchars($row['reason']) . "</p>
                            <p><strong>Complaint:</strong> " . htmlspecialchars($row['complaint_text']) . "</p>";
                if (!empty($row['evidence_file'])) {
                  $file = 'uploads/' . htmlspecialchars($row['evidence_file']);
                  echo  '<a href="' . $file . '" class="btn btn-sm btn-outline-primary" target="_blank">View Evidence</a>';
                }
                echo "</div></div></div>";
                $count++;
              }
            }
            ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Confirm Logout</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">Are you sure you want to logout?</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="php/logout.php" class="btn btn-danger">Yes, Logout</a>
      </div>
    </div>
  </div>
</div>

<!-- Complaint Modal -->
<div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <form action="php/make_complaint.php" method="POST" enctype="multipart/form-data">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title">Submit a Complaint</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="fine_id" id="complaintFineId">
          <div class="mb-3">
            <label for="complaintMessage" class="form-label">Complaint Message</label>
            <textarea name="message" id="complaintMessage" class="form-control" rows="4" required></textarea>
          </div>
          <div class="mb-3">
            <label for="evidence" class="form-label">Upload Photo/Video (Optional)</label>
            <input type="file" name="evidence" id="evidence" class="form-control" accept="image/*,video/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Submit Complaint</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openComplaintModal(fineId) {
  document.getElementById('complaintFineId').value = fineId;
  var modal = new bootstrap.Modal(document.getElementById('complaintModal'));
  modal.show();
}
</script>
<script>
  window.onpageshow = function(event) {
    if (event.persisted) {
      window.location.reload();
    }
  };
</script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
