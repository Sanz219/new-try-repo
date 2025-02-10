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
        header("Location: product_dashboard.php?client_id=$client_id"); // Redirect after successful update
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
    <title>Edit Client Information</title>
    
</head>
<body>
    <h2>Edit Client Information</h2>
    <form method="POST">
        <label for="full_name">Full Name:</label><br>
        <input type="text" name="full_name" id="full_name" value="<?= htmlspecialchars($client['full_name']) ?>" required><br><br>

        <label for="contact">Contact:</label><br>
        <input type="text" name="contact" id="contact" value="<?= htmlspecialchars($client['contact']) ?>" required><br><br>

        <label for="address">Address:</label><br>
        <input type="text" name="address" id="address" value="<?= htmlspecialchars($client['address']) ?>" required><br><br>

        <input type="submit" value="Update">
    </form>
    <a href="product_dashboard.php?client_id=<?= $client_id ?>">Cancel</a>
</body>
</html>
