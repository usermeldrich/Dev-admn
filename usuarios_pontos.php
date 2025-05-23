<?php
require 'conexao.php';

// Consulta os usuários com seus pontos
$sql = "SELECT u.id, u.nome, COALESCE(SUM(p.valor), 0) as total_pontos
        FROM usuarios u
        LEFT JOIN pontos p ON u.id = p.usuario_id
        GROUP BY u.id, u.nome
        ORDER BY total_pontos DESC";

$stmt = $pdo->query($sql);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Usuários e Pontos</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 50%; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Lista de Usuários e Pontos</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Total de Pontos</th>
        </tr>
        <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?= htmlspecialchars($usuario['id']) ?></td>
            <td><?= htmlspecialchars($usuario['nome']) ?></td>
            <td><?= htmlspecialchars($usuario['total_pontos']) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
