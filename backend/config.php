<?php
class connexion
{
public function CNXbase()
{
$dbc=new PDO('mysql:host=localhost:3307;dbname=sportify'
,
'root'
,'');
return $dbc;
}
}
?>