<?php
require_once __DIR__ . '/../config/config.php';

$erro = '';
$sucesso = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $usuario = trim($_POST['usuario']);
    $senha = $_POST['senha'];

    if (empty($nome) || empty($email) || empty($usuario) || empty($senha)) {
        $erro = "Todos os campos são obrigatórios!";
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email OR usuario = :usuario");
        $stmt->execute(['email' => $email, 'usuario' => $usuario]);
        $existe = $stmt->fetchColumn();

        if ($existe > 0) {
            $erro = "Email ou nome de usuário já estão em uso!";
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, usuario, senha) VALUES (:nome, :email, :usuario, :senha)");
            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'usuario' => $usuario,
                'senha' => $hash
            ]);
            $sucesso = "Usuário cadastrado com sucesso! <a href='" . BASE_URL . "/login'>Clique aqui para logar</a>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>
<body>

<div class="cadastro-container">
    <h2>Cadastro de Usuário</h2>

    <?php if ($erro): ?>
        <div class="error"><?= $erro ?></div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <div class="success"><?= $sucesso ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="nome" placeholder="Nome completo" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="text" name="usuario" placeholder="Usuário" required><br>
        <input type="password" name="senha" placeholder="Senha" required><br>
        <button type="submit">Cadastrar</button>
    </form>

    <a class="link-voltar" href="<?= BASE_URL ?>/login">Já tem conta? Fazer login</a>
</div>

</body>
</html>
