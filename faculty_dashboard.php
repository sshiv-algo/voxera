<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_faculty'])) {
    header("Location: faculty_login.php");
    exit();
}

$faculty_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

$stmt = $conn->prepare("SELECT * FROM complaints WHERE assigned_to = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $faculty_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Voxera - Faculty Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .dashboard-container {
            display: flex;
            padding: 40px;
            gap: 30px;
            align-items: flex-start;
        }

        .complaint-feed {
            flex: 2;
        }

        .sidebar {
            flex: 1;
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar h2 {
            font-size: 1.6rem;
            margin-bottom: 10px;
            color: #1d4ed8;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
        }

        .card img {
            width: 280px;
            height: 140px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 10px;
        }

        .status-badge {
            padding: 4px 10px;
            font-size: 0.85rem;
            border-radius: 20px;
            display: inline-block;
            margin-top: 8px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .status-inprogress {
            background-color: #fef9c3;
            color: #92400e;
        }

        .status-resolved {
            background-color: #dcfce7;
            color: #15803d;
        }

        select, button {
            padding: 8px 12px;
            font-size: 0.95rem;
            border-radius: 6px;
            margin-top: 10px;
        }

        button {
            background-color: #1d4ed8;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #2563eb;
        }

        .logout-link {
            display: inline-block;
            margin-top: 20px;
            color: #1d4ed8;
            text-decoration: none;
            font-weight: bold;
        }

        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Complaint Feed -->
        <div class="complaint-feed">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card">
                    <h3><?php echo htmlspecialchars($row['type']); ?></h3>
                    <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>

                    <?php if (!empty($row['image'])): ?>
                        <img src="<?php echo $row['image']; ?>" alt="Complaint Image">
                    <?php endif; ?>

                    <?php
                        $statusClass = match($row['status']) {
                            'Pending' => 'status-pending',
                            'In Progress' => 'status-inprogress',
                            'Resolved' => 'status-resolved',
                            default => ''
                        };
                    ?>
                    <div class="status-badge <?= $statusClass ?>">
                        <?= htmlspecialchars($row['status']) ?>
                    </div>

                    <form method="POST" action="faculty_update_status.php">
                        <input type="hidden" name="complaint_id" value="<?= $row['id'] ?>">
                        <label for="status">Update Status:</label><br>
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

        <!-- Sidebar -->
        <div class="sidebar">
            <h2>👨‍🏫 <?php echo htmlspecialchars($name); ?></h2>
            <p>Faculty Panel</p>
            <a href="logout.php" class="logout-link">🚪 Logout</a>
            <div style="margin-top: 40px; font-size: 32px; font-weight: 800; letter-spacing: 1px; font-family: 'Inter', serif;">
    VOXERA.
</div>
        </div>
    </div>
</body>
</html>

