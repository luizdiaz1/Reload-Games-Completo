<?php
require 'config.php';
$errors = [];

function detectImageByName($name) {
    $n = trim((string)$name);
    if ($n === '') return 'default.jpg';

    // translitera (remoção de acentos) e coloca em minúsculas
    $ascii = iconv('UTF-8', 'ASCII//TRANSLIT', $n);
    $key = strtolower($ascii);

    // mapa: palavras-chave -> arquivo na pasta /img/
    $map = [
        'sonic'       => 'sonic.jpg',
        'sonic the'   => 'sonic.jpg',
        'sonic the hedgehog' => 'sonic.jpg',
        'mario'       => 'mariokart.jpg',
        'mario kart'  => 'mariokart.jpg',
        'kart'        => 'mariokart.jpg',
        'gta'         => 'gtav.jpg',
        'gta v'       => 'gtav.jpg',
        'grand theft' => 'gtav.jpg',
        'grand theft auto' => 'gtav.jpg'
    ];

    foreach ($map as $k => $file) {
        if (strpos($key, $k) !== false) {
            // checa se arquivo existe na pasta img, se não existir retorna default
            if (is_file(__DIR__ . '/img/' . $file)) {
                return $file;
            }
            break; // se mapeou mas arquivo não existe, sair do loop e retornar default
        }
    }

    return 'default.jpg';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $generoOutro = trim($_POST['genero_outro'] ?? "");
    $plataforma = trim($_POST['plataforma'] ?? '');
    $preco = $_POST['preco'] !== '' ? (float)$_POST['preco'] : 0.0;
    $descricao = trim($_POST['descricao'] ?? '');

    // Se escolheu "Outro", usa o texto digitado
    if ($genero === "Outro" && $generoOutro !== "") {
        $genero = $generoOutro;
    }

    if ($nome === '') $errors[] = "O nome é obrigatório.";

    // só detecta a imagem se não houver erros de validação
    $foto = 'default.jpg';
    if (!$errors) {
        $foto = detectImageByName($nome);
    }

    if (!$errors) {
        $stmt = $mysqli->prepare(
            "INSERT INTO jogos (nome, genero, plataforma, preco, descricao, foto)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        if (!$stmt) {
            $errors[] = "Erro ao preparar a query: " . $mysqli->error;
        } else {
            // tipos: s (nome), s (genero), s (plataforma), d (preco double), s (descricao), s (foto)
            $stmt->bind_param('sssdss', $nome, $genero, $plataforma, $preco, $descricao, $foto);
            $ok = $stmt->execute();
            if (!$ok) {
                $errors[] = "Erro ao salvar: " . $stmt->error;
            } else {
                header("Location: principal.php");
                exit;
            }
        }
    }
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Novo Jogo — Reload Games</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Espaço navbar -->
<div style="height:64px"></div>

<div class="page">

    <!-- Header -->
    <div class="hero" style="padding:18px 20px;">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div style="font-size:18px;color:var(--accent);font-weight:700">
                Adicionar Novo Jogo
            </div>

            <a href="principal.php"
               class="action-btn secondary"
               style="padding:8px 14px;">
               Voltar
            </a>
        </div>
    </div>

    <!-- Form -->
    <div style="margin-top:18px;max-width:720px">
    <div class="form-box">

        <?php if ($errors): ?>
            <?php foreach ($errors as $e): ?>
                <div style="color:#ff6b6b;margin-bottom:10px">
                    <?= htmlspecialchars($e) ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <form method="post">

            <!-- Nome -->
            <div class="form-row">
                <label>Nome</label>
                <input type="text" name="nome" required value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>">
            </div>

            <!-- Gênero -->
            <div class="form-row">
                <label>Gênero</label>
                <select name="genero" required class="select-genre">
                    <option value="">Selecione o gênero...</option>
                    <option value="Ação" <?= (($_POST['genero'] ?? '') === 'Ação') ? 'selected' : '' ?>>Ação</option>
                    <option value="Aventura" <?= (($_POST['genero'] ?? '') === 'Aventura') ? 'selected' : '' ?>>Aventura</option>
                    <option value="Corrida" <?= (($_POST['genero'] ?? '') === 'Corrida') ? 'selected' : '' ?>>Corrida</option>
                    <option value="RPG" <?= (($_POST['genero'] ?? '') === 'RPG') ? 'selected' : '' ?>>RPG</option>
                    <option value="Esportes" <?= (($_POST['genero'] ?? '') === 'Esportes') ? 'selected' : '' ?>>Esportes</option>
                    <option value="Outro" <?= (($_POST['genero'] ?? '') === 'Outro') ? 'selected' : '' ?>>Outro</option>
                </select>

                <input type="text"
                       id="generoOutro"
                       name="genero_outro"
                       placeholder="Digite o gênero..."
                       style="display:<?= (($_POST['genero'] ?? '') === 'Outro') ? 'block' : 'none' ?>;margin-top:8px;"
                       value="<?= htmlspecialchars($_POST['genero_outro'] ?? '') ?>">
            </div>

            <!-- Plataforma -->
            <div class="form-row">
                <label>Plataforma</label>
                <select name="plataforma">
                    <option <?= (($_POST['plataforma'] ?? '') === 'PC') ? 'selected' : '' ?>>PC</option>
                    <option <?= (($_POST['plataforma'] ?? '') === 'PlayStation') ? 'selected' : '' ?>>PlayStation</option>
                    <option <?= (($_POST['plataforma'] ?? '') === 'Xbox') ? 'selected' : '' ?>>Xbox</option>
                    <option <?= (($_POST['plataforma'] ?? '') === 'Nintendo') ? 'selected' : '' ?>>Nintendo</option>
                    <option <?= (($_POST['plataforma'] ?? '') === 'Mobile') ? 'selected' : '' ?>>Mobile</option>
                </select>
            </div>

            <!-- Preço -->
            <div class="form-row">
                <label>Preço</label>
                <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($_POST['preco'] ?? '') ?>">
            </div>

            <!-- Descrição -->
            <div class="form-row">
                <label>Descrição</label>
                <textarea name="descricao" rows="4"><?= htmlspecialchars($_POST['descricao'] ?? '') ?></textarea>
            </div>
            
            <button class="action-btn" type="submit">Salvar</button>

        </form>
    </div>
    </div>

</div>

<script>
/* Aplicar tema salvo */
(function(){
    const saved = localStorage.getItem('rg_theme') || 'dark';
    if(saved === 'light') document.documentElement.classList.add('theme-light');
})();

/* Mostrar campo "Outro gênero" */
const selectGenre = document.querySelector('.select-genre');
if (selectGenre) {
    selectGenre.addEventListener('change', function () {
        let outro = document.getElementById('generoOutro');
        if (this.value === "Outro") {
            outro.style.display = "block";
            outro.required = true;
        } else {
            outro.style.display = "none";
            outro.required = false;
            outro.value = "";
        }
    });
}
</script>

</body>
</html>
