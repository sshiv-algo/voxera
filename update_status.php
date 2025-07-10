<?php
session_start();
include 'db.php';

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "🚫 Access Denied. Not logged in.";
    exit();
}

// Check if the user is admin
$uid = $_SESSION['user_id'];
$check = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
$check->bind_param("i", $uid);
$check->execute();
$result = $check->get_result();
$user = $result->fetch_assoc();

if (!$user || $user['is_admin'] != 1) {
    echo "🚫 Access Denied. Only admin can update status.";
    exit();
}

// Process the update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaint_id = $_POST['complaint_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $complaint_id);
    
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "❌ Failed to update complaint status.";
    }
}
?>
