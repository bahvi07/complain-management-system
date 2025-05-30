<?php
// InfinityFree Database Configuration
$host = 'sql107.infinityfree.com';
$username = 'if0_39107611';
$password = 'kr8IQWPRrD'; // Replace with your real password
$database = 'if0_39107611_complaint_db';
$port = 3306;

// Create connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Connection failed: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8");
?>