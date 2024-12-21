<?php
include 'db/config.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and get the transaction ID and account type
    $transaction_id = $_POST['transaction_id'] ?? null;
    $account_type = $_POST['account_type'] ?? null;
    $user_id = $_SESSION['user_id'];

    if ($transaction_id && $account_type) {
        // Prepare and execute the delete query
        $stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ? AND user_id = ?");
        if ($stmt->execute([$transaction_id, $user_id])) {
            // After successful deletion, redirect back with a success message
            header("Location: account_dashboard.php?account_type=" . urlencode($account_type) . "&message=Transaction deleted successfully.");
            exit();
        } else {
            // Redirect with an error message if deletion fails
            header("Location: account_dashboard.php?account_type=" . urlencode($account_type) . "&error=Failed to delete transaction.");
            exit();
        }
    } else {
        // Redirect with an error if transaction ID or account type is missing
        header("Location: account_dashboard.php?account_type=" . urlencode($account_type) . "&error=Invalid transaction or account type.");
        exit();
    }
}
?>
