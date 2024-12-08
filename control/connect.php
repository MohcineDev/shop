<?php
//connect to db
$dataSource = 'mysql:host=localhost;dbname=shop';
$user = 'root';
$pass='';
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8',
);

try{
    $connection = new PDO($dataSource, $user,$pass,$option);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo 'Connected';
}
catch(PDOException $a){
echo $a->getMessage();
}