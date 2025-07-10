<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $desc = $_POST['description'];
    $is_anon = isset($_POST['anonymous']) ? 1 : 0;
    $is_sensitive = ($type == "harassment" || $type == "ragging") ? 1 : 0;

    $name = $is_anon ? "Anonymous" : $_SESSION['name'];
    $student_id = $is_anon ? "Hidden" : $_SESSION['student_id'];

    $image = "";
    if (!empty($_FILES['proof']['name'])) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir);
        $image = $upload_dir . time() . "_" . basename($_FILES['proof']['name']);
        move_uploaded_file($_FILES['proof']['tmp_name'], $image);
    }

    $stmt = $conn->prepare("INSERT INTO complaints (student_id, name, type, description, image, is_sensitive, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("sssssi", $student_id, $name, $type, $desc, $image, $is_sensitive);

    if ($stmt->execute()) {
        $msg = "✅ Complaint submitted!";
    } else {
        $msg = "❌ Failed to submit complaint.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Submit Complaint - Voxera</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: url('images/voxhomewall1.jpg') no-repeat center center/cover;
            color: #0f172a;
        }

        .wrapper {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            gap: 3rem;
        }

        .submit-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
        }

        .submit-card h2 {
            margin-bottom: 20px;
            color: #1d4ed8;
        }

        label {
            font-weight: 600;
            margin-top: 15px;
            display: block;
        }

        select, textarea, input[type=text], input[type=file] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 1rem;
        }

        input[type=checkbox] {
            margin-top: 16px;
        }

        .btn-submit {
            margin-top: 20px;
            width: 100%;
            background-color: #1d4ed8;
            color: white;
            border: none;
            padding: 12px;
            font-size: 1rem;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #2563eb;
        }

        .disclaimer-box {
            max-width: 400px;
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(8px);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
        }

        .disclaimer-box h3 {
            color: #b91c1c;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .disclaimer-box p {
            font-size: 0.95rem;
            margin-bottom: 10px;
            color: #334155;
        }

        .msg {
            margin-top: 15px;
            font-weight: 500;
            color: green;
            text-align: center;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #1d4ed8;
            font-weight: 500;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 960px) {
            .wrapper {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="wrapper">

    <!-- Left: Submit Form -->
    <div class="submit-card">
        <h2> Submit a Complaint</h2>

        <form method="POST" enctype="multipart/form-data">
            <label>Type:</label>
            <select name="type" required>
                <option value="">Select Type</option>
                <option value="infrastructure">Infrastructure</option>
                <option value="electricity">Electricity</option>
                <option value="washroom">Washroom</option>
                <option value="harassment">Harassment</option>
                <option value="ragging">Ragging</option>
                <option value="faculty">Faculty Misconduct</option>
                <option value="other">Other</option>
            </select>

            <label>Description:</label>
            <textarea name="description" rows="5" required></textarea>

            <label>Upload Photo (optional):</label>
            <input type="file" name="proof">

            <label>
                <input type="checkbox" name="anonymous"> Submit as Anonymous
            </label>

            <button class="btn-submit" type="submit">📤 Submit</button>
        </form>

        <?php if ($msg): ?>
            <div class="msg"><?php echo $msg; ?></div>
        <?php endif; ?>

        <a class="back-link" href="student_home.php">⬅ Back to Dashboard</a>
    </div>

    <!-- Right: Disclaimer -->
    <div class="disclaimer-box">
         <div style="margin-top: 40px; font-size: 32px; font-weight: 800; letter-spacing: 1px; font-family: 'Inter', serif;">
          VOXERA.
         </div>
         <br>
         <br>
        <h3>⚠️ Disclaimer Before Submitting</h3>
        <p>By submitting a complaint through Voxera, you agree to provide accurate and respectful information.</p>
        <p>False, misleading, or defamatory submissions may result in disciplinary action.</p>
        <p>For sensitive issues such as harassment or misconduct, your identity will remain confidential unless you choose to disclose it.</p>
        <p>All complaints are reviewed by authorized faculty or admin for appropriate action.</p>
        <p><strong>Voxera</strong> is committed to ensuring a safe, fair, and transparent environment for every student.</p>
    </div>

</div>

</body>
</html>
