<?php
$host = "localhost";
$db = "spotify_clone";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirmPassword = $_POST['cpassword'];

if ($password !== $confirmPassword) {
    echo "password_mismatch";
    exit();
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Check if email already exists
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "email_exists";
} else {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}

$conn->close();
?>
