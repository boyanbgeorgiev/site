<?php
// Include the connection script
include 'connection.php';

// Initialize an empty array to store items
$items = array();

// Perform a query to fetch all items
$sql = "SELECT * FROM items";
$result = $conn->query($sql);

// Check if any rows were returned
if ($result->num_rows > 0) {
    // Fetch rows and add them to the $items array
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

// Close the database connection
$conn->close();

// Output the items as JSON
header('Content-Type: application/json');
echo json_encode($items);
