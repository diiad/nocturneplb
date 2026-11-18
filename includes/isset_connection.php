<?php if(!isset($_SESSION['email'])){
header("Location: /login?error=Veuillez vous connecter");
exit();
}

?>