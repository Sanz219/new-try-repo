<?php
// Include your database connection file
include 'db.php'; // Ensure the path to your db.php is correct

// Initialize error message variable
$error_message = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];

    // Prepare the SQL query to fetch the admin based on email
    $sql = "SELECT * FROM admin WHERE admin_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_email); // Bind the email to the prepared statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if admin exists
    if ($result->num_rows > 0) {
        // Fetch the admin data
        $admin = $result->fetch_assoc();

        // Verify the password (ensure you have hashed passwords in your database)
        if (password_verify($admin_password, $admin['admin_password'])) {
            // Set the session or cookie for the logged-in admin
            session_start();
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['admin_name'];

            // Redirect to the dashboard or admin area
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "Admin not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>

         /* General Reset */
         * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Gradient Animation */
        body {
            background: linear-gradient(45deg, 
                #FFD700,  /* Vibrant yellow */
                #4123b6,  /* Bright orange */
                #28cd20b3,  /* Pink */
                #00BFFF,  /* Sky blue */
                #1E90FF   /* Darker blue */
            );
            background-size: 300% 300%;
            animation: gradient-animation 6s ease infinite;
            font-family: 'Roboto', sans-serif;
            color: #000000;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            min-height: 100vh;
        }

        @keyframes gradient-animation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Basic styling for the login form */
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .login-container {
            background-color: none;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .login-container h2 {
            text-align: center;
        }

        .input-group {
            margin: 10px 0;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
        }

        .input-group input {
            width: 95%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .input-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .input-group input[type="submit"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            text-align: center;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>
        <form action="admin_login.php" method="POST">
            <div class="input-group">
                <label for="admin_email">Email</label>
                <input type="email" name="admin_email" id="admin_email" required>
            </div>
            <div class="input-group">
                <label for="admin_password">Password</label>
                <input type="password" name="admin_password" id="admin_password" required>
            </div>
            <div class="input-group">
                <center><input type="submit" value="Login"></center>
            </div>
            <center><a href="admin_signup.php">Signup</a></center>
        </form>
    </div>
</body>
</html>
