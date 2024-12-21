<?php
include 'db/config.php';
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit();
}

// Fetch transactions
$stmt = $pdo->prepare("SELECT * FROM business_transactions WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; // Ensure $transactions is an array

// Initialize search term and filter variables
$searchTerm = isset($_POST['search']) ? $_POST['search'] : '';
$filterCategory = isset($_POST['filter_category']) ? $_POST['filter_category'] : '';
$filterDate = isset($_POST['filter_date']) ? $_POST['filter_date'] : '';

// Apply filters if any
if ($searchTerm || $filterCategory || $filterDate) {
    $sql = "SELECT * FROM business_transactions WHERE user_id = ?";
    $params = [$_SESSION['user_id']];

    if ($searchTerm) {
        $sql .= " AND (description LIKE ? OR category LIKE ?)";
        $params[] = "%$searchTerm%";
        $params[] = "%$searchTerm%";
    }

    if ($filterCategory) {
        $sql .= " AND category = ?";
        $params[] = $filterCategory;
    }

    if ($filterDate) {
        $sql .= " AND DATE(transaction_date) = ?";
        $params[] = $filterDate;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Transactions</title>
    <link rel="stylesheet" type="text/css" href="css/view_transactions.css">
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
        <h2>View Transactions</h2>

        
        
        <!-- Search and Filter Section -->
        <div class="filter-container">
            <form method="POST" action="" class="search-form">
                <input type="text" name="search" placeholder="Search by description or category" value="<?php echo htmlspecialchars($searchTerm); ?>">
                <select name="filter_category" id="filter_category">
                    <option value="">Filter by Category</option>
                    <option value="Income" <?php echo ($filterCategory === 'Income') ? 'selected' : ''; ?>>Income</option>
                    <option value="Expense" <?php echo ($filterCategory === 'Expense') ? 'selected' : ''; ?>>Expense</option>
                </select>
                <input type="date" name="filter_date" id="filter_date" value="<?php echo htmlspecialchars($filterDate); ?>">
                <button type="submit" class="btn">Search & Filter</button>
            </form>
        </div>

        <!-- Display Transactions (Filtered Results) -->
        <h3>Your Transactions</h3>
        <table class="transaction-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Category</th>
                    <th>Account Type</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($transactions)): ?>
                    <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?php echo (new DateTime($transaction['transaction_date']))->format('d-m-Y'); ?></td>
                        <td><?php echo htmlspecialchars($transaction['amount']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['category']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['account_type']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['description']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="no-transactions">No transactions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
