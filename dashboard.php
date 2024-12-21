<?php
include 'header.php';
include 'db/config.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['account_type'])) {
    header("Location: account_selection.php");
    exit();
}

$account_type = $_SESSION['account_type'];

// Fetch transactions
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? AND account_type = ?");
$stmt->execute([$_SESSION['user_id'], $account_type]);
$transactions = $stmt->fetchAll();

// Calculate total balance
$total = 0;
foreach ($transactions as $transaction) {
    if ($transaction['category'] == 'Income') {
        $total += $transaction['amount'];
    } else {
        $total -= $transaction['amount'];
    }
}

// Handle search/filter functionality
$search = '';
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? AND account_type = ? AND (description LIKE ? OR category LIKE ?)");
    $stmt->execute([$_SESSION['user_id'], $account_type, "%$search%", "%$search%"]);
    $transactions = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $account_type; ?> Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <h2><?php echo $account_type; ?> Dashboard</h2>
        
        <div class="btn-container" style="text-align: right;">
            <a href="add_transaction.php" class="btn">Add Transaction</a>
            <a href="logout.php" class="btn logout-btn">Logout</a>
        </div>

        <div class="search-container">
            <form method="POST" action="">
                <input type="text" name="search" placeholder="Search transactions..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn">Search</button>
            </form>
        </div>

        <h3>Your Transactions</h3>
        <table class="transaction-table">
            <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Category</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($transactions as $transaction): ?>
            <tr>
                <td><?php echo (new DateTime($transaction['transaction_date']))->format('d-m-Y'); ?></td>
                <td><?php echo $transaction['amount']; ?></td>
                <td><?php echo $transaction['category']; ?></td>
                <td><?php echo $transaction['description']; ?></td>
                <td>
                    <a href="edit_transaction.php?id=<?php echo $transaction['id']; ?>" class="action-link">Edit</a>
                    <a href="delete_transaction.php?id=<?php echo $transaction['id']; ?>" class="action-link delete-link" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h3>Total Balance: <span class="total-amount"><?php echo $total; ?></span></h3>

        <div class="export-buttons">
            <a href="generate_pdf.php" class="btn">Download PDF</a>
            <a href="share.php" class="btn">Share</a>
        </div>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
