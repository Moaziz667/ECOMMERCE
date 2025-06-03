<?php
session_start();
require_once "../config.php";
header('Content-Type: application/json');
$cnx = new connexion();
$conn = $cnx->CNXbase();
// Only admin can access
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$sql = "SELECT * FROM commande ORDER BY date DESC";
$result = $conn->query($sql);

$orders = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $orders[] = $row;
}

echo json_encode(['success' => true, 'orders' => $orders]);
