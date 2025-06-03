<?php
require_once __DIR__ . '/../config/config.php';
session_start();

// Redireciona se não estiver logado
$usuario_id = $_SESSION['usuario_id'] ?? null;
$token_sessao = $_SESSION['token_sessao'] ?? null;

if (!$usuario_id || !$token_sessao) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

// Verifica se o token no banco bate com a sessão atual
$stmt = $pdo->prepare("SELECT token_sessao FROM usuarios WHERE id = ?");
$stmt->execute([$usuario_id]);
$tokenAtual = $stmt->fetchColumn();

if ($tokenAtual !== $token_sessao) {
    session_destroy();
    header('Location: ' . BASE_URL . '/login?erro=sessao_encerrada');
    exit;
}

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

    <!-- Div onde a lista será carregada dinamicamente -->
    <div class="veiculos-lista">Carregando veículos...</div>

    <!-- Acesso Administrativo -->
    <form class="admin-form" method="POST">
        <input type="password" name="senha_admin" placeholder="Senha do administrador">
        <button type="submit" name="acesso_admin">Acesso Administrativo</button>
    </form>

    <?php if (isset($erro_admin)): ?>
        <p class="erro-senha"><?= $erro_admin ?></p>
    <?php endif; ?>
</div>

<!-- Script de controle AJAX -->
<script>
    const USUARIO_ID = <?= json_encode($usuario_id) ?>;
</script>
<script src="<?= BASE_URL ?>/js/painel.js"></script>

</body>
</html>
