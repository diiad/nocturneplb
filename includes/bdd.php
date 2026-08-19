<?php

require_once __DIR__ . '/env.php';

$host = env('DB_HOST');
$dbname = env('DB_NAME');
$user = env('DB_USER');
$pass = env('DB_PASSWORD');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

?>
