<?php
// Include the database connection script
include 'itemconnection.php';

// Check if the 'id' parameter exists in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare a SQL query to fetch the remaining quantity by ID
    $sql = "SELECT quantity FROM items WHERE item_id = $id";
    $result = $conn->query($sql);

    // Check if any rows were returned
    if ($result->num_rows > 0) {
        // Fetch the remaining quantity
        $row = $result->fetch_assoc();
        $remaining_quantity = $row['quantity'];
        
        // Check how much of the item users have in their carts
        $sql_cart = "SELECT SUM(quantity) AS total_quantity FROM cart WHERE item_id = $id";
        $result_cart = $conn->query($sql_cart);
        if ($result_cart->num_rows > 0) {
            $row_cart = $result_cart->fetch_assoc();
            $cart_quantity = $row_cart['total_quantity'];
        } else {
            $cart_quantity = 0;
        }

        // Calculate and return the remaining quantity
        $remaining_quantity -= $cart_quantity;
        echo json_encode(array("remaining_quantity" => $remaining_quantity));
    } else {
        echo json_encode(array("error" => "Item not found"));
    }
} else {
    echo json_encode(array("error" => "ID parameter missing"));
}
?>
