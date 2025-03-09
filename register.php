<?php

session_start();
require 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $password);
    
    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    
      <link rel="stylesheet" href="./css/loginstyle.css">
      <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <div class="login-container">

        <?php
        if (isset($error)) {
            echo "<p class='error-message'>$error</p>";
        }
        ?>

        <form method="POST">
             <h1>Create Account</h1>
            <div class="inputs">
                <input  type="text" placeholder="user name" name="username" required >
                <i class='bx bxs-user'></i>           
            </div>

            <div class="inputs">
            <input  type="email" name="email" placeholder="Email" required>
            <i class='bx bxs-envelope'></i>
            </div>
                <div class="inputs">
                    <input  type="password" name="password" placeholder="Password" required>
                    <i class='bx bxs-lock-alt' ></i>
                </div>
                <button type="submit" class="ptn" name="register">Register</button>

                <div class="register">
                <p class="login-link">Already have an account? <a href="login.php">Log in</a></p>
                </div>
        </form>

    </div>

</body>
</html>
