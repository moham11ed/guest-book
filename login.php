
<?php
session_start();
require 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $username, $hashed_password);
        $stmt->fetch();
        
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
    
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/loginstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="login-container">

    <form  method="POST">
        <h1>Log In</h1>
        <?php
        if (isset($error)) {
            echo "<p class='error-message'>$error</p>";
        }
        ?>
        <div class="inputs">
            <input  type="email" name="email" placeholder="Email">
            <i class='bx bxs-envelope'></i>
           </div>
        <div class="inputs">
            <input  type="password" name="password" placeholder="Password">
            <i class='bx bxs-lock-alt' ></i>
        </div>
        
        <button  type="submit" class="ptn" >
            <a href="index.php">Log In</a></button>
        <div class="register">
            <p>Donot have an account ?</p><a href="register.php">Register</a>
        </div>
    </form>
    </div>
    

</body>
</html>
