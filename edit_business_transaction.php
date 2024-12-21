<?php
include 'db/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$transaction_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM business_transactions WHERE id = ? AND user_id = ?");
$stmt->execute([$transaction_id, $_SESSION['user_id']]);
$transaction = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $date = $_POST['date']; // Adding date field

    // Update the transaction including date
    $stmt = $pdo->prepare("UPDATE business_transactions SET amount = ?, category = ?, description = ?, transaction_date = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$amount, $category, $description, $date, $transaction_id, $_SESSION['user_id']]);

    header("Location: account_business_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaction</title>
    <link rel="stylesheet" href="css/edit_transaction.css">
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
        <h2 class="page-title">Edit Transaction</h2>
        <form method="POST" class="transaction-form">
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" value="<?php echo $transaction['amount']; ?>" required>
            </div>
            <div class="form-group">
                <label for="category">Category</label>
                <select name="category" id="category" required>
                    <!-- <option value="Income" <?php echo $transaction['category'] == 'Income' ? 'selected' : ''; ?>>Income</option> -->
                    <option value="Expense" <?php echo $transaction['category'] == 'Expense' ? 'selected' : ''; ?>>Expense</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" value="<?php echo $transaction['transaction_date']; ?>" required> <!-- Adding date field -->
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" required><?php echo $transaction['description']; ?></textarea>
            </div>
            <button type="submit" class="btn-submit">Update Transaction</button>
        </form>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>
