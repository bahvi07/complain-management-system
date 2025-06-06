<?php
// InfinityFree Database Configuration
$host = 'localhost';
$username = 'root';
$password = ''; // Replace with your real password
$database = 'complaint_system';
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