<?php
require_once __DIR__ . '/../config/config.php';
session_start();

// Redireciona se não estiver logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

// Verifica ações de solicitar ou devolver veículo
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['solicitar'], $_POST['veiculo_id'])) {
        $veiculo_id = (int)$_POST['veiculo_id'];

        // Verifica se o usuário já tem uma solicitação pendente
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM solicitacoes WHERE usuario_id = ? AND status = 'pendente'");
        $stmt->execute([$usuario_id]);
        if ($stmt->fetchColumn() == 0) {
            // Insere nova solicitação
            $stmt = $pdo->prepare("INSERT INTO solicitacoes (usuario_id, veiculo_id, status) VALUES (?, ?, 'pendente')");
            $stmt->execute([$usuario_id, $veiculo_id]);
        }
    }

    if (isset($_POST['devolver'], $_POST['veiculo_id'])) {
        $veiculo_id = (int)$_POST['veiculo_id'];

        // Atualiza a solicitação para marcar como devolvida
        $stmt = $pdo->prepare("UPDATE solicitacoes SET status = 'devolvido', data_devolucao = CURRENT_TIMESTAMP 
                               WHERE usuario_id = ? AND veiculo_id = ? AND status = 'pendente'");
        $stmt->execute([$usuario_id, $veiculo_id]);
    }

    // Admin redireciona se a senha estiver correta
    if (isset($_POST['acesso_admin']) && isset($_POST['senha_admin'])) {
        if ($_POST['senha_admin'] === '123adm') {
            header('Location: ' . BASE_URL . '/admin');
            exit;
        } else {
            $erro_admin = "Senha incorreta!";
        }
    }
}

// Busca todos os veículos
$stmt = $pdo->query("SELECT * FROM veiculos ORDER BY modelo ASC");
$veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica quais veículos estão com solicitação pendente
$stmt = $pdo->query("SELECT veiculo_id, usuario_id FROM solicitacoes WHERE status = 'pendente'");
$ocupados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cria um mapa rápido para saber quem está com qual veículo
$veiculoOcupadoPor = [];
foreach ($ocupados as $s) {
    $veiculoOcupadoPor[$s['veiculo_id']] = $s['usuario_id'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel de Veículos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>
<body class="painel-bg">

<div class="painel-container">
    <h2>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h2>
    <h3>Lista de Veículos</h3>

    <div class="veiculos-lista">
        <?php foreach ($veiculos as $v): ?>
            <?php
                $ocupado = array_key_exists($v['id'], $veiculoOcupadoPor);
                $ehUsuario = $ocupado && $veiculoOcupadoPor[$v['id']] == $usuario_id;
            ?>
            <div class="veiculo-card <?= $ocupado && !$ehUsuario ? 'opaco' : ($ehUsuario ? 'proprio' : '') ?>">
                <strong><?= htmlspecialchars($v['modelo']) ?> - <?= htmlspecialchars($v['placa']) ?></strong>
                <form method="POST">
                    <input type="hidden" name="veiculo_id" value="<?= $v['id'] ?>">
                    <?php if (!$ocupado): ?>
                        <button type="submit" name="solicitar">Solicitar</button>
                    <?php elseif ($ehUsuario): ?>
                        <button type="submit" name="devolver">Devolver</button>
                    <?php else: ?>
                        <button disabled>Indisponível</button>
                    <?php endif; ?>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <form class="admin-form" method="POST">
        <input type="password" name="senha_admin" placeholder="Senha do administrador">
        <button type="submit" name="acesso_admin">Acesso Administrativo</button>
    </form>

    <?php if (isset($erro_admin)): ?>
        <p class="erro-senha"><?= $erro_admin ?></p>
    <?php endif; ?>
</div>

</body>
</html>
