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

if ($result->num_rows == 0) {
    echo "Message not found or you don't have permission to edit it.";
    exit();
}

$row = $result->fetch_assoc();
$message = $row['message'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_message = trim($_POST['message']);

    if (!empty($new_message)) {
        $update_stmt = $conn->prepare("UPDATE messages SET message = ? WHERE id = ?");
        $update_stmt->bind_param("si", $new_message, $message_id);

        if ($update_stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $update_stmt->error;
        }

        $update_stmt->close();
    } else {
        echo "Message cannot be empty.";
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Message</title>
    <link rel="stylesheet" href="./css/profile.css">

</head>
<body>

<h1>Edit Message</h1>
<div class="form-container">
<form action="edit_message.php?id=<?php echo $message_id; ?>" method="POST">
    <textarea name="message" required placeholder="Edit your message here"><?php echo htmlspecialchars($message); ?></textarea><br>
    <button type="submit">Update Message</button>
</form>

    
    </div>
</body>
</html>
