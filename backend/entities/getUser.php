<?php
session_start();
require_once "../config.php";
require_once "user.class.php";

// === CORS Headers ===
$allowed_origins = ['http://localhost:5174'];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
} else {
    header("Access-Control-Allow-Origin: http://localhost:5174");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

// === Original Logic ===
// Database connection
$cnx = new connexion();
$conn = $cnx->CNXbase();

// Check if user is logged in
if (!isset($_SESSION['login'])) {
    echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
    exit;
}

// Option 1: Get user ID from session
$userId = $_SESSION['user_id']; // fallback to 2 for testing

// Option 2: Or use GET param ?id=2
// $userId = isset($_GET['id']) ? intval($_GET['id']) : null;

$user = new User($conn);
$userData = $user->getUserById($userId);

if ($userData) {
    echo json_encode(['success' => true, 'user' => $userData]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
}
