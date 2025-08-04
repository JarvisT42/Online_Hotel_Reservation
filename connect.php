<?php
$conn = new mysqli("localhost", "root", "", "db_hor");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// $conn = new mysqli("localhost", "u756490121_db_hor", "[lh~?MvOkV5", "u756490121_db_hor");

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
