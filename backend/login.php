<?php
session_start();

// Allowed origins for CORS
$allowed_origins = ['http://localhost:5174'];

// Check the Origin header and echo it back if allowed
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
} else {
    // Optionally deny or fallback to a safe origin
    header("Access-Control-Allow-Origin: http://localhost:5174");
}

// Allow cookies and credentials
header("Access-Control-Allow-Credentials: true");

// Allow POST and OPTIONS methods for CORS preflight
header("Access-Control-Allow-Methods: POST, OPTIONS");

// Allow specific headers
header("Access-Control-Allow-Headers: Content-Type");

// Handle preflight OPTIONS request
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit();
}

// Only allow POST requests for login
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["status" => "error", "message" => "Method Not Allowed"]);
    exit();
}

header("Content-Type: application/json");

// Read JSON input
$input = json_decode(file_get_contents("php://input"), true);

if (!$input || !isset($input['login']) || !isset($input['pwd'])) {
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit();
}

require_once "user.class.php";

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
            echo json_encode(["status" => "error", "message" => "Incorrect password"]);
        }
    } else {
        http_response_code(404); // Not found
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["status" => "error", "message" => "Database error", "details" => $e->getMessage()]);
}
?>
