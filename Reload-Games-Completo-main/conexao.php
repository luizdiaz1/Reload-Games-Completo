<?php
$mysqli = new mysqli("localhost", "root", "", "reload_games");

// Verifica erro de conexão
if ($mysqli->connect_errno) {
    die("Erro ao conectar ao banco: " . $mysqli->connect_error);
}
?>
