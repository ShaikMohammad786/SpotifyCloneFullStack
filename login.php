<?php
$host = "localhost";
$db = "spotify_clone";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();

$emailOrUsername = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = ? OR username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $emailOrUsername, $emailOrUsername);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        echo "success";
    } else {
        echo "invalid_password";
    }
} else {
    echo "user_not_found";
}

$conn->close();
?>
