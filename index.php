<?php
session_start();
require_once "includes/global.php";
if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}else{
    header("location: dashboard.php");
}
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $webname ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<style>
    html,body{
        background-color: #4B4E53;
        background-image: linear-gradient(147deg, #4B4E53 0%, #000000 74%);
        min-height: 100vh;
    }
    .container {
        margin-top: 50px;
        max-width: 100%;
        padding: 0 1rem;
    }
</style>
</head>

<body>
<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php"><?= $webname ?></a>
    <div>
      <a class="btn btn-outline-light me-2" href="dashboard.php">Dashboard</a>
      <a class="btn btn-outline-light" href="traitements/deconnexion.php">Déconnexion</a>
    </div>
  </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs</h5>
                    <p class="card-text display-6"><?= XXX ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Admins</h5>
                    <p class="card-text display-6"><?= XXX ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Instagram renseigné</h5>
                    <p class="card-text display-6"><?= XXX ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once "includes/footer.php"; ?>
</body>
</html>
