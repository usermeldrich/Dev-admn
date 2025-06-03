<?php
require_once __DIR__ . '/../config/config.php';
session_start();

$usuario_id = $_SESSION['usuario_id'] ?? 0;
$token_sessao = $_SESSION['token_sessao'] ?? '';

// Verifica se sessão é válida
if (!$usuario_id || !$token_sessao) {
    echo json_encode(['sessao_valida' => false, 'html' => '']);
    exit;
}

$stmt = $pdo->prepare("SELECT token_sessao FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$tokenAtual = $stmt->fetchColumn();

if ($tokenAtual !== $token_sessao) {
    echo json_encode(['sessao_valida' => false, 'html' => '']);
    exit;
}

// Código original começa aqui

// Buscar todos os veículos
$stmt = $pdo->query("SELECT * FROM veiculos ORDER BY id ASC");
$veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar solicitações pendentes
$stmt = $pdo->query("SELECT veiculo_id, usuario_id FROM solicitacoes WHERE status = 'pendente'");
$ocupados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mapeia quais veículos estão ocupados e por quem
$veiculoOcupadoPor = [];
foreach ($ocupados as $s) {
    $veiculoOcupadoPor[$s['veiculo_id']] = $s['usuario_id'];
}

$html = '';

foreach ($veiculos as $v) {
    $veiculoId = (int)$v['id'];
    $modelo = htmlspecialchars($v['modelo']);
    $placa = htmlspecialchars($v['placa']);

    $ocupado = array_key_exists($veiculoId, $veiculoOcupadoPor);
    $ehUsuario = $ocupado && $veiculoOcupadoPor[$veiculoId] == $usuario_id;

    $classe = '';
    if ($ocupado && !$ehUsuario) {
        $classe = 'opaco';
    } elseif ($ehUsuario) {
        $classe = 'proprio';
    }

    $html .= "<div class='veiculo-card {$classe}'>";
    $html .= "<strong>{$modelo} - {$placa}</strong>";
    $html .= "<form>";
    $html .= "<input type='hidden' name='veiculo_id' value='{$veiculoId}'>";
    
    if (!$ocupado) {
        $html .= "<button type='submit' name='solicitar'>Solicitar</button>";
    } elseif ($ehUsuario) {
        $html .= "<button type='submit' name='devolver'>Devolver</button>";
    } else {
        $html .= "<button disabled>Indisponível</button>";
    }

    $html .= "</form></div>";
}

// Retorna JSON com sessão válida e o HTML
echo json_encode(['sessao_valida' => true, 'html' => $html]);


/*

// Código original (sem validação de sessão e retorno JSON):

$usuario_id = $_SESSION['usuario_id'] ?? 0;

// Buscar todos os veículos
$stmt = $pdo->query("SELECT * FROM veiculos ORDER BY id ASC");
$veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Buscar solicitações pendentes
$stmt = $pdo->query("SELECT veiculo_id, usuario_id FROM solicitacoes WHERE status = 'pendente'");
$ocupados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mapeia quais veículos estão ocupados e por quem
$veiculoOcupadoPor = [];
foreach ($ocupados as $s) {
    $veiculoOcupadoPor[$s['veiculo_id']] = $s['usuario_id'];
}

// Gerar cards
foreach ($veiculos as $v) {
    $veiculoId = (int)$v['id'];
    $modelo = htmlspecialchars($v['modelo']);
    $placa = htmlspecialchars($v['placa']);

    $ocupado = array_key_exists($veiculoId, $veiculoOcupadoPor);
    $ehUsuario = $ocupado && $veiculoOcupadoPor[$veiculoId] == $usuario_id;

    $classe = '';
    if ($ocupado && !$ehUsuario) {
        $classe = 'opaco';
    } elseif ($ehUsuario) {
        $classe = 'proprio';
    }

    echo "<div class='veiculo-card {$classe}'>";
    echo "<strong>{$modelo} - {$placa}</strong>";
    echo "<form>";
    echo "<input type='hidden' name='veiculo_id' value='{$veiculoId}'>";
    
    if (!$ocupado) {
        echo "<button type='submit' name='solicitar'>Solicitar</button>";
    } elseif ($ehUsuario) {
        echo "<button type='submit' name='devolver'>Devolver</button>";
    } else {
        echo "<button disabled>Indisponível</button>";
    }

    echo "</form></div>";
}

*/
