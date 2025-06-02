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
header("Access-Control-Allow-Methods: DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// === Handle preflight OPTIONS ===
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

// === Allow DELETE only ===
if ($_SERVER["REQUEST_METHOD"] !== "DELETE") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method Not Allowed"]);
    exit();
}

// === Auth Check ===
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// === Read JSON ===
$input = json_decode(file_get_contents("php://input"), true);
$id = $input['id'] ?? null;

if (!$id) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'No product ID provided']);
    exit;
}

// === Delete product ===
if (Product::deleteById($id)) {
    echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to delete product']);
}
