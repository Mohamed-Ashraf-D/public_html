<?php
$servername = "sql706.main-hosting.eu";
$username = "u259378224_root";
$password = "#Mo1411997as";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>