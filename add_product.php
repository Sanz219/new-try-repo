<?php 
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $caption = $_POST['caption'];  // New caption variable
    
    // Image upload handling
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Define the target directory
        $target_dir = "uploads/";
        // Generate a unique filename
        $target_file = $target_dir . uniqid() . "_" . basename($_FILES["image"]["name"]);
        // Move the uploaded file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_path = $target_file;
        } else {
            echo "Error uploading image.";
            exit;
        }
    } else {
        $image_path = null;  // Set to null if no image is uploaded
    }

    // Insert product details into the database
    $stmt = $conn->prepare("INSERT INTO products (product_name, price, quantity, image, caption) VALUES (?, ?, ?, ?, ?)"); // Include caption
    $stmt->bind_param("sdiss", $product_name, $price, $quantity, $image_path, $caption);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
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
    background-color: darkslategray; /* Semi-transparent blue background */
    color: white;
    padding: 20px;
    text-align: center;
    font-size: 24px;
    font-weight: 600;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-bottom: 2px solid #fff;
}

/* Navigation bar styling */
nav {
    background-color: black; /* Semi-transparent blue */
    overflow: hidden;
    text-align: center;
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
    color: #2b2a27bf; /* Golden accent color for the title */
    font-size: 28px;
    margin-bottom: 20px;
}

form {
    background-color: rgba(255, 255, 255, 0.1); /* Semi-transparent form background */
    padding: 20px;
    border-radius: 10px;
    max-width: 600px;
    margin: 0 auto;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(5px);
}

label {
    display: block;
    font-size: 16px;
    margin-bottom: 5px;
    color: #121111ba;
}

input[type="text"], input[type="number"], textarea, input[type="file"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 2px solid #ff475700;
    border-radius: 5px;
    font-size: 16px;
    background-color: #fff;
    color: #333;
    box-sizing: border-box;
}

textarea {
    resize: vertical; /* Allows resizing of the textarea */
}

button {
    width: 100%;
    padding: 14px;
    background-color: #60ff1e6b; /* Blue */
    border: none;
    color: white;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #1C86EE; /* Darker blue on hover */
}

button:active {
    transform: scale(0.98); /* Slight shrink on click */
}

/* Responsive Design */
@media (max-width: 768px) {
    form {
        padding: 15px;
    }

    h2 {
        font-size: 24px;
    }

    label {
        font-size: 14px;
    }

    input[type="text"], input[type="number"], textarea, input[type="file"] {
        font-size: 14px;
    }

    button {
        padding: 12px;
        font-size: 14px;
    }
}


</style>
</head>
<body>
<!-- Add Product Form -->
<h2>Add Product</h2>
<form action="add_product.php" method="POST" enctype="multipart/form-data">
    <label for="product_name">Product Name:</label>
    <input type="text" name="product_name" required><br>

    <label for="price">Price:</label>
    <input type="number" step="0.01" name="price" required><br>

    <label for="quantity">Quantity:</label>
    <input type="number" name="quantity" required><br>

    <label for="caption">Product Caption:</label>
    <textarea name="caption" rows="4" cols="50" required placeholder="Enter product caption here..."></textarea><br> 
    <label for="image">Product Image:</label>
    <input type="file" name="image" accept="image/*"><br>

    <button type="submit" class="add-button">Add Product</button>
</form>


</body>
</html>
