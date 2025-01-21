<?php
require 'db.php';
require 'redis.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['sessionToken'])) {
        $sessionToken = $_POST['sessionToken'];
        $userId = $redis->get($sessionToken);

        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'Invalid session.']);
            exit;
        }

        if (isset($_POST['name']) && isset($_POST['dob']) && isset($_POST['phone']) && isset($_POST['gender'])) {
            $name = $_POST['name'];
            $dob = $_POST['dob'];
            $phone = $_POST['phone'];
            $gender = $_POST['gender'];

            $query = "UPDATE users SET name = :name, dob = :dob, phone = :phone, gender = :gender WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':name' => $name,
                ':dob' => $dob,
                ':phone' => $phone,
                ':gender' => $gender,
                ':id' => $userId,
            ]);

            echo json_encode(['success' => true, 'message' => 'Profile updated successfully.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Session token missing.']);
    }
} else {
    if (isset($_GET['sessionToken'])) {
        $sessionToken = $_GET['sessionToken'];
        $userId = $redis->get($sessionToken);

        if ($userId) {
            $query = "SELECT name, email, dob, phone, gender FROM users WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['id' => $userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid session.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Session token missing.']);
    }
}
?>
