<?php        
class User
{
public $username;
public $last_name;
public $phone;    
public $email;
public $pwd;
public $role;
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

} ?>
