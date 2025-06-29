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
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// === Handle preflight OPTIONS ===
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

// Only allow PUT requests
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

// Auth check: must be admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);
if (!$input || !isset($input['product_id'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Invalid JSON or missing product_id']);
    exit;
}

// Load existing product
$product = Product::findById($input['product_id']);
if (!$product) {
    http_response_code(404); // Not Found
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

// Update values
$updatedProduct = new Product();
$updatedProduct->create_product($input);
$updatedProduct->product_id = $input['product_id'];

if ($updatedProduct->update()) {
    echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to update product']);
}
