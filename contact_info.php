<?php 
include 'db.php'; // Ensure the path to 'db.php' is correct

// Validate if 'client_id' is present and numeric
if (isset($_GET['client_id']) && is_numeric($_GET['client_id'])) {
    $client_id = $_GET['client_id'];

    // Sanitize the input to prevent SQL injection
    $client_id = $conn->real_escape_string($client_id);

    // Fetch client data
    $sql_client = "SELECT * FROM client WHERE client_id = $client_id";
    $result_client = $conn->query($sql_client);

    if (!$result_client) {
        die("Query failed: " . $conn->error);
    }

    $client = $result_client->fetch_assoc();

    if (!$client) {
        die("Client not found.");
    }
} else {
    // Redirect to a default page or show a user-friendly error message
    header("Location: error.php?message=Missing or invalid client ID"); // Redirect to an error page
    exit;
}

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];

    // Validate the file type
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($file['type'], $allowed_types) && $file['error'] === 0) {
        // Define a directory to store uploaded files
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); // Create directory if it doesn't exist
        }
        $file_name = $upload_dir . basename($file['name']);

        // Move the uploaded file to the specified directory
        if (move_uploaded_file($file['tmp_name'], $file_name)) {
            // Update the database with the file path
            $sql_update = "UPDATE client SET profile_picture = '$file_name' WHERE client_id = $client_id";
            if ($conn->query($sql_update) === TRUE) {
                echo "<script>alert('Profile picture uploaded successfully.');</script>";
            } else {
                echo "<script>alert('Error updating database: " . $conn->error . "');</script>";
            }
        } else {
            echo "<script>alert('Failed to upload the file.');</script>";
        }
    } else {
        echo "<script>alert('Invalid file type or upload error.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Information</title>
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
                #FF4500,  /* Bright orange */
                #FF69B4,  /* Pink */
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

/* Header Styling */
header {
    background-color: #ffb6c100; /* Semi-transparent blue background */
    color: black;
    padding: 20px;
    text-align: center;
    font-size: 24px;
    font-weight: 600;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-bottom: 2px solid #ffffff00;
}

/* Profile Picture Styling */
.profile-picture {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #eb1fff; /* Golden border around profile picture */
    display: block;
    margin: 20px auto;
}

/* Content Styling */
.content {
    max-width: 900px;
    margin: 30px auto;
    padding: 20px;
    background-color: rgba(0, 0, 0, 0.1); /* Semi-transparent background */
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

/* Client Details Styling */
h2 {
    text-align: center;
    font-size: 28px;
    color: black; /* Golden accent color */
    margin-bottom: 20px;
}

p {
    font-size: 16px;
    margin: 10px 0;
}

strong {
    color: black; /* Golden color for labels */
}

/* Upload Profile Picture Form */
form {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 20px;
}

input[type="file"] {
    padding: 10px;
    font-size: 16px;
    border: 2px solid #ed89ed29;
    border-radius: 5px;
    margin: 10px 0;
    background-color: #1e59ff38;
    color: black;
    cursor: pointer;
}

input[type="submit"] {
    padding: 12px 20px;
    background-color: #1e59ff38;
    border: none;
    color: black;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
    margin-top: 10px;
}

input[type="submit"]:hover {
    background-color: #ed89ed;
}

/* Back Button Styling */
.back-button {
    display: inline-block;
    padding: 12px 20px;
    background-color: #1e59ff38;
    color: black;
    font-size: 16px;
    font-weight: 500;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 20px;
    transition: background-color 0.3s;
}

.back-button:hover {
    background-color: #1e59ff38;
}

/* Responsive Design */
@media (max-width: 768px) {
    body {
        padding: 10px;
    }

    header {
        font-size: 22px;
    }

    h2 {
        font-size: 24px;
    }

    p {
        font-size: 14px;
    }

    .profile-picture {
        width: 120px;
        height: 120px;
    }

    form {
        width: 100%;
    }

    input[type="file"], input[type="submit"] {
        width: 100%;
    }

    .back-button {
        width: 100%;
        padding: 12px 0;
        text-align: center;
    }
}

    </style>
</head>
<body>
    <header>
        Client Information
    </header>

    <div class="content">
        <?php if (!empty($client['profile_picture'])): ?>
            <img class="profile-picture" src="<?= htmlspecialchars($client['profile_picture']) ?>" alt="Profile Picture">
        <?php endif; ?>
        
        <div>
            <h2>Client Details</h2>
            <p><strong>Full Name:</strong> <?= htmlspecialchars($client['full_name']) ?></p>
            <p><strong>Contact:</strong> <?= htmlspecialchars($client['contact']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($client['address']) ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($client['username']) ?></p>
            <p><strong>Password:</strong> <em>********</em> <!-- Masked password display --></p>

            <form action="" method="POST" enctype="multipart/form-data">
                <label for="profile_picture">Upload Profile Picture:</label><br>
                <input type="file" name="profile_picture" id="profile_picture" required><br><br>
                <input type="submit" value="Upload">
            </form>
            <br>
            
            <a class="back-button" href="edit_client.php?client_id=<?= $client_id ?>">Edit Information</a>
            <a class="back-button" href="product_dashboard.php?client_id=<?= $client_id ?>">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
