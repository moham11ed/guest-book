<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (user_id, message) VALUES (?, ?)");
        $stmt->bind_param("is", $user_id, $message);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Message cannot be empty.";
    }
}

$conn->close();
?>
