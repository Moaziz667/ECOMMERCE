<?php        
class User
{
public $username;
public $last_name;
public $phone;    
public $email;
public $pwd;
public $role;
private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
function recherche_user()
{
    require_once('config.php');
    $cnx = new connexion();
    $pdo = $cnx->CNXbase();
    $req = "SELECT * FROM user WHERE email = '$this->email'";
    $res = $pdo->query($req) or print_r($pdo->errorInfo());
    $user = $res->fetch(PDO::FETCH_ASSOC); 
    return $user ? $user : null;
}

public function updateUser($id, $username, $email, $phone, $last_name) {
        try {
            $sql = "UPDATE user SET username = :username, email = :email, phone = :phone, last_name = :last_name WHERE user_id = :id";
            $stmt = $this->conn->prepare($sql);

            return $stmt->execute([
                ':username'   => $username,
                ':email'      => $email,
                ':phone'      => $phone,
                ':last_name'  => $last_name,
                ':id'         => $id
            ]);
        } catch (PDOException $e) {
            // Optionally log error: error_log($e->getMessage());
            return false;
        }
    }
    public function getUserById($id) {
    try {
        $sql = "SELECT user_id, username, email, phone, last_name FROM user WHERE user_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // error_log($e->getMessage());
        return false;
    }
}


} ?>
