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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Information</title>
    <style>
        /* Header styling */
        header {
            background-color: #4CAF50; /* Green background */
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
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
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
    </style>
</head>
<body>
    <header>
        Contact Information
    </header>

    <div class="content">
        <h2>Client Details</h2>
        <p><strong>Full Name:</strong> <?= htmlspecialchars($client['full_name']) ?></p>
        <p><strong>Contact:</strong> <?= htmlspecialchars($client['contact']) ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($client['address']) ?></p>
        
        <a class="back-button" href="client_dashboard.php?client_id=<?= $client_id ?>">Back to Dashboard</a>
    </div>
</body>
</html>
