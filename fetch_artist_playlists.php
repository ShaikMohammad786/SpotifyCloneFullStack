<?php
header('Content-Type: application/json');


$host = "localhost";
$user = "root";
$pass = ""; 
$db = "spotify_clone";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(['error' => 'DB Connection failed: ' . $conn->connect_error]);
    exit;
}

if (isset($_GET['artist'])) {
    $artist = $_GET['artist'];
    $stmt = $conn->prepare("SELECT * FROM songs WHERE artist = ?");
    $stmt->bind_param("s", $artist);
    $stmt->execute();
    $result = $stmt->get_result();

    $songs = [];
    while ($row = $result->fetch_assoc()) {
        $songs[] = $row;
    }

    echo json_encode($songs);
    exit();
}

$sql = "SELECT * FROM songs GROUP BY artist DESC LIMIT 25";
$sql = "SELECT 
            s.artist, 
            COUNT(*) as song_count,
            (SELECT cover_url FROM songs WHERE artist = s.artist LIMIT 1) as cover_url
        FROM songs s
        GROUP BY s.artist 
        ORDER BY song_count DESC 
        LIMIT 25";
$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(['error' => 'Query failed: ' . mysqli_error($conn)]);
    exit;
}

$artists = [];
while ($row = mysqli_fetch_assoc($result)) {
    $artists[] = $row;
}

echo json_encode($artists);
?>