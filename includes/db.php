<?php
// Database Configuration

if (in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1'])) {
    $host = "localhost";   // Change if using a remote DB
    $username = "root";    // Your MySQL username
    $password = "";        // Your MySQL password (empty for XAMPP)

} else {
    $host = "boughida.com";   // Change if using a remote DB
    $username = "boughida_nazim";    // Your MySQL username
    $password = "Azerty@20252025";        // Your MySQL password (empty for XAMPP)

}


$dbname = "inventory_management_system"; // Your database name


// Create a connection using MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Check if connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set character set to UTF-8
$conn->set_charset("utf8");

// Uncomment to test the connection
// echo "Database Connected Successfully!";
