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

// Get the JSON data sent in the request body
$json_data = file_get_contents('php://input');

// Decode JSON data to associative array
$request_data = json_decode($json_data, true);

// Check if itemId and quantity are provided in the request data
if (!isset($request_data['itemId']) || !isset($request_data['quantity'])) {
    http_response_code(400);
    echo json_encode(array("error" => "Missing itemId or quantity in request"));
    exit;
}
echo $request_data['itemId'] .''. $request_data['quantity'] .'';

// Extract itemId and quantity from the request data
$itemId = $request_data['itemId'];
$quantity = $request_data['quantity'];


if ($itemId === false || $quantity === false) {
    http_response_code(400);
    echo json_encode(array("error" => "Invalid itemId or quantity"));
    exit;
}

// Connect to the database
require_once('connection.php');

// Prepare statement to update the quantity of the item in the cart table
$stmt = $con->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND item_id = ?");
$stmt->bind_param("iii", $quantity, $_SESSION['user_id'], $itemId);

// Execute the statement
if ($stmt->execute()) {
    // Item quantity updated successfully
    http_response_code(200);
    echo json_encode(array("message" => "Item quantity updated in cart"));
} else {
    // Failed to update item quantity
    http_response_code(500);
    echo json_encode(array("error" => "Failed to update item quantity in cart"));
}

// Close the statement and database connection
$stmt->close();
$con->close();

