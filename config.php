<?php
// config.php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'bsit-2e';

$connection = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
