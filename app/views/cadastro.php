<?php
require_once __DIR__ . '/../config/config.php';

$erro = '';
$sucesso = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $usuario = trim($_POST['usuario']);
    $senha = $_POST['senha'];

    // Verifica campos obrigatórios
    if (empty($nome) || empty($email) || empty($usuario) || empty($senha)) {
        $erro = "Todos os campos são obrigatórios!";
    } else {
        // Verifica se email ou usuário já existem
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = :email OR usuario = :usuario");
        $stmt->execute(['email' => $email, 'usuario' => $usuario]);
        $existe = $stmt->fetchColumn();

        if ($existe > 0) {
            $erro = "Email ou nome de usuário já estão em uso!";
        } else {
            // Insere novo usuário
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, usuario, senha) VALUES (:nome, :email, :usuario, :senha)");
            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'usuario' => $usuario,
                'senha' => $hash
            ]);
            $sucesso = "Usuário cadastrado com sucesso! <a href='/Dev-admn/public'>Clique aqui para logar</a>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <style>
        body {
            background-color: #f7f9fc;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .cadastro-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #28a745;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #218838;
        }

        .error, .success {
            margin-top: 10px;
            padding: 10px;
            border-radius: 6px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .link-voltar {
            display: block;
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
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

    <a class="link-voltar" href="/Dev-admn/public">Já tem conta? Fazer login</a>
</div>

</body>
</html>
