<?php
$servername = "localhost"; // MySQL server name
$username = "root"; // MySQL username
$password = ""; // Password (leave empty if not set)
$dbname = "simplemensclothing"; // Database name

// Connect to MySQL using MySQLi (object-oriented)
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Set UTF-8 to avoid character encoding issues
$conn->set_charset("utf8");

// Optional: You can log the successful connection if needed
// error_log("✅ MySQL connection successful!");

// Use the connection for your queries here...

// Close the connection when done
// $conn->close();
?>