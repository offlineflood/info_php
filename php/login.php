<?php
include 'db.php';
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: chat.php");
            exit;
        } else {
            echo "Yanlış şifrə!";
        }
    } else {
        echo "İstifadəçi tapılmadı!";
    }
}
?>

<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <form method="POST" action="">
        <input type="text" name="username" placeholder="İstifadəçi adı" required>
        <input type="password" name="password" placeholder="Şifrə" required>
        <button type="submit" name="login">Daxil ol</button>
    </form>
</body>
</html>
