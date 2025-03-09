<?php
session_start();
require 'db.php'; 

$query = "SELECT messages.id, messages.message, messages.created_at, users.username, messages.user_id
          FROM messages
          JOIN users ON messages.user_id = users.id
          ORDER BY messages.created_at DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Messages</title>
    <link rel="stylesheet" href="./css/profile.css">
</head>
<body>

<nav>
    <div class="left">
        <p style="color: white;"><?php echo $_SESSION['username']; ?></p>
    </div>
    <div class="center">
        <a href="index.php">Leave your message</a>
    </div>
    <div class="right">
        <a href="logout.php">Logout</a>
    </div>
</nav>

<br>
<br>
<?php while ($row = $result->fetch_assoc()): ?>
    <div class="message-container">
    <div class="message-card">

        <div class="user-info">
            <div class="user-icon">
                <?php echo strtoupper(substr($row['username'], 0, 1)); ?>
            </div>
            <div class="username"><?php echo $row['username']; ?></div>
        </div>
        <div class="created-at"><?php echo $row['created_at']; ?></div>
        
        <div class="message-content">
            <p class="message"><?php echo $row['message']; ?></p>
            
            
        </div>
    </div>
    </div>
<?php endwhile; ?>


</body>
</html>

<?php
$conn->close();
?>
