<?php
include 'config.php';

$role = $_POST['role'];
$email = $_POST['email'];
$nic = $_POST['nic'];
$mobile = $_POST['mobile'];
$q1 = $_POST['security_q1'];
$q2 = $_POST['security_q2'];
$new_password = $_POST['new_password'];

$table = $role === 'police' ? 'police_officers' : 'users';

$sql = "UPDATE $table SET password='$new_password' 
        WHERE email='$email' AND nic='$nic' AND mobile='$mobile'
        AND security_q1='$q1' AND security_q2='$q2'";

if ($conn->query($sql) === TRUE && $conn->affected_rows > 0) {
    echo "Password reset successfully!";
} else {
    echo "Failed to reset password. Please check your details.";
}
?>
