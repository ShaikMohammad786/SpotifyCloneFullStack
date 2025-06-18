<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$conn = new mysqli('localhost', 'root', '', 'spotify_clone');
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['songs'])) {
        echo json_encode(['success' => false, 'error' => 'No songs provided']);
        exit;
    }

    $songs_json = json_encode($data['songs']);
    $stmt = $conn->prepare('INSERT INTO user_recently_played (user_id, songs) VALUES (?, ?) ON DUPLICATE KEY UPDATE songs = ?');
    $stmt->bind_param('iss', $user_id, $songs_json, $songs_json);
    $success = $stmt->execute();

    echo json_encode(['success' => $success]);
} elseif ($method === 'GET') {
    $stmt = $conn->prepare('SELECT songs FROM user_recently_played WHERE user_id = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $songs = $row ? json_decode($row['songs'], true) : [];
    echo json_encode($songs);
}

$stmt->close();
$conn->close();
?>