<?php
require 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'], $_POST['password'], $_POST['favorite_color'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $favorite_color = $_POST['favorite_color'];

    $stmt = $db->prepare('INSERT INTO users (username, password, favorite_color) VALUES (?, ?, ?)');
    $stmt->execute([$username, $password, $favorite_color]);

    // Redirect to login.php
    header('Location: login.php');
    exit;
}
?>

<!-- Registration form -->
<form method="post" action="register.php">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <input type="text" name="favorite_color" placeholder="Favorite Color">
    <input type="submit" value="Register">
</form>