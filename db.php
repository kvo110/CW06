<?php
// db.php
// This file keeps the database connection in one place so the main page stays cleaner.

// Codd/MySQL settings.
$host = "localhost";
$user = "hvo30";
$password = "hvo30";
$database = "hvo30";

// Create the database connection.
$conn = new mysqli($host, $user, $password, $database);

// If the connection fails, stop the page and show a helpful message.
if ($conn->connect_error) {
  die("Database connection failed: " . $conn->connect_error);
}
?>
