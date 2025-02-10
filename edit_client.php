<?php 
include 'db.php'; // Ensure the path to 'db.php' is correct

// Fetch clients
$client_id = $_GET['client_id']; // Get client ID from URL
$sql_client = "SELECT * FROM client WHERE client_id = $client_id";
$result_client = $conn->query($sql_client);

if (!$result_client) {
    die("Query failed: " . $conn->error);
}

$client = $result_client->fetch_assoc();

if (!$client) {
    die("Client not found.");
}

// Update client information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    $sql_update = "UPDATE client SET full_name = '$full_name', contact = '$contact', address = '$address' WHERE client_id = $client_id";

    if ($conn->query($sql_update) === TRUE) {
        echo "<script>alert('Client information updated successfully.');</script>";
        header("Location: contact_info.php?client_id=$client_id"); // Redirect after successful update
        exit;
    } else {
        echo "<script>alert('Error updating client information: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <header>
        Contact Information
    </header>

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
        /* Header styling */
        header {
            background-color: #4caf500a; /* Green background */
            color: white; /* White text */
            padding: 20px; /* Padding around the header */
            text-align: center; /* Center the text */
            font-size: 24px; /* Larger font size */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        /* Main content styling */
        .content {
            max-width: 600px; /* Max width for the content */
            margin: 20px auto; /* Center the content */
            padding: 20px; /* Padding inside the content area */
            border: 1px solid #ddd; /* Border around the content */
            border-radius: 10px; /* Rounded corners */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            background-color: #ffffff00; /* White background */
        }

        /* Profile picture styling */
        .profile-picture {
            max-width: 150px; /* Max width for the profile picture */
            border-radius: 5px; /* Rounded corners */
            position: absolute; /* Position the image absolutely */
            top: 20px; /* Distance from the top */
            right: 20px; /* Distance from the right */
        }

        /* Footer styling */
        footer {
            background-color: #f1f1f1; /* Light grey background */
            color: #555; /* Dark grey text */
            text-align: center; /* Centered text */
            padding: 10px; /* Padding around the footer */
            position: relative; /* Positioning for footer */
            bottom: 0; /* Stick to the bottom */
            width: 100%; /* Full width */
            box-shadow: 0 -1px 3px rgba(0, 0, 0, 0.1); /* Subtle shadow on top */
        }

        /* Button styling */
        .back-button {
            display: inline-block; /* Inline block for better button appearance */
            padding: 10px 15px; /* Padding for the button */
            background-color: #4CAF50; /* Green background */
            color: white; /* White text */
            text-decoration: none; /* No underline */
            border-radius: 5px; /* Rounded corners */
            transition: background-color 0.3s; /* Transition effect */
        }

        .back-button:hover {
            background-color: #45a049; /* Darker green on hover */
        }

        /* Form styling */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px; /* Spacing between form elements */
        }

        label {
            font-weight: bold;
            color: #333;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
        }

        .button {
            padding: 12px;
            border-radius: 5px;
            background-color: #4caf5087;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        .button:hover {
            background-color: #45a049;
        }

        a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        a:hover {
            color: #0056b3;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .content {
                padding: 15px;
            }

            header {
                font-size: 20px;
            }

            .button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <h2>Edit Client Information</h2>
        <form method="POST">
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" id="full_name" value="<?= htmlspecialchars($client['full_name']) ?>" required>

            <label for="contact">Contact:</label>
            <input type="text" name="contact" id="contact" value="<?= htmlspecialchars($client['contact']) ?>" required>

            <label for="address">Address:</label>
            <input type="text" name="address" id="address" value="<?= htmlspecialchars($client['address']) ?>" required>

            <input class="button" type="submit" value="Update">
        </form>

        <a href="contact_info.php?client_id=<?= $client_id ?>">Cancel</a>
    </div>
</body>
</html>
