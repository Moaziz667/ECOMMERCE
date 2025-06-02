<?php
session_start();
require_once "product.class.php";

// Allow only DELETE requests
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

// Check if the user is an admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get ID from JSON body
$input = json_decode(file_get_contents("php://input"), true);
$id = $input['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'No product ID provided']);
    exit;
}

// Attempt to delete product
if (Product::deleteById($id)) {
    echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete product']);
}
