<?php
session_start();

// === CORS HEADERS ===
$allowed_origins = ['http://localhost:5174'];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
} else {
    header("Access-Control-Allow-Origin: http://localhost:5174");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Hide PHP warnings from interfering with JSON output
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

// === Preflight
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit();
}

// === AUTH
if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
    exit();
}

// === INPUT
$data = json_decode(file_get_contents("php://input"), true);
error_log("Received update payload: " . print_r($data, true));

$username   = $data['username'] ?? '';
$email      = $data['email'] ?? '';
$phone      = $data['phone'] ?? '';
$last_name  = $data['last_name'] ?? '';
$userId     = $_SESSION['user_id'] ?? null;

// === DATABASE
require_once "config.php";
require_once "<entities/user.class.php";  // Make sure this path is correct

$cnx = new connexion();
$conn = $cnx->CNXbase();
$user = new User($conn);

// === UPDATE LOGIC
try {
    $result = $user->updateUser($userId, $username, $email, $phone, $last_name);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'User updated successfully!']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Update failed.']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error.', 'details' => $e->getMessage()]);
}
?>
