<?php
session_start();
require_once "entities/product.class.php";

// Check if the user is an admin (assuming session-based auth)
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Read JSON body
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit;
}

// Create and save product
$product = new Product();
$product->create_product($data);
if ($product->save()) {
    echo json_encode(['success' => true, 'message' => 'Product added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add product']);
}
