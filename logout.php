<?php
include 'header.php';
session_start();
session_destroy();
header("Location: login.php");
exit();
?>
<?php include 'footer.php'; ?>

