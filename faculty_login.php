<?php
session_start();
include 'db.php';

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND is_faculty = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_faculty'] = true;
            $_SESSION['name'] = $user['name'];

            header("Location: faculty_dashboard.php");
            exit();
        } else {
            $msg = "❌ Incorrect password.";
        }
    } else {
        $msg = "⚠️ No faculty found with this email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Voxera - Faculty Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap">
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

        .branding {
            position: absolute;
            top: 30px;
            left: 40px;
            font-size: 24px;
            font-weight: 700;
            color:rgb(255, 255, 255);
        }

        .glass-container {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 40px;
            width: 400px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
            color: #000;
        }

        .glass-container h2 {
            margin-bottom: 30px;
            font-weight: 700;
            color: #111;
            text-align: center;
        }

        input[type="email"], input[type="password"] {
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
            text-align: center;
        }

        .bottom-link {
            text-align: center;
            margin-top: 15px;
            font-size: 14px;
        }

        .bottom-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="branding">VOXERA.</div>

    <div class="glass-container">
        <h2> Faculty Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Faculty Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p class="msg"><?php echo $msg; ?></p>
        <p class="bottom-link"><a href="index.php">⬅ Back to Home</a></p>
    </div>

</body>
</html>
