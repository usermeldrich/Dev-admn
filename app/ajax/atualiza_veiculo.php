<?php
require_once __DIR__ . '/../config/config.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Não autenticado']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$veiculo_id = $_POST['veiculo_id'];
$acao = $_POST['acao'];

if ($acao === 'solicitar') {
    // Verifica se o usuário já possui uma solicitação pendente
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM solicitacoes WHERE usuario_id = ? AND status = 'pendente'");
    $stmt->execute([$usuario_id]);
    if ($stmt->fetchColumn() == 0) {
        // Registra a nova solicitação
        $stmt = $pdo->prepare("INSERT INTO solicitacoes (usuario_id, veiculo_id, status) VALUES (?, ?, 'pendente')");
        $stmt->execute([$usuario_id, $veiculo_id]);
        echo json_encode(['sucesso' => true]);
    } else {
        echo json_encode(['erro' => 'Você já possui uma solicitação pendente.']);
    }
} elseif ($acao === 'devolver') {
    // Atualiza o status para devolvido
    $stmt = $pdo->prepare("UPDATE solicitacoes SET status = 'devolvido', data_devolucao = CURRENT_TIMESTAMP 
                           WHERE usuario_id = ? AND veiculo_id = ? AND status = 'pendente'");
    $stmt->execute([$usuario_id, $veiculo_id]);
    echo json_encode(['sucesso' => true]);
} else {
    echo json_encode(['erro' => 'Ação inválida.']);
}
