<?php
include 'db.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Prepare and execute the select statement
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if (!$product) {
        echo "Product not found!";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $caption = $_POST['caption']; // Capture the caption from the form

    // Handle image upload
    $image = $product['image']; // Keep the old image path by default

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/"; // Directory to store uploaded images
        $image_file_name = basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_file_name;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image_path = $target_file; // Update the image path if upload is successful
        } else {
            echo "Error uploading image.";
        }
    } else {
        $image_path = $image; // Use the old image if no new one is uploaded
    }

    // Prepare the update statement
    $stmt = $conn->prepare("UPDATE products SET product_name = ?, price = ?, quantity = ?, image = ?, caption = ? WHERE product_id = ?");
    $stmt->bind_param("sdissi", $product_name, $price, $quantity, $image_path, $caption, $product_id); // Updated to include caption

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
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
        
        h3 {
            text-align: center;
            color: #333;
        }
        
        form {
            background-color: #ffffff00;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            margin: auto;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px; /* Height for the caption area */
        }

        button {
            background-color: #5cb85cb0;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #4cae4c;
        }

        h4 {
            text-align: center;
            color: #333;
            margin-top: 30px;
        }

        img {
            display: block;
            margin: 0 auto;
            max-width: 200px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h3>Edit Product</h3>
    <form action="edit_product.php?id=<?= htmlspecialchars($product['product_id']) ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">
        <label>Product Name: 
            <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required>
        </label>
        <label>Price: 
            <input type="number" name="price" value="<?= htmlspecialchars($product['price']) ?>" step="0.01" required>
        </label>
        <label>Quantity: 
            <input type="number" name="quantity" value="<?= htmlspecialchars($product['quantity']) ?>" required>
        </label>
        <label>Caption: 
            <textarea name="caption" required><?= htmlspecialchars($product['caption']) ?></textarea>
        </label>
        <label>Image: 
            <input type="file" name="image">
        </label>
        <button type="submit">Update Product</button>
    </form>

    <!-- Display the current image -->
    <?php if ($product['image']): ?>
        <h4>Current Image:</h4>
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="Product Image">
    <?php endif; ?>
</body>
</html>
