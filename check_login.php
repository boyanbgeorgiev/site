<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $response = array(
        "logged_in" => true,
        "username" => $_SESSION['user_name']
    );
} else {
    $response = array("logged_in" => false);
}

header('Content-Type: application/json');
echo json_encode($response);
?>
