<?php
require 'config.php';
require 'fpdf/fpdf.php';

// função para consertar acentuação no PDF
function fixChars($str) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $str);
}

// busca todos os jogos
$res = $mysqli->query("SELECT * FROM jogos ORDER BY nome ASC");

// cálculo de total
$total = $res->num_rows;

// estatísticas por gênero
$generos = [];
foreach ($res as $j) {
    $g = $j['genero'];
    if (!isset($generos[$g])) $generos[$g] = 0;
    $generos[$g]++;
}

// cria pdf
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// TÍTULO
$pdf->Cell(0, 10, fixChars("Relatório — Reload Games"), 0, 1, 'C');
$pdf->Ln(5);

// SUBTÍTULO
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, fixChars("Total de jogos cadastrados: $total"), 0, 1);
$pdf->Ln(4);

// GÊNEROS POPULARES
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, fixChars("Gêneros mais populares:"), 0, 1);

$pdf->SetFont('Arial', '', 12);

if (count($generos) > 0) {
    foreach ($generos as $g => $count) {
        $pdf->Cell(0, 8, fixChars("- $g : $count jogos"), 0, 1);
    }
} else {
    $pdf->Cell(0, 8, fixChars("Nenhum jogo cadastrado."), 0, 1);
}

$pdf->Ln(6);

// LISTA DE JOGOS
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, fixChars("Lista completa de jogos"), 0, 1);
$pdf->Ln(2);

// cabeçalho da tabela
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, fixChars("Nome"), 1);
$pdf->Cell(40, 10, fixChars("Gênero"), 1);
$pdf->Cell(40, 10, fixChars("Plataforma"), 1);
$pdf->Cell(40, 10, fixChars("Preço"), 1);
$pdf->Ln();

// corpo da tabela
$pdf->SetFont('Arial', '', 12);

$res = $mysqli->query("SELECT * FROM jogos ORDER BY nome ASC");

foreach ($res as $j) {
    $pdf->Cell(50, 10, fixChars($j['nome']), 1);
    $pdf->Cell(40, 10, fixChars($j['genero']), 1);
    $pdf->Cell(40, 10, fixChars($j['plataforma']), 1);
    $pdf->Cell(40, 10, "R$ " . number_format($j['preco'], 2, ',', '.'), 1);
    $pdf->Ln();
}

$pdf->Output("I", "relatorio_reload_games.pdf");
?>
