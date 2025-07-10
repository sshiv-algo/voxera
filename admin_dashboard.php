<?php
session_start();
include 'db.php';

// Only allow admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

// Fetch all complaints
$stmt = $conn->prepare("SELECT * FROM complaints ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Voxera - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: url('images/adminwall1.jpg') no-repeat center center/cover;
        }

        .navbar {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(8px);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 2rem;
    font-weight: 600;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    flex-wrap: wrap;
    box-sizing: border-box;
}

.navbar a {
    text-decoration: none;
    color: #1e3a8a;
    margin-left: 1rem;
    white-space: nowrap;
}

.navbar div {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
}


        .container {
            padding: 6rem 2rem 2rem;
        }

        .card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            margin: 1.5rem auto;
            max-width: 700px;
            padding: 1.5rem;
        }

        .card img {
            width: 100%;
            max-height: 240px;
            object-fit: cover;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .status {
            display: inline-block;
            padding: 4px 12px;
            font-size: 0.9rem;
            border-radius: 12px;
            font-weight: bold;
        }

        .status.Pending {
            background: #fee2e2;
            color: #b91c1c;
        }

        .status['In Progress'] {
            background: #fef9c3;
            color: #b45309;
        }

        .status.Resolved {
            background: #dcfce7;
            color: #15803d;
        }

        select, button {
            padding: 6px 10px;
            margin-top: 10px;
            font-size: 1rem;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
        }

        h2 {
            color:rgb(255, 255, 255);
            text-align: center;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div><strong>Voxera Admin</strong></div>
        <div>
            <a href="admin_sensitive.php">⚠ Sensitive Complaints</a>
            <a href="logout.php">🚪 Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>📋 All Complaints</h2>

        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <p><strong>Type:</strong> <?= htmlspecialchars($row['type']) ?></p>
                <p><strong>By:</strong> <?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['student_id']) ?>)</p>
                <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>

                <?php if ($row['image']): ?>
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="Proof Image">
                <?php endif; ?>

                <p>📅 Submitted: <?= htmlspecialchars($row['created_at']) ?></p>
                <span class="status <?= $row['status'] ?>"><?= $row['status'] ?></span>

                <form method="POST" action="admin_update_status.php">
                    <input type="hidden" name="complaint_id" value="<?= $row['id'] ?>">
                    <label>Update Status:</label>
                    <select name="status">
                        <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="In Progress" <?= $row['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="Resolved" <?= $row['status'] == 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                    </select>
                    <button type="submit">Update</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
