<?php
// Accès admin (1) et gestionnaire (2)
if(!in_array((int) $_SESSION['role'], [1, 2], true)){
    header('Location: /dashboard');
    exit();
}
?>