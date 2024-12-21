<?php
include 'db/config.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$user_id = $_SESSION['user_id'];

// Fetch predefined business accounts (you can add predefined accounts here)
$default_business_accounts = [];

// Fetch custom business accounts from the database
$business_accounts_stmt = $pdo->prepare("SELECT account_name FROM business_accounts WHERE user_id = ?");
$business_accounts_stmt->execute([$user_id]);
$custom_business_accounts = $business_accounts_stmt->fetchAll(PDO::FETCH_COLUMN);

// Merge predefined and custom business accounts
$business_accounts = array_merge($default_business_accounts, $custom_business_accounts);

// Initialize totals and total expenses
$business_totals = [];
$totalBusinessExpenses = 0;

// Calculate total expenses for each business account
foreach ($business_accounts as $account) {
    $stmt = $pdo->prepare("SELECT SUM(amount) AS total FROM business_transactions WHERE user_id = ? AND account_type = ?");
    $stmt->execute([$user_id, $account]);
    $result = $stmt->fetch();
    $business_totals[$account] = $result['total'] ?? 0; // Default to 0 if no results
    $totalBusinessExpenses += $business_totals[$account];
}

// Fetch user details including the profile photo
$stmt = $pdo->prepare("SELECT photo, name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Default profile picture if none is set
$defaultProfilePicture = 'uploads/default_profile.jpg'; // Path to a default profile picture
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Dashboard</title>
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
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <?php include 'business_header.php'; ?>

    <div class="btn-container">
        <a href="profile.php" class="circular-btn">
            <?php if (!empty($user['photo'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($user['photo']); ?>" alt="Profile" class="profile-photo">
            <?php else: ?>
                <span class="initial"><?php echo strtoupper(substr(htmlspecialchars($user['name']), 0, 1)); ?></span>
            <?php endif; ?>
        </a>
    </div>

    <div class="dashboard-container">
        <h2>Business Dashboard</h2>

        <!-- Account Selection -->
        <div class="account-selection">
            <h3>Select a Business Account</h3>
            <form action="account_business_dashboard.php" method="GET">
                <select name="account_type" required>
                    <?php foreach ($business_accounts as $account): ?>
                        <option value="<?php echo htmlspecialchars($account); ?>"><?php echo htmlspecialchars($account); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn">Go to Account</button>
            </form>
        </div>

        <br>

        <!-- New Business Account Creation -->
        <div class="new-account">
            <h3>Create a New Business Account</h3>
            <form action="create_business_account.php" method="POST">
                <input type="text" name="new_account" placeholder="Account Name" required>
                <button type="submit" class="btn">Create Account</button>
            </form>
        </div>

        <br>

        <!-- Business Account Deletion -->
        <div class="delete-account">
            <h3>Select Business Account to Delete</h3>
            <form action="delete_business_account.php" method="POST">
                <select name="account_to_delete" required>
                    <?php foreach ($custom_business_accounts as $account): ?>
                        <option value="<?php echo htmlspecialchars($account); ?>"><?php echo htmlspecialchars($account); ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn">Delete Account</button>
            </form>
        </div>

        <br>

        <!-- Financial Overview -->
        <h3>Financial Overview</h3>
        <table class="overview-table">
            <tr>
                <th>Account</th>
                <th>Total Expense + Income</th>
            </tr>
            <?php foreach ($business_accounts as $account): ?>
            <tr>
                <td><?php echo htmlspecialchars($account); ?></td>
                <td><?php echo number_format($business_totals[$account], 2); ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <th>Total</th>
                <th><?php echo number_format($totalBusinessExpenses, 2); ?></th>
            </tr>
        </table>
    </div>
</body>
</html>
