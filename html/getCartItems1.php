<?php
// Start session to access session data
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, return error
    http_response_code(401);
    echo json_encode(array("error" => "User not logged in"));
    exit;
}

// Connect to the database
require_once('connection.php');

// Prepare statement to select items from the cart table for the logged-in user
$stmt = $con->prepare("SELECT items.item_id, items.item_name, items.item_price, items.item_description, items.item_image, SUM(cart.quantity) AS total_quantity FROM cart INNER JOIN items ON cart.item_id = items.item_id WHERE cart.user_id = ? GROUP BY items.item_id");
$stmt->bind_param("i", $_SESSION['user_id']);

// Execute the statement
$stmt->execute();

// Bind the result variables
$stmt->bind_result($itemId, $itemName, $itemPrice, $itemDescription, $itemImage, $totalQuantity);

// Fetch items and store them in an array
$cartItems = array();
while ($stmt->fetch()) {
    // Check if total quantity is greater than 0
    if ($totalQuantity > 0) {
        // If total quantity is greater than 1, stack the item
        if ($totalQuantity > 1) {
            $cartItems[] = array(
                "id" => $itemId,
                "name" => $itemName,
                "price" => $itemPrice,
                "description" => $itemDescription,
                "image" => $itemImage,
                "quantity" => $totalQuantity
            );
        } else {
            // If total quantity is 1, don't stack, just add the item once
            $cartItems[] = array(
                "id" => $itemId,
                "name" => $itemName,
                "price" => $itemPrice,
                "description" => $itemDescription,
                "image" => $itemImage,
                "quantity" => 1
            );
        }
    }
}

// Close the statement and database connection
$stmt->close();
$con->close();

// Return the cart items as JSON
http_response_code(200);
echo json_encode($cartItems);
?>
