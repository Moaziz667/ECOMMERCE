<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once('entities\user.class.php');
require_once('config.php');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
$us = new user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
        exit();
    }

    $us->email = $data["email"] ?? "";
    $us->pwd = $data["password"] ?? "";
    $us->username = $data["username"] ?? "";
    $us->last_name = $data["last_name"] ?? "";
    $us->phone = $data["phone"] ?? "";
    $us->role = $data["role"] ?? "user";
    //hachage du mdp
    $us->pwd = password_hash($us->pwd, PASSWORD_DEFAULT);


    if ($us->recherche_user() == null) {
        $cnx = new connexion();
        $pdo = $cnx->CNXbase();
        
        
        try {
            $stmt = $pdo->prepare("INSERT INTO user (username, last_name, phone, email, password, role)
            VALUES (:username, :last_name, :phone, :email, :password, :role)");
            $stmt->execute([
                ':username' => $us->username,
                ':last_name' => $us->last_name,
                ':phone' => $us->phone,
                ':email' => $us->email,
                ':password' => $us->pwd,
                ':role' => $us->role
            ]);
            echo json_encode(["status" => "success", "message" => "User registered"]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
        }
    } else {
        http_response_code(409);
        echo json_encode(["status" => "error", "message" => "User already exists"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
}
?>