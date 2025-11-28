<?php
require 'config.php';

// BUSCA LISTA COMPLETA
$lista = $mysqli->query("SELECT * FROM jogos ORDER BY nome ASC");

// TOTAL DE JOGOS
$totalJogos = $mysqli->query("SELECT COUNT(*) AS t FROM jogos")->fetch_assoc()['t'];

// GÊNEROS MAIS POPULARES
$generosBD = $mysqli->query("
    SELECT genero, COUNT(*) AS total 
    FROM jogos 
    GROUP BY genero 
    ORDER BY total DESC
");

// PREPARAR DADOS PARA O GRÁFICO
$labels = [];
$values = [];

foreach ($generosBD as $g) {
    $labels[] = $g['genero'];
    $values[] = $g['total'];
}

// resetar ponteiro
$generosBD->data_seek(0);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatórios — Reload Games</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div style="height:64px"></div>

<div class="page">

    <!-- CABEÇALHO -->
    <div class="hero" style="padding:20px;">
        <div style="font-size:22px;font-weight:700;color:var(--accent)">Relatórios Gerais</div>

        <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:-30px;">
            <a href="relatorio_pdf.php" class="action-btn" 
               style="background:var(--accent);padding:8px 14px;color:#fff;">
               📄 Gerar PDF
            </a>
            <a href="principal.php" class="action-btn"
               style="background:transparent;border:1px solid var(--card-border);color:var(--muted);padding:8px 14px;">
               Voltar
            </a>
        </div>
    </div>

    <!-- RESUMO -->
    <div class="hero" style="margin-top:20px;">
        <div style="font-size:18px;font-weight:700;margin-bottom:10px;color:var(--text)">
            Resumo Geral
        </div>

        <p style="color:var(--muted);font-size:14px;">
            <b style="color:var(--accent);"><?= $totalJogos ?></b> jogos cadastrados.
        </p>

        <div style="margin-top:20px;color:var(--text);font-size:16px;font-weight:700;">
            Gêneros mais populares:
        </div>

        <?php if ($generosBD->num_rows > 0): ?>
            <?php while ($g = $generosBD->fetch_assoc()): ?>
                <div style="color:var(--muted);margin-left:14px;margin-top:6px;">
                    • <b style="color:var(--accent)"><?= $g['genero'] ?></b> — <?= $g['total'] ?> jogos
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="color:var(--muted);margin-left:14px;">Nenhum gênero encontrado.</div>
        <?php endif; ?>
    </div>

    <!-- LISTA COMPLETA -->
    <div class="hero" style="margin-top:25px;">
        <div style="font-size:18px;font-weight:700;margin-bottom:10px;color:var(--text)">
            Lista completa de jogos
        </div>

        <div class="table-wrap">
            <table class="game-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Gênero</th>
                        <th>Plataforma</th>
                        <th>Preço</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($j = $lista->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($j['nome']) ?></td>
                            <td><?= htmlspecialchars($j['genero']) ?></td>
                            <td><?= htmlspecialchars($j['plataforma']) ?></td>
                            <td>R$ <?= number_format($j['preco'], 2, ',', '.') ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>


<!-- GRÁFICO ESTATÍSTICO -->
    <div class="hero" style="margin-top:25px;">
        <div style="font-size:18px;font-weight:700;margin-bottom:10px;color:var(--text)">
            📊 Estatísticas de Jogos por Gênero
        </div>

        <canvas id="graficoEstatistico" style="height:350px;"></canvas>
    </div>

<!-- GRÁFICO -->
<script>
// tema
(function(){
    const saved = localStorage.getItem('rg_theme') || 'dark';
    if(saved === 'light') document.documentElement.classList.add('theme-light');
})();

const labels = <?= json_encode($labels) ?>;
const values = <?= json_encode($values) ?>;

function themeColor(v) {
    return getComputedStyle(document.documentElement).getPropertyValue(v).trim();
}

const textColor = themeColor('--text');

const ctx = document.getElementById('graficoEstatistico').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: 'Quantidade de Jogos',
            data: values,
            borderColor: 'rgba(0,0,0,0.25)',
            borderWidth: 1.5,
            backgroundColor: [
                '#58a6ff','#9b4fd9','#2ea043',
                '#ff6b6b','#f2c94c','#ffa726','#7e57c2'
            ]
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                ticks: { color: textColor },
                grid: { color: 'rgba(255,255,255,0.05)' }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    color: textColor,
                    stepSize: 1,
                    callback: value => Number.isInteger(value) ? value : ''
                },
                grid: { color: 'rgba(255,255,255,0.05)' }
            }
        },
        plugins: {
            legend: {
                labels: { color: textColor }
            },
            tooltip: {
                callbacks: {
                    label: i => ` ${i.raw} jogos`
                }
            }
        }
    }
});
</script>

</body>
</html>
