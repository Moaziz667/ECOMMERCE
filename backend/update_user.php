<?php
//? simle et beauxx
session_start();
require_once "config.php";      
require_once "user.class.php";  

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in.";
    exit;
}

// Get dat from form (POST)
$userId = $_SESSION['user_id'];
$username = $_POST['username'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$last_name = $_POST['last_name'];


$user = new User($conn);
$result = $user->updateUser($userId, $username, $email, $phone, $last_name);

if ($result) {
    echo "User updated successfully!";
} else {
    echo "Failed to update user.";
}
