<?php
require_once 'conn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'], $_POST['new_password'], $_POST['favorite_color'])) {
    $username = $_POST['username'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $favorite_color = $_POST['favorite_color'];

    $stmt = $db->prepare('SELECT * FROM users WHERE username = ? AND favorite_color = ?');
    $stmt->execute([$username, $favorite_color]);
    $user = $stmt->fetch();

    if ($user) {
        $stmt = $db->prepare('UPDATE users SET password = ? WHERE username = ?');
        $stmt->execute([$new_password, $username]);

        // Redirect to index.php
        header('Location: index.php');
        exit;
    } else {
        // Invalid credentials
        $error = "Invalid username or favorite color.";
    }
}
?>

<!-- Password recovery form -->
<form method="post" action="recover.php">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="new_password" placeholder="New Password" required>
    <input type="text" name="favorite_color" placeholder="Favorite Color" required>
    <input type="submit" value="Recover">
</form>

<?php
if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}
?>
