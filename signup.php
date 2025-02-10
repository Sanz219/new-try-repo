<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "harrydash";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind parameters
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);
    $email = $conn->real_escape_string($_POST['email']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password for security

    // Check if the username, full name, or email already exists
    $check_sql = "SELECT * FROM client WHERE full_name = '$full_name' OR email = '$email' OR username = '$username'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        echo "<script>
            alert('Error: Full name, email, or username already exists.');
            window.location.href = 'signup.php'; // Redirect to the signup page
        </script>";
    } else {
        // Handle file upload
        $profile_picture = $_FILES['profile_picture'];
        $upload_dir = 'picture/';
        $upload_file = $upload_dir . basename($profile_picture['name']);
        
        if ($profile_picture['error'] === UPLOAD_ERR_OK) {
            if (move_uploaded_file($profile_picture['tmp_name'], $upload_file)) {
                // Insert query including username and password fields
                $sql = "INSERT INTO client (full_name, contact, address, email, username, password, profile_picture) 
                        VALUES ('$full_name', '$contact', '$address', '$email', '$username', '$password', '$upload_file')";

                if ($conn->query($sql) === TRUE) {
                    $last_id = $conn->insert_id;
                    echo "<script>
                        alert('New record created successfully');
                        window.location.href = 'client_dashboard.php?client_id=" . $last_id . "'; 
                    </script>";
                } else {
                    echo "<script>
                        alert('Error: " . $conn->error . "');
                        window.location.href = 'signup.php'; 
                    </script>";
                }
            } else {
                echo "<script>
                    alert('Error uploading file.');
                    window.location.href = 'signup.php'; 
                </script>";
            }
        } else {
            echo "<script>
                alert('File upload error: " . $profile_picture['error'] . "');
                window.location.href = 'signup.php'; 
            </script>";
        }
    }
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Signup Form</title>
    <link rel="stylesheet" href="styles.css">
    <script src="sweetalert2/dist/sweetalert2.all.min.js"></script>
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
            color: #fff;
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

        h2 {
            text-align: center;
            color: #120f10ad; /* Red Pokémon theme color */
            margin-top: 30px;
            font-size: 2rem;
        }

        /* Form Container */
        form {
            max-width: 480px;
            width: 100%;
            padding: 20px;
            background-color: #ffffff00;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.3);
            border: 3px solid #ff475700;
        }

        /* Input Fields, Button Styling */
        input[type="text"], input[type="password"], input[type="email"], input[type="file"], textarea, button {
            width: 100%; /* Ensure inputs fill the form width */
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #ff475717;
            border-radius: 5px;
            font-size: 16px;
        }

        /* Textarea style */
        textarea {
            resize: vertical;
        }

        /* Button styling */
        button {
            background-color: #62ff4740;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
            font-size: 1.2rem;
        }

        button:hover {
            background-color: #ff6b81;
        }

        /* Label Styling */
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #003a63;
        }

        /* Link Styling */
        a {
            color: #003a63;
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
        }

        a:hover {
            color: #ff4757;
        }

        /* Responsive Design for smaller screens */
        @media (max-width: 768px) {
            h2 {
                font-size: 1.8rem;
            }

            form {
                padding: 15px;
            }

            input[type="text"], input[type="password"], input[type="email"], textarea, button {
                font-size: 14px;
            }
        }

    </style>
</head>
<body>
    <div>
        <h2>Client Signup Form</h2>
        <form action="signup.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div>
                <label for="contact">Contact:</label>
                <input type="text" id="contact" name="contact" required>
            </div>
            <div>
                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="4" required></textarea>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <label for="profile_picture">Profile Picture:</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
            </div>
            <button type="submit">Sign Up</button>
            <div style="text-align:center;">
                <a href="login.php">Already have an account? Login</a>
            </div>
        </form>
    </div>
</body>
</html>
