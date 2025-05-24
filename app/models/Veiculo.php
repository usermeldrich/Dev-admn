<?php
require_once __DIR__ . '/../config/config.php';

class Veiculo {
    public static function listarDisponiveis() {
        global $pdo;
        $sql = "SELECT * FROM veiculos WHERE disponivel = TRUE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function solicitar($usuario_id, $veiculo_id) {
        global $pdo;

        // Inserir na tabela solicitacoes
        $sql = "INSERT INTO solicitacoes (usuario_id, veiculo_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$usuario_id, $veiculo_id]);

        // Atualizar veiculo para indisponível
        $sql = "UPDATE veiculos SET disponivel = FALSE WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$veiculo_id]);
    }
}
?>
