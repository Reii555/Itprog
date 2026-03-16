<?php
// database connection file
// @alledelweiss

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'scholarship_db';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

// hello u guys can edit here yes pls do yes 123
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // sample user ID
}
?> 
