<?php
session_start();
require_once "../config.php";

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

// Handle preflight OPTIONS request and exit early
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

// Only allow POST method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit();
}

// Only admin can access
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Database connection
$cnx = new connexion();
$conn = $cnx->CNXbase();

// Get JSON body
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['commande_id']) || !isset($data['status'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing commande_id or status']);
    exit;
}

$commande_id = intval($data['commande_id']);
$status = filter_var($data['status'], FILTER_SANITIZE_STRING);

// Use prepared statements to avoid SQL injection
try {
    $stmt = $conn->prepare("UPDATE commande SET status = ? WHERE commande_id = ?");
    $success = $stmt->execute([$status, $commande_id]);

    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Order status updated']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error', 'error' => $e->getMessage()]);
}
