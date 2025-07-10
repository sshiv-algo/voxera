<?php
session_start();
include 'db.php';

// Allow only logged in faculty
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_faculty'])) {
    header("Location: faculty_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaint_id = $_POST['complaint_id'];
    $new_status = $_POST['status'];

    // Optional: Ensure the complaint is assigned to this faculty only
    $faculty_id = $_SESSION['user_id'];
    $check = $conn->prepare("SELECT * FROM complaints WHERE id = ? AND assigned_to = ?");
    $check->bind_param("ii", $complaint_id, $faculty_id);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows === 1) {
        // Proceed with update
        $update = $conn->prepare("UPDATE complaints SET status = ? WHERE id = ?");
        $update->bind_param("si", $new_status, $complaint_id);
        $update->execute();
    }
}

header("Location: faculty_dashboard.php");
exit();
