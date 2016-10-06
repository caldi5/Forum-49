<?php
$servername = "sql.srv247.se";
$username = "root";
$password = "Lunar.1471!";
$dbname = "dva231";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    $error = "Connection failed: " . $conn->connect_error;
    die("Connection failed: " . $conn->connect_error);
    $conn->close();
}
?>