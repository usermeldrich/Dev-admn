<?php
require_once __DIR__ . '/../models/Veiculo.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: /login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['veiculo_id'])) {
    Veiculo::solicitar($_SESSION['usuario_id'], $_POST['veiculo_id']);
    header('Location: /painel');
    exit;
}
?>
