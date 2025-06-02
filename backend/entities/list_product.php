<?php
session_start();
require_once "product.class.php";

// === CORS Headers ===
$allowed_origins = ['http://localhost:5174'];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
} else {
    header("Access-Control-Allow-Origin: http://localhost:5174");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// === Handle preflight OPTIONS ===
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

// === Only allow GET ===
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit();
}

// === Check if user is admin ===
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

try {
    $products = Product::getAll(); // Make sure this method is defined in your Product class

    echo json_encode([
        'success' => true,
        'data' => $products
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching products',
        'error' => $e->getMessage()
    ]);
}
