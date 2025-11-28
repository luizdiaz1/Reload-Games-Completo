<?php
session_start();

// PROTEÇÃO DE LOGIN
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

require 'config.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

/* ==========================================
   FUNÇÃO PARA RETORNAR IMAGEM DO JOGO
   ========================================== */
function getGameImage($name) {

    $name = strtolower(trim($name));

    $map = [
        'sonic'        => 'sonic.jpg',
        'mario'        => 'mariokart.jpg',
        'kart'         => 'mariokart.jpg',
        'gta v'        => 'gtav.jpg',
        'grand theft'  => 'gtav.jpg',
        'red dead redemption 2' => 'red.jpg',
        'ark survival evolved' => 'ark.png',
        'fifa 23' => 'fifa23.webp'
    ];

    foreach ($map as $key => $img) {
        if (strpos($name, $key) !== false) {
            return "img/" . $img;
        }
    }

    return "img/default.jpg";
}

/* ==========================================
    BUSCA NO BANCO
   ========================================== */

if ($q) {
    $stmt = $mysqli->prepare("SELECT * FROM jogos WHERE nome LIKE ? OR genero LIKE ? OR plataforma LIKE ? ORDER BY criado_em DESC");
    $like = "%{$q}%";
    $stmt->bind_param('sss', $like, $like, $like);
    $stmt->execute();
    $res = $stmt->get_result();
} else {
    $res = $mysqli->query("SELECT * FROM jogos ORDER BY criado_em DESC");
}

$recent = $mysqli->query("SELECT * FROM jogos ORDER BY criado_em DESC LIMIT 3");
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Reload Games</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- NAVBAR -->
  <div class="navbar">
    <div class="nav-left">
      <div class="logo">
        <div class="icon">🎮</div>
        <div>Reload Games</div>
      </div>
      <div class="nav-links">
        <div>Dashboard</div>
      </div>
    </div>


</ul>

    <div class="nav-right">

      <!-- EXIBE O USUÁRIO LOGADO -->
      <div style="margin-right:20px;">
        👤 <?= htmlspecialchars($_SESSION['usuario_nome']); ?>
      </div>
      <div class="relatorio-container">
    <a href="relatorio.php" class="btn-relatorio">Relatório</a>
</div>

      <!-- BOTÃO DE LOGOUT CORRIGIDO -->
      <a href="logout.php" class="action-btn" style="
        background:#e74c3c;
        color:white;
        padding:6px 14px;
        border-radius:6px;
        text-decoration:none;">
        Sair
      </a>

      <button id="themeBtn" class="theme-toggle" title="Alternar tema">🌙</button>
    </div>
  </div>

  <!-- PAGE -->
  <div class="page">

    <!-- HERO -->
    <div class="hero">
      <div class="title">Reload Games</div>
      <div class="subtitle">Gerencie sua coleção de jogos da melhor maneira. Adicione, organize e acompanhe seus jogos preferidos.</div>

      <div class="search-row">
        <form method="get" class="search-box" style="width:72%;">
            <input type="text" name="q" placeholder="Pesquisar por nome, gênero ou plataforma..." value="<?= htmlspecialchars($q) ?>">
            <button class="action-btn" type="submit">Buscar</button>
        </form>
        <a href="addnovojogo.php" class="action-btn" style="width:140px;display:inline-flex;align-items:center;justify-content:center;">+ Novo Jogo</a>
      </div>
    </div>

    <!-- TABLE -->
    <div class="table-card">
      <div class="table-wrap">
        <table class="game-table">
          <thead>
            <tr>
              <th>Capa</th>
              <th>Nome</th>
              <th>Gênero</th>
              <th>Plataforma</th>
              <th>Preço</th>
              <th>Descrição</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($res && $res->num_rows): ?>
              <?php while ($j = $res->fetch_assoc()): ?>
                <tr class="game-row">

                  <!-- CAPA -->
                  <td>
                    <img src="<?= getGameImage($j['nome']) ?>" class="game-cover" alt="capa">
                  </td>

                  <td><?= htmlspecialchars($j['nome']) ?></td>

                  <td><span class="badge"><?= htmlspecialchars($j['genero']) ?></span></td>

                  <td>
                    <?php
                      $pf = strtolower($j['plataforma'] ?? '');
                      if (strpos($pf,'pc') !== false) echo '💻 PC';
                      else if (strpos($pf,'play') !== false) echo '🎮 PlayStation';
                      else if (strpos($pf,'xbox') !== false) echo '🎮 Xbox';
                      else if (strpos($pf,'nintendo') !== false) echo '🎮 Nintendo';
                      else echo htmlspecialchars($j['plataforma']);
                    ?>
                  </td>

                  <td class="price">R$ <?= number_format($j['preco'],2,',','.') ?></td>

                  <td><?= htmlspecialchars(mb_strimwidth($j['descricao'],0,80,'...')) ?></td>

                  <td class="actions">
                    <div class="action-buttons">
                      <a href="editarnovojogo.php?id=<?= $j['id'] ?>" class="btn-edit">✏️ Editar</a>
                      <a href="deletarjogo.php?id=<?= $j['id'] ?>" class="btn-delete" onclick="return confirm('Excluir este jogo?')">🗑 Excluir</a>
                    </div>
                  </td>

                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="7"><em style="color:var(--muted)">Nenhum jogo encontrado.</em></td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- RECENTES -->
    <div class="section-title">
      <div class="bar"></div>
      <div style="font-weight:700">Adicionados Recentemente</div>
    </div>

    <div class="recent-grid">
      <?php if ($recent && $recent->num_rows): ?>
        <?php while ($r = $recent->fetch_assoc()): ?>
          <div class="recent-card">
            <img src="<?= getGameImage($r['nome']) ?>" class="recent-cover" alt="recent">
            <div class="name"><?= htmlspecialchars($r['nome']) ?></div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="text-muted">Nenhum jogo adicionado recentemente.</div>
      <?php endif; ?>
    </div>

    <!-- FOOTER -->
    <div class="footer">
      <div>© 2025 Reload Games. Todos os direitos reservados.</div>
      <div class="socials">
        <a href="https://www.instagram.com/rluiz_671/" target="_blank" rel="noopener">Instagram</a>
        <a href="https://github.com/luizdiaz1" target="_blank" rel="noopener">GitHub</a>
        <a href="https://www.linkedin.com/in/luiz-dias-76ab892b9/" target="_blank" rel="noopener">LinkedIn</a>
      </div>
    </div>

  </div>

  <!-- SCRIPT DO DARK/LIGHT -->
  <script>
    (function(){
      const body = document.documentElement;
      const btn = document.getElementById('themeBtn');

      function applyTheme(t){
        if(t === 'light') body.classList.add('theme-light'); 
        else body.classList.remove('theme-light');
        btn.textContent = t === 'light' ? '☀️' : '🌙';
      }

      const saved = localStorage.getItem('rg_theme') || 'dark';
      applyTheme(saved);

      btn.addEventListener('click', function(){
        const cur = document.documentElement.classList.contains('theme-light') ? 'light' : 'dark';
        const next = cur === 'light' ? 'dark' : 'light';
        localStorage.setItem('rg_theme', next);
        applyTheme(next);
      });
    })();
  </script>

</body>
</html>
