<?php
/* Database credentials. Assuming you are running MySQL server */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'phpmyadmin');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'blog');

/* Attempt to connect to MySQL database */
try{
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $GLOBALS['conn'] = $pdo;

} catch(PDOException $e){
    $GLOBALS['e'] = $e;
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>