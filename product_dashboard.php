<?php 
session_start();
include 'db.php';

// Check if the client is logged in; otherwise, redirect to the login page
if (!isset($_SESSION['client_id'])) {
    header("Location: signup.php");
    exit();
}

// Retrieve client ID from session
$client_id = $_SESSION['client_id'];

// Fetch client information
$sql_client = "SELECT * FROM client WHERE client_id = $client_id";
$result_client = $conn->query($sql_client);

// Check if client data was fetched successfully
if ($result_client && $result_client->num_rows > 0) {
    $client = $result_client->fetch_assoc();
    $client_name = htmlspecialchars($client['full_name']);
} else {
    $client_name = "Guest"; // Default name if client data not found
}

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
            background-color: #b0c4de00;
            color: black;
            padding: 20px;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-bottom: #ffffff00;
        }

        /* Navigation bar styling */
        nav {
            background-color: #b0c4de00;
            overflow: hidden;
            text-align: center;
            margin-top: 20px;
        }

        nav a {
            display: inline-block;
            padding: 14px 20px;
            color: black;
            text-decoration: none;
            font-weight: 250;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #1c80d4;
        }

        /* Product Cards Container */
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }

        /* Individual Product Card */
        .product-card {
            width: 250px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease-in-out;
            backdrop-filter: blur(5px);
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .product-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-bottom: 2px solid #fff;
        }

        .product-info {
            padding: 15px;
            color: white;
        }

        .product-info h4 {
            font-size: 18px;
            font-weight: 600;
        }

        .product-info p {
            font-size: 14px;
            margin: 10px 0;
        }

        .product-info form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        input[type="number"] {
            padding: 8px;
            font-size: 14px;
            border-radius: 5px;
            border: 2px solid #ff4757;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #dc1eff36;
            border: none;
            color: black;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: black;
        }

        button:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>
    <header>
        Welcome, <?= $client_name ?>!
    </header>

    <!-- Navigation bar -->
    <nav>
        <a href="contact_info.php?client_id=<?= $client_id ?>">Contact Information</a>
        <a href="product_dashboard.php">Product Dashboard</a>
        <a href="signup.php">Sign Up</a>
        <a href="log_page.php">Logout</a>
    </nav>

    <!-- Product cards container -->
    <div class="product-container">
        <?php while ($row = $result_products->fetch_assoc()): ?>
            <div class="product-card">
                <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>">
                <div class="product-info">
                    <h4><?= htmlspecialchars($row['product_name']) ?></h4>
                    <p><?= htmlspecialchars($row['caption']) ?></p>
                    <p>Price: ₱<?= number_format(htmlspecialchars($row['price']), 2) ?></p>
                    <form action="purchase.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" min="1" value="1" required>
                        <button type="submit" name="buy" class="buy-button">Buy Now</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
