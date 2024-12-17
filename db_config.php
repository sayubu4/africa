<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$db_username = 'eunice.sayubu';
$db_password = 'sayubueunice';
$dbname = 'webtech_fall2024_eunice_sayubu';


// Create connection
$conn = new mysqli($host, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>