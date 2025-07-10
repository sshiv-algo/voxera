<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch non-sensitive complaints
$stmt = $conn->prepare("SELECT * FROM complaints WHERE is_sensitive = 0 ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Voxera - Student Home</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
        }

        .main-container {
            display: flex;
            padding: 40px;
            gap: 30px;
        }

        .left-panel {
            flex: 2;
        }

        .right-panel {
            flex: 1;
            background-color: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            height: fit-content;
            text-align: center;


        }

        .right-panel h2 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
        }

        .right-panel p {
            font-size: 14px;
            margin-bottom: 25px;
            color: #333;
        }

        .right-panel a {
            display: block;
            background-color: #2f63f6;
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 12px 0;
            border-radius: 6px;
            margin-bottom: 12px;
        }

        .right-panel a.logout {
            background-color: #f1f1f1;
            color: #111;
            font-weight: 500;
        }

        h1 {
            font-size: 28px;
            margin-bottom: 25px;
            font-weight: 700;
            color: #111;
        }

        .complaint-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }

        .complaint-card h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        .complaint-card p {
            margin: 10px 0;
            color: #333;
        }

        .complaint-meta {
            color: #777;
            font-size: 14px;
        }

        .complaint-img {
            width: 280px;
            height: 140px;
            object-fit: cover;
            border-radius: 8px;
            margin: 10px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 20px;
            float: right;
            margin-left: 10px;
        }

        .status-pending {
            background-color: #ffe2e2;
            color: #d81b60;
        }

        .status-progress {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-resolved {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>

<div class="main-container">
    <!-- Complaint Feed -->
    <div class="left-panel">
        <h1>Submitted Complaints</h1>

        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="complaint-card">
                <h3>
                    <?php echo htmlspecialchars($row['description']) ?>
                    <?php
                        $status = strtolower($row['status']);
                        $badgeClass = $status === 'pending' ? 'status-pending' :
                                      ($status === 'in progress' ? 'status-progress' : 'status-resolved');
                    ?>
                    <span class="status-badge <?php echo $badgeClass; ?>">
                        <?php echo ucfirst($row['status']); ?>
                    </span>
                </h3>

                <p class="complaint-meta">
                    <?php echo htmlspecialchars($row['is_anonymous'] ? 'Anonymous' : $row['name']); ?>
                    · <?php echo date("F d, Y", strtotime($row['created_at'])); ?>
                </p>

                <?php if (!empty($row['image'])): ?>
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Complaint Image" class="complaint-img">
                <?php endif; ?>

                <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Right Sidebar -->
    <div class="right-panel">
        <p>Welcome,</p>
        <h2><?php echo $_SESSION['name']; ?></h2>
        <p>Student ID : <?php echo $_SESSION['student_id']; ?></p>

        <a href="submit_complaint.php">New Complaint/Suggestion</a>
        <a href="logout.php" class="logout">Logout</a>

        <div style="margin-top: 40px; font-size: 32px; font-weight: 800; letter-spacing: 1px; font-family: 'Inter', serif;">
    VOXERA.
</div>

    </div>
</div>

</body>
</html>

