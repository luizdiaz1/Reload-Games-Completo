<?php
require 'config.php';
$id = (int)($_GET['id'] ?? 0);

$stmt = $mysqli->prepare("SELECT * FROM jogos WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$game = $stmt->get_result()->fetch_assoc();

if (!$game) {
    header("Location: principal.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $genero = trim($_POST['genero']);
    $plataforma = trim($_POST['plataforma']);
    $preco = $_POST['preco'] ?: 0;
    $descricao = trim($_POST['descricao']);

    $stmt = $mysqli->prepare("UPDATE jogos SET nome=?, genero=?, plataforma=?, preco=?, descricao=? WHERE id=?");
    $stmt->bind_param('sssdsi', $nome, $genero, $plataforma, $preco, $descricao, $id);
    $stmt->execute();
    header("Location: principal.php");
    exit;
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Editar Jogo — Reload Games</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div style="height:64px"></div>

  <div class="page">
    <div class="hero" style="padding:18px 20px;">
      <div style="display:flex;justify-content:space-between;align-items:center">
        <div style="font-size:18px;color:var(--accent);font-weight:700">Editar Jogo</div>
        <a href="principal.php" class="action-btn" style="background:transparent;border:1px solid rgba(255,255,255,0.04);color:var(--muted);padding:8px 12px">Voltar</a>
      </div>
    </div>

    <div style="margin-top:18px;max-width:720px">
      <div class="form-box">
        <form method="post">
          <div class="form-row">
            <label>Nome</label>
            <input type="text" name="nome" required value="<?= htmlspecialchars($game['nome']) ?>">
          </div>

          <div class="form-row">
            <label>Gênero</label>
            <input type="text" name="genero" value="<?= htmlspecialchars($game['genero']) ?>">
          </div>

          <div class="form-row">
            <label>Plataforma</label>
            <select name="plataforma">
              <?php foreach (['PC','PlayStation','Xbox','Nintendo','Mobile'] as $p): ?>
                <option value="<?= $p ?>" <?= $game['plataforma']==$p?'selected':'' ?>><?= $p ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-row">
            <label>Preço</label>
            <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($game['preco']) ?>">
          </div>

          <div class="form-row">
            <label>Descrição</label>
            <textarea name="descricao" rows="4"><?= htmlspecialchars($game['descricao']) ?></textarea>
          </div>

          <button class="action-btn" type="submit">Salvar</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    // apply theme from storage
    (function(){
      const saved = localStorage.getItem('rg_theme') || 'dark';
      if(saved === 'light') document.documentElement.classList.add('theme-light');
    })();
  </script>
</body>
</html>
