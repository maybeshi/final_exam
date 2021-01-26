<?php
function connectDB():PDO{
    $dsn = "mysql:host=localhost;dbname=productdb;charset=utf8";
    $user = "productdb_admin";
    $password = "admin123";
    
    $pdo =new PDO($dsn,$user,$password);
    return $pdo;
}
?>