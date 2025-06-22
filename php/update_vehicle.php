<?php
include 'config.php';

$user_id = $_POST['user_id'];
$vehicle_no = $_POST['vehicle_no'];

$sql = "INSERT INTO vehicles (user_id, vehicle_no) VALUES ('$user_id', '$vehicle_no')";

if ($conn->query($sql)) {
    echo "<script>
        alert('Vehicle assigned successfully.');
        window.location.href = '../admin_dashboard.php';
    </script>";
} else {
    echo "<script>
        alert('Error assigning vehicle: " . addslashes($conn->error) . "');
        window.location.href = '../admin_dashboard.php';
    </script>";
}
?>
