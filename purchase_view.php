<?php
session_start();
include 'db.php'; // Include your database connection file

// Initialize a message variable
$message = "";

// Handle deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    if (!empty($_POST['purchase_ids'])) {
        // Sanitize the input to prevent SQL injection
        $idsToDelete = implode(",", array_map('intval', $_POST['purchase_ids']));
        $deleteSql = "DELETE FROM purchases WHERE purchase_id IN ($idsToDelete)";
        
        if ($conn->query($deleteSql) === TRUE) {
            $message = "Purchases deleted successfully."; // Set success message
        } else {
            $message = "Error deleting purchases: " . $conn->error; // Set error message
        }
    } else {
        $message = "No purchases selected for deletion."; // Set no selection message
    }
}

// Fetch purchases
$sql = "
    SELECT 
        purchases.purchase_id,
        client.full_name,
        client.address,
        client.contact,
        products.product_name,
        products.price,
        purchases.quantity,
        purchases.date,
        client.client_id
    FROM 
        purchases
    INNER JOIN 
        client ON purchases.client_id = client.client_id
    INNER JOIN 
        products ON purchases.product_id = products.product_id
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History</title>
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

    /* Header Styling */
    header {
        background-color: #2f4f4f00; /* Semi-transparent blue background */
        color: white;
        padding: 20px;
        text-align: center;
        font-size: 24px;
        font-weight: 600;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-bottom: 2px solid #ffffff00;
    }

    /* Navigation bar styling */
    nav {
        background-color: #00000000; /* Semi-transparent blue */
        overflow: hidden;
        text-align: center;
        margin-bottom: 20px;
    }

    nav a {
        display: inline-block;
        padding: 14px 20px;
        color: #fff;
        text-decoration: none;
        font-weight: 250;
        transition: background-color 0.3s;
    }

    nav a:hover {
        background-color: #1c80d4; /* Slightly darker blue on hover */
    }

    /* Add Product Form Styling */
    h2 {
        text-align: center;
        color: #ffcb1f; /* Golden accent color for the title */
        font-size: 28px;
        margin-bottom: 20px;
    }

    form {
        background-color: rgba(255, 255, 255, 0.1); /* Semi-transparent form background */
        padding: 20px;
        border-radius: 10px;
        max-width: 100%; /* Allow form to expand in smaller screens */
        margin: 0 auto;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(5px);
    }

    table {
        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
        overflow-x: auto; /* Make the table scrollable horizontally on smaller screens */
    }

    table th, table td {
        padding: 12px;
        text-align: left;
        border: 1px solid #ddd;
    }

    table th {
        background-color: #1E90FF;
        color: white;
    }

    table td {
        background-color: #333;
    }

    table td a {
        color: #ffcb1f;
        text-decoration: none;
    }

    /* Button Styling */
    button {
        width: 100%;
        padding: 14px;
        background-color: #28cd2000; /* Blue */
        border: none;
        color: white;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
        margin-top: 20px;
    }

    button:hover {
        background-color: #1C86EE; /* Darker blue on hover */
    }

    button:active {
        transform: scale(0.98); /* Slight shrink on click */
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        h2 {
            font-size: 24px;
        }

        form {
            padding: 15px;
        }

        table th, table td {
            padding: 10px;
            font-size: 14px;
        }

        table {
            font-size: 14px;
        }

        button {
            font-size: 14px;
            padding: 12px;
        }
    }
    </style>
</head>
<body>
    <header>Purchase History</header>
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="purchase_view.php">View Purchases</a>
        <a href="add_product.php" class="add-button">Add Product</a>
    </nav>

    <div>
        <form method="POST" action="">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <tr>
                        <th>Select</th>
                        <th>Purchase ID</th>
                        <th>Client Name</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>Client Details</th>
                    </tr>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><input type="checkbox" name="purchase_ids[]" value="<?= htmlspecialchars($row['purchase_id']) ?>"></td>
                            <td><?= htmlspecialchars($row['purchase_id']) ?></td>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= htmlspecialchars($row['contact']) ?></td>
                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                            <td>₱<?= number_format(htmlspecialchars($row['price']), 2) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                            <td><a href="contact_info.php?client_id=<?= htmlspecialchars($row['client_id']) ?>">View Details</a></td>
                        </tr>
                    <?php endwhile; ?>
                </table>
                <button type="submit" name="delete">Delete Selected</button>
            <?php else: ?>
                <p>No purchases found.</p>
            <?php endif; ?>
        </form>
    </div>

    <?php if (!empty($message)): ?>
        <script>
            alert("<?= addslashes($message) ?>");
        </script>
    <?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
