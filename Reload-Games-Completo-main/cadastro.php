<?php
session_start();

// Se já estiver logado, redireciona
if (isset($_SESSION['usuario_id'])) {
    header("Location: principal.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Reload Games</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: linear-gradient(135deg, #3a0ca3, #4361ee, #4cc9f0);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .cadastro-container {
            background: rgba(30, 30, 45, 0.95);
            padding: 40px;
            width: 380px;
            border-radius: 18px;
            backdrop-filter: blur(5px);
            box-shadow: 0 0 25px rgba(67, 97, 238, 0.4);
            border: 1px solid rgba(67, 97, 238, 0.35);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .cadastro-container h2 {
            text-align: center;
            margin-bottom: 25px;
            font-size: 26px;
            font-weight: 700;
            color: #4cc9f0;
            text-shadow: 0 0 10px rgba(76, 201, 240, 0.5);
        }

        .cadastro-container input {
            width: 100%;
            padding: 14px;
            margin-bottom: 18px;
            border: 1px solid #555;
            background: #1e1e2f;
            color: #fff;
            border-radius: 10px;
            outline: none;
            transition: 0.2s;
        }

        .cadastro-container input:focus {
            border-color: #4cc9f0;
            box-shadow: 0 0 10px rgba(76, 201, 240, 0.5);
        }

        .cadastro-container button {
            width: 100%;
            padding: 14px;
            background: #4361ee;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            color: #fff;
            font-size: 16px;
            transition: 0.2s;
        }

        .cadastro-container button:hover {
            background: #3a0ca3;
            transform: scale(1.03);
        }

        .erro, .sucesso {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }

        .erro {
            background: #d63c3c;
            box-shadow: 0 0 10px rgba(255,0,0,0.3);
        }

        .sucesso {
            background: #4cc9f0;
            color: #111;
            box-shadow: 0 0 10px rgba(76,201,240,0.4);
        }

        a {
            display: block;
            text-align: center;
            margin-top: 12px;
            color: #4cc9f0;
            text-decoration: none;
            font-weight: 500;
            transition: 0.2s;
        }

        a:hover {
            color: #4361ee;
            transform: scale(1.05);
        }
    </style>
</head>

<body>

<div class="cadastro-container">

    <h2>Criar Conta</h2>

    <?php if (isset($_GET['erro'])): ?>
        <div class="erro"><?= htmlspecialchars($_GET['erro']) ?></div>
    <?php endif; ?>

    <?php if (isset($_GET['ok'])): ?>
        <div class="sucesso">Conta criada com sucesso!</div>
    <?php endif; ?>

    <form action="processa_cadastro.php" method="POST">
        <input type="text" name="nome" placeholder="Digite seu nome" required>
        <input type="email" name="email" placeholder="Digite seu email" required>
        <input type="password" name="senha" placeholder="Digite sua senha" required>
        <button type="submit">Cadastrar</button>
    </form>

    <a href="login.php">Já tenho uma conta</a>
</div>

</body>
</html>
