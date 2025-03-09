<?php
session_start();
require 'db.php'; 

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = "SELECT messages.id, messages.message, messages.created_at, users.username
              FROM messages
              JOIN users ON messages.user_id = users.id
              WHERE messages.user_id = ? 
              ORDER BY messages.created_at DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);  
    $stmt->execute();
    $result = $stmt->get_result();  
} else {
    echo "You must be logged in to view your messages.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Book</title>
    <link rel="stylesheet" href="./css/profile.css">
  
</head>
<body>
<nav>
    <div class="left">
        <p style="color: white;"><?php echo $_SESSION['username']; ?></p>
    </div>
    <div class="center">
        <a href="all_messages.php">View All Messages</a>
    </div>
    <div class="right">
        <a href="logout.php">Logout</a>
    </div>
</nav>

<?php if (isset($_SESSION['user_id'])): ?>
    <div class="form-container">
    <h1>Guest Book</h1>
        <form action="add_message.php" method="POST">
            <textarea name="message" required placeholder="Write your message here"></textarea><br>
            <button type="submit">Post Message</button>
        </form>
    </div>
<?php else: ?>
    <p><a href="login.php">Login</a> or <a href="register.php">Register</a> to post a message.</p>
<?php endif; ?>


<?php while ($row = $result->fetch_assoc()): ?>
    <br>
    <div class="message-container">
    <div class="message-card">
    <h2>Your Messages</h2>
        <div class="user-info">
            <div class="user-icon">
                <?php echo strtoupper(substr($row['username'], 0, 1)); ?>
            </div>
            <div class="username"><?php echo $row['username']; ?></div>
        </div>
        <div class="created-at"><?php echo $row['created_at']; ?></div>
        
        <div class="message-content">
            
            <p class="message"><?php echo $row['message']; ?></p>
            
            <div class="message-links">
                <a href="edit_message.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                <a href="delete_message.php?id=<?php echo $row['id']; ?>">Delete</a>
            </div>
        </div>
    </div>
    </div>
<?php endwhile; ?>



</body>
</html>
