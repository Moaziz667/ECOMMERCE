<?php
session_start();
require_once "config.php";      
require_once "entities/user.class.php";  

header('Content-Type: application/json');
$cnx = new connexion();
$conn = $cnx->CNXbase();
// Check if user is logged in
if (!isset($_SESSION['login'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
    exit;
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'] ?? null;
$email = $data['email'] ?? null;
$phone = $data['phone'] ?? null;
$last_name = $data['last_name'] ?? null;
$userId= 2;
//$userId = $_SESSION['user_id'];

$user = new User($conn);
$result = $user->updateUser($userId, $username, $email, $phone, $last_name);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'User updated successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update user.']);
}
