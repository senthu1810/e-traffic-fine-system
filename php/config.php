<?php
$host = 'localhost';
$db = 'traffic_fine_system';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Connection Failed: ' . $conn->connect_error);
}
?>
