<?php
require_once __DIR__ . '/../config/config.php';
session_start();

// Redireciona se não estiver logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

// Verifica se usuário solicitou ou devolveu
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario_id = $_SESSION['usuario_id'];
    $veiculo_id = $_POST['veiculo_id'];

    if (isset($_POST['solicitar'])) {
        // Garante que o usuário não tem outro veículo
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM veiculos WHERE status = 'solicitado' AND usuario_id = ?");
        $stmt->execute([$usuario_id]);
        if ($stmt->fetchColumn() == 0) {
            $stmt = $pdo->prepare("UPDATE veiculos SET status = 'solicitado', usuario_id = ? WHERE id = ?");
            $stmt->execute([$usuario_id, $veiculo_id]);
        }
    } elseif (isset($_POST['devolver'])) {
        $stmt = $pdo->prepare("UPDATE veiculos SET status = 'disponivel', usuario_id = NULL WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$veiculo_id, $usuario_id]);
    }
}

// Pega lista de veículos
$stmt = $pdo->query("SELECT * FROM veiculos ORDER BY reserva ASC");
$veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <h2>Bem-vindo, <?= $_SESSION['usuario_nome'] ?>!</h2>
        <h3>Lista de Veículos</h3>

        <div class="veiculos-lista">
            <?php foreach ($veiculos as $v): ?>
                <?php
                    $ocupado = $v['status'] === 'solicitado';
                    $ehUsuario = $v['usuario_id'] == $_SESSION['usuario_id'];
                ?>
                <div class="veiculo-card <?= $ocupado && !$ehUsuario ? 'opaco' : '' ?>">
                    <strong><?= $v['nome'] ?></strong>
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

        <?php
        if (isset($_POST['acesso_admin'])) {
            $senha = $_POST['senha_admin'];
            if ($senha === '123adm') { // senha fixa no código
                header('Location: ' . BASE_URL . '/admin.php');
                exit;
            } else {
                echo '<p class="erro-senha">Senha incorreta!</p>';
            }
        }
        ?>
    </div>

</body>
</html>
