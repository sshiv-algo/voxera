<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complaint_id'])) {
    $cid = $_POST['complaint_id'];
    $is_like = isset($_POST['like']) ? 1 : 0;

    // Check if user already liked/disliked this complaint
    $check = $conn->prepare("SELECT * FROM likes WHERE user_id = ? AND complaint_id = ?");
    $check->bind_param("ii", $user_id, $cid);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO likes (complaint_id, user_id, is_like) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $cid, $user_id, $is_like);
        $stmt->execute();
    }
}

header("Location: view_feed.php");
exit();
