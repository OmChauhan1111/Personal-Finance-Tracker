<?php
include 'db/config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle POST request for creating a new business account
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_account'])) {
    $new_account = trim($_POST['new_account']);
    $user_id = $_SESSION['user_id'];

    // Check if the account already exists for the user
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM business_accounts WHERE user_id = ? AND account_name = ?");
    $check_stmt->execute([$user_id, $new_account]);
    $exists = $check_stmt->fetchColumn();

    if (!$exists) {
        // Insert the new account into the database
        $insert_stmt = $pdo->prepare("INSERT INTO business_accounts (user_id, account_name) VALUES (?, ?)");
        $insert_stmt->execute([$user_id, $new_account]);

        // Redirect with a success message
        header("Location: business_dashboard.php?message=Business+Account+created+successfully");
        exit();
    } else {
        // Redirect with an error message
        header("Location: business_dashboard.php?error=Business+Account+already+exists");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Business Account</title>
    <link rel="stylesheet" href="css/dashboard.css">
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
    <div class="dashboard-container">
        <h2>Create a New Business Account</h2>
        <form action="create_business_account.php" method="POST" class="account-form">
            <input type="text" name="new_account" placeholder="Account Name" required class="input-field">
            <button type="submit" class="btn">Create Account</button>
        </form>
    </div>
</body>
</html>
