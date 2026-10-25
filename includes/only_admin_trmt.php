<?php
if($_SESSION['role'] != 1){
    header('Location: /dashboard?error=Accès refusé');
    exit();
}
?>