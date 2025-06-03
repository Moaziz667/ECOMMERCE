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
header("Access-Control-Allow-Methods: GET, OPTIONS");
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

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit();
}

// === AUTH
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

require_once "../config.php";

try {
    $cnx = new connexion();
    $conn = $cnx->CNXbase();

    // Fetch all orders
    $sql = "SELECT * FROM commande ORDER BY date DESC";
    $stmt = $conn->query($sql);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare statement to get products for an order
    $sqlProducts = "SELECT 
        p.product_id, 
        p.libelle, 
        p.description, 
        p.prix, 
        p.category, 
        p.image_url, 
        pc.quantity 
    FROM product_commande pc
    JOIN product p ON pc.product_id = p.product_id
    WHERE pc.commande_id = :commande_id";

    $stmtProducts = $conn->prepare($sqlProducts);

    // Attach products to each order
    foreach ($orders as &$order) {
        $stmtProducts->execute(['commande_id' => $order['commande_id']]);
        $products = $stmtProducts->fetchAll(PDO::FETCH_ASSOC);
        $order['products'] = $products;
    }

    echo json_encode(['success' => true, 'orders' => $orders]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error.', 'details' => $e->getMessage()]);
}
