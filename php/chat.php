<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Bütün istifadəçiləri gətir
$sql = "SELECT id, username FROM users WHERE id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$users = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <title>Chat Sistemi</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .user-list { float: left; width: 20%; border-right: 1px solid #ddd; }
        .chat-box { float: left; width: 75%; padding: 10px; }
        .message { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="user-list">
        <h3>İstifadəçilər</h3>
        <ul>
            <?php while ($user = $users->fetch_assoc()): ?>
                <li><a href="chat.php?receiver_id=<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></a></li>
            <?php endwhile; ?>
        </ul>
    </div>

    <div class="chat-box">
        <?php
        if (isset($_GET['receiver_id'])):
            $receiver_id = $_GET['receiver_id'];

            // Mesajları gətir
            $sql = "SELECT * FROM messages 
                    WHERE (sender_id = ? AND receiver_id = ?)
                    OR (sender_id = ? AND receiver_id = ?)
                    ORDER BY timestamp";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiii", $user_id, $receiver_id, $receiver_id, $user_id);
            $stmt->execute();
            $messages = $stmt->get_result();
        ?>

        <div>
            <?php while ($msg = $messages->fetch_assoc()): ?>
                <div class="message">
                    <strong><?= $msg['sender_id'] == $user_id ? 'Siz:' : 'Onun:' ?></strong>
                    <?= htmlspecialchars($msg['message']) ?>
                </div>
            <?php endwhile; ?>
        </div>

        <form method="POST" action="send_message.php">
            <input type="hidden" name="receiver_id" value="<?= $receiver_id ?>">
            <textarea name="message" placeholder="Mesajınızı yazın..." required></textarea>
            <button type="submit">Göndər</button>
        </form>

        <?php else: ?>
            <p>Bir istifadəçi seçin.</p>
        <?php endif; ?>
    </div>
</body>
</html>
