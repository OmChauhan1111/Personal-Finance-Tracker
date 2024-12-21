<?php
include 'db/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from the database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('User not found');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <div class="profile-container">
        <h2>User Profile</h2>
        <div class="profile-info">
            <img src="<?php echo !empty($user['photo']) ? htmlspecialchars($user['photo']) : 'assets/profile.png'; ?>" alt="Profile" class="profile-photo">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Account Type:</strong> <?php echo htmlspecialchars($user['account_type']); ?></p>
        </div>
        <div class="form-actions">
            <a href="edit_profile.php" class="link-button">Edit Profile</a>
        </div>
    </div>

    <div class="button-container">
    <a href="personal_dashboard.php" class="link-button">Main Dashboard</a>
    <a href="add_transaction.php" class="link-button">Add Transaction</a>
    <a href="view_transactions.php" class="link-button">Show Transactions</a>
    <a href="logout.php" class="link-button">Logout</a>
</div>

</body>
</html>
