<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connection.php';

// Check if itemId and newQuantity are set in the POST request
if(isset($_POST['itemId']) && isset($_POST['newQuantity'])) {
    // Retrieve itemId and newQuantity from the request
    $itemId = $_POST['itemId'];
    $newQuantity = $_POST['newQuantity'];

    // Update the SQL table
    $sql = "UPDATE cart SET quantity = ? WHERE item_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $newQuantity, $itemId);
    $stmt->execute();

    // Check for errors
    if ($stmt->error) {
        // Log the error
        error_log("MySQL Error: " . $stmt->error);

        // Return failure response
        http_response_code(500);
        echo json_encode(['message' => 'Failed to remove item from cart']);
    } else {
        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            // Return success response
            http_response_code(200);
            echo json_encode(['message' => 'Item removed from cart successfully']);
        } else {
            // Return failure response
            http_response_code(500);
            echo json_encode(['message' => 'Failed to remove item from cart']);
        }
    }

    $stmt->close();
} else {
    // If itemId or newQuantity are not set in the POST request
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'Missing itemId or newQuantity in the request']);
}

$con->close();
?>
