<?php
$servername = "localhost";
$username = "price_fetch_view";
$password = "myviewpass";
$dbname = "PRICE_FETCH";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>