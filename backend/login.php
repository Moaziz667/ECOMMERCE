<?php
    session_start();

    require_once "user.class.php";
    require_once "entities\user.class.php";
    $us=new user();
    if (isset($_POST['login'])){
        $us->email =$_POST["login"];
        $us->pwd =$_POST["pwd"];
        try{
            $res = $us->getUser();         
            $data = $res->fetchAll(PDO::FETCH_ASSOC);            
            if ($data){
                $_SESSION["connecte"]="1";
                $_SESSION["user"]=$data[0]["user"];
                // add header here !
                //header("location:liste_etudiant.php");
                exit();
            }
            else
                echo "aucun utilisateur";
        try{
            $res = $us->recherche_user();         
            if ($res){
                $hashedPasswordFromDB = $res['password'];
                if(password_verify($us->pwd, $hashedPasswordFromDB))
                {
                    $_SESSION["connecte"]="1";
                    $_SESSION["user"]=$res["user"];
                    
                    if($res["role"]=='user'){
                        // add header here !
                        header("location:user_login_succes.php");
                    }
                    else
                        header("location:admin_login_succes.php");
                    
                    exit();
            }
            }
            else
                echo "aucun utilisateur";
                // add login page header !!!
            }
        catch (PDOException $e){
            echo "ERREUR : ".$e->getMessage(). " LIGNE : ".$e->getLine();
        }
    }}
?>
