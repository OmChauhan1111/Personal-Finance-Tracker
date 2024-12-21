<?php
include 'db/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['account_to_delete'])) {
    $account_to_delete = $_POST['account_to_delete'];
    $user_id = $_SESSION['user_id'];

    // Delete the account from the database
    $delete_stmt = $pdo->prepare("DELETE FROM business_accounts WHERE user_id = ? AND account_name = ?");
    $delete_stmt->execute([$user_id, $account_to_delete]);

    // Optionally, delete transactions related to this account
    $delete_transactions_stmt = $pdo->prepare("DELETE FROM business_transactions WHERE user_id = ? AND account_type = ?");
    $delete_transactions_stmt->execute([$user_id, $account_to_delete]);

    // Redirect back to dashboard with a success message
    header("Location: business_dashboard.php?message=Account deleted successfully!");
    exit();
}
?>
