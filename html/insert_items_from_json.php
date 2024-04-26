<?php

// Database connection
$dbhost = "192.168.20.99";
$dbuser = "root";
$dbpass = "Bobiubuntu1!";
$dbname = "login_sample_DB";

$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Read JSON file
$jsonData = file_get_contents('items.json');

// Parse JSON data
$items = json_decode($jsonData, true);

// Iterate over items and insert into database
foreach ($items as $item) {
    $name = $item['name'];
    $price = $item['price'];
    $description = $item['description'];
    $image = $item['image'];

    // Escape values to prevent SQL injection
    $name = mysqli_real_escape_string($con, $name);
    $description = mysqli_real_escape_string($con, $description);
    $image = mysqli_real_escape_string($con, $image);

    // Insert item into database
    $sql = "INSERT INTO items (item_name, item_price, item_description, item_image) 
            VALUES ('$name', $price, '$description', '$image')";

    if (mysqli_query($con, $sql)) {
        echo "Item inserted successfully: $name <br>";
    } else {
        echo "Error inserting item: " . mysqli_error($con) . "<br>";
    }
}

mysqli_close($con);

