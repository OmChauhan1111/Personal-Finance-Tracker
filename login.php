<?php
include 'db/config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: account_selection.php"); // Redirect to account selection
        exit();
    } else {
        echo "<p style='color: red;'>Invalid credentials!</p>"; // Display error message
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="profile-container">
                <img src="assets/profile.png" alt="Profile Image" class="profile-img">
            </div>
            <h2>Login</h2>
            <form method="POST" action="login.php">
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="login-btn">Login</button>
                <a href="#" class="forgot-password">Forgot Password?</a>
            </form>
        </div>
    </div>
</body>
</html>
