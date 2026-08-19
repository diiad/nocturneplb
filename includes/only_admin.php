<?php
if($_SESSION['role'] == '0'){
    header('Location: /dashboard.php');
    exit();
}
?>