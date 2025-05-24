<?php
require_once __DIR__ . '/../models/Veiculo.php';
session_start();

$veiculos = Veiculo::listarDisponiveis();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Veículo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <h1>Selecione um veículo para solicitar:</h1>
    <div class="veiculos">
        <?php foreach ($veiculos as $veiculo): ?>
            <form method="POST" action="/controllers/VeiculoController.php">
                <input type="hidden" name="veiculo_id" value="<?= $veiculo['id'] ?>">
                <button class="card">
                    <?= htmlspecialchars($veiculo['modelo']) ?> - <?= htmlspecialchars($veiculo['placa']) ?>
                </button>
            </form>
        <?php endforeach; ?>
    </div>
</body>
</html>

