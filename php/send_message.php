<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'];
$message = $_POST['message'];

// Mesajı bazaya əlavə et
$sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $user_id, $receiver_id, $message);
$stmt->execute();

header("Location: chat.php?receiver_id=$receiver_id");
exit;
?>
