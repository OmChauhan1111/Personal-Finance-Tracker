<?php
include 'db/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user's profile data (e.g., photo and name)
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Handle form submission for adding a transaction
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $date = $_POST['date'];  // Getting the date value from the form
    $description = $_POST['description'];
    $account_type = $_POST['account_type'];  // Getting the account type from the form

    // Insert the transaction into the database
    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, amount, category, transaction_date, description, account_type) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $amount, $category, $date, $description, $account_type]);

    header("Location: personal_dashboard.php");
    exit();
}

// Fetch user-created accounts for the dropdown
$stmt = $pdo->prepare("SELECT account_name FROM accounts WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$accounts = $stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Transaction</title>
    <link rel="stylesheet" type="text/css" href="css/edit_transaction.css">
</head>
<body>
   <div class="btn-container">
        <a href="profile.php" class="circular-btn">
            <?php if (!empty($user['photo'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($user['photo']); ?>" alt="Profile" class="profile-photo">
            <?php else: ?>
                <span class="initial"><?php echo strtoupper(substr(htmlspecialchars($user['name']), 0, 1)); ?></span>
            <?php endif; ?>
        </a>
    </div>


    <div class="transaction-container">
        <h2 class="page-title">Add New Transaction</h2>
        <form method="POST" class="transaction-form">
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" placeholder="Enter amount" required>
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select name="category" id="category" required>
                    <!-- <option value="Income">Income</option> -->
                    <option value="Expense">Expense</option>
                </select>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" required>
            </div>

            <div class="form-group">
                <label for="account_type">Account Type</label>
                <select name="account_type" id="account_type" required>
                    <!-- Dynamic account options from user-created accounts -->
                    <?php if (!empty($accounts)): ?>
                        <?php foreach ($accounts as $account): ?>
                            <option value="<?php echo htmlspecialchars($account); ?>">
                                <?php echo htmlspecialchars($account); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No Accounts Available</option>
                    <?php endif; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" placeholder="Enter description" required></textarea>
            </div>
            
            <button type="submit" class="btn-submit">Add Transaction</button>
        </form>
    </div>
</body>
</html>
