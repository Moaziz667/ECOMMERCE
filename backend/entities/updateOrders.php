<?php
session_start();
require_once "config.php";
header('Content-Type: application/json');

// Only admin can access
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get JSON body
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['commande_id']) || !isset($data['status'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing commande_id or status']);
    exit;
}

$commande_id = intval($data['commande_id']);
$status = $conn->real_escape_string($data['status']);

// Update
$sql = "UPDATE commande SET status = '$status' WHERE commande_id = $commande_id";
if ($conn->query($sql)) {
    echo json_encode(['success' => true, 'message' => 'Order status updated']);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
