<?php
// database.php
$servername = "172.31.22.43";
$username = "Naman200514073";
$password = "eHc3vApvfw";
$dbname = "Naman200514073";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>