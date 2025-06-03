<?php
require_once __DIR__ . '/../config/config.php';

$erros = [];
$sucesso = '';
$nome = $email = $usuario = ''; // Para preservar dados no form

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $usuario = trim($_POST['usuario']);
    $senha = $_POST['senha'];

    // Validações
    if (empty($nome)) $erros[] = "Nome é obrigatório.";
    if (empty($email)) {
        $erros[] = "Email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Formato de email inválido.";
    }
    if (empty($usuario)) $erros[] = "Usuário é obrigatório.";
    if (empty($senha)) $erros[] = "Senha é obrigatória.";

    if (count($erros) === 0) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email OR usuario = :usuario");
        $stmt->execute(['email' => $email, 'usuario' => $usuario]);
        $existe = $stmt->fetchColumn();

        if ($existe > 0) {
            $erros[] = "Email ou nome de usuário já estão em uso!";
        } else {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, usuario, senha) VALUES (:nome, :email, :usuario, :senha)");
            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'usuario' => $usuario,
                'senha' => $hash
            ]);
            $sucesso = "Usuário cadastrado com sucesso!";
            // Limpa os dados do formulário após sucesso
            $nome = $email = $usuario = '';
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
<body class="cadastro-bg">


<div class="cadastro-container">
    <h2>Cadastro de Usuário</h2>

    <?php if (!empty($erros)): ?>
        <div class="error">
            <?= implode('<br>', array_map('htmlspecialchars', $erros)) ?>
        </div>
    <?php endif; ?>

    <?php if ($sucesso): ?>
        <div class="success">
            <?= htmlspecialchars($sucesso) ?>
            <a href="<?= BASE_URL ?>/login">Clique aqui para logar</a>
        </div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="nome" placeholder="Nome completo" value="<?= htmlspecialchars($nome) ?>" required><br>
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>" required><br>
        <input type="text" name="usuario" placeholder="Usuário" value="<?= htmlspecialchars($usuario) ?>" required><br>
        <input type="password" name="senha" placeholder="Senha" required><br>
        <button type="submit">Cadastrar</button>
    </form>

    <a class="link-voltar" href="<?= BASE_URL ?>/login">Já tem conta? Fazer login</a>
</div>

</body>
</html>
