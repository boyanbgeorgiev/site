<?php
$dbhost = "192.168.20.99";
$dbuser = "root";
$dbpass = "Bobiubuntu1!";
$dbname = "login_sample_DB";

// Create connection
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch items from the items table
$sql = "SELECT * FROM items";
$result = $conn->query($sql);