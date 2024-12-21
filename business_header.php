<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Header Design */
.fixed-header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background-color: #6A82FB;
    color: white;
    padding: 20px;
    text-align: center;
    z-index: 1000; /* Keeps header on top */
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

.fixed-header h1 {
    margin: 0;
    font-size: 24px;
}

.fixed-header nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}

.fixed-header nav ul li {
    display: inline-block;
    margin-left: 20px;
}

.fixed-header nav ul li a {
    color: white;
    text-decoration: none;
    font-size: 16px;
    transition: color 0.3s ease;
}

.fixed-header nav ul li a:hover {
    color: #FC5C7D;
}

/* Footer Design */
.fixed-footer {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: #6A82FB;
    color: white;
    text-align: center;
    padding: 10px;
    z-index: 1000; /* Keeps footer on top */
    box-shadow: 0px -4px 8px rgba(0, 0, 0, 0.1);
}

.fixed-footer p {
    margin: 0;
    font-size: 14px;
}
/* Profile button styling */
.btn-container {
    position: absolute;
    top: 20px;
    right: 20px;
}

.circular-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: black;
    color: white;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.circular-btn:hover {
    background-color: #0056b3;
}

.circular-btn img.profile-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Body padding to prevent content overlap */
body {
    padding-top: 100px; /* To make space for the fixed header */
    padding-bottom: 50px; /* To make space for the fixed footer */
}

    </style>
</head>
<body>


    <header class="fixed-header">
        <div class="header-content">
            <h1>Personal Finance Tracker</h1>
            <nav>
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="add_business_transaction.php">Add Transaction</a></li>
                    <li><a href="view_business_transactions.php">View Transactions</a></li>
                    
                </ul>
                <div class="btn-container">
        <a href="profile.php" class="circular-btn">
            <?php if (!empty($user['photo'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($user['photo']); ?>" alt="Profile" class="profile-photo">
            <?php else: ?>
                <span class="initial"><?php echo strtoupper(substr(htmlspecialchars($user['name']), 0, 1)); ?></span>
            <?php endif; ?>
        </a>
    </div>
            </nav>
        </div>
    </header>
</body>
</html>
