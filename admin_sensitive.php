<?php
session_start();
include 'db.php';

// Allow only logged in admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM complaints WHERE is_sensitive = 1 ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Voxera - Admin Sensitive Complaints</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: url('bg.jpg') no-repeat center center fixed;
            background-size: cover;
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

        .content {
            margin-top: 100px;
            padding: 2rem;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        .card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(8px);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .card img {
            max-width: 100%;
            border-radius: 8px;
            margin-top: 10px;
            max-height: 250px;
            object-fit: cover;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 8px;
        }

        .status-pending {
            background: #fee2e2;
            color: #b91c1c;
        }

        .status-inprogress {
            background: #fef3c7;
            color: #92400e;
        }

        .status-resolved {
            background: #dcfce7;
            color: #166534;
        }

        select, button {
            padding: 8px;
            margin-top: 8px;
            font-size: 1rem;
        }

        form {
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div><strong style="color:#1e3a8a;">Voxera Admin</strong></div>
        <div>
            <a href="admin_dashboard.php">📄 All Complaints</a>
            <a href="admin_sensitive.php">⚠️ Sensitive Complaints</a>
            <a href="logout.php">🚪 Logout</a>
        </div>
    </div>

    <div class="content">
        <h2 style="margin-bottom: 20px;">⚠️ Sensitive Complaints</h2>

        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <p><strong>Type:</strong> <?= htmlspecialchars($row['type']) ?></p>
                <p><strong>By:</strong> <?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['student_id']) ?>)</p>
                <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>

                <?php if ($row['image']): ?>
                    <img src="<?= $row['image'] ?>" alt="Proof Image">
                <?php endif; ?>

                <p>📅 <em>Submitted: <?= $row['created_at'] ?></em></p>

                <span class="status-badge 
                    <?= $row['status'] === 'Pending' ? 'status-pending' : 
                         ($row['status'] === 'In Progress' ? 'status-inprogress' : 'status-resolved') ?>">
                    <?= $row['status'] ?>
                </span>

                <form method="POST" action="admin_update_status.php">
                    <input type="hidden" name="complaint_id" value="<?= $row['id'] ?>">
                    <label>Update Status:</label>
                    <select name="status">
                        <option value="Pending" <?= $row['status'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="In Progress" <?= $row['status'] === 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="Resolved" <?= $row['status'] === 'Resolved' ? 'selected' : '' ?>>Resolved</option>
                    </select>
                    <button type="submit">Update</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>


