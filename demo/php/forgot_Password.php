<?php
require 'db.php';
require 'redis.php';

if (isset($_POST['email']) && !isset($_POST['new_password'])) {
    $email = $_POST['email'];

    $query = "SELECT id FROM users WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $resetToken = bin2hex(random_bytes(16));
        $redis->set("reset:$resetToken", $user['id']);
        $redis->expire("reset:$resetToken", 600);

        echo json_encode(['success' => true, 'resetToken' => $resetToken]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Email not found.']);
    }
}

if (isset($_POST['reset_token']) && isset($_POST['new_password'])) {
    $resetToken = $_POST['reset_token'];
    $newPassword = $_POST['new_password'];

    $userId = $redis->get("reset:$resetToken");

    if ($userId) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        $query = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':password' => $hashedPassword,
            ':id' => $userId,
        ]);

        $redis->del("reset:$resetToken");

        echo json_encode(['success' => true, 'message' => 'Password reset successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid or expired reset token.']);
    }
}
?>
