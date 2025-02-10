<?php 
include 'db.php'; // Ensure the path to 'db.php' is correct

// Fetch clients
$client_id = 1; // Assuming you want to display the dashboard for client with ID 1
$sql_client = "SELECT * FROM client WHERE client_id = $client_id";
$result_client = $conn->query($sql_client);
$client = $result_client->fetch_assoc();

// Fetch products
$sql_products = "SELECT * FROM products";
$result_products = $conn->query($sql_products);

if (!$result_products) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Dashboard</title>
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

        /* Navigation bar styling */
        nav {
            background-color: #333; /* Dark background */
            overflow: hidden; /* Clear floats */
        }

        nav a {
            float: left; /* Align links to the left */
            display: block; /* Make links appear as blocks */
            color: white; /* White text */
            text-align: center; /* Centered text */
            padding: 14px 16px; /* Padding for links */
            text-decoration: none; /* No underline */
        }

        nav a:hover {
            background-color: #ddd; /* Light background on hover */
            color: black; /* Dark text on hover */
        }

        /* Container for product cards */
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 20px auto; /* Center the container with auto margins */
            max-width: 1200px; /* Set a maximum width for the container */
            justify-content: center; /* Center the items horizontally */
        }

        /* Style for individual product cards */
        .product-card {
            width: 200px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Image styling */
        .product-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        /* Product details styling */
        .product-info {
            padding: 15px;
        }

        /* Buttons styling */
        .product-card button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: none;
            color: white;
            cursor: pointer;
        }

        .buy-button { background-color: #4CAF50; }
        .buy-button:hover { background-color: #45a049; }

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
    </style>
</head>
<body>
    <header>
        Welcome, <?= htmlspecialchars($client['full_name']) ?>!
    </header>

    <!-- Navigation bar -->
    <nav>
        <a href="contact_info.php?client_id=<?= $client_id ?>">Contact Information</a>
        <a href="client.php">Product Dashboard</a>
        <a href="signup.php">Sign Up</a>

    </nav>

    <!-- Product cards container -->
    <!--<h2>Available Products</h2>-->
    <div class="product-container">
        <?php while ($row = $result_products->fetch_assoc()): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                <div class="product-info">
                    <h4><?= htmlspecialchars($row['product_name']) ?></h4>
                    <p>Price: ₱<?= htmlspecialchars($row['price']) ?></p>
                    <div>
                        <a href="purchase.php?id=<?= $row['product_id'] ?>">
                            <button class="buy-button">Buy Now</button>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <footer>
        &copy; <?= date("Y") ?> Mataro Company Corp™. All rights reserved.
    </footer>
</body>
</html>
