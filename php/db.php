<?php
$host = 'localhost';
$db = 'chat_system';
$user = 'root'; // MySQL default istifadəçi adı
$pass = ''; // Şifrənizi qeyd edin

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Bağlantı uğursuz oldu: " . $conn->connect_error);
}
?>
