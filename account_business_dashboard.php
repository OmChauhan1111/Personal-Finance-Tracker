<?php
include 'db/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$accountType = $_GET['account_type'] ?? null;

if (!$accountType) {
    header("Location: account_dashboard.php");
    exit();
}

// Fetch user data for profile display
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch transactions for the selected account
$stmt = $pdo->prepare("SELECT * FROM business_transactions WHERE user_id = ? AND account_type = ?");
$stmt->execute([$user_id, $accountType]);
$transactions = $stmt->fetchAll();

// Initialize totals for income and expense
$totalIncome = 0;
$totalExpense = 0;

foreach ($transactions as $transaction) {
    if ($transaction['category'] === 'Income') {
        $totalIncome += $transaction['amount'];
    } else {
        $totalExpense += $transaction['amount'];
    }
}

$totalAccount = $totalIncome + $totalExpense; // Total can also be used if needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($accountType); ?> Dashboard</title>
    <link rel="stylesheet" href="css/account_dashboard.css">
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


    <div class="dashboard-container">
        <h2><?php echo htmlspecialchars($accountType); ?> Dashboard</h2>

        <div>
            <a href="add_business_transaction.php?account_type=<?php echo urlencode($accountType); ?>" class="btn">Add Transaction</a>
        </div>

        <h3>Your Transactions</h3>
        <table class="transaction-table">
            <tr>
                <th>Date</th>
                <th>Income</th>
                <th>Expense</th>
                <th>Description</th>
                <th>Total</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php foreach ($transactions as $transaction): ?>
            <tr>
                <td><?php echo (new DateTime($transaction['transaction_date']))->format('d-m-Y'); ?></td>
                <td>
                    <?php if ($transaction['category'] === 'Income'): ?>
                        <?php echo number_format($transaction['amount'], 2); ?>
                    <?php else: ?>
                        0.00
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($transaction['category'] === 'Expense'): ?>
                        <?php echo number_format($transaction['amount'], 2); ?>
                    <?php else: ?>
                        0.00
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($transaction['description']); ?></td>
                <td>
                    <?php
                    // Show total based on income/expense category
                    echo $transaction['category'] === 'Income' 
                        ? '+' . number_format($transaction['amount'], 2) 
                        : '-' . number_format($transaction['amount'], 2);
                    ?>
                </td>
                <td>
                    <a href="edit_business_transaction.php?id=<?php echo $transaction['id']; ?>&account_type=<?php echo urlencode($accountType); ?>" class="btn edit-btn">Edit</a>
                </td>
                <td>
                    <form method="POST" action="delete_business_transaction.php" onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                        <input type="hidden" name="transaction_id" value="<?php echo $transaction['id']; ?>">
                        <input type="hidden" name="account_type" value="<?php echo htmlspecialchars($accountType); ?>">
                        <button type="submit" class="btn delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <div class="summary-container">
            <h3>Total Summary for <?php echo htmlspecialchars($accountType); ?></h3>
            <div class="summary-item">
                <p>Total Income: +<?php echo number_format($totalIncome, 2); ?></p>
                <p>Total Expense: -<?php echo number_format($totalExpense, 2); ?></p>
                <p>Net Total: <?php echo number_format($totalAccount, 2); ?></p>
            </div>
        </div>
    </div>
</body>
</html>
