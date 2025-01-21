<?php
require 'db.php';
require 'redis.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $query = "SELECT id, password FROM users WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $sessionToken = bin2hex(random_bytes(16));
            $redis->set($sessionToken, $user['id']);
            $redis->expire($sessionToken, 3600);

            echo json_encode(['success' => true, 'sessionToken' => $sessionToken]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    }
}
?>
