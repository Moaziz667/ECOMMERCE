<?php
session_start();
require_once "product.class.php";
header("Content-Type: application/json");

// Check if user is logged in and is admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    // Call a static method or instantiate then call method to get products
    $products = Product::getAll(); // You need to implement this method

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
