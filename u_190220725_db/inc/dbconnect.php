<?php

//set database dettails in variable to make it easier to get/change later on
$db_host = 'localhost';
$db_name = 'u_190220725_db';
$username = 'u-190220725';
$password = 'fieVLrtFtVbWO73';

try {
	//use a try and catch method used to connect to the database using PDO
    $db = new PDO("mysql:dbname=$db_name;host=$db_host", $username, $password); 
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $exception) {
	//throw an error when we are unable to connect to the database
    echo("Connection Error when trying to connect to the database");
    echo($exception->getMessage());
    exit;
}
?>
