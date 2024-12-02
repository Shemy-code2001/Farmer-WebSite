<?php
try{
    $con = new PDO("mysql:host=localhost;dbname=ecommerce_project","root","");
}catch(PDOException $e){
    echo "Ereeur de connexion" .$e->getMessage();
}
?>