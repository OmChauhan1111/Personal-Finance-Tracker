<?php
include 'db/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_account'])) {
    $new_account = trim($_POST['new_account']);
    $user_id = $_SESSION['user_id'];

    // Check if the account already exists for the user
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM accounts WHERE user_id = ? AND account_name = ?");
    $check_stmt->execute([$user_id, $new_account]);
    $exists = $check_stmt->fetchColumn();

    if (!$exists) {
        // Insert the new account into the database
        $insert_stmt = $pdo->prepare("INSERT INTO accounts (user_id, account_name) VALUES (?, ?)");
        $insert_stmt->execute([$user_id, $new_account]);

        // Redirect with a success message
        header("Location: personal_dashboard.php?message=Account+created+successfully");
        exit();
    } else {
        // Redirect with an error message
        header("Location: personal_dashboard.php?error=Account+already+exists");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const successMessage = urlParams.get('message');
            const errorMessage = urlParams.get('error');

            if (successMessage) {
                alert(successMessage);
            }

            if (errorMessage) {
                alert(errorMessage);
            }
        };
    </script>
</head>
<body>
    <!-- Your dashboard content goes here -->

</body>
</html>
