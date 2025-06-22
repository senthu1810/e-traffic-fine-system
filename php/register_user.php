<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $nic = mysqli_real_escape_string($conn, $_POST['nic']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password_raw = $_POST['password'];
$password = password_hash($password_raw, PASSWORD_DEFAULT);

    $security_q1 = mysqli_real_escape_string($conn, $_POST['security_question']);
    $security_a1 = mysqli_real_escape_string($conn, $_POST['security_answer']);

    // Check if user already exists
    $checkQuery = "SELECT * FROM users WHERE email = '$email'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        header("Location: ../index.html?register_error=Email+is+already+in+use");
        exit();
    }
    

    // Insert into DB
    $insertQuery = "INSERT INTO users (first_name, last_name, nic, email, phone, password, security_q1, security_a1)
                    VALUES ('$first_name', '$last_name', '$nic', '$email', '$phone', '$password', '$security_q1', '$security_a1')";

    if (mysqli_query($conn, $insertQuery)) {
        header("Location: ../index.html?success=1");
    } else {
        header("Location: ../index.html?error=Registration+failed");
    }
}
?>
