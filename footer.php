<!DOCTYPE html>
<html lang="en">
<head>
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

/* Body padding to prevent content overlap */
body {
    padding-top: 100px; /* To make space for the fixed header */
    padding-bottom: 50px; /* To make space for the fixed footer */
}

    </style>
</head>
<body>
    <footer class="fixed-footer">
        <p>&copy; 2024 Personal Finance Tracker. All rights reserved.</p>
    </footer>
</body>
</html>
