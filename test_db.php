<?php
// Database credentials
$host = "localhost";
$username = "u756490121_db_hor";
$password = "[lh~?MvOkV5";
$database = "u756490121_db_hor";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
} else {
    echo "✅ Connection successful!";
}

// Close connection
$conn->close();
?>
