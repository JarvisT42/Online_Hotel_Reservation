<?php
$conn = new mysqli("localhost", "root", "", "db_hor");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
