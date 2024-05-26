<?php
ini_set('display_errors', '0');
error_reporting(E_ALL);

try{
    $pdo = new PDO('mysql:host=localhost;dbname=wang','wang','&wang;');
}catch(Exception $e){
    die("Erreur : ".$e->getMessage());
}
?>