/*not in use*/

<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM complaints WHERE is_sensitive = 0 ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Voxera - Complaint Feed</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #111;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        .feed-wrapper {
            padding: 40px;
            max-width: 800px;
            margin: auto;
        }

        .card {
            background: #222;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
        }

        .card h2 {
            color: #fcd440;
            margin-bottom: 20px;
        }

        .card p {
            margin-bottom: 10px;
        }

        .card img {
            max-width: 100%;
            border-radius: 10px;
            margin-top: 10px;
        }

        a {
            color: #fcd440;
            text-decoration: none;
        }

        a:hover {
            color: #fff;
        }
    </style>
</head>
<body>

<div class="feed-wrapper">
    <div class="card">
        <h2>📰 Complaint Feed</h2>
        <a href="student_home.php">⬅ Back to Dashboard</a>
    </div>

    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card">
            <p><strong>Type:</strong> <?php echo htmlspecialchars(ucfirst($row['type'])); ?></p>
            <p><strong>By:</strong> <?php echo htmlspecialchars($row['name']); ?> (<?php echo htmlspecialchars($row['student_id']); ?>)</p>
            <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>

            <?php if (!empty($row['image'])): ?>
                <img src="<?php echo $row['image']; ?>" alt="Complaint Image">
            <?php endif; ?>

            <p><strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?></p>
            <p>👍 <?php echo $row['likes'] ?? 0; ?> | 👎 <?php echo $row['dislikes'] ?? 0; ?></p>
        </div>
    <?php endwhile; ?>
</div>

</body>
</html>
