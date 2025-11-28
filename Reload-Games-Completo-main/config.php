<?php
$host = 'localhost';
$user = 'root';
$pass = ''; 
$db   = 'reload_games';

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_errno) {
    die("Erro ao conectar ao banco de dados: " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8mb4");
?>
