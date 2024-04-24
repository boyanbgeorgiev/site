<?php
// Start the session
session_start();

// Check if the user is logged in
if(isset($_SESSION['user_name'])) {
    // Unset the session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Unset the cookie by setting its expiration time to the past
    setcookie('user_name', '', time() - 3600, '/');

    // Redirect to the index page
    header("Location: index.html");
    exit; // Terminate script execution
} else {
    // If the user is not logged in, redirect to the index page anyway
    header("Location: index.html");
    exit; // Terminate script execution
}
?>
