<?php
include 'db.php';

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $student_id = $_POST['student_id'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT * FROM users WHERE student_id = ?");
    $check->bind_param("s", $student_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $msg = "⚠️ Student ID already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, student_id, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $student_id, $password);
        if ($stmt->execute()) {
            $msg = "✅ Registered successfully! <a href='login.php'>Login here</a>";
        } else {
            $msg = "❌ Registration failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Voxera - Register</title>
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
            text-align: center;
        }

        .msg a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

        .bottom-text {
            margin-top: 15px;
            font-size: 14px;
            text-align: center;
        }

        .bottom-text a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="branding">VOXERA.</div>

    <div class="glass-container">
        <h2>📝 Student Registration</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="student_id" placeholder="Student ID" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <p class="msg"><?php echo $msg; ?></p>
        <p class="bottom-text">Already registered? <a href="login.php">Login here</a></p>
    </div>

</body>
</html>
