<?php
// Database configuration
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "jhnvlldb";

// Establish database connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set character set to UTF-8 for proper encoding support
$conn->set_charset("utf8");

?>
