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

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Method is not POST, return error
    http_response_code(405);
    echo json_encode(array("error" => "Method not allowed"));
    exit;
}

// Get the item ID from the request body
$data = json_decode(file_get_contents("php://input"));
$itemId = $data->itemId;

// Validate item ID (you can add more validation if needed)
if (!is_numeric($itemId)) {
    // Invalid item ID, return error
    http_response_code(400);
    echo json_encode(array("error" => "Invalid item ID"));
    exit;
}

// Connect to the database
require_once('connection.php');

// Check if the item already exists in the user's cart
$stmt = $con->prepare("SELECT quantity FROM cart WHERE user_id = ? AND item_id = ?");
$stmt->bind_param("ii", $_SESSION['user_id'], $itemId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Item already exists in the cart, update the quantity
    $stmt->bind_result($quantity);
    $stmt->fetch();

    $quantity += 1;

    // Update the quantity in the cart table
    $updateStmt = $con->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND item_id = ?");
    $updateStmt->bind_param("iii", $quantity, $_SESSION['user_id'], $itemId);

    if ($updateStmt->execute()) {
        // Quantity updated successfully
        http_response_code(200);
        echo json_encode(array("message" => "Quantity updated in cart"));
    } else {
        // Failed to update quantity, return error
        http_response_code(500);
        echo json_encode(array("error" => "Failed to update quantity in cart"));
    }

    $updateStmt->close();
} else {
    // Item does not exist in the cart, insert a new row
    $insertStmt = $con->prepare("INSERT INTO cart (user_id, item_id, quantity) VALUES (?, ?, 1)");
    $insertStmt->bind_param("ii", $_SESSION['user_id'], $itemId);

    if ($insertStmt->execute()) {
        // Item added to cart successfully
        http_response_code(200);
        echo json_encode(array("message" => "Item added to cart"));
    } else {
        // Failed to add item to cart, return error
        http_response_code(500);
        echo json_encode(array("error" => "Failed to add item to cart"));
    }

    $insertStmt->close();
}

// Close the statement and database connection
$stmt->close();
$con->close();
?>

