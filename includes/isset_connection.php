<?php if(!isset($_SESSION['email'])){
header("Location: login.php?error=Veuillez vous connecter");
exit();
}

?>