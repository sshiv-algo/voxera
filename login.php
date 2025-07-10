<?php
session_start();
include 'db.php';

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE student_id = ?");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['student_id'] = $user['student_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['is_admin'] = $user['is_admin'];
            $_SESSION['is_faculty'] = $user['is_faculty'];

            if ($user['is_admin'] == 1) {
                header("Location: admin_dashboard.php");
            } elseif ($user['is_faculty'] == 1) {
                header("Location: faculty_dashboard.php");
            } else {
                header("Location: student_home.php");
            }
            exit();
        } else {
            $msg = "❌ Incorrect password.";
        }
    } else {
        $msg = "⚠️ Student ID not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Voxera - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background: url('images/voxhomewall1.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .glass-container {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 50px;
            width: 360px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            color: #000;
        }

        .glass-container h2 {
            margin-bottom: 30px;
            font-weight: 700;
            color: #111;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 14px;
            margin-bottom: 20px;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.9);
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #1d4ed8;
        }

        .msg {
            color: red;
            margin-top: 10px;
        }

        .bottom-text {
            margin-top: 15px;
            font-size: 14px;
        }

        .bottom-text a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .branding {
            position: absolute;
            top: 30px;
            left: 40px;
            font-size: 24px;
            font-weight: 700;
            color:rgb(255, 255, 255);
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

    <div class="branding">VOXERA.</div>

    <div class="glass-container">
        <h2>Sign in</h2>
        <form method="POST">
            <input type="text" name="student_id" placeholder="Student ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign In</button>
        </form>
        <p class="msg"><?php echo $msg; ?></p>
        <p class="bottom-text">Don't have an account? <a href="register.php">Sign up</a></p>
    </div>

</body>
</html>
