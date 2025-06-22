<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $nic = mysqli_real_escape_string($conn, $_POST['nic']);
    $job_id = mysqli_real_escape_string($conn, $_POST['job_id']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    $security_q1 = mysqli_real_escape_string($conn, $_POST['security_q1']);
    $security_q2 = mysqli_real_escape_string($conn, $_POST['security_q2']);

    // Check if email already exists
    $query = "SELECT * FROM police_officers WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = 'Email already exists!';
        header('Location: ../register_police.html');
        exit();
    }

    // Insert new police officer into the database
    $insert_query = "INSERT INTO police_officers (first_name, last_name, nic, job_id, email, mobile, password, security_q1, security_q2)
                     VALUES ('$first_name', '$last_name', '$nic', '$job_id', '$email', '$mobile', '$password', '$security_q1', '$security_q2')";
    if (mysqli_query($conn, $insert_query)) {
        $_SESSION['success'] = 'Registration successful!';
        header('Location: ../index.html#loginModal');
        exit();
    } else {
        $_SESSION['error'] = 'Registration failed. Please try again.';
        header('Location: ../index.html');
        exit();
    }
}
?>
