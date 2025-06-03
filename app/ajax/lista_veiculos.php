<?php
require_once __DIR__ . '/../config/config.php';
session_start();

$usuario_id = $_SESSION['usuario_id'] ?? 0;

// Buscar veículos
$stmt = $pdo->query("SELECT * FROM veiculos ORDER BY id ASC");
$veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar veículos ocupados
$stmt = $pdo->query("SELECT veiculo_id, usuario_id FROM solicitacoes WHERE status = 'pendente'");
$ocupados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mapa: veiculo_id => usuario_id
$veiculoOcupadoPor = [];
foreach ($ocupados as $s) {
    $veiculoOcupadoPor[$s['veiculo_id']] = $s['usuario_id'];
}

// Renderizar lista
foreach ($veiculos as $v) {
    $ocupado = array_key_exists($v['id'], $veiculoOcupadoPor);
    $ehUsuario = $ocupado && $veiculoOcupadoPor[$v['id']] == $usuario_id;
    $classe = $ocupado && !$ehUsuario ? 'opaco' : ($ehUsuario ? 'proprio' : '');

    echo "<div class='veiculo-card $classe'>";
    echo "<strong>{$v['modelo']} - {$v['placa']}</strong>";
    echo "<form>";
    echo "<input type='hidden' name='veiculo_id' value='{$v['id']}'>";
    if (!$ocupado) {
        echo "<button type='submit' name='solicitar'>Solicitar</button>";
    } elseif ($ehUsuario) {
        echo "<button type='submit' name='devolver'>Devolver</button>";
    } else {
        echo "<button disabled>Indisponível</button>";
    }
    echo "</form></div>";
}
