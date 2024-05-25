<?php
require_once 'conn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'], $_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();


    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, create session
        $_SESSION['user'] = $user;
        // Redirect to index.php    
        header('Location: index.php');
        exit;
    } else {
        // Invalid credentials
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .center-text {
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Login form -->
    <form method="post" action="login.php">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>
    <!-- Display error message if there is one -->
    <?php
    if (isset($error)) {
        echo "<p class='error'>$error</p>";
    }
    ?>
    <!-- Link to register.php -->
    <p class="center-text">Don't have an account? <a href="register.php">Register</a></p>
    <!-- Link to recover.php -->
    <p class="center-text">Forgot your password? <a href="recover.php">Recover</a></p>
</body>
</html>
