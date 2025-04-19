<?php
$conn = new mysqli("localhost", "root", "", "spotify_clone");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM songs ORDER BY id DESC LIMIT 25"; // No 'plays'
$result = $conn->query($sql);

$songs = [];

while ($row = $result->fetch_assoc()) {
    $songs[] = $row;
}

header('Content-Type: application/json');
echo json_encode($songs);
$conn->close();
?>
