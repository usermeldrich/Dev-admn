<?php
require_once __DIR__ . '/../config/config.php';

// Processamento do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    $identificador = $_POST['identificador'];
    $senha = $_POST['senha'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE (email = :id OR usuario = :id)");
    $stmt->bindParam(':id', $identificador);
    $stmt->execute();

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        // Inicia sessão
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_usuario'] = $usuario['usuario'];
        $_SESSION['usuario_email'] = $usuario['email'];

        header('Location: ' . BASE_URL . '/painel');
        exit;
    } else {
        $erro = "Usuário/email ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>
<body>

<div class="login-container">
    <h2>Login</h2>

    <?php if (isset($erro)) : ?>
        <div class="error"><?php echo $erro; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="identificador" placeholder="Email ou Usuário" required><br>
        <input type="password" name="senha" placeholder="Senha" required><br>
        <button type="submit">Entrar</button>
    </form>

    <a class="link-cadastro" href="<?= BASE_URL ?>/cadastro">Primeira Vez Aqui? Cadastre-se</a>
</div>

</body>
</html>
