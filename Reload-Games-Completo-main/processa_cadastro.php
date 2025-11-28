<?php
require "config.php";

$nome  = trim($_POST['nome']);
$email = trim($_POST['email']);
$senha = trim($_POST['senha']);

// verifica se o email já está cadastrado
$stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    header("Location: cadastro.php?erro=1");
    exit;
}

// senha criptografada
$hash = password_hash($senha, PASSWORD_DEFAULT);

// insere no banco
$stmt = $mysqli->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?,?,?)");
$stmt->bind_param("sss", $nome, $email, $hash);
$stmt->execute();

header("Location: cadastro.php?ok=1");
exit;
