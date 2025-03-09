<?php
session_start();
require 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message_id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM messages WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $message_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $delete_stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $delete_stmt->bind_param("i", $message_id);

    if ($delete_stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $delete_stmt->error;
    }

    $delete_stmt->close();
} else {
    echo "Message not found or you don't have permission to delete it.";
}

$stmt->close();
$conn->close();
?>
