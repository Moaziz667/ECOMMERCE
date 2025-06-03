<?php
session_start();
require_once "../config.php";
require_once "user.class.php";

header('Content-Type: application/json');

// Database connection
$cnx = new connexion();
$conn = $cnx->CNXbase();

// Check if user is logged in
if (!isset($_SESSION['login'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
    exit;
}

// Option 1: Get user ID from session
$userId = $_SESSION['user_id'] ?? 2; // fallback to 2 for testing

// Option 2: Or use GET param ?id=2
// $userId = isset($_GET['id']) ? intval($_GET['id']) : null;

$user = new User($conn);
$userData = $user->getUserById($userId);

if ($userData) {
    echo json_encode(['success' => true, 'user' => $userData]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}
