<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Account Type</title>
    <style>
        /* Reset some default browser styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f7fc; /* Light background for a calm effect */
}

/* Container for account selection */
.account-selection-container {
    max-width: 400px; /* Max width for the container */
    margin: 100px auto; /* Center the container vertically and horizontally */
    background-color: #ffffff; /* White background for the form */
    padding: 30px; /* Padding for the form */
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

/* Title styling */
h2 {
    font-size: 28px; /* Title size */
    text-align: center; /* Center the title */
    color: #007bff; /* Primary color for title */
    margin-bottom: 20px; /* Space below title */
}

/* Form group styling */
.form-group {
    margin-bottom: 20px; /* Space between radio buttons */
}

/* Radio button styling */
label {
    font-size: 18px; /* Label font size */
    color: #333; /* Dark color for labels */
    cursor: pointer; /* Pointer cursor on hover */
    display: flex; /* Flex layout for radio button and label */
    align-items: center; /* Center alignment */
}

/* Custom radio button */
input[type="radio"] {
    margin-right: 10px; /* Space between radio button and label */
    accent-color: #007bff; /* Custom color for radio button */
}

/* Button styling */
.btn {
    width: 100%; /* Full-width button */
    padding: 15px; /* Padding for button */
    border: none; /* No border */
    border-radius: 5px; /* Rounded corners */
    background-color: #007bff; /* Primary button color */
    color: white; /* White text */
    font-size: 18px; /* Font size for button text */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background-color 0.3s ease; /* Smooth transition */
}

.btn:hover {
    background-color: #0056b3; /* Darker shade on hover */
}

/* Responsive design for mobile devices */
@media (max-width: 480px) {
    .account-selection-container {
        padding: 20px; /* Adjust padding for small screens */
    }

    h2 {
        font-size: 24px; /* Smaller title size */
    }

    .btn {
        font-size: 16px; /* Smaller button size */
    }
}

    </style>
    <!-- <link rel="stylesheet" href="css/styles.css"> -->
</head>
<body>
    <div class="account-selection-container">
        <h2>Select Account Type</h2>
        <form method="POST" action="account_redirect.php">
            <div class="form-group">
                <label>
                    <input type="radio" name="account_type" value="personal" required>
                    Personal Account
                </label>
            </div>
            <div class="form-group">
                <label>
                    <input type="radio" name="account_type" value="business" required>
                    Business Account
                </label>
            </div>
            <button type="submit" class="btn">Continue</button>
        </form>
    </div>
</body>
</html>
