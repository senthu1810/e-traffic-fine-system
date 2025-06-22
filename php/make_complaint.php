<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id']; // Assuming you store user_id in session
    $fine_id = $_POST['fine_id'];
    $complaint_text = mysqli_real_escape_string($conn, $_POST['message']);
    $evidence_file = '';

    // Handle file upload
    if (!empty($_FILES['evidence']['name'])) {
        $target_dir = "../uploads/";
        $filename = basename($_FILES["evidence"]["name"]);
        $new_filename = time() . "_" . $filename;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["evidence"]["tmp_name"], $target_file)) {
            $evidence_file = $new_filename; // ✅ Save only the filename

        }
    }

    $stmt = $conn->prepare("INSERT INTO complaints (user_id, fine_id, complaint_text, evidence_file) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $fine_id, $complaint_text, $evidence_file);
    $stmt->execute();
    $stmt->close();

    header("Location: ../user_dashboard.php?complaint=success");
    exit();
} else {
    header("Location: ../user_dashboard.php");
    exit();
}
?>
