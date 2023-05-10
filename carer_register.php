<?php

// Include the database connection
require_once 'setup.php';

// Prevent output before header
ob_start();

// Get the JSON request body
$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

// Store user details
$id = $_POST['carer_id'];
$name = $_POST['carer_name'];
$username = $_POST['carer_user'];

// Validate user details
$result = $mysqli->query("SELECT carer_id FROM official_carers WHERE carer_name = $name");
if($result != $id) {
    header("location: registration.html");
    exit();
} else {
    // Check that username is unique
    $result = $mysqli->query("SELECT carer_username FROM carer_logins WHERE carer_username = $username");
    if($result == $username) {
	header("location: registration.html");
	exit();
    } else {
        // Insert the new item into the database
        $stmt = $mysqli->prepare('INSERT INTO carer_logins (carer_id, carer_name, carer_age, carer_gender, carer_phone,
        carer_username, carer_password) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sii', $_POST['carer_id'], $_POST['carer_name'], $_POST['carer_age'], $_POST['carer_gender'], $_POST['carer_phone'],
        $_POST['carer_user'], $_POST['carer_pass']);
        $stmt->execute();
	header("location: Homepage.html");
	exit();
    }
}