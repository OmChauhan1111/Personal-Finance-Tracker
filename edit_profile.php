<?php
include 'db/config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from the database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('User not found');
}

// Initialize photo variable to avoid undefined index notice
$photo = !empty($user['photo']) ? $user['photo'] : 'assets/profile.png'; // Default photo if none exists

// If form is submitted, update the user's details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $account_type = isset($_POST['account_type']) ? $_POST['account_type'] : '';

    // Handle profile photo upload
    if (isset($_FILES['photo']['name']) && !empty($_FILES['photo']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);

        // Check if the uploads folder exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo = $target_file; // Update photo path
        } else {
            echo "Error uploading file.";
        }
    }

    // Update user details in the database
    $updateStmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, account_type = ?, photo = ? WHERE id = ?");
    if ($updateStmt->execute([$name, $email, $account_type, $photo, $_SESSION['user_id']])) {
        header("Location: profile.php?message=Profile updated successfully");
        exit();
    } else {
        $error = "Failed to update profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        /* Global Reset */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
}

.profile-container {
    max-width: 500px;
    margin: 0 auto;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #555;
}

input[type="text"],
input[type="email"],
input[type="file"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type="text"]:focus,
input[type="email"]:focus {
    border-color: #007BFF;
    outline: none;
}

.profile-photo {
    display: block;
    max-width: 100px;
    margin: 10px auto;
    border-radius: 50%;
}

.form-actions {
    text-align: center;
}

.save-btn {
    background-color: #007BFF;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.save-btn:hover {
    background-color: #0056b3;
}

.nav-links {
    margin-top: 20px;
    text-align: center;
}

.nav-links a {
    display: inline-block;
    margin: 5px 10px;
    text-decoration: none;
    color: #007BFF;
    padding: 8px 15px;
    border: 1px solid transparent;
    border-radius: 5px;
    transition: background-color 0.3s ease, border-color 0.3s ease;
}

.nav-links a:hover {
    background-color: #007BFF;
    color: white;
    border-color: #0056b3;
}


    </style>
    <!-- <link rel="stylesheet" href="css/profile.css"> -->
</head>
<body>
    <div class="profile-container">
        <h2>Edit Your Profile</h2>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data" class="profile-form">
            <div class="form-group">
                <label for="photo">Profile Photo:</label>
                <img src="<?php echo htmlspecialchars($photo); ?>" alt="Profile" class="profile-photo">
                <input type="file" name="photo">
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="account_type">Account Type:</label>
                <input type="text" name="account_type" value="<?php echo htmlspecialchars($user['account_type']); ?>" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="save-btn">Save Changes</button>
            </div>
        </form>
    </div>

    <nav class="nav-links">
        <a href="main_dashboard.php">Main Dashboard</a>
        <a href="add_transaction.php">Add Transaction</a>
        <a href="view_transactions.php">Show Transactions</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</body>
</html>
