<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM client WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $client = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $client['password'])) {
            $_SESSION['client_id'] = $client['client_id'];
            $_SESSION['full_name'] = $client['full_name'];

            header("Location: product_dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid password.'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Username not found.'); window.location.href='login.php';</script>";
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Cyberpunk Background and Animation */
        body {
            background: linear-gradient(135deg, #FF00FF, #00FFFF, #00FF00, #FFFF00, #FF0000); 
            background-size: 400% 400%;
            animation: gradient-animation 10s ease infinite;
            font-family: 'Orbitron', sans-serif; /* Futuristic font */
            color: #fff;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        @keyframes gradient-animation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h2 {
            color: #00FF00; /* Neon green color */
            margin-top: 30px;
            font-size: 3rem;
            text-shadow: 0 0 10px rgba(0, 255, 0, 0.8), 0 0 20px rgba(0, 255, 0, 0.8);
        }

        /* Center the form on the page */
        form {
            max-width: 450px;
            width: 100%;
            margin: 0 auto;
            padding: 30px;
            background-color: rgba(0, 0, 0, 0.7); /* Dark transparent background */
            border-radius: 10px;
            box-shadow: 0 0 25px rgba(0, 255, 255, 0.6); /* Neon glow shadow */
            border: 2px solid #00FFFF; /* Cyan border */
        }

        /* Style for input fields and buttons */
        input[type="text"], input[type="password"], input[type="email"], input[type="file"], textarea, button {
            width: 95%;
            padding: 14px;
            margin: 12px 0;
            border: 2px solid #00FFFF;
            border-radius: 5px;
            background-color: rgba(0, 0, 0, 0.5);
            color: #fff;
            font-size: 16px;
            box-shadow: 0 0 5px rgba(0, 255, 255, 0.7); /* Glowing effect */
        }

        button {
            background-color: #FF00FF;
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
            cursor: pointer;
            transition: background-color 0.3s;
            padding: 12px;
            width: 95%;
        }

        /* Glowing button effect on hover */
        button:hover {
            background-color: #FF00FF;
            box-shadow: 0 0 20px rgba(255, 0, 255, 0.8); /* Glowing effect */
        }

        /* Labels for input fields */
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            color: #00FF00;
            font-size: 1.2rem;
        }

        /* Link to Signup page */
        a {
            color: #00FFFF;
            text-decoration: none;
            font-size: 1rem;
            display: inline-block;
            margin-top: 15px;
            transition: color 0.3s;
        }

        a:hover {
            color: #FF00FF; /* Neon pink link hover */
        }

        @media (max-width: 768px) {
            h2 {
                font-size: 2rem;
            }

            form {
                padding: 20px;
            }

            input[type="text"], input[type="password"], input[type="email"], input[type="file"], textarea {
                font-size: 14px;
                padding: 10px;
            }

            button {
                font-size: 1.2rem;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <h2>Client Login</h2>
    <form action="login.php" method="POST">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit">Login</button>
        <a href="signup.php">Don't have an account? Signup</a>
    </form>
</body>
</html>
