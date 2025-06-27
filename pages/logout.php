<?php
session_start();
// Destroy session if user is logged in
if (isset($_SESSION['ecode'])) {
    session_destroy();
    unset($_SESSION['ecode']);
}
// Redirect to login
header("Location: ../index.php");
exit();
?>