<?php
// Check if we're on Heroku
if (isset($_ENV['DATABASE_URL'])) {
    // Heroku database configuration
    $url = parse_url($_ENV['DATABASE_URL']);
    $host = $url['host'];
    $username = $url['user'];
    $password = $url['pass'];
    $database = ltrim($url['path'], '/');
    $port = $url['port'];
} else {
    // Local XAMPP configuration
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'your_database_name'; // Replace with your actual database name
    $port = 3306;
}

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>