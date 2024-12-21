<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $account_type = $_POST['account_type'];

    if ($account_type == 'personal') {
        header("Location: personal_dashboard.php"); // Redirect to personal dashboard
    } elseif ($account_type == 'business') {
        header("Location: business_dashboard.php"); // Redirect to business dashboard
    }
    exit();
} else {
    // If accessed without POST request, redirect back to account selection
    header("Location: account_selection.php");
    exit();
}
?>
