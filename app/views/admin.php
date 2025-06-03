<?php
require_once __DIR__ . '/../config/config.php';
session_start();

// Autenticação simples (senha foi verificada antes de chegar aqui)
$relatorio = [];

$stmt = $pdo->query("
    SELECT 
        u.nome AS usuario,
        v.modelo,
        v.placa,
        s.data_solicitacao,
        s.data_devolucao
    FROM solicitacoes s
    JOIN usuarios u ON s.usuario_id = u.id
    JOIN veiculos v ON s.veiculo_id = v.id
    ORDER BY s.data_solicitacao DESC
");
$relatorio = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatório Administrativo</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>
<body class="painel-bg">
    <div class="painel-container">
        <h2>Relatório de Solicitações</h2>
        <table class="relatorio-tabela">
            <thead>
                <tr>
                    <th>Usuário</th>
                    <th>Veículo</th>
                    <th>Placa</th>
                    <th>Data da Solicitação</th>
                    <th>Data da Devolução</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($relatorio as $linha): ?>
                <tr>
                    <td><?= htmlspecialchars($linha['usuario']) ?></td>
                    <td><?= htmlspecialchars($linha['modelo']) ?></td>
                    <td><?= htmlspecialchars($linha['placa']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($linha['data_solicitacao'])) ?></td>
                    <td>
                        <?= $linha['data_devolucao'] ? date('d/m/Y H:i', strtotime($linha['data_devolucao'])) : '<span class="pendente">Pendente</span>' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
