<?php

$host = "localhost";
$dbname = "hotel";
$username = "root";
$password = "07072105145";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  
    

} catch(PDOException $e) {

    die("Connection Failed: " . $e->getMessage());

}

?>