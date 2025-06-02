<?php
session_start();
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");  // Adjust origin if needed
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

require_once "user.class.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed"]);
    exit();
}

$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['login']) || !isset($input['pwd'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit();
}

$us = new User();
$us->email = $input["login"];
$us->pwd = $input["pwd"];

try {
    $res = $us->recherche_user();

    if ($res) {
        $hashedPasswordFromDB = $res['password'];
        
        if (password_verify($us->pwd, $hashedPasswordFromDB)) {
            $_SESSION["connecte"] = "1";
            $_SESSION["user"] = $res["username"];
            $_SESSION["role"] = $res["role"];

            http_response_code(200);
            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "session" => [
                    "username" => $res["username"],
                    "role" => $res["role"]
                ]
            ]);
        } else {
            http_response_code(401); // Unauthorized
            echo json_encode(["status" => "error", "message" => "Incorrect password , $us->pwd -- $hashedPasswordFromDB"]);
        }
    } else {
        http_response_code(404); // Not found
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error", "details" => $e->getMessage()]);
}
?>