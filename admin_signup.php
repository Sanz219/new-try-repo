<?php
// Include your database connection file
include 'db.php'; // Ensure the path to your db.php is correct

// Initialize message variable
$message = '';
$message_type = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $admin_name = $_POST['admin_name'];
    $admin_email = $_POST['admin_email'];
    $admin_password = $_POST['admin_password'];

    // Check if the email already exists in the database
    $sql = "SELECT * FROM admin WHERE admin_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $admin_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If email exists, show an error message
        $message = "Email is already taken.";
        $message_type = 'error-message';
    } else {
        // Hash the password
        $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

        // Insert the new admin data into the database
        $sql = "INSERT INTO admin (admin_name, admin_email, admin_password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $admin_name, $admin_email, $hashed_password);
        if ($stmt->execute()) {
            // If insertion is successful, show a success message
            $message = "Admin account created successfully. You can now log in.";
            $message_type = 'success-message';
        } else {
            // If there is an error while inserting
            $message = "Error creating account. Please try again.";
            $message_type = 'error-message';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign Up</title>
    <style>

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

        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .signup-container {
            background-color: none;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .signup-container h2 {
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
            background-color: skyblue;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .input-group input[type="submit"]:hover {
            background-color: deepskyblue;
        }

        .error-message {
            color: red;
            text-align: center;
            margin: 10px 0;
        }

        .success-message {
            color: green;
            text-align: center;
            margin: 10px 0;
        }

    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Admin Sign Up</h2>
        <?php if (isset($message)): ?>
            <p class="<?= $message_type ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form action="admin_signup.php" method="POST">
            <div class="input-group">
                <label for="admin_name">Name</label>
                <input type="text" name="admin_name" id="admin_name" required>
            </div>
            <div class="input-group">
                <label for="admin_email">Email</label>
                <input type="email" name="admin_email" id="admin_email" required>
            </div>
            <div class="input-group">
                <label for="admin_password">Password</label>
                <input type="password" name="admin_password" id="admin_password" required>
            </div>
            <div class="input-group">
                <center><input type="submit" value="Sign Up"></center><br>
            <center><a href="admin_login.php">Login</a></center>

            </div>
        </form>
    </div>
</body>
</html>
