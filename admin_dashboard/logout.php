<?php
    session_start();
    if (!isset($_SESSION['logged_in'])) {
        header('Location: login.php');
    }
    session_destroy();
    header("Location: /admin_dashboard/login.php");

?>