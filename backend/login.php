<?php
    session_start();

    require_once "user.class.php";
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
            }
        catch (PDOException $e){
            echo "ERREUR : ".$e->getMessage(). " LIGNE : ".$e->getLine();
        }
    }
?>
