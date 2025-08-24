<?php

// Detect if running on localhost
$is_local = (
    strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
    strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false
);

// echo $is_local ? "Localhost" : "Remote server";

// Set database connection settings based on environment
if ($is_local) {
    // Localhost settings
    $conn = new mysqli("localhost", "root", "", "db_hor");
} else {
    // Remote server settings
    $conn = new mysqli("localhost", "u756490121_db_hor", "[lh~?MvOkV5", "u756490121_db_hor");
}

// Check connection once
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
