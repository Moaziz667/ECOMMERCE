<?php
session_start();
require_once "config.php";

// === CORS Headers (added only) ===
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

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

// === Original Script ===
$cnx = new connexion();
$conn = $cnx->CNXbase(); 
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['user_id']) || !isset($data['products']) || !is_array($data['products'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$user_id = intval($data['user_id']);
$products = $data['products'];

$total_price = 0;
$valid_products = [];

// Step 1: Calculate total price
foreach ($products as $item) {
    $product_id = intval($item['product_id']);
    $quantity = intval($item['quantity']);

    $stmt = $conn->prepare("SELECT prix FROM product WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $total_price += $row['prix'] * $quantity;
        $valid_products[] = ['product_id' => $product_id, 'quantity' => $quantity];
    }
}

// Step 2: Insert into `commande`
$date = date('Y-m-d');
$status = 'pending';

$stmt = $conn->prepare("INSERT INTO commande (date, status, prix_total, user_id) VALUES (?, ?, ?, ?)");
if (!$stmt->execute([$date, $status, $total_price, $user_id])) {
    echo json_encode(['success' => false, 'message' => 'Failed to insert commande']);
    exit;
}

$commande_id = $conn->lastInsertId(); // Récupère l'ID de la commande insérée

// Step 3: Insert into `product_commande`
$stmt = $conn->prepare("INSERT INTO product_commande (commande_id, product_id, quantity) VALUES (?, ?, ?)");
foreach ($valid_products as $item) {
    $stmt->execute([$commande_id, $item['product_id'], $item['quantity']]);
}

echo json_encode(['success' => true, 'commande_id' => $commande_id]);
