<?php
session_start();

// Se já estiver logado, redireciona
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Reload Games</title>
<style>
body {
    margin: 0;
    padding: 0;
    font-family: "Segoe UI", Arial, sans-serif;
    background: linear-gradient(135deg, #35007d, #4c00ff, #1e003c, #000);
    background-size: 300% 300%;
    animation: gradientMove 10s ease infinite;
    
    height: 100vh;
    display: flex;
    justify-content: center;   /* <-- centro horizontal */
    align-items: center;       /* <-- centro vertical */
    color: #fff;
}

/* ANIMAÇÃO DO FUNDO */
@keyframes gradientMove {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* ================================
   Container do Login
   ================================ */

.login-container {
    background: rgba(20, 20, 35, 0.85);
    padding: 45px;
    width: 380px;
    border-radius: 18px;
    backdrop-filter: blur(8px);

    box-shadow: 0 0 25px rgba(127, 0, 255, 0.4),
                0 0 35px rgba(76, 201, 240, 0.2);

    border: 1px solid rgba(127, 0, 255, 0.4);
    animation: fadeIn 0.6s ease;
}

/* Animação suave */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(25px); }
    to { opacity: 1; transform: translateY(0); }
}

.login-container h2 {
    text-align: center;
    margin-bottom: 25px;
    font-size: 28px;
    font-weight: 700;
    color: #4cc9f0;
    text-shadow: 0 0 10px rgba(76, 201, 240, 0.6);
}

/* ================================
   Inputs
   ================================ */

.login-container input {
    width: 100%;
    padding: 14px;
    margin-bottom: 18px;
    border: 1px solid #4c00ff;
    background: rgba(20, 20, 35, 0.6);
    color: #fff;
    border-radius: 10px;
    outline: none;
    transition: 0.25s;
}

.login-container input:focus {
    border-color: #4cc9f0;
    box-shadow: 0 0 12px rgba(76, 201, 240, 0.6);
}

/* ================================
   Botão
   ================================ */

.login-container button {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #4c00ff, #7f00ff);
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: bold;
    color: #fff;
    font-size: 17px;
    transition: 0.25s;
}

.login-container button:hover {
    transform: scale(1.04);
    box-shadow: 0 0 15px rgba(127, 0, 255, 0.6);
}

/* ================================
   Mensagem de erro
   ================================ */

.erro {
    background: rgba(255, 0, 80, 0.8);
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 15px;
    text-align: center;
    font-weight: bold;
    box-shadow: 0 0 12px rgba(255, 0, 80, 0.5);
}

/* ================================
   Link criar conta
   ================================ */

.login-container a {
    display: block;
    text-align: center;
    margin-top: 12px;
    color: #4cc9f0;
    text-decoration: none;
    font-weight: 500;
    transition: 0.2s;
}

.login-container a:hover {
    color: #7f00ff;
    transform: scale(1.05);
}
</style>
</head>
</body>

<body>

<div class="login-container">

    <h2>Login</h2>

    <?php if (isset($_GET['erro'])): ?>
        <div class="erro">Usuário ou senha incorretos!</div>
    <?php endif; ?>

    <form action="validar_login.php" method="POST">
        <input type="email" name="email" placeholder="Digite seu email" required>
        <input type="password" name="senha" placeholder="Digite sua senha" required>
        <button type="submit">Entrar</button>
    </form>

    <a href="cadastro.php">Criar conta</a>
</div>

</body>
</html>
