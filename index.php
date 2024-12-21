<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Personal Finance Tracker</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>
    <header class="main-header">
        <div class="container">
            <h1>Welcome to Your Personal Finance Tracker</h1>
            <p>Manage your finances like a pro with our simple, yet powerful tools!</p>
            <a href="login.php" class="btn-main">Login</a>
            <a href="register.php" class="btn-secondary">Register</a>
        </div>
    </header>

    <section class="features-section">
        <div class="container">
            <h2>Why Choose Us?</h2>
            <div class="features">
                <div class="feature">
                    <i class="fas fa-chart-line"></i>
                    <h3>Track Your Income</h3>
                    <p>Keep a detailed record of your income sources and monitor your earnings over time.</p>
                </div>
                <div class="feature">
                    <i class="fas fa-wallet"></i>
                    <h3>Manage Expenses</h3>
                    <p>Organize and categorize your expenses for a clear financial overview.</p>
                </div>
                <div class="feature">
                    <i class="fas fa-balance-scale"></i>
                    <h3>Budget Wisely</h3>
                    <p>Create and maintain budgets to ensure you live within your means.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="main-footer">
        <div class="container">
            <p>&copy; 2024 Personal Finance Tracker. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
